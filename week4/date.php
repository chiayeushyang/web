<!DOCTYPE html>

<html>

<head>
    <!-- bootstrap -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <title>Week4_Exercise 1</title>
</head>

<body>
    <div class="row">
        <div class="col-12 col-sm-4">
            <select class="form-select form-select-lg mb-3 bg-info text-light" aria-label="Date">
                <option selected>DAY</option>
                <?php
                $current_day = date("j");
                $current_month = date("m");
                $current_year = date("Y");

                for ($num = 1; $num <= 31; $num++) {
                    if($num == $current_day) {
                        echo "<option value=\"$num\" selected>$num</option>";
                    } else {
                        echo "<option value=\"$num\">$num</option> }";
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-12 col-sm-4">
            <select class="form-select form-select-lg mb-3 bg-warning " aria-label="Date">
                <option selected>MONTH</option>
                <?php
                for ($num = 1; $num <= 12; $num++) {
                    if($num == $current_month) {
                        echo "<option value=\"$num\" selected>$num</option>";
                    } else {
                        echo "<option value=\"$num\">$num</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-12 col-sm-4">
            <select class="form-select form-select-lg mb-3 bg-danger text-light" aria-label="Date">
                <option selected>YEAR</option>
                <?php
                for ($num = 1900; $num <= $current_year; $num++) {
                    if($num == $current_year) {
                        echo "<option value=\"$num\" selected>$num</option>";
                    } else {
                        echo "<option value=\"$num\">$num</option>";
                    }
                    
                }
                ?>
            </select>
        </div>
    </div>
</body>

</html>