<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil da Série</title>

    <!-- Estilos internos -->
    <style>
        .success-message {
            font-weight: bold;
            padding: 10px;
            margin-bottom: 20px;
        }

        .success-message-verde {
            color: green;
            background-color: #e6ffed;
            border: 1px solid #c3e6cb;
        }

        .success-message-vermelho {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .fa {
            margin-right: 5px;
        }

        /* Estilo do Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 600px;
            border-radius: 10px;
        }

        textarea {
            width: 100%; /* Faz com que o campo ocupe toda a largura disponível do modal */
            max-width: 100%; /* Garante que o campo não ultrapasse a largura do modal */
            box-sizing: border-box; /* Para que a largura leve em conta o padding */
            resize: vertical; /* Permite que o usuário redimensione o campo verticalmente, mas não horizontalmente */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Botões Editar e Excluir */
        .editar-comentario {
            background-color: orange;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            margin-right: 10px;
        }

        .editar-comentario:hover {
            background-color: darkorange;
        }

        .excluir-comentario {
            background-color: red;
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }

        .excluir-comentario:hover {
            background-color: darkred;
        }

        /* Estilo do textarea */
        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        button[type="submit"] {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background-color: darkorange;
        }
    </style>
</head>
<body>

<?php
session_start();

// Incluir arquivo de conexão com o banco de dados
require 'database.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para comentar.");
}

// Verificar se há uma mensagem na URL para exibir a mensagem de sucesso
if (isset($_GET['message'])) {
    if ($_GET['message'] == 'ComentarioExcluido') {
        echo '<p class="success-message success-message-vermelho" id="success-message">Comentário excluído com sucesso!</p>';
    } elseif ($_GET['message'] == 'ComentarioInserido') {
        echo '<p class="success-message success-message-verde" id="success-message">Comentário inserido com sucesso!</p>';
    }
}

$usuarioId = $_SESSION['user_id']; // ID do usuário logado
$serieId = isset($_GET['id']) ? $_GET['id'] : '';

if (!empty($serieId)) {
    $query = "SELECT * FROM series WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $serieId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $serie = $result->fetch_assoc();

        $html = file_get_contents('perfil_series.html');

        $html = str_replace('{{NOME_SERIE}}', htmlspecialchars($serie['nome']), $html);
        $html = str_replace('{{IMAGEM_SERIE}}', htmlspecialchars($serie['poster_url']), $html);
        $html = str_replace('{{GENERO_SERIE}}', htmlspecialchars($serie['genero']), $html);
        $html = str_replace('{{SINOPSE_SERIE}}', htmlspecialchars($serie['sinopse']), $html);
        $html = str_replace('{{ELOCO_SERIE}}', htmlspecialchars($serie['elenco']), $html);
        $html = str_replace('{{ANO_LANCAMENTO_SERIE}}', htmlspecialchars($serie['ano_lancamento']), $html);
        $html = str_replace('{{ID_SERIE}}', $serie['id'], $html);

        // Inserir comentário no banco
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario']) && !empty($_POST['comentario'])) {
            $comentario = $_POST['comentario'];

            $query_insert = "INSERT INTO comentarios (comentario, usuario_id, serie_id, data_comentario) 
                             VALUES (?, ?, ?, NOW())";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bind_param("sii", $comentario, $usuarioId, $serieId);
            $stmt_insert->execute();

            // Redirecionar com a mensagem de sucesso após a inserção
            header("Location: perfil_series.php?id=" . $serieId . "&message=ComentarioInserido");
            exit();
        }

        $query_comentarios = "SELECT c.*, u.username AS nome_usuario FROM comentarios c
                              JOIN users u ON c.usuario_id = u.id 
                              WHERE c.serie_id = ? ORDER BY c.data_comentario DESC";
        $stmt_comentarios = $conn->prepare($query_comentarios);
        $stmt_comentarios->bind_param("i", $serie['id']);
        $stmt_comentarios->execute();
        $comentarios = $stmt_comentarios->get_result();

        $comentarios_html = '';
        while ($comentario = $comentarios->fetch_assoc()) {
            $comentarios_html .= '<div class="comentario">';
            $comentarios_html .= '<div class="comentario-usuario">';
            $comentarios_html .= '<span class="nome-usuario">' . htmlspecialchars($comentario['nome_usuario']) . '</span>';
            $comentarios_html .= '</div>';
            $comentarios_html .= '<p class="comentario-texto" id="comentario-texto-' . $comentario['id'] . '">' . htmlspecialchars($comentario['comentario']) . '</p>';
            $comentarios_html .= '<span class="data">' . date('d/m/Y H:i', strtotime($comentario['data_comentario'])) . '</span>';

            // Adicionar botão de exclusão e edição se o comentário for do usuário logado
            if ($comentario['usuario_id'] == $usuarioId) {
                $comentarios_html .= '<button class="editar-comentario" onclick="openEditModal(' . $comentario['id'] . ', \'' . addslashes($comentario['comentario']) . '\')">Editar</button>';
                $comentarios_html .= ' <button class="excluir-comentario" onclick="confirmDelete(' . $comentario['id'] . ', ' . $serie['id'] . ')">Excluir</button>';
            }

            $comentarios_html .= '</div>';
        }

        $html = str_replace('{{COMENTARIOS_RECBIDOS}}', $comentarios_html, $html);
        echo $html;
    } else {
        echo "Série não encontrada!";
    }
} else {
    echo "ID da série não especificado!";
}

