<?php
    include '../elements/header.php';
    include '../config/db.php';
    $found = true;

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        $found = false;
    } else {
        $id = (int)$_GET['id'];
    
        $sql = "SELECT * FROM articles WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 'i',$id);
            mysqli_stmt_execute($stmt);
    
            if ($result = mysqli_stmt_get_result($stmt)) {
                if (!$result || mysqli_num_rows($result) == 0) {
                    $found = false;
                } else {
                    $row = mysqli_fetch_assoc($result);
                }
            } else {
                $found = false;
            }
        } else {
            $found = false;
        }
        $stmt->close();
    }

    $conn->close();
?>

<main class="article">
    <?php
        if ($found) {
            echo "<h1>" . $row['title'] . "</h1>";
            echo '<div class="article-horiz-align">';
            echo "<p class='article-date'>(" . strtoupper($row['category']) . ") Objavljeno: " . date('d. m. Y. H:i', strtotime($row['created'])) . "</p>";
            if (isset($_SESSION['user'])) {
                echo
                    '<form action="obrisi_clanak.php" method="POST" onsubmit="return confirm(\'Jeste li sigurni da želite obrisati ovaj članak?\');">
                        <input type="hidden" name="id" value="' . $row['id'] . '">
                        <button type="submit" class="delete-button">Obriši članak</button>
                     </form>';
            }
            echo '</div>';
            if (!empty($row['image'])) {
                echo "<img src='data:" . $row['image_type'] . ";base64," . base64_encode($row['image']) . "' alt='Slika članka'>";    
            }

            echo '<div class="article-content">' . $row['content'] . '</div>';
        } else {
            echo "<p style='text-align:center; margin-top:40px;'>Članak ne postoji.</p>";
        }
    ?>
</main>

<?php include '../elements/footer.php'; ?>
