<?php
// VIEW/editar_produto.php
session_start();
// Redireciona para o login se não houver sessão ativa
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

// Inclui as classes DAL necessárias
require_once '../DAL/ProdutoDAL.php';
require_once '../DAL/CategoriaDAL.php';

// Instancia as DALs
$produtoDAL = new ProdutoDAL();
$categoriaDAL = new CategoriaDAL();

// Obtém o ID do produto da URL (via GET)
$id_produto = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Se não houver ID válido, redireciona de volta para a lista de produtos
if ($id_produto <= 0) {
    header('Location: produtos.php');
    exit();
}

$mensagem_sucesso = '';
$mensagem_erro = '';
$erros_validacao = [];
$produto = null; // Variável para armazenar os dados do produto a ser editado

// Carrega as categorias para o select
$stmt_categorias = $categoriaDAL->listarCategorias();
$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

// Processa o formulário quando ele é submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Coleta os dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = trim($_POST['preco'] ?? '');
    $estoque = trim($_POST['estoque'] ?? '');
    $id_categoria = trim($_POST['id_categoria'] ?? '');
    $id_produto = intval($_POST['id_produto'] ?? $id_produto); // Garante que o ID é do POST ou GET inicial

    // 2. Validação de Campos (mesma lógica do cadastrar)
    if (empty($nome)) {
        $erros_validacao[] = 'O nome do produto é obrigatório.';
    }
    if (empty($preco)) {
        $erros_validacao[] = 'O preço do produto é obrigatório.';
    } elseif (!is_numeric(str_replace(',', '.', $preco)) || str_replace(',', '.', $preco) <= 0) {
        $erros_validacao[] = 'O preço deve ser um número válido e maior que zero.';
    }
    if (empty($estoque)) {
        $erros_validacao[] = 'O estoque é obrigatório.';
    } elseif (!filter_var($estoque, FILTER_VALIDATE_INT) || $estoque < 0) {
        $erros_validacao[] = 'O estoque deve ser um número inteiro e não negativo.';
    }

    // 3. Se não houver erros de validação, tenta atualizar no banco de dados
    if (empty($erros_validacao)) {
        $preco_formatado = str_replace(',', '.', $preco);

        if ($produtoDAL->atualizarProduto($id_produto, $nome, $descricao, $preco_formatado, $estoque, $id_categoria)) {
            $mensagem_sucesso = 'Produto atualizado com sucesso!';
            // Recarrega os dados do produto para refletir as alterações no formulário
            $produto = $produtoDAL->buscarProdutoPorId($id_produto);
        } else {
            $mensagem_erro = 'Erro ao atualizar produto. Tente novamente.';
        }
    } else {
        $mensagem_erro = 'Por favor, corrija os erros no formulário.';
        // Para exibir os valores inválidos após erro, mantém os valores do POST
        $produto = [
            'id' => $id_produto,
            'nome' => $nome,
            'descricao' => $descricao,
            'preco' => $preco, // Manter o formato original para exibição
            'estoque' => $estoque,
            'id_categoria' => $id_categoria
        ];
    }
} else {
    // Se o formulário NÃO foi submetido (primeira vez acessando a página)
    // Busca os dados do produto pelo ID para preencher o formulário
    $produto = $produtoDAL->buscarProdutoPorId($id_produto);

    // Se o produto não for encontrado, redireciona para a lista
    if (!$produto) {
        header('Location: produtos.php');
        exit();
    }
}

// Variáveis da sessão para o cabeçalho
$nome_usuario = $_SESSION['usuario_logado']['nome'];
$cargo_usuario = $_SESSION['usuario_logado']['cargo'];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - Padaria</title>
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
            <li>
                <div class="divider"></div>
            </li>
            <li><a class="subheader">Gerenciamento</a></li>
            <li><a href="produtos.php"><i class="material-icons">local_bakery</i>Produtos</a></li>
            <li><a href="clientes.php"><i class="material-icons">people</i>Clientes</a></li>
            <li><a href="funcionarios.php"><i class="material-icons">badge</i>Funcionários</a></li>
            <li><a href="categorias.php"><i class="material-icons">category</i>Categorias</a></li>
            <li>
                <div class="divider"></div>
            </li>
            <li><a href="logout.php"><i class="material-icons">exit_to_app</i>Sair</a></li>
        </ul>
    </header>

    <main>
        <div class="container">
            <h3 class="light">Editar Produto</h3>
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
                        <form action="editar_produto.php?id=<?php echo htmlspecialchars($id_produto); ?>" method="POST">
                            <input type="hidden" name="id_produto" value="<?php echo htmlspecialchars($produto['id'] ?? ''); ?>">

                            <div class="input-field">
                                <i class="material-icons prefix">bakery_dining</i>
                                <input type="text" id="nome" name="nome" class="validate" required value="<?php echo htmlspecialchars($produto['nome'] ?? ''); ?>">
                                <label for="nome" class="active">Nome do Produto</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">description</i>
                                <textarea id="descricao" name="descricao" class="materialize-textarea"><?php echo htmlspecialchars($produto['descricao'] ?? ''); ?></textarea>
                                <label for="descricao" class="active">Descrição</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">attach_money</i>
                                <input type="text" id="preco" name="preco" class="validate" required value="<?php echo htmlspecialchars(str_replace('.', ',', $produto['preco'] ?? '')); ?>" pattern="^\d+(\,\d{1,2})?$" title="Use vírgula para centavos (ex: 10,50)">
                                <label for="preco" class="active">Preço (R$)</label>
                                <span class="helper-text" data-error="Preço inválido (ex: 10,50)" data-success=""></span>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">warehouse</i>
                                <input type="number" id="estoque" name="estoque" class="validate" required min="0" value="<?php echo htmlspecialchars($produto['estoque'] ?? ''); ?>">
                                <label for="estoque" class="active">Estoque</label>
                                <span class="helper-text" data-error="Estoque deve ser um número inteiro não negativo" data-success=""></span>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">category</i>
                                <select id="id_categoria" name="id_categoria">
                                    <option value="" disabled <?php echo (empty($produto['id_categoria'])) ? 'selected' : ''; ?>>Selecione uma Categoria</option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?php echo htmlspecialchars($categoria['id']); ?>" <?php echo (isset($produto['id_categoria']) && $produto['id_categoria'] == $categoria['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($categoria['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="id_categoria">Categoria</label>
                            </div>

                            <div class="row">
                                <div class="col s12 m6">
                                    <button class="btn waves-effect waves-light orange darken-2" type="submit" name="action">
                                        <i class="material-icons left">update</i>Atualizar Produto
                                    </button>
                                </div>
                                <div class="col s12 m6">
                                    <a href="produtos.php" class="btn waves-effect waves-light grey darken-1">
                                        <i class="material-icons left">arrow_back</i>Voltar aos Produtos
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
            var elemsSelect = document.querySelectorAll('select');
            M.FormSelect.init(elemsSelect); // Inicializa o select do Materialize
            M.updateTextFields(); // Atualiza as labels dos campos de input preenchidos
        });
    </script>
</body>

</html>