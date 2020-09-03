<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\UraEstudiante;
use App\UraControlCicloAcademico;
use App\SegCredencialPerfil;

class SegCredencialController extends Controller
{
    /**
     * Obtiene la información de seguridad del credencial.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerInfoCredencial()
    {
        $credencial = auth()->user();
        $credencial->load('grlPersona', 'segCredencialesPerfiles.segPerfil.segPerfilesModulos.segModulo',
        'segCredencialesPerfiles.segCredencialesPerfilesFilialesCarreras.grlFilial',
        'segCredencialesPerfiles.segCredencialesPerfilesFilialesCarreras.uraCarrera',
        'segCredencialesPerfiles.segCredencialesPerfilesSubmodulos.segRangoSubmodulo',
        'segCredencialesPerfiles.segCredencialesPerfilesSubmodulos.segSubmodulo');

        foreach ($credencial->segCredencialesPerfiles as $i => $credencialPerfil) {
            
            foreach ($credencialPerfil->segPerfil->segPerfilesModulos as $j => $perfilModulo) {
                $subModulos = [];
                foreach ($credencialPerfil->segCredencialesPerfilesSubmodulos as $credencialPerfilSubmodulo) {
                    if ($perfilModulo->iModuloId == $credencialPerfilSubmodulo->segSubmodulo->iModuloId) {
                        $subModulos[] = $credencialPerfilSubmodulo->segSubmodulo;
                    }
                }
                $credencial->segCredencialesPerfiles[$i]->segPerfil->segPerfilesModulos[$j]->subModulos = $subModulos;
            }

            // Comprueba si el perfil es de estudiante
            if ($credencialPerfil->iPerfilId == 1) {
                $credencial->estudiante = UraEstudiante::select('ura.estudiantes.*', 'ura.carreras.cCarreraDsc', 'ura.modalidades.cModalDsc', 'ura.clasificacion.cClasificDsc')->join('ura.carreras', 'ura.carreras.iCarreraId', '=', 'ura.estudiantes.iCarreraId')->join('ura.modalidades', 'ura.modalidades.cModalidadCod', '=', 'ura.estudiantes.cModalidadCod')->join('ura.clasificacion', 'ura.clasificacion.iClasificId', '=', 'ura.estudiantes.iClasificId')->where('iPersId', $credencial->grlPersona->iPersId)->orderBy('ura.estudiantes.iEstudId', 'desc')->get();
            }
        }

        $credencial->cicloVigente = UraControlCicloAcademico::where('iControlEstado', 1)->first();
        $credencial->cicloVigente->cicloFormateado = implode('-', str_split($credencial->cicloVigente->iControlCicloAcad, 4));

        /*$modulos = [];
        foreach ($credencial->segCredencialesPerfiles as $i => $credencialPerfil) {
            foreach ($credencialPerfil->segPerfil->segPerfilesModulos as $j => $perfilModulo) {
                $modulos[] = $perfilModulo->segModulo;
            }
        }

        $credencial->modulos = array_unique($modulos);*/

        return response()->json( $credencial );
    }

    public function verificarLogueo($moduloCod)
    {
        try {
            $credencial = auth()->user();

            $modulos = \DB::select('exec [seg].[Sp_SEL_modulos_credencial] ?', array( $credencial->cCredUsuario ));

            foreach ($modulos as $modulo) {
                if ($modulo->cModuloCodigo == $moduloCod || $modulo->iModuloId == $moduloCod) {
                    $response = [ 'success' => true, 'access' => true ];
                    break;
                }
                else {
                    $response = [ 'success' => true, 'access' => false ];
                }
            }

        } catch (\Exception $th) {
            $response = [ 'success' => false, 'access' => false, 'throw' => $th ];
        }

        return response()->json( $response );
        
    }

    public function cambiarPassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'oldPassword' => 'required|min:6',
                'newPassword' => 'required|min:6',
                'newPasswordAgain' => 'required|min:6|same:newPassword'
            ],
            [
                'oldPassword.required' => 'Debe especificar su contraseña actual',
                'newPassword.required' => 'Nueva contraseña es obligatorio',
                'newPassword.min' => 'La Nueva contraseña debe ser de al menos :min caracteres',
                'newPasswordAgain.required' => 'Debe volver a escribir la nueva contraseña',
                'newPasswordAgain.min' => 'Las contraseñas no coinciden.',
                'newPasswordAgain.same' => 'Las contraseñas no coinciden.',
            ]
        );

        try {
            $queryResult = \DB::select('exec seg.Sp_UPD_CambiarClave_credencialesXiCredIdXcClaveAnteriorXcClaveNueva ?, ?, ?, ?, ?, ?, ?', [ auth()->user()->iCredId, $request->oldPassword, $request->newPassword, auth()->user()->iCredId, 'equipo', $request->server->get('REMOTE_ADDR'), 'mac' ]);
            $queryResult = ['validated' => true, 'mensaje' => 'Se ha cambiado su contraseña exitosamente.', 'queryResult' => $queryResult[0] ];

            $codeResponse = 200;
            
        } catch (\Exception $e) {
            $queryResult = ['validated' => true, 'mensaje' => substr($e->errorInfo[2] ?? '', 54), 'exception' => $e];
            $codeResponse = 500;
        }

        return response()->json( $queryResult, $codeResponse );
    
    }
}
