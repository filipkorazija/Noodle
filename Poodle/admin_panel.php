<?php
// admin_panel.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || $_SESSION['tip'] != 'skrbnik') {
    header('location: prijava.php');
}

// Pridobi uporabnike
$sql = "SELECT * FROM uporabniki";
$result = mysqli_query($conn, $sql);

// Brisanje uporabnika
if(isset($_GET['brisi_id'])) {
    $id = $_GET['brisi_id'];
    $sql = "DELETE FROM uporabniki WHERE id='$id'";
    mysqli_query($conn, $sql);
    header('location: admin_panel.php');
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Upravljanje Uporabnikov</h1>
            <a href="dodaj_uporabnika.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Dodaj Uporabnika</a>
        </div>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2">ID</th>
                    <th class="py-2">Uporabniško Ime</th>
                    <th class="py-2">Ime</th>
                    <th class="py-2">Priimek</th>
                    <th class="py-2">Email</th>
                    <th class="py-2">Tip</th>
                    <th class="py-2">Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php while($uporabnik = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $uporabnik['id']; ?></td>
                        <td class="border px-4 py-2"><?php echo $uporabnik['uporabnisko_ime']; ?></td>
                        <td class="border px-4 py-2"><?php echo $uporabnik['ime']; ?></td>
                        <td class="border px-4 py-2"><?php echo $uporabnik['priimek']; ?></td>
                        <td class="border px-4 py-2"><?php echo $uporabnik['email']; ?></td>
                        <td class="border px-4 py-2"><?php echo ucfirst($uporabnik['tip']); ?></td>
                        <td class="border px-4 py-2">
                            <a href="uredi_uporabnika.php?id=<?php echo $uporabnik['id']; ?>" class="text-blue-500 hover:underline">Uredi</a> |
                            <a href="admin_panel.php?brisi_id=<?php echo $uporabnik['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Ali res želite izbrisati tega uporabnika?');">Briši</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
