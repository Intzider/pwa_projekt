<?php
    session_start();
    $url = $_SERVER['REQUEST_URI'];
    $category = $_GET['category'] ?? '';
    $fullPath = realpath(__DIR__);
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
    $projectName = explode(DIRECTORY_SEPARATOR, str_replace($docRoot, '', $fullPath))[1];
    $path = basename(parse_url($url, PHP_URL_PATH));
    $adminPages = ['unos.php', 'login.php', 'registracija.php'];

    $isHome = !isset($_GET['category']) && $path === 'index.php';
    $isPolitika = $category === 'politika';
    $isSport = $category === 'sport';
    $isArhiva = $category === 'arhiva';
    $isAdmin = in_array($path, $adminPages);
?>

<!DOCTYPE html>
<html lang="hr">
    <head>
        <meta charset="UTF-8">
        <title>
            <?php
                if ($isPolitika || $isSport) {
                    echo 'Frankfurter Allgemeine - ' . ucfirst($category);
                } else if ($category == ""){
                    echo 'Frankfurter Allgemeine';
                }
            ?>
        </title>
        <link rel="icon" type="image/x-icon" href=<?= "/$projectName/images/favicon.png"?>>
        <link rel="stylesheet" type="text/css" href=<?= "/$projectName/elements/style.css"?>>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=UnifrakturCook:wght@700&display=swap" rel="stylesheet">
    </head>
<body>
    <header class="top-header">
        <a href=<?= "/$projectName/index.php"?> class="noeff">
            <div class="logo">Frankfurter Allgemeine</div>
        </a>
        <nav class="nav-menu">
            <a href=<?= "/$projectName/index.php"?> class="<?= $isHome ? 'active' : '' ?>">Home</a>
            <a href=<?= "\"/$projectName/index.php?category=politika\""?> class="<?= $isPolitika ? 'active' : '' ?>">Politika</a>
            <a href=<?= "\"/$projectName/index.php?category=sport\""?> class="<?= $isSport ? 'active' : '' ?>">Sport</a>
            <a href=<?= "\"/$projectName/index.php?category=arhiva\""?> class="<?= $isArhiva ? 'active' : '' ?>">Arhiva</a>
            <div class="dropdown">
                <a href="#" class="<?= $isAdmin ? 'active' : '' ?>">Administracija ▾</a>
                <div class="dropdown-content">
                    <?php
                        if (isset($_SESSION['user'])) {
                            echo '<a href="/' . $projectName . '/articles/unos.php">Dodaj članak</a>';
                            echo '<a href="/' . $projectName . '/administration/logout.php">Odjava</a>';
                        } else {
                            echo '<a href="/' . $projectName . '/administration/login.php">Prijava</a>';
                            echo '<a href="/' . $projectName . '/administration/registracija.php">Registracija</a>';
                        }
                    ?>
                </div>
            </div>
        </nav>
    </header>
    <main>
