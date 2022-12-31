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
        $query_image = "SELECT customer_image FROM customers WHERE CustomerID = ?";
        $stmt_image = $con->prepare($query_image);
        $stmt_image->bindParam(1, $id);
        $stmt_image->execute();
        $num_image = $stmt_image->rowCount();

        if ($num_image > 0) {
            $row = $stmt_image->fetch(PDO::FETCH_ASSOC);
            extract($row);
        }
        // delete query
        $query = "DELETE FROM customers WHERE CustomerID = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            unlink("uploads_customer/$customer_image");
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
