<?php
// dodaj_predmet.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || ($_SESSION['tip'] != 'profesor' && $_SESSION['tip'] != 'skrbnik')) {
    header('location: prijava.php');
}

if(isset($_POST['dodaj_predmet'])) {
    $ime_predmeta = mysqli_real_escape_string($conn, $_POST['ime_predmeta']);
    $letnik_id = $_POST['letnik_id'];
    $kljuc = mysqli_real_escape_string($conn, $_POST['kljuc']);

    $sql = "INSERT INTO predmeti (ime, letnik_id, kljuc) VALUES ('$ime_predmeta', '$letnik_id', '$kljuc')";
    if(mysqli_query($conn, $sql)) {
        $sporocilo = "Predmet uspešno dodan.";
    } else {
        $napaka = "Napaka pri dodajanju predmeta: " . mysqli_error($conn);
    }
}

// Pridobimo seznam letnikov za izbiro
$sql = "SELECT l.id AS letnik_id, l.ime AS letnik_ime, p.ime AS program_ime, s.ime AS sola_ime
        FROM letniki l
        JOIN programi p ON l.program_id = p.id
        JOIN sole s ON p.sola_id = s.id
        ORDER BY s.ime, p.ime, l.ime";
$result_letniki = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Predmet - Noodle</title>
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

            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST">
                <h2 class="text-center text-2xl mb-6">Dodaj Nov Predmet</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ime Predmeta</label>
                    <input type="text" name="ime_predmeta" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Izberi Letnik</label>
                    <select name="letnik_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Izberi Letnik</option>
                        <?php while($letnik = mysqli_fetch_assoc($result_letniki)): ?>
                            <option value="<?php echo $letnik['letnik_id']; ?>">
                                <?php echo $letnik['sola_ime'] . ' - ' . $letnik['program_ime'] . ' - ' . $letnik['letnik_ime']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ključ Predmeta</label>
                    <input type="text" name="kljuc" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <small class="text-gray-500">Dijaki bodo potrebovali ta ključ za vpis v predmet.</small>
                </div>
                <div class="flex items-center justify-between">
                    <button name="dodaj_predmet" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Dodaj Predmet
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
