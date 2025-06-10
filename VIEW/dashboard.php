<?php
// VIEW/dashboard.php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

// Variáveis da sessão
$nome_usuario = $_SESSION['usuario_logado']['nome'];
$cargo_usuario = $_SESSION['usuario_logado']['cargo'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Padaria</title>
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
        }
        .brand-logo {
            margin-left: 20px;
        }
        .sidebar-nav {
            padding-top: 20px;
        }
        .sidebar-nav li a {
            padding: 15px;
            color: #424242;
            font-weight: 500;
        }
        .sidebar-nav li a:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <header>
        <nav class="light-blue darken-4">
            <div class="nav-wrapper">
                <a href="#" class="brand-logo">Padaria</a>
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    <li>Bem-vindo, <?php echo htmlspecialchars($nome_usuario); ?>! (<?php echo htmlspecialchars($cargo_usuario); ?>)</li>
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
        <div class="container" style="padding-top: 30px;">
            <h3 class="light">Dashboard</h3>
            <div class="row">
                <div class="col s12 m6 l4">
                    <div class="card blue lighten-1">
                        <div class="card-content white-text">
                            <span class="card-title">Produtos</span>
                            <p>Gerencie seus produtos.</p>
                        </div>
                        <div class="card-action">
                            <a href="produtos.php" class="white-text">Ver Produtos</a>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    <div class="card green lighten-1">
                        <div class="card-content white-text">
                            <span class="card-title">Clientes</span>
                            <p>Gerencie seus clientes.</p>
                        </div>
                        <div class="card-action">
                            <a href="clientes.php" class="white-text">Ver Clientes</a>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    <div class="card orange lighten-1">
                        <div class="card-content white-text">
                            <span class="card-title">Funcionários</span>
                            <p>Gerencie sua equipe.</p>
                        </div>
                        <div class="card-action">
                            <a href="funcionarios.php" class="white-text">Ver Funcionários</a>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l4">
                    <div class="card purple lighten-1">
                        <div class="card-content white-text">
                            <span class="card-title">Categorias</span>
                            <p>Organize seus produtos.</p>
                        </div>
                        <div class="card-action">
                            <a href="categorias.php" class="white-text">Ver Categorias</a>
                        </div>
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
        });
    </script>
</body>
</html>