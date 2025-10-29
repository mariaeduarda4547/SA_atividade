<?php
// public/header.php
// Este arquivo é incluído em páginas protegidas,
// então a sessão ($_SESSION['usuario_nome']) já deve existir.
?>
<header>
    <nav class="container">
        <a href="dashboard.php" class="logo">My Task Manager</a>
        <div class="user-info">
            <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
            <a href="logout.php" class="btn btn-logout">Sair</a>
        </div>
    </nav>
</header>