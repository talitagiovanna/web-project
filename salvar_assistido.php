<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Conexão com o banco de dados
require 'database.php';

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['user_id'];
    $serie_id = $_POST['serie']; // 'serie' agora representa o id da série
    $data_assistida = $_POST['data'];
    $avaliacao = $_POST['avaliacao'];

    // Verificação de campos vazios
    if (empty($serie_id) || empty($data_assistida) || empty($avaliacao)) {
        $mensagem = "Todos os campos são obrigatórios.";
    } else {
        // Verificar se o usuário já cadastrou essa série
        $query = "SELECT * FROM series_assistidas WHERE serie = ? AND usuario_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $serie_id, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Se o usuário já cadastrou a série, exibe mensagem de erro
            $mensagem = "Você já cadastrou esta série.";
        } else {
            // Inserir nova série assistida
            $sql = "INSERT INTO series_assistidas (serie, data_assistida, avaliacao, usuario_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isii", $serie_id, $data_assistida, $avaliacao, $usuario_id);

            if ($stmt->execute()) {
                $mensagem = "Série cadastrada com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar série: " . $conn->error;
            }
        }
    }

    // Exibir mensagem em um pop-up e redirecionar para a página de séries
    echo "<script>
            alert('" . htmlspecialchars($mensagem, ENT_QUOTES) . "');
            window.location.href='series.html';
          </script>";
    exit();
}

$conn->close();
?>
