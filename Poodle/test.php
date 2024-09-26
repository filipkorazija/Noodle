<?php
// test.php
include('povezava.php');

// Preveri, ali skrbniški račun že obstaja
$sql = "SELECT * FROM uporabniki WHERE tip='skrbnik' AND uporabnisko_ime='admin'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
    echo "Skrbniški račun že obstaja. Odstranite test.php iz strežnika.";
} else {
    // Ustvari skrbniški račun
    $uporabnisko_ime = 'admin';
    $geslo = 'admin123'; // Geslo je 'admin123'
    $geslo_hash = password_hash($geslo, PASSWORD_BCRYPT);
    $ime = 'Admin';
    $priimek = 'Skrbnik';
    $email = 'admin@noodle.si';
    $tip = 'skrbnik';

    $sql = "INSERT INTO uporabniki (uporabnisko_ime, geslo, ime, priimek, email, tip) VALUES ('$uporabnisko_ime', '$geslo_hash', '$ime', '$priimek', '$email', '$tip')";

    if(mysqli_query($conn, $sql)) {
        echo "Skrbniški račun uspešno ustvarjen.<br>";
        echo "Uporabniško ime: admin<br>";
        echo "Geslo: admin123<br>";
        echo "Prosimo, prijavite se in spremenite geslo.";
    } else {
        echo "Napaka pri ustvarjanju skrbniškega računa: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
