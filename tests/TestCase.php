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
        $app['config']->set('permission', [
            'models' => [
                'permission' => \Spatie\Permission\Models\Permission::class,
                'role' => \Spatie\Permission\Models\Role::class,
            ],
            'table_names' => [
                'roles' => 'roles',
                'permissions' => 'permissions',
                'model_has_permissions' => 'model_has_permissions',
                'model_has_roles' => 'model_has_roles',
                'role_has_permissions' => 'role_has_permissions',
            ],
            'column_names' => [
                'role_pivot_key' => 'role_id',
                'permission_pivot_key' => 'permission_id',
                'model_morph_key' => 'model_id',
                'team_foreign_key' => 'team_id',
            ],
            'teams' => false,
            'display_permission_in_exception' => false,
            'display_role_in_exception' => false,
            'enable_wildcard_permission' => false,
            'cache' => [
                'expiration_time' => \DateInterval::createFromDateString('24 hours'),
                'key' => 'spatie.permission.cache',
                'store' => 'default',
            ],
        ]);
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
