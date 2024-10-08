<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
?>

<head>

    <?php include "bootstrap.php"; ?>

    <title>Read Customer</title>

    <link rel="stylesheet" href="css/styles.css" />
    
</head>

<body>
    <!-- NAVBAR -->
    <?php
    include "navbar.php";
    ?>
    <!-- NAVBAR END -->

    <main class="mt-5">

        <!-- Content Start-->
        <!-- container -->
        <div class="container">
            <div class="page-header">
                <h1>Read Customers</h1>
            </div>

            <!-- PHP code to read records will be here -->
            <?php

            if ($_GET) {
                $message = isset($_GET['message']) ? $_GET['message'] : "";
                $id = isset($_GET['id']) ? $_GET['id'] : "";

                if ($message == "update_success" && $id != "") {
                    echo "<div class='alert alert-success'>Record with <b class='fs-2'> CustomerID : $id </b> updated.</div>";
                } else if ($message == "update_success") {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else if ($message == "customer_in_use") {
                    echo "<div class='alert alert-danger'>Selected Customer founded in order (Please delete specific order before delete customer)</div>";
                } else if ($message == "deleted") { // if it was redirected from delete.php
                    echo "<div class='alert alert-success'>Record was deleted.</div>";
                } else {
                    echo "<div class='alert alert-danger align-item-center'>Unknown error happened</div>";
                }
            }

            // include database connection
            include 'config/database.php';

            // delete message prompt will be here

            // select all data
            $query = "SELECT CustomerID, username, password, customer_image, first_name, last_name, gender, date_of_birth, registration_date_time, account_status FROM customers ORDER BY CustomerID ASC";
            $stmt = $con->prepare($query);
            $stmt->execute();

            // this is how to get number of rows returned
            $num = $stmt->rowCount();

            // link to create record form
            echo "<a href='customer_create.php' class='btn btn-primary mb-3'>Create New Customer</a>";

            //check if more than 0 record found
            if ($num > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-hover table-bordered align-middle'>"; //start table
                //creating our table heading
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Photo</th>";
                // echo "<th>Passowrd</th>";
                echo "<th>First Name</th>";
                echo "<th>Last Name</th>";
                echo "<th>Gender</th>";
                echo "<th>Date of Birth</th>";
                echo "<th>Registration Date</th>";
                echo "<th>Account Status</th>";
                echo "<th>Action</th>";
                echo "</tr>";

                // retrieve our table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // extract row
                    // this will make $row['firstname'] to just $firstname only
                    extract($row);
                    // creating new table row per record
                    echo "<tr>";
                    echo "<td>{$CustomerID}</td>";
                    echo "<td>";
                    if ($customer_image !== "") {
                        echo "<div class='text-center'><img src='uploads_customer/$customer_image' width='50px'/></div>";
                    } else if ($gender == "Male") {
                        echo "<a href='customer_read_one.php?id={$CustomerID}'><img class='ms-3 rounded' src='images/male.png' width='50px' /></a>";
                    } else if ($gender == "Female") {
                        echo "<a href='customer_read_one.php?id={$CustomerID}'><img class='ms-3 rounded' src='images/female.png' width='50px' /></a>";
                    }
                    echo "</td>";
                    // echo "<td>{$password}</td>";
                    echo "<td>{$first_name}</td>";
                    echo "<td>{$last_name}</td>";
                    echo "<td>";
                    if ($gender == 'Male') {
                        echo "<div class='d-flex justify-content-center align-items-center'>";
                        echo "<img src='images/male_icon.png' width='20px' class='me-2'/>";
                        echo "<p class='text-primary fw-bold mb-0'>$gender</p>";
                        echo "</div>";
                    } else if ($gender == 'Female') {
                        echo "<div class='d-flex justify-content-center align-items-center'>";
                        echo "<img src='images/female_icon.png' width='20px' class='me-2'/>";
                        echo "<p class='text-danger fw-bold mb-0'>$gender</p>";
                        echo "</div>";
                    };
                    echo "</td>";
                    echo "<td>{$date_of_birth}</td>";
                    echo "<td>{$registration_date_time}</td>";
                    echo "<td class='text-center'>";
                    if ($account_status == 'Active') {
                        echo "<p class='btn btn-success fw-bold mb-0 rounded-pill'>$account_status</p>";
                    } else if ($account_status == 'Inactive') {
                        echo "<p class='btn btn-danger fw-bold mb-0 text-center rounded-pill'>$account_status</p>";
                    };
                    echo "</td>";
                    echo "<td>";

                    echo "<div class='row'>";
                    // read one record
                    echo "<a href='customer_read_one.php?id={$CustomerID}' class='btn btn-info col-11 col-lg m-auto me-lg-1'>Read</a>";

                    // we will use this links on next part of this post
                    echo "<a href='customer_update.php?id={$CustomerID}' class='btn btn-primary col-11 col-lg m-auto me-lg-1 mt-2 mt-lg-0'>Edit</a>";

                    // we will use this links on next part of this post
                    echo "<a href='#' onclick='delete_customer({$CustomerID});' class='btn btn-danger col-11 col-lg m-auto mt-2 mt-xl-0'>Delete</a>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }


                // end table
                echo "</table>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }
            ?>

        </div> <!-- end .container -->

        <script type='text/javascript'>
            // confirm record deletion
            function delete_customer(id) {

                if (confirm('Are you sure?')) {
                    // if user clicked ok,
                    // pass the id to delete.php and execute the delete query
                    window.location = 'customer_delete.php?id=' + id;
                }
            }
        </script>

        <!-- Content End -->

        <hr class="featurette-divider">

    </main>
    <!-- FOOTER -->
    <footer class="container">
        <p class="float-end"><a class="text-decoration-none fw-bold" href="#">Back to top</a></p>
        <p class="text-muted fw-bold">&copy; 2022 Chia Yeu Shyang</p>
    </footer>
    <!-- FOOTER END -->
</body>