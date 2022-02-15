<?php

namespace ConsulConfigManager\Auth\Test\Unit\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Services\Authenticator;
use ConsulConfigManager\Users\Factories\UserFactory;
use ConsulConfigManager\Auth\Http\Requests\AuthRequest;
use ConsulConfigManager\Users\Interfaces\UserInterface;
use ConsulConfigManager\Users\ValueObjects\PasswordValueObject;
use ConsulConfigManager\Users\Interfaces\UserRepositoryInterface;

/**
 * Class AuthenticatorTest
 * @package ConsulConfigManager\Auth\Test\Unit\Services
 */
class AuthenticatorTest extends TestCase
{
    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfNullReturnedWhenAuthenticatingLocallyWithNonExistentUser(array $data): void
    {
        $authenticator = $this->authenticator();
        $authenticationRequest = $this->createAuthenticationRequest($data);
        $this->assertNull($authenticator->attempt(
            $authenticationRequest
        ));
    }

    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfNullReturnedWhenAuthenticatingLocallyWithInvalidCredentials(array $data): void
    {
        $this->createDatabaseUser($data);
        $authenticator = $this->authenticator();
        Arr::set($data, 'password', 'invalidPassword');
        $authenticationRequest = $this->createAuthenticationRequest($data);
        $this->assertNull($authenticator->attempt(
            $authenticationRequest
        ));
    }

    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfCanBeAuthenticatedLocallyWithValidData(array $data): void
    {
        $this->createDatabaseUser($data);
        $authenticator = $this->authenticator();
        $authenticationRequest = $this->createAuthenticationRequest($data);
        $this->assertInstanceOf(UserInterface::class, $authenticator->attempt(
            $authenticationRequest
        ));
    }

    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfNullReturnedWhenAuthenticatingWithLdapWithNonExistentUser(array $data): void
    {
        $data = $this->createLdapMissingUser();
        $authenticator = $this->ldapAuthenticator($data, 'missing');
        $this->assertNull($authenticator->attempt(
            $this->createAuthenticationRequest($data)
        ));
    }

    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfNullReturnedWhenAuthenticatingWithLdapWithInvalidEmail(array $data): void
    {
        $data = $this->createLdapUserWithInvalidEmailFromData($data);
        $authenticator = $this->ldapAuthenticator($data, 'email');
        $this->assertNull($authenticator->attempt(
            $this->createAuthenticationRequest($data)
        ));
    }

    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfNullReturnedWhenAuthenticatingWithLdapWithInvalidUsername(array $data): void
    {
        $data = $this->createLdapUserWithInvalidUsernameFromData($data);
        $authenticator = $this->ldapAuthenticator($data, 'username');
        $this->assertNull($authenticator->attempt(
            $this->createAuthenticationRequest($data)
        ));
    }

    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfNullReturnedWhenAuthenticatingWithLdapWithInvalidPassword(array $data): void
    {
        $data = $this->createLdapUserWithInvalidPasswordFromData($data);
        $authenticator = $this->ldapAuthenticator($data, 'password');
        $this->assertNull($authenticator->attempt(
            $this->createAuthenticationRequest($data)
        ));
    }

    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfCanBeAuthenticatedWithLdapWithValidEmail(array $data): void
    {
        $authenticator = $this->ldapAuthenticator($data, 'email');
        $this->assertInstanceOf(UserInterface::class, $authenticator->attempt(
            $this->createAuthenticationRequest($data, 'email')
        ));
    }

    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfCanBeAuthenticatedWithLdapWithValidUsername(array $data): void
    {
        $authenticator = $this->ldapAuthenticator($data, 'username');
        $this->assertInstanceOf(UserInterface::class, $authenticator->attempt(
            $this->createAuthenticationRequest($data, 'username')
        ));
    }

    /**
     * Create new authenticator instance
     * @return Authenticator
     */
    private function authenticator(): Authenticator
    {
        return new Authenticator($this->userRepository());
    }

