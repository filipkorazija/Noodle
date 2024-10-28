<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Noodle - Navigacija</title>
    <!-- Include Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <nav class="bg-gray-800 p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <!-- Logo or website title -->
            <a href="index.php" class="text-white text-lg font-semibold">Noodle</a>
            <div class="flex items-center space-x-4">
                <?php if(isset($_SESSION['uporabnik_id'])): ?>
                    <?php if(isset($_SESSION['tip']) && $_SESSION['tip'] == 'dijak'): ?>
                        <!-- Student's navigation -->
                        <div class="relative" onmouseover="showMenu('predmetiMenu')" onmouseout="hideMenu('predmetiMenu')">
                            <button class="text-gray-300 hover:text-white focus:outline-none">
                                Predmeti
                            </button>
                            <!-- Dropdown menu -->
                            <div id="predmetiMenu" class="absolute left-0 mt-2 w-48 bg-white text-gray-800 py-2 rounded shadow-lg opacity-0 invisible transition-opacity duration-200">
                                <a href="moji_predmeti.php" class="block px-4 py-2 hover:bg-gray-100">Moji Predmeti</a>
                                <a href="kategorije_sol.php" class="block px-4 py-2 hover:bg-gray-100">Kategorije Šol</a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['tip']) && ($_SESSION['tip'] == 'profesor' || $_SESSION['tip'] == 'skrbnik')): ?>
                        <!-- Teacher's and admin's navigation -->
                        <div class="relative" onmouseover="showMenu('nalogeMenu')" onmouseout="hideMenu('nalogeMenu')">
                            <button class="text-gray-300 hover:text-white focus:outline-none">
                                Naloge
                            </button>
                            <!-- Dropdown menu -->
                            <div id="nalogeMenu" class="absolute left-0 mt-2 w-48 bg-white text-gray-800 py-2 rounded shadow-lg opacity-0 invisible transition-opacity duration-200">
                                <a href="dodaj_nalogo.php" class="block px-4 py-2 hover:bg-gray-100">Dodaj Nalogo</a>
                                <a href="uredi_naloge.php" class="block px-4 py-2 hover:bg-gray-100">Uredi Naloge</a>
                            </div>
                        </div>

                        <div class="relative" onmouseover="showMenu('predmetiMenuProf')" onmouseout="hideMenu('predmetiMenuProf')">
                            <button class="text-gray-300 hover:text-white focus:outline-none">
                                Predmeti
                            </button>
                            <!-- Dropdown menu -->
                            <div id="predmetiMenuProf" class="absolute left-0 mt-2 w-48 bg-white text-gray-800 py-2 rounded shadow-lg opacity-0 invisible transition-opacity duration-200">
                                <a href="dodaj_predmet.php" class="block px-4 py-2 hover:bg-gray-100">Dodaj Predmet</a>
                                <a href="uredi_predmete.php" class="block px-4 py-2 hover:bg-gray-100">Uredi Predmete</a>
                            </div>
                        </div>

                        <div class="relative" onmouseover="showMenu('noviceMenu')" onmouseout="hideMenu('noviceMenu')">
                            <button class="text-gray-300 hover:text-white focus:outline-none">
                                Novice
                            </button>
                            <!-- Dropdown menu -->
                            <div id="noviceMenu" class="absolute left-0 mt-2 w-48 bg-white text-gray-800 py-2 rounded shadow-lg opacity-0 invisible transition-opacity duration-200">
                                <a href="urejanje_novic.php" class="block px-4 py-2 hover:bg-gray-100">Uredi Novice</a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['tip']) && $_SESSION['tip'] == 'skrbnik'): ?>
                        <!-- Admin's additional navigation -->
                        <div class="relative" onmouseover="showMenu('adminMenu')" onmouseout="hideMenu('adminMenu')">
                            <button class="text-gray-300 hover:text-white focus:outline-none">
                                Admin
                            </button>
                            <!-- Dropdown menu -->
                            <div id="adminMenu" class="absolute right-0 mt-2 w-56 bg-white text-gray-800 py-2 rounded shadow-lg opacity-0 invisible transition-opacity duration-200">
                                <a href="admin_panel.php" class="block px-4 py-2 hover:bg-gray-100">Admin Panel</a>
                                <a href="dodaj_solo.php" class="block px-4 py-2 hover:bg-gray-100">Dodaj Šolo/Razred</a>
                                <a href="dodaj_uporabnika.php" class="block px-4 py-2 hover:bg-gray-100">Dodaj Uporabnika</a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Common navigation for all logged-in users -->
                    <div class="relative" onmouseover="showMenu('profilMenu')" onmouseout="hideMenu('profilMenu')">
                        <button class="text-gray-300 hover:text-white focus:outline-none">
                            Profil
                        </button>
                        <!-- Dropdown menu -->
                        <div id="profilMenu" class="absolute right-0 mt-2 w-40 bg-white text-gray-800 py-2 rounded shadow-lg opacity-0 invisible transition-opacity duration-200">
                            <a href="profil.php" class="block px-4 py-2 hover:bg-gray-100">Moj Profil</a>
                            <a href="odjava.php" class="block px-4 py-2 hover:bg-gray-100">Odjava</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Navigation for non-logged-in users -->
                    <div class="flex items-center space-x-4">
                        <a href="prijava.php" class="text-gray-300 hover:text-white">Prijava</a>
                        <a href="registracija.php" class="text-gray-300 hover:text-white">Registracija</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- JavaScript for dropdown menus with delay -->
    <script>
        var menuTimeouts = {};

        function showMenu(menuId) {
            clearTimeout(menuTimeouts[menuId]);
            var menu = document.getElementById(menuId);
            menu.classList.remove('opacity-0', 'invisible');
            menu.classList.add('opacity-100', 'visible');
        }

        function hideMenu(menuId) {
            menuTimeouts[menuId] = setTimeout(function() {
                var menu = document.getElementById(menuId);
                menu.classList.remove('opacity-100', 'visible');
                menu.classList.add('opacity-0', 'invisible');
            }, 200); // Adjust the delay (in milliseconds) as needed
        }
    </script>
</body>
</html>
