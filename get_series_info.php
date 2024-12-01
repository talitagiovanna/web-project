<?php
session_start();
require 'database.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user_id']; // Obtém o ID do usuário da sessão

// Verifica se é uma requisição GET para buscar as informações da série
if (isset($_GET['id'])) {
    $serieId = $_GET['id'];
    
    // Buscar dados da série assistida
    $sql = "SELECT sa.serie_id, sa.avaliacao, sa.data_assistida
            FROM series_assistidas sa
            WHERE sa.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $serieId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $serie = $result->fetch_assoc();
        echo json_encode($serie);
    } else {
        echo json_encode(['error' => 'Série não encontrada']);
    }
    
    $stmt->close();
    $conn->close();
}

// Verifica se é uma requisição POST para atualizar os dados da série
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['serie_id'])) {
        $serieId = $_POST['serie_id'];
        $avaliacao = $_POST['avaliacao'];
        $dataAssistida = $_POST['data_assistida'];

        // Atualiza os dados no banco de dados
        $sql = "UPDATE series_assistidas SET avaliacao = ?, data_assistida = ? WHERE serie_id = ? AND usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isii", $avaliacao, $dataAssistida, $serieId, $userId);

        if ($stmt->execute()) {
            echo "Dados atualizados com sucesso! Atualize a Página para ver as Modificações.";
        } else {
            echo "Erro ao atualizar dados: " . $stmt->error;
        }
        
        $stmt->close();
        $conn->close();
    }
    
}
?>
