<?php
namespace PatrykNamyslak\Auth;

use Exception;
use PatrykNamyslak\Patbase;

include_once 'environment_variables.php';

class AuthService{
    private string $usersTable;
    private(set) array $usersTableStructure;

    public function __construct(protected Patbase $db){}


    public function login(array $credentials): bool{
        $result = false;
        $isValid = $this->credentialValidation($credentials);
        $user = User::get(username: $credentials["username"]);
        if ($isValid and $user){
            $result = password_verify($credentials["password"], $user->passwordHash());
        }
        return $result;
    }

    /**
     * Authenticate a user using their email and password rather than their username and password as the default login function does not use emails.
     * @param array $credentials
     * @return void
     */
    public function loginWithEmail(array $credentials): bool{}

    /**
     * Login with a magic link
     * @param string $email
     * @return void
     */
    public function loginWithMagicLink(string $email): bool{}


    public static function filterTableStructure(array $tableStructure, AuthMode $for){
        return match($for){
            AuthMode::LOGIN => ["username", "password"],
            AuthMode::REGISTER => ["email", "username", "password"],
        };
    }

    /**
     * Use this to set properties
     * @param mixed $property
     * @param mixed $args[0] The value passed in the function, i.e $Auth->usersTable("users") $args[0] = "users"
     * @return static
     */
    public function __call($property, $args){
        $this->$property = $args[0];
        return $this;
    }
}

?>