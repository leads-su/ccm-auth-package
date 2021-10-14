<?php namespace ConsulConfigManager\Auth\Domain\UseCases\User;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface UserInputPort
 * @package ConsulConfigManager\Auth\Domain\UseCases\User
 */
interface UserInputPort {

    /**
     * Retrieve information about authenticated user
     *
     * @param UserRequestModel $userRequestModel
     * @return ViewModel
     */
    public function user(UserRequestModel $userRequestModel): ViewModel;

}
