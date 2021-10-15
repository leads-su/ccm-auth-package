<?php namespace ConsulConfigManager\Auth\Domain\UseCases\Logout;

use Illuminate\Http\Request;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class LogoutRequestModel
 * @package ConsulConfigManager\Auth\Domain\UseCases\Logout
 */
class LogoutRequestModel {

    /**
     * Request instance
     * @var Request
     */
    private Request $request;

    /**
     * UserRequestModel constructor.
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Get request instance
     * @return Request
     */
    public function getRequest(): Request {
        return $this->request;
    }

    /**
     * Get user who made this request
     * @return UserEntity|null
     */
    public function getUser(): ?UserEntity {
        return $this->getRequest()->user();
    }

}
