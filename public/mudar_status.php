<?php
// public/mudar_status.php
require_once '../includes/verifica_login.php';
require_once '../includes/conexao.php';

$usuario_id = $_SESSION['usuario_id'];
$tarefa_id = $_GET['id'] ?? null;
$novo_status = $_GET['status'] ?? null;

// Validação simples
if (!$tarefa_id || !$novo_status) {
    header("Location: dashboard.php?erro=Dados inválidos.");
    exit;
}

// Lista de status permitidos para mudança rápida
$status_permitidos = ['Em Andamento', 'Concluída'];
if (!in_array($novo_status, $status_permitidos)) {
     header("Location: dashboard.php?erro=Status inválido.");
     exit;
}

// SQL SEGURO: Atualiza SOMENTE SE o id da tarefa E o id do usuário baterem
$sql = "UPDATE tarefas SET status = ? WHERE id = ? AND usuario_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$novo_status, $tarefa_id, $usuario_id]);

if ($stmt->rowCount() > 0) {
    header("Location: dashboard.php");
} else {
    header("Location: dashboard.php?erro=Não foi possível alterar o status.");
}
exit;
?>