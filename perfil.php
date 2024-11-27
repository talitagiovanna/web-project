<?php
session_start();

// Incluir arquivo de conexão com o banco de dados
require 'database.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['user_id']; // Obtém o ID do usuário da sessão

// Busca os dados do usuário
$sqlUser = "SELECT username, name, email, bio FROM users WHERE id = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$user = $resultUser->fetch_assoc();

// Verifica se o usuário foi encontrado
if (!$user) {
    die("Usuário não encontrado.");
}

// Inicializa as variáveis com valores seguros
$username = htmlspecialchars($user['username']);
$name = htmlspecialchars($user['name'] ?? ''); // Se null, fica vazio
$email = htmlspecialchars($user['email'] ?? ''); // Se null, fica vazio
$bio = htmlspecialchars($user['bio'] ?? ''); // Se null, fica vazio

// Busca as séries assistidas, avaliações e datas
$sqlSeries = "
    SELECT s.id, s.nome, s.poster_url, sa.avaliacao, sa.data_assistida
    FROM series_assistidas sa
    INNER JOIN series s ON sa.serie_id = s.id
    WHERE sa.usuario_id = ?
";
$stmtSeries = $conn->prepare($sqlSeries);
$stmtSeries->bind_param("i", $userId);
$stmtSeries->execute();
$resultSeries = $stmtSeries->get_result();

// Array para armazenar séries assistidas
$series_assistidas = [];
if ($resultSeries->num_rows > 0) {
    while ($row = $resultSeries->fetch_assoc()) {
        $series_assistidas[] = $row;
    }
}

// Fechar conexões preparadas
$stmtUser->close();
$stmtSeries->close();
$conn->close();
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil &bull; My Series List</title>
    <link rel="icon" href="assets/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="perfil.css">
    <script src="script.js" defer></script>
    <style>
        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0 auto;
            padding: 0;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <svg viewBox="0 0 640 512" fill="orange" xmlns="http://www.w3.org/2000/svg">
                <path d="..."></path>
            </svg>
            <span>My Series List</span>
        </div>
        <ul>
            <li><a href="welcome-two.php">Início</a></li>
            <li><a href="perfil.php">Meu Perfil</a></li>
            <li><a href="series.html">Séries</a></li>
            <li><a href="pesquisarseries.php">Pesquisar</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>      
    </nav>

    <!-- Container do Perfil -->
    <div class="profile-container">
        <div class="profile-details">
            <div class="profile-picture">
                <input type="file" id="profile-photo" accept="image/*" style="display: none;" />
                <img src="assets/default-avatar.jpg" alt="Profile Picture" id="profile-img" class="clickable-avatar">
            </div>
            <div class="profile-username">
                <h3><?php echo !empty($user['name']) ? htmlspecialchars($user['name']) : htmlspecialchars($user['username']); ?></h3>
                <button id="edit-profile-btn" class="edit-profile-btn">Editar Perfil</button>
            </div>
        </div>
    </div>

    <!-- Links para seções -->
    <div class="profile-info">
        <button id="atividades-btn" class="profile-btn">Atividades</button>
        <button id="series-btn" class="profile-btn">Séries</button>
        <button id="amigos-btn" class="profile-btn">Amigos</button>
        <button id="listas-btn" class="profile-btn">Listas</button>
    </div>

    <!-- Modal de edição de perfil -->
    <div id="edit-profile-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Editar Perfil</h2>
            <form id="edit-profile-form" action="editar_perfil.php" method="POST">
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo $bio; ?></textarea>
                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>

    <!-- Seções de conteúdo -->
    <div id="atividades-section" class="slide-section">
        <h3>Atividades</h3>
        <p>Conteúdo das atividades...</p>
    </div>


     <!-- Seções das séries -->
     <div id="series-section" class="slide-section">
    <h3>Séries Assistidas</h3>
    <div class="series-container">
        <!-- Botão "+" -->
        <div class="add-series-card">
            <a href="cadastrar_assistido.php" class="add-series-link" aria-label="Adicionar nova série">
                <div class="plus-icon">+</div>
            </a>
        </div>

        <!-- Listagem de séries -->
        <div class="series-list">
            <?php foreach ($series_assistidas as $serie): ?>
                <div class="series-card" onclick="openModal(<?php echo $serie['id']; ?>)">
                    <img src="<?php echo htmlspecialchars($serie['poster_url'], ENT_QUOTES); ?>" 
                         alt="Poster da série <?php echo htmlspecialchars($serie['nome'], ENT_QUOTES); ?>">
                    <div class="card-overlay">
                        <h4><?php echo htmlspecialchars($serie['nome'], ENT_QUOTES); ?></h4>
                        <p>Avaliação: <?php echo htmlspecialchars($serie['avaliacao'], ENT_QUOTES); ?>/10</p>
                        <p>Assistido em: <?php echo date("d/m/Y", strtotime($serie['data_assistida'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modalseries" class="modalseries">
    <div class="modal-content">
        <span class="close-btn" id="close-btn">&times;</span>
        <h2>Editar Série</h2>
        <form id="edit-form" method="POST">
            <input type="hidden" name="serie_id" id="serie_id">
            <label for="avaliacao">Avaliação:</label>
            <input type="number" id="avaliacao" name="avaliacao" min="1" max="10" required>
            <label for="data_assistida">Data Assistida:</label>
            <input type="date" id="data_assistida" name="data_assistida" required>
            <button type="submit">Atualizar</button>
        </form>
    </div>
</div>




    <div id="amigos-section" class="slide-section">
        <h3>Amigos</h3>
        <p>Conteúdo dos amigos...</p>
    </div>
    <div id="listas-section" class="slide-section">
    <h2>Minhas Listas</h2>

    <!-- Botão do Menu Hamburguer -->
    <button class="menu-hamburguer" id="menu-btn" aria-label="Abrir menu">&#9776;</button>

    <!-- Menu Lateral -->
    <div class="menu-lateral" id="menu-lateral">
        <h3>Criar Lista</h3>
        <form id="form-criar-lista">
            <label for="nova_lista">Nova Lista</label>
            <input 
                type="text" 
                id="nova_lista" 
                name="nova_lista" 
                placeholder="Digite o nome da lista" 
                required
            >
            <button type="submit">Criar Lista</button>
        </form>
    </div>
    
    <!-- Exibir listas -->
    <div id="listas-container" aria-live="polite">
        <!-- As listas serão carregadas dinamicamente aqui -->
    </div>
</div>

    <script src="perfil.js"></script>
</body>
</html>
