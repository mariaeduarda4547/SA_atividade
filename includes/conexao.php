<?php
// includes/conexao.php

$host = 'localhost';     // Ou seu host (ex: 127.0.0.1)
$dbname = 'mytaskmanager'; // Nome do banco de dados que você criou
$user = 'root';          // Seu usuário do MySQL (padrão do XAMPP/WAMP)
$pass = '';              // Sua senha do MySQL (padrão do XAMPP/WAMP é vazia)
$charset = 'utf8mb4';

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Opções do PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna resultados como array associativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa prepared statements nativos
];

try {
     // Cria a instância do PDO
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Se a conexão falhar, exibe o erro
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Inicia a sessão em todas as páginas que incluírem este arquivo.
// Isso é necessário para o sistema de login (Etapa 2).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}