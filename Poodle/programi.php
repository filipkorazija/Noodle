<?php
// programi.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || $_SESSION['tip'] != 'dijak') {
    header('location: prijava.php');
}

$sola_id = $_GET['sola_id'];

// Pridobi programe
$sql = "SELECT * FROM programi WHERE sola_id='$sola_id'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Programi - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Programi</h1>
        <ul>
            <?php while($program = mysqli_fetch_assoc($result)): ?>
                <li>
                    <a href="letniki.php?program_id=<?php echo $program['id']; ?>" class="text-blue-500 hover:underline">
                        <?php echo $program['ime']; ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>
