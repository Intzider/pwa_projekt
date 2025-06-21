<?php
    include '../elements/header.php';
    require '../config/db.php';

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uname = mysqli_real_escape_string($conn, $_POST['username']);
        $passwd = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = ?";

        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's',$uname);
            mysqli_stmt_execute($stmt);

            if ($result = mysqli_stmt_get_result($stmt)) {
                if (mysqli_num_rows($result) !== 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if (password_verify($passwd, $row['password'])) {
                            $_SESSION['user'] = $row['username'];
                            header("Location: /$projectName/index.php");
                            exit;
                        } else {
                            $error = "Netočno korisničko ime ili lozinka.";
                        }
                    }
                } else {
                    $error = "Netočno korisničko ime ili lozinka.";
                }
            } else {
                $error = "Netočno korisničko ime ili lozinka.";
            }
        } else {
            $error = "Greška prilikom provjere. Pokušajte ponovno kasnije.";
        }

        $stmt->close();
    }

    $conn->close();
?>

<form method="post" action="login.php" class="article-form">
    <?php
        if (!empty($error)) {
            echo '<div class="error-message" style="color:red; margin-bottom:1em;">';
                echo $error;
            echo '</div>';
        }
    ?>
    <label for="username">Korisničko ime</label>
    <input type="text" id="username" name="username" autocomplete="username" required autofocus>

    <label for="password">Lozinka</label>
    <input type="password" id="password" name="password" autocomplete="current-password" required>

    <button type="submit">Prijavi se</button>
</form>

<?php include '../elements/footer.php'; ?>

