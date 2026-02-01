<?php
namespace PatrykNamyslak\Auth\Blueprints;

abstract class Listener{
    abstract public function handle(Event $event): void;
}