<?php
// dodaj_solo.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || $_SESSION['tip'] != 'skrbnik') {
    header('location: prijava.php');
}

// Dodajanje šole
if(isset($_POST['dodaj_solo'])) {
    $ime_sole = $_POST['ime_sole'];

    $sql = "INSERT INTO sole (ime) VALUES ('$ime_sole')";
    if(mysqli_query($conn, $sql)) {
        $sporocilo = "Šola uspešno dodana.";
    } else {
        $napaka = "Napaka pri dodajanju šole.";
    }
}

// Dodajanje programa
if(isset($_POST['dodaj_program'])) {
    $ime_programa = $_POST['ime_programa'];
    $sola_id = $_POST['sola_id'];

    $sql = "INSERT INTO programi (ime, sola_id) VALUES ('$ime_programa', '$sola_id')";
    if(mysqli_query($conn, $sql)) {
        $sporocilo = "Program uspešno dodan.";
    } else {
        $napaka = "Napaka pri dodajanju programa.";
    }
}

// Dodajanje letnika
if(isset($_POST['dodaj_letnik'])) {
    $ime_letnika = $_POST['ime_letnika'];
    $program_id = $_POST['program_id'];

    $sql = "INSERT INTO letniki (ime, program_id) VALUES ('$ime_letnika', '$program_id')";
    if(mysqli_query($conn, $sql)) {
        $sporocilo = "Letnik uspešno dodan.";
    } else {
        $napaka = "Napaka pri dodajanju letnika.";
    }
}

// Pridobi vse šole za izbiro
$sql = "SELECT * FROM sole";
$sole_result = mysqli_query($conn, $sql);

// Pridobi vse programe za izbiro
$sql = "SELECT * FROM programi";
$programi_result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj Šolo/Razred - Noodle</title>
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

            <!-- Dodajanje Šole -->
            <h2 class="text-2xl mb-6">Dodaj Novo Šolo</h2>
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-10" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ime Šole</label>
                    <input type="text" name="ime_sole" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex items-center justify-between">
                    <button name="dodaj_solo" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Dodaj Šolo
                    </button>
                </div>
            </form>

            <!-- Dodajanje Programa -->
            <h2 class="text-2xl mb-6">Dodaj Nov Program</h2>
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-10" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ime Programa</label>
                    <input type="text" name="ime_programa" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Šola</label>
                    <select name="sola_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Izberi Šolo</option>
                        <?php while($sola = mysqli_fetch_assoc($sole_result)): ?>
                            <option value="<?php echo $sola['id']; ?>"><?php echo $sola['ime']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <button name="dodaj_program" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Dodaj Program
                    </button>
                </div>
            </form>

            <!-- Dodajanje Letnika -->
            <h2 class="text-2xl mb-6">Dodaj Nov Letnik</h2>
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ime Letnika</label>
                    <input type="text" name="ime_letnika" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Program</label>
                    <select name="program_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Izberi Program</option>
                        <?php while($program = mysqli_fetch_assoc($programi_result)): ?>
                            <option value="<?php echo $program['id']; ?>"><?php echo $program['ime']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <button name="dodaj_letnik" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Dodaj Letnik
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
