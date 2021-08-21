<?php namespace ConsulConfigManager\Auth\Domain\UseCases\Authenticate;

use ConsulConfigManager\Auth\Http\Requests\AuthRequest;

/**
 * Class AuthenticateRequestModel
 *
 * @package ConsulConfigManager\Auth\Domain\UseCases\Authenticate
 */
class AuthenticateRequestModel {

    /**
     * Authentication request instance
     * @var AuthRequest
     */
    private AuthRequest $authenticationRequest;

    /**
     * AuthenticateRequestModel Constructor.
     *
     * @param AuthRequest $request
     */
    public function __construct(AuthRequest $request) {
        $this->authenticationRequest = $request;
    }

    /**
     * Get authentication request instance
     * @return AuthRequest
     */
    public function getRequest(): AuthRequest {
        return $this->authenticationRequest;
    }

    /**
     * Get login from authentication request
     * @return string|null
     */
    public function getLogin(): ?string {
        return $this->authenticationRequest->get('emailOrUsername');
    }

    /**
     * Get password from authentication request
     * @return string|null
     */
    public function getPassword(): ?string {
        return $this->authenticationRequest->get('password');
    }

}