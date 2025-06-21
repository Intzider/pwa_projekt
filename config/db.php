<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projekt";
    $port = 3307;

    $conn = mysqli_connect($host, $username, $password, $dbname, $port);
    if (!$conn) {
        die("Greška pri povezivanju s bazom: " . mysqli_connect_error());
    }
?>
