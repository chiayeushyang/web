<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
ob_start();
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create New Product</title>

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

                $validation = true;
                $target_file = "";
                // error message is empty
                $file_upload_error_messages = "";


                if ($name == "" || $description == "" || $price == "" || $manufacture_date == "") {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>Please make sure all fields are not empty</div>";
                    $validation = false;
                }

                if ($promotion_price == "") {
                    $promotion_price = NULL;
                }

                if ($expired_date == "") {
                    $expired_date = NULL;
                } else if ($expired_date != "") {
                    $date1 = date_create($expired_date);
                    $date2 = date_create($manufacture_date);
                    $expired_check = date_diff($date2, $date1);
                    if ($expired_check->format("%R%a") < 0) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Expired date should be later than manufacture date</div>";
                        $validation = false;
                    }
                }

                if (!is_numeric($price)) {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>All Prices should be numbers only</div>";
                } else if ($price > 1000) {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>Price cannot exceed RM1000</div>";
                    $validation = false;
                } else if ($price < 0) {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>Price cannot be negative</div>";
                    $validation = false;
                }
                if ($promotion_price >= $price) {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>Promotion price should be cheaper than original price</div>";
                    $validation = false;
                }

                if (!empty($_FILES["image"]["name"])) {
                    include "image_upload.php";
                } else {
                    $image = "";
                }

                if ($validation) {
                    // include database connection
                    include 'config/database.php';

                    try {
                        // insert query
                        $query = "INSERT INTO products SET name=:name, description=:description, price=:price, image=:image, promotion_price = :promotion_price ,manufacture_date = :manufacture_date, expired_date = :expired_date, created=:created";
                        // prepare query for execution
                        $stmt = $con->prepare($query);

                        // bind the parameters
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':image', $image);
                        $stmt->bindParam(':promotion_price', $promotion_price);
                        $stmt->bindParam(':manufacture_date', $manufacture_date);
                        $stmt->bindParam(':expired_date', $expired_date);
                        $created = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':created', $created);

                        // Execute the query
                        if ($stmt->execute()) {
                            header("Location: product_read.php?message=update_success");
                            ob_end_flush();
                        } else {
                            if (file_exists($target_file)) {
                                unlink($target_file);
                            }
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }
                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                } else {
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                    // it means there are some errors, so show them to user
                    echo "<div class='alert alert-danger'>";
                    echo "<div>{$file_upload_error_messages}</div>";
                    echo "</div>";
                }
            }

            ?>
            <!-- PHP insert code will be here -->

            <!-- html form here where the product information will be entered -->
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                <div class="table-responsive">
                    <table class='table table-hover table-responsive table-bordered'>
                        <tr>
                            <td>Name</td>
                            <td><input type='text' name='name' value="<?php echo isset($name) ? $name : ""; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td><textarea type='text' name='description' rows="5" class='form-control'><?php echo isset($description) ? $description : ""; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Price</td>
                            <td><input type='text' name='price' value="<?php echo isset($price) ? $price : ""; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Photo</td>
                            <td><input type="file" name="image" /></td>
                        </tr>
                        <tr>
                            <td>Promotion Price</td>
                            <td><input type='text' value="<?php echo isset($promotion_price) ? $promotion_price : ""; ?>" name='promotion_price' class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Manufacture Date</td>
                            <td><input type='date' name='manufacture_date' value="<?php echo isset($manufacture_date) ? $manufacture_date : ""; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Expired Date</td>
                            <td><input type='date' name='expired_date' value="<?php echo isset($expired_date) ? $expired_date : ""; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type='submit' value='Save' class='btn btn-primary' />
                                <a href='welcome_page.php' class='btn btn-danger'>Back to home</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <!-- end .container -->

        <hr class="featurette-divider">

        <!-- FOOTER -->
        <footer class="container">
            <p class="float-end"><a class="text-decoration-none fw-bold" href="#">Back to top</a></p>
            <p class="text-muted fw-bold">&copy; 2022 Chia Yeu Shyang</p>
        </footer>
    </main>
</body>

</html>