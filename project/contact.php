<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
?>

<head>
    
    <?php include "bootstrap.php"; ?>

    <title>Contact</title>

    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/contact.css" />

</head>


<body>
    <!-- NAVBAR -->
    <?php
    include "navbar.php";

    if ($_GET) {
        $message = isset($_GET['message']) ? $_GET['message'] : "";

        if ($message == "mail_success") {
            echo "<div class='alert alert-success text-center container mt-5'>Your message with send succesfully.</div>";
        } else if ($message == "mail_empty") {
            echo "<div class='alert alert-danger text-center container mt-5'>Empty mail was not allowed to send.</div>";
        } else {
            echo "<div class='alert alert-danger  text-center container mt-5'>Unknown error happened</div>";
        }
    }
    ?>
    <!-- NAVBAR END -->
    <main>

        <!-- Content -->
        <div class="container contact-form">
            <div class="contact-image">
                <img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact" />
            </div>
            <form method="post" action="send.php">
                <h3>Drop Us a Message</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Your Name *" value="" />
                        </div>
                        <div class="form-group  mt-4">
                            <input type="text" name="email" class="form-control" placeholder="Your Email *" value="" />
                        </div>
                        <div class="form-group mt-4">
                            <input type="text" name="phone" class="form-control" placeholder="Your Phone Number *" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4 mt-md-auto">
                            <textarea name="msg" class="form-control" placeholder="Your Message *" style="width: 100%; height: 165px;"></textarea>
                        </div>
                    </div>
                    <div class="form-group mt-4 text-center">
                        <input type="submit" name="send" class="btnContact" value="Send Message" />
                    </div>
                </div>
            </form>
        </div>

        <!-- Content End -->

        <hr class="featurette-divider">

        <!-- FOOTER -->
        <footer class="container">
            <p class="float-end"><a class="text-white fw-bold" href="#">Back to top</a></p>
            <p class="text-white fw-bold">&copy; 2022 Chia Yeu Shyang</p>
        </footer>
    </main>
</body>

</html>