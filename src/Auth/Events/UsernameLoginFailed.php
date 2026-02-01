<?php
namespace PatrykNamyslak\Auth\Events;

use PatrykNamyslak\Auth\Blueprints\Event;


/**
 * Fired when a username-based login attempt fails
 */
class UsernameLoginFailed extends Event{
    /**
     * @param string $username The username that failed to login
     * @param string $ip The IP address of the machine that failed to login
     */
    public function __construct(
        public readonly string $username,
        public readonly string $ip,
        ) {
            parent::__construct();
        }
}