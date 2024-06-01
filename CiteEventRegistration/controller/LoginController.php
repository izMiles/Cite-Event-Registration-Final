<?php
include_once "model/UserModel.php";

class LoginController {
    public function handleRequest() {
        // Check if the login form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'login') {
            $this->login();
        } else {
            // Display the login form
            include_once "view/login.php";
        }
    }

    private function login() {
        // Validate username and password
        $username = isset($_POST["uname"]) ? $_POST["uname"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";

        if (empty($username) || empty($password)) {
            // Redirect back to login page with error message if username or password is empty
            header("Location: ../index.php?error=Username and password are required.");
            exit();
        } else {
            // Create an instance of the UserModel and attempt login
            $userModel = new UserModel();
            $userModel->login($username, $password);
        }
    }
}
?>
