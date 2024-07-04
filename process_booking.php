<?php
session_start();
include_once('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["movieId"]) && isset($_POST["userEmail"]) && isset($_POST["seats"]) && isset($_POST["movieName"])) {
    // Sanitize inputs
    $movieId = mysqli_real_escape_string($conn, $_POST['movieId']);
    $userName = $_SESSION['username']; // Retrieve username from session
    $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $seats = intval($_POST['seats']);
    $movieName = mysqli_real_escape_string($conn, $_POST['movieName']); // Retrieve movie name from form

    // Check available seats
    $query = "SELECT total_seats FROM movie WHERE movie_id = '$movieId'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $availableSeats = $row['total_seats'];

        if ($seats > $availableSeats) {
            echo "Error: Not enough available seats. Redirecting back to homepage...";
            header("refresh:3;url=homepage.php");
            exit;
        } else {
            // Proceed with booking
            $bookingTime = date('Y-m-d H:i:s');
            $query = "INSERT INTO bookings (movie_id, username, user_email, seats, booking_time, movie_name) 
                      VALUES ('$movieId', '$userName', '$userEmail', '$seats', '$bookingTime', '$movieName')";

            if (mysqli_query($conn, $query)) {
                // Booking successfully inserted, generate ticket
                $bookingId = mysqli_insert_id($conn); // Get the auto-generated booking ID
                generateTicket($bookingId, $movieName, $userName, $seats, $userEmail, $bookingTime); // Call function to generate ticket

                // Update available seats in movie table
                $updateQuery = "UPDATE movie SET total_seats = total_seats - $seats WHERE movie_id = '$movieId'";
                mysqli_query($conn, $updateQuery);

                // Redirect to homepage after a short delay
                header("refresh:3;url=homepage.php");
                echo "Booking successful! Redirecting to homepage in 3 seconds...";
            } else {
                // Handle database insertion error
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Error: Movie not found. Redirecting back to homepage...";
        header("refresh:3;url=homepage.php");
        exit;
    }
} else {
    // Redirect or handle if accessed directly without POST or missing parameters
    header("location: homepage.php");
    exit;
}

// Function to generate ticket
function generateTicket($bookingId, $movieName, $userName, $seats, $userEmail, $bookingTime) {
    // Generate ticket content
    $ticketContent = "Booking ID: " . $bookingId . "\n";
    $ticketContent .= "Movie Name: " . $movieName . "\n";
    $ticketContent .= "Date: " . $bookingTime . "\n";
    $ticketContent .= "Username: " . $userName . "\n";
    $ticketContent .= "Number of Seats: " . $seats . "\n";
    $ticketContent .= "Email: " . $userEmail . "\n";

    // Check if tickets directory exists, if not create it
    $ticketsDir = 'tickets';
    if (!is_dir($ticketsDir)) {
        mkdir($ticketsDir, 0777, true);
    }

    // Save ticket to file
    $filename = $ticketsDir . '/booking_' . $bookingId . '.txt';
    file_put_contents($filename, $ticketContent);

    // Provide a link to download the ticket
    echo "<p>Your ticket is ready! <a href='$filename' download>Download Ticket</a></p>";
}
?>
