<?php
    session_start();
    require_once ('mysqli_connect.php');

    if(!$_SESSION['book_id']){
        header("location: index.php");
    }

    $book_id = $_SESSION['book_id'];

    $result = $mysqli->query("SELECT * FROM bookinventory WHERE id = $book_id");

    $num = $result->num_rows;

    if($num > 0) {
        if($row = $result->fetch_object()) {
            
            $book_name = $row->book_name;
            $author = $row->author;
            $quantity = $row->quantity;

        }
    }


    $quantity = $quantity - 1;

    $query = "UPDATE bookinventory SET quantity = $quantity WHERE id = $book_id";

    if ($mysqli->query($query) === TRUE) {
    } else {
        echo "Error updating record: " . $mysqli->error;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row text-center">
            <h1 class="text-success">Successfull</h1>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 mt-3 border rounded">
                <div class="row">
                    <div class="col-md-6">
                        <b>Book Name:</b> <?php echo $book_name; ?>
                    </div>
                    <div class="col-md-6">
                        <b>Book Author:</b> <?php echo $author; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row text-center">
            <h3>Order Details</h3>
            <p class="text-muted">Book Name: <?php echo $book_name; ?></p>
            <a href="index.php">
                <button class="btn btn-danger">Home</button>
            </a>
        </div>
    </div>
</body>
</html>