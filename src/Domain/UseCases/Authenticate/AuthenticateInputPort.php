<?php namespace ConsulConfigManager\Auth\Domain\UseCases\Authenticate;

use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Interface AuthenticateInputPort
 *
 * @package ConsulConfigManager\Auth\Domain\UseCases\Authenticate
 */
interface AuthenticateInputPort {

    /**
     * Authenticate user using provided credentials
     * @param AuthenticateRequestModel $authenticateRequestModel
     *
     * @return ViewModel
     */
    public function authenticate(AuthenticateRequestModel $authenticateRequestModel): ViewModel;

}