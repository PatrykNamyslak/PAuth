<?php

use PatrykNamyslak\Auth\Controllers\LoginController;
use PatrykNamyslak\Auth\Controllers\RegisterController;



ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once "vendor/autoload.php";


use PatrykNamyslak\Auth\AuthService;
use PatrykNamyslak\Auth\Components\Forms\LoginForm;
use PatrykNamyslak\Auth\Components\Forms\RegisterForm;
use PatrykNamyslak\Auth\Components\Forms\Form;


use PatrykNamyslak\FormBuilder\RequestMethod;
use PatrykNamyslak\Patbase;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = new Patbase($_ENV["DB"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);
$Auth = new AuthService($db);

$loginForm = new LoginForm($db, $_ENV["USERS_TABLE"]);
$loginForm->action("/login")->method(RequestMethod::POST)->htmx();
$loginController = new LoginController($Auth, $loginForm);



$registerForm = new RegisterForm($db, $_ENV["USERS_TABLE"]);
$registerForm->action("/register")->method(RequestMethod::POST)->htmx();
$registerController = new RegisterController($Auth, $registerForm);



switch ($_SERVER['REQUEST_METHOD']){
    case "GET":
        match(trim($_SERVER['REQUEST_URI'], "/")){
            "login" => $loginController->form(),
            "register" => $registerController->form("Register"),
            default => exit(header("location: /login")),
        };
        break;
    case "POST":
        match(trim($_SERVER['REQUEST_URI'], "/")){
            "login" => $Auth->login($_POST),
            "register" => $Auth->loginForm->submit($_POST),
        };
        break;
}

