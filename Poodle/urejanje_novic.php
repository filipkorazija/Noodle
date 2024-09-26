<?php
// uredi_novice.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || ($_SESSION['tip'] != 'profesor' && $_SESSION['tip'] != 'skrbnik')) {
    header('location: prijava.php');
}

$avtor_id = $_SESSION['uporabnik_id'];

// Dodaj novico
if(isset($_POST['dodaj'])) {
    $naslov = $_POST['naslov'];
    $vsebina = $_POST['vsebina'];

    $sql = "INSERT INTO novice (naslov, vsebina, avtor_id) VALUES ('$naslov', '$vsebina', '$avtor_id')";
    if(mysqli_query($conn, $sql)) {
        $sporocilo = "Novica uspešno dodana.";
    } else {
        $napaka = "Napaka pri dodajanju novice.";
    }
}

// Pridobi novice
$sql = "SELECT * FROM novice WHERE avtor_id='$avtor_id' ORDER BY datum DESC";
$result = mysqli_query($conn, $sql);

// Brisanje novice
if(isset($_GET['brisi_id'])) {
    $id = $_GET['brisi_id'];
    $sql = "DELETE FROM novice WHERE id='$id'";
    mysqli_query($conn, $sql);
    header('location: uredi_novice.php');
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Uredi Novice - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <div class="w-full max-w-2xl mx-auto">
            <?php if(isset($napaka)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $napaka; ?>
                </div>
            <?php endif; ?>
            <?php if(isset($sporocilo)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $sporocilo; ?>
                </div>
            <?php endif; ?>

            <h2 class="text-2xl mb-6">Dodaj Novico</h2>
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-10" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Naslov</label>
                    <input type="text" name="naslov" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Vsebina</label>
                    <textarea name="vsebina" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <button name="dodaj" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Dodaj
                    </button>
                </div>
            </form>

            <h2 class="text-2xl mb-6">Moje Novice</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Naslov</th>
                        <th class="py-2">Datum</th>
                        <th class="py-2">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($novica = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo $novica['naslov']; ?></td>
                            <td class="border px-4 py-2"><?php echo date('d.m.Y H:i', strtotime($novica['datum'])); ?></td>
                            <td class="border px-4 py-2">
                                <a href="uredi_novico.php?id=<?php echo $novica['id']; ?>" class="text-blue-500 hover:underline">Uredi</a> |
                                <a href="uredi_novice.php?brisi_id=<?php echo $novica['id']; ?>" class="text-red-500 hover:underline">Briši</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
