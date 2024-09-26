<?php
// uredi_uporabnika.php
require 'db.php';
session_start();

// Check if the user is an administrator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrator') {
    header("Location: login.php");
    exit;
}


// Add User Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $ime = $_POST['ime'];
    $priimek = $_POST['priimek'];
    $uporabnisko_ime = $_POST['uporabnisko_ime'];
    $mail = $_POST['mail'];
    $geslo = password_hash($_POST['geslo'], PASSWORD_DEFAULT);  // Hash the password
    $vrsta_profila = $_POST['vrsta_profila'];

    $stmt = $conn->prepare("INSERT INTO uporabniki (ime, priimek, uporabnisko_ime, mail, geslo, vrsta_profila) VALUES (:ime, :priimek, :uporabnisko_ime, :mail, :geslo, :vrsta_profila)");
    $stmt->execute([
        ':ime' => $ime,
        ':priimek' => $priimek,
        ':uporabnisko_ime' => $uporabnisko_ime,
        ':mail' => $mail,
        ':geslo' => $geslo,
        ':vrsta_profila' => $vrsta_profila
    ]);
    $message = "Uporabnik dodan uspešno!";
}

// Update User Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $uporabnisko_ime = $_POST['uporabnisko_ime'];
    $mail = $_POST['mail'];
    $vrsta_profila = $_POST['vrsta_profila'];

    // Update password if it's set, otherwise keep the old one
    if (!empty($_POST['geslo'])) {
        $geslo = password_hash($_POST['geslo'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE uporabniki SET uporabnisko_ime = :uporabnisko_ime, mail = :mail, geslo = :geslo, vrsta_profila = :vrsta_profila WHERE ID = :id");
        $stmt->execute([
            ':uporabnisko_ime' => $uporabnisko_ime,
            ':mail' => $mail,
            ':geslo' => $geslo,
            ':vrsta_profila' => $vrsta_profila,
            ':id' => $user_id
        ]);
    } else {
        $stmt = $conn->prepare("UPDATE uporabniki SET uporabnisko_ime = :uporabnisko_ime, mail = :mail, vrsta_profila = :vrsta_profila WHERE ID = :id");
        $stmt->execute([
            ':uporabnisko_ime' => $uporabnisko_ime,
            ':mail' => $mail,
            ':vrsta_profila' => $vrsta_profila,
            ':id' => $user_id
        ]);
    }
    $message = "Uporabnik uspešno posodobljen!";
}

// Delete User Logic
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM uporabniki WHERE ID = :id");
    $stmt->execute([':id' => $user_id]);
    $message = "Uporabnik izbrisan uspešno!";
}

// Fetch Users
$stmt = $conn->query("SELECT * FROM uporabniki");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uredi uporabnike</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<?php include 'nav.php'; ?>
    <div class="container mx-auto mt-10">
        <br> <br>
        <h1 class="text-2xl font-bold mb-6">Uredi uporabnike</h1>
        
        <?php if (isset($message)): ?>
            <div class="p-4 mb-4 text-green-700 bg-green-200 rounded-lg"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Add User Form -->
        <form method="POST" class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Dodaj uporabnika</h2>
            <div class="mb-4">
                <label class="block text-gray-700">Ime</label>
                <input type="text" name="ime" class="w-full border px-3 py-2 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Priimek</label>
                <input type="text" name="priimek" class="w-full border px-3 py-2 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Uporabniško ime</label>
                <input type="text" name="uporabnisko_ime" class="w-full border px-3 py-2 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="mail" class="w-full border px-3 py-2 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Geslo</label>
                <input type="password" name="geslo" class="w-full border px-3 py-2 rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Vrsta profila</label>
                <select name="vrsta_profila" class="w-full border px-3 py-2 rounded-lg">
                    <option value="administrator">Administrator</option>
                    <option value="ucenec">Učenec</option>
                    <option value="profesor">Profesor</option>
                </select>
            </div>
            <button type="submit" name="add_user" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Dodaj uporabnika</button>
        </form>

        <!-- User List with Edit Forms -->
        <h2 class="text-xl font-semibold mb-4">Obstoječi uporabniki</h2>
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4">Ime</th>
                    <th class="py-2 px-4">Priimek</th>
                    <th class="py-2 px-4">Uporabniško ime</th>
                    <th class="py-2 px-4">Email</th>
                    <th class="py-2 px-4">Vrsta profila</th>
                    <th class="py-2 px-4">Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <form method="POST">
                        <td class="py-2 px-4"><?php echo $user['ime']; ?></td>
                        <td class="py-2 px-4"><?php echo $user['priimek']; ?></td>
                        <td class="py-2 px-4">
                            <input type="text" name="uporabnisko_ime" value="<?php echo $user['uporabnisko_ime']; ?>" class="border px-2 py-1 w-full">
                        </td>
                        <td class="py-2 px-4">
                            <input type="email" name="mail" value="<?php echo $user['mail']; ?>" class="border px-2 py-1 w-full">
                        </td>
                        <td class="py-2 px-4">
                            <select name="vrsta_profila" class="border px-2 py-1 w-full">
                                <option value="administrator" <?php if ($user['vrsta_profila'] == 'administrator') echo 'selected'; ?>>Administrator</option>
                                <option value="ucenec" <?php if ($user['vrsta_profila'] == 'ucenec') echo 'selected'; ?>>Učenec</option>
                                <option value="profesor" <?php if ($user['vrsta_profila'] == 'profesor') echo 'selected'; ?>>Profesor</option>
                            </select>
                        </td>
                        <td class="py-2 px-4 flex items-center">
                            <input type="hidden" name="user_id" value="<?php echo $user['ID']; ?>">
                            <input type="password" name="geslo" placeholder="Novo geslo" class="border px-2 py-1 w-full mr-2">
                            <button type="submit" name="update_user" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Posodobi</button>
                            <a href="?delete=<?php echo $user['ID']; ?>" class="text-red-500 ml-2">Izbriši</a>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
