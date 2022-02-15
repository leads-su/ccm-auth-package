<?php

namespace ConsulConfigManager\Auth\UseCases\Logout;

use Illuminate\Http\Request;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class LogoutRequestModel
 * @package ConsulConfigManager\Auth\UseCases\Logout
 */
class LogoutRequestModel
{
    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * UserRequestModel constructor.
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get request instance
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Get user who made this request
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->getRequest()->user();
    }
}
