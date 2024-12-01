function submitSearchForm() {
    var inputSearch = document.getElementById('search-input').value; // Captura o valor do input

    // Redireciona para o perfil_series.php com o parâmetro de busca
    window.location.href = 'perfil_series.php?search=' + encodeURIComponent(inputSearch);
}


document.addEventListener('DOMContentLoaded', function () {
    const editarBtns = document.querySelectorAll('.editar-comentario');
    
    editarBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const comentarioId = this.getAttribute('data-comentario-id');
            const comentarioTexto = this.previousElementSibling.textContent; // Pegando o texto do comentário
            const formulario = document.getElementById('form-editar-comentario');
            
            // Preenchendo o campo de edição
            formulario.querySelector('textarea[name="comentario"]').value = comentarioTexto;
            formulario.querySelector('input[name="comentario_id"]').value = comentarioId;
            formulario.style.display = 'block'; // Exibe o formulário de edição
        });
    });
});


// Acessa a foto do usuário armazenada no localStorage
document.addEventListener('DOMContentLoaded', function() {
    var userFoto = localStorage.getItem('userFoto');
    if (!userFoto) {
        userFoto = 'default_avatar.jpg'; // Foto padrão caso não exista
    }

    // Atualiza a foto do usuário ao lado de cada comentário
    document.querySelectorAll('.foto-usuario').forEach(function(img) {
        img.src = userFoto;
    });

    // Editar comentário
    document.querySelectorAll('.editar-comentario').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var comentarioId = this.getAttribute('data-id');
        

            // Cria um campo para edição do comentário

            if (newComentario !== null) {
                // Envia o novo comentário para o servidor
                editarComentario(comentarioId, newComentario);
            }
        });
    });
});

// Função para editar o comentário
function editarComentario(id, novoComentario) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'editar_comentario.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert('Comentário atualizado!');
            location.reload(); // Recarrega a página para exibir o comentário atualizado
        } else {
            alert('Erro ao atualizar comentário!');
        }
    };
    xhr.send('id=' + id + '&comentario=' + encodeURIComponent(novoComentario));
}
