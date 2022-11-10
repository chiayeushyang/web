<?php 
session_start();

if (!isset($_SESSION["username"]) && !isset($_SESSION["password"])) {
    header("Location: login.php?error=session_expired");
}
?>