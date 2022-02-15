<?php

namespace ConsulConfigManager\Auth\UseCases\User;

use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class UserResponseModel
 * @package ConsulConfigManager\Auth\UseCases\User
 */
class UserResponseModel
{
    /**
     * User entity instance
     * @var UserInterface|null
     */
    private ?UserInterface $userEntity;

    /**
     * UserResponseModel constructor.
     * @param UserInterface|null $userEntity
     * @return void
     */
    public function __construct(?UserInterface $userEntity = null)
    {
        $this->userEntity = $userEntity;
    }

    /**
     * Get user entity instance
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->userEntity;
    }
}
