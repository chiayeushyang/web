<?php
// used to connect to the database
$host = "localhost";
$db_name = "eshop";
$username1 = "eshop";
$password1 = "O(NQHcnvp8cV-h0C";
  
try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username1, $password1); //PDO make connection to database
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // show error
}  
// show error
catch(PDOException $exception){
    echo "Connection error: ".$exception->getMessage();
}
?>
