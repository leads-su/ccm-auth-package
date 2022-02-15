<?php

namespace ConsulConfigManager\Auth\UseCases\Authenticate;

use Throwable;
use ConsulConfigManager\Auth\Services\Authenticator;
use ConsulConfigManager\Domain\Interfaces\ViewModel;
use ConsulConfigManager\Auth\Exceptions\InvalidCredentialsException;

/**
 * Class AuthenticateInteractor
 *
 * @package ConsulConfigManager\Auth\UseCases\Authenticate
 */
class AuthenticateInteractor implements AuthenticateInputPort
{
    /**
     * Output port instance
     * @var AuthenticateOutputPort
     */
    private AuthenticateOutputPort $output;

    /**
     * Authenticator instance
     * @var Authenticator
     */
    private Authenticator $authenticator;

    /**
     * AuthenticateInteractor Constructor.
     *
     * @param AuthenticateOutputPort $output
     * @param Authenticator          $authenticator
     */
    public function __construct(AuthenticateOutputPort $output, Authenticator $authenticator)
    {
        $this->output = $output;
        $this->authenticator = $authenticator;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(AuthenticateRequestModel $authenticateRequestModel): ViewModel
    {
        $request = $authenticateRequestModel->getRequest();
        try {
            $user = $this->authenticator->attempt($request);
            if (!$user) {
                throw new InvalidCredentialsException();
            }
            return $this->output->userAuthenticated(new AuthenticateResponseModel($user));
        } catch (Throwable $exception) {
            if ($exception instanceof InvalidCredentialsException) {
                return $this->output->invalidCredentials(new AuthenticateResponseModel());
            }
            // @codeCoverageIgnoreStart
            return $this->output->unableToAuthenticateUser(new AuthenticateResponseModel(), $exception);
            // @codeCoverageIgnoreEnd
        }
    }
}
