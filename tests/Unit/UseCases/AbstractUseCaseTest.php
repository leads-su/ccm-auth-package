<?php

namespace ConsulConfigManager\Auth\Test\Unit\UseCases;

use Illuminate\Support\Arr;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Services\Authenticator;
use ConsulConfigManager\Users\Factories\UserFactory;
use ConsulConfigManager\Users\Interfaces\UserInterface;
use ConsulConfigManager\Users\ValueObjects\PasswordValueObject;
use ConsulConfigManager\Users\Interfaces\UserRepositoryInterface;

/**
 * Class AbstractUseCase
 * @package ConsulConfigManager\Auth\Test\Unit\UseCases
 */
abstract class AbstractUseCaseTest extends TestCase
{
    /**
     * User data provider
     * @return \array[][]
     */
    public function dataProvider(): array
    {
        return [
            'example_user_entity'       =>  [
                'data'                  =>  [
                    'id'                =>  1,
                    'guid'              =>  '08b41f7c-14fa-4e7f-9287-56717802640a',
                    'domain'            =>  'example',
                    'first_name'        =>  'John',
                    'last_name'         =>  'Doe',
                    'username'          =>  'john.doe',
                    'email'             =>  'john.doe@example.com',
                    'password'          =>  'insecurePassword',
                ],
            ],
        ];
    }

    /**
     * Create new instance of authenticator
     * @return Authenticator
     */
    protected function authenticator(): Authenticator
    {
        return new Authenticator($this->userRepository());
    }

    /**
     * Create and save user to database
     * @param array $data
     * @return UserInterface
     */
    protected function createDatabaseUser(array $data): UserInterface
    {
        $passwordObject = new PasswordValueObject(Arr::get($data, 'password'));
        Arr::forget($data, 'password');

        $instance = $this->createUserEntity($data);
        return $this->userRepository()->create($instance, $passwordObject);
    }

    /**
     * Create new instance of user model
     * @param array $data
     * @return UserInterface
     */
    private function createUserEntity(array $data): UserInterface
    {
        Arr::forget($data, 'password');
        return UserFactory::new()->make($data);
    }

    /**
     * Create new instance of user repository
     * @return UserRepositoryInterface
     */
    private function userRepository(): UserRepositoryInterface
    {
        return $this->app->make(UserRepositoryInterface::class);
    }
}
