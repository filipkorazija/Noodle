<?php
// moji_predmeti.php
session_start();
include('povezava.php');

if(!isset($_SESSION['uporabnik_id']) || $_SESSION['tip'] != 'dijak') {
    header('location: prijava.php');
    exit();
}

$dijak_id = $_SESSION['uporabnik_id'];

// Pridobimo predmete, v katere je dijak vpisan
$sql = "SELECT p.id AS predmet_id, p.ime AS predmet_ime, l.ime AS letnik_ime, pr.ime AS program_ime, s.ime AS sola_ime
        FROM prijave_predmetov pp
        JOIN predmeti p ON pp.predmet_id = p.id
        JOIN letniki l ON p.letnik_id = l.id
        JOIN programi pr ON l.program_id = pr.id
        JOIN sole s ON pr.sola_id = s.id
        WHERE pp.dijak_id = '$dijak_id'
        ORDER BY s.ime, pr.ime, l.ime, p.ime";
$result_predmeti = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Moji Predmeti - Noodle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <?php include('navbar.php'); ?>

    <!-- Vsebina -->
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Moji Predmeti</h1>
        <?php if(mysqli_num_rows($result_predmeti) > 0): ?>
            <ul>
                <?php while($predmet = mysqli_fetch_assoc($result_predmeti)): ?>
                    <li class="mb-4">
                        <div class="border rounded p-4">
                            <h2 class="text-xl font-semibold"><?php echo $predmet['predmet_ime']; ?></h2>
                            <p class="text-gray-600"><?php echo $predmet['sola_ime']; ?> > <?php echo $predmet['program_ime']; ?> > <?php echo $predmet['letnik_ime']; ?></p>
                            <a href="naloge.php?predmet_id=<?php echo $predmet['predmet_id']; ?>" class="text-blue-500 hover:underline mt-2 inline-block">Pojdi na predmet</a>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Nimate vpisanih predmetov. Prosimo, vpišite se v predmete preko <a href="kategorije_sol.php" class="text-blue-500 hover:underline">Kategorije Šol</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>
