<?php
// includes/verifica_login.php
session_start();

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

// Verifica se o usuário ainda existe no banco de dados
require_once 'conexao.php';

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT id, nome FROM usuarios WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    // Usuário não existe mais no banco
    session_destroy();
    header('Location: ../login.php');
    exit();
}

// Atualiza o nome do usuário na sessão (caso tenha mudado)
$_SESSION['usuario_nome'] = $usuario['nome'];
?>