<?php
// public/excluir_tarefa.php
require_once '../includes/verifica_login.php';
require_once '../includes/conexao.php';

$usuario_id = $_SESSION['usuario_id'];
$tarefa_id = $_GET['id'] ?? null;

if (!$tarefa_id) {
    header("Location: dashboard.php");
    exit;
}

// SQL SEGURO: Deleta SOMENTE SE o id da tarefa E o id do usuário baterem
$sql = "DELETE FROM tarefas WHERE id = ? AND usuario_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$tarefa_id, $usuario_id]);

if ($stmt->rowCount() > 0) {
    // Excluiu com sucesso
    header("Location: dashboard.php?sucesso=Tarefa excluída!");
} else {
    // Ou a tarefa não existe, ou não pertence ao usuário
    header("Location: dashboard.php?erro=Não foi possível excluir a tarefa.");
}
exit;
?>