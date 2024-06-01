<?php
include_once "../model/UserModel.php";

class HomeController {
    public function index() {
        session_start();

        if (!isset($_SESSION['id'])) {
            header("Location: ../../index.php");
            exit();
        }

        $userModel = new UserModel();
        $recentEvents = $userModel->getRecentEvents();
        $upcomingEvents = $userModel->getUpcomingEvents($_SESSION['id']);
        $registeredEvents = $userModel->getRegisteredEvents($_SESSION['id']);

        include_once "../../view/home.php";
    }

    public function registerForEvent() {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['event_title'], $_POST['user_id'], $_POST['user_name'], $_POST['section'], $_POST['department'])) {
                $eventTitle = $_POST['event_title'];
                $userId = $_POST['user_id'];
                $userName = $_POST['user_name'];
                $section = $_POST['section'];
                $department = $_POST['department'];

                $userModel = new UserModel();
                $success = $userModel->registerForEvent($eventTitle, $userId, $userName, $section, $department);

                if ($success) {
                    header("Location: ../view/home.php");
                    exit();
                } else {
                    echo "Error: Unable to register for the event.";
                }
            } else {
                echo "Error: Missing POST data.";
            }
        } else {
            echo "Error: Invalid request method.";
        }
    }

    public function downloadRegistration() {
        session_start();

        if (isset($_GET['event_title']) && isset($_SESSION['id'])) {
            $eventTitle = $_GET['event_title'];
            $userId = $_SESSION['id'];

            $userModel = new UserModel();
            $registrationInfo = $userModel->getRegistrationInfo($eventTitle, $userId);

            if ($registrationInfo) {
                $filename = "registration_info.txt";
                header('Content-Type: text/plain');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                echo "Event Title: " . htmlspecialchars($registrationInfo['event_title']) . "\n";
                echo "User ID: " . htmlspecialchars($registrationInfo['user_id']) . "\n";
                echo "User Name: " . htmlspecialchars($registrationInfo['user_name']) . "\n";
                echo "Section: " . htmlspecialchars($registrationInfo['section']) . "\n";
                echo "Department: " . htmlspecialchars($registrationInfo['department']) . "\n";
                echo "Registration Date: " . htmlspecialchars($registrationInfo['registration_date']) . "\n";
            } else {
                echo "No registration found.";
            }
            exit();
        } else {
            echo "Invalid request.";
            exit();
        }
    }
}

// Route to handle requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] === 'registerForEvent') {
        $homeController = new HomeController();
        $homeController->registerForEvent();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    if ($_GET['action'] === 'downloadRegistration') {
        $homeController = new HomeController();
        $homeController->downloadRegistration();
    } else {
        $homeController = new HomeController();
        $homeController->index();
    }
} else {
    $homeController = new HomeController();
    $homeController->index();
}



// Usage
$homeController = new HomeController();
$homeController->index();
?>
