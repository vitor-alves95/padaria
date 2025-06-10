<?php
// VIEW/editar_cliente.php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

require_once '../DAL/ClienteDAL.php';

$clienteDAL = new ClienteDAL();

$id_cliente = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_cliente <= 0) {
    header('Location: clientes.php');
    exit();
}

$mensagem_sucesso = '';
$mensagem_erro = '';
$erros_validacao = [];
$cliente = null; // Variável para armazenar os dados do cliente a ser editado

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $id_cliente = intval($_POST['id_cliente'] ?? $id_cliente); // Garante que o ID é do POST ou GET inicial

    // Validação (mesma lógica do cadastrar)
    if (empty($nome)) {
        $erros_validacao[] = 'O nome do cliente é obrigatório.';
    }
    if (empty($email)) {
        $erros_validacao[] = 'O email do cliente é obrigatório.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros_validacao[] = 'Formato de email inválido.';
    }
    if (empty($telefone)) {
        $erros_validacao[] = 'O telefone do cliente é obrigatório.';
    }
    if (empty($cpf)) {
        $erros_validacao[] = 'O CPF do cliente é obrigatório.';
    } elseif (!preg_match('/^\d{3}\.?\d{3}\.?\d{3}\-?\d{2}$/', $cpf)) {
        $erros_validacao[] = 'Formato de CPF inválido. Use XXX.XXX.XXX-XX ou apenas números.';
    }

    if (empty($erros_validacao)) {
        $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);

        if ($clienteDAL->atualizarCliente($id_cliente, $nome, $email, $telefone, $endereco, $cpf_limpo)) {
            $mensagem_sucesso = 'Cliente atualizado com sucesso!';
            $cliente = $clienteDAL->buscarClientePorId($id_cliente); // Recarrega os dados para o formulário
        } else {
            $mensagem_erro = 'Erro ao atualizar cliente. Tente novamente.';
        }
    } else {
        $mensagem_erro = 'Por favor, corrija os erros no formulário.';
        // Para exibir os valores inválidos após erro, mantém os valores do POST
        $cliente = [
            'id' => $id_cliente,
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone,
            'endereco' => $endereco,
            'cpf' => $cpf
        ];
    }
} else {
    // Se não for POST, busca os dados do cliente pelo ID para preencher o formulário
    $cliente = $clienteDAL->buscarClientePorId($id_cliente);

    if (!$cliente) {
        header('Location: clientes.php');
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
    <title>Editar Cliente - Padaria</title>
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
            <h3 class="light">Editar Cliente</h3>
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
                        <form action="editar_cliente.php?id=<?php echo htmlspecialchars($id_cliente); ?>" method="POST">
                            <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($cliente['id'] ?? ''); ?>">

                            <div class="input-field">
                                <i class="material-icons prefix">person_outline</i>
                                <input type="text" id="nome" name="nome" class="validate" required value="<?php echo htmlspecialchars($cliente['nome'] ?? ''); ?>">
                                <label for="nome" class="active">Nome do Cliente</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">email</i>
                                <input type="email" id="email" name="email" class="validate" required value="<?php echo htmlspecialchars($cliente['email'] ?? ''); ?>">
                                <label for="email" class="active">Email</label>
                                <span class="helper-text" data-error="Email inválido" data-success="ok"></span>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">phone</i>
                                <input type="text" id="telefone" name="telefone" class="validate" required value="<?php echo htmlspecialchars($cliente['telefone'] ?? ''); ?>" pattern="^\(?\d{2}\)?\s?\d{4,5}\-?\d{4}$" title="Formato: (XX) XXXX-XXXX ou XXXXXXXXX">
                                <label for="telefone" class="active">Telefone</label>
                                <span class="helper-text" data-error="Telefone inválido" data-success="ok"></span>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">home</i>
                                <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($cliente['endereco'] ?? ''); ?>">
                                <label for="endereco" class="active">Endereço</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">credit_card</i>
                                <input type="text" id="cpf" name="cpf" class="validate" required value="<?php echo htmlspecialchars($cliente['cpf'] ?? ''); ?>" pattern="\d{3}\.?\d{3}\.?\d{3}\-?\d{2}" title="Formato: XXX.XXX.XXX-XX ou apenas números">
                                <label for="cpf" class="active">CPF</label>
                                <span class="helper-text" data-error="CPF inválido" data-success="ok"></span>
                            </div>

                            <div class="row">
                                <div class="col s12 m6">
                                    <button class="btn waves-effect waves-light orange darken-2" type="submit" name="action">
                                        <i class="material-icons left">update</i>Atualizar Cliente
                                    </button>
                                </div>
                                <div class="col s12 m6">
                                    <a href="clientes.php" class="btn waves-effect waves-light grey darken-1">
                                        <i class="material-icons left">arrow_back</i>Voltar aos Clientes
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