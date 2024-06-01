<?php
session_start();
include "../model/AdminModel.php";

function handleRequest() {
    $adminModel = new AdminModel();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action === 'removeUserFromEvent' && isset($_POST['event_title'], $_POST['user_id'])) {
                $eventTitle = $_POST['event_title'];
                $userId = $_POST['user_id'];
                removeUserFromEvent($adminModel, $eventTitle, $userId);
            } elseif ($action === 'removeEvent' && isset($_POST['event_title'])) {
                $eventTitle = $_POST['event_title'];
                removeEvent($adminModel, $eventTitle);
            } elseif ($action === 'removeUserById' && isset($_POST['user_id'])) {
                $userId = $_POST['user_id'];
                removeUserById($adminModel, $userId);
            } elseif ($action === 'addEvent' && isset($_POST['event_title'], $_POST['event_date'], $_POST['event_deadline'])) {
                $eventTitle = $_POST['event_title'];
                $eventDate = $_POST['event_date'];
                $eventDeadline = $_POST['event_deadline'];
                addEvent($adminModel, $eventTitle, $eventDate, $eventDeadline);
            } else {
                echo "Error: Missing or invalid POST data.";
            }
        } else {
            echo "Error: Action not specified.";
        }
    } elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['department'])) {
        $department = $_GET['department'];
        fetchUsersByDepartment($adminModel, $department);
    } else {
        echo "Error: Invalid request method.";
    }
}

function removeUserFromEvent($adminModel, $eventTitle, $userId) {
    // Call the method from the model to remove the user from the event
    $success = $adminModel->removeUserFromEvent($eventTitle, $userId);
    if ($success) {
        echo "User removed successfully from the event.";
    } else {
        echo "Error: Unable to remove user from the event.";
    }
}

function removeEvent($adminModel, $eventTitle) {
    if (isset($_SESSION['id']) && isset($_SESSION['user_name']) && $_SESSION['user_type'] === 'admin') {
        $success = $adminModel->removeEvent($eventTitle);
        echo $success ? "Event removed successfully." : "Error: Unable to remove event.";
    } else {
        echo "Error: Unauthorized access.";
    }
}

function removeUserById($adminModel, $userId) {
    if (isset($_SESSION['id']) && isset($_SESSION['user_name']) && $_SESSION['user_type'] === 'admin') {
        $success = $adminModel->removeUserById($userId);
        echo $success ? "User deleted successfully." : "Error: Could not delete user.";
    } else {
        echo "Error: Unauthorized access.";
    }
}

function fetchUsersByDepartment($adminModel, $department) {
    $users = $adminModel->getUsersByDepartment($department);
    foreach ($users as $user) {
        echo '<li>' . htmlspecialchars($user['user_name']) . ' - ' . htmlspecialchars($user['section']) . ' - ' . htmlspecialchars($user['department']);
        echo ' <button class="remove-btn" onclick="removeUserFromDepartment(' . $user['id'] . ')">Remove</button></li>';
    }
}

function addEvent($adminModel, $eventTitle, $eventDate, $eventDeadline) {
    if (isset($_SESSION['id']) && isset($_SESSION['user_name']) && $_SESSION['user_type'] === 'admin') {
        $success = $adminModel->addEvent($eventTitle, $eventDate, $eventDeadline);
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

handleRequest();
?>
