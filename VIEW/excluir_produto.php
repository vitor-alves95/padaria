<?php
// VIEW/excluir_produto.php
session_start();

// Redireciona para o login se não houver sessão ativa
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

// Inclui a classe DAL necessária
require_once '../DAL/ProdutoDAL.php';

// Obtém o ID do produto da URL (via GET)
$id_produto = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Instancia a classe ProdutoDAL
$produtoDAL = new ProdutoDAL();

// Mensagem de status para redirecionamento
$status_mensagem = '';

// Verifica se um ID válido foi fornecido e tenta excluir
if ($id_produto > 0) {
    if ($produtoDAL->excluirProduto($id_produto)) {
        $status_mensagem = 'sucesso_exclusao';
    } else {
        $status_mensagem = 'erro_exclusao';
    }
} else {
    $status_mensagem = 'erro_id_invalido';
}

// Redireciona de volta para a página de listagem de produtos com uma mensagem de status
header('Location: produtos.php?status=' . $status_mensagem);
exit();
?>