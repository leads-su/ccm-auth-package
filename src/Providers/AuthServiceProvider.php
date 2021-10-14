<?php namespace ConsulConfigManager\Auth\Providers;

use Illuminate\Support\Facades\Route;
use ConsulConfigManager\Auth\AuthDomain;
use Laravel\Sanctum\SanctumServiceProvider;
use ConsulConfigManager\Domain\DomainServiceProvider;
use ConsulConfigManager\Auth\Http\Controllers\UserController;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserInputPort;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserInteractor;
use ConsulConfigManager\Auth\Http\Controllers\AuthenticateController;
use ConsulConfigManager\Auth\Domain\Presenters\User\UserHttpPresenter;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateInputPort;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateInteractor;
use ConsulConfigManager\Auth\Domain\Presenters\Authenticate\AuthenticateHttpPresenter;

/**
 * Class AuthServiceProvider
 *
 * @package ConsulConfigManager\Auth\Providers
 */
class AuthServiceProvider extends DomainServiceProvider {

    /**
     * @inheritDoc
     */
    public function boot(): void {
        $this->registerRoutes();
        $this->offerPublishing();
    }

    /**
     * @inheritDoc
     */
    public function register(): void {
        $this->app->register(SanctumServiceProvider::class);
        $this->registerConfiguration();
        parent::register();
    }

    /**
     * Register package routes
     * @return void
     */
    protected function registerRoutes(): void {
        if (AuthDomain::shouldRegisterRoutes()) {
            Route::group([
                'prefix'        =>  config('domain.auth.prefix'),
                'middleware'    =>  config('domain.auth.middleware'),
            ], function(): void {
                $this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
            });
        }
    }

    /**
     * Register package configuration
     * @return void
     */
    protected function registerConfiguration(): void {
        $this->mergeConfigFrom(__DIR__ . '/../../config/auth.php', 'domain.auth');
    }

    /**
     * Offer resources for publishing
     * @return void
     */
    protected function offerPublishing(): void {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/auth.php'     =>  config_path('domain/auth.php')
            ], 'ccm-auth-config');
        }
    }

    /**
     * @inheritDoc
     */
    protected function registerInterceptors(): void {
        $this->registerInterceptorFromParameters(
            AuthenticateInputPort::class,
            AuthenticateInteractor::class,
            AuthenticateController::class,
            AuthenticateHttpPresenter::class,
        );

        $this->registerInterceptorFromParameters(
            UserInputPort::class,
            UserInteractor::class,
            UserController::class,
            UserHttpPresenter::class,
        );
    }

}
