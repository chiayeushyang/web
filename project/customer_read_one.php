<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
?>

<head>

    <?php include "bootstrap.php"; ?>

    <title>Read One Customer</title>

    <link rel="stylesheet" href="css/styles.css" />

</head>

<body>
    <!-- NAVBAR -->
    <?php
    include "navbar.php";
    ?>
    <!-- NAVBAR END -->

    <!-- Content Start-->
    <div class="container mt-5">
        <div class="page-header">
            <h1>Enter an ID</h1>
        </div>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>ID</td>
                    <td><input type='text' name='id' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                    </td>
                </tr>
            </table>
        </form>

        <hr class="featurette-divider">

        <div class="page-header">
            <h1>Read Customers</h1>
        </div>

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $CustomerID = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT CustomerID, username, password, customer_image, first_name, last_name, gender, date_of_birth, registration_date_time, account_status FROM customers WHERE CustomerID = :CustomerID ";
            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":CustomerID", $CustomerID);

            // execute our query
            $stmt->execute();

            $num = $stmt->rowCount();

            if ($num > 0) {
                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form
                $username = $row['username'];
                // $password = $row['password'];
                $customer_image = $row['customer_image'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $gender = $row['gender'];
                $date_of_birth = $row['date_of_birth'];
                $registration_date_time = $row['registration_date_time'];
                $account_status = $row['account_status'];
                // shorter way to do that is extract($row)
            } else {
                die('ERROR: Record ID not found.');
            }
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!--we have our html table here where the record will be displayed-->
        <div class="table-responsive">
            <table class='table table-hover table-bordered'>
                <?php if ($customer_image != "") {
                    echo "<tr>";
                    echo "<td colspan='2' class='text-center'><img src='uploads_customer/$customer_image'alt='Image not found' width='250px'></td>";
                    echo "</tr>";
                }
                ?>
                <tr>
                    <td class='col-3'>ID</td>
                    <td class='col'><?php echo htmlspecialchars($CustomerID, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
                </tr>
                <!-- <tr>
                    <td>Password</td>
                    <td><?php echo htmlspecialchars($password, ENT_QUOTES);  ?></td>
                </tr> -->
                <tr>
                    <td>First Name</td>
                    <td><?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><?php echo htmlspecialchars($date_of_birth, ENT_QUOTES); ?></td>
                </tr>
                <tr>
                    <td>Registration Date Time</td>
                    <td><?php echo htmlspecialchars($registration_date_time, ENT_QUOTES); ?></td>
                </tr>
                <tr>
                    <td>Account</td>
                    <td><?php echo htmlspecialchars($account_status, ENT_QUOTES); ?></td>
                </tr>
                <tr>
                    <td><?php echo "<a href='customer_update.php?id={$CustomerID}' class='btn btn-primary col-12'>Edit</a>"; ?></td>
                    <td>
                        <a href='customer_read.php' class='btn btn-danger col-12'>Back to read customers</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Content End -->

    <hr class="featurette-divider">

    </main>
    <!-- FOOTER -->
    <footer class="container">
        <p class="float-end"><a class="text-decoration-none fw-bold" href="#">Back to top</a></p>
        <p class="text-muted fw-bold">&copy; 2022 Chia Yeu Shyang</p>
    </footer>
    <!-- FOOTER END -->
</body>