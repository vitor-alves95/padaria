<?php
// VIEW/categorias.php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

require_once '../DAL/CategoriaDAL.php';

$categoriaDAL = new CategoriaDAL();
$stmt_categorias = $categoriaDAL->listarCategorias();

$nome_usuario = $_SESSION['usuario_logado']['nome'];
$cargo_usuario = $_SESSION['usuario_logado']['cargo'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Padaria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
            padding-top: 30px;
        }
        .brand-logo {
            margin-left: 20px;
        }
        table.highlight thead th {
            background-color: #f2f2f2;
        }
        /* Estilo para garantir que os botões flutuantes na tabela fiquem alinhados */
        td .btn-floating {
            margin: 0 5px; /* Adiciona um pequeno espaçamento entre os botões */
        }
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
            <h3 class="light">Listagem de Categorias</h3>
            <div class="row">
                <div class="col s12">
                    <a href="cadastrar_categoria.php" class="btn waves-effect waves-light blue darken-2" style="margin-bottom: 20px;">
                        <i class="material-icons left">add</i>Nova Categoria
                    </a>
                    <?php if ($stmt_categorias->rowCount() > 0): ?>
                        <table class="striped highlight responsive-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($categoria = $stmt_categorias->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($categoria['id']); ?></td>
                                        <td><?php echo htmlspecialchars($categoria['nome'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($categoria['descricao'] ?? ''); ?></td>
                                        <td>
                                            <a href="editar_categoria.php?id=<?php echo htmlspecialchars($categoria['id']); ?>" class="btn-floating waves-effect waves-light orange tooltipped" data-position="top" data-tooltip="Editar">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="excluir_categoria.php?id=<?php echo htmlspecialchars($categoria['id']); ?>" class="btn-floating waves-effect waves-light red modal-trigger tooltipped" data-position="top" data-tooltip="Excluir" data-target="modalExcluir<?php echo htmlspecialchars($categoria['id']); ?>">
                                                <i class="material-icons">delete</i>
                                            </a>
                                            <div id="modalExcluir<?php echo htmlspecialchars($categoria['id']); ?>" class="modal">
                                                <div class="modal-content">
                                                    <h4>Confirmar Exclusão</h4>
                                                    <p>Tem certeza que deseja excluir a categoria "<?php echo htmlspecialchars($categoria['nome'] ?? ''); ?>"?</p>
                                                    <p>Isso fará com que os produtos associados a esta categoria fiquem sem categoria.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
                                                    <a href="excluir_categoria.php?id=<?php echo htmlspecialchars($categoria['id']); ?>" class="waves-effect waves-red btn-flat red-text">Excluir</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="flow-text center-align">Nenhuma categoria cadastrada ainda. <a href="cadastrar_categoria.php">Adicione uma nova!</a></p>
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
            M.AutoInit(); // Inicializa todos os componentes Materialize automaticamente
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