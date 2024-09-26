<?php
// uredi_novico.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || ($_SESSION['tip'] != 'profesor' && $_SESSION['tip'] != 'skrbnik')) {
    header('location: prijava.php');
}

$novica_id = $_GET['id'];
$avtor_id = $_SESSION['uporabnik_id'];

// Pridobi novico
$sql = "SELECT * FROM novice WHERE id='$novica_id' AND avtor_id='$avtor_id'";
$result = mysqli_query($conn, $sql);
$novica = mysqli_fetch_assoc($result);

if(!$novica) {
    header('location: uredi_novice.php');
}

if(isset($_POST['posodobi'])) {
    $naslov = $_POST['naslov'];
    $vsebina = $_POST['vsebina'];

    $sql = "UPDATE novice SET naslov='$naslov', vsebina='$vsebina' WHERE id='$novica_id'";
    if(mysqli_query($conn, $sql)) {
        $sporocilo = "Novica uspešno posodobljena.";
    } else {
        $napaka = "Napaka pri posodobitvi novice.";
    }
}

?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Uredi Novico - Noodle</title>
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

            <h2 class="text-2xl mb-6">Uredi Novico</h2>
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Naslov</label>
                    <input type="text" name="naslov" value="<?php echo $novica['naslov']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Vsebina</label>
                    <textarea name="vsebina" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo $novica['vsebina']; ?></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <button name="posodobi" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Posodobi Novico
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
