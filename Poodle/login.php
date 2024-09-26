<?php
// login.php
session_start();
include('povezava.php');

if(isset($_POST['prijava'])) {
    $uporabnisko_ime = mysqli_real_escape_string($conn, $_POST['uporabnisko_ime']);
    $geslo = $_POST['geslo'];

    $sql = "SELECT * FROM uporabniki WHERE uporabnisko_ime='$uporabnisko_ime'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if(password_verify($geslo, $user['geslo'])) {
            $_SESSION['uporabnik_id'] = $user['id'];
            $_SESSION['tip'] = $user['tip'];
            header('location: index.php');
            exit();
        } else {
            $napaka = "Napačno geslo.";
        }
    } else {
        $napaka = "Uporabniško ime ne obstaja.";
    }
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Prijava - Noodle</title>
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

                <!-- Prijavni obrazec -->
                <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-6" method="POST">
                    <h2 class="text-center text-2xl mb-6">Prijava</h2>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Uporabniško ime</label>
                        <input type="text" name="uporabnisko_ime" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Geslo</label>
                        <input type="password" name="geslo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button name="prijava" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Prijava
                        </button>
                        <a href="registracija.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                            Registracija
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
