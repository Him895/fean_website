<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $link = trim($_POST['link']);

    $stmt = $conn->prepare("UPDATE navigation SET title = ?, link = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $link, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
