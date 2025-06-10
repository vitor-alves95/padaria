<?php
// VIEW/excluir_categoria.php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

require_once '../DAL/CategoriaDAL.php';

$id_categoria = isset($_GET['id']) ? intval($_GET['id']) : 0;

$categoriaDAL = new CategoriaDAL();

if ($id_categoria > 0) {
    if ($categoriaDAL->excluirCategoria($id_categoria)) {
        header('Location: categorias.php?status=sucesso_exclusao'); // Opcional: para exibir mensagem de status
        exit();
    } else {
        header('Location: categorias.php?status=erro_exclusao'); // Opcional
        exit();
    }
} else {
    header('Location: categorias.php?status=erro_id_invalido'); // Opcional
    exit();
}
?>