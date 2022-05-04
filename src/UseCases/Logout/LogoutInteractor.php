<?php

namespace ConsulConfigManager\Auth\UseCases\Logout;

use Throwable;
use ConsulConfigManager\Domain\Interfaces\ViewModel;

/**
 * Class LogoutInteractor
 * @package ConsulConfigManager\Auth\UseCases\Logout
 */
class LogoutInteractor implements LogoutInputPort
{
    /**
     * Output port instance
     * @var LogoutOutputPort
     */
    private LogoutOutputPort $output;

    /**
     * LogoutInteractor constructor.
     * @param LogoutOutputPort $output
     * @return void
     */
    public function __construct(LogoutOutputPort $output)
    {
        $this->output = $output;
    }

    /**
     * @inheritDoc
     */
    public function logout(LogoutRequestModel $logoutRequestModel): ViewModel
    {
        $user = $logoutRequestModel->getUser();
        if ($user === null) {
            return $this->output->notAuthorized(new LogoutResponseModel());
        }
        try {
            $accessToken = $user->currentAccessToken();
            $accessToken->deleteOrFail();
            return $this->output->successfullyLoggedOut(new LogoutResponseModel());
            // @codeCoverageIgnoreStart
        } catch (Throwable $exception) {
            return $this->output->failedToLogOut(new LogoutResponseModel(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }
}
