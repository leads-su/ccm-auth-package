<?php namespace ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\User;

use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;
use ConsulConfigManager\Auth\Domain\UseCases\User\UserResponseModel;

/**
 * Class UserResponseModelTest
 * @package ConsulConfigManager\Auth\Test\Unit\Domain\UseCases\User
 */
class UserResponseModelTest extends TestCase {
    use ProvidesUsersRepository;

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldPassIfUserEntityIsReturned(array $data): void {
        $instance = new UserResponseModel($this->createUserEntityFromArray($data));
        $this->assertInstanceOf(UserEntity::class, $instance->getUser());
    }

    public function testShouldFailIfNullIsReturned(): void {
        $instance = new UserResponseModel();
        $this->assertNull($instance->getUser());
    }

}
