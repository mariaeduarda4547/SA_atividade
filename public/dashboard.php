<?php
// public/dashboard.php
require_once '../includes/verifica_login.php';
require_once '../includes/conexao.php';

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['titulo'])) {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    
    if (!empty($titulo)) {
        try {
            $sql_insert = "INSERT INTO tarefas (titulo, descricao, status, usuario_id) 
                           VALUES (?, ?, 'Pendente', ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            
            if ($stmt_insert->execute([$titulo, $descricao, $usuario_id])) {
                header("Location: dashboard.php"); 
                exit;
            }
        } catch (PDOException $e) {
            $erro = "Erro ao criar tarefa: " . $e->getMessage();
        }
    } else {
        $erro = "O título da tarefa é obrigatório!";
    }
}

// Restante do código para buscar tarefas...
$sql = "SELECT * FROM tarefas WHERE usuario_id = ? ORDER BY 
        CASE status
            WHEN 'Pendente' THEN 1
            WHEN 'Em Andamento' THEN 2
            WHEN 'Concluída' THEN 3
        END, data_criacao DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario_id]);
$tarefas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - My Task Manager</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <?php include_once 'header.php'; ?>

    <main>
        <div class="container">
            <h2>Olá, <?php echo htmlspecialchars($usuario_nome); ?>!</h2>
            <p>Estas são suas tarefas.</p>

            <!-- Exibir mensagens de erro/sucesso -->
            <?php if (isset($erro)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($erro); ?>
                </div>
            <?php endif; ?>

            <div class="header-actions">
                <button id="btn-nova-tarefa" class="btn">
                    + Adicionar Nova Tarefa
                </button>
            </div>

            <div class="task-form" id="form-nova-tarefa">
                <h3>Nova Tarefa</h3>
                <form action="dashboard.php" method="POST">
                    <div class="form-group">
                        <label for="titulo">Título da Tarefa *</label>
                        <input type="text" id="titulo" name="titulo" placeholder="Título da Tarefa" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição (opcional)</label>
                        <textarea name="descricao" id="descricao" placeholder="Descrição (opcional)"></textarea>
                    </div>
                    <button type="submit" class="btn">Adicionar Tarefa</button>
                </form>
            </div>

            <!-- Restante do seu HTML permanece igual -->
            <div class="task-list">
                <h3>Minhas Tarefas</h3>
                <?php if (count($tarefas) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tarefas as $tarefa): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($tarefa['titulo']); ?></td>
                                    <td><span class="status status-<?php echo strtolower(str_replace(' ', '-', $tarefa['status'])); ?>"><?php echo $tarefa['status']; ?></span></td>
                                    <td class="actions">
                                        <?php if ($tarefa['status'] == 'Pendente'): ?>
                                            <a href="mudar_status.php?id=<?php echo $tarefa['id']; ?>&status=Em Andamento" class="btn-action btn-start">Iniciar</a>
                                        <?php elseif ($tarefa['status'] == 'Em Andamento'): ?>
                                            <a href="mudar_status.php?id=<?php echo $tarefa['id']; ?>&status=Concluída" class="btn-action btn-complete">Concluir</a>
                                        <?php endif; ?>
                                        
                                        <a href="editar_tarefa.php?id=<?php echo $tarefa['id']; ?>" class="btn-action btn-edit">Editar</a>
                                        <a href="excluir_tarefa.php?id=<?php echo $tarefa['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nenhuma tarefa encontrada. Clique no botão acima para criar sua primeira tarefa!</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        const btnNovaTarefa = document.getElementById('btn-nova-tarefa');
        const formNovaTarefa = document.getElementById('form-nova-tarefa');

        btnNovaTarefa.addEventListener('click', function() {
            formNovaTarefa.classList.toggle('form-visivel');
        });
    </script>
</body>
</html>