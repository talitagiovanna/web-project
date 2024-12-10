<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $seguidor_id = $_SESSION['user_id']; // ID do usuário logado
    $seguido_id = $_POST['seguido_id'];

    if ($seguidor_id && $seguido_id) {
        $query = $conn->prepare("INSERT INTO seguindo (seguidor_id, seguido_id) VALUES (?, ?)");
        $query->bind_param("ii", $seguidor_id, $seguido_id);

        if ($query->execute()) {
            echo "Seguindo com sucesso!";
        } else {
            echo "Erro ao seguir o usuário.";
        }
    } else {
        echo "Dados inválidos.";
    }
}
?>
