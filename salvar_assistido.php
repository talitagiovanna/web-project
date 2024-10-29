<?php
session_start();

// Iniciar a conexão com o banco de dados
$servername = "localhost";
$username = "root"; 
$password = "ROOT";
$dbname = "myserieslist";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$mensagem = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['user_id'];
    $serie = $_POST['serie'];
    $data_assistida = $_POST['data_assistida']; // Corrigido para data_assistida
    $avaliacao = $_POST['avaliacao'];

    if (empty($serie) || empty($data_assistida) || empty($avaliacao)) {
        $mensagem = "Todos os campos são obrigatórios.";
    } else {
        $query = "SELECT * FROM series_assistidas WHERE serie = ? AND usuario_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $serie, $usuario_id); // Corrigido para int
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $mensagem = "Você já cadastrou esta série.";
        } else {
            $sql = "INSERT INTO series_assistidas (serie, data_assistida, avaliacao, usuario_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isii", $serie, $data_assistida, $avaliacao, $usuario_id);

            if ($stmt->execute()) {
                $mensagem = "Série cadastrada com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar série: " . $conn->error;
            }
        }
    }

    // Redirecionar com a mensagem
    echo "<script>
            alert('" . htmlspecialchars($mensagem, ENT_QUOTES) . "');
            window.location.href='series.html';
          </script>";
    exit();
}

$conn->close();
?>
