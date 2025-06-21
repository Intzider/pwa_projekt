<?php
    include '../elements/header.php';
    require '../config/db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uname = trim($_POST['username']);
        $email = trim($_POST['email']);
        if (strlen($_POST['password']) < 8) {
            $error = "Šifra mora imati najmanje 8 znakova!";
        } else if ($_POST['password'] !== $_POST['confirm_password']) {
            $error = "Lozinke moraju biti jednake!";
        } else {
            $passwd = password_hash($_POST['password'], CRYPT_BLOWFISH);
            
            $sql = "SELECT * FROM users WHERE username = ?";
    
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 's',$uname);
                mysqli_stmt_execute($stmt);
    
                if ($result = mysqli_stmt_get_result($stmt)) {
                    $rows = mysqli_fetch_assoc($result);
                    if ($rows !== null && sizeof($rows) != 0) {
                        $error = 'Korisničko ime se već koristi!';
                    } else {
                        $sql = "INSERT INTO users (username, email, password) VALUES (?,?,?)";
                        
                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $sql)) {
                            mysqli_stmt_bind_param($stmt, 'sss', $uname, $email, $passwd);
    
                            if (mysqli_stmt_execute($stmt)) {
                                header("Location: login.php");
                                exit;
                            } else {
                                $error = "Greška prilikom spremanja. Pokušajte ponovno kasnije...";
                            }
                        } else {
                            $error = "Greška prilikom spremanja. Pokušajte ponovno kasnije.";
                        }
                    }
                }
            } else {
                $error = "Greška prilikom spremanja. Pokušajte ponovno kasnije..";
            }
        }
    }

    $conn->close();
?>

<form method="post" action="registracija.php" class="article-form">
    <?php
        if (!empty($error)) {
            echo '<div class="error-message" style="color:red; margin-bottom:1em;">';
                echo $error;
            echo '</div>';
        }
    ?>
    <label for="username">Korisničko ime</label>
    <input type="text" id="username" name="username" required autofocus value=<?= isset($uname) ? $uname : "" ?>>

    <label for="email">Email adresa</label>
    <input type="email" id="email" name="email" required value=<?= isset($email) ? $email : "" ?>>

    <label for="password">Lozinka</label>
    <input type="password" id="password" name="password" autocomplete="new-password" placeholder="********" required>

    <label for="confirm_password">Ponovi lozinku</label>
    <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" placeholder="********" required>

    <button type="submit">Registriraj se</button>
</form>

<?php include '../elements/footer.php'; ?>

