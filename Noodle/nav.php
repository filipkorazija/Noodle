<?php
session_start();
include 'db.php'; // Vključite povezavo z bazo podatkov

// Preverite, ali je uporabnik prijavljen
if (isset($_SESSION['uporabnisko_ime'])) {
    $uporabnisko_ime = $_SESSION['uporabnisko_ime'];

    // Poizvedba za pridobitev slike uporabnika
    $query = "SELECT user_image FROM uporabniki WHERE uporabnisko_ime = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $uporabnisko_ime);
    $stmt->execute();
    $result = $stmt->get_result();

    // Preverite, ali je bila najdena slika za uporabnika
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_image = $row['user_image'];
    } else {
        // Privzeta slika, če uporabnik nima naložene slike
        $user_image = 'default-profile.png';
    }
} else {
    // Če uporabnik ni prijavljen, nastavi privzeto sliko
    $user_image = 'default-prafil.png';
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/>
</head>
<body>
    <nav>
        <div class="logo">
            <i class="bx bx-menu menu-icon"></i>
            <span class="logo-name">Noodle</span>
        </div>

        <!-- Profilna slika v desnem zgornjem kotu -->
        <div class="user-profile" style="position: absolute; top: 10px; right: 10px;">
            <img src="<?php echo $user_image; ?>" alt="Profilna slika" class="profile-picture" style="width: 50px; height: 50px; border-radius: 50%;">
        </div>

        <div class="sidebar">
            <div class="logo">
                <i class="bx bx-menu menu-icon"></i>
                <span class="logo-name">Noodle</span>
            </div>
            <div class="sidebar-content">
                <ul class="lists">
                    <?php if (isset($_SESSION['vrsta_uporabnika'])): ?>
                        <?php if ($_SESSION['vrsta_uporabnika'] == 'administrator'): ?>
                            <li class="list">
                                <a href="index.php" class="nav-link">
                                    <i class="bx bxs-home icon"></i>
                                    <span class="link">Domov</span>
                                </a>
                            </li>
                            <li class="list">
                                <a href="uredi_uporabnika.php" class="nav-link">
                                    <i class="bx bx-user icon"></i>
                                    <span class="link">Uredi uporabnike</span>
                                </a>
                            </li>
                        <?php elseif ($_SESSION['vrsta_uporabnika'] == 'profesor'): ?>
                            <li class="list">
                                <a href="professor_dashboard.php" class="nav-link">
                                    <i class="bx bx-book icon"></i>
                                    <span class="link">Professor Dashboard</span>
                                </a>
                            </li>
                        <?php elseif ($_SESSION['vrsta_uporabnika'] == 'ucenec'): ?>
                            <li class="list">
                                <a href="student_dashboard.php" class="nav-link">
                                    <i class="bx bx-book-reader icon"></i>
                                    <span class="link">Student Dashboard</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="list">
                            <a href="login.php" class="nav-link">
                                <i class="bx bx-log-in icon"></i>
                                <span class="link">Prijava</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="bottom-content">
                    <li class="list">
                        <a href="logout.php" class="nav-link" style="color: white;">
                            <i class="bx bx-log-out icon"></i>
                            <span class="link">Odjava</span>
                        </a>
                    </li>
                </div>
            </div>
        </div>
    </nav>

    <section class="overlay"></section>

    <script>
        const navBar = document.querySelector("nav"),
            menuBtns = document.querySelectorAll(".menu-icon"),
            overlay = document.querySelector(".overlay");

        menuBtns.forEach((menuBtn) => {
            menuBtn.addEventListener("click", () => {
                navBar.classList.toggle("open");
            });
        });

        overlay.addEventListener("click", () => {
            navBar.classList.remove("open");
        });
    </script>
</body>
</html>
