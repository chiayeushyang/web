<!DOCTYPE HTML>
<html>

<?php
include 'check_session.php';
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Welcome</title>

    <link rel="stylesheet" href="css/welcome.css" />
    <link rel="stylesheet" href="css/styles.css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
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
        <div class="container shadow p-3 mb-5 rounded" id="background">
            <?php

            $current_user = $_SESSION["username"];

            // include database connection
            include 'config/database.php';

            try {
                $query = "SELECT * FROM 
                (SELECT COUNT(CustomerID) as total_customer FROM customers) as c, 
                (SELECT COUNT(ProductID) as total_product FROM products) as p, 
                (SELECT COUNT(OrderID) as total_order FROM order_summary) as o,
                (SELECT IFNULL((SELECT OrderID FROM order_summary INNER JOIN customers ON order_summary.CustomerID = customers.CustomerID WHERE customers.username = :username ORDER BY order_date DESC LIMIT 0,1), 'No Record Found') as latest_order ) as l_o";

                $stmt = $con->prepare($query);

                $stmt->bindParam(':username', $current_user);

                $stmt->execute();
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }

            $num = $stmt->rowCount();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            extract($row);

            try {
                $query_2 = "SELECT first_name, last_name, order_date, sum(quantity * price) as total_price FROM order_summary 
                INNER JOIN customers 
                ON order_summary.CustomerID = customers.CustomerID
                INNER JOIN order_detail
                ON order_summary.OrderID = order_detail.OrderID
                INNER JOIN products
                ON order_detail.ProductID = products.ProductID
                WHERE order_summary.OrderID = :latest_order
                GROUP BY order_detail.OrderID";

                $stmt_2 = $con->prepare($query_2);

                $stmt_2->bindParam(':latest_order', $latest_order);

                $query_highest = "SELECT first_name as top_first_name, last_name as top_last_name,order_summary.OrderID as top_OrderID, order_date as top_order_date,sum(quantity * price) as highest_price FROM order_summary 
                INNER JOIN customers ON order_summary.CustomerID = customers.CustomerID 
                INNER JOIN order_detail ON order_summary.OrderID = order_detail.OrderID 
                INNER JOIN products ON order_detail.ProductID = products.ProductID 
                GROUP BY order_detail.OrderID 
                ORDER BY highest_price DESC LIMIT 1";

                $stmt_highest = $con->prepare($query_highest);

                $stmt_2->execute();

                $stmt_highest->execute();
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }

            $num_2 = $stmt_2->rowCount();

            $row_2 = $stmt_2->fetch(PDO::FETCH_ASSOC);

            if ($num_2 > 0) {
                extract($row_2);
            } 

            $num_highest = $stmt_highest->rowCount();

            if ($num_highest > 0) {

                $row_highest = $stmt_highest->fetch(PDO::FETCH_ASSOC);
                extract($row_highest);
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }
            ?>
            <div class="monitor-wrapper position-relative bg-black p-4 mb-5">
                <div class="monitor center">
                    <p class="m-0">Welcome <?php echo $current_user ?>&nbsp;ʕ•́ᴥ•̀ʔっ♡</p>
                </div>
            </div>
            <div class="row gx-5 gy-5">
                <div class="col-12 col-md-4">
                    <div class="p-3 bg-white border rounded text-center">
                        <h4 class="fw-semibold text-black text-opacity-75">Total Customers <br> <?php echo "<p class='my-2 fs-3 text-black fw-bolder'>$total_customer</p>" ?></h4>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="p-3 bg-white bg-opacity-75 border rounded text-center">
                        <h4 class="fw-semibold text-black text-opacity-50">Total Products <br> <?php echo "<p class='my-2 fs-3 text-black text-opacity-75 fw-bolder'>$total_product</p>" ?></h4>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="p-3 bg-white border rounded text-center">
                        <h4 class="fw-semibold text-black text-opacity-75">Total Orders <br> <?php echo "<p class='my-2 fs-3 text-black fw-bolder'>$total_order</p>" ?></h4>
                    </div>
                </div>
            </div>
            <div class="row gx-5 gy-5 mt-3">
                <h3 class="fw-semibold text-light">Your Latest Order</h3>
                <div class="col-12 col-md-6">
                    <div class="p-3 bg-white border rounded-top text-center">
                        <h4 class="fw-semibold text-black text-opacity-75">Latest OrderID<br> <?php echo "<p class='my-2 fs-3 text-black fw-bolder'>$latest_order</p>" ?></h4>
                    </div>
                    <div class="p-3 bg-white border text-center">
                        <h4 class="fw-semibold text-black text-opacity-75">Name <br>
                            <p class='my-2 fs-3 text-black fw-bolder'><?php echo isset($first_name) && isset($last_name) ? $first_name . " " . $last_name : "No Record Found" ?></p>
                        </h4>
                    </div>
                    <div class="p-3 bg-white rounded-bottom text-center">
                        <h4 class="fw-semibold text-black text-opacity-75">Purchase Date <br>
                            <p class='my-2 fs-3 text-black fw-bolder'><?php echo isset($order_date) ? $order_date : "No Record Found" ?></p>
                        </h4>
                    </div>
                </div>
                <div class="col-12 col-md align-self-center">
                    <div class="p-3 bg-white border rounded text-center">
                        <h4 class="fw-semibold text-black text-opacity-75">Purchase Amount <br>
                            <p class='my-2 fs-3 text-black fw-bolder'><?php echo isset($total_price) ? "RM" . $total_price : "No Record Found" ?></p>
                    </div>
                </div>
            </div>
            <div class="row gx-5 gy-5 mt-3">
                <h3 class="fw-semibold text-light">Top Purchase Order</h3>
                <div class="col-12 col-md-6">
                    <div class="p-3 bg-white border rounded-top text-center">
                        <h4 class="fw-semibold text-black text-opacity-75">OrderID<br> <?php echo "<p class='my-2 fs-3 text-black fw-bolder'>$top_OrderID</p>" ?></h4>
                    </div>
                    <div class="p-3 bg-white border text-center">
                        <h4 class="fw-semibold text-black text-opacity-75">Name <br>
                            <p class='my-2 fs-3 text-black fw-bolder'><?php echo isset($top_first_name) && isset($top_last_name) ? $top_first_name . " " . $top_last_name : "No Record Found" ?></p>
                        </h4>
                    </div>
                    <div class="p-3 bg-white rounded-bottom text-center">
                        <h4 class="fw-semibold text-black text-opacity-75">Purchase Date <br>
                            <p class='my-2 fs-3 text-black fw-bolder'><?php echo isset($top_order_date) ? $top_order_date : "No Record Found" ?></p>
                        </h4>
                    </div>
                </div>
                <div class="col-12 col-md align-self-center">
                    <div class="p-3 bg-white border rounded text-center">
                        <h4 class="fw-semibold text-black text-opacity-75">Purchase Amount <br>
                            <p class='my-2 fs-3 text-black fw-bolder'><?php echo isset($highest_price) ? "RM" . $highest_price : "No Record Found" ?></p>
                    </div>
                </div>
            </div>
        </div>

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

</html>