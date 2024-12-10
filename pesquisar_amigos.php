<?php
include('database.php');
session_start(); // Inicia a sessão para acessar $_SESSION

if (!isset($_SESSION['user_id'])) {
    die('Erro: Usuário não autenticado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['seguido_id'])) {
        $usuarioAtualId = $_SESSION['user_id'];
        $seguidoId = intval($_POST['seguido_id']);
        
        if ($_POST['action'] === 'seguir') {
            // Adicionar relacionamento de seguimento
            $sql = "INSERT INTO seguindo (seguidor_id, seguido_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $usuarioAtualId, $seguidoId);
            $stmt->execute();
            echo 'Seguindo';
        } elseif ($_POST['action'] === 'deixar_de_seguir') {
            // Remover relacionamento de seguimento
            $sql = "DELETE FROM seguindo WHERE seguidor_id = ? AND seguido_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $usuarioAtualId, $seguidoId);
            $stmt->execute();
            echo 'Seguir';
        }

        $stmt->close();
        exit;
    }

    $nomeUsuario = $_POST['nomeUsuario'];
    $usuarioAtualId = $_SESSION['user_id']; // ID do usuário atual

    // Ajuste a consulta para usar as colunas corretas
    $sql = "SELECT id, username, 
                   EXISTS(SELECT 1 FROM seguindo WHERE seguidor_id = ? AND seguido_id = users.id) AS seguindo
            FROM users 
            WHERE username LIKE ? OR name LIKE ?";

    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $nomeUsuario . '%';
    $stmt->bind_param('iss', $usuarioAtualId, $searchTerm, $searchTerm);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="usuario-item">';
            echo '<span>' . htmlspecialchars($row['username']) . '</span>';

            if ($row['seguindo']) {
                echo '<button class="seguir-btn seguindo" data-id="' . $row['id'] . '" data-action="deixar_de_seguir">Seguindo</button>';
            } else {
                echo '<button class="seguir-btn" data-id="' . $row['id'] . '" data-action="seguir">Seguir</button>';
            }

            echo '</div>';
        }
    } else {
        echo '<p>Nenhum usuário encontrado.</p>';
    }

    $stmt->close();
}

$conn->close();
?>