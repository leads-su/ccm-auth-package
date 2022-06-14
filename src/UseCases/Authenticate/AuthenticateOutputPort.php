<?php

namespace ConsulConfigManager\Auth\UseCases\Authenticate;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface AuthenticateOutputPort
 *
 * @package ConsulConfigManager\Auth\UseCases\Authenticate
 */
interface AuthenticateOutputPort
{
    /**
     * Output port for "user authenticated" status
     * @param AuthenticateResponseModel $responseModel
     *
     * @return ViewModel
     */
    public function userAuthenticated(AuthenticateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "invalid credentials" status
     * @param AuthenticateResponseModel $responseModel
     *
     * @return ViewModel
     */
    public function invalidCredentials(AuthenticateResponseModel $responseModel): ViewModel;

    /**
     * Output port for "unable to authenticate user" status
     * @param AuthenticateResponseModel $responseModel
     * @param Throwable                 $exception
     *
     * @return ViewModel
     */
    public function unableToAuthenticateUser(AuthenticateResponseModel $responseModel, Throwable $exception): ViewModel;
}
