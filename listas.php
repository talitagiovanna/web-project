<?php
session_start(); // Inicia a sessão para acessar os dados do usuário logado

$servername = "localhost";
$username = "root";
$password = "ROOT";
$dbname = "myserieslist";

// Configura o cabeçalho para retorno de JSON
header('Content-Type: application/json');

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Acesso negado. Faça login primeiro.']);
    exit;
}

// Capturar o ID do usuário logado
$usuario_id = $_SESSION['user_id']; // Usar o ID armazenado na sessão

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    echo json_encode(['error' => 'Erro de conexão com o banco de dados: ' . $conn->connect_error]);
    exit;
}

// Criar nova lista
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nova_lista'])) {
    $nome_lista = $_POST['nova_lista'];
    $stmt = $conn->prepare("INSERT INTO listas (nome, usuario_id) VALUES (?, ?)");
    $stmt->bind_param("si", $nome_lista, $usuario_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Lista criada com sucesso!']);
    } else {
        echo json_encode(['error' => 'Erro ao criar a lista.']);
    }
    $stmt->close();
    exit;
}

// Adicionar série a uma lista
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['lista_id'], $_POST['serie_id'])) {
    $lista_id = $_POST['lista_id'];
    $serie_id = $_POST['serie_id'];

    // Verificar se a série já está associada à lista
    $stmt = $conn->prepare("SELECT 1 FROM listas_series WHERE lista_id = ? AND serie_id = ?");
    $stmt->bind_param("ii", $lista_id, $serie_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['error' => 'A série já foi adicionada a essa lista.']);
        $stmt->close();
        exit;
    }

    // Adicionar série à lista
    $stmt = $conn->prepare("INSERT INTO listas_series (lista_id, serie_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $lista_id, $serie_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Série adicionada com sucesso!']);
    } else {
        echo json_encode(['error' => 'Erro ao adicionar a série à lista.']);
    }
    $stmt->close();
    exit;
}

// Editar nome da lista
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'editar_nome' && isset($_POST['lista_id'], $_POST['novo_nome'])) {
    $lista_id = $_POST['lista_id'];
    $novo_nome = $_POST['novo_nome'];
    $stmt = $conn->prepare("UPDATE listas SET nome = ? WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("sii", $novo_nome, $lista_id, $usuario_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => 'Nome da lista atualizado com sucesso!']);
    exit;
}

// Apagar lista
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'apagar_lista' && isset($_POST['lista_id'])) {
    $lista_id = $_POST['lista_id'];

    // Apagar todas as séries associadas a esta lista
    $stmt = $conn->prepare("DELETE FROM listas_series WHERE lista_id = ?");
    $stmt->bind_param("i", $lista_id);
    $stmt->execute();
    $stmt->close();

    // Apagar a lista
    $stmt = $conn->prepare("DELETE FROM listas WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $lista_id, $usuario_id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => 'Lista apagada com sucesso!']);
    exit;
}

// Retornar listas e séries associadas a cada lista via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'fetch') {
    // Buscar listas do usuário
    $listas_query = $conn->prepare("SELECT id, nome FROM listas WHERE usuario_id = ?");
    $listas_query->bind_param("i", $usuario_id);
    $listas_query->execute();
    $listas_result = $listas_query->get_result();
    $listas = $listas_result->fetch_all(MYSQLI_ASSOC);
    $listas_query->close();

    // Preparar consulta para buscar todas as séries, com possibilidade de filtro
    $series_query = "SELECT id, nome, poster_url FROM series";
    
    // Se houver um termo de pesquisa
    if (isset($_GET['pesquisa']) && !empty($_GET['pesquisa'])) {
        $pesquisa = '%' . $_GET['pesquisa'] . '%';
        $series_query .= " WHERE nome LIKE ?";
    }
    
    // Executar a consulta das séries
    $stmt = $conn->prepare($series_query);
    
    if (isset($pesquisa)) {
        $stmt->bind_param("s", $pesquisa);
    }
    
    $stmt->execute();
    $series_result = $stmt->get_result();
    $series = $series_result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Buscar as séries associadas a cada lista
    $listas_com_series = [];
    foreach ($listas as $lista) {
        $stmt = $conn->prepare("SELECT series.id, series.nome, series.poster_url FROM series
            JOIN listas_series ON series.id = listas_series.serie_id
            WHERE listas_series.lista_id = ?");
        $stmt->bind_param("i", $lista['id']);
        $stmt->execute();
        $serie_result = $stmt->get_result();
        $series_associadas = $serie_result->fetch_all(MYSQLI_ASSOC);
        $listas_com_series[] = [
            'lista' => $lista,
            'series' => $series_associadas
        ];
        $stmt->close();
    }

    echo json_encode(['listas' => $listas_com_series, 'series' => $series]);
    exit;
}

// Verifique se a ação é 'remover_serie_lista'
if ($_POST['action'] === 'remover_serie_lista') {
    $serie_id = $_POST['serie_id'];
    $lista_id = $_POST['lista_id'];

    // Lógica para remover a série da lista no banco de dados
    $query = "DELETE FROM lista_series WHERE lista_id = :lista_id AND serie_id = :serie_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':lista_id', $lista_id);
    $stmt->bindParam(':serie_id', $serie_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Série removida com sucesso!']);
    } else {
        echo json_encode(['error' => 'Erro ao remover série.']);
    }
}


$conn->close();
?>
