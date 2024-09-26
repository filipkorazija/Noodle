<?php
// naloge.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id'])) {
    header('location: login.php');
    exit();
}

$predmet_id = $_GET['predmet_id'];
$uporabnik_id = $_SESSION['uporabnik_id'];

// Preveri, ali uporabnik ima dostop do predmeta
if($_SESSION['tip'] == 'dijak') {
    // Preveri, ali je dijak vpisan v predmet
    $sql = "SELECT * FROM prijave_predmetov WHERE dijak_id='$uporabnik_id' AND predmet_id='$predmet_id'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0) {
        header('location: vpis_kljuca.php?predmet_id=' . $predmet_id);
        exit();
    }
}

// Pridobi naloge za predmet
$sql = "SELECT * FROM naloge WHERE predmet_id='$predmet_id' ORDER BY datum_oddaje DESC";
$result = mysqli_query($conn, $sql);

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
    <title>Naloge - <?php echo $predmet_info['predmet_ime']; ?> - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-4"><?php echo $predmet_info['predmet_ime']; ?></h1>
        <p class="text-gray-600 mb-6"><?php echo $predmet_info['sola_ime']; ?> > <?php echo $predmet_info['program_ime']; ?> > <?php echo $predmet_info['letnik_ime']; ?></p>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($naloga = mysqli_fetch_assoc($result)): ?>
                <div class="mb-8 p-4 border rounded">
                    <h2 class="text-2xl font-semibold"><?php echo $naloga['naslov']; ?></h2>
                    <p class="text-gray-600 text-sm">Datum oddaje: <?php echo date('d.m.Y', strtotime($naloga['datum_oddaje'])); ?></p>
                    <p class="mt-4"><?php echo nl2br($naloga['opis']); ?></p>
                    <?php if($_SESSION['tip'] == 'dijak'): ?>
                        <a href="oddaj_nalogo.php?naloga_id=<?php echo $naloga['id']; ?>" class="text-blue-500 hover:underline">Oddaj Nalogo</a>
                    <?php endif; ?>
                    <?php if($_SESSION['tip'] == 'profesor' || $_SESSION['tip'] == 'skrbnik'): ?>
                        <a href="pregled_oddaj.php?naloga_id=<?php echo $naloga['id']; ?>" class="text-blue-500 hover:underline">Preglej Oddaje</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Za ta predmet še ni dodanih nalog.</p>
        <?php endif; ?>
    </div>
</body>
</html>
