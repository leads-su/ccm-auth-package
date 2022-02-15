<?php

namespace ConsulConfigManager\Auth\UseCases\User;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Class UserInteractor
 * @package ConsulConfigManager\Auth\UseCases\User
 */
class UserInteractor implements UserInputPort
{
    /**
     * Output port instance
     * @var UserOutputPort
     */
    private UserOutputPort $output;

    /**
     * UserInteractor constructor.
     * @param UserOutputPort $output
     * @return void
     */
    public function __construct(UserOutputPort $output)
    {
        $this->output = $output;
    }

    /**
     * @inheritDoc
     */
    public function user(UserRequestModel $userRequestModel): ViewModel
    {
        $user = $userRequestModel->getUser();
        if ($user === null) {
            return $this->output->userNotAuthenticated(new UserResponseModel());
        }
        return $this->output->userDetails(new UserResponseModel($user));
    }
}
