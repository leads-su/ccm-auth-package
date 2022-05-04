<?php

namespace ConsulConfigManager\Auth\Test\Unit;

use ConsulConfigManager\Auth\AuthDomain;
use ConsulConfigManager\Auth\Test\TestCase;

/**
 * Class AuthDomainTest
 * @package ConsulConfigManager\Auth\Test\Unit
 */
class AuthDomainTest extends TestCase
{
    public function testRoutesShouldNotBeRegisteredByDefault(): void
    {
        AuthDomain::ignoreRoutes();
        $this->assertFalse(AuthDomain::shouldRegisterRoutes());
        AuthDomain::registerRoutes();
    }

    public function testRoutesRegistrationCanBeEnabled(): void
    {
        AuthDomain::registerRoutes();
        $this->assertTrue(AuthDomain::shouldRegisterRoutes());
        AuthDomain::ignoreRoutes();
    }
}
