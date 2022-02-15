<?php

namespace ConsulConfigManager\Auth\UseCases\Authenticate;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface AuthenticateInputPort
 *
 * @package ConsulConfigManager\Auth\UseCases\Authenticate
 */
interface AuthenticateInputPort
{
    /**
     * Authenticate user using provided credentials
     * @param AuthenticateRequestModel $authenticateRequestModel
     *
     * @return ViewModel
     */
    public function authenticate(AuthenticateRequestModel $authenticateRequestModel): ViewModel;
}
