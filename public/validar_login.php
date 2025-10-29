<?php
// public/validar_login.php
require_once '../includes/conexao.php'; // Já inicia a sessão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        header("Location: login.php?erro=Email e senha são obrigatórios!");
        exit;
    }

    $sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    // Verifica se o usuário existe E se a senha está correta
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido
        // session_start() já foi chamado no conexao.php
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        
        header("Location: dashboard.php");
        exit;
    } else {
        // Falha no login
        header("Location: login.php?erro=Email ou senha inválidos!");
        exit;
    }
} else {
    // Se não for POST, redireciona
    header("Location: login.php");
    exit;
}
?>