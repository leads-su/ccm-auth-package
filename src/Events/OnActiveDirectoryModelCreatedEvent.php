<?php namespace ConsulConfigManager\Auth\Events;

use LdapRecord\Models\Events\Created;
use ConsulConfigManager\Users\Models\User;
use ConsulConfigManager\Users\Domain\Interfaces\UserEntity;

/**
 * Class OnActiveDirectoryModelCreatedEvent
 *
 * @package ConsulConfigManager\Auth\Events
 * @codeCoverageIgnoreStart
 */
abstract class OnActiveDirectoryModelCreatedEvent {

    /**
     * E-Mail attribute name
     * @var string
     */
    protected string $emailAttribute = 'userprincipalname';

    /**
     * Handle event
     * @param Created $event
     */
    public abstract function handle(Created $event): void;

    /**
     * Get local user from event
     * @param Created $event
     *
     * @return UserEntity|null
     */
    public function getUserFromEvent(Created $event): ?UserEntity {
        $email = $event->getModel()->getAttribute($this->emailAttribute);
        return User::where('email', '=', $email)->first();
    }

}
// @codeCoverageIgnoreEnd