<?php
// Function to check string starting
// with given substring
function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function active($pagename)
{
    return basename($_SERVER["PHP_SELF"]) == $pagename ? "active" : "";
}

function active_startwith($pagename_start)
{
    return startsWith(basename($_SERVER["PHP_SELF"]), "$pagename_start") ? "active" : "";
}

$current_user = $_SESSION["username"];

include 'config/database.php';

try {
    // prepare select query
    $query = "SELECT customer_image FROM customers WHERE username = :username ";
    $stmt = $con->prepare($query);

    // Bind the parameter
    $stmt->bindParam(":username", $current_user);

    // execute our query
    $stmt->execute();

    $num = $stmt->rowCount();

    if ($num > 0) {

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        extract($row);
    }
} // show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>

<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-xl d-flex justify-content-between ">
            <a class="navbar-brand " href="#">
                <i class="fa-solid fa-shop fa-xl text-light me-2 "></i>
                Eshop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse d-md-flex justify-content-end" id="navbarCollapse">
                <ul class="navbar-nav mb-2 mb-md-0">
                    <li class="nav-item align-self-md-center">
                        <a class="nav-link <?php echo active("welcome_page.php") ?>" aria-current="page" href="welcome_page.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown align-self-md-center">
                        <a class="nav-link dropdown-toggle <?php echo active_startwith("product") ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Product
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?php echo active("product_create.php") ?>" href="product_create.php">Create Product</a></li>
                            <li><a class="dropdown-item <?php echo active("product_read.php") ?>" href="product_read.php">Read Product</a></li>
                            <li><a class="dropdown-item <?php echo active("product_read_one.php") ?>" href="product_read_one.php">Read One Product</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown align-self-md-center">
                        <a class="nav-link dropdown-toggle <?php echo active_startwith("customer") ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Customer
                        </a>
                        <ul class="dropdown-menu align-self-md-center">
                            <li><a class="dropdown-item <?php echo active("customer_create.php") ?>" href="customer_create.php">Create Customer</a></li>
                            <li><a class="dropdown-item <?php echo active("customer_read.php") ?>" href="customer_read.php">Read Customer</a></li>
                            <li><a class="dropdown-item <?php echo active("customer_read_one.php") ?>" href="customer_read_one.php">Read One Customer</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown align-self-md-center">
                        <a class="nav-link dropdown-toggle <?php echo active_startwith("order") ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Order
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?php echo active("order_create.php") ?>" href="order_create.php">Create New Order</a></li>
                            <li><a class="dropdown-item <?php echo active("order_read.php") ?>" href="order_read.php">Read Order</a></li>
                            <li><a class="dropdown-item <?php echo active("order_read_one.php") ?>" href="order_read_one.php">Read Order One</a></li>
                        </ul>
                    </li>
                    <li class="nav-item align-self-md-center">
                        <a class="nav-link <?php echo active("contact.php") ?>" href="contact.php">Contact Us</a>
                    </li>
                    <li class="nav-item align-self-md-center">
                        <?php echo "<img class='ms-3 rounded' src='uploads/$customer_image' width='50px' />" ?>
                    </li>
                    <li class="nav-item align-self-md-center">
                        <a class="btn btn-danger ms-0 ms-md-4 mt-3 mt-md-0" href="logout.php">LOGOUT</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</header>