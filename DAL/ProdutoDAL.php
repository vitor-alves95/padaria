<?php
// DAL/ProdutoDAL.php

require_once 'Conexao.php'; // Inclui o arquivo de conexão

class ProdutoDAL {
    private $conn;
    private $table_name = "produtos";

    public function __construct() {
        $database = new Conexao();
        $this->conn = $database->conectar();
    }

    // Método para listar todos os produtos com suas categorias
    public function listarProdutos() {
        $query = "SELECT p.id, p.nome, p.descricao, p.preco, p.estoque, p.data_cadastro, c.nome as categoria_nome
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.id_categoria = c.id
                  ORDER BY p.nome ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt; // Retorna o objeto PDOStatement
    }

    // Método para inserir um novo produto
    public function inserirProduto($nome, $descricao, $preco, $estoque, $id_categoria) {
        $query = "INSERT INTO " . $this->table_name . " (nome, descricao, preco, estoque, id_categoria) VALUES (:nome, :descricao, :preco, :estoque, :id_categoria)";

        $stmt = $this->conn->prepare($query);

        // Limpa e vincula os parâmetros para evitar SQL Injection
        $nome = htmlspecialchars(strip_tags($nome));
        $descricao = htmlspecialchars(strip_tags($descricao));
        $preco = htmlspecialchars(strip_tags($preco));
        $estoque = htmlspecialchars(strip_tags($estoque));
        $id_categoria = ($id_categoria === '' || $id_categoria === null) ? null : intval($id_categoria);


        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':estoque', $estoque);
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para buscar um produto pelo ID
    public function buscarProdutoPorId($id) {
        $query = "SELECT p.id, p.nome, p.descricao, p.preco, p.estoque, p.id_categoria, c.nome as categoria_nome
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.id_categoria = c.id
                  WHERE p.id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $produto = $stmt->fetch(PDO::FETCH_ASSOC); // Retorna os dados como um array associativo
        return $produto;
    }

    // Método para atualizar um produto existente
    public function atualizarProduto($id, $nome, $descricao, $preco, $estoque, $id_categoria) {
        $query = "UPDATE " . $this->table_name . "
                  SET nome = :nome,
                      descricao = :descricao,
                      preco = :preco,
                      estoque = :estoque,
                      id_categoria = :id_categoria
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Limpa e vincula os parâmetros
        $nome = htmlspecialchars(strip_tags($nome));
        $descricao = htmlspecialchars(strip_tags($descricao));
        $preco = htmlspecialchars(strip_tags($preco));
        $estoque = htmlspecialchars(strip_tags($estoque));
        $id_categoria = ($id_categoria === '' || $id_categoria === null) ? null : intval($id_categoria);


        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':estoque', $estoque);
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // NOVO MÉTODO: Excluir um produto
    public function excluirProduto($id) {
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