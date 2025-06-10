<?php
// VIEW/editar_categoria.php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

require_once '../DAL/CategoriaDAL.php';

$categoriaDAL = new CategoriaDAL();

$id_categoria = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_categoria <= 0) {
    header('Location: categorias.php');
    exit();
}

$mensagem_sucesso = '';
$mensagem_erro = '';
$erros_validacao = [];
$categoria = null; // Variável para armazenar os dados da categoria a ser editada

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $id_categoria = intval($_POST['id_categoria'] ?? $id_categoria); // Garante que o ID é do POST ou GET inicial

    // Validação
    if (empty($nome)) {
        $erros_validacao[] = 'O nome da categoria é obrigatório.';
    }

    if (empty($erros_validacao)) {
        if ($categoriaDAL->atualizarCategoria($id_categoria, $nome, $descricao)) {
            $mensagem_sucesso = 'Categoria atualizada com sucesso!';
            $categoria = $categoriaDAL->buscarCategoriaPorId($id_categoria); // Recarrega dados
        } else {
            $mensagem_erro = 'Erro ao atualizar categoria. Tente novamente.';
        }
    } else {
        $mensagem_erro = 'Por favor, corrija os erros no formulário.';
        // Para exibir os valores inválidos após erro, mantém os valores do POST
        $categoria = [
            'id' => $id_categoria,
            'nome' => $nome,
            'descricao' => $descricao
        ];
    }
} else {
    // Se não for POST, busca os dados da categoria para preencher o formulário
    $categoria = $categoriaDAL->buscarCategoriaPorId($id_categoria);

    if (!$categoria) {
        header('Location: categorias.php');
        exit();
    }
}

$nome_usuario = $_SESSION['usuario_logado']['nome'];
$cargo_usuario = $_SESSION['usuario_logado']['cargo'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria - Padaria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; }
        main { flex: 1 0 auto; padding-top: 30px; }
        .brand-logo { margin-left: 20px; }
    </style>
</head>
<body>
    <header>
        <nav class="light-blue darken-4">
            <div class="nav-wrapper">
                <a href="dashboard.php" class="brand-logo">Padaria</a>
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <li>Bem-vindo, <?php echo htmlspecialchars($nome_usuario); ?>! (<?php echo htmlspecialchars($cargo_usuario); ?>)</li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php"><i class="material-icons left">exit_to_app</i>Sair</a></li>
                </ul>
            </div>
        </nav>
        <ul class="sidenav" id="mobile-demo">
            <li><a href="dashboard.php"><i class="material-icons">dashboard</i>Dashboard</a></li>
            <li><div class="divider"></div></li>
            <li><a class="subheader">Gerenciamento</a></li>
            <li><a href="produtos.php"><i class="material-icons">local_bakery</i>Produtos</a></li>
            <li><a href="clientes.php"><i class="material-icons">people</i>Clientes</a></li>
            <li><a href="funcionarios.php"><i class="material-icons">badge</i>Funcionários</a></li>
            <li><a href="categorias.php"><i class="material-icons">category</i>Categorias</a></li>
            <li><div class="divider"></div></li>
            <li><a href="logout.php"><i class="material-icons">exit_to_app</i>Sair</a></li>
        </ul>
    </header>

    <main>
        <div class="container">
            <h3 class="light">Editar Categoria</h3>
            <div class="row">
                <div class="col s12 m10 offset-m1 l8 offset-l2">
                    <?php if ($mensagem_sucesso): ?>
                        <div class="card-panel green lighten-1 white-text">
                            <?php echo $mensagem_sucesso; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($mensagem_erro): ?>
                        <div class="card-panel red lighten-1 white-text">
                            <?php echo $mensagem_erro; ?>
                            <?php if (!empty($erros_validacao)): ?>
                                <ul>
                                    <?php foreach ($erros_validacao as $erro): ?>
                                        <li><i class="material-icons tiny">error_outline</i> <?php echo htmlspecialchars($erro); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="card-panel z-depth-2">
                        <form action="editar_categoria.php?id=<?php echo htmlspecialchars($id_categoria); ?>" method="POST">
                            <input type="hidden" name="id_categoria" value="<?php echo htmlspecialchars($categoria['id'] ?? ''); ?>">

                            <div class="input-field">
                                <i class="material-icons prefix">label_outline</i>
                                <input type="text" id="nome" name="nome" class="validate" required value="<?php echo htmlspecialchars($categoria['nome'] ?? ''); ?>">
                                <label for="nome" class="active">Nome da Categoria</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">info_outline</i>
                                <textarea id="descricao" name="descricao" class="materialize-textarea"><?php echo htmlspecialchars($categoria['descricao'] ?? ''); ?></textarea>
                                <label for="descricao" class="active">Descrição</label>
                            </div>

                            <div class="row">
                                <div class="col s12 m6">
                                    <button class="btn waves-effect waves-light orange darken-2" type="submit" name="action">
                                        <i class="material-icons left">update</i>Atualizar Categoria
                                    </button>
                                </div>
                                <div class="col s12 m6">
                                    <a href="categorias.php" class="btn waves-effect waves-light grey darken-1">
                                        <i class="material-icons left">arrow_back</i>Voltar às Categorias
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="page-footer light-blue darken-4">
        <div class="footer-copyright">
            <div class="container">
                © 2025 Padaria
                <a class="grey-text text-lighten-4 right" href="#!">Mais Links</a>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            M.AutoInit();
            var elems = document.querySelectorAll('.sidenav');
            M.Sidenav.init(elems);
            M.updateTextFields();
        });
    </script>
</body>
</html>