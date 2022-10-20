<?php
// used to connect to the database
$host = "localhost";
$db_name = "eshop";
$username = "eshop";
$password = "O(NQHcnvp8cV-h0C";
  
try {
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password); //PDO make connection to database
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // show error
    echo "Connected successfully"; 
}  
// show error
catch(PDOException $exception){
    echo "Connection error: ".$exception->getMessage();
}
?>
