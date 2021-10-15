<?php namespace ConsulConfigManager\Auth\Test\Feature;

use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;

/**
 * Class LogoutTest
 * @package ConsulConfigManager\Auth\Test\Feature
 */
class LogoutTest extends TestCase {
    use ProvidesUsersRepository;

    public function testShouldPassIfUnauthorizedUserCannotUseThisRoute(): void {
        $response = $this->post('/auth/logout');
        $response->assertStatus(401);
    }

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldPassIfAuthorizedUserCanUseThisRoute(array $data): void {
        // We need to create user first
        $user = $this->createUserEntityFromArray($data);
        $user->save();
        $response = $this->post('/auth/authenticate', [
            'emailOrUsername'       =>  Arr::get($data, 'email'),
            'password'              =>  Arr::get($data, 'password')
        ]);
        $response->assertStatus(200);

        Sanctum::actingAs($user);

        // Now we can log user out
        $response = $this->post('/auth/logout');
        $response->assertStatus(200);
    }
}
