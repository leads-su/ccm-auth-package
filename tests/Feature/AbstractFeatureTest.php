<?php

namespace ConsulConfigManager\Auth\Test\Feature;

use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Users\Factories\UserFactory;
use ConsulConfigManager\Users\Interfaces\UserInterface;
use ConsulConfigManager\Users\ValueObjects\PasswordValueObject;
use ConsulConfigManager\Users\Interfaces\UserRepositoryInterface;

/**
 * Class AbstractFeatureTest
 * @package ConsulConfigManager\Auth\Test\Feature
 */
abstract class AbstractFeatureTest extends TestCase
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
     * Assert base response structure
     * @param TestResponse|array $response
     * @return void
     */
    protected function assertBaseResponseStructure(TestResponse|array $response): void
    {
        if ($response instanceof TestResponse) {
            $response = $response->json();
        }
        $this->assertArrayHasKey('success', $response);
        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('message', $response);
    }

    /**
     * Assert successful authentication response
     * @param TestResponse|array $response
     * @return void
     */
    protected function assertSuccessfulAuthenticationResponse(TestResponse|array $response): void
    {
        $this->assertBaseResponseStructure($response);

        if ($response instanceof TestResponse) {
            $data = $response->json('data');
        } else {
            $data = Arr::get($response, 'data');
        }

        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('token', $data);

        $token = Arr::get($data, 'token');

        $this->assertArrayHasKey('type', $token);
        $this->assertArrayHasKey('expires', $token);
        $this->assertArrayHasKey('token', $token);
    }

    /**
     * Assert failing authentication response
     * @param TestResponse|array $response
     * @return void
     */
    protected function assertFailedAuthenticationResponse(TestResponse|array $response): void
    {
        $this->assertBaseResponseStructure($response);

        if ($response instanceof TestResponse) {
            $response = $response->json();
        }

        $this->assertFalse(Arr::get($response, 'success'));
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, Arr::get($response, 'code'));
        $this->assertEquals([], Arr::get($response, 'data'));
        $this->assertEquals('Invalid credentials specified', Arr::get($response, 'message'));
    }

    /**
     * Assert successful logout response
     * @param TestResponse|array $response
     * @return void
     */
    protected function assertSuccessfulLogoutResponse(TestResponse|array $response): void
    {
        $this->assertBaseResponseStructure($response);

        if ($response instanceof TestResponse) {
            $response = $response->json();
        }

        $this->assertTrue(Arr::get($response, 'success'));
        $this->assertEquals(Response::HTTP_OK, Arr::get($response, 'code'));
        $this->assertEquals([], Arr::get($response, 'data'));
        $this->assertEquals('Successfully logged user out', Arr::get($response, 'message'));
    }

    /**
     * Assert successful user request
     * @param TestResponse|array $response
     * @param array $provider
     * @return void
     */
    protected function assertSuccessfulUserResponse(TestResponse|array $response, array $provider): void
    {
        $this->assertBaseResponseStructure($response);

        if ($response instanceof TestResponse) {
            $data = $response->json('data');
        } else {
            $data = Arr::get($response, 'data');
        }

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('created_at', $data);
        $this->assertArrayHasKey('updated_at', $data);
        $this->assertNotNull(Arr::get($data, 'created_at'));
        $this->assertNotNull(Arr::get($data, 'updated_at'));
        $this->assertSame(Arr::get($provider, 'guid'), Arr::get($data, 'guid'));
        $this->assertSame(Arr::get($provider, 'domain'), Arr::get($data, 'domain'));
        $this->assertSame(Arr::get($provider, 'first_name'), Arr::get($data, 'first_name'));
        $this->assertSame(Arr::get($provider, 'last_name'), Arr::get($data, 'last_name'));
        $this->assertSame(Arr::get($provider, 'last_name'), Arr::get($data, 'last_name'));
        $this->assertSame(Arr::get($provider, 'last_name'), Arr::get($data, 'last_name'));
        $this->assertSame(Arr::get($provider, 'username'), Arr::get($data, 'username'));
        $this->assertSame(Arr::get($provider, 'email'), Arr::get($data, 'email'));
        $this->assertSame([], Arr::get($data, 'scopes'));
        $this->assertSame('guest', Arr::get($data, 'role'));
    }

    /**
     * Create request array
     * @param array $data
     * @param string $emailOrUsername
     * @return array
     */
    protected function createRequestArray(array $data, string $emailOrUsername): array
    {
        return [
            'emailOrUsername'       =>  Arr::get($data, $emailOrUsername),
            'password'              =>  Arr::get($data, 'password'),
        ];
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
