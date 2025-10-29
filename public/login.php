<?php
// public/login.php
session_start(); // Necessário para verificar se já está logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php"); // Se já está logado, vai pro dashboard
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>My Task Manager - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <main>
        <div class="container login-container">
            <h2>Login - My Task Manager</h2>

            <?php if (isset($_GET['erro'])): ?>
                <p class="error"><?php echo htmlspecialchars($_GET['erro']); ?></p>
            <?php endif; ?>
            <?php if (isset($_GET['sucesso'])): ?>
                <p class="success"><?php echo htmlspecialchars($_GET['sucesso']); ?></p>
            <?php endif; ?>

            <form action="validar_login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn">Entrar</button>
            </form>
            <p>Não tem uma conta? <a href="cadastrar.php">Cadastre-se aqui</a>.</p>
        </div>
    </main>
    </body>
</html>