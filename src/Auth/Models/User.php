<?php
namespace PatrykNamyslak\Auth\Models;

use Exception;
use PatrykNamyslak\Auth\Enums\UserStatus;
use PatrykNamyslak\Patbase;

class User{

    private static Patbase $db;
    private static string $table;

    private(set) ?UserStatus $status;

    private function __construct(
        protected string $userID, 
        protected string $username, 
        protected string $email, 
        private(set) bool $verified, 
        ?string $status = NULL,
        ){
            $this->status = UserStatus::tryFrom($status);
        }

    public function isUnrestricted(): bool{
        return $this->status === UserStatus::ACTIVE->value;
    }

    public function isBanned(): bool{
        return $this->status === UserStatus::BANNED->value;
    }
    public function isLocked(): bool{
        return $this->status === UserStatus::LOCKED->value;
    }


    private static function columnsToFetch(): string{
        return "`unique_id`,`email`,`username`, `verified`, `status`";
    }

    public static function get(string $query, array $params): self|null{
        try{
            $userData = self::$db->prepare($query, $params)->fetch();
            if ($userData){
                return new self(
                    $userData["unique_id"], 
                    $userData["username"], 
                    $userData["email"], 
                    $userData["verified"], 
                    $userData["status"], 
                );
            }else{
                return null;
            }
        }catch (Exception $e){
            // echo $e;
            return null;
        }
    }



    /**
     * Fetch by `UNIQUE ID` `NOT` the `AUTO_INCREMENT ID`!
     * @return User|null
     */
    public static function getByID(string $id):self|null{
        $userDataToFetch = self::columnsToFetch();
        $params = [":uniqueID" => $id];
        $query = "SELECT {$userDataToFetch} FROM `" . self::$table . "` WHERE `unique_id`=:uniqueID";
        return self::get($query, $params);
    }
    public static function getByEmail(string $email):self|null{
        $userDataToFetch = self::columnsToFetch();
        $params = [":email" => $email];
        $query = "SELECT {$userDataToFetch} FROM `" . self::$table . "` WHERE `email`=:email";
        return self::get($query, $params);
    }
    public static function getByUsername(string $username):self|null{
        $userDataToFetch = self::columnsToFetch();
        $params = [":username" => $username];
        $query = "SELECT {$userDataToFetch} FROM `" . self::$table . "` WHERE `username`=:username";
        return self::get($query, $params);
    }
 
    public function isVerified(){
        return $this->verified;
    }

    public function passwordHash(): string{
        $query = "SELECT `password` FROM " . self::$table . " WHERE username=:username;";
        return self::$db->prepare($query, [":username" => $this->username])->fetch();
    }


    /**
     * Helper method that injects the required dependencies
     * @param Patbase $db
     * @param string $table This is the users table name
     * @return void
     */
    public static function init(Patbase $db, string $table){
        self::$db = $db;
        self::$table = $table;
    }

    /** Sets the database object also known as a database communication interface (DCI) */
    public static function dci(Patbase $db): void{
        self::$db = $db;
    }
    /** Sets the database object also known as a database communication interface (DCI) */
    public static function db(Patbase $db): void{
        self::dci($db);
    }
    public static function table(string $table): void{
        self::$table = $table;
    }

    public static function generateUserID(int $length): string{
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $unique = false;
        while(!$unique){
            $string = '';
            for ($i = 0; $i < $length; $i++) {
                $string .= $characters[random_int(0, strlen($characters) - 1)];
            }
            if (!User::getByID($string)){
                $unique = true;
            }
        }
        return $string;
    }
    // END OF STATIC METHODS
}