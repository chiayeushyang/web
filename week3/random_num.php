<!DOCTYPE html>

<html>
    <head>
        <title>Week3_Exercise 1</title>
        <style>
            .green {
                color: green;
            }

            .blue {
                color: blue;
            }

            .red {
                color: red;
            }
        </style>
    </head>
    <body>
        <?php 
        $num1 = rand(100, 200);
        $num2 = rand(100, 200);
        $sum = $num1 + $num2;
        $multiple = $num1 * $num2;

        echo "<i class=\"green\">Number 1 = $num1</i><br>";
        echo "<i class=\"blue\">Numer 2 = $num2</i><br>";
        echo "<b class=\"red\">Sum = $sum</b><br>";
        echo "<b><i>Multiple = $multiple</i></b><br>";
        ?>
    </body>
</html>