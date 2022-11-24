<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    $query_check = "SELECT OrderID, order_detail.ProductID FROM order_detail INNER JOIN products on order_detail.ProductID = products.ProductID WHERE order_detail.ProductID = ?";
    $stmt_check = $con->prepare($query_check);
    $stmt_check->bindParam(1, $id);
    $stmt_check->execute();

    $num = $stmt_check->rowCount();

    if ($num > 0) {
        header('Location: product_read.php?message=product_in_use');
    } else {
        // delete query
        $query = "DELETE FROM products WHERE ProductID = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            header('Location: product_read.php?message=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
