<?php
session_start();
include_once('db.php');

$bookingId = $_GET['booking_id'];

$query = "SELECT * FROM bookings WHERE id = $bookingId";
$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if ($booking) {
    $movieName = $booking['movie_name'];
    $userName = $booking['username'];
    $userEmail = $booking['user_email'];
    $seats = $booking['seats'];
    $bookingTime = $booking['booking_time'];

    // Generate ticket (for example, as an HTML page or PDF)
    echo "
    <html>
    <head><title>Ticket</title></head>
    <body>
        <h1>Ticket for $movieName</h1>
        <p>Name: $userName</p>
        <p>Email: $userEmail</p>
        <p>Number of Seats: $seats</p>
        <p>Booking Time: $bookingTime</p>
    </body>
    </html>
    ";
} else {
    echo "No booking found.";
}
?>
