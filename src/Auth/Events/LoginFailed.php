<?php
namespace PatrykNamyslak\Auth\Events;

class LoginFailed extends Event{

    public function getName(){
        return "login.failed";
    }
}