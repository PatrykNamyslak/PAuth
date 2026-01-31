<?php
namespace PatrykNamyslak\Auth\Events;

abstract class Event{
    protected(set) float $timestamp;

    public function __construct(){
        $this->timestamp = microtime(true);
    }

    abstract public function getName();
}