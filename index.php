<?php
    include 'elements/header.php';
    include 'config/db.php';

    $GLOBALS['error'] = false;
    function prikaziClanke($category, $conn, $num) {
        $sql = "SELECT id, summary, title, content, image FROM articles WHERE"
                . ($category !== "arhiva" ? " category = ? and archive = 0" : " archive = 1")
                ." ORDER BY created DESC " . (!$num ? "" : "LIMIT ?");

        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            if ($category !== "arhiva") {
                if (!$num) {
                    mysqli_stmt_bind_param($stmt, 's',$category);
                } else {
                    mysqli_stmt_bind_param($stmt, 'si',$category, $num);
                }
            }
            mysqli_stmt_execute($stmt);

            if ($result = mysqli_stmt_get_result($stmt)) {
                if (!(!$result || mysqli_num_rows($result) === 0)) {
                    echo 
                        '<section class="category-block">
                            <div class="category-title">' . $category .'</div>
                            <div class="article-grid">';
                    while ($row = mysqli_fetch_array($result)) {
                        $id = $row['id'];
                        $link = "articles/clanak.php?id=$id";
                        echo
                            '<article class="article-card" onclick="window.location.href=\'' . $link. '\'">
                                <div class="image">
                                    <img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="image">
                                </div>
                                <h3>' . $row['title'] . '</h3>
                                <p>' . $row['summary'] . '</p>
                            </article>';
                    }
                    echo '</div></section>';
                } else if (mysqli_num_rows($result) === 0) {
                    $GLOBALS['error'] = true;
                }
            }
        }

        $stmt->close();
    }

    if (isset($_GET['category'])) {
        $category = strtolower($_GET['category']);
        if (in_array($category, ['politika', 'sport', 'arhiva'])) {
            prikaziClanke($category, $conn, null);
        } else {
            echo "<p style='text-align:center; margin-top:40px;'>Nepoznata kategorija.</p>";
        }
    } else {
        prikaziClanke('politika', $conn, 3);
        prikaziClanke('sport', $conn, 3);
    }
    if (!empty($GLOBALS['error'])) {
        echo "<p style='text-align:center; margin-top:40px;'>Nema podataka.</p>";
    }

    include 'elements/footer.php';
    $conn->close();
?>