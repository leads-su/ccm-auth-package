<?php namespace ConsulConfigManager\Auth\Test\Feature;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class AuthenticationTest
 *
 * @package ConsulConfigManager\Auth\Test\Feature
 */
class AuthenticationTest extends TestCase {
    use ProvidesUsersRepository;

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldPassIfUserCanBeAuthenticatedThroughDatabase(array $data): void {
        $this->createUserEntityFromArray($data)->save();
        $response = $this->post('/auth/authenticate', [
            'emailOrUsername'       =>  Arr::get($data, 'email'),
            'password'              =>  Arr::get($data, 'password')
        ]);
        $response->assertStatus(200);
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldFailIfUserCannotBeAuthenticatedThroughDatabase(array $data): void {
        $response = $this->post('/auth/authenticate', [
            'emailOrUsername'       =>  Arr::get($data, 'email'),
            'password'              =>  Arr::get($data, 'password')
        ]);
        $response->assertStatus(401);
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldFailIfUserCannotBeAuthenticatedThroughDatabaseWithInvalidPassword(array $data): void {
        $this->createUserEntityFromArray($data)->save();
        $response = $this->post('/auth/authenticate', [
            'emailOrUsername'       =>  Arr::get($data, 'email'),
            'password'              =>  'invalidpassword'
        ]);
        $response->assertStatus(401);
    }

    /**
     * @param array $data
     * @dataProvider ldapUserDataProvider
     */
    public function testShouldPassIfUserCanBeAuthenticatedThroughLdap(array $data): void {
        $this->mockLdapServer($data);
        $response = $this->post('/auth/authenticate', [
            'emailOrUsername'       =>  Arr::get($data, 'email'),
            'password'              =>  Arr::get($data, 'password')
        ]);
        $response->assertStatus(200);
    }
    /**
     * @param array $data
     * @dataProvider ldapUserDataProvider
     */
    public function testShouldFailIfUserCannotBeAuthenticatedThroughLdap(array $data): void {
        $this->mockLdapServer($data, false);
        $response = $this->post('/auth/authenticate', [
            'emailOrUsername'       =>  Arr::get($data, 'email'),
            'password'              =>  Arr::get($data, 'password')
        ]);
        $response->assertStatus(401);
    }

    /**
     * Mock LDAP server
     *
     * @param array  $data
     * @param bool   $pass
     */
    private function mockLdapServer(array $data, bool $pass = true): void {
        Auth::shouldReceive('attempt')
            ->once()
            ->withArgs(function (array $credentials = [], bool $remember = false): bool {
                return true;
            })
            ->andReturnUsing(function () use ($pass): bool {
                return $pass;
            });

        Auth::shouldReceive('userResolver')
            ->andReturnUsing(function() use ($data, $pass) {
                return function() use ($data, $pass) {
                    return $pass ? $this->createUserEntityFromArray($data) : null;
                };
            });

        Auth::shouldReceive('user')
            ->andReturnUsing(function () use ($data, $pass): ?UserEntity {
                return $pass ? $this->createUserEntityFromArray($data) : null;
            });
    }

    /**
     * LDAP user data provider
     * @return array
     */
    public function ldapUserDataProvider(): array {
        $user = $this->userDataProvider();
        Arr::set($user, 'John Doe.data.username', 'doe.john');
        Arr::set($user, 'John Doe.data.email', 'doe.john@example.com');
        Arr::set($user, 'John Doe.data.password', 'ldap_password');
        return $user;
    }

}