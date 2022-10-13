<!DOCTYPE html>

<html>
    <head>
        <!-- bootstrap -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

        <title>Week3_Exercise 3</title>
    </head>
    <body class="p-2">
        <?php 
            $total = 0;
            $sign = "+";

            for ($num = 1; $num <= 100; $num++) {

                if ($num == 100) {
                    $sign = "=";
                }

                if ($num % 2 == 0) {
                    echo "<p class =\"fw-bold d-inline\"> $num $sign
                    </p>";
                } else {
                    echo "$num $sign";
                }

                $total += $num;
              } 
              if ($total % 2 == 0) {
                echo "<p class =\"fw-bold d-inline\"> $total</p>";
            } else {
                echo "$total";
            }
        ?>
    </body>
</html>  