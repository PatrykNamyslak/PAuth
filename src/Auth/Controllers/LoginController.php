<?php
namespace PatrykNamyslak\Auth\Controllers;

use Exception;
use PatrykNamyslak\Auth\AuthService as Auth;
use PatrykNamyslak\Auth\Components\Forms\LoginForm;
use PatrykNamyslak\Auth\Core;
use PatrykNamyslak\Auth\Enums\AuthMode;
use PatrykNamyslak\Auth\Traits\DefaultLoginMode;


class LoginController{

    use DefaultLoginMode;
    use Core;

    private(set) LoginForm $form;
    private array $usersTableStructure;
    private(set) AuthMode $loginMode;

    public function __construct(public Auth $authService, LoginForm $loginForm){
        $this->usersTableStructure = $loginForm->tableStructure;
        $filteredTableStructure = Auth::filterTableStructure(tableStructure: $loginForm->tableStructure, for: self::$DEFAULT_LOGIN_MODE);
        $loginForm->onlyUse($filteredTableStructure);
        $this->form = $loginForm;
    }

    protected function beforeLogin(array $credentials){
        // ...
        return $credentials;
    }
    protected function login(array $credentials, AuthMode $mode){
        return $this->authService->login($credentials, loginMode: $mode);
    }
    protected function afterLogin(array $credentials, bool $success): void{
        // ...
    }
    public function attempt(array $data): bool{
        $loginModeUsed = $this->getLoginMode($data["login_mode"]);
        if (!$loginModeUsed){
            throw new Exception("Invalid login mode detected in the form field.");
        }
        unset($data["login_mode"]);
        $credentials = $data;
        $this->beforeLogin($credentials);
        $success = $this->login($credentials, mode: $loginModeUsed);
        $this->afterLogin($credentials, $success);
        // Check if the form expects a htmx response
        if ($this->form->htmx){
            echo "Loggin in";
            $this->redirect("/");
        }else{
            return $success;
        }
    }

    private function getLoginMode(string $loginMode){
        return AuthMode::tryFrom($loginMode);
    }
    

    public function form(string $title = "Login"): void{
        $this->form->render($title);
    }
}