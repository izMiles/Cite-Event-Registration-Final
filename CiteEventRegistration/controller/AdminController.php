<?php
session_start();
include "../model/AdminModel.php";

function handleRequest() {
    $adminModel = new AdminModel();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

          
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
