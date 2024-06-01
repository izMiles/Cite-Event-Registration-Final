<?php
include "../database/db_conn.php";

class AdminModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
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
