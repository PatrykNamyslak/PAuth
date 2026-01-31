<?php
namespace PatrykNamyslak\Auth;

use PatrykNamyslak\Auth\Events\Event;

class EventDispatcher{

    private array $listeners = [];

    public function subscribe(string $eventName, callable $handler){
        $this->listeners[$eventName] = $handler;
    }

    public function dispatch(Event $event): void{
        $name = $event->getName();
        if (!isset($this->listeners[$name])){
            return;
        }
        foreach ($this->listeners[$name] as $handler){
            $handler($event);
        }
    }
}