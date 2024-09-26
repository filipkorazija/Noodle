<?php
// dodaj_nalogo.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || ($_SESSION['tip'] != 'profesor' && $_SESSION['tip'] != 'skrbnik')) {
    header('location: prijava.php');
}

$avtor_id = $_SESSION['uporabnik_id'];

// Dodajanje naloge
if(isset($_POST['dodaj_nalogo'])) {
    $naslov = $_POST['naslov'];
    $opis = $_POST['opis'];
    $datum_oddaje = $_POST['datum_oddaje'];
    $predmet_id = $_POST['predmet_id'];

    $sql = "INSERT INTO naloge (naslov, opis, datum_oddaje, predmet_id, avtor_id) VALUES ('$naslov', '$opis', '$datum_oddaje', '$predmet_id', '$avtor_id')";
    if(mysqli_query($conn, $sql)) {
        $sporocilo = "Naloga uspešno dodana.";
    } else {
        $napaka = "Napaka pri dodajanju naloge.";
    }
}

// Pridobi predmete
if($_SESSION['tip'] == 'skrbnik') {
    $sql = "SELECT * FROM predmeti";
} else {
    // Če želite omejiti profesorje na določene predmete, lahko dodate dodatno logiko
    $sql = "SELECT * FROM predmeti";
}
$predmeti_result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Nalogo - Noodle</title>
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

            <h2 class="text-2xl mb-6">Dodaj Novo Nalogo</h2>
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Naslov</label>
                    <input type="text" name="naslov" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Opis</label>
                    <textarea name="opis" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Datum Oddaje</label>
                    <input type="date" name="datum_oddaje" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Predmet</label>
                    <select name="predmet_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Izberi Predmet</option>
                        <?php while($predmet = mysqli_fetch_assoc($predmeti_result)): ?>
                            <option value="<?php echo $predmet['id']; ?>"><?php echo $predmet['ime']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <button name="dodaj_nalogo" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Dodaj Nalogo
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
