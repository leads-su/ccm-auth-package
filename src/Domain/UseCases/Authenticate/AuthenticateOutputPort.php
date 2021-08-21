<?php namespace ConsulConfigManager\Auth\Domain\UseCases\Authenticate;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface AuthenticateOutputPort
 *
 * @package ConsulConfigManager\Auth\Domain\UseCases\Authenticate
 */
interface AuthenticateOutputPort {

    /**
     * Output port for "user authenticated" status
     * @param AuthenticateResponseModel $authenticateResponseModel
     *
     * @return ViewModel
     */
    public function userAuthenticated(AuthenticateResponseModel $authenticateResponseModel): ViewModel;

    /**
     * Output port for "invalid credentials" status
     * @param AuthenticateResponseModel $authenticateResponseModel
     *
     * @return ViewModel
     */
    public function invalidCredentials(AuthenticateResponseModel $authenticateResponseModel): ViewModel;

    /**
     * Output port for "unable to authenticate user" status
     * @param AuthenticateResponseModel $authenticateResponseModel
     * @param Throwable                 $exception
     *
     * @return ViewModel
     */
    public function unableToAuthenticateUser(AuthenticateResponseModel $authenticateResponseModel, Throwable $exception): ViewModel;

}