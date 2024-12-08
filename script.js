document.addEventListener('DOMContentLoaded', () => {
    const loginLink = document.getElementById('login-link');
    const loginDialog = document.getElementById('login-dialog');
    const createAccountLink = document.getElementById('create-account-link');
    const createAccountDialog = document.getElementById('create-account-dialog');
    const closeButtons = document.querySelectorAll('.close-button');

    loginLink.addEventListener('click', (e) => {
        e.preventDefault();
        loginDialog.style.display = 'flex';
    });

    createAccountLink.addEventListener('click', (e) => {
        e.preventDefault();
        createAccountDialog.style.display = 'flex';
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            button.parentElement.parentElement.style.display = 'none';
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target === loginDialog || e.target === createAccountDialog) {
            e.target.style.display = 'none';
        }
    });
});

// Função para adicionar uma notificação
function addNotification(message) {
    const formData = new FormData();
    formData.append('action', 'add');  // Define a ação como 'add'
    formData.append('message', message);  // Passa a mensagem a ser salva

    fetch('notifications.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Notificação adicionada:', data.message);
            loadNotifications(); // Atualiza as notificações
        } else {
            console.error('Erro ao adicionar notificação:', data.message);
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
    });
}

// Função para carregar notificações
function loadNotifications() {
    const formData = new FormData();
    formData.append('action', 'get');  // Define a ação como 'get'

    fetch('notifications.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const menu = document.getElementById('notification-menu');
        if (Array.isArray(data)) {  // Verifica se o retorno é um array
            if (data.length === 0) {
                menu.innerHTML = '<p>Sem notificações novas.</p>';
                return;
            }

            // Atualiza a lista de notificações
            menu.innerHTML = '';
            data.forEach(notification => {
                const div = document.createElement('div');
                div.textContent = notification.message;

                // Marcar notificação como lida ao clicar
                div.addEventListener('click', () => markNotificationAsRead(notification.id));

                menu.appendChild(div);
            });

            // Atualiza o contador de notificações não lidas
            const unreadCount = data.filter(notification => !notification.is_read).length;
            document.getElementById('notification-count').textContent = unreadCount;
        } else {
            console.error('Erro: Dados recebidos não são um array de notificações');
        }
    })
    .catch(error => console.error('Erro ao carregar notificações:', error));
}

// Função para marcar notificação como lida
function markNotificationAsRead(notificationId) {
    fetch('notifications.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'mark-read',
            notification_id: notificationId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Notificação marcada como vista.');
            loadNotifications();  // Atualiza as notificações
            deleteReadNotifications();  // Exclui notificações lidas
        } else {
            console.error('Erro ao marcar notificação como vista:', data.message);
        }
    })
    .catch(error => console.error('Erro ao marcar notificação:', error));
}

// Função para excluir notificações lidas do banco
function deleteReadNotifications() {
    fetch('notifications.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'delete-read'  // Ação para excluir notificações lidas
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Notificações lidas excluídas.');
        } else {
            console.error('Erro ao excluir notificações lidas:', data.message);
        }
    })
    .catch(error => console.error('Erro ao excluir notificações lidas:', error));
}

// Função para excluir notificações antigas
function deleteOldNotifications() {
    fetch('notifications.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'delete-old'  // Ação para excluir notificações antigas
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Notificações antigas excluídas.');
        } else {
            console.error('Erro ao excluir notificações antigas:', data.message);
        }
    })
    .catch(error => console.error('Erro ao excluir notificações antigas:', error));
}

// Inicializa a busca de listas ao carregar a página
loadNotifications();