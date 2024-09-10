<?php
// index.php
session_start();

// Nastavitve povezave z bazo
$host = 'localhost';
$db = 'spletna_ucilnica';
$user = 'root';
$password = '';

// Povezava z bazo podatkov
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Napaka pri povezavi z bazo: " . $e->getMessage());
}

// Funkcija za preverjanje prijave
function preveriPrijavo($pdo) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: prijava.php');
        exit();
    }
    
    // Pridobimo podatke uporabnika
    $stmt = $pdo->prepare("SELECT * FROM Uporabniki WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$uporabnik = preveriPrijavo($pdo);

// Prikažemo ustrezni pogled glede na vlogo
switch ($uporabnik['vloga_id']) {
    case 1: // Dijak
        include 'pogledi/dijak.php';
        break;
    case 2: // Profesor
        include 'pogledi/profesor.php';
        break;
    case 3: // Skrbnik
        include 'pogledi/skrbnik.php';
        break;
    default:
        echo "Neznana vloga.";
        break;
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Spletna Učilnica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Dobrodošli v spletni učilnici</h1>
        <nav>
            <ul>
                <li><a href="index.php">Domov</a></li>
                <li><a href="odjava.php">Odjava</a></li>
            </ul>
        </nav>
    </header>

    <main>

    </main>

    <footer>
        <p>&copy; 2024 Spletna Učilnica. Vse pravice pridržane.</p>
    </footer>
</body>
</html>
