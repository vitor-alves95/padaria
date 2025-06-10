<?php
// VIEW/editar_funcionario.php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

require_once '../DAL/FuncionarioDAL.php';

$funcionarioDAL = new FuncionarioDAL();

$id_funcionario = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_funcionario <= 0) {
    header('Location: funcionarios.php');
    exit();
}

$mensagem_sucesso = '';
$mensagem_erro = '';
$erros_validacao = [];
$funcionario = null; // Variável para armazenar os dados do funcionário a ser editado

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cargo = trim($_POST['cargo'] ?? '');
    $id_funcionario = intval($_POST['id_funcionario'] ?? $id_funcionario); // Garante que o ID é do POST ou GET inicial

    // Validação
    if (empty($nome)) {
        $erros_validacao[] = 'O nome do funcionário é obrigatório.';
    }
    if (empty($email)) {
        $erros_validacao[] = 'O email do funcionário é obrigatório.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros_validacao[] = 'Formato de email inválido.';
    }
    if (empty($cargo)) {
        $erros_validacao[] = 'O cargo do funcionário é obrigatório.';
    }

    if (empty($erros_validacao)) {
        if ($funcionarioDAL->atualizarFuncionario($id_funcionario, $nome, $email, $cargo)) {
            $mensagem_sucesso = 'Funcionário atualizado com sucesso!';
            $funcionario = $funcionarioDAL->buscarFuncionarioPorId($id_funcionario); // Recarrega os dados
        } else {
            $mensagem_erro = 'Erro ao atualizar funcionário. Verifique se o email já está em uso.';
        }
    } else {
        $mensagem_erro = 'Por favor, corrija os erros no formulário.';
        // Para exibir os valores inválidos após erro, mantém os valores do POST
        $funcionario = [
            'id' => $id_funcionario,
            'nome' => $nome,
            'email' => $email,
            'cargo' => $cargo
        ];
    }
} else {
    // Se não for POST, busca os dados do funcionário pelo ID para preencher o formulário
    $funcionario = $funcionarioDAL->buscarFuncionarioPorId($id_funcionario);

    if (!$funcionario) {
        header('Location: funcionarios.php');
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
    <title>Editar Funcionário - Padaria</title>
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
            <h3 class="light">Editar Funcionário</h3>
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
                        <form action="editar_funcionario.php?id=<?php echo htmlspecialchars($id_funcionario); ?>" method="POST">
                            <input type="hidden" name="id_funcionario" value="<?php echo htmlspecialchars($funcionario['id'] ?? ''); ?>">

                            <div class="input-field">
                                <i class="material-icons prefix">person_outline</i>
                                <input type="text" id="nome" name="nome" class="validate" required value="<?php echo htmlspecialchars($funcionario['nome'] ?? ''); ?>">
                                <label for="nome" class="active">Nome do Funcionário</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">email</i>
                                <input type="email" id="email" name="email" class="validate" required value="<?php echo htmlspecialchars($funcionario['email'] ?? ''); ?>">
                                <label for="email" class="active">Email</label>
                                <span class="helper-text" data-error="Email inválido" data-success="ok"></span>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">work_outline</i>
                                <input type="text" id="cargo" name="cargo" class="validate" required value="<?php echo htmlspecialchars($funcionario['cargo'] ?? ''); ?>">
                                <label for="cargo" class="active">Cargo</label>
                            </div>

                            <div class="row">
                                <div class="col s12 m6">
                                    <button class="btn waves-effect waves-light orange darken-2" type="submit" name="action">
                                        <i class="material-icons left">update</i>Atualizar Funcionário
                                    </button>
                                </div>
                                <div class="col s12 m6">
                                    <a href="funcionarios.php" class="btn waves-effect waves-light grey darken-1">
                                        <i class="material-icons left">arrow_back</i>Voltar aos Funcionários
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