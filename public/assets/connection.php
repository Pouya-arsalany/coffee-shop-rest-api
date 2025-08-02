<?php
function connect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "store_db";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    return $conn;

}