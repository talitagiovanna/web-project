<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    $sql = "UPDATE users SET username = ?, name = ?, email = ?, bio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $name, $email, $bio, $userId);

    if ($stmt->execute()) {
        header("Location: perfil.php?success=1");
    } else {
        echo "Erro ao atualizar o perfil: " . $stmt->error;
    }
} else {
    echo "Método inválido.";
}
?>
