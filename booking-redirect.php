<?php

    session_start();
    
    $book_id = $_GET['book_id'];

    $_SESSION['book_id'] = $book_id;

    header("Location: checkout.php");

?>