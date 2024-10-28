<?php
// vpis_kljuca.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || $_SESSION['tip'] != 'dijak') {
    header('location: prijava.php');
    exit();
}

$predmet_id = $_GET['predmet_id'];

// Preveri, ali je dijak že vpisan v predmet
$dijak_id = $_SESSION['uporabnik_id'];
$sql_check = "SELECT * FROM prijave_predmetov WHERE dijak_id='$dijak_id' AND predmet_id='$predmet_id'";
$result_check = mysqli_query($conn, $sql_check);
if(mysqli_num_rows($result_check) > 0) {
    // Dijak je že vpisan v predmet, preusmerimo ga na naloge
    header('location: naloge.php?predmet_id=' . $predmet_id);
    exit();
}

if(isset($_POST['vpis_kljuca'])) {
    $vnesen_kljuc = mysqli_real_escape_string($conn, $_POST['kljuc']);

    $sql = "SELECT * FROM predmeti WHERE id='$predmet_id' AND kljuc='$vnesen_kljuc'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1) {
        // Vpiši dijaka v predmet
        $sql_insert = "INSERT INTO prijave_predmetov (dijak_id, predmet_id) VALUES ('$dijak_id', '$predmet_id')";
        mysqli_query($conn, $sql_insert);
        header('location: naloge.php?predmet_id=' . $predmet_id);
        exit();
    } else {
        $napaka = "Napačen ključ.";
    }
}

// Pridobi informacije o predmetu
$sql_predmet = "SELECT p.ime AS predmet_ime, l.ime AS letnik_ime, pr.ime AS program_ime, s.ime AS sola_ime
                FROM predmeti p
                JOIN letniki l ON p.letnik_id = l.id
                JOIN programi pr ON l.program_id = pr.id
                JOIN sole s ON pr.sola_id = s.id
                WHERE p.id='$predmet_id'";
$result_predmet = mysqli_query($conn, $sql_predmet);
$predmet_info = mysqli_fetch_assoc($result_predmet);
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Vpis Ključa - <?php echo $predmet_info['predmet_ime']; ?> - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <div class="w-full max-w-md mx-auto">
            <?php if(isset($napaka)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $napaka; ?>
                </div>
            <?php endif; ?>

            <h2 class="text-center text-2xl mb-6">Vpis Ključa za Predmet</h2>
            <p class="text-center mb-4"><?php echo $predmet_info['predmet_ime']; ?></p>
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST">
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ključ</label>
                    <input type="text" name="kljuc" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button name="vpis_kljuca" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Vpis
                    </button>
                    <a href="kategorije_sol.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Nazaj na Kategorije
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
