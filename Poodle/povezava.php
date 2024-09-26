<?php
// povezava.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "noodle";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Povezava ni uspela: " . mysqli_connect_error());
}
?>
