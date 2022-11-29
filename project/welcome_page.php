<!DOCTYPE HTML>
<html>

<?php
include 'check_session.php';
?>

<head>
    <title>Read Product</title>

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
        <div class="container">
            <?php

            $current_user = "Admin1";

            // include database connection
            include 'config/database.php';

            try {
                $query = "SELECT * FROM 
                (SELECT COUNT(CustomerID) as total_customer FROM customers) as c, 
                (SELECT COUNT(ProductID) as total_product FROM products) as p, 
                (SELECT COUNT(OrderID) as total_order FROM order_summary) as o,
                (SELECT IFNULL((SELECT OrderID FROM order_summary INNER JOIN customers ON order_summary.CustomerID = customers.CustomerID WHERE customers.username = :username ORDER BY order_date DESC LIMIT 0,1), 'Record Not Fouund') as latest_order ) as l_o";

                $stmt = $con->prepare($query);

                $stmt->bindParam(':username', $current_user);

                $stmt->execute();
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }


            $num = $stmt->rowCount();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            extract($row);

            echo $total_customer . "<br>";
            echo $total_product . "<br>";
            echo $total_order . "<br>";
            echo $latest_order . "<br>";
            ?>
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