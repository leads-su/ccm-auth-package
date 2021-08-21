<?php namespace ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\Authenticate;

use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Http\Requests\AuthRequest;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateRequestModel;

/**
 * Class AuthenticateRequestModelTest
 *
 * @package ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\Authenticate
 */
class AuthenticateRequestModelTest extends TestCase {

    public function testShouldPassIfInitializedCorrectly(): void {
        $this->assertInstanceOf(AuthenticateRequestModel::class, new AuthenticateRequestModel($this->createNewAuthRequest()));
    }

    public function testShouldPassIfInstanceofAuthRequestIsReturned(): void {
        $instance = new AuthenticateRequestModel($this->createNewAuthRequest());
        $this->assertInstanceOf(AuthRequest::class, $instance->getRequest());
    }

    public function testShouldPassIfLoginIsReturned(): void {
        $instance = new AuthenticateRequestModel($this->createNewAuthRequest());
        $this->assertEquals('john.doe', $instance->getLogin());
    }

    public function testShouldPassIfPasswordIsReturned(): void {
        $instance = new AuthenticateRequestModel($this->createNewAuthRequest());
        $this->assertEquals('insecurePassword', $instance->getPassword());
    }

    /**
     * Create new instance of Auth Request
     * @return AuthRequest
     */
    private function createNewAuthRequest(): AuthRequest {
        return new AuthRequest([], [
            'emailOrUsername'       =>  'john.doe',
            'password'              =>  'insecurePassword'
        ]);
    }

}