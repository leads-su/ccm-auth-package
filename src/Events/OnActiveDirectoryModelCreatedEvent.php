<?php

namespace ConsulConfigManager\Auth\Events;

use LdapRecord\Models\Events\Created;
use ConsulConfigManager\Users\Models\User;
use ConsulConfigManager\Users\Interfaces\UserInterface;

/**
 * Class OnActiveDirectoryModelCreatedEvent
 *
 * @package ConsulConfigManager\Auth\Events
 * @codeCoverageIgnoreStart
 */
abstract class OnActiveDirectoryModelCreatedEvent
{
    /**
     * E-Mail attribute name
     * @var string
     */
    protected string $emailAttribute = 'userprincipalname';

    /**
     * Handle event
     * @param Created $event
     */
    abstract public function handle(Created $event): void;

    /**
     * Get local user from event
     * @param Created $event
     *
     * @return UserInterface|null
     */
    public function getUserFromEvent(Created $event): ?UserInterface
    {
        $email = $event->getModel()->getAttribute($this->emailAttribute);
        return User::where('email', '=', $email)->first();
    }
}
// @codeCoverageIgnoreEnd
