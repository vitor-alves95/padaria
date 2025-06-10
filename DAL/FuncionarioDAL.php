<?php
// DAL/FuncionarioDAL.php

require_once 'Conexao.php'; // Inclui o arquivo de conexão

class FuncionarioDAL {
    private $conn;
    private $table_name = "funcionarios";

    public function __construct() {
        $database = new Conexao();
        $this->conn = $database->conectar();
    }

    // Método para listar todos os funcionários
    public function listarFuncionarios() {
        $query = "SELECT id, nome, email, cargo, data_contratacao FROM " . $this->table_name . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna o objeto PDOStatement
    }

    // Método para inserir um novo funcionário
    public function inserirFuncionario($nome, $email, $senha, $cargo) {
        // Criptografar a senha antes de inserir no banco de dados
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $query = "INSERT INTO " . $this->table_name . " (nome, email, senha, cargo) VALUES (:nome, :email, :senha, :cargo)";
        $stmt = $this->conn->prepare($query);

        // Limpa e vincula os parâmetros para evitar SQL Injection
        $nome = htmlspecialchars(strip_tags($nome));
        $email = htmlspecialchars(strip_tags($email));
        $cargo = htmlspecialchars(strip_tags($cargo));

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_hash); // Salva a senha criptografada
        $stmt->bindParam(':cargo', $cargo);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para buscar um funcionário pelo ID
    public function buscarFuncionarioPorId($id) {
        $query = "SELECT id, nome, email, cargo, data_contratacao FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC); // Retorna os dados como um array associativo
        return $funcionario;
    }

    // Método para atualizar um funcionário existente
    public function atualizarFuncionario($id, $nome, $email, $cargo) {
        $query = "UPDATE " . $this->table_name . "
                  SET nome = :nome,
                      email = :email,
                      cargo = :cargo
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Limpa e vincula os parâmetros
        $nome = htmlspecialchars(strip_tags($nome));
        $email = htmlspecialchars(strip_tags($email));
        $cargo = htmlspecialchars(strip_tags($cargo));

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cargo', $cargo);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para atualizar a senha de um funcionário (opcional e separado)
    public function atualizarSenhaFuncionario($id, $nova_senha) {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $query = "UPDATE " . $this->table_name . " SET senha = :senha WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para excluir um funcionário
    public function excluirFuncionario($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para autenticar um funcionário (login)
    public function autenticar($email, $senha) {
        $query = "SELECT id, nome, email, senha, cargo FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $email = htmlspecialchars(strip_tags($email)); // Limpar email

        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o funcionário foi encontrado, verificar a senha
        if ($funcionario && password_verify($senha, $funcionario['senha'])) {
            // Senha correta, remove a senha do array antes de retornar para segurança
            unset($funcionario['senha']);
            return $funcionario;
        }

        return false; // Autenticação falhou
    }
}
?>