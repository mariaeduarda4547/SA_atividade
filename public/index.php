<?php
// public/index.php

// Inicia a sessão para verificar se o usuário está logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se a sessão 'usuario_id' já existe
if (isset($_SESSION['usuario_id'])) {
    // Usuário está logado, redireciona para o painel principal
    header("Location: dashboard.php");
    exit;
} else {
    // Usuário não está logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}
?>