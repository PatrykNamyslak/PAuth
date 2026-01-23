<?php
namespace PatrykNamyslak\Auth\Controllers;

use PatrykNamyslak\Auth\AuthMode;
use PatrykNamyslak\Auth\AuthService as Auth;
use PatrykNamyslak\Auth\Components\Forms\RegisterForm;


class RegisterController{

    private RegisterForm $form;
    private array $usersTableStructure;

    function __construct(private Auth $authService, RegisterForm $registerForm){
        $this->usersTableStructure = $registerForm->tableStructure;
        $filteredTableStructure = Auth::filterTableStructure(tableStructure: $registerForm->tableStructure, for: AuthMode::REGISTER);
        $registerForm->onlyUse($filteredTableStructure);
        $this->form = $registerForm;
    }

    protected function beforeStore(array $credentials){
        /** 
         * 1. Check if the user exists already
         * 2. Run validation on the provided data, for example check if the email is valid
         * */
        return $credentials;
    }
    protected function store(array $credentials){
        return $this->authService;
    }
    protected function afterStore(array $credentials){
        // Send a verification email for example
    }

    public function index(array $credentials){
        $this->beforeStore($credentials);
        $success = $this->store($credentials);
        $this->afterStore($credentials);
    }

    public function form(string $title = "Login"): void{
        $this->form->render($title);
    }
}