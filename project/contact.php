<!DOCTYPE html>
<html>

<?php
include 'check_session.php';
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Contact</title>

    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/contact.css" />
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

        <!-- Content -->
        <div class="container contact-form">
            <div class="contact-image">
                <img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact" />
            </div>
            <?php
            if ($_POST) {
                $txtName = $_POST['txtName'];
                $txtEmail = $_POST['txtEmail'];
                $txtPhone = $_POST['txtPhone'];
                $txtMsg = $_POST['txtMsg'];

 
                // the message
                $msg = "First line of text\nSecond line of text";
                
                // use wordwrap() if lines are longer than 70 characters
                $msg = wordwrap($msg,70);
                
                // send email
                var_dump(mail("yeushyang020825@gmail.com","My subject",$msg));
            }
            ?>
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <h3>Drop Us a Message</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="txtName" class="form-control" placeholder="Your Name *" value="" />
                        </div>
                        <div class="form-group  mt-4">
                            <input type="text" name="txtEmail" class="form-control" placeholder="Your Email *" value="" />
                        </div>
                        <div class="form-group mt-4">
                            <input type="text" name="txtPhone" class="form-control" placeholder="Your Phone Number *" value="" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4 mt-md-auto">
                            <textarea name="txtMsg" class="form-control" placeholder="Your Message *" style="width: 100%; height: 165px;"></textarea>
                        </div>
                    </div>
                    <div class="form-group mt-4 text-center">
                        <input type="submit" name="btnSubmit" class="btnContact" value="Send Message" />
                    </div>
                </div>
            </form>
        </div>

        <!-- Content End -->

        <hr class="featurette-divider">

        <!-- FOOTER -->
        <footer class="container">
            <p class="float-end"><a class="text-white fw-bold" href="#">Back to top</a></p>
            <p class="text-white fw-bold">&copy; 2022 Chia Yeu Shyang &middot;
                <a class="text-white fw-bold" href="#">Privacy</a> &middot;
                <a class="text-white fw-bold" href="#">Terms</a>
            </p>
        </footer>
    </main>
</body>

</html>