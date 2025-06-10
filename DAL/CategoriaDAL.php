<?php
// DAL/CategoriaDAL.php

require_once 'Conexao.php';

class CategoriaDAL {
    private $conn;
    private $table_name = "categorias";

    public function __construct() {
        $database = new Conexao();
        $this->conn = $database->conectar();
    }

    // Método para listar todas as categorias
    public function listarCategorias() {
        $query = "SELECT id, nome, descricao FROM " . $this->table_name . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Método para inserir uma nova categoria
    public function inserirCategoria($nome, $descricao) {
        $query = "INSERT INTO " . $this->table_name . " (nome, descricao) VALUES (:nome, :descricao)";
        $stmt = $this->conn->prepare($query);

        $nome = htmlspecialchars(strip_tags($nome));
        $descricao = htmlspecialchars(strip_tags($descricao));

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // *** ESTE É O MÉTODO CRÍTICO QUE DEVE ESTAR PRESENTE E CORRETAMENTE ESCRITO ***
    public function buscarCategoriaPorId($id) {
        $query = "SELECT id, nome, descricao FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        return $categoria;
    }

    // Método para atualizar uma categoria existente
    public function atualizarCategoria($id, $nome, $descricao) {
        $query = "UPDATE " . $this->table_name . "
                  SET nome = :nome, descricao = :descricao
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $nome = htmlspecialchars(strip_tags($nome));
        $descricao = htmlspecialchars(strip_tags($descricao));

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para excluir uma categoria
    public function excluirCategoria($id) {
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