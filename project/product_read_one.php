<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
?>

<head>
    <?php include "bootstrap.php"; ?>
    
    <title>Read One Product</title>

    <link rel="stylesheet" href="css/styles.css" />

</head>

<body>
    <div>
        <!-- NAVBAR -->
        <?php
        include "navbar.php";
        ?>
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
            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $ProductID = isset($_GET['id']) ? $_GET['id'] : die();

            echo "<hr class='featurette-divider'>";

            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT image, ProductID, name, description, price, promotion_price, manufacture_date, expired_date FROM products WHERE ProductID = :ProductID ";
                $stmt = $con->prepare($query);

                // Bind the parameter
                $stmt->bindParam(":ProductID", $ProductID);

                // execute our query
                $stmt->execute();

                $num = $stmt->rowCount();

                if ($num > 0) {
                    // store retrieved row to a variable
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    echo "<div class='page-header'>";
                    echo "<h1>Read Product</h1>";
                    echo "</div>";

                    // values to fill up our form
                    $image = $row['image'];
                    $name = $row['name'];
                    $description = $row['description'];
                    $price = $row['price'];
                    $promotion_price = $row['promotion_price'];
                    $manufacture_date = $row['manufacture_date'];
                    $expired_date = $row['expired_date'];
                    // shorter way to do that is extract($row)

                    if ($promotion_price == Null) {
                        $promotion_price = "-";
                    } else {
                        $promotion_price = number_format((float)$promotion_price, 2);
                    }

                    if ($manufacture_date == Null) {
                        $manufacture_date = "-";
                    }

                    if ($expired_date == Null) {
                        $expired_date = "-";
                    }
                } else {
                    die("<p>Cannot Find the Product with ProductID = <b>$ProductID</b></p>");
                }
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <!--we have our html table here where the record will be displayed-->
            <div class="table-responsive">
                <table class='table table-hover table-responsive table-bordered'>
                    <?php if ($image != "") {
                        echo "<tr>";
                        echo "<td colspan='2' class='text-center'><img src='uploads/$image'alt='Image not found' width='250px'></td>";
                        echo "</tr>";
                    }
                    ?>
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
                        <td><?php echo "RM ", number_format($price, 2); ?></td>
                    </tr>
                    <tr>
                        <td>Promotion Price</td>
                        <td><?php echo "RM ", $promotion_price; ?></td>
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
                        <td><?php echo "<a href='product_update.php?id={$ProductID}' class='btn btn-primary col-12'>Edit</a>"; ?></td>
                        <td>
                            <a href='product_read.php' class='btn btn-danger col-12'>Back to read products</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Content End -->

        <hr class="featurette-divider">

        <!-- FOOTER -->
        <footer class="container">
            <p class="float-end"><a class="text-decoration-none fw-bold" href="#">Back to top</a></p>
            <p class="text-muted fw-bold">&copy; 2022 Chia Yeu Shyang</p>
        </footer>
        <!-- FOOTER END -->
    </div>
</body>

</html>