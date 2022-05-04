<?php

namespace ConsulConfigManager\Auth\Test\Feature;

 use Laravel\Sanctum\Sanctum;

 /**
  * Class UserTest
  * @package ConsulConfigManager\Auth\Test\Feature
  */
 class UserTest extends AbstractFeatureTest
 {
     /**
      * @return void
      */
     public function testShouldPassIfUnauthorizedReturnedWhenTriedToAccessRouteWithoutAuthorization(): void
     {
         $response = $this->get('/auth/user');
         $response->assertStatus(401);
     }

     /**
      * @param array $data
      * @return void
      * @dataProvider dataProvider
      */
     public function testShouldPassIfAuthenticatedUserCanRetrievePersonalInformation(array $data): void
     {
         $user = $this->createDatabaseUser($data);
         $response = $this->post('/auth/authenticate', $this->createRequestArray($data, 'email'));
         $this->assertSuccessfulAuthenticationResponse($response);

         Sanctum::actingAs($user);

         $response = $this->get('/auth/user');
         $this->assertSuccessfulUserResponse($response, $data);
     }
 }
