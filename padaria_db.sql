-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/06/2025 às 16:33
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `padaria_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `descricao`) VALUES
(1, 'Pães', 'Variedade de pães frescos e artesanais.'),
(2, 'Bolos e Tortas', 'Deliciosos bolos e tortas para todas as ocasiões.'),
(3, 'Salgados', 'Opções salgadas para lanches rápidos.'),
(4, 'Doces', 'Confeitos e sobremesas em geral.'),
(6, 'Bebidas', 'Bebidas e drinks');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `sobrenome` varchar(150) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `cpf` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `sobrenome`, `email`, `telefone`, `endereco`, `data_cadastro`, `cpf`) VALUES
(2, 'João', 'Santos', 'joao.santos@email.com', '21912345678', 'Avenida Principal, 456', '2025-06-10 12:17:01', '12345678985'),
(3, 'Ana', 'Costa', 'ana.costa@email.com', '31998765432', 'Travessa da Paz, 789', '2025-06-10 12:17:01', '12345678998');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `sobrenome` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `data_contratacao` date DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `nome`, `sobrenome`, `email`, `senha`, `cargo`, `data_contratacao`, `data_cadastro`) VALUES
(1, 'Vitor', 'Alves', 'vitor@padaria.com', '$2y$10$AP9i0FpaO77coAe1MkUxx.uGXdRin2R6FL22au0aB5RDV9XzmyiMm', 'Gerente', '2023-01-15', '2025-06-10 12:17:01'),
(4, 'Matheus', '', 'matheus@padaria.com', '$2y$10$.DO8.795hX7RYKyHMKCKLONjsv04gW7dVeqy3qaSxFblVmND01Ugy', 'Gerente', NULL, '2025-06-10 14:25:04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `id_categoria` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `estoque`, `id_categoria`, `data_cadastro`) VALUES
(1, 'Pão Francês', 'Tradicional pão francês crocante.', 0.80, 500, 1, '2025-06-10 12:17:01'),
(2, 'Bolo de Chocolate', 'Bolo caseiro de chocolate com cobertura.', 45.00, 8, 2, '2025-06-10 12:17:01'),
(3, 'Coxinha de Frango', 'Salgado frito recheado com frango.', 6.50, 80, 3, '2025-06-10 12:17:01'),
(4, 'Brigadeiro', 'Doce típico brasileiro.', 3.00, 150, 4, '2025-06-10 12:17:01'),
(5, 'Café Expresso', 'Café forte e aromático.', 5.00, 999, 6, '2025-06-10 12:17:01');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
