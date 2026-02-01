<?php
namespace PatrykNamyslak\Auth\Blueprints;

abstract class Event{
    public readonly float $timestamp;

    public function __construct(){
        $this->timestamp = microtime(true);
    }
}