document.addEventListener("DOMContentLoaded", function() {
    const createAccountLink = document.querySelector('a[href="signin.html"]');
    const createAccountButton = document.querySelector('.cta-button');
    const dialog = document.getElementById('signup-dialog');

    // Event listener para o link "Criar Conta" no navbar
    createAccountLink.addEventListener('click', function(event) {
        event.preventDefault(); // Evita que o link redirecione para signin.html
        dialog.classList.add('active');
    });

    // Event listener para o botão "Criar Conta" abaixo do header
    createAccountButton.addEventListener('click', function(event) {
        event.preventDefault(); // Evita o comportamento padrão do botão
        dialog.classList.add('active');
    });

    // Fechar o diálogo ao clicar fora da área do formulário
    dialog.addEventListener('click', function(event) {
        if (event.target === dialog) {
            dialog.classList.remove('active');
        }
    });
});
