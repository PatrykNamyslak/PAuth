<?php
namespace PatrykNamyslak\Auth\Controllers;

use PatrykNamyslak\Auth\Enums\AuthMode;
use PatrykNamyslak\Auth\AuthService as Auth;
use PatrykNamyslak\Auth\Components\Forms\RegisterForm;
use PatrykNamyslak\Auth\Core;
use PatrykNamyslak\Auth\Enums\HttpErrorResponseCode;
use PatrykNamyslak\Auth\Enums\HttpResponseCode;
use PatrykNamyslak\Auth\Events\Dispatcher;
use PatrykNamyslak\Auth\Events\LoginFailed;
use PatrykNamyslak\Auth\Models\User;
use PatrykNamyslak\Auth\Http\Response;

class RegisterController{
    use Core;

    private(set) RegisterForm $form;
    private static ?Dispatcher $Dispatcher;
    private array $usersTableStructure;

    function __construct(private Auth $authService, RegisterForm $registerForm){
        $this->usersTableStructure = $registerForm->tableStructure;
        $filteredTableStructure = Auth::filterTableStructure(tableStructure: $registerForm->tableStructure, for: AuthMode::REGISTER);
        $registerForm->onlyUse($filteredTableStructure);
        $this->form = $registerForm;
        User::init($this->authService->db, $this->authService->usersTable);
    }

    public function setDispatcher(Dispatcher $dispatcher = Dispatcher::class){
        self::$Dispatcher = new $dispatcher;
    }
    protected function subscribeToEventListeners(){
        self::$Dispatcher->subscribe("login.failed", function(LoginFailed $e) {
            echo "Login Failed!";
        });
    }

    /**
     * Checks whether the form that is being used expects a HTML response. I.e if it is using HTMX
     * @return bool
     */
    public function expectsHTMLResponse(){
        return $this->form->htmx;
    }

    protected function beforeStore(array $requestData){
        $response = new Response;
        // Response errors
        $errors = [];

        if (!$this->form->validateCSRFToken($requestData["csrf_token"])){
            $errors[] = $this->form::INVALID_CSRF;
        }
        // Check if the user exists already
        if (User::getByEmail($requestData["email"])){
            $errors[] = "Email is already in use.";
        }
        if (User::getByUsername($requestData["username"])){
            $errors[] = "Username is already in use.";
        }
        // Store the message in the response
        if ($errors){
            $response = match(true){
                $this->expectsHTMLResponse() => $response->setErrorMessages($errors),
                default => $response->json(["errors" => $errors]),
            };
        }

        if ($response->isUnsuccessful()){
            $response->handleErrors($errors, $this->expectsHTMLResponse());
            exit;
        }else{
            unset($requestData["csrf_token"]);
            $credentials = $requestData;
            return $credentials;
        }
    }

    protected function beforeStoreOnFailure(array $credentials){
        return match (true){
            !$credentials and $this->expectsHTMLResponse() => $this->attemptFailed(),
            !$credentials => $this->attemptFailed(json: true),
            default => null,
        };
    }

    protected function store(array $credentials): bool{
        $query = "INSERT INTO `" . $this->authService->usersTable . "` (unique_id, email, username, password) VALUES(:uniqueID,:email,:username,:password);";
        $credentials["uniqueID"] = User::generateUserID($_ENV["USER_UNIQUE_ID_LENGTH"]);
        $credentials["password"] = password_hash($credentials["password"], PASSWORD_DEFAULT);
        $this->authService->db->prepare($query, $credentials)->execute();
        return User::getByUsername($credentials["username"]) instanceof User;
    }
    protected function afterStore(array $credentials, bool $success){
        $message = match($success){
            true => "Account created!",
            false => "Something went wrong when creating your account.",
        };
        echo $message;
    }

    public function attempt(array $requestData){
        $credentials = $this->beforeStore($requestData);
        $this->beforeStoreOnFailure($credentials);
        $success = $this->store($credentials);
        $this->afterStore($credentials, $success);
    }

    public function form(string $title = "Login"): void{
        $this->form->render($title);
    }
}