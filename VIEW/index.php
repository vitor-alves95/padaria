<?php
// VIEW/index.php
// Redireciona para o login se não houver sessão ativa
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

// Se houver sessão, redireciona para o dashboard
header('Location: dashboard.php'); // Ou apenas mostre o dashboard aqui
exit();
?>