<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Imagine Cinema - Homepage</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}
.menu {
    background-color: #0E2E1D;
    color: white;
    padding: 1rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}
.header {
    background-color: #137C33;
    color: white;
    padding: 1rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
nav a {
    color: white;
    margin: 0 1rem;
    text-decoration: none;
}
nav a:hover {
  background-color: white;
  color: black;
  height: auto;
}
h1 {
    text-align: center;
    margin-bottom: 20px;
}
.movies {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    padding-top: 20px;
}
.movie-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 200px;
    overflow: hidden;
    text-align: center;
    cursor: pointer;
}
.movie-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
}
.movie-details {
    padding: 10px;
}
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}
.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    text-align: center;
}
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
}
</style>
</head>
<body>
<div class="header">
    <img src="logo.jpg" width="70" height="70">
    <div class="sub"><h1>IMAGINE CINEMA</h1></div>

<!-- <h3>Movie Ticket Reservation System</h3> -->
</div>
<div class="menu">
    <nav>
        <a href="homepage.php">Browse Movies</a>
        <a href="booking_history.php">Booking History</a>
        <a href="logout.php">Logout</a>
</nav>
</div>

<div class="movies">
    <!-- Loop through movies and display movie cards -->
    <?php
    session_start(); // Start the session
    include_once('db.php');

    $query = "SELECT * FROM movie";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Calculate available seats
            $movieId = $row['movie_id'];
            $querySeats = "SELECT total_seats FROM movie WHERE movie_id = $movieId";
            $resultSeats = mysqli_query($conn, $querySeats);
            $rowSeats = mysqli_fetch_assoc($resultSeats);
            $availableSeats = $rowSeats['total_seats'];

            $imagePath = str_replace("../", "", $row['filename']); // Adjust path as needed
            echo "<div class='movie-card' data-movie-id='" . $row['movie_id'] . "' data-movie-name='" . $row['names'] . "' data-showtime='" . $row['showtime'] . "' data-available-seats='" . $availableSeats . "'>";
            echo "<img src='" . $imagePath . "' alt='" . $row['names'] . "' class='movie-image'>";
            echo "<div class='movie-details'>";
            echo "<h2>" . $row['names'] . "</h2>";
            echo "<p>Showtime: " . $row['showtime'] . "</p>";
            echo "<p>Available Seats: " . $availableSeats . "</p>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No movies found.</p>";
    }
    ?>
</div>

<!-- Modal for booking form -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modalMovieName">Booking for: </h2>
        <form id="bookingForm" method="post" action="process_booking.php">
            <input type="hidden" id="movieId" name="movieId">
            <input type="hidden" id="availableSeats" name="availableSeats">
            <input type="hidden" id="movieName" name="movieName"> <!-- Add hidden input for movieName -->
            <?php
            $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
            ?>
            <label for="userName">Name:</label>
            <input type="text" id="userName" name="userName" value="<?php echo htmlspecialchars($username); ?>" readonly><br><br>
            <label for="userEmail">Email:</label>
            <input type="email" id="userEmail" name="userEmail" required><br><br>
            <label for="seats">Number of Seats:</label>
            <input type="number" id="seats" name="seats" min="1" required><br><br>
            <!-- Display for available seats -->
            <label for="availableSeats">Available Seats:</label>
            <span id="availableSeatsDisplay"></span><br><br>
            <button type="submit">Book</button>
            <button type="button" class="cancelBooking">Cancel</button>
        </form>
    </div>
</div>



<script>
// JavaScript to handle modal and form submission
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("bookingModal");
    var movieNameInput = document.getElementById("movieName"); // Add this line

    document.querySelectorAll('.movie-card').forEach(card => {
        card.addEventListener('click', function() {
            var movieName = this.getAttribute('data-movie-name');
            var movieId = this.getAttribute('data-movie-id');
            var availableSeats = this.getAttribute('data-available-seats');

            modal.querySelector('#modalMovieName').textContent = 'Booking for: ' + movieName;
            document.getElementById("movieId").value = movieId;
            document.getElementById("availableSeats").value = availableSeats;
            document.getElementById("availableSeatsDisplay").textContent = availableSeats;
            movieNameInput.value = movieName; // Set movieName in hidden input
            document.getElementById("seats").setAttribute('max', availableSeats);
            modal.style.display = "block";
        });
    });

    document.querySelector('.close').onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    document.querySelector('.cancelBooking').addEventListener('click', function() {
        modal.style.display = "none";
    });

    document.getElementById('seats').addEventListener('input', function() {
        var maxSeats = parseInt(this.getAttribute('max'));
        var selectedSeats = parseInt(this.value);
        if (selectedSeats > maxSeats) {
            this.setCustomValidity('Selected seats exceed available seats');
        } else {
            this.setCustomValidity('');
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("bookingModal");

    // Check if modal element is found
    console.log(modal); 

    // Check if movie cards are being selected
    var movieCards = document.querySelectorAll('.movie-card');
    console.log(movieCards);

    // Open modal when clicking on movie card
    movieCards.forEach(card => {
        card.addEventListener('click', function() {
            console.log("Movie card clicked");
            // Ensure this log message appears in the console
            modal.style.display = "block";
        });
    });
});


</script>

</body>
</html>
