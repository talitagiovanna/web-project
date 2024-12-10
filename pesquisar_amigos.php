<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeUsuario = $_POST['nomeUsuario'];

    // Ajuste a consulta para usar as colunas corretas
    $sql = "SELECT id, username FROM users WHERE username LIKE ? OR name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $nomeUsuario . '%';
    $stmt->bind_param('ss', $searchTerm, $searchTerm);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="usuario-item">';
            echo '<span>' . htmlspecialchars($row['username']) . '</span>';
            echo '<button class="seguir-btn" data-id="' . $row['id'] . '">Seguir</button>';
            echo '</div>';
        }
    } else {
        echo '<p>Nenhum usuário encontrado.</p>';
    }

    $stmt->close();
}

$conn->close();
?>
