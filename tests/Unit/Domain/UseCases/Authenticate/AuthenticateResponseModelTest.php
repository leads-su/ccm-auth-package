<?php namespace ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\Authenticate;

use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;
use ConsulConfigManager\Auth\Domain\UseCases\Authenticate\AuthenticateResponseModel;

/**
 * Class AuthenticateResponseModelTest
 *
 * @package ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\Authenticate
 */
class AuthenticateResponseModelTest extends TestCase {
    use ProvidesUsersRepository;

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldPassIfUserEntityIsReturned(array $data): void {
        $instance = new AuthenticateResponseModel($this->createUserEntityFromArray($data));
        $this->assertInstanceOf(UserEntity::class, $instance->getUser());
    }

    public function testShouldFailIfNullIsReturned(): void {
        $instance = new AuthenticateResponseModel();
        $this->assertNull($instance->getUser());
    }

}