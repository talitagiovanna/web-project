<?php
session_start();

// Incluir arquivo de conexão com o banco de dados
require 'database.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para excluir o comentário.");
}

$usuarioId = $_SESSION['user_id']; // ID do usuário logado

$comentarioId = isset($_GET['id']) ? $_GET['id'] : '';
$serieId = isset($_GET['serie_id']) ? $_GET['serie_id'] : ''; // Obter ID da série

// Verificar se o comentário existe e pertence ao usuário logado
if (!empty($comentarioId) && !empty($serieId)) {
    $query = "SELECT usuario_id FROM comentarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $comentarioId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $comentario = $result->fetch_assoc();

        // Verificar se o comentário pertence ao usuário logado
        if ($comentario['usuario_id'] == $usuarioId) {
            // Excluir o comentário
            $query_delete = "DELETE FROM comentarios WHERE id = ?";
            $stmt_delete = $conn->prepare($query_delete);
            $stmt_delete->bind_param("i", $comentarioId);
            $stmt_delete->execute();

            // Redirecionar de volta para a página de perfil da série com uma mensagem de sucesso
            header("Location: perfil_series.php?id=" . $serieId . "&message=ComentarioExcluido");
            exit();
        } else {
            echo "Você não tem permissão para excluir este comentário.";
        }
    } else {
        echo "Comentário não encontrado!";
    }
} else {
    echo "ID do comentário ou série não especificado!";
}

$conn->close();
?>
