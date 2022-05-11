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
     * User agent string
     * @var string|null
     */
    private ?string $userAgent;

    /**
     * User model instance
     * @var UserInterface|null
     */
    private ?UserInterface $userEntity;

    /**
     * AuthenticateResponseModel Constructor.
     *
     * @param string|null $userAgent
     * @param UserInterface|null $userEntity
     */
    public function __construct(?string $userAgent = null, ?UserInterface $userEntity = null)
    {
        $this->userAgent = $userAgent;
        $this->userEntity = $userEntity;
    }

    /**
     * Get user agent
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent === null ? '' : $this->userAgent;
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
