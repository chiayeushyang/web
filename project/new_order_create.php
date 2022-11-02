<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create New Order</title>

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
    <!-- Database ---

    CREATE TABLE IF NOT EXISTS `order_summary` (
        `OrderID` varchar(128) NOT NULL,
        CustomerID` int(11) NOT NULL,
        `total_price` double NOT NULL,
        `total_item` int NOT NULL,
        `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (OrderID),
        FOREIGN KEY (CustomerID) REFERENCES customers(CustomerID)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    CREATE TABLE IF NOT EXISTS `order_detail` (     
        `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, 
        `OrderID` varchar(11) NOT NULL, 
        `ProductID` int(11) NOT NULL,
        `quantity` int(11) NOT NULL,
        `unit_price` double NOT NULL, 
        PRIMARY KEY (OrderDetailID),
        FOREIGN KEY (OrderID) REFERENCES order_summary(OrderID), 
        FOREIGN KEY (ProductID) REFERENCES products(ProductID) 
       ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    --- Database.END -->




    <!-- NAVBAR -->
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-xl d-flex justify-content-between">

                <a class="navbar-brand " href="#">
                    <i class="fa-solid fa-shop fa-xl text-light me-2 "></i>
                    Eshop
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse d-md-flex justify-content-end" id="navbarCollapse">
                    <ul class="navbar-nav mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Product
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="product_create.php">Create Product</a></li>
                                <li><a class="dropdown-item" href="product_read.php">Read Product</a></li>
                                <li><a class="dropdown-item" href="product_read_one.php">Read One Product</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Customer
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="customer_create.php">Create Customer</a></li>
                                <li><a class="dropdown-item" href="customer_read.php">Read Customer</a></li>
                                <li><a class="dropdown-item" href="customer_read_one.php">Read One Customer</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Order
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item active" href="new_order_create">Create New Order</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact Us</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- NAVBAR END -->

    <!-- Content Start-->
    <div class="container mt-5">
        <div class="page-header">
            <h1>New Order</h1>
        </div>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
            <div class="row">
                <div class="col-10 col-sm-6 m-auto">
                    <label for="OrderID" class="form-label">Order ID</label>
                    <?php
                    include 'config/database.php';

                    // select all data
                    $query = "SELECT OrderID FROM order_summary ORDER BY OrderID DESC LIMIT 1";
                    $stmt = $con->prepare($query);
                    $stmt->execute();

                    // this is how to get number of rows returned
                    $num = $stmt->rowCount();

                    //check if more than 0 record found
                    if ($num > 0) {

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);

                            $newOrderID = $OrderID + 1;
                            echo "<input class='form-control bg-light' type='text' name=\"OrderID\" value='$newOrderID' readonly>";
                        }
                    }
                    ?>

                </div>
                <div class="col-10 col-sm-6 m-auto">
                    <label for="CustomerID" class="form-label">Customer ID</label>
                    <select class="form-select" name='CustomerID' aria-label="OrderID">
                        <option selected>Select a customer</option>
                        <?php

                        // select all data
                        $query = "SELECT CustomerID, username FROM customers ORDER BY CustomerID ASC";
                        $stmt = $con->prepare($query);
                        $stmt->execute();

                        // this is how to get number of rows returned
                        $num = $stmt->rowCount();

                        //check if more than 0 record found
                        if ($num > 0) {

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<option value=\"$CustomerID\">$username</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mt-5 mb-2">
                    <label class="form-label">--- Select Product Here ---</label>
                </div>

                <!-- First product -->
                <?php
                $query = "SELECT * FROM products ORDER BY ProductID ASC";
                $stmt = $con->prepare($query);
                $stmt->execute();

                // this is how to get number of rows returned
                $num = $stmt->rowCount();
                ?>
                <div class="col-10 col-sm-3 m-auto">
                    <select class="form-select" name="first_order_product" aria-label="OrderID">
                        <option selected>Open this select menu</option>
                        <?php
                        if ($num > 0) {

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);

                                echo "<option value=\"$ProductID\">$name</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-10 col-sm-3 m-auto">
                    <input type='number' name='first_order_number' value="0" class='form-control' />
                </div>
                <div class="col-10 col-sm-3 m-auto">
                    <input type='text' name='first_unit_price' class='form-control' readonly />
                </div>
                <div class="col-10 col-sm-3 m-auto">
                    <input type='text' name='first_total_price' class='form-control' readonly />
                </div>
                <!-- First product.END -->

                <!-- Second product -->
                <div class="col-10 col-sm-3 m-auto mt-4">
                        <select class="form-select" name="first_order_product" aria-label="OrderID">
                            <option selected>Open this select menu</option>
                            <?php
                            $stmt->execute();

                            // this is how to get number of rows returned
                            $num = $stmt->rowCount();

                            if ($num > 0) {

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);

                                    echo "<option value=\"$ProductID\">$name</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-10 col-sm-3 m-auto mt-4">
                        <input type='number' name='second_order_number' value="0" class='form-control' />
                    </div>
                    <div class="col-10 col-sm-3 m-auto mt-4">
                        <input type='text' name='second_unit_price' class='form-control' readonly />
                    </div>
                    <div class="col-10 col-sm-3 m-auto mt-4">
                        <input type='text' name='second_total_price' class='form-control' readonly />
                    </div>
                    <!-- Second product.END -->

                    <!-- Third product -->
                    <div class="col-10 col-sm-3 m-auto mt-4">
                        <select class="form-select" aria-label="OrderID">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-10 col-sm-3 m-auto mt-4">
                        <input type='number' name='third_order_number' value="0" class='form-control' />
                    </div>
                    <div class="col-10 col-sm-3 m-auto mt-4">
                        <input type='text' name='third_unit_price' class='form-control' readonly />
                    </div>
                    <div class="col-10 col-sm-3 m-auto mt-4">
                        <input type='text' name='third_total_price' class='form-control' readonly />
                    </div>
                    <div>
                        <input type='submit' value='Save' class='btn btn-primary mt-3' />
                    </div>
                    <!-- Third product.END -->
                </div>
        </form>
        <!-- Content End -->

        <hr class="featurette-divider">

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