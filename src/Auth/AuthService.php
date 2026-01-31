<?php
namespace PatrykNamyslak\Auth;

use Exception;
use InvalidArgumentException;
use PatrykNamyslak\Auth\Enums\AuthMode;
use PatrykNamyslak\Auth\Models\User;
use PatrykNamyslak\Auth\Traits\DefaultLoginMode;
use PatrykNamyslak\Patbase;
use Dotenv\Dotenv;

class AuthService{

    use DefaultLoginMode;

    private(set) array $usersTableStructure;


    public function __construct(public Patbase $db, private(set) string $usersTable){
        User::init($db, $this->usersTable);
        Validator::init($db);
    }

    public static function loadEnv(string $path){
        $envLoader = Dotenv::createImmutable($path);
        $envLoader->load();
    }
    
    public function setDefaultLoginMode(AuthMode $authMode): void{
        $allowedModes = [
            AuthMode::LOGIN_EMAIL,
            AuthMode::LOGIN_USERNAME,
            AuthMode::LOGIN_MAGIC_LINK,
        ];
        if (!in_array($authMode, $allowedModes)){
            throw new InvalidArgumentException("Invalid AuthMode provided for DEFAULT_LOGIN_MODE");
        }
        self::$DEFAULT_LOGIN_MODE = $authMode;
    }


    /**
     * Default login is `AuthMode::LOGIN_USERNAME` but you can change it by modifying `DEFAULT_LOGIN_MODE`
     * @param array $credentials
     * @return bool
     */
    public function login(array $credentials, ?AuthMode $loginMode = null): bool{
        $loginMode = $loginMode ?? self::$DEFAULT_LOGIN_MODE;
        return match($loginMode){
            AuthMode::LOGIN_USERNAME => $this->loginWithUsername($credentials),
            AuthMode::LOGIN_EMAIL => $this->loginWithEmail($credentials),
            AuthMode::LOGIN_MAGIC_LINK => $this->loginWithMagicLink($credentials["email"], $credentials["secret"]),
        };
    }

    public function loginWithUsername(array $credentials): bool{
        $isValid = Validator::validateUsernameLogin($credentials);
        if (!$isValid){
            return false;
        }
        $user = User::getByUsername(username: $credentials["username"]);
        if (!$user){
            return false;
        }
        if($user->isBanned() or $user->isLocked()){
            return false;
        }
        return password_verify($credentials["password"], $user->passwordHash());
    }

    /**
     * Authenticate a user using their email and password rather than their username and password as the default login function does not use emails.
     * @param array $credentials
     * @return void
     */
    public function loginWithEmail(array $credentials): bool{
        $isValid = Validator::validateEmailLogin($credentials);
        if (!$isValid){
            return false;
        }
        $user = User::getByEmail(email: $credentials["email"]);
        return password_verify($credentials["password"], $user->passwordHash());
    }

    /**
     * Login with a magic link
     * @param string $email
     * @return void
     */
    public function loginWithMagicLink(string $email, string $secret): bool{
        $isSecretValid = Validator::validateLoginDetails(["email" => $email, "secret" => $secret], AuthMode::LOGIN_MAGIC_LINK);
        if (!$isSecretValid){
            return false;
        }
        $query = "SELECT 1 FROM `magic_links` WHERE `secret` = :secret";
        return self::$db->prepare($query, [":secret" => $secret])->fetch();
    }


    public static function filterTableStructure(array $tableStructure, AuthMode $for){
        return match($for){
            AuthMode::LOGIN_EMAIL => ["username", "password"],
            AuthMode::LOGIN_USERNAME => ["email", "password"],
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