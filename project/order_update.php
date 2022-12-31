<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
ob_start();
?>

<head>
    <?php include "bootstrap.php"; ?>
    
    <title>Update Order</title>

    <link rel="stylesheet" href="css/styles.css" />

</head>

<body>
    <!-- NAVBAR -->
    <?php
    include "navbar.php";
    ?>
    <!-- NAVBAR END -->

    <?php
    // get passed parameter value, in this case, the record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

    //include database connection
    include 'config/database.php';

    // read current record's data
    try {
        // prepare select query
        $query_prev = "SELECT order_detail.OrderID, order_detail.quantity ,order_detail.ProductID, customers.CustomerID, customers.username FROM order_summary 
        INNER JOIN customers ON order_summary.CustomerID = customers.CustomerID 
        INNER JOIN order_detail ON order_summary.OrderID = order_detail.OrderID
        WHERE order_detail.OrderID = ?";

        $stmt_prev = $con->prepare($query_prev);

        // this is the first question mark
        $stmt_prev->bindParam(1, $id);

        // execute our query
        $stmt_prev->execute();

        $row_prev = $stmt_prev->fetch(PDO::FETCH_ASSOC);

        $num_prev = $stmt_prev->rowCount();
    }

    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
    ?>

    <!-- Content Start-->
    <div class="container mt-5">
        <div class="page-header">
            <h1>Update Order</h1>
        </div>

        <?php
        if ($_POST) {
            $CustomerID = $_POST['CustomerID'];
            $ProductID = $_POST['ProductID'];

            $validation = true;
            $product_error = 0;

            if ($CustomerID == 0) {
                echo "<div class='alert alert-danger'>Please choose a customer</div>";
                $validation = false;
            }

            for ($count = 0; $count < count($ProductID); $count++) {
                if ($ProductID[$count] == 0) {
                    $product_error++;
                }
            }

            if ($product_error > 0) {
                echo "<div class='alert alert-danger'>Please choose product for all blank</div>";
                $validation = false;
            }

            $quantity_error = 0;
            for ($count = 0; $count < count($ProductID); $count++) {
                if (isset($_POST["quantity"]) && $_POST["quantity"][$count] < 1) {
                    $quantity_error++;
                }
            }
            if ($quantity_error > 0) {
                echo "<div class='alert alert-danger'>Please enter a valid quantity</div>";
                $validation = false;
            }

            if ($validation) {
                try {
                    // include database connection
                    include 'config/database.php';
                    $query_delete = "DELETE FROM order_detail WHERE OrderID = ?";
                    $stmt_delete = $con->prepare($query_delete);
                    $stmt_delete->bindParam(1, $id);

                    if ($stmt_delete->execute()) {
                        $query_order_summary = "UPDATE order_summary SET CustomerID=:CustomerID WHERE OrderID=:OrderID";

                        // prepare query for execution
                        $stmt_order_summary = $con->prepare($query_order_summary);
                        $stmt_order_summary->bindParam(':OrderID', $id);
                        $stmt_order_summary->bindParam(':CustomerID', $CustomerID);

                        if ($stmt_order_summary->execute()) {
                            echo "<div class='alert alert-success'>Your order ID is <b class=\"fs-4 ms-2 mt-3\">$id</b></div>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }

                $record_saved = 0;

                for ($count = 0; $count < count($ProductID); $count++) {
                    try {
                        $query_order_detail = "INSERT INTO order_detail SET OrderID=:OrderID, ProductID=:ProductID, quantity=:quantity";
                        // prepare query for execution
                        $stmt_order_detail = $con->prepare($query_order_detail);

                        $stmt_order_detail->bindParam(':OrderID', $id);
                        $stmt_order_detail->bindParam(':ProductID', $ProductID[$count]);
                        $stmt_order_detail->bindParam(':quantity', $_POST['quantity'][$count]);

                        $record_number = $count + 1;
                        if ($stmt_order_detail->execute()) {
                            $record_saved++;
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    } catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                }
                if ($record_saved == count($ProductID)) {
                    header("Location: order_read.php?message=update_success&id=$id");
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                }
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form id="myForm" action="<?php echo $_SERVER["PHP_SELF"] . "?id={$id}"; ?>" method="POST">
            <div class="row">
                <?php include 'config/database.php'; ?>
                <div class="col-10 col-sm-7 m-auto mb-4">
                    <div><b>OrderID</b></div>
                    <input type='text' name='OrderID' value="<?php echo $row_prev['OrderID']; ?>" class='form-control' disabled />
                </div>
                <div class="col-10 col-sm-7 m-auto">
                    <label for="CustomerID" class="form-label">Customer Name</label>
                    <select class="form-select" name='CustomerID' aria-label="CustomerID">
                        <?php
                        $query_username = "SELECT CustomerID, username FROM customers ORDER BY CustomerID ASC";
                        $stmt_username = $con->prepare($query_username);
                        $stmt_username->execute();

                        $num_username = $stmt_username->rowCount();

                        //check if more than 0 record found
                        if ($num_username > 0) {
                            while ($row_username = $stmt_username->fetch(PDO::FETCH_ASSOC)) {
                                extract($row_username);
                                if ($CustomerID == $row_prev['CustomerID']) {
                                    echo "<option value=\"$CustomerID\" selected>$username</option>";
                                } else {
                                    echo "<option value=\"$CustomerID\">$username</option>";
                                }
                            }
                        }
                        ?>

                    </select>
                </div>
                <div class="mt-5 text-center">
                    <label class="form-label">--- Edit Product Here ---</label>
                </div>
                <table class='table table-hover table-responsive table-bordered' id='order'>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Products</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>

                    <?php
                    $query = "SELECT * FROM products ORDER BY ProductID ASC";
                    $stmt = $con->prepare($query);

                    if ($num_prev > 0) {
                        do {
                            $default = "";

                            $stmt->execute();
                            $num = $stmt->rowCount();
                            echo "<tr class=\"pRow\">";
                            echo "<td class=\"d-flex justify-content-center\">";
                            echo "<p class=\"mb-0 mt-2\">1</p>";
                            echo "</td>";
                            echo "<td>";
                            echo "<select id='my-select' class=\"form-select\" name=\"ProductID[]\" aria-label=\"ProductID\">";
                            echo "<option $default value='0'>Open this select menu</option>";
                            if ($num > 0) {
                                $status = "";
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                    if ($ProductID == $row_prev['ProductID']) {
                                        $status = "selected";
                                    } else {
                                        $status = "";
                                    }
                                    echo "<option $status value=\"$ProductID\">$name</option>";
                                }
                            }
                            echo   "</select>";
                            echo   "</td>";
                            echo   "<td>";
                            echo   "<input type='number' id='quantity' name='quantity[]' value='$row_prev[quantity]' class='form-control' min=\"1\" />";
                            echo   "</td>";
                            echo   "<td><button type='button' class='btn btn-danger col-11 delete-button'>Delete</button></td>";
                            echo   "</tr>";
                        } while ($row_prev = $stmt_prev->fetch(PDO::FETCH_ASSOC));
                    }
                    ?>
                </table>
                <div class="d-flex justify-content-between mb-4">
                    <input type='button' value='Save' class='btn btn-primary mt-3 mx-2 col-3 col-md' onclick="checkDuplicate()" />
                    <input type="button" value="Add More Product" class="btn btn-info mt-3 mx-2 col-3 col-md add_one" />
                    <!-- <input type="button" value="Delete First" class="btn btn-danger mt-3 mx-2 col-3 col-md delete_one" /> -->
                </div>
            </div>
        </form>

        <!-- Content End -->

        <hr class="featurette-divider">

        <!-- FOOTER -->
        <footer class="container">
            <p class="float-end"><a class="text-decoration-none fw-bold" href="#">Back to top</a></p>
            <p class="text-muted fw-bold">&copy; 2022 Chia Yeu Shyang</p>
        </footer>
        <!-- FOOTER END -->
        <script>
            document.addEventListener('click', function(event) {
                if (event.target.matches('.add_one')) {
                    var table = document.querySelectorAll('.pRow');
                var rowCount = table.length;
                var clone = table[rowCount - 1].cloneNode(true);
                // empty the input after clone
                clone.querySelector('#my-select').value = "0";
                clone.querySelector('#quantity').value = "1";
                table[rowCount - 1].after(clone);

                    // Get a reference to the newly added delete button
                    const deleteButton = clone.querySelector('.delete-button');

                    // Add an event listener to the delete button
                    deleteButton.addEventListener('click', event => {
                        var total = document.querySelectorAll('.pRow').length;
                        if (total > 1) {
                            // Get a reference to the table row containing the delete button that was clicked
                            const row = event.target.closest('tr');

                            // Use the .remove() method to remove the table row
                            row.remove();
                        } else {
                            alert("The last row is not allowed to be deleted")
                        }
                    });
                }
                // ------------- Delete one function -------------
                // if (event.target.matches('.delete_one')) {
                //     var total = document.querySelectorAll('.pRow').length;
                //     if (total > 1) {
                //         var element = document.querySelector('.pRow');
                //         element.remove(element);
                //     }
                // }
                var total = document.querySelectorAll('.pRow').length;

                var row = document.getElementById('order').rows;
                for (var i = 1; i <= total; i++) {
                    row[i].cells[0].innerHTML = i;
                }
            }, false);

            // Get a reference to the table
            const table = document.querySelector('#order');

            // Get a reference to all of the delete buttons within the table
            deleteButtons = table.querySelectorAll('.delete-button');

            // Add an event listener to each delete button
            deleteButtons.forEach(button => {
                button.addEventListener('click', event => {

                    var total = document.querySelectorAll('.pRow').length;
                    if (total > 1) {
                        // Get a reference to the table row containing the delete button that was clicked
                        const row = event.target.closest('tr');

                        // Use the .remove() method to remove the table row
                        row.remove();
                    } else {
                        alert("The last row is not allowed to be deleted")
                    }
                });
            });

            function checkDuplicate() {
                var newarray = [];
                const table = document.querySelector('#order');
                var select = table.getElementsByTagName('select');
                for (var i = 0; i < select.length; i++) {
                    newarray.push(select[i].value);
                }
                var set = new Set(newarray);
                if (set.size !== newarray.length) {
                    alert("There are duplicate items in the order");
                } else {
                    document.getElementById("myForm").submit();
                }
            }
        </script>
</body>

</html>