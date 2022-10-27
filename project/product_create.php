<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PDO - Create a Record - PHP CRUD Tutorial</title>

    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/e0e2f315c7.js" crossorigin="anonymous"></script>
</head>

<body>
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
                        <li class="nav-item">
                            <a class="nav-link active" href="product_create.php">Create Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="create_customer.php">Create Customer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>

        <!-- container -->
        <div class="container mt-5">
            <div class="page-header">
                <h1>Create Product</h1>
            </div>

            <!-- html form to create product will be here -->
            <?php
            if ($_POST) {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $promotion_price = $_POST['promotion_price'];
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = $_POST['expired_date'];

                $validated = true;

                if ($name == "" || $description == "" || $price == "" || $manufacture_date == "" ) {
                    echo "<div class='alert alert-danger'>Please make sure all fields are not empty</div>";
                    $validated = false;
                }

                if ($promotion_price == "") {
                    $promotion_price = NULL;
                }

                if ($expired_date == "") {
                    $expired_date = NULL;
                }

                if (!is_numeric($price)) {
                    echo "<div class='alert alert-danger'>All Prices should be numbers only</div>";
                } else if ($price > 1000) {
                    echo "<div class='alert alert-danger'>Price cannot exceed RM1000</div>";
                    $validated = false;
                } else if ($price < 0) {
                    echo "<div class='alert alert-danger'>Price cannot be negative</div>";
                    $validated = false;
                }
                if ($promotion_price > $price) {
                    echo "<div class='alert alert-danger'>Promotion price should be cheaper than original price</div>";
                    $validated = false;
                } 
                
                if ($expired_date < $manufacture_date) {
                    echo "<div class='alert alert-danger'>Expired date should be later than manufacture date</div>";
                    $validated = false;
                }

                if ($validated) {
                    // include database connection
                    include 'config/database.php';
                    try {
                        // insert query
                        $query = "INSERT INTO products SET name=:name, description=:description, price=:price, promotion_price = :promotion_price ,manufacture_date = :manufacture_date, expired_date = :expired_date, created=:created";
                        // prepare query for execution
                        $stmt = $con->prepare($query);

                        // bind the parameters
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':promotion_price', $promotion_price);
                        $stmt->bindParam(':manufacture_date', $manufacture_date);
                        $stmt->bindParam(':expired_date', $expired_date);
                        $created = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':created', $created);
                        // Execute the query
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Record was saved.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }
                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                }
            }

            ?>

            <!-- PHP insert code will be here -->

            <!-- html form here where the product information will be entered -->
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Name</td>
                        <td><input type='text' name='name' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><textarea type='text' name='description' rows="5" class='form-control'></textarea></td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td><input type='text' name='price' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Promotion Price</td>
                        <td><input type='text' name='promotion_price' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Manufacture Date</td>
                        <td><input type='date' name='manufacture_date' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Expired Date</td>
                        <td><input type='date' name='expired_date' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save' class='btn btn-primary' />
                            <a href='index.php' class='btn btn-danger'>Back to home</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <!-- end .container -->

        <hr class="featurette-divider">

        <!-- FOOTER -->
        <footer class="container">
            <p class="float-end"><a class="text-decoration-none fw-bold" href="#">Back to top</a></p>
            <p class="text-muted fw-bold">&copy; 2022 Chia Yeu Shyang &middot;
                <a class="text-decoration-none fw-bold" href="#">Privacy</a> &middot;
                <a class="text-decoration-none fw-bold" href="#">Terms</a>
            </p>
        </footer>
    </main>
</body>

</html>