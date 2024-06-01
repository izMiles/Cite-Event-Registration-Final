<?php
session_start();
include_once "../database/db_conn.php";
include_once "../model/UserModel.php"; // Include UserModel

$userModel = new UserModel(); // Create an instance of UserModel

// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name'])) {
    header("Location: ../index.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['id'];

// Retrieve upcoming events, passing the user ID to exclude already registered events
$upcomingEvents = $userModel->getUpcomingEvents($user_id);

// Retrieve recent events
$recentEvents = $userModel->getRecentEvents();

// Retrieve registered events
$registeredEvents = $userModel->getRegisteredEvents($user_id);
?>


<!DOCTYPE html>
<html>
<head>
    <title>HOME</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <div class="header-container">
        <div class="userlogo"><img src="../assets/img/user.png" alt="User Logo"></div>
        <div class="homeheader"><?php echo $_SESSION['user_name']; ?></div>
    </div>
    <a href="../index.php" class="logoutbtn">Logout</a>

    <h1 class="hometitle">CITE Events Registration</h1>

    <div class="main-display" id="main-display">
        <!-- This area will be updated with the registration form via JavaScript -->
    </div>

    <div class="recent-events">
        <h2>Recent Events</h2>
        <ul>
            <?php foreach ($recentEvents as $event): ?>
                <li>
                    <?php echo htmlspecialchars($event['event_title']); ?> - <?php echo htmlspecialchars($event['date']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="upcoming-events" id="upcoming-events">
        <h2>Upcoming Events</h2>
        <ul>
            <?php foreach ($upcomingEvents as $event): ?>
                <li>
                    <?php echo htmlspecialchars($event['event_title']); ?> - <?php echo htmlspecialchars($event['date']); ?>
                    <button onclick="showRegistrationForm('<?php echo $event['event_title']; ?>')">Register</button>
                </li>
            <?php endforeach; ?>
            <?php if (empty($upcomingEvents)): ?>
                <li>No upcoming events.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="registered-events">
    <h2>Registered Events</h2>
    <ul>
        <?php foreach ($registeredEvents as $event): ?>
            <li>
                <?php echo htmlspecialchars($event['event_title']); ?>
                <a href="../controller/HomeController.php?action=downloadRegistration&event_title=<?php echo urlencode($event['event_title']); ?>" class="downloadbtn">Download</a>
                <span class="registration-date"><?php echo htmlspecialchars($event['registration_date']); ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>


    <script>
        function showRegistrationForm(eventTitle) {
    const mainDisplay = document.getElementById('main-display');
    mainDisplay.innerHTML = `
        <h2>Register for ${eventTitle}</h2>
        <form method="post" action="../controller/HomeController.php">
            <input type="hidden" name="action" value="registerForEvent">
            <input type="hidden" name="event_title" value="${eventTitle}">
            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" value="<?php echo $_SESSION['id']; ?>" readonly required>
            <label for="user_name">Confirm Full Name:</label>
            <input type="text" id="user_name" name="user_name" required>
            <label for="section">Confirm Section:</label>
            <input type="text" id="section" name="section" required>
            <label for="department">Confirm Department:</label>
            <select id="department" name="department" class="styled-select" required>
                <option value="Electrical Department">Electrical Department</option>
                <option value="Electronics Department">Electronics Department</option>
                <option value="Mechanical Department">Mechanical Department</option>
                <option value="Computer Department">Computer Department</option>
            </select>
            <button type="submit">Submit</button>
        </form>
    `;
}
    </script>

</body>
</html>
