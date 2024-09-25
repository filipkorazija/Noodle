<?php
include 'db.php'; // Povezava z bazo
session_start();
if (!isset($_SESSION['user_id'])) {
    // Če uporabnik ni prijavljen, ga preusmeri na login.php
    header("Location: login.php");
    exit();
}
// Pridobi vse predmete iz baze
$sql = "SELECT * FROM predmeti"; // SQL query as a string
$stmt = $conn->prepare($sql); // Prepare the SQL statement
$stmt->execute(); // Execute the prepared statement
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seznam predmetov</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
</head>
<body class="bg-gray-100">
    <?php include 'nav.php'; ?>

    <div class="container mx-auto p-4">
        <br><br>
        <h1 class="text-2xl font-bold mb-4">Seznam predmetov</h1>

        <ul class="list-disc pl-5">
            <?php foreach ($result as $row): ?>
                <li class="mb-2">
                    <?php echo htmlspecialchars($row['Ime_predmeta']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</body>
</html>
