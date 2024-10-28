<?php
// index.php
session_start();
include('povezava.php');

// Pridobi novice
$sql = "SELECT n.*, u.uporabnisko_ime FROM novice n JOIN uporabniki u ON n.avtor_id = u.id ORDER BY n.datum DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Noodle - Novice</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="container mx-auto bg-white p-8 rounded-lg shadow-md max-w-2xl">
        <h1 class="text-3xl font-bold mb-6 text-center">Novice</h1>
        <?php while($novica = mysqli_fetch_assoc($result)): ?>
            <div class="mb-8 p-4 rounded-lg <?php echo (strtotime($novica['datum']) > strtotime('-1 day')) ? 'bg-gray-200' : 'bg-gray-50'; ?>">
                <h2 class="text-2xl font-semibold"><?php echo $novica['naslov']; ?></h2>
                <p class="text-gray-600 text-sm">Objavil: <?php echo $novica['uporabnisko_ime']; ?> | Datum: <?php echo date('d.m.Y', strtotime($novica['datum'])); ?></p>
                <p class="mt-4"><?php echo nl2br($novica['vsebina']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>


</body>
</html>
