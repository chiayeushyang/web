<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PDO - Read One Record - PHP CRUD Tutorial</title>

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
    <div>
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
                                    <li><a class="dropdown-item active" href="product_read_one.php">Read One Product</a></li>
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
                                    <li><a class="dropdown-item" href="new_order_create">Create New Order</a></li>
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
                <h1>Enter an ID</h1>
            </div>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>ID</td>
                        <td><input type='text' name='id' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save' class='btn btn-primary' />
                        </td>
                    </tr>
                </table>
            </form>

            <hr class="featurette-divider">

            <div class="page-header">
                <h1>Read Product</h1>
            </div>

            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $ProductID = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT ProductID, name, description, price, promotion_price, manufacture_date, expired_date FROM products WHERE ProductID = :ProductID ";
                $stmt = $con->prepare($query);

                // Bind the parameter
                $stmt->bindParam(":ProductID", $ProductID);

                // execute our query
                $stmt->execute();

                $num = $stmt->rowCount(); 

                if ($num > 0) {
                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    // values to fill up our form
                    $name = $row['name'];
                    $description = $row['description'];
                    $price = $row['price'];
                    $promotion_price = $row['promotion_price'];
                    $manufacture_date = $row['manufacture_date'];
                    $expired_date = $row['expired_date'];
                    // shorter way to do that is extract($row)

                    if ($promotion_price == Null) {
                        $promotion_price = "-";
                    }

                    if ($manufacture_date == Null) {
                        $manufacture_date = "-";
                    }

                    if ($expired_date == Null) {
                        $expired_date = "-";
                    }
                   
                } else {
                    die('ERROR: Record ID not found.');
                }
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <!--we have our html table here where the record will be displayed-->
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>ID</td>
                    <td><?php echo htmlspecialchars($ProductID, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><?php echo "RM ", htmlspecialchars($price, ENT_QUOTES); ?></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><?php echo "RM ", htmlspecialchars($promotion_price, ENT_QUOTES); ?></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><?php echo htmlspecialchars($manufacture_date, ENT_QUOTES); ?></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><?php echo htmlspecialchars($expired_date, ENT_QUOTES); ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <a href='index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>

        </div>

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
    </div>
</body>

</html>