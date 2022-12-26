<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
?>

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
        `OrderID` int(11) NOT NULL AUTO_INCREMENT,
         `CustomerID` int(11) NOT NULL, 
         `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (OrderID), 
          FOREIGN KEY (CustomerID) REFERENCES customers(CustomerID)
           ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    CREATE TABLE IF NOT EXISTS `order_detail` (     
        `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, 
        `OrderID` int(11) NOT NULL, 
        `ProductID` int(11) NOT NULL,
        `quantity` int(11) NOT NULL,
        PRIMARY KEY (OrderDetailID),
        FOREIGN KEY (OrderID) REFERENCES order_summary(OrderID), 
        FOREIGN KEY (ProductID) REFERENCES products(ProductID)
       ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    --- Database.END -->


    <!-- NAVBAR -->
    <?php
    include "navbar.php";
    ?>
    <!-- NAVBAR END -->

    <!-- Content Start-->
    <div class="container mt-5">
        <div class="page-header">
            <h1>New Order</h1>
        </div>

        <?php
        if ($_POST) {
            $CustomerID = $_POST['CustomerID'];
            $ProductID = $_POST['ProductID'];

            function formReset()
            {
                unset($_POST['CustomerID']);
                unset($_POST['ProductID']);
                unset($_POST['quantity']);
            }

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

                    $query_order_summary = "INSERT INTO order_summary SET CustomerID=:CustomerID";

                    // prepare query for execution
                    $stmt_order_summary = $con->prepare($query_order_summary);

                    $stmt_order_summary->bindParam(':CustomerID', $CustomerID);

                    if ($stmt_order_summary->execute()) {

                        // prepare select query
                        $query = "SELECT OrderID FROM order_summary ORDER BY OrderID DESC LIMIT 1";
                        $stmt = $con->prepare($query);

                        // execute our query
                        $stmt->execute();
                        $num = $stmt->rowCount();

                        if ($num > 0) {
                            // store retrieved row to a variable
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            extract($row);
                            echo "<div class='alert alert-success'>Your order ID is <b class=\"fs-4 ms-2 mt-3\">$OrderID</b></div>";
                        } else {
                            die('ERROR: Record ID not found.');
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
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

                        $stmt_order_detail->bindParam(':OrderID', $OrderID);
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
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                    formReset();
                }
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form id="myForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <div class="row">
                <?php include 'config/database.php'; ?>
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
                                echo "<option value='$CustomerID'";

                                if (isset($_POST["CustomerID"]) && $_POST["CustomerID"] == $CustomerID) {
                                    echo "selected";
                                }

                                echo ">$username</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mt-5 text-center">
                    <label class="form-label">--- Select Product Here ---</label>
                </div>

                <?php
                $query = "SELECT * FROM products ORDER BY ProductID ASC";
                ?>
                <div class="d-flex justify-content-between mb-4">
                    <input type='button' value='Save' class='btn btn-primary mt-3 mx-2 col-3 col-md' onclick="checkDuplicate()" />
                    <input type="button" value="Add More Product" class="btn btn-info mt-3 mx-2 col-3 col-md add_one" />
                    <!-- <input type="button" value="Delete First" class="btn btn-danger mt-3 mx-2 col-3 col-md delete_one" /> -->
                </div>
                <table class='table table-hover table-responsive table-bordered' id='order'>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Products</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>

                    <?php
                    $index = 0;
                    $productCount = 0;
                    $colNum = 1;

                    if (isset($_POST['ProductID'])) {
                        $productCount = count($_POST['ProductID']);
                    }

                    do {
                        $stmt = $con->prepare($query);
                        $stmt->execute();

                        // this is how to get number of rows returned
                        $num = $stmt->rowCount();

                        echo "<tr class=\"pRow\">";
                        echo "<td class=\"d-flex justify-content-center\">";
                        echo "<p class=\"mb-0 mt-2\">$colNum</p>";
                        echo "</td>";
                        echo "<td>";
                        echo "<select id='my-select' class=\"form-select\" name=\"ProductID[]\" aria-label=\"OrderID\">";
                        echo "<option selected>Open this select menu</option>";

                        if ($num > 0) {

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);

                                echo "<option value={$ProductID} ";

                                // if the option is selected then auto select it
                                if (isset($_POST["ProductID"]) && $_POST["ProductID"][$index] == $ProductID) {
                                    echo "selected";
                                }
                                echo ">{$name}</option>";
                            }
                        }
                        echo   "</select>";
                        echo   "</td>";
                        echo   "<td>";
                        echo   "<input type='number' id='quantity' name='quantity[]' value= '";
                        if (isset($_POST["quantity"])) {
                            echo $_POST["quantity"][$index];
                        } else {
                            echo "1";
                        }
                        echo "' class='form-control' min=\"1\" />";
                        echo   "</td>";
                        echo   "<td><button type='button' class='btn btn-danger col-11 delete-button'>Delete</button></td>";
                        echo   "</tr>";

                        $index++;
                        $colNum++;
                    } while ($productCount > $index);
                    ?>

                </table>
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
        <script>
            document.addEventListener('click', function(event) {
                if (event.target.matches('.add_one')) {
                    var table = document.querySelectorAll('.pRow');
                    var rowCount = table.length;
                    var clone = table[rowCount - 1].cloneNode(true);
                    table[rowCount - 1].after(clone);
                    var quantity = document.getElementById('quantity').length - 1
                    quantity.value = "1";

                    const selectElement = document.getElementById('my-select');
                    // selectElement.selectedIndex = 0;
                    const deleteButton = clone.querySelector('.delete-button');
                    deleteButton.addEventListener('click', event => {

                        var total = document.querySelectorAll('.pRow').length;
                        if (total > 1) {
                            const row = event.target.closest('tr');

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

            const table = document.querySelector('#order');
            deleteButtons = table.querySelectorAll('.delete-button');

            deleteButtons.forEach(button => {
                button.addEventListener('click', event => {

                    var total = document.querySelectorAll('.pRow').length;
                    if (total > 1) {
                        const row = event.target.closest('tr');
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