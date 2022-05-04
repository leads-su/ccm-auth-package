<?php

namespace ConsulConfigManager\Auth;

/**
 * Class AuthDomain
 *
 * @package ConsulConfigManager\Auth
 */
class AuthDomain
{
    /**
     * Indicates if package will register its routes
     * @var bool
     */
    public static bool $registersRoutes = false;

    /**
     * Determine if package should register its routes
     * @return bool
     */
    public static function shouldRegisterRoutes(): bool
    {
        return static::$registersRoutes;
    }

    /**
     * Configure package to not register its routes
     * @return static
     */
    public static function ignoreRoutes(): static
    {
        static::$registersRoutes = false;
        return new static();
    }

    /**
     * Configure package to register its routes
     * @return static
     */
    public static function registerRoutes(): static
    {
        static::$registersRoutes = true;
        return new static();
    }
}
