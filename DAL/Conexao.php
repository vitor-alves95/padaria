<?php
// DAL/Conexao.php

class Conexao {
    private $host = "localhost";
    private $db_name = "padaria_db";
    private $username = "root"; // Usuário padrão do XAMPP
    private $password = "";     // Senha padrão do XAMPP (geralmente vazia)
    public $conn;

    public function conectar() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8mb4"); // Garante o suporte a caracteres especiais
        } catch(PDOException $exception) {
            echo "Erro de conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>