    /**
     * Create new LDAP authenticator instance
     * @param array $data
     * @param string $checking
     * @return Authenticator
     */
    private function ldapAuthenticator(array $data, string $checking): Authenticator
    {
        $this->mockLdapServer($data, $checking);
        return $this->authenticator();
    }

    /**
     * Create new authentication request
     * @param array $data
     * @param string|null $specific
     * @return AuthRequest
     */
    private function createAuthenticationRequest(array $data, ?string $specific = null): AuthRequest
    {
        $emailOrUsername = Arr::random([
            Arr::get($data, 'email'),
            Arr::get($data, 'username'),
        ]);

        if ($specific !== null) {
            $emailOrUsername = Arr::get($data, $specific);
        }

        return new AuthRequest([
            'emailOrUsername'       =>  $emailOrUsername,
            'password'              =>  Arr::get($data, 'password'),
        ]);
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
     * Create and save user to database
     * @param array $data
     * @return UserInterface
     */
    private function createDatabaseUser(array $data): UserInterface
    {
        $passwordObject = new PasswordValueObject(Arr::get($data, 'password'));
        Arr::forget($data, 'password');

        $instance = $this->createUserEntity($data);
        return $this->userRepository()->create($instance, $passwordObject);
    }

    /**
     * Create LDAP user which is not present on LDAP server
     * @return string[]
     */
    private function createLdapMissingUser(): array
    {
        return [
            'username'      =>  'alice.doe',
            'email'         =>  'alice.doe@example.com',
            'password'      =>  'insecurePassword',
        ];
    }

    /**
     * Create LDAP user with invalid username
     * @param array $data
     *
     * @return array
     */
    private function createLdapUserWithInvalidUsernameFromData(array $data): array
    {
        Arr::set($data, 'username', 'jane.doe');
        return $data;
    }

    /**
     * Create LDAP user with invalid E-Mail
     * @param array $data
     *
     * @return array
     */
    private function createLdapUserWithInvalidEmailFromData(array $data): array
    {
        Arr::set($data, 'email', 'jane.doe@example.com');
        return $data;
    }

    /**
     * Create LDAP user with invalid password
     * @param array $data
     *
     * @return array
     */
    private function createLdapUserWithInvalidPasswordFromData(array $data): array
    {
        Arr::set($data, 'password', 'invalidPassword');
        return $data;
    }

    /**
     * Create LDAP user or return null
     * @param array $data
     * @param string $checking
     * @return UserInterface|null
     */
    private function createLdapUserOrReturnNull(array $data, string $checking): ?UserInterface
    {
        switch ($checking) {
            case 'username':
                if (Arr::get($data, 'username') === 'jane.doe') {
                    return null;
                }
                return UserFactory::new()->make($data);
            case 'email':
                if (Arr::get($data, 'email') === 'jane.doe@example.com') {
                    return null;
                }
                return UserFactory::new()->make($data);
            case 'password':
                if (Arr::get($data, 'password') === 'invalidPassword') {
                    return null;
                }
                if (
                    Arr::get($data, 'username') === 'jane.doe' ||
                    Arr::get($data, 'email') === 'jane.doe@example.com'
                ) {
                    return null;
                }
                return UserFactory::new()->make($data);
            default:
                return null;
        }
    }

    /**
     * Create new instance of user repository
     * @return UserRepositoryInterface
     */
    private function userRepository(): UserRepositoryInterface
    {
        return $this->app->make(UserRepositoryInterface::class);
    }

    /**
     * Mock LDAP server
     * @param array $data
     * @param string $checking
     * @return void
     */
    private function mockLdapServer(array $data, string $checking): void
    {
        Auth::shouldReceive('attempt')
            ->once()
            ->withArgs(function (array $credentials = [], bool $remember = false): bool {
                return true;
            })
            ->andReturnUsing(function () use ($data, $checking): bool {
                return $this->createLdapUserOrReturnNull($data, $checking) !== null;
            });

        Auth::shouldReceive('user')
            ->andReturnUsing(function () use ($data, $checking): ?UserInterface {
                return $this->createLdapUserOrReturnNull($data, $checking);
            });
    }

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
}
