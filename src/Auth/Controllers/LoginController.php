<?php
namespace PatrykNamyslak\Auth\Controllers;

use PatrykNamyslak\Auth\AuthMode;
use PatrykNamyslak\Auth\AuthService as Auth;
use PatrykNamyslak\Auth\Components\Forms\LoginForm;


class LoginController{

    private LoginForm $form;
    private array $usersTableStructure;

    public function __construct(public Auth $authService, LoginForm $loginForm){
        $this->usersTableStructure = $loginForm->tableStructure;
        $filteredTableStructure = Auth::filterTableStructure(tableStructure: $loginForm->tableStructure, for: AuthMode::LOGIN);
        $loginForm->onlyUse($filteredTableStructure);
        $this->form = $loginForm;
    }

    protected function beforeLogin(array $credentials){
        // ...
        return $credentials;
    }
    protected function login(array $credentials){
        return $this->authService->login($credentials);
    }
    protected function afterLogin(array $credentials, bool $success): void{
        // ...
    }
    public function index(array $credentials): bool{
        $this->beforeLogin($credentials);
        $success = $this->login($credentials);
        $this->afterLogin($credentials, $success);
        return $success;
    }
    

    public function form(string $title = "Login"): void{
        return $this->form->render($title);
    }
}