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

    public function signup($name, $username, $password, $re_password, $department, $section) {
        $name = $this->validate($name);
        $uname = $this->validate($username);
        $pass = $this->validate($password);
        $re_pass = $this->validate($re_password);
        $dept = $this->validate($department);
        $sec = $this->validate($section);

        $user_data = 'uname='. $uname. '&name='. $name . '&department=' . $dept . '&section=' . $sec;

        if (empty($uname) || empty($pass) || empty($re_pass) || empty($name) || empty($dept) || empty($sec)) {
            header("Location: ../view/signup.php?error=All fields are required&$user_data");
            exit();
        } else if ($pass !== $re_pass) {
            header("Location: ../view/signup.php?error=The confirmation password does not match&$user_data");
            exit();
        } else {
            $pass = md5($pass);

            $sql = "SELECT * FROM users WHERE user_name='$uname'";
            $result = mysqli_query($this->conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                header("Location: ../view/signup.php?error=The username is taken try another&$user_data");
                exit();
            } else {
                $sql2 = "INSERT INTO users(user_name, password, name, department, section) VALUES('$uname', '$pass', '$name', '$dept', '$sec')";
                $result2 = mysqli_query($this->conn, $sql2);
                if ($result2) {
                    header("Location: ../view/signup.php?success=Your account has been created successfully");
                    exit();
                } else {
                    header("Location: ../view/signup.php?error=Unknown error occurred&$user_data");
                    exit();
                }
            }
        }
    }

   public function getUpcomingEvents($user_id) {
        $events_sql = "
            SELECT e.*
            FROM events e
            LEFT JOIN registrations r ON e.event_title = r.event_title AND r.user_id = ?
            WHERE r.user_id IS NULL AND e.date >= CURDATE()
            ORDER BY e.date ASC
        ";
        $stmt = mysqli_prepare($this->conn, $events_sql);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $events = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $events[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
        return $events;
    }   

    public function getRecentEvents() {
        $recent_events_sql = "SELECT * FROM events WHERE date < CURDATE() ORDER BY date DESC";
        $recent_events_result = mysqli_query($this->conn, $recent_events_sql);
        $recent_events = [];
        if ($recent_events_result && mysqli_num_rows($recent_events_result) > 0) {
            while ($row = mysqli_fetch_assoc($recent_events_result)) {
                $recent_events[] = $row;
            }
        }
        return $recent_events;
    }
    }
    ?>
    
