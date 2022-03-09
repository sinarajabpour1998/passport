<?php

namespace SRA\Passport;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use SRA\Passport\Facades\PassportFacade;
use SRA\Passport\Http\Middleware\ValidateToken;
use SRA\Passport\Repositories\PassportRepository;

class PassportServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        PassportFacade::shouldProxyTo(PassportRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
     public function boot()
     {
         $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
         $this->loadViewsFrom(__DIR__ . '/views','passport');
         $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
         $this->mergeConfigFrom(__DIR__ . '/config/passport.php', 'passport');

         $router = $this->app->make(Router::class);
         $router->aliasMiddleware('has.token', ValidateToken::class);
//         $this->loadViewComponentsAs('', [
//             //
//         ]);
     }
}
