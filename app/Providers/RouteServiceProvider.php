<?php

namespace App\Providers;

use App\Http\Middleware\Api\AuthenticateAdminApi;
use App\Services\PxCommandService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Load routes/api-admin/** (mirror of routes/admin/**) as /api/v1/admin/*
     * behind the admin_api token guard. Route names get the api.v1. prefix
     * so they can never clash with the web route names.
     */
    private function appendApiAdminRoutes(): void
    {
        $dir = base_path('routes/api-admin');
        if (!is_dir($dir)) {
            return;
        }
        $files = iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)));
        ksort($files);
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                Route::group([
                    'middleware' => ['api', AuthenticateAdminApi::class],
                    'prefix' => 'api/v1',
                    'as' => 'api.v1.',
                ], $file->getPathname());
            }
        }
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        //get the service class for commands
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        $this->routes(function () {
            $pxCommandService = app(PxCommandService::class);
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            Route::middleware('web')->group(base_path('routes/web.php'));
            //vpx_append_routes
            $pxCommandService->appendRoutes("routes/admin");
            $this->appendApiAdminRoutes();
        });
    }
}
