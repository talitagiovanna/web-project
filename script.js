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
