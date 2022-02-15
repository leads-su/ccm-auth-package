<?php

namespace ConsulConfigManager\Auth\Test\Feature;

use Illuminate\Support\Arr;

/**
 * Class AuthenticationTest
 * @package ConsulConfigManager\Auth\Test\Feature
 */
class AuthenticationTest extends AbstractFeatureTest
{
    /**
     * @param array $data
     * @return void
     * @dataProvider dataProvider
     */
    public function testShouldPassIfUserCanBeAuthenticatedUsingEmail(array $data): void
    {
        $this->createDatabaseUser($data);
        $response = $this->post('/auth/authenticate', $this->createRequestArray($data, 'email'));
        $response->assertStatus(200);
        $this->assertSuccessfulAuthenticationResponse($response);
    }

    /**
     * @param array $data
     * @return void
     * @dataProvider dataProvider
     */
    public function testShouldPassIfUserCanBeAuthenticatedUsingUsername(array $data): void
    {
        $this->createDatabaseUser($data);
        $response = $this->post('/auth/authenticate', $this->createRequestArray($data, 'username'));
        $response->assertStatus(200);
        $this->assertSuccessfulAuthenticationResponse($response);
    }

    /**
     * @param array $data
     * @return void
     * @dataProvider dataProvider
     */
    public function testShouldPassIfUserCannotBeAuthenticatedWithInvalidEmail(array $data): void
    {
        $this->createDatabaseUser($data);
        Arr::set($data, 'email', 'jane.doe@example.com');
        $response = $this->post('/auth/authenticate', $this->createRequestArray($data, 'email'));
        $this->assertFailedAuthenticationResponse($response);
    }

    /**
     * @param array $data
     * @return void
     * @dataProvider dataProvider
     */
    public function testShouldPassIfUserCannotBeAuthenticatedWithInvalidUsername(array $data): void
    {
        $this->createDatabaseUser($data);
        Arr::set($data, 'username', 'jane.doe');
        $response = $this->post('/auth/authenticate', $this->createRequestArray($data, 'username'));
        $this->assertFailedAuthenticationResponse($response);
    }

    /**
     * @param array $data
     * @return void
     * @dataProvider dataProvider
     */
    public function testShouldPassIfUserCannotBeAuthenticatedWithValidEmailAndInvalidPassword(array $data): void
    {
        $this->createDatabaseUser($data);
        Arr::set($data, 'password', 'invalidPassword');
        $response = $this->post('/auth/authenticate', $this->createRequestArray($data, 'email'));
        $this->assertFailedAuthenticationResponse($response);
    }

    /**
     * @param array $data
     * @return void
     * @dataProvider dataProvider
     */
    public function testShouldPassIfUserCannotBeAuthenticatedWithValidUsernameAndInvalidPassword(array $data): void
    {
        $this->createDatabaseUser($data);
        Arr::set($data, 'password', 'invalidPassword');
        $response = $this->post('/auth/authenticate', $this->createRequestArray($data, 'username'));
        $this->assertFailedAuthenticationResponse($response);
    }
}
