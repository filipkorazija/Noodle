<?php
// uredi_naloge.php
session_start();
include('povezava.php');

// Preverimo, ali je uporabnik prijavljen in ima ustrezne pravice
if (!isset($_SESSION['uporabnik_id']) || ($_SESSION['tip'] != 'profesor' && $_SESSION['tip'] != 'skrbnik')) {
    header('location: prijava.php');
    exit();
}

$uporabnik_id = $_SESSION['uporabnik_id'];

// Brisanje naloge
if (isset($_GET['brisi_id'])) {
    $naloga_id = $_GET['brisi_id'];

    // Preverimo, ali ima uporabnik pravico izbrisati to nalogo
    if ($_SESSION['tip'] == 'profesor') {
        $sql_check = "SELECT n.*
                      FROM naloge n
                      JOIN predmeti p ON n.predmet_id = p.id
                      WHERE n.id = '$naloga_id' AND p.profesor_id = '$uporabnik_id'";
    } else {
        $sql_check = "SELECT * FROM naloge WHERE id = '$naloga_id'";
    }
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        $sql_delete = "DELETE FROM naloge WHERE id='$naloga_id'";
        mysqli_query($conn, $sql_delete);
        $sporocilo = "Naloga uspešno izbrisana.";
    } else {
        $napaka = "Nimate dovoljenja za brisanje te naloge.";
    }
}

// Pridobimo seznam nalog
if ($_SESSION['tip'] == 'profesor') {
    // Profesor vidi samo svoje naloge
    $sql = "SELECT n.id AS naloga_id, n.naslov, n.datum_oddaje, p.ime AS predmet_ime
            FROM naloge n
            JOIN predmeti p ON n.predmet_id = p.id
            WHERE p.profesor_id = '$uporabnik_id'
            ORDER BY n.datum_oddaje DESC";
} else {
    // Skrbnik vidi vse naloge
    $sql = "SELECT n.id AS naloga_id, n.naslov, n.datum_oddaje, p.ime AS predmet_ime
            FROM naloge n
            JOIN predmeti p ON n.predmet_id = p.id
            ORDER BY n.datum_oddaje DESC";
}
$result_naloge = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Uredi Naloge - Noodle</title>
    <!-- Vključitev Tailwind CSS prek CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina strani -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Uredi Naloge</h1>

        <?php if (isset($sporocilo)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $sporocilo; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($napaka)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $napaka; ?>
            </div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($result_naloge) > 0): ?>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Naslov</th>
                        <th class="py-2 px-4 border-b">Predmet</th>
                        <th class="py-2 px-4 border-b">Datum Oddaje</th>
                        <th class="py-2 px-4 border-b">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($naloga = mysqli_fetch_assoc($result_naloge)): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($naloga['naslov']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($naloga['predmet_ime']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo date('d.m.Y', strtotime($naloga['datum_oddaje'])); ?></td>
                            <td class="py-2 px-4 border-b">
                                <a href="uredi_nalogo.php?id=<?php echo $naloga['naloga_id']; ?>" class="text-blue-500 hover:underline">Uredi</a> |
                                <a href="uredi_naloge.php?brisi_id=<?php echo $naloga['naloga_id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Ali res želite izbrisati to nalogo?');">Izbriši</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Ni nalog za prikaz.</p>
        <?php endif; ?>
    </div>
</body>
</html>
