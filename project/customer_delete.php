<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    $query_check = "SELECT OrderID, order_summary.CustomerID FROM order_summary INNER JOIN customers on order_summary.CustomerID = customers.CustomerID WHERE order_summary.CustomerID = ?";
    $stmt_check = $con->prepare($query_check);
    $stmt_check->bindParam(1, $id);
    $stmt_check->execute();

    $num = $stmt_check->rowCount();

    if ($num > 0) {
        header('Location: customer_read.php?message=customer_in_use');
    } else {
        // delete query
        $query = "DELETE FROM customers WHERE CustomerID = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            header('Location: customer_read.php?message=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
