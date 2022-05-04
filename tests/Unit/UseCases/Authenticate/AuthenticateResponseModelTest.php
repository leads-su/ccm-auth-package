<?php

namespace ConsulConfigManager\Auth\Test\Unit\UseCases\Authenticate;

use ConsulConfigManager\Users\Factories\UserFactory;
use ConsulConfigManager\Users\Interfaces\UserInterface;
use ConsulConfigManager\Auth\Test\Unit\UseCases\AbstractUseCaseTest;
use ConsulConfigManager\Auth\UseCases\Authenticate\AuthenticateResponseModel;

/**
 * Class AuthenticateResponseModelTest
 *
 * @package ConsulConfigManager\Auth\Test\Unit\UseCases\Authenticate
 */
class AuthenticateResponseModelTest extends AbstractUseCaseTest
{
    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfUserEntityIsReturned(array $data): void
    {
        $instance = new AuthenticateResponseModel(UserFactory::new()->make($data));
        $this->assertInstanceOf(UserInterface::class, $instance->getUser());
    }

    /**
     * @return void
     */
    public function testShouldFailIfNullIsReturned(): void
    {
        $instance = new AuthenticateResponseModel();
        $this->assertNull($instance->getUser());
    }
}
