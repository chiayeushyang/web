<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register</title>

    <link rel="stylesheet" href="css/login.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/e0e2f315c7.js" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>

</head>

<body class="pb-5">
    <main class="rounded m-auto pb-5">
        <!-- Container -->
        <div class="container mt-5">
            <div class="page-header mb-5">
                <h1>Register</h1>
            </div>

            <?php
            if ($_POST) {
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $gender = $_POST['gender'];
                $date_of_birth = $_POST['date_of_birth'];

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
                        $query = "INSERT INTO customers SET username=:username, password=:password, customer_image=:image, first_name=:first_name, last_name=:last_name ,gender=:gender, date_of_birth=:date_of_birth, registration_date_time=:registration_date_time, account_status='Active'";
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
                        // Execute the query

                        if ($stmt->execute()) {
                            header("Location: login.php?message=create_success");
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
                <div class="d-md-flex">
                    <div class="mb-3">
                        <p class="fw-bold">First name</p>
                        <div class="input-group input-group-lg">
                            <input type='text' name='first_name' value="<?php echo isset($first_name) ? $first_name : ""; ?>" class='form-control' />
                        </div>
                    </div>
                    <div class="ms-md-1 mb-3">
                        <p class="fw-bold">Last name</p>
                        <div class="input-group input-group-lg">
                            <input type='text' name='last_name' value="<?php echo isset($last_name) ? $last_name : ""; ?>" class='form-control' />
                        </div>
                    </div>
                </div>
                <div class="d-md-flex">
                    <div class="mb-3">
                        <p class="fw-bold">Password</p>
                        <div class="input-group input-group-lg">
                            <input type='password' name='password' class='form-control input-group-lg' />
                        </div>
                    </div>
                    <div class="ms-md-1 mb-3">
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
                <div class="row mx-1 mb-3">
                    <input type="radio" class="btn-check" name="gender" id="Male" value="Male" autocomplete="off" checked>
                    <label class="btn btn-lg btn-outline-primary col" for="Male">Male</label>
                    <input type="radio" class="btn-check" name="gender" id="Female" value="Female" autocomplete="off" <?php echo ((isset($gender)) && ($gender == 'Female')) ?  "checked" : ""; ?>>
                    <label class="btn btn-lg btn-outline-danger col" for="Female">Female</label>
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

    </main>
</body>

</html>