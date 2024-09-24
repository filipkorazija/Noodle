<?php
include 'db.php'; // Povezava z bazo

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

        <table class="min-w-full bg-white shadow-md rounded">
            <thead>
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Ime predmeta</th>
                    <th class="px-6 py-4">Profesorji</th>
                    <th class="px-6 py-4">Kljuc</th>
                    <th class="px-6 py-4">Oddane naloge</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td class="border px-6 py-4"><?php echo htmlspecialchars($row['ID']); ?></td>
                        <td class="border px-6 py-4"><?php echo htmlspecialchars($row['Ime_predmeta']); ?></td>
                        <td class="border px-6 py-4"><?php echo htmlspecialchars($row['Profesorji']); ?></td>
                        <td class="border px-6 py-4"><?php echo $row['Kljuc'] ? htmlspecialchars($row['Kljuc']) : 'Ni ključa'; ?></td>
                        <td class="border px-6 py-4"><?php echo $row['Oddane_naloge'] ? htmlspecialchars($row['Oddane_naloge']) : 'Ni oddanih nalog'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
