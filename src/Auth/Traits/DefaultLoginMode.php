<?php
namespace PatrykNamyslak\Auth\Traits;

use Exception;
use PatrykNamyslak\Auth\Enums\AuthMode;

trait DefaultLoginMode{
    public static AuthMode $DEFAULT_LOGIN_MODE = AuthMode::LOGIN_USERNAME;

    public function set(AuthMode $authMode): void{
        $allowedModes = [
            AuthMode::LOGIN_EMAIL,
            AuthMode::LOGIN_USERNAME,
            AuthMode::LOGIN_MAGIC_LINK,
        ];
        if (!in_array($authMode, $allowedModes)){
            throw new Exception("Invalid AuthMode provided for DEFAULT_LOGIN_MODE");
        }
        self::$DEFAULT_LOGIN_MODE = $authMode;
    }
}