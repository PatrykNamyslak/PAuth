<?php
namespace PatrykNamyslak\Auth\Listeners;

use PatrykNamyslak\Auth\Blueprints\Event;
use PatrykNamyslak\Auth\Blueprints\Listener;

class LogLoginFailure extends Listener{

    
    public function handle(Event $event): void{
        // Handles Login failure
    }
}