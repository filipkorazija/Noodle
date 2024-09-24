<?php
// login.php
require 'db.php';

session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uporabnisko_ime = $_POST['uporabnisko_ime'];
    $geslo = $_POST['geslo'];

    $stmt = $conn->prepare("SELECT * FROM uporabniki WHERE uporabnisko_ime = :uporabnisko_ime OR mail = :uporabnisko_ime");
    $stmt->bindParam(':uporabnisko_ime', $uporabnisko_ime);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($geslo, $user['geslo'])) {
        // Setting session variables based on user type
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['vrsta_uporabnika'] = $user['vrsta_profila']; // Change here

        if ($user['vrsta_profila'] == 'administrator') {
            $_SESSION['role'] = 'administrator';
        } elseif ($user['vrsta_profila'] == 'ucenec') {
            $_SESSION['role'] = 'ucenec';
        } elseif ($user['vrsta_profila'] == 'profesor') {
            $_SESSION['role'] = 'profesor';
        }

        header("Location: index.php"); // Redirect after login
        exit;
    } else {
        $error = 'Napačno uporabniško ime ali geslo.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
        <h2 class="mb-6 text-2xl font-bold text-center">Prijava</h2>
        
        <?php if ($error): ?>
        <div class="mb-4 text-red-500 text-center">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label class="block mb-2 text-sm font-bold text-gray-700">Uporabniško ime ali email</label>
                <input type="text" name="uporabnisko_ime" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div class="mb-4">
                <label class="block mb-2 text-sm font-bold text-gray-700">Geslo</label>
                <input type="password" name="geslo" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Prijava
                </button>
            </div>
        </form>
    </div>
</body>
</html>
