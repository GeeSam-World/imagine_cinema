<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Index</title>
</head>
<body>
    <!-- navbar -->
    <div class="whole-container">
        <div class="desktop-navigation">

            <div class="header">
                <!-- <div class="logo">Beauty Contest Voting System</div> -->
                <div class="logo"><img src="image/logos.jpg" alt=""></div>
                <h2>Miss  Legacy Africa <br>
                    <span class="sub_h">Election system</span>
                </h2>
            </div>

            <!-- <div class="fav-cont">
                <p>Vote for your favourite contestant</p>
            </div> -->

            <div class="navbar">
                <ul>
                    <li><a href="report.php">View Report</a></li>
                </ul>
            </div>
            
        </div>
        <div class="navigations">

            <div class="header">
                <div class="logo">MLA</div>
                <div class=" icon-menu"><img id="openmenu" src="./image/icon-menu.svg" alt=""></div>
            </div>
            <div class="navbars" id="navbars">
                <ul>
                    <li><a href="javascript:void(0)"><img id="closemenu" class="close-menu" src="./image/icon-close-menu.svg" alt=""></a></li>
                    <li><a href="report.php">View Report</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="fav-contestant">
        <p>Vote For Your Favourite Contestant.</p>


        <div class="msg">

         <marquee behavior="" direction="left">To vote, click on your preferred constestant picture.</marquee>
        <?php
            // Include database connection
            include_once('db_connection.php');

            // Check if form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieve form data
                $pictureId = $_POST['picture_id'];
                $numVotes = $_POST['num_votes'];
                $userName = $_POST['user_name'];
                $proofOfPayment = $_FILES['proof_of_payment']['name']; // File name of proof of payment

                // File upload path
                $targetDir = "proof_of_payment/";
                $targetFilePath = $targetDir . basename($proofOfPayment);

                // Check if file is selected
                if (!empty($proofOfPayment)) {
                    // Upload file to server
                    if (move_uploaded_file($_FILES["proof_of_payment"]["tmp_name"], $targetFilePath)) {
                        // Insert vote data into database with status 'pending' and initial number of votes
                        $query = "INSERT INTO votes (picture_id, user_name,  proof_of_payment, pending_num_votes, status) VALUES ('$pictureId', '$userName',  '$proofOfPayment', '$numVotes', 'pending')";
                        $result = mysqli_query($conn, $query);

                        if ($result) {
                            echo "Vote submitted successfully. It will be pending until approved by admin.";
                            header('location:report.php');
                        } else {
                            echo "Error submitting vote.";
                        }
                    } else {
                        echo "Error uploading proof of payment.";
                    }
                } else {
                    echo "Proof of payment is required.";
                }
            }

        ?>
    </div>
        
    </div>
    <!-- navbar -->

    <!-- pic card-->

    <div class="card-container">
        <?php
            // Include database connection
            include_once('db_connection.php');

            // Display pictures for voting
            $query = "SELECT * FROM pictures";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='card'>";
                echo "<div class='card-image'>";
                echo "<img src='". $row['filename'] . "' alt='" . $row['name'] . "' style='width:100%' onclick='showPayment(\"{$row['id']}\", \"{$row['name']}\")'>";
                echo "</div>";
                echo "<div class='container'>";
                echo "<p class = 'dsec-name'> " . $row['name'] . "</p>";
                echo "</div>";
                echo "</div>";
            }
        ?>
    </div>


     <!-- Pop-up modal for account details and payment -->
     <div id="myModal" class="modal">

        <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
            <h2>Payment Details:<br> 8062381814<br>Moniepoint<br>Christel Chimuamanda</h2>

            <form action="" method="post" enctype="multipart/form-data">
                <input class="gen-width" type='hidden' id='picture_id' name='picture_id' value=''>

                <input class="gen-width" type='hidden' id='amount_per_vote' name='amount_per_vote' value='100'>
                <!-- Amount to pay per vote -->
                <p id='contestant-name'></p>
                
                <p>Total Votes:
                    <select class="gen-width" id='num_votes' name='num_votes' onchange="calculateTotalAmount()">
                    </select>
                </p>

                <p>Total Amount to Pay: <span id='total_amount'></span>
                </p>
                <label for="proof">Upload proof of payment</label>
                <input class="gen-width" type="file" name="proof_of_payment" required id="proof">

                <input class="gen-width" type="text" name="user_name" placeholder="Your Name" required><br>
                
                <button class="butt-width" type='submit'>Submit</button>
            </form>
        </div>
    </div>

    <script>
        //    script for navbar
        var menu = document.getElementById("openmenu");
        var navbar = document.getElementById("navbars");
        menu.addEventListener("click", function(){
            navbar.style.width = "240px";
        })

        var close = document.getElementById("closemenu");
        var navbar = document.getElementById("navbars");
        close.addEventListener("click", function(){
            navbar.style.width = "0px"
        })


        // Function to display modal with payment details
        function showPayment(pictureId, name) {
        var modal = document.getElementById("myModal");
        modal.style.display = "flex";
        modal.style.height = "50%";
        modal.style.alignItems = "center";
        document.getElementById('picture_id').value = pictureId;
        document.getElementById('contestant-name').innerText = "Name Of Contestant: " + name;

        // Dynamically populate the number of votes select dropdown
        var selectVotes = document.getElementById('num_votes');
        selectVotes.innerHTML = '';
        for (var i = 10; i <= 1000; i++) { // Maximum 10 votes per submission (can be adjusted)
            var option = document.createElement('option');
            option.value = i;
            option.text = i;
            selectVotes.appendChild(option);
        }

        // Calculate total amount based on the number of votes selected
        calculateTotalAmount();
        }

        // Function to calculate total amount based on the number of votes selected
        function calculateTotalAmount() {
            var amountPerVote = parseFloat(document.getElementById('amount_per_vote').value);
            var numVotes = parseInt(document.getElementById('num_votes').value);
            var totalAmount = amountPerVote * numVotes;
            document.getElementById('total_amount').innerText = totalAmount.toFixed(2) + ' Naira'; // Assuming currency is USD
        }

        // Function to close modal
        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }




    </script>
</body>
</html>