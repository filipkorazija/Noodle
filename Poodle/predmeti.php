<?php
// predmeti.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || $_SESSION['tip'] != 'dijak') {
    header('location: prijava.php');
}

$letnik_id = $_GET['letnik_id'];

// Pridobi predmete
$sql = "SELECT * FROM predmeti WHERE letnik_id='$letnik_id'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Predmeti - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Predmeti</h1>
        <ul>
            <?php while($predmet = mysqli_fetch_assoc($result)): ?>
                <li>
                    <a href="vpis_kljuca.php?predmet_id=<?php echo $predmet['id']; ?>" class="text-blue-500 hover:underline">
                        <?php echo $predmet['ime']; ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>
