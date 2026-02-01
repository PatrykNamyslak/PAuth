<?php
namespace PatrykNamyslak\Auth;

use PatrykNamyslak\Auth\Enums\AuthMode;
use PatrykNamyslak\Auth\Interfaces\Database;
use PatrykNamyslak\Patbase;

abstract class Validator{
    private static Database $db;

    public static function init(Database $db){
        self::$db = $db;
    }

    /**
     * Used to mitigate unnecessary database hits
     * @param array $credentials
     * @param AuthMode $authMode
     * @return bool
     */
    public static function validateLoginDetails(array $credentials, AuthMode $authMode): bool{
        return match($authMode){
            AuthMode::LOGIN_USERNAME => self::validateUsernameLogin($credentials),
            AuthMode::LOGIN_EMAIL => self::validateEmailLogin($credentials),
            AuthMode::LOGIN_MAGIC_LINK => self::validateMagicLinkLogin($credentials),
        };
    }

    // Helper function to reduce boilerplate
    private static function validatePassword(string $password){
        if (strlen($password) < 8){
            return false;
        }
        return true;
    }

    public static function validateUsernameLogin(array $credentials): bool{
        $username = $credentials["username"];
        $password = $credentials["password"];
        if (strlen($username) < 8){
            return false;
        }
        return self::validatePassword($password);
    }
    public static function validateEmailLogin(array $credentials): bool{
        $email = $credentials["email"];
        $password = $credentials["password"];
        // If the email is an invalid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        return self::validatePassword($password);
    }

    /**
     * Determines whether the secret provided is of the expected format and exists in the database
     * @param array $credentials
     * @return bool
     */
    public static function validateMagicLinkLogin(array $credentials): bool{
        $secret = $credentials["secret"];
        if (strlen($secret) < $_ENV["MAGIC_LINK_SECRET_LENGTH"]){
            return false;
        }
        return true;
    }

}