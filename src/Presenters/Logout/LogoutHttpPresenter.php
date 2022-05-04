<?php

namespace ConsulConfigManager\Auth\Presenters\Logout;

use Throwable;
use Illuminate\Http\Response;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Auth\UseCases\Logout\LogoutOutputPort;
use ConsulConfigManager\Domain\ViewModels\HttpResponseViewModel;
use ConsulConfigManager\Auth\UseCases\Logout\LogoutResponseModel;

/**
 * Class LogoutHttpPresenter
 * @package ConsulConfigManager\Auth\Presenters\Logout
 */
class LogoutHttpPresenter implements LogoutOutputPort
{
    /**
     * @inheritDoc
     */
    public function successfullyLoggedOut(LogoutResponseModel $logoutResponseModel): ViewModel
    {
        return new HttpResponseViewModel(
            response_success(
                [],
                'Successfully logged user out',
                Response::HTTP_OK
            )
        );
    }

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function failedToLogOut(LogoutResponseModel $logoutResponseModel, Throwable $exception): ViewModel
    {
        if (config('app.debug')) {
            throw $exception;
        }
        return new HttpResponseViewModel(
            response_error(
                $exception,
                'Unable to log user out'
            )
        );
    }
    // @codeCoverageIgnoreEnd

    // @codeCoverageIgnoreStart
    /**
     * @inheritDoc
     */
    public function notAuthorized(LogoutResponseModel $logoutResponseModel): ViewModel
    {
        return new HttpResponseViewModel(response_error(
            [],
            'Not authenticated to access this route',
            Response::HTTP_UNAUTHORIZED
        ));
    }
    // @codeCoverageIgnoreEnd
}
