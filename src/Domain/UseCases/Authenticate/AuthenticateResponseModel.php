<?php namespace ConsulConfigManager\Auth\Domain\UseCases\Authenticate;

use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class AuthenticateResponseModel
 *
 * @package ConsulConfigManager\Auth\Domain\UseCases\Authenticate
 */
class AuthenticateResponseModel {

    /**
     * User model instance
     * @var UserEntity|null
     */
    private ?UserEntity $userEntity;

    /**
     * AuthenticateResponseModel Constructor.
     *
     * @param UserEntity|null $userEntity
     */
    public function __construct(?UserEntity $userEntity = null) {
        $this->userEntity = $userEntity;
    }

    /**
     * Get user
     * @return UserEntity|null
     */
    public function getUser(): ?UserEntity {
        return $this->userEntity;
    }

}