<?php

namespace ConsulConfigManager\Auth\Test;

use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Application;
use ConsulConfigManager\Auth\AuthDomain;
use ConsulConfigManager\Users\Factories\UserFactory;
use ConsulConfigManager\Auth\Providers\AuthServiceProvider;
use ConsulConfigManager\Users\Providers\UsersServiceProvider;

/**
 * Class TestCase
 *
 * @package ConsulConfigManager\Users\Test
 */
abstract class TestCase extends \ConsulConfigManager\Testing\TestCase
{
    /**
     * @inheritDoc
     */
    protected array $packageProviders = [
        UsersServiceProvider::class,
        AuthServiceProvider::class,
    ];

    /**
     * @inheritDoc
     */
    protected bool $configurationFromEnvironment = true;

    /**
     * @inheritDoc
     */
    protected string $configurationFromFile = __DIR__ . '/..';

    /**
     * @inheritDoc
     */
    public function runBeforeSetUp(): void
    {
        AuthDomain::registerRoutes();
    }

    /**
     * @inheritDoc
     */
    public function runBeforeTearDown(): void
    {
        AuthDomain::ignoreRoutes();
    }

    /**
     * @inheritDoc
     */
    public function setUpEnvironment(Application $app): void
    {
        $this->setConfigurationValue('permission', [
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
        ], $app);
    }

    /**
     * Use Laravel Sanctum to provide authenticated access to routes
     * @param array $data
     *
     * @return $this
     */
    protected function useSanctum(array $data): self
    {
        Sanctum::actingAs(UserFactory::new()->make($data));
        return $this;
    }
}
