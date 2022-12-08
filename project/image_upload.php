<?php
include 'config/database.php';

error_reporting(E_WARNING);

// new 'image' field
$image = !empty($_FILES["image"]["name"])
? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
: "";
$image = htmlspecialchars(strip_tags($image));

// now, if image is not empty, try to upload the image
if ($image) {

    // upload to file to folder
    $target_directory = "uploads/";
    $target_file = $target_directory . $image;
    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

    // make sure that file is a real image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        $file_upload_error_messages .= "<div class='alert alert-danger'>Submitted file is not an image.</div>";
        $validated = false;
    } 

    // make sure certain file types are allowed
    $allowed_file_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($file_type, $allowed_file_types)) {
        $file_upload_error_messages .= "<div class='alert alert-danger'>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
        $validated = false;
    }

    // make sure file does not exist
    if (file_exists($target_file)) {
        $file_upload_error_messages .= "<div class='alert alert-danger'>Image already exists. Try to change file name.</div>";
        $validated = false;
    }

    // make sure submitted file is not too large, can't be larger than 1 MB
    if ($_FILES['image']['size'] > (1024000)) {
        $file_upload_error_messages .= "<div class='alert alert-danger'>Image must be less than 1 MB in size.</div>";
        $validated = false;
    }

    // make sure the 'uploads' folder exists
    // if not, create it
    if (!is_dir($target_directory)) {
        mkdir($target_directory, 0777, true);
    }
}
