<!DOCTYPE html>

<html>
    <head>
        <!-- bootstrap -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

        <title>Week3_Exercise 2</title>
    </head>
    <body>
        <?php 
        $num1 = rand(100, 200);
        $num2 = rand(100, 200);
        $sum = $num1 + $num2;
        $multiple = $num1 * $num2;

        if ($num1 > $num2) {
            echo "
            <div class=\"row justify-content-evenly\">

                <diV class=\"col-5\">
                    <div class=\"card text-bg-success\">
                      <div class=\"card-body fs-1 text-center\">
                        <b>$num1</b>
                      </div>
                    </div> 
                </div>

                <div class=\"col-5\">
                    <div class=\"card text-bg-danger\">
                      <div class=\"card-body text-center\">
                        $num2
                      </div>
                    </div> 
                </div>

            </div>";
        } else {
            echo "
            <div class=\"row justify-content-evenly\">

                <div class=\"col-5\">
                    <div class=\"card text-bg-danger\">
                      <div class=\"card-body text-center\">
                        $num1
                      </div>
                    </div> 
                </div>

                <diV class=\"col-5\">
                    <div class=\"card text-bg-success\">
                      <div class=\"card-body fs-1 text-center\">
                        <b>$num2</b>
                      </div>
                    </div> 
                </div>

            </div>";
        }
        ?>
    </body>
</html>