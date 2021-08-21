<?php namespace ConsulConfigManager\Auth\Domain\Presenters\Authenticate;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateOutputPort;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateResponseModel;

/**
 * Class AuthenticateHttpPresenter
 *
 * @package ConsulConfigManager\Auth\Domain\Presenters\Authenticate
 */
class AuthenticateHttpPresenter implements AuthenticateOutputPort {

    /**
     * @inheritDoc
     */
    public function userAuthenticated(AuthenticateResponseModel $authenticateResponseModel): ViewModel {
        return new HttpResponseViewModel(
            response_success(
                $authenticateResponseModel->getUser(),
                'Successfully authenticated user',
                Response::HTTP_OK
            )
        );

    }

    /**
     * @inheritDoc
     */
    public function invalidCredentials(AuthenticateResponseModel $authenticateResponseModel): ViewModel {
        return new HttpResponseViewModel(
            response_error(
                [],
                'Invalid credentials specified',
                Response::HTTP_UNAUTHORIZED
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function unableToAuthenticateUser(AuthenticateResponseModel $authenticateResponseModel, Throwable $exception): ViewModel {
        if (config('app.debug')) {
            throw $exception;
        }
        return new HttpResponseViewModel(
            response_error(
                $exception,
                'Unable to authenticate user'
            )
        );

    }

}