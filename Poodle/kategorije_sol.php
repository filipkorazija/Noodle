<?php
// kategorije_sol.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || $_SESSION['tip'] != 'dijak') {
    header('location: prijava.php');
}

// Pridobi šole
$sql = "SELECT * FROM sole";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Kategorije Šol - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Kategorije Šol</h1>
        <ul>
            <?php while($sola = mysqli_fetch_assoc($result)): ?>
                <li>
                    <a href="programi.php?sola_id=<?php echo $sola['id']; ?>" class="text-blue-500 hover:underline">
                        <?php echo $sola['ime']; ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>
