<?php namespace ConsulConfigManager\Auth\Test;

use Mockery;
use Mockery\MockInterface;
use Illuminate\Support\Arr;
use ConsulConfigManager\Users\Factories\UserModelFactory;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;
use ConsulConfigManager\Users\Domain\Interfaces\UserRepository;
use ConsulConfigManager\Users\Domain\ValueObjects\EmailValueObject;
use ConsulConfigManager\Users\Domain\ValueObjects\UsernameValueObject;

/**
 * Trait ProvidesUsersRepository
 *
 * @package ConsulConfigManager\Auth\Test
 */
trait ProvidesUsersRepository {

    /**
     * Name of user we want to provide
     * @var string
     */
    private string $provideUser = 'Jessie Doe';

    /**
     * Repository mock instance
     * @var MockInterface
     */
    private MockInterface $repositoryMock;

    /**
     * User data provider
     * @return array
     */
    public function userDataProvider(): array {
        return [
            'John Doe'              =>  [
                'data'              =>  [
                    'id'            =>  1,
                    'guid'          =>  'some-random-guid-string-1',
                    'domain'        =>  'company',
                    'first_name'    =>  'John',
                    'last_name'     =>  'Doe',
                    'username'      =>  'john.doe',
                    'email'         =>  'john.doe@example.com',
                    'password'      =>  'insecurePassword',
                ]
            ]
        ];
    }

    private function userRepositoryUsers(): array {
        return [
            Arr::except(Arr::get(Arr::first($this->userDataProvider()), 'data'), ['created'])
        ];
    }

    /**
     * Bootstrap repository information
     * @return UserRepository
     */
    protected function bootstrapUsersRepository(): UserRepository {
        $this->repositoryMock = Mockery::mock(UserRepository::class);
        $this
            ->userRepositoryBootstrapFindByEmailMethod()
            ->userRepositoryBootstrapFindByUsernameMethod();
        return $this->repositoryMock;
    }


    /**
     * Ensure that repository is able to respond to `findByEmail` method request
     * @return $this
     */
    private function userRepositoryBootstrapFindByEmailMethod(): self {
        $this->repositoryMock
            ->shouldReceive('findByEmail')
            ->with(EmailValueObject::class)
            ->andReturnUsing(function (EmailValueObject $email): ?UserEntity {
                $user = null;

                foreach ($this->userRepositoryUsers() as $userData) {
                    if (isset($userData['email']) && $userData['email'] === (string) $email) {
                        $user = $userData;
                        break;
                    }
                }

                if (!$user) {
                    return null;
                }

                return $this->createUserEntityFromArray($user);
            });
        return $this;
    }

    /**
     * Ensure that repository is able to respond to `findByUsername` method request
     * @return $this
     */
    private function userRepositoryBootstrapFindByUsernameMethod(): self {
        $this->repositoryMock
            ->shouldReceive('findByUsername')
            ->with(UsernameValueObject::class)
            ->andReturnUsing(function (UsernameValueObject $username): ?UserEntity {
                $user = null;

                foreach ($this->userRepositoryUsers() as $userData) {
                    if (isset($userData['username']) && $userData['username'] === (string) $username) {
                        $user = $userData;
                        break;
                    }
                }

                if (!$user) {
                    return null;
                }

                return $this->createUserEntityFromArray($user);
            });
        return $this;
    }

    /**
     * Create user entity from provided array
     * @param array $data
     *
     * @return UserEntity
     */
    private function createUserEntityFromArray(array $data): UserEntity {
        return UserModelFactory::new()->make($data);
    }

}
