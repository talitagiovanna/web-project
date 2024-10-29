<?php
session_start();

// Incluir arquivo de conexão com o banco de dados
require 'database.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$mensagem = "";

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['user_id'];
    $serie = $_POST['serie'];
    $data_assistida = $_POST['data_assistida']; // Nome atualizado
    $avaliacao = $_POST['avaliacao'];

    // Valida se os campos estão preenchidos
    if (empty($serie) || empty($data_assistida) || empty($avaliacao)) {
        $mensagem = "Todos os campos são obrigatórios.";
    } else {
        // Verifica se a série já está cadastrada para o usuário
        $query = "SELECT * FROM series_assistidas WHERE serie = ? AND usuario_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $serie, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $mensagem = "Você já cadastrou esta série.";
        } else {
            // Cadastra a nova série assistida
            $sql = "INSERT INTO series_assistidas (serie, data_assistida, avaliacao, usuario_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isii", $serie, $data_assistida, $avaliacao, $usuario_id);

            if ($stmt->execute()) {
                $mensagem = "Série cadastrada com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar série: " . $stmt->error;
            }
        }
    }

    // Exibe mensagem e redireciona
    echo "<script>
            alert('" . htmlspecialchars($mensagem, ENT_QUOTES) . "');
            window.location.href='series.html';
          </script>";
    exit();
}

// Fecha a conexão
$conn->close();
?>

