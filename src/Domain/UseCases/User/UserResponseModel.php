<?php namespace ConsulConfigManager\Auth\Domain\UseCases\User;

use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class UserResponseModel
 * @package ConsulConfigManager\Auth\Domain\UseCases\User
 */
class UserResponseModel {

    /**
     * User entity instance
     * @var UserEntity|null
     */
    private ?UserEntity $userEntity;

    /**
     * UserResponseModel constructor.
     * @param UserEntity|null $userEntity
     * @return void
     */
    public function __construct(?UserEntity $userEntity = null) {
        $this->userEntity = $userEntity;
    }

    /**
     * Get user entity instance
     * @return UserEntity|null
     */
    public function getUser(): ?UserEntity {
        return $this->userEntity;
    }

}
