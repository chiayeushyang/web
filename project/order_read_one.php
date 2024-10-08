<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
?>

<head>

    <?php include "bootstrap.php"; ?>

    <title>Read One Order</title>

    <link rel="stylesheet" href="css/styles.css" />

</head>

<body>
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
                        <input type='submit' value='Search' class='btn btn-primary' />
                    </td>
                </tr>
            </table>
        </form>

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $OrderID = isset($_GET['id']) ? $_GET['id'] : die();

        echo "<hr class='featurette-divider'>";

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT OrderID, ProductID, quantity FROM order_detail WHERE OrderID= :OrderID ";
            $stmt = $con->prepare($query);
            $query_customer = "SELECT username, OrderID, order_date FROM customers LEFT JOIN order_summary ON customers.CustomerID = order_summary.CustomerID WHERE OrderID=:OrderID ";
            $stmt_customer = $con->prepare($query_customer);

            // Bind the parameter
            $stmt->bindParam(":OrderID", $OrderID);
            $stmt_customer->bindParam(":OrderID", $OrderID);
            // execute our query
            $stmt->execute();
            $stmt_customer->execute();

            $num = $stmt->rowCount();

            if ($num > 0) {
                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                echo "<div class='page-header'>";
                echo "<h1>Read Order Details</h1>";
                echo "</div>";

                extract($row);
            } else {
                die("<p>Cannot Find the Order with OrderID = <b>$OrderID</b></p>");
            }

            $num_customer = $stmt_customer->rowCount();

            if ($num_customer > 0) {
                // store retrieved row to a variable
                $row_customer = $stmt_customer->fetch(PDO::FETCH_ASSOC);

                extract($row_customer);
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
        <div class="table-responsive">
            <table class='table table-hover table-bordered '>
                <thead>
                    <tr>
                        <th class="table-dark">OrderID</th>
                        <td colspan="3" class="table-dark table-active"><?php echo $OrderID;  ?></td>
                    </tr>
                    <tr>
                        <th class="table-dark ">Customer</th>
                        <td colspan="3" class="table-dark table-active"><?php echo $username;  ?></td>
                    </tr>
                    <tr>
                        <th class="table-dark ">Order Date</th>
                        <td colspan="3" class="table-dark table-active"><?php echo $order_date;  ?></td>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <tr>
                        <th colspan="4" class="text-center">
                            <p class="my-2">------------------- Order -------------------</p>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center">Product</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Unit Price (RM)</th>
                        <th class="text-center">Price (RM)</th>
                    </tr>
                    <?php
                    try {
                        $query_product = "SELECT * FROM products WHERE ProductID= :ProductID ";
                        $stmt_product = $con->prepare($query_product);

                        $no = 1;
                        $total_amount = 0;

                        echo $stmt_product->fetch(PDO::FETCH_ASSOC);
                        do {
                            // Bind the parameter
                            $stmt_product->bindParam(":ProductID", $row["ProductID"]);
                            // execute our query
                            $stmt_product->execute();

                            $num_product = $stmt_product->rowCount();
                            if ($num_product > 0) {
                                // store retrieved row to a variable
                                $row_product = $stmt_product->fetch(PDO::FETCH_ASSOC);
                            } else {
                                die('ERROR: Record ID not found.');
                            }

                            $unit_price = $row_product['promotion_price'] == "" ? $row_product['price'] : $row_product['promotion_price'];

                            $total_unit_price = $row['quantity'] * $row_product['promotion_price'] == "" ? $row['quantity'] *  $row_product['price'] : $row['quantity'] * $row_product['promotion_price'];

                            $total_amount += $total_unit_price;
                            echo "<tr>";
                            echo "<td>$no. {$row_product['name']}</td>";
                            echo "<td>{$row['quantity']}</td>";
                            echo "<td class='text-end'>" . number_format($unit_price, 2) . "</td>";
                            echo "<td class='text-end'>" . number_format($total_unit_price, 2) . "</td>";
                            echo "</tr>";

                            $no++;
                        } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
                    }

                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                    ?>
                    <tr>
                        <th colspan="3" class="text-end">
                            <p class="me-3 my-2">Rounded</p>
                        </th>
                        <td><?php echo "<p class='my-2 text-end'>" . number_format(round($total_amount, 1) - $total_amount, 2) . "</p>" ?></td>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">
                            <p class="me-3 my-2 fs-5">Total</p>
                        </th>
                        <td><?php echo "<p class='my-2 text-end'>" . number_format(round($total_amount, 1), 2) . "</p>" ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a href='order_read.php' class='btn btn-danger'>Back to read orders</a>
    </div>

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