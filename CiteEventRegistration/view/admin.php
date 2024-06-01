<?php
session_start();
include "../database/db_conn.php";

// Check if the user is logged in and is an admin
if (isset($_SESSION['id']) && isset($_SESSION['user_name']) && $_SESSION['user_type'] === 'admin') {
    $user_name = $_SESSION['user_name'];

    // Fetch all events from the database
    $events_sql = "SELECT * FROM events ORDER BY date ASC";
    $events_result = mysqli_query($conn, $events_sql);
    $events = [];
    if ($events_result && mysqli_num_rows($events_result) > 0) {
        while ($row = mysqli_fetch_assoc($events_result)) {
            $events[] = $row;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Events</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <div class="header-container">
        <div class="userlogo"><img src="../assets/img/user.png" alt="Admin Logo"></div>
        <div class="homeheader"><?php echo $_SESSION['user_name']; ?></div>
    </div>
    <a href="../model/logout.php" class="logoutbtn">Logout</a>

    <h1 class="hometitle">Admin Panel - Events</h1>

    <div class="event-list main-display">
        <table>
            <tr>
                <th class="table-title">Event List</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Deadline</th>
                <th>Action</th>
            </tr>
            <!-- Loop through the events and display them in the table -->
            <?php foreach ($events as $event): ?>
            <tr>
                <td><a href="?event=<?php echo urlencode($event['event_title']); ?>"><?php echo htmlspecialchars($event['event_title']); ?></a></td>
                <td><?php echo htmlspecialchars($event['date']); ?></td>
                <td><?php echo htmlspecialchars($event['deadline']); ?></td>
                <td>
                    <button class="remove-event-btn" onclick="removeEvent('<?php echo htmlspecialchars($event['event_title']); ?>')">Remove</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <button onclick="openAddEventModal()" class="add-event-btn">Add Event</button>
    </div>

    



    <script>

        // Open the modal to add a new event
        function openAddEventModal() {
            var modal = document.getElementById("addEventModal");
            modal.style.display = "block";
        }


        // Close the modal to add a new event
        function closeAddEventModal() {
            var modal = document.getElementById("addEventModal");
            modal.style.display = "none";
        }


        
    </script>
</body>
</html>

<?php 
} else {
    // Redirect to login page if the user is not an admin
    header("Location: index.php");
    exit();
}
?>
