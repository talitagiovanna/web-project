<?php
session_start(); // Inicia a sessão

// Conecta ao banco de dados
$conn = new mysqli('localhost', 'root', 'ROOT', 'mySeriesList');

// Verifica se há erros na conexão
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Erro na conexão com o banco."]));
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["success" => false, "message" => "Usuário não autenticado."]));
}

$user_id = $_SESSION['user_id']; // Obtém o ID do usuário logado

// Ação solicitada pelo cliente (add, get, delete-read, etc.)
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Se for a ação de adicionar notificação
if ($action === 'add') {
    // Verifica se a mensagem foi enviada
    $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; // Sanitiza a mensagem

    // Verifica se a mensagem não está vazia
    if (empty($message)) {
        die(json_encode(["success" => false, "message" => "Mensagem não pode ser vazia."]));
    }

    // Insere a notificação no banco
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);

    // Executa a consulta e verifica o resultado
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Notificação adicionada com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao adicionar notificação: " . $stmt->error]);
    }

    $stmt->close();
} 

// Se for a ação de carregar notificações
else if ($action === 'get') {
    // Recupera as notificações não lidas do banco (limita a 5 notificações para otimização)
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        // Decodifica a mensagem para remover entidades HTML como &quot;
        $row['message'] = html_entity_decode($row['message'], ENT_QUOTES, 'UTF-8');
        $notifications[] = $row;  // Adiciona cada notificação ao array
    }

    // Retorna as notificações em formato JSON
    echo json_encode($notifications);

    $stmt->close();
} 

// Se for a ação de marcar notificação como lida
else if ($action === 'mark-read') {
    $notification_id = isset($_POST['notification_id']) ? $_POST['notification_id'] : 0;

    if ($notification_id > 0) {
        // Marca a notificação como lida
        $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $notification_id, $user_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Notificação marcada como lida."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao marcar a notificação como lida."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "ID de notificação inválido."]);
    }
}

// Se for a ação de excluir notificações lidas
else if ($action === 'delete-read') {
    // Define o tempo máximo que as notificações lidas permanecem no banco (exemplo: 30 dias)
    $expiry_time = '30 DAY';  // Ajuste conforme necessário

    // Deleta as notificações lidas e com mais de 30 dias
    $stmt = $conn->prepare("DELETE FROM notifications WHERE is_read = 1 AND created_at < NOW() - INTERVAL $expiry_time");
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Notificações antigas excluídas com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao excluir notificações antigas."]);
    }

    $stmt->close();
}

$conn->close();
?>
