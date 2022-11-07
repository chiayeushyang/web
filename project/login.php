<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Home</title>

    <link rel="stylesheet" href="css/login.css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/e0e2f315c7.js" crossorigin="anonymous"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
    <link href="/docs/5.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="mask-icon" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
    <link rel="icon" href="https://getbootstrap.com/docs/5.2/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#712cf9">

</head>

<body class="text-center">

    <?php


    if ($_POST) {
        $username = trim($_POST['username']);
        $pass = trim($_POST['password']);

        $validate = true;

        if ($username == "") {
            echo "<div class='alert alert-danger align-item-center'>Please enter your username</div>";
            $validate = false;
        }

        if ($pass == "") {
            echo "<div class='alert alert-danger align-item-center'>Please enter your password</div>";
            $validate = false;
        }

        if ($validate) {
            include 'config/database.php';
            try {

                // prepare select query
                $query = "SELECT username, password, account_status FROM customers WHERE username = :username";
                $stmt = $con->prepare($query);

                $stmt->bindParam(':username', $username);
                // execute our query
                $stmt->execute();

                $num = $stmt->rowCount();

                if ($num > 0) {
                    // store retrieved row to a variable
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    extract($row);

                    if ($pass == $password) {
                        switch ($account_status) {
                            case "Inactive":
                                echo "<div class='alert alert-danger align-item-center'>Your Account is suspended</div>";
                                break;
                            case "Active":
                                header("Location: index.php");
                                break;
                            default:
                                echo "<div class='alert alert-danger align-item-center'>No account status stated</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger align-item-center mt-5'>Incorrect Password</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger align-item-center mt-5'>User not found (Invalid Account)</div>";
                }
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
    }
    ?>


    <main class="form-signin w-100 m-auto px-3 py-5 rounded">
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <i class="fa-solid fa-shop fa-xl fs-1 text-light mb-4" width="72" height="57"></i>
            <h1 class="h3 mb-3 fw-normal fw-bold">Please sign in</h1>

            <div class="form-floating">
                <input type="text" class="form-control" name="username" placeholder="Username">
                <label for="username">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <label for="password">Password</label>
            </div>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me" name="remember"> Remember me
                </label>
            </div>
            <input type='submit' value='Save' class='w-100 btn btn-lg btn-primary' />
            <p class="mt-5 mb-3 text-muted">&copy; 2022 Chia Yeu Shyang</p>
        </form>
    </main>

</body>

</html>