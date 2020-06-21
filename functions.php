<?php
include_once "dbconn.php";
function getStatusMessage($statusCode = 0) {
    $status = [
        0 => '',
        1 => 'Duplicate Email Address',
        3 => 'Username or Password Empty',
        4 => 'Passwod didn\'t match',
        5 => 'Username and password doesn\'t match',
    ];
    return $status[$statusCode];
}

function getAllWords($user_id, $searchText = NULL) {
    global $conn;
    if ($searchText) {
        $stmt = $conn->prepare("SELECT word,meaning FROM words WHERE user_id=? AND word LIKE '{$searchText}%' ORDER BY word");

    } else {
        $stmt = $conn->prepare("SELECT word,meaning FROM words WHERE user_id=? ORDER BY word");
    }
    $stmt->execute([$user_id]);
    $words = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $words;
}
?>