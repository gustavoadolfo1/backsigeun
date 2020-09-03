<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapApiTramiteRoutes();
        $this->mapApiGeneralesRoutes();
        $this->mapApiRecursosHumanosRoutes();
        $this->mapApiRoutesTre();
        $this->mapApiDocenteRoutes();
        $this->mapApiAulaVirtualRoutes();
        $this->mapApiDBURoutes();
        $this->mapApiBibliotecaRoutes();
        $this->mapApiSegRoutes();
        $this->mapApiCCTiCRoutes();
        $this->mapApiLogisticaRoutes();
        $this->mapApiDasaRoutes();
        $this->mapApiCeidRoutes();
        $this->mapApiRoutesPat(); //patrimnonio
        $this->mapApiInvestigacionRoutes();
        $this->mapApiCepreRoutes();
        $this->mapApiAdmisionRoutes();
        $this->mapApiTesoreriaRoutes();
        $this->mapApiConveniosRoutes();
        $this->mapApiUsegresadoRoutes();
        $this->mapApiCCURoutes();
        //
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiTramiteRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            //->middleware(['api', 'auth:api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/tramite/api.php'));
    }

    protected function mapApiGeneralesRoutes()
    {
        Route::prefix('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/grl/api.php'));
    }

    protected function mapApiRecursosHumanosRoutes()
    {
        Route::prefix('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/rrhh/api.php'));
    }

    protected function mapApiRoutesTre()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api_tre.php'));
    }
    //protected $namespaceDocente = 'App\Http\Controllers\Docente';

    /**
     * [mapApiDocenteRoutes routas para docente]
     *
     * @return  [type]  [return route]
     */
    protected function mapApiDocenteRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/docente/route.php'));
    }

    protected function mapApiAulaVirtualRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/aulaVirtual/api.php'));
    }


    protected function mapApiDBURoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/dbu/route.php'));
    }

    protected function mapApiBibliotecaRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/biblioteca/route.php'));
    }

    protected function mapApiSegRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/seg/api.php'));
    }

    protected function mapApiCCTiCRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/cctic/api.php'));
    }

    protected function mapApiLogisticaRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/logistica/api.php'));
    }
    protected function mapApiCCURoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/ccu/api.php'));
    }

    protected function mapApiDasaRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/dasa/api.php'));
    }

    public function mapApiCeidRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/ceid/api.php'));
    }

    protected function mapApiRoutesPat()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/patrimonio/api.php'));
    }

    protected function mapApiInvestigacionRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            //->middleware(['api', 'auth:api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/investigacion/api.php'));
    }
    protected function mapApiCepreRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/cepre/api.php'));
    }
    protected function mapApiAdmisionRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/admision/api.php'));
    }
    protected function mapApiTesoreriaRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/tesoreria/api.php'));
    }
    protected function mapApiUsegresadoRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->prefix('api')
            ->group(base_path('routes/usegresado/route.php'));
    }
    protected function mapApiConveniosRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            //->middleware(['api', 'auth:api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/convenios/api.php'));
    }
}
