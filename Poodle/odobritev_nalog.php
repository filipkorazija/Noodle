<?php
// odobritev_nalog.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || ($_SESSION['tip'] != 'profesor' && $_SESSION['tip'] != 'skrbnik')) {
    header('location: prijava.php');
}

// Pridobi vse oddane naloge
$sql = "SELECT on.*, u.uporabnisko_ime, n.naslov AS naslov_naloge
        FROM oddane_naloge on
        JOIN uporabniki u ON on.dijak_id = u.id
        JOIN naloge n ON on.naloga_id = n.id
        ORDER BY on.datum_oddaje DESC";
$result = mysqli_query($conn, $sql);

// Posodobitev ocene
if(isset($_POST['posodobi_oceno'])) {
    $oddana_naloga_id = $_POST['oddana_naloga_id'];
    $ocena = $_POST['ocena'];

    $sql = "UPDATE oddane_naloge SET ocena='$ocena' WHERE id='$oddana_naloga_id'";
    mysqli_query($conn, $sql);
    header('location: odobritev_nalog.php');
}

?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Pregled Oddanih Nalog - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Pregled Oddanih Nalog</h1>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2">Dijak</th>
                    <th class="py-2">Naloga</th>
                    <th class="py-2">Datum Oddaje</th>
                    <th class="py-2">Datoteka</th>
                    <th class="py-2">Ocena</th>
                    <th class="py-2">Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php while($oddana_naloga = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $oddana_naloga['uporabnisko_ime']; ?></td>
                        <td class="border px-4 py-2"><?php echo $oddana_naloga['naslov_naloge']; ?></td>
                        <td class="border px-4 py-2"><?php echo date('d.m.Y H:i', strtotime($oddana_naloga['datum_oddaje'])); ?></td>
                        <td class="border px-4 py-2">
                            <a href="<?php echo $oddana_naloga['datoteka']; ?>" class="text-blue-500 hover:underline">Prenesi</a>
                        </td>
                        <td class="border px-4 py-2"><?php echo $oddana_naloga['ocena']; ?></td>
                        <td class="border px-4 py-2">
                            <form method="POST" class="flex items-center">
                                <input type="hidden" name="oddana_naloga_id" value="<?php echo $oddana_naloga['id']; ?>">
                                <input type="number" name="ocena" min="1" max="5" value="<?php echo $oddana_naloga['ocena']; ?>" class="shadow appearance-none border rounded w-16 py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">
                                <button name="posodobi_oceno" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline" type="submit">
                                    Shrani
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
