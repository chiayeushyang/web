<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
ob_start();
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create Customer</title>

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
    </header>
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
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Username</td>
                        <td>
                            <div class="input-group input-group-lg mb-3">
                                <span class="input-group-text" id="basic-addon1">@</span>
                                <input type="text" class="form-control" name="username" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type='password' name='password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Confirm Password</td>
                        <td><input type='password' name='confirm_password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Photo</td>
                        <td><input type="file" name="image" /></td>
                    </tr>
                    <tr>
                        <td>First name</td>
                        <td><input type='text' name='first_name' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Last name</td>
                        <td><input type='text' name='last_name' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td class="d-flex">
                            <div class="form-check mx-3">
                                <input class="form-check-input" type="radio" name="gender" value="Male" id="Male" required>
                                <label class="form-check-label" for="Male">
                                    Male
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="Female" id="Female" required>
                                <label class="form-check-label" for="Female">
                                    Female
                                </label>
                            </div>
                            <div class="form-check mx-3">
                                <input class="form-check-input" type="radio" name="gender" value="Others" id="Others" required>
                                <label class="form-check-label" for="Others">
                                    Others
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Date of birth</td>
                        <td><input type='date' name='date_of_birth' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Account status</td>
                        <td class="d-flex">
                            <div class="form-check mx-3">
                                <input class="form-check-input" type="radio" name="account_status" value="Active" id="Active" required>
                                <label class="form-check-label" for="Active">
                                    Active
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="account_status" value="Inactive" id="Inactive">
                                <label class="form-check-label" for="Inactive">
                                    Inactive
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save' class='btn btn-primary' />
                            <a href='welcome_page.php' class='btn btn-danger'>Back to home</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <!-- End Container  -->

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