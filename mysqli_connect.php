<?php
$mysqli = new MySQLi("localhost", "root", "", "book_store");
if($mysqli->connect_error) {
    echo $mysqli->connect_error;
    unset($mysqli);
}else{
    $mysqli->set_charset('utf8');
}
?>