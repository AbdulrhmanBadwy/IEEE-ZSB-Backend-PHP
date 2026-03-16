<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>
<body>
    <?php
        $names = "Dark Matter";
        $read = true ; 
        
        if($read){
            $message = "You have read $names";
        }else {
            $message = "You have Not  read $names";
        }

    ?>
    <h1>

            <!-- <?php
                echo $message;
            ?> -->
            <?= $message?>
    </h1>
</body>
</html>