<?php
    include '../elements/header.php';
    include '../config/db.php';

    if (!isset($_SESSION['user'])) {
        header("Location: ../administration/login.php");
        exit;
    }

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $summary = $_POST['summary'];
        $content = $_POST['content'];
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageType = $_FILES['image']['type'];
        $category = $_POST['category'];
        $archive = isset($_POST['archive']) ? 1 : 0;
        if (strlen($summary) > 50) {
            $error = 'Kratki sadržaj ne smije biti više od 50 znakova';
        } else {
            $sql = "INSERT INTO articles (title, summary, content, image, image_type, category, archive) VALUES (?,?,?,?,?,?,?)";
    
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                $null = NULL;
                mysqli_stmt_bind_param($stmt, 'sssbssi', $title, $summary, $content, $null, $imageType, $category,  $archive);
                mysqli_stmt_send_long_data($stmt, 3, $imageData);
    
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ../index.php");
                    exit;
                } else {
                    $error = "Greška prilikom spremanja. Pokušajte ponovno kasnije...";
                }
            } else {
                $error = "Greška prilikom spremanja. Pokušajte ponovno kasnije.";
            }
    
            $stmt->close();
        }
    }

    $conn->close();
?>

<form method="post" enctype="multipart/form-data" class="article-form">
    <?php
        if (!empty($error)) {
            echo '<div class="error-message" style="color:red; margin-bottom:1em;">';
                echo $error;
            echo '</div>';
        }
    ?>
    <label for="title">Naslov članka</label>
    <input type="text" id="title" name="title" placeholder="Unesi naslov" required value=<?= isset($title) ? $title : "" ?>>

    <label for="summary">Kratki sadržaj</label>
    <textarea id="summary" name="summary" rows="5" placeholder="Unesi kratki sadržaj članka (maksimalno 50 znakova)" required><?= isset($summary) ? $summary : "" ?></textarea>
    <label for="content">Sadržaj</label>
    <textarea id="content" name="content" rows="10" placeholder="Unesi sadržaj članka" required><?= isset($content) ? $content : "" ?></textarea>

    <label for="category">Kategorija</label>
    <select id="category" name="category" required>
        <option value="" selected=<?= isset($category) ? $category === '' : false ?>>Odaberi kategoriju</option>
        <option value="politika" selected=<?= isset($category) ? $category === 'politika' : false ?>>Politika</option>
        <option value="sport" selected=<?= isset($category) ? $category === 'sport' : false ?>>Sport</option>
    </select>

    <label for="image">Slika</label>
    <input type="file" id="image" name="image" accept="image/*" required>
    <div class="form-checkbox">
        <input type="checkbox" name="archive" id="archive" checked=<?= isset($archive) ? $archive : 0 ?>>
        <label for="archive">Spremi u arhivu</label>
    </div>

    
    <button type="submit">Dodaj članak</button>
</form>


<?php include '../elements/footer.php'; ?>


