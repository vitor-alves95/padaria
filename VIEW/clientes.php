<?php
// VIEW/clientes.php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

require_once '../DAL/ClienteDAL.php';

$clienteDAL = new ClienteDAL();
$stmt_clientes = $clienteDAL->listarClientes();

$nome_usuario = $_SESSION['usuario_logado']['nome'];
$cargo_usuario = $_SESSION['usuario_logado']['cargo'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Padaria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; }
        main { flex: 1 0 auto; padding-top: 30px; }
        .brand-logo { margin-left: 20px; }
        table.highlight thead th { background-color: #f2f2f2; }
        td .btn-floating { margin: 0 5px; }
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
            <h3 class="light">Listagem de Clientes</h3>
            <div class="row">
                <div class="col s12">
                    <a href="cadastrar_cliente.php" class="btn waves-effect waves-light blue darken-2" style="margin-bottom: 20px;">
                        <i class="material-icons left">person_add</i>Novo Cliente
                    </a>
                    <?php if ($stmt_clientes->rowCount() > 0): ?>
                        <table class="striped highlight responsive-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Endereço</th>
                                    <th>CPF</th>
                                    <th>Data Cadastro</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($cliente = $stmt_clientes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['nome'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['email'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['telefone'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['endereco'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['cpf'] ?? ''); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($cliente['data_cadastro'] ?? '')); ?></td>
                                        <td>
                                            <a href="editar_cliente.php?id=<?php echo htmlspecialchars($cliente['id']); ?>" class="btn-floating waves-effect waves-light orange tooltipped" data-position="top" data-tooltip="Editar">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="excluir_cliente.php?id=<?php echo htmlspecialchars($cliente['id']); ?>" class="btn-floating waves-effect waves-light red modal-trigger tooltipped" data-position="top" data-tooltip="Excluir" data-target="modalExcluir<?php echo htmlspecialchars($cliente['id']); ?>">
                                                <i class="material-icons">delete</i>
                                            </a>
                                            <div id="modalExcluir<?php echo htmlspecialchars($cliente['id']); ?>" class="modal">
                                                <div class="modal-content">
                                                    <h4>Confirmar Exclusão</h4>
                                                    <p>Tem certeza que deseja excluir o cliente "<?php echo htmlspecialchars($cliente['nome'] ?? ''); ?>"?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
                                                    <a href="excluir_cliente.php?id=<?php echo htmlspecialchars($cliente['id']); ?>" class="waves-effect waves-red btn-flat red-text">Excluir</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="flow-text center-align">Nenhum cliente cadastrado ainda. <a href="cadastrar_cliente.php">Adicione um novo!</a></p>
                    <?php endif; ?>
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
            var elemsModal = document.querySelectorAll('.modal');
            M.Modal.init(elemsModal);
            var elemsTooltip = document.querySelectorAll('.tooltipped');
            M.Tooltip.init(elemsTooltip);
        });
    </script>
</body>
</html>