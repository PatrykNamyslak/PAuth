<?php
namespace PatrykNamyslak\Auth;
use PatrykNamyslak\Auth\Blueprints\ServiceProvider;

// Events
use PatrykNamyslak\Auth\Events\UsernameLoginFailed;

// Listeners
use PatrykNamyslak\Auth\Listeners\IncrementFailedLoginAttempts;

class AuthServiceProvider extends ServiceProvider{
    protected array $listen = [
        UsernameLoginFailed::class => [
            IncrementFailedLoginAttempts::class,
            
        ]
    ];
}