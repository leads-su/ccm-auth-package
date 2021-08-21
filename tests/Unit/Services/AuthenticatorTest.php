<?php namespace ConsulConfigManager\Auth\Test\Unit\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Services\Authenticator;
use ConsulConfigManager\Auth\Http\Requests\AuthRequest;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;


/**
 * Class AuthenticatorTest
 *
 * @package ConsulConfigManager\Auth\Test\Unit\Services
 */
class AuthenticatorTest extends TestCase {
    use ProvidesUsersRepository;

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldPassIfCanBeAuthenticatedLocallyWithValidData(array $data): void {
        $usersRepository = $this->bootstrapUsersRepository();
        $authenticator = new Authenticator($usersRepository);
        $this->assertInstanceOf(UserEntity::class, $authenticator->attempt($this->createAuthenticationRequest($data)));
    }

    public function testShouldFailIfCannotBeAuthenticatedLocallyWithInvalidUsernameOrEmail(): void {
        $usersRepository = $this->bootstrapUsersRepository();
        $authenticator = new Authenticator($usersRepository);
        $this->assertNull($authenticator->attempt($this->createAuthenticationRequest([
            'username'          =>  'invalid.username',
            'email'             =>  'invalid.email@internet.org',
            'password'          =>  'insecurePassword'
        ])));
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldFailIfCannotBeAuthenticatedLocallyWithInvalidPassword(array $data): void {
        $usersRepository = $this->bootstrapUsersRepository();
        $authenticator = new Authenticator($usersRepository);
        Arr::set($data, 'password', 'invalidPassword');
        $this->assertNull($authenticator->attempt($this->createAuthenticationRequest($data)));
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldPassIfCanBeAuthenticatedWithLdapAndValidUsername(array $data): void {
        $data = $this->createValidLdapUserWithFromData($data);
        $authenticator = $this->createLdapAuthenticator($data, 'username');
        $this->assertInstanceOf(UserEntity::class, $authenticator->attempt($this->createAuthenticationRequest($data)));
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldFailIfCannotBeAuthenticatedWithLdapAndInvalidUsername(array $data): void {
        $data = $this->createLdapUserWithInvalidUsernameFromData($data);
        $authenticator = $this->createLdapAuthenticator($data, 'username');
        $this->assertNull($authenticator->attempt($this->createAuthenticationRequest($data)));
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldPassIfCanBeAuthenticatedWithLdapAndValidEmail(array $data): void {
        $data = $this->createValidLdapUserWithFromData($data);
        $authenticator = $this->createLdapAuthenticator($data, 'email');
        $this->assertInstanceOf(UserEntity::class, $authenticator->attempt($this->createAuthenticationRequest($data)));
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldFailIfCannotBeAuthenticatedWithLdapAndInvalidEmail(array $data): void {
        $data = $this->createLdapUserWithInvalidEmailFromData($data);
        $authenticator = $this->createLdapAuthenticator($data, 'email');
        $this->assertNull($authenticator->attempt($this->createAuthenticationRequest($data)));
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldFailIfCannotBeAuthenticatedWithLdapAndInvalidPassword(array $data): void {
        $data = $this->createLdapUserWithInvalidPasswordFromData($data);
        $authenticator = $this->createLdapAuthenticator($data, 'password');
        $this->assertNull($authenticator->attempt($this->createAuthenticationRequest($data)));
    }

    /**
     * Create authentication request
     * @param array $data
     *
     * @return AuthRequest
     */
    private function createAuthenticationRequest(array $data): AuthRequest {
        $loginOptions = [
            Arr::get($data, 'email'),
            Arr::get($data, 'username')
        ];

        return new AuthRequest([
            'emailOrUsername'       =>  Arr::random($loginOptions),
            'password'              =>  Arr::get($data, 'password')
        ]);
    }

    /**
     * Create LDAP specific authenticator instance
     * @param array  $data
     * @param string $checking
     *
     * @return Authenticator
     */
    private function createLdapAuthenticator(array $data, string $checking): Authenticator {
        $this->mockLdapServer($data, $checking);
        $usersRepository = $this->bootstrapUsersRepository();
        return new Authenticator($usersRepository);
    }

    /**
     * Mock LDAP server
     *
     * @param array  $data
     * @param string $checking
     */
    private function mockLdapServer(array $data, string $checking): void {
        Auth::shouldReceive('attempt')
            ->once()
            ->withArgs(function (array $credentials = [], bool $remember = false): bool {
                return true;
            })
            ->andReturnUsing(function () use ($data, $checking): bool {
                return $this->createUserOrReturnNull($data, $checking) !== null;
            });

        Auth::shouldReceive('user')
            ->andReturnUsing(function () use ($data, $checking): ?UserEntity {
                return $this->createUserOrReturnNull($data, $checking);
            });
    }

    /**
     * Create user or return null if needed
     * @param array  $data
     * @param string $checking
     *
     * @return UserEntity|null
     */
    private function createUserOrReturnNull(array $data, string $checking): ?UserEntity {
        switch ($checking) {
            case 'username':
                if (Arr::get($data, 'username') === 'non_existent') {
                    return null;
                }
                return $this->createUserEntityFromArray($data);
            case 'email':
                if (Arr::get($data, 'email') === 'non_existent@example.com') {
                    return null;
                }
                return $this->createUserEntityFromArray($data);
            case 'password':
                if (Arr::get($data, 'password') === 'invalidpass') {
                    return null;
                }
                if (
                    Arr::get($data, 'username') === 'non_existent' ||
                    Arr::get($data, 'email') === 'non_existent@example.com'
                ) {
                    return null;
                }
                return $this->createUserEntityFromArray($data);
            default:
                return null;
        }
    }

    /**
     * Create valid LDAP user
     * @param array $data
     *
     * @return array
     */
    private function createValidLdapUserWithFromData(array $data): array {
        Arr::set($data, 'username', 'doe.john');
        Arr::set($data, 'email', 'doe.john@example.com');
        return $data;
    }

    /**
     * Create LDAP user with invalid username
     * @param array $data
     *
     * @return array
     */
    private function createLdapUserWithInvalidUsernameFromData(array $data): array {
        Arr::set($data, 'username', 'non_existent');
        Arr::set($data, 'email', 'doe.john@example.com');
        return $data;
    }

    /**
     * Create LDAP user with invalid E-Mail
     * @param array $data
     *
     * @return array
     */
    private function createLdapUserWithInvalidEmailFromData(array $data): array {
        Arr::set($data, 'username', 'doe.john');
        Arr::set($data, 'email', 'non_existent@example.com');
        return $data;
    }

    /**
     * Create LDAP user with invalid password
     * @param array $data
     *
     * @return array
     */
    private function createLdapUserWithInvalidPasswordFromData(array $data): array {
        $data = $this->createValidLdapUserWithFromData($data);
        Arr::set($data, 'password', 'invalidpass');
        return $data;
    }

}