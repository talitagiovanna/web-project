document.addEventListener("DOMContentLoaded", function() {
    const createAccountLink = document.querySelector('a[href="signin.html"]');
    const createAccountButton = document.querySelector('.cta-button');
    const dialog = document.getElementById('signup-dialog');
    
    const signinLink = document.getElementById('signin-link'); // Selecionar o link de Entrar pelo ID
    const loginDialog = document.getElementById('login-dialog');
    const loginForm = document.getElementById('login-form');
    //const entrarButton = document.querySelector('.cta-button');
    //const dialog2 = document.getElementById('login-dialog');

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
    
    // Event listener para o link "Entrar" no navbar
    signinLink.addEventListener('click', function(event) {
        event.preventDefault(); // Evitar o comportamento padrão do link
        loginDialog.style.display = 'block'; // Exibir o diálogo de Entrar
    });

    // Fechar o diálogo de login ao clicar fora da área do formulário
    loginDialog.addEventListener('click', function(event) {
        if (event.target === loginDialog) {
            loginDialog.style.display = 'none'; // Fechar o diálogo se o clique for fora do formulário
        }
    });

    // Event listener para o formulário de login
    loginForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(loginForm);
        const email = formData.get('email');
        const password = formData.get('password');

        try {
            const response = await fetch('login.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Erro ao tentar fazer login.');
            }

            const data = await response.json();

            if (data.success) {
                // Redirecionar para a página de perfil ou página inicial do usuário
                window.location.href = 'perfil.php';
            } else {
                // Exibir mensagem de erro
                alert(data.message);
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Ocorreu um erro ao tentar fazer login. Por favor, tente novamente mais tarde.');
        }
    });
});