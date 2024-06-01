
<?php
session_start();
include "../model/userModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $username = $_POST["uname"];
    $password = $_POST["password"];
    $re_password = $_POST["re_password"];
    $department = $_POST["department"];
    $section = $_POST["section"];

    $userModel = new UserModel();
    $userModel->signup($name, $username, $password, $re_password, $department, $section);
}
?>
