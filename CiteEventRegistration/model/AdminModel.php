<?php
include "../database/db_conn.php";

class AdminModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function removeUserFromEvent($eventTitle, $userId) {
        $eventTitle = mysqli_real_escape_string($this->conn, $eventTitle);
        $userId = mysqli_real_escape_string($this->conn, $userId);
        
        $delete_sql = "DELETE FROM registrations WHERE event_title = ? AND user_id = ?";
        $stmt = mysqli_prepare($this->conn, $delete_sql);
        
        if (!$stmt) {
            error_log("Error preparing statement: " . mysqli_error($this->conn));
            return false;
        }
        
        mysqli_stmt_bind_param($stmt, "ss", $eventTitle, $userId);
        if (!mysqli_stmt_execute($stmt)) {
            error_log("Error executing statement: " . mysqli_stmt_error($stmt));
            return false;
        }
        
        mysqli_stmt_close($stmt);
        return true;
    }

    

    public function removeEvent($eventTitle) {
        $success = false;
        $delete_event_sql = "DELETE FROM events WHERE event_title = ?";
        $stmt_event = mysqli_prepare($this->conn, $delete_event_sql);
        mysqli_stmt_bind_param($stmt_event, "s", $eventTitle);

        $delete_registrations_sql = "DELETE FROM registrations WHERE event_title = ?";
        $stmt_registrations = mysqli_prepare($this->conn, $delete_registrations_sql);
        mysqli_stmt_bind_param($stmt_registrations, "s", $eventTitle);

        mysqli_begin_transaction($this->conn);
        mysqli_stmt_execute($stmt_event);
        mysqli_stmt_execute($stmt_registrations);

        if (mysqli_commit($this->conn)) {
            $success = true;
        } else {
            mysqli_rollback($this->conn);
            echo "Error: Unable to remove event.";
        }

        mysqli_stmt_close($stmt_event);
        mysqli_stmt_close($stmt_registrations);
        return $success;
    }

    public function getUsersByDepartment($department) {
        $sql = "SELECT * FROM users WHERE department=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $department);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $users;
    }

    public function removeUserById($userId) {
        $success = false;
        $sql = "DELETE FROM users WHERE id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
        } else {
            echo "Error: " . mysqli_error($this->conn);
        }
        mysqli_stmt_close($stmt);
        return $success;
    }

    public function addEvent($eventTitle, $eventDate, $eventDeadline) {
        $success = false;
        $insert_sql = "INSERT INTO events (event_title, date, deadline) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $insert_sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $eventTitle, $eventDate, $eventDeadline);
            if (mysqli_stmt_execute($stmt)) {
                $success = true;
            } else {
                echo "Error: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        return $success;
    }


}
?>
