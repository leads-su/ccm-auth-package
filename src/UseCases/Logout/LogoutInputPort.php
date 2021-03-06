<?php

namespace ConsulConfigManager\Auth\UseCases\Logout;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface LogoutInputPort
 * @package ConsulConfigManager\Auth\UseCases\Logout
 */
interface LogoutInputPort
{
    /**
     * Logout currently authenticated user
     * @param LogoutRequestModel $logoutRequestModel
     * @throws Throwable
     * @return ViewModel
     */
    public function logout(LogoutRequestModel $logoutRequestModel): ViewModel;
}
