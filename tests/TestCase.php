<?php namespace ConsulConfigManager\Auth\Test;

use Laravel\Sanctum\Sanctum;
use ConsulConfigManager\Auth\AuthDomain;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use ConsulConfigManager\Users\Factories\UserModelFactory;
use ConsulConfigManager\Auth\Providers\AuthServiceProvider;
use ConsulConfigManager\Users\Providers\UsersServiceProvider;

/**
 * Class TestCase
 *
 * @package ConsulConfigManager\Users\Test
 */
abstract class TestCase extends Orchestra {
    use DatabaseMigrations;

    /**
     * @inheritDoc
     */
    public function setUp(): void {
        AuthDomain::registerRoutes();
        parent::setUp();
    }

    public function tearDown(): void {
        AuthDomain::ignoreRoutes();
        parent::tearDown();
    }

    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app): array {
        return [
            UsersServiceProvider::class,
            AuthServiceProvider::class,
        ];
    }

    /**
     * Use Laravel Sanctum to provide authenticated access to routes
     * @param array $data
     *
     * @return $this
     */
    protected function useSanctum(array $data): self {
        Sanctum::actingAs(UserModelFactory::new()->make($data));
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function ignorePackageDiscoveriesFrom(): array {
        return [];
    }


    /**
     * @inheritDoc
     */
    protected function getEnvironmentSetUp($app): void {
        $app['config']->set('app.env', 'testing');
        $app['config']->set('app.debug', true);
        $app['config']->set('auth.default', 'api');
        $app['config']->set('cache.default', 'array');
        $app['config']->set('hashing.bcrypt.round', 4);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'        =>  'sqlite',
            'database'      =>  ':memory:',
            'prefix'        =>  '',
        ]);
    }

}
