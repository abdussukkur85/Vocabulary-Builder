<?php
session_start();
include_once "dbconn.php";
$statusCode = 0;
$action = $_POST['action'] ?? '';

if (!$action) {
    header('location: index.php');
} else {
    if ('register' == $action) {
        $username = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if ($username && $password) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            try {
                $conn->prepare("INSERT INTO users (email, password) VALUES (?,?)")->execute([$username, $hash]);
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $statusCode = 1;
                } else {
                    $statusCode = 0;
                }
            }

        } else {
            $statusCode = 3;
        }
        header("location:index.php?status={$statusCode}");
    } else if ('login' == $action) {
        $username = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if ($username && $password) {
            $stmt = $conn->prepare("SELECT id,email,password FROM users WHERE email=? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['id'] = $user['id'];
                    header("location:words.php");
                    die();
                } else {
                    $statusCode = 4;
                }
            } else {
                $statusCode = 5;
            }

        } else {
            $statusCode = 3;
        }
        header("location:index.php?status={$statusCode}");
    } else if ("addword" == $action) {
        $word = $_POST['word'];
        $meaning = $_POST['meaning'];
        $user_id = $_SESSION['id'] ?? 0;
        if ($user_id && $word && $meaning) {
            $conn->prepare("INSERT INTO words (user_id, word, meaning) VALUES (?,?,?)")->execute([$user_id, $word, $meaning]);
            header("location:words.php");
        }

    }

}

?>