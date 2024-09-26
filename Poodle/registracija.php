<?php
// registracija.php
session_start();
include('povezava.php');

if(isset($_POST['registracija'])) {
    $uporabnisko_ime = mysqli_real_escape_string($conn, $_POST['uporabnisko_ime']);
    $geslo = $_POST['geslo'];
    $potrditev_gesla = $_POST['potrditev_gesla'];
    $ime = mysqli_real_escape_string($conn, $_POST['ime']);
    $priimek = mysqli_real_escape_string($conn, $_POST['priimek']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $tip = 'dijak';

    if($geslo != $potrditev_gesla) {
        $napaka = "Gesli se ne ujemata.";
    } else {
        // Preveri, ali uporabniško ime že obstaja
        $sql = "SELECT * FROM uporabniki WHERE uporabnisko_ime='$uporabnisko_ime'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
            $napaka = "Uporabniško ime že obstaja.";
        } else {
            $geslo_hash = password_hash($geslo, PASSWORD_BCRYPT);

            $sql = "INSERT INTO uporabniki (uporabnisko_ime, geslo, ime, priimek, email, tip) VALUES ('$uporabnisko_ime', '$geslo_hash', '$ime', '$priimek', '$email', '$tip')";
            if(mysqli_query($conn, $sql)) {
                $sporocilo = "Registracija uspešna. Sedaj se lahko prijavite.";
                header('location: login.php');
                exit();
            } else {
                $napaka = "Napaka pri registraciji: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Registracija - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
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

                <!-- Registracijski obrazec -->
                <form class="bg-white shadow-md rounded px-8 pt-6 pb-8" method="POST">
                    <h2 class="text-center text-2xl mb-6">Registracija</h2>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Ime</label>
                        <input type="text" name="ime" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Priimek</label>
                        <input type="text" name="priimek" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Uporabniško ime</label>
                        <input type="text" name="uporabnisko_ime" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Geslo</label>
                        <input type="password" name="geslo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Potrditev gesla</label>
                        <input type="password" name="potrditev_gesla" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button name="registracija" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Registracija
                        </button>
                        <a href="login.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                            Prijava
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
