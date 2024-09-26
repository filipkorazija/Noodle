<?php
// oddaj_nalogo.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || $_SESSION['tip'] != 'dijak') {
    header('location: prijava.php');
}

$naloga_id = $_GET['naloga_id'];
$dijak_id = $_SESSION['uporabnik_id'];

if(isset($_POST['oddaj'])) {
    $target_dir = "uploads/";
    $datoteka = $target_dir . basename($_FILES["datoteka"]["name"]);
    move_uploaded_file($_FILES["datoteka"]["tmp_name"], $datoteka);

    // Preveri, ali je že oddal nalogo
    $sql = "SELECT * FROM oddane_naloge WHERE naloga_id='$naloga_id' AND dijak_id='$dijak_id'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0) {
        $sql = "INSERT INTO oddane_naloge (naloga_id, dijak_id, datoteka) VALUES ('$naloga_id', '$dijak_id', '$datoteka')";
    } else {
        $sql = "UPDATE oddane_naloge SET datoteka='$datoteka', datum_oddaje=NOW() WHERE naloga_id='$naloga_id' AND dijak_id='$dijak_id'";
    }
    if(mysqli_query($conn, $sql)) {
        $sporocilo = "Naloga uspešno oddana.";
    } else {
        $napaka = "Napaka pri oddaji naloge.";
    }
}

// Pridobi nalogo
$sql = "SELECT * FROM naloge WHERE id='$naloga_id'";
$result = mysqli_query($conn, $sql);
$naloga = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Oddaj Nalogo - Noodle</title>
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
            <?php if(isset($sporocilo)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $sporocilo; ?>
                </div>
            <?php endif; ?>

            <h2 class="text-center text-2xl mb-6"><?php echo $naloga['naslov']; ?></h2>
            <p class="mb-4"><?php echo nl2br($naloga['opis']); ?></p>
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" enctype="multipart/form-data">
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Naloži Datoteko</label>
                    <input type="file" name="datoteka" class="w-full text-gray-700">
                </div>
                <div class="flex items-center justify-between">
                    <button name="oddaj" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Oddaj
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
