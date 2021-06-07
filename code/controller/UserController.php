<?php

require_once("model/UserDB.php");
require_once("ViewHelper.php");

class UserController {

    public static function showLoginForm() {
        ViewHelper::render("view/user-login-form.php");
    }

    public static function login() {
        if (password_verify($_POST["password"], UserDB::validLoginAttempt($_POST["username"])["password"])) {
            $vars = [
                "username" => $_POST["username"],
                "password" => $_POST["password"]
            ];
            $_SESSION["user"] = filter_var($_POST["username"], FILTER_SANITIZE_SPECIAL_CHARS);
            $_SESSION["user_id"] = UserDB::getIdFromUsername($_SESSION["user"]);
            ViewHelper::redirect(BASE_URL . "home");
        } else {
            ViewHelper::render("view/user-login-form.php", [
                "errorMessage" => "Invalid username or password."
            ]);
        }
    }

    public static function showRegisterForm($data = [], $errors = []) {
        if (empty($data)) {
            $data = [
                "firstname" => "",
                "lastname" => "",
                "username" => "",
                "email" => "",
                "password" => "",
                "confirmpassword" => ""
            ];
        }

        if (empty($errors)) {
            foreach ($data as $key => $value) {
                $errors[$key] = "";
            }
        }

        $vars = ["user" => $data, "errors" => $errors];
        ViewHelper::render("view/user-register-form.php", $vars);
    }

    public static function register() {
        $rules = [
            "firstname" => [
                "filter" => FILTER_VALIDATE_REGEXP,
                "options" => ["regexp" => "/^[ a-zA-ZšđčćžŠĐČĆŽ\.\-]+$/"]
            ],
            "lastname" => [
                "filter" => FILTER_VALIDATE_REGEXP,
                "options" => ["regexp" => "/^[ a-zA-ZšđčćžŠĐČĆŽ\.\-]+$/"]
            ],
            "username" => FILTER_SANITIZE_SPECIAL_CHARS,
            "email" => [
                "filter" => FILTER_VALIDATE_REGEXP,
                "options" => ["regexp" => "/^[A-ZZšđčćžŠĐČĆŽ0-9._%+-]+@[A-ZZšđčćžŠĐČĆŽ0-9.-]+\.[A-ZZšđčćžŠĐČĆŽ]{2,4}$/"]
            ],
            "password" => FILTER_SANITIZE_SPECIAL_CHARS,            
            "confirmpassowrd" => FILTER_SANITIZE_SPECIAL_CHARS,
        ];

        $data = filter_input_array(INPUT_POST, $rules);
        
        $errors["firstname"] = $data["firstname"] === false ? "Only letters, dots, dashes and spaces are allowed!" : "";
        $errors["lastname"] = $data["lastname"] === false ? "Only letters, dots, dashes and spaces are allowed!" : "";
        $errors["email"] = $data["email"] === false ? "Invalid email!" : "";
        $errors["username"] = UserDB::usernameExists($_POST["username"]) === true ? "Username is taken!" : "";
        $errors["email"] = empty($errors["email"]) && UserDB::emailExists($_POST["email"]) === true ? "Email is taken!" : "";
        $errors["password"] = strlen($data["password"])<4 ? "Password needs to be at least 4 characters long!" : "";
        $errors["confirmpassword"] = $data["password"] != $data["confirmpassowrd"] ? "Paswords do not match!" : "";

        $isDataValid = true;
        foreach ($errors as $error) {
            $isDataValid = $isDataValid && empty($error);
        }

        if ($isDataValid) {
            UserDB::addNewUser($data["firstname"], $data["lastname"], $data["username"], $data["email"], password_hash($data["password"], PASSWORD_DEFAULT));
            $id = UserDB::getIdByUsername($data["username"]);
            MovieDB::init($id);
            TvShowDB::init($id);

            $ids = TvShowDB::getIds($id);
    
            $i = 1;
            foreach ($ids as $k) {
                foreach($k as $key => $value) {
                    ContentDB::init($id, $value, $i);
                    $i++;
                }
            } 
            self::showLoginForm();
        } else {
            self::showRegisterForm($data, $errors);
        }

    }

    public static function logout() {
        $_SESSION["user"] = "";
        $_SESSION["user_id"] = "";
        ViewHelper::redirect(BASE_URL . "home");
    }
}

?>
