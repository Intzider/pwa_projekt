<?php
    session_start();
    include '../config/db.php';

    if (!isset($_SESSION['user'])) {
        header("Location: ../administration/login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];

        $sql = "DELETE FROM articles WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $stmt->close();
        }

        $conn->close();
        header("Location: ../index.php");
        exit;
    } else {
        echo "Neispravan zahtjev.";
    }
?>