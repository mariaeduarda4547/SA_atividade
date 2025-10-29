<?php
// public/cadastrar.php
require_once '../includes/conexao.php';

$mensagem_erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (seu código PHP de cadastro continua aqui, sem mudança) ...
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem_erro = "Todos os campos são obrigatórios!";
    } else {
        $sql_check = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$email]);
        
        if ($stmt_check->fetch()) {
            $mensagem_erro = "Este email já está cadastrado!";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            
            if ($stmt_insert->execute([$nome, $email, $senha_hash])) {
                header("Location: login.php?sucesso=Cadastro realizado!");
                exit;
            } else {
                $mensagem_erro = "Erro ao cadastrar. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>My Task Manager - Cadastro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <main>
        <div class="container login-container">
            <h2>Cadastrar-se</h2>
            
            <?php if ($mensagem_erro): ?>
                <p class="error"><?php echo $mensagem_erro; ?></p>
            <?php endif; ?>

            <form action="cadastrar.php" method="POST">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn">Cadastrar</button>
            </form>
            <p>Já tem uma conta? <a href="login.php">Faça login aqui</a>.</p>
        </div>
    </main>
    </body>
</html>