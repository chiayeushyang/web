<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
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
    <!-- NAVBAR END -->
    <main>

        <body>
            <!-- container -->
            <div class="container mt-5">
                <div class="page-header">
                    <h1>Update Product</h1>
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
                    $query = "SELECT ProductID, name, description, price FROM products WHERE ProductID = ? LIMIT 0,1";
                    $stmt = $con->prepare($query);

                    // this is the first question mark
                    $stmt->bindParam(1, $id);

                    // execute our query
                    $stmt->execute();

                    // store retrieved row to a variable
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    // values to fill up our form
                    $name = $row['name'];
                    $description = $row['description'];
                    $price = $row['price'];
                }

                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
                ?>

                <?php
                // check if form was submitted
                if ($_POST) {
                    try {
                        // write update query
                        // in this case, it seemed like we have so many fields to pass and
                        // it is better to label them and not use question marks
                        $query = "UPDATE products SET name=:name, description=:description, price=:price WHERE ProductID=:ProductID";
                        // prepare query for excecution
                        $stmt = $con->prepare($query);
                        // posted values
                        $name = $_POST['name'];
                        $description = $_POST['description'];
                        $price = $_POST['price'];
                        // bind the parameters
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $descriptiont);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':ProductID', $id);
                        // Execute the query
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Record was updated.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                        }
                    }
                    // show errors
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                } ?>


                <!--we have our html form here where new record information can be updated-->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
                    <table class='table table-hover table-responsive table-bordered'>
                        <tr>
                            <td>Name</td>
                            <td><input type='text' name='name' value="<?php echo $name;  ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td><textarea name='description' class='form-control'><?php echo $description;  ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Price</td>
                            <td><input type='text' name='price' value="<?php echo $price; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type='submit' value='Save Changes' class='btn btn-primary' />
                                <a href='index.php' class='btn btn-danger'>Back to read products</a>
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