<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'seg.credenciales';
    protected $primaryKey = 'iCredId';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'cCredUsuario', 'iTipoCredId', 'iPersId',
        'dtCredRegistro', 'dtCredUltimaSesion', 'cCredToken', 'iCredIntentos',
        'iCredEstado', 'cCredKey', 'cRol', 'cCredUsuarioSis',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'dtCredFechaSis', 'cCredEquipoSis', 'cCredIpSis',
        'cCredOpenUsr', 'cCredMacNicSis',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'dev'  => 'sigeuxxxxn' // my custom claim, add as many as you like
        ];
    }

    public function grlPersona()
    {
        return $this->belongsTo('App\GrlPersona', 'iPersId');
    }

    public function estudiante()
    {
        return $this->hasMany('App\UraEstudiante', 'iPersId', 'iPersId')->orderByDesc('iEstudId');
    }

    public function segCredencialesPerfiles()
    {
        return $this->hasMany('App\SegCredencialPerfil', 'iCredId');
    }

    /* public function getAttributecRol(){
         return trim($this.cRol);
     } */
}
