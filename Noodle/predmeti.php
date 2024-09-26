<?php
include 'db.php'; // Povezava z bazo
session_start();

// Preveri, če je uporabnik prijavljen
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$vrsta_uporabnika = $_SESSION['vrsta_uporabnika'];

// Preveri, če je obrazec poslan za ustvarjanje novega predmeta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ustvari_predmet'])) {
    $ime_predmeta = $_POST['ime_predmeta'];
    $kljuc = $_POST['kljuc'];

    // Preveri, če so polja izpolnjena
    if (!empty($ime_predmeta)) {
        try {
            $stmt = $conn->prepare("INSERT INTO predmeti (Ime_predmeta, Kljuc, id_profesorja) VALUES (:ime_predmeta, :kljuc, :id_profesorja)");
            $stmt->bindParam(':ime_predmeta', $ime_predmeta);
            $stmt->bindParam(':kljuc', $kljuc);
            $stmt->bindParam(':id_profesorja', $user_id);
            $stmt->execute();

            header("Location: predmeti.php");
            exit();
        } catch (PDOException $e) {
            echo "Napaka pri dodajanju predmeta: " . $e->getMessage();
        }
    } 
}

// Preveri, če je obrazec poslan za urejanje predmeta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uredi_predmet'])) {
    $ime_predmeta = $_POST['ime_predmeta'];
    $kljuc = $_POST['kljuc'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];

        // Pridobi id_profesorja za predmet, ki se ureja
        $stmt = $conn->prepare("SELECT id_profesorja FROM predmeti WHERE ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $predmet = $stmt->fetch(PDO::FETCH_ASSOC);

        // Preveri, če je trenutni uporabnik admin ali lastnik predmeta
        if ($vrsta_uporabnika == 'admin' || ($vrsta_uporabnika == 'profesor' && $predmet['id_profesorja'] == $user_id)) {
            $stmt = $conn->prepare("UPDATE predmeti SET Ime_predmeta = :ime_predmeta, Kljuc = :kljuc WHERE ID = :id");
            $stmt->bindParam(':ime_predmeta', $ime_predmeta);
            $stmt->bindParam(':kljuc', $kljuc);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
    }

    header("Location: predmeti.php");
    exit();
}

// Preveri, če je obrazec poslan za brisanje predmeta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['izbrisi_predmet'])) {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];

        // Preveri, ali lahko predmet izbrišemo
        $stmt = $conn->prepare("SELECT id_profesorja FROM predmeti WHERE ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $predmet = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($vrsta_uporabnika == 'admin' || ($vrsta_uporabnika == 'profesor' && $predmet['id_profesorja'] == $user_id)) {
            $stmt = $conn->prepare("DELETE FROM predmeti WHERE ID = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
    }

    header("Location: predmeti.php");
    exit();
}

// Pridobi predmete glede na tip uporabnika
if ($vrsta_uporabnika == 'admin') {
    $sql = "SELECT predmeti.*, uporabniki.ime AS ime_profesorja, uporabniki.priimek AS priimek_profesorja FROM predmeti 
            JOIN uporabniki ON predmeti.id_profesorja = uporabniki.ID";
} else {
    $sql = "SELECT predmeti.*, uporabniki.ime AS ime_profesorja, uporabniki.priimek AS priimek_profesorja FROM predmeti 
            JOIN uporabniki ON predmeti.id_profesorja = uporabniki.ID WHERE predmeti.id_profesorja = :user_id";
}

$stmt = $conn->prepare($sql);

if ($vrsta_uporabnika != 'admin') {
    $stmt->bindParam(':user_id', $user_id);
}

$stmt->execute();
$predmeti = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urejanje predmetov</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'nav.php'; ?>
    <br><br><br>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Urejanje predmetov</h1>
        
        <!-- Seznam predmetov -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full">
                <thead class="bg-gray-200 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="py-3 px-4 text-left">Ime predmeta</th>
                        <th class="py-3 px-4 text-left">Profesor</th>
                        <th class="py-3 px-4 text-left">Ključ za dostop</th>
                        <th class="py-3 px-4 text-left">Akcije</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach ($predmeti as $predmet): ?>
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-2 px-4">
                                <span id="ime_predmeta_<?php echo $predmet['ID']; ?>"><?php echo htmlspecialchars($predmet['Ime_predmeta']); ?></span>
                                <input type="text" id="edit_ime_<?php echo $predmet['ID']; ?>" class="hidden mt-1 block w-full p-2 border border-gray-300 rounded" value="<?php echo htmlspecialchars($predmet['Ime_predmeta']); ?>">
                            </td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($predmet['ime_profesorja'] . ' ' . $predmet['priimek_profesorja']); ?></td>
                            <td class="py-2 px-4">
                                <span id="kljuc_<?php echo $predmet['ID']; ?>"><?php echo htmlspecialchars($predmet['Kljuc']); ?></span>
                                <input type="text" id="edit_kljuc_<?php echo $predmet['ID']; ?>" class="hidden mt-1 block w-full p-2 border border-gray-300 rounded" value="<?php echo htmlspecialchars($predmet['Kljuc']); ?>">
                            </td>
                            <td class="py-2 px-4">
                                <?php if ($vrsta_uporabnika == 'admin' || ($vrsta_uporabnika == 'profesor' && $predmet['id_profesorja'] == $user_id)): ?>
                                    <button onclick="editPredmet(<?php echo $predmet['ID']; ?>)" id="edit_button_<?php echo $predmet['ID']; ?>" class="px-4 py-2 bg-yellow-500 text-white rounded">Uredi</button>
                                    <button onclick="savePredmet(<?php echo $predmet['ID']; ?>)" id="save_button_<?php echo $predmet['ID']; ?>" class="hidden px-4 py-2 bg-blue-500 text-white rounded">Shrani</button>
                                    <form method="POST" action="predmeti.php" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $predmet['ID']; ?>">
                                        <button type="submit" name="izbrisi_predmet" class="px-4 py-2 bg-red-500 text-white rounded">Izbriši</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Obrazec za dodajanje novega predmeta -->
        <h2 class="text-xl font-semibold mb-4">Dodaj nov predmet</h2>
        <form method="POST" action="predmeti.php" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="ime_predmeta">Ime predmeta</label>
                <input type="text" name="ime_predmeta" id="ime_predmeta" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="kljuc">Ključ za dostop</label>
                <input type="text" name="kljuc" id="kljuc" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" name="ustvari_predmet" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Ustvari predmet</button>
        </form>
    </div>

    <script>
        function editPredmet(id) {
            document.getElementById('ime_predmeta_' + id).classList.add('hidden');
            document.getElementById('edit_ime_' + id).classList.remove('hidden');

            document.getElementById('kljuc_' + id).classList.add('hidden');
            document.getElementById('edit_kljuc_' + id).classList.remove('hidden');

            document.getElementById('edit_button_' + id).classList.add('hidden');
            document.getElementById('save_button_' + id).classList.remove('hidden');
        }

        function savePredmet(id) {
            var imePredmeta = document.getElementById('edit_ime_' + id).value;
            var kljuc = document.getElementById('edit_kljuc_' + id).value;
            var formData = new FormData();
            formData.append('id', id);
            formData.append('ime_predmeta', imePredmeta);
            formData.append('kljuc', kljuc);
            formData.append('uredi_predmet', true);

            fetch('predmeti.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                window.location.reload(); // Osveži stran, da se prikažejo posodobljeni podatki
            })
            .catch(error => console.error('Napaka:', error));
        }
    </script>
</body>
</html>
