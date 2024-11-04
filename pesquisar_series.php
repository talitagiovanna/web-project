<?php
include('database.php');

$nomeSerie = isset($_POST['nome']) ? $_POST['nome'] : '';
$genero = isset($_POST['genero']) ? $_POST['genero'] : '';
$anoLancamento = isset($_POST['ano']) ? $_POST['ano'] : '';

$query = "SELECT * FROM series WHERE 1=1";
if (!empty($nomeSerie)) {
    $query .= " AND nome LIKE '%" . $conn->real_escape_string($nomeSerie) . "%'";
}
if (!empty($genero)) {
    $query .= " AND genero = '" . $conn->real_escape_string($genero) . "'";
}
if (!empty($anoLancamento)) {
    $query .= " AND ano_lancamento = '" . $conn->real_escape_string($anoLancamento) . "'";
}

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($serie = $result->fetch_assoc()) {
        echo '<div class="serie-item" data-id="' . htmlspecialchars($serie['id']) . '">';
        echo '<img src="' . htmlspecialchars($serie['poster_url']) . '" alt="Poster da Série" class="serie-poster">';
        echo '<div class="serie-info">';
        echo '<h3>' . htmlspecialchars($serie['nome']) . '</h3>';
        echo '<p><strong>Gênero:</strong> ' . htmlspecialchars($serie['genero']) . '</p>';
        echo '<p><strong>Ano de Lançamento:</strong> ' . htmlspecialchars($serie['ano_lancamento']) . '</p>';
        echo '<p><strong>Sinopse:</strong> ' . htmlspecialchars($serie['sinopse']) . '</p>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p>Nenhuma série encontrada.</p>';
}

$conn->close();
?>
