<?php
session_start(); // Start the session to access session variables
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
            <span class="logo-name">Evidenca</span>
        </div>

        <div class="sidebar">
        <div class="logo ">
            <i class="bx bx-menu menu-icon"></i>
            <span class="logo-name">Evidenca</span>
        </div>
            <div class="sidebar-content">
                <ul class="lists">
                    <?php if (isset($_SESSION['vrsta_uporabnika'])): ?>
                        <?php if ($_SESSION['vrsta_uporabnika'] == 'administrator'): ?>
                            <li class="list">
                                <a href="admin_dashboard.php" class="nav-link">
                                    <i class="bx bxs-dashboard icon"></i>
                                    <span class="link">Admin Dashboard</span>
                                </a>
                            </li>
                            <li class="list">
                                <a href="manage_users.php" class="nav-link">
                                    <i class="bx bx-user icon"></i>
                                    <span class="link">Manage Users</span>
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
