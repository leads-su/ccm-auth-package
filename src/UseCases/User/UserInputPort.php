<?php

namespace ConsulConfigManager\Auth\UseCases\User;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface UserInputPort
 * @package ConsulConfigManager\Auth\UseCases\User
 */
interface UserInputPort
{
    /**
     * Retrieve information about authenticated user
     *
     * @param UserRequestModel $userRequestModel
     * @return ViewModel
     */
    public function user(UserRequestModel $userRequestModel): ViewModel;
}
