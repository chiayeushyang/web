<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Read Order</title>

    <link rel="stylesheet" href="css/styles.css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/e0e2f315c7.js" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>

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
                <h1>Read Orders</h1>
            </div>

            <!-- PHP code to read records will be here -->
            <?php

            if ($_GET) {
                $message = isset($_GET['message']) ? $_GET['message'] : "";

                if ($message == "update_success") {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
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
            $query = "SELECT order_summary.OrderID, first_name, last_name, order_date, sum(quantity * price) as total_price FROM order_summary 
            INNER JOIN customers 
            ON order_summary.CustomerID = customers.CustomerID
            INNER JOIN order_detail
            ON order_summary.OrderID = order_detail.OrderID
            INNER JOIN products
            ON order_detail.ProductID = products.ProductID
            GROUP BY order_detail.OrderID";

            $stmt = $con->prepare($query);
            $stmt->execute();

            // this is how to get number of rows returned
            $num = $stmt->rowCount();

            // link to create record form
            echo "<a href='order_create.php' class='btn btn-primary mb-3'>Create New Order</a>";

            //check if more than 0 record found
            if ($num > 0) {

                echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

                //creating our table heading
                echo "<tr>";
                echo "<th>OrderID</th>";
                echo "<th>First Name</th>";
                echo "<th>Last Name</th>";
                echo "<th>Total Price (RM)</th>";
                echo "<th>Order Date</th>";
                echo "<th>Action</th>";
                echo "</tr>";

                // retrieve our table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // extract row
                    // this will make $row['firstname'] to just $firstname only
                    extract($row);
                    // creating new table row per record
                    echo "<tr>";
                    echo "<td>{$OrderID}</td>";
                    echo "<td>{$first_name}</td>";
                    echo "<td>{$last_name}</td>";
                    echo "<td class='text-end'>" . number_format(round($total_price, 1), 2) . "</p></td>";
                    echo "<td>{$order_date}</td>";
                    echo "<td>";

                    echo "<div class='row'>";
                    // read one record
                    echo "<a href='order_read_one.php?id={$OrderID}' class='btn btn-info col-11 col-lg m-auto me-lg-1'>Read</a>";

                    // we will use this links on next part of this post
                    echo "<a href='order_update.php?id={$OrderID}' class='btn btn-primary col-11 col-lg m-auto me-lg-1 mt-2 mt-lg-0'>Edit</a>";

                    // we will use this links on next part of this post
                    echo "<a href='#' onclick='delete_order({$OrderID});' class='btn btn-danger col-11 col-lg m-auto mt-2 mt-xl-0'>Delete</a>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }


                // end table
                echo "</table>";
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }
            ?>

        </div> <!-- end .container -->

        <script type='text/javascript'>
            // confirm record deletion
            function delete_order(id) {

                if (confirm('Are you sure?')) {
                    // if user clicked ok,
                    // pass the id to delete.php and execute the delete query
                    window.location = 'order_delete.php?id=' + id;
                }
            }
        </script>

        <!-- Content End -->

        <hr class="featurette-divider">

    </main>
    <!-- FOOTER -->
    <footer class="container">
        <p class="float-end"><a class="text-decoration-none fw-bold" href="#">Back to top</a></p>
        <p class="text-muted fw-bold">&copy; 2022 Chia Yeu Shyang &middot;
            <a class="text-decoration-none fw-bold" href="#">Privacy</a> &middot;
            <a class="text-decoration-none fw-bold" href="#">Terms</a>
        </p>
    </footer>
    <!-- FOOTER END -->
</body>