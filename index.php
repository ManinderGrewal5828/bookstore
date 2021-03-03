<?php
    require_once ('mysqli_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <?php
include 'includes/header.html';
?>

    <div class="container">
        <table class="table mt-5">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Book Name</th>
                    <th scope="col">Author</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Buy</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $r = $mysqli->query("SELECT * FROM bookinventory");
 
                    $num = $r->num_rows;
                
                    if($num > 0) {
                        while($row = $r->fetch_object()) {
                            
                            $book_id = $row->id;
                            $book_name = $row->book_name;
                            $author = $row->author;
                            $quantity = $row->quantity;

                            echo '
                                <tr>
                                    <th scope="row">'.$book_id.'</th>
                                    <td>'.$book_name.'</td>
                                    <td>'.$author.'</td>
                                    <td>'.$quantity.'</td>
                                    <td>
                                        <a href="booking-redirect.php?book_id='.$book_id.'">Buy Book</a>
                                    </td>
                                </tr> 
                            ';

                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
