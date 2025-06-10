<?php
// VIEW/cadastrar_funcionario.php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

require_once '../DAL/FuncionarioDAL.php';

$funcionarioDAL = new FuncionarioDAL();

$mensagem_sucesso = '';
$mensagem_erro = '';
$erros_validacao = [];

$nome = '';
$email = '';
$cargo = '';
$senha = '';
$confirma_senha = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cargo = trim($_POST['cargo'] ?? '');
    $senha = $_POST['senha'] ?? ''; // Senha não é trimada por segurança (pode ter espaços intencionais)
    $confirma_senha = $_POST['confirma_senha'] ?? '';

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
    if (empty($senha)) {
        $erros_validacao[] = 'A senha é obrigatória.';
    } elseif (strlen($senha) < 6) {
        $erros_validacao[] = 'A senha deve ter no mínimo 6 caracteres.';
    }
    if ($senha !== $confirma_senha) {
        $erros_validacao[] = 'A senha e a confirmação de senha não coincidem.';
    }

    if (empty($erros_validacao)) {
        if ($funcionarioDAL->inserirFuncionario($nome, $email, $senha, $cargo)) {
            $mensagem_sucesso = 'Funcionário cadastrado com sucesso!';
            // Limpa os campos do formulário após o sucesso, exceto senha
            $nome = $email = $cargo = $senha = $confirma_senha = '';
        } else {
            $mensagem_erro = 'Erro ao cadastrar funcionário. Verifique se o email já está em uso.';
        }
    } else {
        $mensagem_erro = 'Por favor, corrija os erros no formulário.';
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
    <title>Cadastrar Funcionário - Padaria</title>
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
            <h3 class="light">Cadastrar Novo Funcionário</h3>
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
                        <form action="cadastrar_funcionario.php" method="POST">
                            <div class="input-field">
                                <i class="material-icons prefix">person_outline</i>
                                <input type="text" id="nome" name="nome" class="validate" required value="<?php echo htmlspecialchars($nome); ?>">
                                <label for="nome">Nome do Funcionário</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">email</i>
                                <input type="email" id="email" name="email" class="validate" required value="<?php echo htmlspecialchars($email); ?>">
                                <label for="email">Email</label>
                                <span class="helper-text" data-error="Email inválido" data-success="ok"></span>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">work_outline</i>
                                <input type="text" id="cargo" name="cargo" class="validate" required value="<?php echo htmlspecialchars($cargo); ?>">
                                <label for="cargo">Cargo</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">lock_outline</i>
                                <input type="password" id="senha" name="senha" class="validate" required>
                                <label for="senha">Senha</label>
                                <span class="helper-text" data-error="Senha deve ter no mínimo 6 caracteres" data-success="ok"></span>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">lock</i>
                                <input type="password" id="confirma_senha" name="confirma_senha" class="validate" required>
                                <label for="confirma_senha">Confirmar Senha</label>
                                <span class="helper-text" data-error="Senhas não coincidem" data-success="ok"></span>
                            </div>

                            <div class="row">
                                <div class="col s12 m6">
                                    <button class="btn waves-effect waves-light blue darken-2" type="submit" name="action">
                                        <i class="material-icons left">save</i>Salvar Funcionário
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