$conn->close();
?>

<!-- Modal de Edição -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <form id="editForm" method="POST">
            <h2>Edite seu comentário:</h2>
            <textarea id="editComentario" name="comentario"></textarea>
            <input type="hidden" id="editComentarioId" name="id">
            <button type="submit">Atualizar Comentário</button>
        </form>
    </div>
</div>

<script>
    // Função para abrir o modal e carregar o comentário atual
    function openEditModal(comentarioId, comentarioTexto) {
    document.getElementById("editComentario").value = comentarioTexto;  // Preenche o textarea
    document.getElementById("editComentarioId").value = comentarioId;  // Preenche o id do comentário
    document.getElementById("editModal").style.display = "block";  // Abre o modal
    }

    // Exemplo de como abrir o modal com o comentário atual (quando clicar no botão de editar)
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function() {
            const comentarioId = this.dataset.comentarioId; // ID do comentário
            const comentarioTexto = this.dataset.comentarioTexto; // Texto do comentário
            openEditModal(comentarioId, comentarioTexto);
        });
    });

    function confirmDelete(comentarioId, serieId) {
        if (confirm('Tem certeza que deseja excluir este comentário?')) {
            window.location.href = 'excluir_comentario.php?id=' + comentarioId + '&serie_id=' + serieId;
        }
    }

    // Função para enviar o formulário e atualizar a página com o novo comentário
    document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var comentarioId = document.getElementById("editComentarioId").value;
    var comentarioTexto = document.getElementById("editComentario").value;

    var formData = new FormData();
    formData.append("id", comentarioId);
    formData.append("comentario", comentarioTexto);

    fetch('atualizar_comentario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualiza o comentário na página com o novo texto
            const comentarioElemento = document.getElementById("comentario-texto-" + comentarioId);
            comentarioElemento.innerText = comentarioTexto;
            comentarioElemento.style.whiteSpace = "pre-wrap";  // Para que quebre as linhas, se necessário

            // Fecha o modal após a atualização
            closeEditModal();

            // Limpa o campo do textarea (opcional, caso queira limpar após enviar)
            document.getElementById("editComentario").value = "";
        } else {
            alert(data.message); // Exibe a mensagem de erro, se houver
        }
    })
    .catch(error => {
        alert("Erro ao atualizar o comentário!");
    });
});

// Função para fechar o modal
function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

</script>

</body>
</html>
