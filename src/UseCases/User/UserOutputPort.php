<?php

namespace ConsulConfigManager\Auth\UseCases\User;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface UserOutputPort
 * @package ConsulConfigManager\Auth\UseCases\User
 */
interface UserOutputPort
{
    /**
     * Output port for "user details" status
     * @param UserResponseModel $userResponseModel
     * @return ViewModel
     */
    public function userDetails(UserResponseModel $userResponseModel): ViewModel;

    /**
     * Output port for "user not authenticated" status
     * @param UserResponseModel $userResponseModel
     * @return ViewModel
     */
    public function userNotAuthenticated(UserResponseModel $userResponseModel): ViewModel;
}
