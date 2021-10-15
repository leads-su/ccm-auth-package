<?php namespace ConsulConfigManager\Auth\Domain\UseCases\Logout;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface LogoutOutputPort
 * @package ConsulConfigManager\Auth\Domain\UseCases\Logout
 */
interface LogoutOutputPort {

    /**
     * Output port for "successfully logged out" status
     * @param LogoutResponseModel $logoutResponseModel
     * @return ViewModel
     */
    public function successfullyLoggedOut(LogoutResponseModel $logoutResponseModel): ViewModel;

    /**
     * Output port for "failed to log out" status
     * @param LogoutResponseModel $logoutResponseModel
     * @param Throwable $exception
     * @throws Throwable
     * @return ViewModel
     */
    public function failedToLogOut(LogoutResponseModel $logoutResponseModel, Throwable $exception): ViewModel;

    /**
     * Output port for "not authorized" status
     * @param LogoutResponseModel $logoutResponseModel
     * @return ViewModel
     */
    public function notAuthorized(LogoutResponseModel $logoutResponseModel): ViewModel;

}
