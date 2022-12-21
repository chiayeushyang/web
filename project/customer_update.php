<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
ob_start();
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Update Customer</title>

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
    <!-- NAVBAR -->
    <?php
    include "navbar.php";
    ?>
    <!-- NAVBAR END -->
    <main>

        <body>
            <!-- container -->
            <div class="container mt-5">
                <div class="page-header">
                    <h1>Update Customer</h1>
                </div>
                <?php
                // get passed parameter value, in this case, the record ID
                // isset() is a PHP function used to verify if a value is there or not
                $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

                //include database connection
                include 'config/database.php';

                // read current record's data
                try {
                    // prepare select query
                    $query = "SELECT CustomerID, username, password, customer_image as old_image, first_name, last_name, gender, date_of_birth, registration_date_time, account_status FROM customers WHERE CustomerID = ? LIMIT 0,1";
                    $stmt = $con->prepare($query);

                    // this is the first question mark
                    $stmt->bindParam(1, $id);

                    // execute our query
                    $stmt->execute();

                    // store retrieved row to a variable
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    extract($row);
                }

                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
                ?>

                <?php
                // check if form was submitted
                if ($_POST) {

                    $username = trim($_POST['username']);
                    $old_password = $_POST['old_password'];
                    $new_password = $_POST['new_password'];
                    $confirm_password = $_POST['confirm_password'];
                    $first_name = $_POST['first_name'];
                    $last_name = $_POST['last_name'];
                    $gender = $_POST['gender'];
                    $date_of_birth = $_POST['date_of_birth'];
                    $account_status = $_POST['account_status'];
                    $delete_image = $_POST['delete_image'];

                    $today = date('Y-m-d');

                    $date1 = date_create($date_of_birth);
                    $date2 = date_create($today);
                    $age = date_diff($date1, $date2);

                    $validation = true;

                    $target_file = "";
                    // error message is empty
                    $file_upload_error_messages = "";

                    // Check Empty
                    if ($username == "" || $first_name == "" || $last_name == "" || $gender == "" || $date_of_birth == "") {
                        echo "<div class='alert alert-danger'>Please make sure all fields are not empty</div>";
                        $validation = false;
                    }

                    //Check Password
                    if ($old_password != "" && md5($old_password) != $password) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Please enter corret password</div>";
                        $validation = false;
                    } else if ($old_password == "" && $new_password == "" && $confirm_password == "") {
                        $pass = $password;
                    } else if (!preg_match("/[0-9]/", $new_password) || !preg_match("/[a-z]/", $new_password) || !preg_match("/[A-Z]/", $new_password) || strlen($new_password) < 8) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Please enter new password with at least <br> - 1 capital letter <br> - 1 small letter <br> - 1 integer <br> - more than 8 character</div>";
                        $validation = false;
                    } else if ($confirm_password !== $new_password) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Please enter valid confirm password</div>";
                        $validation = false;
                    }

                    // var_dump($username);

                    // Check Username
                    if (strpos($username, " ") !== false) {
                        // if (preg_match("/[\s]/", $username)) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>No space is allowed in username</div>";
                        $validation = false;
                    } else if (strlen($username) < 6) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Username should contained at leats 6 characters</div>";
                        $validation = false;
                    }

                    // Check birthday
                    if ($date_of_birth > date('Y-m-d')) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Date of Birth cannot in future.</div>";
                        $validation = false;
                    } else if ($age->format("%y") < 18) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Age below 18 years old are not allowed.</div>";
                        $validation = false;
                    }

                    if ((!empty($_FILES["image"]["name"]) && $delete_image == "Yes")) {
                        $file_upload_error_messages .= "<div class='alert alert-danger'>Cannot upload image if want to delete image.</div>";
                        $validation = false;
                    } else if ($validation == true && $delete_image == "Yes") {
                        unlink("uploads/$old_image");
                        $new_image = "";
                    } else if (empty($_FILES["image"]["name"])) {
                        $new_image = $old_image;
                    } else {
                        include "image_upload.php";
                        if ($validation == true && $old_image != "" && getimagesize($target_file) !== false) {
                            unlink("uploads/$old_image");
                        }
                        $new_image = $image;
                    }


                    if ($validation) {
                        try {
                            // write update query
                            // in this case, it seemed like we have so many fields to pass and
                            // it is better to label them and not use question marks
                            $query = "UPDATE customers SET username=:username, password=:password, customer_image=:image,first_name=:first_name, last_name=:last_name ,gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status WHERE CustomerID=:CustomerID";
                            // prepare query for execution
                            $stmt = $con->prepare($query);

                            // bind the parameters
                            $stmt->bindParam(":CustomerID", $id);
                            $stmt->bindParam(':username', $username);
                            if ($old_password == "" && $new_password == "" && $confirm_password == "") {
                                $stmt->bindParam(':password', $pass);
                            } else {
                                $stmt->bindParam(':password', md5($new_password));
                            }
                            $stmt->bindParam(':image', $new_image);
                            $stmt->bindParam(':first_name', $first_name);
                            $stmt->bindParam(':last_name', $last_name);
                            $stmt->bindParam(':gender', $gender);
                            $stmt->bindParam(':date_of_birth', $date_of_birth);
                            $stmt->bindParam(':account_status', $account_status);
                            // Execute the query

                            // Execute the query
                            if ($stmt->execute()) {
                                header("Location: customer_read.php?message=update_success");
                                ob_end_flush();
                            } else {
                                if (file_exists($target_file)) {
                                    unlink($target_file);
                                }
                                echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                            }
                        }
                        // show errors
                        catch (PDOException $exception) {
                            die('ERROR: ' . $exception->getMessage());
                        }
                    } else {
                        // it means there are some errors, so show them to user
                        echo "<div class='alert alert-danger'>";
                        echo "<div>{$file_upload_error_messages}</div>";
                        echo "</div>";
                    }
                } ?>


                <!--we have our html form here where new record information can be updated-->
                <form action="<?php echo $_SERVER["PHP_SELF"] . "?id={$id}"; ?>" method="post" enctype="multipart/form-data">
                    <table class='table table-hover table-responsive table-bordered'>
                        <input type='hidden' name='delete_image' value='No'>
                        <?php if ($old_image != "") {
                            echo "<tr>";
                            echo "<td colspan='2' class='text-center'><img src='uploads/$old_image'alt='Image not found' width='250px'>";
                            echo "<div class='form-check form-switch mt-2 d-flex justify-content-center'>";
                            echo "<input class='form-check-input me-3' type='checkbox' role='switch' name='delete_image' value='Yes' id='delete_image'>";
                            echo "<label class='form-check-label fw-bold' for='delete_image'>";
                            echo  "Delete Image";
                            echo "</td>";
                            echo "</label>";
                            echo "</div>";
                            echo "</tr>";
                        } else {
                            echo "<tr>";
                            echo "<td colspan='2' class='text-center'><img src='images/noimage.jpg'alt='Image not found' width='250px'></td>";
                            echo "</tr>";
                        }
                        ?>
                        <tr>
                            <td>Photo</td>
                            <td><input type="file" name="image" /></td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td><input type='text' name='username' value="<?php echo $username;  ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Old Password</td>
                            <td><input type='password' name='old_password' placeholder="Enter old password" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>New Password</td>
                            <td><input type='password' name='new_password' placeholder="Enter new password" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Confirm Password</td>
                            <td><input type='password' name='confirm_password' placeholder="Enter confirm password" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>First Name</td>
                            <td><input type='text' name='first_name' value="<?php echo $first_name; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Last Name</td>
                            <td><input type='text' name='last_name' value="<?php echo $last_name; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td class="d-flex">
                                <div class="form-check mx-3">
                                    <input class="form-check-input" type="radio" name="gender" value="Male" id="Male" required <?php echo ($gender == 'Male') ?  "checked" : "";  ?>>
                                    <label class="form-check-label" for="Male">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="Female" id="Female" required <?php echo ($gender == 'Female') ?  "checked" : "";  ?>>
                                    <label class="form-check-label" for="Female">
                                        Female
                                    </label>
                                </div>
                                <div class="form-check mx-3">
                                    <input class="form-check-input" type="radio" name="gender" value="Others" id="Others" required <?php echo ($gender == 'Others') ?  "checked" : "";  ?>>
                                    <label class="form-check-label" for="Others">
                                        Others
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Birth</td>
                            <td><input type='date' name='date_of_birth' value="<?php echo $date_of_birth; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Account status</td>
                            <td class="d-flex">
                                <div class="form-check mx-3">
                                    <input class="form-check-input" type="radio" name="account_status" value="Active" id="Active" required <?php echo ($account_status == 'Active') ?  "checked" : "";  ?>>
                                    <label class="form-check-label" for="Active">
                                        Active
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="account_status" value="Inactive" id="Inactive" <?php echo ($account_status == 'Inactive') ?  "checked" : "";  ?>>
                                    <label class="form-check-label" for="Inactive">
                                        Inactive
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type='submit' value='Save Changes' class='btn btn-primary' />
                                <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <!-- end .container -->
        </body>


        <hr class="featurette-divider">

        <!-- FOOTER -->
        <footer class="container">
            <p class="float-end"><a class="text-decoration-none fw-bold" href="#">Back to top</a></p>
            <p class="text-muted fw-bold">&copy; 2022 Chia Yeu Shyang &middot;
                <a class="text-decoration-none fw-bold" href="#">Privacy</a> &middot;
                <a class="text-decoration-none fw-bold" href="#">Terms</a>
            </p>
        </footer>
    </main>
</body>

</html>