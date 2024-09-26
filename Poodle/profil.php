<?php
// profil.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id'])) {
    header('location: prijava.php');
}

$uporabnik_id = $_SESSION['uporabnik_id'];

if(isset($_POST['posodobi'])) {
    $ime = $_POST['ime'];
    $priimek = $_POST['priimek'];
    $email = $_POST['email'];

    $sql = "UPDATE uporabniki SET ime='$ime', priimek='$priimek', email='$email' WHERE id='$uporabnik_id'";
    if(mysqli_query($conn, $sql)) {
        $sporocilo = "Profil uspešno posodobljen.";
    } else {
        $napaka = "Napaka pri posodobitvi profila.";
    }
}

$sql = "SELECT * FROM uporabniki WHERE id='$uporabnik_id'";
$result = mysqli_query($conn, $sql);
$uporabnik = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Moj Profil - Noodle</title>
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
                <h2 class="text-center text-2xl mb-6">Moj Profil</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ime</label>
                    <input type="text" name="ime" value="<?php echo $uporabnik['ime']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Priimek</label>
                    <input type="text" name="priimek" value="<?php echo $uporabnik['priimek']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo $uporabnik['email']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex items-center justify-between">
                    <button name="posodobi" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Posodobi
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
