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
        <div class="row mt-4 mb-4">
            <div class="col-md-2"></div>
            <div class="col-md-8 border rounded bg-white">
                <h3 class="mt-2 mb-5 text-danger text-center">Checkout Form</h3>

                <?php
                    if($_SERVER['REQUEST_METHOD'] === 'POST') {

                        if(isset($_POST['checkout'])) {
                                
                            $errors = [];

                            $flag1 = $flag2 = false;

                            if(empty($_POST['firstname'])) {
                                $errors[] = "First Name cannot be empty";
                            } else {
                                $firstname = $mysqli->real_escape_string(trim($_POST['firstname']));
                            }

                            if(empty($_POST['lastname'])) {
                                $errors[] = "Last Name cannot be empty";
                            } else {
                                $lastname = $mysqli->real_escape_string(trim($_POST['lastname']));
                            }

                            if(empty($_POST['email'])) {
                                $errors[] = "Email cannot be empty";
                            } else {
                                $email = $mysqli->real_escape_string(trim($_POST['email']));
                            }

                            if(empty($_POST['address'])) {
                                $errors[] = "Address cannot be empty";
                            } else {
                                $address = $mysqli->real_escape_string(trim($_POST['address']));
                            }

                            if(empty($_POST['method'])) {
                                $errors[] = "Payment Method cannot be empty";
                            } else {
                                $method = $mysqli->real_escape_string(trim($_POST['method']));
                            }

                            if(empty($_POST['card_holder_name'])) {
                                $errors[] = "Card Holder Name cannot be empty";
                            } else {
                                $card_holder_name = $mysqli->real_escape_string(trim($_POST['card_holder_name']));
                            }

                            if(empty($_POST['card_number'])) {
                                $errors[] = "Card Number cannot be empty";
                            } else if (strlen($_POST['card_number']) != 16) {
                                $errors[] = "Enter valid card Number";
                            } else {
                                $card_number = $mysqli->real_escape_string(trim($_POST['card_number']));
                            }

                            if(empty($_POST['card_expiry'])) {
                                $errors[] = "Card Expiry cannot be empty";
                            } else {
                                $card_expiry = $mysqli->real_escape_string(trim($_POST['card_expiry']));
                            }

                            if(empty($_POST['card_cvv'])) {
                                $errors[] = "Card CVV cannot be empty";
                            } else if( strlen($_POST['card_cvv']) != 3 ) {
                                $errors[] = "Enter valid CVV number";
                            } else {
                                $card_cvv = $mysqli->real_escape_string(trim($_POST['card_cvv']));
                            }

                            if(empty($errors)) {
                        
                                //Insert into checkout DB
                                $query = 'INSERT INTO checkout (id, book_id, first_name, last_name, email, address) VALUES (DEFAULT, ?, ?, ?, ?, ?)';

                                $stmt = mysqli_prepare($mysqli, $query);

                                mysqli_stmt_bind_param($stmt, 'issss', $book_id, $firstname, $lastname, $email, $address);

                                $book_id = strip_tags($book_id);
                                $firstname = strip_tags($_POST['firstname']);
                                $lastname = strip_tags($_POST['lastname']);
                                $email = strip_tags($_POST['email']);
                                $address = strip_tags($_POST['address']);

                                mysqli_stmt_execute($stmt);

                                if (mysqli_stmt_affected_rows($stmt) == 1) {
                                    $flag1 =  true;
                                } else {
                                    echo '<p style="font-weight: bold; color: #C00">Failure</p>';
                                    echo '<p>' . mysqli_stmt_error($stmt) . '</p>';
                                }

                                mysqli_stmt_close($stmt);

                                //Get Checkout id
                                $result = $mysqli->query("SELECT id FROM checkout");
                                $num = $result->num_rows;
                                if($num > 0) {
                                    while($row = $result->fetch_object()) {
                                        $checkout_id = $row->id;
                                    }
                                }

                                //Insert into Payment DB
                                $query = 'INSERT INTO payment (id, checkout_id, method, card_holder_name, card_number, card_expiry, card_cvv) VALUES (DEFAULT, ?, ?, ?, ?, ?, ?)';

                                $stmt = mysqli_prepare($mysqli, $query);

                                mysqli_stmt_bind_param($stmt, 'issisi', $checkout_id, $method, $card_holder_name, $card_number, $card_expiry, $card_cvv);

                                $checkout_id = strip_tags($checkout_id);
                                $method = strip_tags($_POST['method']);
                                $card_holder_name = strip_tags($_POST['card_holder_name']);
                                $card_number = strip_tags($_POST['card_number']);
                                $card_expiry = strip_tags($_POST['card_expiry']);
                                $card_cvv = strip_tags($_POST['card_cvv']);

                                mysqli_stmt_execute($stmt);

                                if (mysqli_stmt_affected_rows($stmt) == 1) {
                                    $flag2 = true;
                                } else {
                                    echo '<p style="font-weight: bold; color: #C00">Failure</p>';
                                    echo '<p>' . mysqli_stmt_error($stmt) . '</p>';
                                }

                                mysqli_stmt_close($stmt);
                                mysqli_close($mysqli);
                            }

                            if(!empty($errors)) {
                                echo '<div class="alert alert-warning">
                                <ul>';
                                    foreach($errors as $err) {
                                        echo  '<li>'.$err.'</li>';
                                    }
                                echo '</ul>
                                </div>';
                            }

                            if($flag1 == true && $flag2 == true) {        
                                // echo "<script>window.location = order.php</script>";
                                header("Location: order.php");
                            }

                        }
                    }
                ?>

                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter your first name" value="<?php if(isset($_POST['firstname'])) { echo $_POST['firstname']; } ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter your last name" value="<?php if(isset($_POST['lastname'])) { echo $_POST['lastname']; } ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email id</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your Email id" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea rows="5" class="form-control" id="address" name="address" placeholder="Enter your Address" required><?php if(isset($_POST['address'])) { echo $_POST['address']; } ?></textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-3 mb-3">
                    
                    <h3 class="text-danger text-center mb-4">Payment Details</h3>

                    <div class="d-block my-3 text-center">
                        <div class="custom-control custom-radio">
                            <input id="credit" name="method" value="Credit Card" type="radio" class="custom-control-input" checked required>
                            <label class="custom-control-label" for="credit">Credit card</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input id="debit" name="method" value="Debit Card" type="radio" class="custom-control-input" required>
                            <label class="custom-control-label" for="debit">Debit card</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="card_holder_name" class="form-label">Name on Card</label>
                                <input type="text" class="form-control" id="card_holder_name" name="card_holder_name" placeholder="Enter name on card" value="<?php if(isset($_POST['card_holder_name'])) { echo $_POST['card_holder_name']; } ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Card Number</label>
                                <input type="number" class="form-control" id="card_number" name="card_number" placeholder="Enter card number" value="<?php if(isset($_POST['card_number'])) { echo $_POST['card_number']; } ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="card_expiry" class="form-label">Expiry</label>
                                <input type="month" class="form-control" id="card_expiry" name="card_expiry" placeholder="Enter card expiry" value="<?php if(isset($_POST['card_expiry'])) { echo $_POST['card_expiry']; } ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="card_cvv" class="form-label">CVV</label>
                                <input type="number" class="form-control" id="card_cvv" name="card_cvv" placeholder="Enter card CVV" value="<?php if(isset($_POST['card_cvv'])) { echo $_POST['card_cvv']; } ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button class="btn btn-success mt-3 mb-4" type="submit" name="checkout">Checkout</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>