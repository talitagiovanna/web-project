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

// Busca os usuários que o usuário segue
$sqlSeguindo = "
    SELECT u.id, u.username, u.name 
    FROM seguindo s
    INNER JOIN users u ON s.seguido_id = u.id
    WHERE s.seguidor_id = ?
";
$stmtSeguindo = $conn->prepare($sqlSeguindo);
$stmtSeguindo->bind_param("i", $userId);
$stmtSeguindo->execute();
$resultSeguindo = $stmtSeguindo->get_result();

$seguindo = [];
while ($row = $resultSeguindo->fetch_assoc()) {
    $seguindo[] = $row;
}

// Busca os seguidores do usuário
$sqlSeguidores = "
    SELECT u.id, u.username, u.name 
    FROM seguindo s
    INNER JOIN users u ON s.seguidor_id = u.id
    WHERE s.seguido_id = ?
";
$stmtSeguidores = $conn->prepare($sqlSeguidores);
$stmtSeguidores->bind_param("i", $userId);
$stmtSeguidores->execute();
$resultSeguidores = $stmtSeguidores->get_result();

$seguidores = [];
while ($row = $resultSeguidores->fetch_assoc()) {
    $seguidores[] = $row;
}

// Fechar conexões preparadas
$stmtSeguindo->close();
$stmtSeguidores->close();

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

        .seguir-btn {
            padding: 8px 16px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .seguir-btn:disabled {
            background-color: gray;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <svg viewBox="0 0 640 512" fill="orange" xmlns="http://www.w3.org/2000/svg"><path d="M206.9 245.1C171 255.6 146.8 286.4 149.3 319.3C160.7 306.5 178.1 295.5 199.3 288.4L206.9 245.1zM95.78 294.9L64.11 115.5C63.74 113.9 64.37 112.9 64.37 112.9c57.75-32.13 123.1-48.99 189-48.99c1.625 0 3.113 .0745 4.738 .0745c13.1-13.5 31.75-22.75 51.62-26c18.87-3 38.12-4.5 57.25-5.25c-9.999-14-24.47-24.27-41.84-27.02c-23.87-3.875-47.9-5.732-71.77-5.732c-76.74 0-152.4 19.45-220.1 57.07C9.021 70.57-3.853 98.5 1.021 126.6L32.77 306c14.25 80.5 136.3 142 204.5 142c3.625 0 6.777-.2979 10.03-.6729c-13.5-17.13-28.1-40.5-39.5-67.63C160.1 366.8 101.7 328 95.78 294.9zM193.4 157.6C192.6 153.4 191.1 149.7 189.3 146.2c-8.249 8.875-20.62 15.75-35.25 18.37c-14.62 2.5-28.75 .376-39.5-5.249c-.5 4-.6249 7.998 .125 12.12c3.75 21.75 24.5 36.24 46.25 32.37C182.6 200.1 197.3 179.3 193.4 157.6zM606.8 121c-88.87-49.38-191.4-67.38-291.9-51.38C287.5 73.1 265.8 95.85 260.8 123.1L229 303.5c-15.37 87.13 95.33 196.3 158.3 207.3c62.1 11.13 204.5-53.68 219.9-140.8l31.75-179.5C643.9 162.3 631 134.4 606.8 121zM333.5 217.8c3.875-21.75 24.62-36.25 46.37-32.37c21.75 3.75 36.25 24.49 32.5 46.12c-.7499 4.125-2.25 7.873-4.125 11.5c-8.249-9-20.62-15.75-35.25-18.37c-14.75-2.625-28.75-.3759-39.5 5.124C332.1 225.9 332.9 221.9 333.5 217.8zM403.1 416.5c-55.62-9.875-93.49-59.23-88.99-112.1c20.62 25.63 56.25 46.24 99.49 53.87c43.25 7.625 83.74 .3781 111.9-16.62C512.2 392.7 459.7 426.3 403.1 416.5zM534.4 265.2c-8.249-8.875-20.75-15.75-35.37-18.37c-14.62-2.5-28.62-.3759-39.5 5.249c-.5-4-.625-7.998 .125-12.12c3.875-21.75 24.62-36.25 46.37-32.37c21.75 3.875 36.25 24.49 32.37 46.24C537.6 257.9 536.1 261.7 534.4 265.2z"/></svg>
            <span>My Series List</span>
        </div>
        <ul>
            <li><a href="welcome-two.php">Início</a></li>
            <li><a href="perfil.php">Meu Perfil</a></li>
            <li><a href="series.html">Séries</a></li>
            <li><a href="pesquisarseries.php">Pesquisar</a></li>
            <li><a href="logout.php">Sair</a></li>
            <li class="dropdown">
        <a href="#" id="notification-toggle">Notificações (<span id="notification-count">0</span>)</a>
        <div id="notification-menu" class="dropdown-menu">
            <!-- Lista de notificações será carregada aqui -->
            <p>Sem notificações novas.</p>
        </div>
    </li>
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
    <div class="tabs">
        <button class="tab-btn" onclick="showTab('seguindo')">Seguindo</button>
        <button class="tab-btn" onclick="showTab('seguidores')">Seguidores</button>
    </div>
    <div id="seguindo-tab" class="tab-content">
        <h4>Seguindo</h4>
        <?php if (empty($seguindo)): ?>
            <p>Você não está seguindo ninguém.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($seguindo as $user): ?>
                    <li><?php echo htmlspecialchars($user['name'] ?? $user['username']); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <div id="seguidores-tab" class="tab-content">
        <h4>Seguidores</h4>
        <?php if (empty($seguidores)): ?>
            <p>Ninguém está seguindo você ainda.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($seguidores as $user): ?>
                    <li><?php echo htmlspecialchars($user['name'] ?? $user['username']); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
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

<div id="modal-editar-series" class="modal-editar-series">
    <div class="modal-editar-series-conteudo">
        <span id="fechar-modal" class="fechar-modal">&times;</span>
        <h2>Adicionar Séries</h2>
        <input type="text" id="pesquisar-series" placeholder="Pesquisar séries...">
        <div id="conteudo-modal"></div> <!-- Aqui você vai colocar os conteúdos das séries -->
    </div>
</div>


    <script src="perfil.js"></script>
</body>
</html>
