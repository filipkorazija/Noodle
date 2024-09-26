<?php
// letniki.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || $_SESSION['tip'] != 'dijak') {
    header('location: prijava.php');
}

$program_id = $_GET['program_id'];

// Pridobi letnike
$sql = "SELECT * FROM letniki WHERE program_id='$program_id'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Letniki - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Letniki</h1>
        <ul>
            <?php while($letnik = mysqli_fetch_assoc($result)): ?>
                <li>
                    <a href="predmeti.php?letnik_id=<?php echo $letnik['id']; ?>" class="text-blue-500 hover:underline">
                        <?php echo $letnik['ime']; ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>
