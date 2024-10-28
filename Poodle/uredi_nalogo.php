<?php
// uredi_nalogo.php
session_start();
include('povezava.php');

// Preverimo, ali je uporabnik prijavljen in ima ustrezne pravice
if (!isset($_SESSION['uporabnik_id']) || ($_SESSION['tip'] != 'profesor' && $_SESSION['tip'] != 'skrbnik')) {
    header('location: prijava.php');
    exit();
}

$uporabnik_id = $_SESSION['uporabnik_id'];
$naloga_id = $_GET['id'];

// Preverimo, ali ima uporabnik pravico urejati to nalogo
if ($_SESSION['tip'] == 'profesor') {
    $sql_check = "SELECT * FROM naloge WHERE id='$naloga_id' AND profesor_id='$uporabnik_id'";
} else {
    $sql_check = "SELECT * FROM naloge WHERE id='$naloga_id'";
}
$result_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result_check) == 0) {
    $napaka = "Nimate dovoljenja za urejanje te naloge.";
} else {
    $naloga = mysqli_fetch_assoc($result_check);

    // Obdelava obrazca za posodobitev naloge
    if (isset($_POST['posodobi'])) {
        $naslov = mysqli_real_escape_string($conn, $_POST['naslov']);
        $opis = mysqli_real_escape_string($conn, $_POST['opis']);
        $datum_oddaje = $_POST['datum_oddaje'];
        $predmet_id = $_POST['predmet_id'];

        $sql_update = "UPDATE naloge SET naslov='$naslov', opis='$opis', datum_oddaje='$datum_oddaje', predmet_id='$predmet_id' WHERE id='$naloga_id'";
        if (mysqli_query($conn, $sql_update)) {
            $sporocilo = "Naloga uspešno posodobljena.";
            // Osvežimo podatke o nalogi
            $naloga['naslov'] = $naslov;
            $naloga['opis'] = $opis;
            $naloga['datum_oddaje'] = $datum_oddaje;
            $naloga['predmet_id'] = $predmet_id;
        } else {
            $napaka = "Napaka pri posodabljanju naloge.";
        }
    }

    // Pridobimo seznam predmetov
    if ($_SESSION['tip'] == 'profesor') {
        $sql_predmeti = "SELECT * FROM predmeti WHERE profesor_id='$uporabnik_id'";
    } else {
        $sql_predmeti = "SELECT * FROM predmeti";
    }
    $result_predmeti = mysqli_query($conn, $sql_predmeti);
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Uredi Nalogo - Noodle</title>
    <!-- Vključitev Tailwind CSS prek CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina strani -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Uredi Nalogo</h1>

        <?php if (isset($sporocilo)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $sporocilo; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($napaka)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $napaka; ?>
            </div>
        <?php else: ?>
            <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Naslov</label>
                    <input type="text" name="naslov" value="<?php echo htmlspecialchars($naloga['naslov']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Opis</label>
                    <textarea name="opis" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?php echo htmlspecialchars($naloga['opis']); ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Datum Oddaje</label>
                    <input type="date" name="datum_oddaje" value="<?php echo $naloga['datum_oddaje']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Predmet</label>
                    <select name="predmet_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <?php while ($predmet = mysqli_fetch_assoc($result_predmeti)): ?>
                            <option value="<?php echo $predmet['id']; ?>" <?php if ($predmet['id'] == $naloga['predmet_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($predmet['ime']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <button name="posodobi" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Posodobi
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
