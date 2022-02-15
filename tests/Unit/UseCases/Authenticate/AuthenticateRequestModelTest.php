<?php

namespace ConsulConfigManager\Auth\Test\Unit\UseCases\Authenticate;

use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Http\Requests\AuthRequest;
use ConsulConfigManager\Auth\UseCases\Authenticate\AuthenticateRequestModel;

/**
 * Class AuthenticateRequestModelTest
 *
 * @package ConsulConfigManager\Auth\Test\Unit\UseCases\Authenticate
 */
class AuthenticateRequestModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldPassIfInitializedCorrectly(): void
    {
        $this->assertInstanceOf(AuthenticateRequestModel::class, new AuthenticateRequestModel($this->createNewAuthRequest()));
    }

    /**
     * @return void
     */
    public function testShouldPassIfInstanceofAuthRequestIsReturned(): void
    {
        $instance = new AuthenticateRequestModel($this->createNewAuthRequest());
        $this->assertInstanceOf(AuthRequest::class, $instance->getRequest());
    }

    /**
     * @return void
     */
    public function testShouldPassIfLoginIsReturned(): void
    {
        $instance = new AuthenticateRequestModel($this->createNewAuthRequest());
        $this->assertEquals('john.doe', $instance->getLogin());
    }

    /**
     * @return void
     */
    public function testShouldPassIfPasswordIsReturned(): void
    {
        $instance = new AuthenticateRequestModel($this->createNewAuthRequest());
        $this->assertEquals('insecurePassword', $instance->getPassword());
    }

    /**
     * Create new instance of Auth Request
     * @return AuthRequest
     */
    private function createNewAuthRequest(): AuthRequest
    {
        return new AuthRequest([], [
            'emailOrUsername'       =>  'john.doe',
            'password'              =>  'insecurePassword',
        ]);
    }
}
