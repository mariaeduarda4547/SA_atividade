<?php
// public/editar_tarefa.php
require_once '../includes/verifica_login.php';
require_once '../includes/conexao.php';

// --- (LÓGICA PHP) ---
$usuario_id = $_SESSION['usuario_id'];
$tarefa_id = $_GET['id'] ?? null;
$mensagem = '';
$tipo_mensagem = ''; // 'sucesso' ou 'erro'

if (!$tarefa_id) {
    header("Location: dashboard.php");
    exit;
}

// Lógica de Update (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $status = $_POST['status'];
    
    if (empty($titulo) || empty($status)) {
        $mensagem = "Título e Status são obrigatórios.";
        $tipo_mensagem = 'erro';
    } else {
        try {
            $sql_update = "UPDATE tarefas SET titulo = ?, descricao = ?, status = ? 
                           WHERE id = ? AND usuario_id = ?";
            $stmt_update = $pdo->prepare($sql_update);
            
            if ($stmt_update->execute([$titulo, $descricao, $status, $tarefa_id, $usuario_id])) {
                header("Location: dashboard.php?sucesso=Tarefa atualizada com sucesso!");
                exit;
            } else {
                $mensagem = "Erro ao atualizar a tarefa.";
                $tipo_mensagem = 'erro';
            }
        } catch (PDOException $e) {
            $mensagem = "Erro no sistema: " . $e->getMessage();
            $tipo_mensagem = 'erro';
        }
    }
}

// Lógica de Buscar Dados (GET)
$sql_select = "SELECT * FROM tarefas WHERE id = ? AND usuario_id = ?";
$stmt_select = $pdo->prepare($sql_select);
$stmt_select->execute([$tarefa_id, $usuario_id]);
$tarefa = $stmt_select->fetch();

// Se não encontrou a tarefa (ou ela não pertence ao usuário), volta ao dashboard
if (!$tarefa) {
    header("Location: dashboard.php?erro=Tarefa não encontrada!");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa - Minhas Tarefas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #a8a9b1ff 0%, #c6c5c7ff 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .main-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .edit-form-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: #007bff;
            font-weight: 500;
            margin-bottom: 15px;
            padding: 8px 16px;
            border: 1px solid #007bff;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .back-link:hover {
            background-color: #007bff;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }
        
        .page-title {
            color: #333;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .page-subtitle {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 1;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,123,255,0.4);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #545b62);
            color: white;
            box-shadow: 0 4px 15px rgba(108,117,125,0.3);
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108,117,125,0.4);
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            text-align: center;
        }
        
        .alert-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
            transform: translateY(-1px);
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-group select {
            background-color: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'><path fill='%23333' d='M2 0L0 2h4zm0 5L0 3h4z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }
        
        .form-info {
            margin-top: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            font-size: 14px;
            color: #666;
            border-left: 4px solid #007bff;
        }
        
        .form-info strong {
            color: #333;
        }
        
        .required {
            color: #dc3545;
        }
        
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .edit-form-container {
                padding: 30px 20px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="edit-form-container">
            <div class="page-header">
                <a href="dashboard.php" class="back-link">← Voltar para o Dashboard</a>
                <h1 class="page-title">Editar Tarefa</h1>
                <p class="page-subtitle">Atualize os detalhes da sua tarefa</p>
            </div>

            <?php if ($mensagem): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>
            <?php endif; ?>

            <form action="editar_tarefa.php?id=<?php echo $tarefa_id; ?>" method="POST">
                <div class="form-group">
                    <label for="titulo">
                        Título da Tarefa <span class="required">*</span>
                    </label>
                    <input type="text" id="titulo" name="titulo" 
                           value="<?php echo htmlspecialchars($tarefa['titulo']); ?>" 
                           placeholder="Digite o título da tarefa" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" 
                              placeholder="Descreva detalhes sobre esta tarefa (opcional)"><?php echo htmlspecialchars($tarefa['descricao']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status">
                        Status <span class="required">*</span>
                    </label>
                    <select id="status" name="status" required>
                        <option value="Pendente" <?php echo ($tarefa['status'] == 'Pendente') ? 'selected' : ''; ?>>
                             Pendente
                        </option>
                        <option value="Em Andamento" <?php echo ($tarefa['status'] == 'Em Andamento') ? 'selected' : ''; ?>>
                             Em Andamento
                        </option>
                        <option value="Concluída" <?php echo ($tarefa['status'] == 'Concluída') ? 'selected' : ''; ?>>
                             Concluída
                        </option>
                    </select>
                </div>
                
                <div class="form-info">
                    <strong>Informações da Tarefa:</strong><br>
                    <strong>Data de Criação:</strong> <?php echo date('d/m/Y H:i', strtotime($tarefa['data_criacao'])); ?><br>
                    <strong>ID da Tarefa:</strong> #<?php echo $tarefa['id']; ?>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                         Salvar Alterações
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary">
                         Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const tituloInput = document.getElementById('titulo');
            const statusSelect = document.getElementById('status');
            
            // Validação antes de enviar
            form.addEventListener('submit', function(e) {
                if (!tituloInput.value.trim()) {
                    e.preventDefault();
                    alert('Por favor, preencha o título da tarefa.');
                    tituloInput.focus();
                    return;
                }
                
                if (!statusSelect.value) {
                    e.preventDefault();
                    alert('Por favor, selecione um status.');
                    statusSelect.focus();
                    return;
                }
                
                // Mostrar loading
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = ' Salvando...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>
