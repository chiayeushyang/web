<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
ob_start();
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Update Product</title>

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

        <body>
            <!-- container -->
            <div class="container mt-5">
                <div class="page-header">
                    <h1>Update Product</h1>
                </div>
                <?php
                // get passed parameter value, in this case, the record ID
                // isset() is a PHP function used to verify if a value is there or not
                $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

                //include database connection
                include 'config/database.php';

                // read current record's data
                try {
                    // prepare select query
                    $query = "SELECT ProductID, name, description, price, image as old_image, promotion_price, manufacture_date, expired_date FROM products WHERE ProductID = ? LIMIT 0,1";
                    $stmt = $con->prepare($query);

                    // this is the first question mark
                    $stmt->bindParam(1, $id);

                    // execute our query
                    $stmt->execute();

                    // store retrieved row to a variable
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    extract($row);
                }

                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
                ?>

                <?php
                // check if form was submitted
                if ($_POST) {
                    // posted values
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $price = $_POST['price'];
                    $promotion_price = $_POST['promotion_price'];
                    $manufacture_date = $_POST['manufacture_date'];
                    $expired_date = $_POST['expired_date'];

                    $validated = true;

                    // error message is empty
                    $file_upload_error_messages = "";

                    if ($name == "" || $description == "" || $price == "" || $manufacture_date == "") {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Please make sure all fields are not empty</div>";
                        $validated = false;
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
                            $validated = false;
                        }
                    }

                    if (!is_numeric($price)) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>All Prices should be numbers only</div>";
                    } else if ($price > 1000) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Price cannot exceed RM1000</div>";
                        $validated = false;
                    } else if ($price < 0) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Price cannot be negative</div>";
                        $validated = false;
                    }
                    if ($promotion_price > $price) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Promotion price should be cheaper than original price</div>";
                        $validated = false;
                    }

                    if (empty($_FILES["image"]["name"])) {
                        $new_image = $old_image;
                    } else { 
                        include "image_upload.php";
                        if ($validated == true && $old_image != "" && getimagesize($target_file) !== false) {
                            unlink("uploads/$old_image");
                        }
                        $new_image = $image;
                    }

                    if ($validated) {
                        

                        try {
                            // write update query
                            // in this case, it seemed like we have so many fields to pass and
                            // it is better to label them and not use question marks
                            $query = "UPDATE products SET name=:name, description=:description, price=:price, image=:image, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date WHERE ProductID=:ProductID";
                            // prepare query for excecution
                            $stmt = $con->prepare($query);
                            // bind the parameters
                            $stmt->bindParam(':name', $name);
                            $stmt->bindParam(':description', $description);
                            $stmt->bindParam(':price', $price);
                            $stmt->bindParam(':image', $new_image);
                            $stmt->bindParam(':ProductID', $id);
                            $stmt->bindParam(':promotion_price', $promotion_price);
                            $stmt->bindParam(':manufacture_date', $manufacture_date);
                            $stmt->bindParam(':expired_date', $expired_date);

                            if ($stmt->execute()) {
                                header("Location: product_read.php?message=update_success");
                                ob_end_flush();
                            } else {
                                if (file_exists($target_file)) {
                                    unlink($target_file);
                                }
                                echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                            }
                        }
                        // show errors
                        catch (PDOException $exception) {
                            die('ERROR: ' . $exception->getMessage());
                        }
                    } else {
                        // it means there are some errors, so show them to user
                        echo "<div class='alert alert-danger'>";
                        echo "<div>{$file_upload_error_messages}</div>";
                        echo "</div>";
                    }
                } ?>


                <!--we have our html form here where new record information can be updated-->
                <form action="<?php echo $_SERVER["PHP_SELF"] . "?id={$id}"; ?>" method="post" enctype="multipart/form-data">
                    <table class='table table-hover table-responsive table-bordered'>
                        <tr>
                            <td>Name</td>
                            <td><input type='text' name='name' value="<?php echo $name;  ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td><textarea name='description' class='form-control'><?php echo $description; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Price</td>
                            <td><input type='text' name='price' value="<?php echo $price; ?>" class='form-control' /></td>
                        </tr>
                        <?php if ($old_image != "") {
                            echo "<tr>";
                            echo "<td colspan='2' class='text-center'><img src='uploads/$old_image'alt='Image not found' width='250px'></td>";
                            echo "</tr>";
                        }
                        ?>
                        <tr>
                            <td>Photo</td>
                            <td><input type="file" name="image" /></td>
                        </tr>
                        <tr>
                            <td>Promotion Price</td>
                            <td><input type='text' name='promotion_price' value="<?php echo $promotion_price; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Manufacture Date</td>
                            <td><input type='date' name='manufacture_date' value="<?php echo $manufacture_date; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Expired Date</td>
                            <td><input type='date' name='expired_date' value="<?php echo $expired_date; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type='submit' value='Save Changes' class='btn btn-primary' />
                                <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <!-- end .container -->
        </body>


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