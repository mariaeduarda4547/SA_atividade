<?php
// public/logout.php
session_start(); // Inicia a sessão para poder destruí-la

session_unset();   // Limpa todas as variáveis da sessão
session_destroy(); // Destrói a sessão

header("Location: login.php?sucesso=Logout realizado com sucesso!");
exit;
?>