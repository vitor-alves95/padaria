<?php
// DAL/ClienteDAL.php

require_once 'Conexao.php'; // Inclui o arquivo de conexão

class ClienteDAL {
    private $conn;
    private $table_name = "clientes";

    public function __construct() {
        $database = new Conexao();
        $this->conn = $database->conectar();
    }

    // Método para listar todos os clientes
    public function listarClientes() {
        $query = "SELECT id, nome, email, telefone, endereco, cpf, data_cadastro FROM " . $this->table_name . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Retorna o objeto PDOStatement
    }

    // Método para inserir um novo cliente
    public function inserirCliente($nome, $email, $telefone, $endereco, $cpf) {
        $query = "INSERT INTO " . $this->table_name . " (nome, email, telefone, endereco, cpf) VALUES (:nome, :email, :telefone, :endereco, :cpf)";
        $stmt = $this->conn->prepare($query);

        // Limpa e vincula os parâmetros para evitar SQL Injection
        $nome = htmlspecialchars(strip_tags($nome));
        $email = htmlspecialchars(strip_tags($email));
        $telefone = htmlspecialchars(strip_tags($telefone));
        $endereco = htmlspecialchars(strip_tags($endereco));
        $cpf = htmlspecialchars(strip_tags($cpf)); // CPF deve ser armazenado como string

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':cpf', $cpf);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para buscar um cliente pelo ID
    public function buscarClientePorId($id) {
        $query = "SELECT id, nome, email, telefone, endereco, cpf, data_cadastro FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $cliente = $stmt->fetch(PDO::FETCH_ASSOC); // Retorna os dados como um array associativo
        return $cliente;
    }

    // Método para atualizar um cliente existente
    public function atualizarCliente($id, $nome, $email, $telefone, $endereco, $cpf) {
        $query = "UPDATE " . $this->table_name . "
                  SET nome = :nome,
                      email = :email,
                      telefone = :telefone,
                      endereco = :endereco,
                      cpf = :cpf
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Limpa e vincula os parâmetros
        $nome = htmlspecialchars(strip_tags($nome));
        $email = htmlspecialchars(strip_tags($email));
        $telefone = htmlspecialchars(strip_tags($telefone));
        $endereco = htmlspecialchars(strip_tags($endereco));
        $cpf = htmlspecialchars(strip_tags($cpf));

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para excluir um cliente
    public function excluirCliente($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>