<?php

namespace ConsulConfigManager\Auth\UseCases\Authenticate;

use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class AuthenticateResponseModel
 *
 * @package ConsulConfigManager\Auth\UseCases\Authenticate
 */
class AuthenticateResponseModel
{
    /**
     * User model instance
     * @var UserInterface|null
     */
    private ?UserInterface $userEntity;

    /**
     * AuthenticateResponseModel Constructor.
     *
     * @param UserInterface|null $userEntity
     */
    public function __construct(?UserInterface $userEntity = null)
    {
        $this->userEntity = $userEntity;
    }

    /**
     * Get user
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->userEntity;
    }
}
