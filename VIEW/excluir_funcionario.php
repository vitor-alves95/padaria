<?php
// VIEW/excluir_funcionario.php
session_start();

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

require_once '../DAL/FuncionarioDAL.php';

$id_funcionario = isset($_GET['id']) ? intval($_GET['id']) : 0;

$funcionarioDAL = new FuncionarioDAL();

if ($id_funcionario > 0) {
    // Adicione uma lógica de segurança aqui se necessário (ex: não permitir excluir o próprio usuário logado)
    // if ($id_funcionario == $_SESSION['usuario_logado']['id']) {
    //     header('Location: funcionarios.php?status=erro_excluir_proprio');
    //     exit();
    // }

    if ($funcionarioDAL->excluirFuncionario($id_funcionario)) {
        header('Location: funcionarios.php?status=sucesso_exclusao'); // Opcional: para exibir mensagem de status
        exit();
    } else {
        header('Location: funcionarios.php?status=erro_exclusao'); // Opcional
        exit();
    }
} else {
    header('Location: funcionarios.php?status=erro_id_invalido'); // Opcional
    exit();
}
?>