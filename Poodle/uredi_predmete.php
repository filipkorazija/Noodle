<?php
// uredi_predmete.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || ($_SESSION['tip'] != 'profesor' && $_SESSION['tip'] != 'skrbnik')) {
    header('location: prijava.php');
}

// Brisanje predmeta
if(isset($_GET['brisi_id'])) {
    $id = $_GET['brisi_id'];
    $sql = "DELETE FROM predmeti WHERE id='$id'";
    mysqli_query($conn, $sql);
    header('location: uredi_predmete.php');
}

// Pridobitev seznam predmetov
$sql = "SELECT p.id AS predmet_id, p.ime AS predmet_ime, l.ime AS letnik_ime, pr.ime AS program_ime, s.ime AS sola_ime
        FROM predmeti p
        JOIN letniki l ON p.letnik_id = l.id
        JOIN programi pr ON l.program_id = pr.id
        JOIN sole s ON pr.sola_id = s.id
        ORDER BY s.ime, pr.ime, l.ime, p.ime";
$result_predmeti = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Uredi Predmete - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Uredi Predmete</h1>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2">Ime Predmeta</th>
                    <th class="py-2">Letnik</th>
                    <th class="py-2">Program</th>
                    <th class="py-2">Šola</th>
                    <th class="py-2">Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php while($predmet = mysqli_fetch_assoc($result_predmeti)): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $predmet['predmet_ime']; ?></td>
                        <td class="border px-4 py-2"><?php echo $predmet['letnik_ime']; ?></td>
                        <td class="border px-4 py-2"><?php echo $predmet['program_ime']; ?></td>
                        <td class="border px-4 py-2"><?php echo $predmet['sola_ime']; ?></td>
                        <td class="border px-4 py-2">
                            <a href="uredi_predmet.php?id=<?php echo $predmet['predmet_id']; ?>" class="text-blue-500 hover:underline">Uredi</a> |
                            <a href="uredi_predmete.php?brisi_id=<?php echo $predmet['predmet_id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Ali res želite izbrisati ta predmet?');">Briši</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
