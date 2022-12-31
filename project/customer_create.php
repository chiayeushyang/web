<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
ob_start();
?>

<head>

    <?php include "bootstrap.php"; ?>

    <title>Create Customer</title>

    <link rel="stylesheet" href="css/styles.css" />
    
</head>

<body>
    <!-- NAVBAR -->
    <?php
    include "navbar.php";
    ?>
    <!-- NAVBAR END -->
    <main>

        <!-- Container -->
        <div class="container mt-5">
            <div class="page-header">
                <h1>Customers</h1>
            </div>

            <!-- html form to create product will be here -->
            <?php
            if ($_POST) {
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $gender = $_POST['gender'];
                $date_of_birth = $_POST['date_of_birth'];
                $account_status = $_POST['account_status'];

                $today = date('Y-m-d');

                $date1 = date_create($date_of_birth);
                $date2 = date_create($today);
                $age = date_diff($date1, $date2);

                $validation = true;
                $target_file = "";
                // error message is empty
                $file_upload_error_messages = "";

                // Check Empty
                if ($username == "" || $password == "" || $first_name == "" || $last_name == "" || $gender == "" || $date_of_birth == "") {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>Please make sure all fields are not empty</div>";
                    $validation = false;
                }

                // include database connection
                include 'config/database.php';

                // delete message prompt will be here

                // select all data
                $query_check = "SELECT username FROM customers WHERE username=:username";
                $stmt_check = $con->prepare($query_check);
                $stmt_check->bindParam(':username', $username);

                $stmt_check->execute();

                // this is how to get number of rows returned
                $num_check = $stmt_check->rowCount();

                if ($num_check > 0) {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>The username already exist</div>";
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

                // Check password
                if (!preg_match("/[0-9]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password) || strlen($password) < 8) {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>Please enter password with at least <br> - 1 capital letter <br> - 1 small letter <br> - 1 integer <br> - more than 8 character</div>";
                    $validation = false;
                } else if ($confirm_password !== $password) {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>Please enter valid confirm password</div>";
                    $validation = false;
                } else {
                    $password = md5($password);
                }

                // Check birthday
                if ($date_of_birth > date('Y-m-d')) {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>Date of Birth cannot in future.</div>";
                    $validation = false;
                } else if ($age->format("%y") < 18) {
                    $file_upload_error_messages .= "<div class='alert alert-danger'>Age below 18 years old are not allowed.</div>";
                    $validation = false;
                }

                if (!empty($_FILES["image"]["name"])) {
                    include "image_upload.php";
                } else {
                    $image = "";
                }


                if ($validation == true) {
                    // include database connection
                    include 'config/database.php';

                    try {
                        // insert query
                        $query = "INSERT INTO customers SET username=:username, password=:password, customer_image=:image, first_name=:first_name, last_name=:last_name ,gender=:gender, date_of_birth=:date_of_birth, registration_date_time=:registration_date_time, account_status=:account_status";
                        // prepare query for execution
                        $stmt = $con->prepare($query);

                        // bind the parameters
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':password', $password);
                        $stmt->bindParam(':image', $image);
                        $stmt->bindParam(':first_name', $first_name);
                        $stmt->bindParam(':last_name', $last_name);
                        $stmt->bindParam(':gender', $gender);
                        $stmt->bindParam(':date_of_birth', $date_of_birth);
                        $registration_date_time = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':registration_date_time', $registration_date_time);
                        $stmt->bindParam(':account_status', $account_status);
                        // Execute the query

                        if ($stmt->execute()) {
                            header("Location: customer_read.php?message=update_success");
                            ob_end_flush();
                        } else {
                            if (file_exists($target_file)) {
                                unlink($target_file);
                            }
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }
                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                } else {
                    if (file_exists($target_file)) {
                        unlink($target_file);
                    }
                    // it means there are some errors, so show them to user
                    echo "<div class='alert alert-danger'>";
                    echo "<div>{$file_upload_error_messages}</div>";
                    echo "</div>";
                }
            }
            ?>

            <!-- PHP insert code will be here -->

            <!-- html form here where the product information will be entered -->
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                <div class="m-auto">
                    <p class="fw-bold">Username</p>
                    <div class="input-group input-group-lg mb-3">
                        <span class="input-group-text" id="basic-addon1">@</span>
                        <input type="text" class="form-control" name="username" value="<?php echo isset($username) ? $username : ""; ?>" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" />
                    </div>
                </div>
                <div class="d-md-flex row">
                    <div class="mb-3 col">
                        <p class="fw-bold">First name</p>
                        <div class="input-group input-group-lg">
                            <input type='text' name='first_name' value="<?php echo isset($first_name) ? $first_name : ""; ?>" class='form-control' />
                        </div>
                    </div>
                    <div class="ms-md-1 mb-3 col">
                        <p class="fw-bold">Last name</p>
                        <div class="input-group input-group-lg">
                            <input type='text' name='last_name' value="<?php echo isset($last_name) ? $last_name : ""; ?>" class='form-control' />
                        </div>
                    </div>
                </div>
                <div class="d-md-flex row">
                    <div class="mb-3 col">
                        <p class="fw-bold">Password</p>
                        <div class="input-group input-group-lg">
                            <input type='password' name='password' class='form-control input-group-lg' />
                        </div>
                    </div>
                    <div class="ms-md-1 mb-3 col">
                        <p class="fw-bold">Confirm Password</p>
                        <div class="input-group input-group-lg">
                            <input type='password' name='confirm_password' class='form-control' />
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <p class="fw-bold">Date of Birth</p>
                    <div class="input-group input-group-lg">
                        <input type='date' name='date_of_birth' value="<?php echo isset($date_of_birth) ? $date_of_birth : ""; ?>" class='form-control' />
                    </div>
                </div>
                <p class="fw-bold">Gender</p>
                <input type="hidden" class="btn-check" name="gender" value="" />
                <div class="d-flex mx-1 mb-3">
                    <input type="radio" class="btn-check" name="gender" id="Male" value="Male" autocomplete="off" <?php echo ((isset($gender)) && ($gender == 'Male')) ?  "checked" : ""; ?>>
                    <label class="btn btn-lg btn-outline-primary col-6" for="Male">Male</label>
                    <input type="radio" class="btn-check" name="gender" id="Female" value="Female" autocomplete="off" <?php echo ((isset($gender)) && ($gender == 'Female')) ?  "checked" : ""; ?>>
                    <label class="btn btn-lg btn-outline-danger col-6" for="Female">Female</label>
                </div>
                <p class="fw-bold">Account Status</p>
                <div class="d-flex mx-1 mb-3">
                    <input type="radio" class="btn-check" name="account_status" id="Active" value="Active" autocomplete="off" checked>
                    <label class="btn btn-lg btn-outline-success col-6" for="Active">Active</label>
                    <input type="radio" class="btn-check" name="account_status" id="Inactive" value="Inactive" autocomplete="off" <?php echo ((isset($account_status)) && ($account_status == 'Inactive')) ?  "checked" : ""; ?>>
                    <label class="btn btn-lg btn-outline-secondary col-6" for="Inactive">Inactive</label>
                </div>
                <div>
                    <p class="fw-bold">Photo</p>
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" name="image" id="image">
                    </div>
                </div>
                <div class="m-auto mt-5 row justify-content-center">
                    <input type='submit' value='Save' class='btn btn-primary col-6 me-3' />
                    <a href='login.php' class='btn btn-danger col-3 ms-3'>Cancle</a>
                </div>
            </form>
        </div>
        <!-- End Container  -->

        <hr class="featurette-divider">

        <!-- FOOTER -->
        <footer class="container">
            <p class="float-end"><a class="text-decoration-none fw-bold" href="#">Back to top</a></p>
            <p class="text-muted fw-bold">&copy; 2022 Chia Yeu Shyang</p>
        </footer>
    </main>
</body>

</html>