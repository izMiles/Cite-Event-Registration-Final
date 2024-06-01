<?php
include __DIR__ . "/../database/db_conn.php";

class UserModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function login($username, $password) {
        $uname = $this->validate($username);
        $pass = $this->validate($password);
    
        if (empty($uname)) {
            header("Location: index.php?error=User Name is required");
            exit();
        } elseif (empty($pass)) {
            header("Location: index.php?error=Password is required");
            exit();
        } else {
            $pass = md5($pass);
    
            $sql = "SELECT * FROM users WHERE user_name='$uname' AND password='$pass'";
            $result = mysqli_query($this->conn, $sql);
    
            if (!$result) {
                header("Location: index.php?error=Database error: " . mysqli_error($this->conn));
                exit();
            }
    
            // Check if any row is returned
            if (mysqli_num_rows($result) === 1) {
                // Fetch the user data
                $row = mysqli_fetch_assoc($result);
                // Start session and set session variables
                session_start();
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['user_type'] = $row['user_type'];
                
                // Redirect based on user type
                if ($row['user_type'] === 'admin') {
                    header("Location: view/admin.php");
                    exit();
                } else {
                    header("Location: view/home.php");
                    exit();
                }
            } else {
                header("Location: index.php?error=Incorrect User name or password");
                exit();
            }
        }
    }

   
    }
    ?>
    
