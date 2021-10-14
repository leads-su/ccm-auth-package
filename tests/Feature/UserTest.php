<?php namespace ConsulConfigManager\Auth\Test\Feature;

use ConsulConfigManager\Auth\Test\TestCase;
use ConsulConfigManager\Auth\Test\ProvidesUsersRepository;

/**
 * Class UserTest
 * @package ConsulConfigManager\Auth\Test\Feature
 */
class UserTest extends TestCase {
    use ProvidesUsersRepository;

    /**
     * @param array $data
     * @dataProvider userDataProvider
     */
    public function testShouldPassIfAuthenticatedUserCanRetrievePersonalInformation(array $data): void {
        unset($data['password']);
        $user = $this->createUserEntityFromArray($data);
        $response = $this->actingAs($user)->get('/auth/user');
        $this->assertEquals(200, $response->status());

        $decoded = $response->json();
        $this->assertArrayHasKey('scopes', $decoded['data']);
        $this->assertArrayHasKey('role', $decoded['data']);
    }

    public function testShouldPassIfNotAuthenticatedUserCannotRetrievePersonalInformation(): void {
        $response = $this->get('/auth/user');
        $this->assertEquals(403, $response->status());
    }

}
