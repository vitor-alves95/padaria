<?php
// VIEW/login.php
session_start(); // Inicia a sessão PHP

// Inclui o arquivo da camada DAL para Funcionario
require_once '../DAL/FuncionarioDAL.php';

$erro_login = false; // Variável para controlar a exibição de mensagem de erro

// Verifica se o formulário foi submetido (método POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['password'] ?? '';

    // Instancia a classe FuncionarioDAL
    $funcionarioDAL = new FuncionarioDAL();

    // Tenta autenticar o usuário
    $funcionario_autenticado = $funcionarioDAL->autenticar($email, $senha);

    if ($funcionario_autenticado) {
        // Se a autenticação for bem-sucedida, armazena os dados do funcionário na sessão
        $_SESSION['usuario_logado'] = [
            'id' => $funcionario_autenticado['id'],
            'nome' => $funcionario_autenticado['nome'],
            'sobrenome' => $funcionario_autenticado['sobrenome'],
            'email' => $funcionario_autenticado['email'],
            'cargo' => $funcionario_autenticado['cargo']
        ];
        // Redireciona para o dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Se a autenticação falhar
        $erro_login = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Padaria</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-color: #f5f5f5;
        }
        main {
            flex: 1 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-panel {
            padding: 20px;
            margin-top: 50px;
            width: 100%;
            max-width: 400px;
        }
        .btn {
            width: 100%;
        }
    </style>
</head>
<body>
    <main>
        <div class="container">
            <div class="row">
                <div class="col s12 m8 offset-m2 l6 offset-l3">
                    <div class="card-panel z-depth-3">
                        <h4 class="center-align light">Login da Padaria</h4>
                        <form action="login.php" method="POST"> <div class="input-field">
                                <i class="material-icons prefix">email</i>
                                <input type="email" id="email" name="email" class="validate" required>
                                <label for="email">E-mail</label>
                                <span class="helper-text" data-error="E-mail inválido" data-success="Ok"></span>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">lock</i>
                                <input type="password" id="password" name="password" class="validate" required>
                                <label for="password">Senha</label>
                            </div>
                            <button class="btn waves-effect waves-light blue darken-2" type="submit" name="action">Entrar
                                <i class="material-icons right">send</i>
                            </button>
                            <?php
                                // Exibe a mensagem de erro se a autenticação falhar
                                if ($erro_login) {
                                    echo "<p class='red-text center-align' style='margin-top: 15px;'>E-mail ou senha incorretos!</p>";
                                }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            M.AutoInit(); // Inicializa componentes Materialize (input fields, etc.)
        });
    </script>
</body>
</html>