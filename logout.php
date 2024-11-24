<?php
  // Inicia a sessão
  session_start();

  // Destrói todas as variáveis de sessão
  session_unset();

  // Destrói a sessão
  session_destroy();

  // Redireciona o usuário para a página inicial ou de login
  header("Location: index.html");  // Ou para login.php, se for o caso
  exit();
?>
