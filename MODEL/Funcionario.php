<?php
// MODEL/Funcionario.php

class Funcionario {
    public $id;
    public $nome;
    public $sobrenome;
    public $email;
    public $senha; // Lembre-se: será o hash da senha
    public $cargo;
    public $data_contratacao;
    public $data_cadastro;

    // Métodos para setar e obter propriedades, e validações específicas podem vir aqui
}
?>