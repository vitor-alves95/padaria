<?php
// VIEW/produtos.php
session_start();
// Redireciona para o login se não houver sessão ativa
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

// Inclui o arquivo da camada DAL para Produto
require_once '../DAL/ProdutoDAL.php';

// Instancia a classe ProdutoDAL
$produtoDAL = new ProdutoDAL();

// Chama o método para listar os produtos
$stmt_produtos = $produtoDAL->listarProdutos();

// Variáveis da sessão para o cabeçalho
$nome_usuario = $_SESSION['usuario_logado']['nome'];
$cargo_usuario = $_SESSION['usuario_logado']['cargo'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Padaria</title>
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
            background-color: #f2f2f2; /* Cor de fundo para o cabeçalho da tabela */
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
            <h3 class="light">Listagem de Produtos</h3>
            <div class="row">
                <div class="col s12">
                    <a href="cadastrar_produto.php" class="btn waves-effect waves-light blue darken-2" style="margin-bottom: 20px;">
                        <i class="material-icons left">add</i>Novo Produto
                    </a>
                    <?php if ($stmt_produtos->rowCount() > 0): // Verifica se há produtos para exibir ?>
                        <table class="striped highlight responsive-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Preço</th>
                                    <th>Estoque</th>
                                    <th>Categoria</th>
                                    <th>Data Cadastro</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($produto = $stmt_produtos->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($produto['id']); ?></td>
                                        <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                                        <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($produto['estoque']); ?></td>
                                        <td><?php echo htmlspecialchars($produto['categoria_nome'] ?? 'N/A'); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($produto['data_cadastro'])); ?></td>
                                        <td>
                                            <a href="editar_produto.php?id=<?php echo htmlspecialchars($produto['id']); ?>" class="btn-floating waves-effect waves-light orange tooltipped" data-position="top" data-tooltip="Editar">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="excluir_produto.php?id=<?php echo htmlspecialchars($produto['id']); ?>" class="btn-floating waves-effect waves-light red modal-trigger tooltipped" data-position="top" data-tooltip="Excluir" data-target="modalExcluir<?php echo htmlspecialchars($produto['id']); ?>">
                                                <i class="material-icons">delete</i>
                                            </a>
                                            <div id="modalExcluir<?php echo htmlspecialchars($produto['id']); ?>" class="modal">
                                                <div class="modal-content">
                                                    <h4>Confirmar Exclusão</h4>
                                                    <p>Tem certeza que deseja excluir o produto "<?php echo htmlspecialchars($produto['nome']); ?>"?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancelar</a>
                                                    <a href="excluir_produto.php?id=<?php echo htmlspecialchars($produto['id']); ?>" class="waves-effect waves-red btn-flat red-text">Excluir</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="flow-text center-align">Nenhum produto cadastrado ainda. <a href="cadastrar_produto.php">Adicione um novo!</a></p>
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