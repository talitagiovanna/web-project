<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados enviados pelo formulário
    $comentarioId = isset($_POST['id']) ? $_POST['id'] : '';
    $novoComentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';

    // Verifica se os dados estão completos
    if (!empty($comentarioId) && !empty($novoComentario)) {
        // Atualiza o comentário no banco de dados
        $query = "UPDATE comentarios SET comentario = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $novoComentario, $comentarioId);

        // Executa a consulta
        if ($stmt->execute()) {
            // Resposta de sucesso
            echo json_encode(["success" => true, "message" => "Comentário atualizado com sucesso!"]);
        } else {
            // Resposta de erro
            echo json_encode(["success" => false, "message" => "Erro ao atualizar o comentário!"]);
        }
    } else {
        // Resposta de erro caso os dados estejam incompletos
        echo json_encode(["success" => false, "message" => "Dados inválidos!"]);
    }
}

$conn->close();
?>
