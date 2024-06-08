<?php
session_start();
include "../database/db_conn.php";
include "../model/AdminModel.php";

class AdminController {
    private $adminModel;

    public function __construct($adminModel) {
        $this->adminModel = $adminModel;
    }

    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['action'])) {
                $action = $_POST['action'];

                switch ($action) {
                    case 'removeUserFromEvent':
                        if (isset($_POST['event_title'], $_POST['user_id'])) {
                            $eventTitle = $_POST['event_title'];
                            $userId = $_POST['user_id'];
                            $this->removeUserFromEvent($eventTitle, $userId);
                        } else {
                            echo "Error: Missing or invalid POST data.";
                        }
                        break;

                    case 'removeEvent':
                        if (isset($_POST['event_title'])) {
                            $eventTitle = $_POST['event_title'];
                            $this->removeEvent($eventTitle);
                        } else {
                            echo "Error: Missing or invalid POST data.";
                        }
                        break;

                    case 'removeUserById':
                        if (isset($_POST['user_id'])) {
                            $userId = $_POST['user_id'];
                            $this->removeUserById($userId);
                        } else {
                            echo "Error: Missing or invalid POST data.";
                        }
                        break;

                    case 'addEvent':
                        if (isset($_POST['event_title'], $_POST['event_date'], $_POST['event_deadline'])) {
                            $eventTitle = $_POST['event_title'];
                            $eventDate = $_POST['event_date'];
                            $eventDeadline = $_POST['event_deadline'];
                            $this->addEvent($eventTitle, $eventDate, $eventDeadline);
                        } else {
                            echo "Error: Missing or invalid POST data.";
                        }
                        break;

                    default:
                        echo "Error: Action not specified.";
                        break;
                }
            } else {
                echo "Error: Action not specified.";
            }
        } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (isset($_GET['department'])) {
                $department = $_GET['department'];
                $this->fetchUsersByDepartment($department);
            } elseif (isset($_GET['event'])) {
                $selected_event = $_GET['event'];
                $this->fetchRegistrationsByEvent($selected_event);
            } else {
                echo "Error: Invalid request.";
            }
        } else {
            echo "Error: Invalid request method.";
        }
    }

    private function removeUserFromEvent($eventTitle, $userId) {
        $success = $this->adminModel->removeUserFromEvent($eventTitle, $userId);
        echo $success ? "User removed successfully from the event." : "Error: Unable to remove user from the event.";
    }

    private function removeEvent($eventTitle) {
        if ($this->isAdmin()) {
            $success = $this->adminModel->removeEvent($eventTitle);
            echo $success ? "Event removed successfully." : "Error: Unable to remove event.";
        } else {
            echo "Error: Unauthorized access.";
        }
    }

    private function removeUserById($userId) {
        if ($this->isAdmin()) {
            $success = $this->adminModel->removeUserById($userId);
            echo $success ? "User deleted successfully." : "Error: Could not delete user.";
        } else {
            echo "Error: Unauthorized access.";
        }
    }

    private function fetchUsersByDepartment($department) {
        $users = $this->adminModel->getUsersByDepartment($department);
        foreach ($users as $user) {
            echo '<li>' . htmlspecialchars($user['user_name']) . ' - ' . htmlspecialchars($user['section']) . ' - ' . htmlspecialchars($user['department']);
            echo ' <button class="remove-btn" onclick="removeUserFromDepartment(' . htmlspecialchars($user['id']) . ')">Remove</button></li>';
        }
    }

    private function fetchRegistrationsByEvent($selected_event) {
        $registrations = $this->adminModel->getRegistrationsByEvent($selected_event);
        foreach ($registrations as $row) {
            echo '<li>
                    ' . htmlspecialchars($row['user_name']) . ' - 
                    ' . htmlspecialchars($row['section']) . ' - 
                    ' . htmlspecialchars($row['department']) . ' - 
                    ' . htmlspecialchars($row['registration_date']) . '
                    <button class="remove-btn" onclick="removeUser(\'' . htmlspecialchars($selected_event) . '\', \'' . htmlspecialchars($row['user_id']) . '\')">Remove</button>
                  </li>';
        }
    }

    private function addEvent($eventTitle, $eventDate, $eventDeadline) {
        if ($this->isAdmin()) {
            $success = $this->adminModel->addEvent($eventTitle, $eventDate, $eventDeadline);
            if ($success) {
                header("Location: ../view/admin.php");
                exit();
            } else {
                echo "Error: Unable to add event.";
            }
        } else {
            echo "Error: Unauthorized access.";
        }
    }

    private function isAdmin() {
        return isset($_SESSION['id']) && isset($_SESSION['user_name']) && $_SESSION['user_type'] === 'admin';
    }
}

$adminModel = new AdminModel($conn);
$controller = new AdminController($adminModel);
$controller->handleRequest();
?>
