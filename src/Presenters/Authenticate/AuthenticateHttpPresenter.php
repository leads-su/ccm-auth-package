<?php

namespace ConsulConfigManager\Auth\Presenters\Authenticate;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Auth\Services\TokenNameGenerator;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Auth\UseCases\Authenticate\AuthenticateOutputPort;
use ConsulConfigManager\Auth\UseCases\Authenticate\AuthenticateResponseModel;

/**
 * Class AuthenticateHttpPresenter
 *
 * @package ConsulConfigManager\Auth\Presenters\Authenticate
 */
class AuthenticateHttpPresenter implements AuthenticateOutputPort
{
    /**
     * @inheritDoc
     */
    public function userAuthenticated(AuthenticateResponseModel $responseModel): ViewModel
    {
        $user = $responseModel->getUser();
        return new HttpResponseViewModel(
            response_success(
                [
                    'user'          =>  $user,
                    'token'         =>  [
                        'type'      =>  'Bearer',
                        'expires'   =>  now()->diffInSeconds(now()->addMinutes(config('sanctum.expiration'))->subMinutes(50)),
                        'token'     =>  $user->createToken(TokenNameGenerator::from($responseModel->getUserAgent())->toJson())->plainTextToken,
                    ],
                ],
                'Successfully authenticated user',
                Response::HTTP_OK
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function invalidCredentials(AuthenticateResponseModel $responseModel): ViewModel
    {
        return new HttpResponseViewModel(
            response_error(
                [],
                'Invalid credentials specified',
                Response::HTTP_UNAUTHORIZED
            )
        );
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function unableToAuthenticateUser(AuthenticateResponseModel $responseModel, Throwable $exception): ViewModel
    {
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
    // @codeCoverageIgnoreEnd
}
