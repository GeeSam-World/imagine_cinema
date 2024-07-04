<?php
session_start();
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];
    // Fetch schedule details and seats information if needed
} else {
    header("Location: browse_movies.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Seat</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <h1>Select Seat</h1>
    <div class="seats">
        <!-- Form to select seats -->
        <form method="post" action="booking_confirmation.php">
            <!-- Seats selection interface -->
            <!-- Example: checkboxes for seat selection -->
            <label for="seat1"><input type="checkbox" id="seat1" name="seats[]" value="1"> Seat 1</label>
            <label for="seat2"><input type="checkbox" id="seat2" name="seats[]" value="2"> Seat 2</label>
            <!-- Submit button -->
            <button type="submit">Confirm Booking</button>
        </form>
    </div>
</body>
</html>
