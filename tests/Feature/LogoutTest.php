<?php

namespace ConsulConfigManager\Auth\Test\Feature;

use Laravel\Sanctum\Sanctum;

/**
 * Class LogoutTest
 * @package ConsulConfigManager\Auth\Test\Feature
 */
class LogoutTest extends AbstractFeatureTest
{
    /**
     * @return void
     */
    public function testShouldPassIfUnauthorizedReturnedWhenTriedToAccessRouteWithoutAuthorization(): void
    {
        $response = $this->post('/auth/logout');
        $response->assertStatus(401);
    }

    /**
     * @param array $data
     * @dataProvider dataProvider
     */
    public function testShouldPassIfAuthorizedUserCanUseThisRoute(array $data): void
    {
        $user = $this->createDatabaseUser($data);
        $response = $this->post('/auth/authenticate', $this->createRequestArray($data, 'email'));
        $this->assertSuccessfulAuthenticationResponse($response);

        Sanctum::actingAs($user);

        $response = $this->post('/auth/logout');
        $response->assertStatus(200);
        $this->assertSuccessfulLogoutResponse($response);
    }
}
