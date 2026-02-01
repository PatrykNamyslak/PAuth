<?php
namespace PatrykNamyslak\Auth\Events;

use PatrykNamyslak\Auth\Blueprints\Event;


/**
 * Dispatches upon event triggers, i.e login.failed will fire 
 */
class Dispatcher{

    private array $listeners = [];

    public function subscribe(string $eventClassName, callable $listener): void{
        $this->listeners[$eventClassName][] = $listener;
    }

    public function dispatch(Event $event): void{
        $eventClass = get_class($event);
        if (!isset($this->listeners[$eventClass])){
            return;
        }
        foreach ($this->listeners[$eventClass] as $listener){
            $listener($event);
        }
    }
}