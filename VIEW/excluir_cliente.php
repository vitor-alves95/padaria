<?php
// VIEW/excluir_cliente.php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

require_once '../DAL/ClienteDAL.php';

$id_cliente = isset($_GET['id']) ? intval($_GET['id']) : 0;

$clienteDAL = new ClienteDAL();

if ($id_cliente > 0) {
    if ($clienteDAL->excluirCliente($id_cliente)) {
        header('Location: clientes.php?status=sucesso_exclusao'); // Opcional: para exibir mensagem de status
        exit();
    } else {
        header('Location: clientes.php?status=erro_exclusao'); // Opcional
        exit();
    }
} else {
    header('Location: clientes.php?status=erro_id_invalido'); // Opcional
    exit();
}
?>