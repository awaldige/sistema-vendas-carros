-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Tempo de geração: 18/02/2026 às 22:52
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
-- Banco de dados: `vendascarros`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `acessorio`
--

CREATE TABLE `acessorio` (
  `id_acessorio` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `acessorio`
--

INSERT INTO `acessorio` (`id_acessorio`, `descricao`) VALUES
(1, 'Ar-condicionado'),
(2, 'Teto solar'),
(3, 'Banco de couro'),
(4, 'Vidros elétricos'),
(5, 'Câmbio automático'),
(6, 'Freios ABS'),
(7, 'Sensor de estacionamento'),
(8, 'Central multimídia'),
(9, 'Faróis de LED'),
(10, 'Airbags laterais');

-- --------------------------------------------------------

--
-- Estrutura para tabela `banco`
--

CREATE TABLE `banco` (
  `id_banco` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `banco`
--

INSERT INTO `banco` (`id_banco`, `nome`) VALUES
(1, 'Banco ABC'),
(2, 'Banco Itaú'),
(3, 'Banco Bradesco'),
(4, 'Banco Santander'),
(5, 'Banco do Brasil'),
(6, 'Caixa Econômica Federal');

-- --------------------------------------------------------

--
-- Estrutura para tabela `carro`
--

CREATE TABLE `carro` (
  `id_carro` int(11) NOT NULL,
  `ano` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `foto` varchar(255) DEFAULT 'sem-foto.jpg',
  `id_loja` int(11) DEFAULT NULL,
  `id_modelo` int(11) DEFAULT NULL,
  `vendido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carro`
--

INSERT INTO `carro` (`id_carro`, `ano`, `preco`, `foto`, `id_loja`, `id_modelo`, `vendido`) VALUES
(1, 2023, 73000.00, 'c256d9f7f4191c7d029594c0a322c9fa.png', 3, 11, 0),
(2, 2022, 129859.00, '6e20ffe04aac76db1809e483419122d1.png', 3, 16, 0),
(3, 2017, 89023.00, '178bd92c5f731ff87d74dec415280261.png', 4, 4, 0),
(4, 2022, 124379.00, 'b3c234f71e18af93d660ecdc959582c7.png', 4, 1, 0),
(5, 2023, 128847.00, 'f4fea767c757686aba5b67aef1d54bfc.png', 4, 15, 0),
(6, 2016, 43500.00, '4b943f8e822882a775009be449e5e0ed.png', 2, 7, 0),
(7, 2021, 195000.00, '565877015b4d686de787fa01d41b74ed.png', 5, 2, 0),
(8, 2023, 77000.00, 'b961551520c836c541a5c30074cea266.png', 4, 6, 0),
(9, 2018, 105000.00, 'c515f525b250666ed25ae52e2f24126e.png', 4, 5, 0),
(10, 2022, 98000.00, '7ac0ca458ce1b7cafe79caf78aadb38b.png', 5, 9, 0),
(11, 2022, 106746.00, '0b7be7a21848fbd3bbcc71ea57e647f5.png', 3, 8, 0),
(19, 2022, 60080.00, 'sem-foto.jpg', 6, 12, 0),
(20, 2022, 100500.00, '4b62cfe8c52b6ed949eb897202aa026b.png', 7, 13, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `carro_acessorio`
--

CREATE TABLE `carro_acessorio` (
  `id_carro` int(11) NOT NULL,
  `id_acessorio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `carro_acessorio`
--

INSERT INTO `carro_acessorio` (`id_carro`, `id_acessorio`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nome`, `telefone`, `email`) VALUES
(1, 'João Silva', '11987654321', 'joao@email.com'),
(2, 'Maria Oliveira', '21987654322', 'maria@email.com'),
(3, 'Carlos Santos', '31987654323', 'carlos@email.com'),
(4, 'Ana Souza', '41987654324', 'ana@email.com'),
(5, 'Pedro Lima', '51987654325', 'pedro@email.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeira`
--

CREATE TABLE `financeira` (
  `id_financeira` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `financeira`
--

INSERT INTO `financeira` (`id_financeira`, `nome`) VALUES
(1, 'Financeira XYZ'),
(2, 'Financeira Alfa'),
(3, 'Financeira Beta'),
(4, 'Financeira Omega'),
(5, 'Financeira Delta'),
(6, 'Financeira Sigma');

-- --------------------------------------------------------

--
-- Estrutura para tabela `gerente`
--

CREATE TABLE `gerente` (
  `id_gerente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `gerente`
--

INSERT INTO `gerente` (`id_gerente`, `nome`, `telefone`, `email`) VALUES
(1, 'Roberto Nunes', '31912345670', 'roberto@email.com'),
(2, 'Mariana Costa', '41912345671', 'mariana@email.com'),
(3, 'Eduardo Lima', '51912345672', 'eduardo@email.com'),
(4, 'Fernanda Soares', '61912345673', 'fernanda@email.com'),
(5, 'Gustavo Pereira', '71912345674', 'gustavo@email.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `loja`
--

CREATE TABLE `loja` (
  `id_loja` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `id_gerente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `loja`
--

INSERT INTO `loja` (`id_loja`, `nome`, `endereco`, `id_gerente`) VALUES
(1, 'AutoCar Center', 'Av. Principal, 1000', 1),
(2, 'Top Veículos', 'Rua Secundária, 200', 2),
(3, 'Mega Autos', 'Av. das Nações, 300', 3),
(4, 'Aw_Elite Motors', 'Rua das Flores, 400', 4),
(5, 'Luxo Carros', 'Av. Central, 500', 5),
(6, 'AW Veiculos', NULL, NULL),
(7, 'AW Motors', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `manutencao_garantia`
--

CREATE TABLE `manutencao_garantia` (
  `id_manutencao` int(11) NOT NULL,
  `id_carro` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `descricao` varchar(255) NOT NULL,
  `data_servico` date NOT NULL,
  `custo` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `manutencao_garantia`
--

INSERT INTO `manutencao_garantia` (`id_manutencao`, `id_carro`, `id_cliente`, `descricao`, `data_servico`, `custo`) VALUES
(1, 1, 1, 'Troca de óleo e revisão geral', '2025-03-22', 500.00),
(2, 1, 1, 'Revisão de motor e troca de filtros', '2025-03-27', 800.00),
(3, 2, 2, 'Substituição de pastilhas de freio', '2025-03-28', 400.00),
(4, 3, 3, 'Ajuste na suspensão e alinhamento', '2025-03-29', 300.00),
(5, 4, 4, 'Troca de pneus e alinhamento', '2025-03-30', 600.00),
(6, 5, 5, 'Instalação de som e ar-condicionado', '2025-03-31', 700.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `marca`
--

CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL,
  `nome_marca` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `marca`
--

INSERT INTO `marca` (`id_marca`, `nome_marca`) VALUES
(1, 'Toyota'),
(2, 'Honda'),
(3, 'Volkswagen'),
(4, 'Fiat'),
(5, 'Jeep'),
(6, 'Chevrolet ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `modelo`
--

CREATE TABLE `modelo` (
  `id_modelo` int(11) NOT NULL,
  `nome_modelo` varchar(50) NOT NULL,
  `id_marca` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `modelo`
--

INSERT INTO `modelo` (`id_modelo`, `nome_modelo`, `id_marca`) VALUES
(1, 'Corolla XEi 2.0 Flex', 1),
(2, 'Hilux SR (2.8 AT 4x4)', 1),
(3, 'Civic Touring: Motor 1.5 Turbo (173 cv).', 2),
(4, 'HR-V EXL ', 2),
(5, 'Golf GTi 2.0 TSI 220cv Automático', 3),
(6, 'Polo 1.0 Turbo 170 TSI', 3),
(7, 'Uno Sporting 1.4 EVO Flex 8V 4p (Manual)', 4),
(8, 'Toro Freedom 1.3 T270 Flex 4x2 AT6 (Flex)', 4),
(9, 'Renegade Limited 1.8 Flex AT6', 5),
(10, ' Compass Série S (1.3 T270 Flex)', 5),
(11, 'Onix LT', 6),
(12, ' Fiat Argo Drive 1.0 Flex 6V', 4),
(13, 'Nivus Comfortline 1.0 TSI (Aut)', 3),
(14, 'Onix 1.0 LT Turbo', 6),
(15, 'Toro Volcano 1.3 T270 4x2 Flex Aut', 4),
(16, 'Corolla GR-Sport 2.0 Flex', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipopagamento`
--

CREATE TABLE `tipopagamento` (
  `id_tipo_pagamento` int(11) NOT NULL,
  `descricao` enum('À Vista','Financiamento') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipopagamento`
--

INSERT INTO `tipopagamento` (`id_tipo_pagamento`, `descricao`) VALUES
(1, 'À Vista'),
(2, 'Financiamento');

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda`
--

CREATE TABLE `venda` (
  `id_venda` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `id_carro` int(11) DEFAULT NULL,
  `id_tipo_pagamento` int(11) DEFAULT NULL,
  `id_financeira` int(11) DEFAULT NULL,
  `id_banco` int(11) DEFAULT NULL,
  `data_venda` date NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `veiculo_nome` varchar(255) DEFAULT NULL,
  `veiculo_marca` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `venda`
--

INSERT INTO `venda` (`id_venda`, `id_cliente`, `id_vendedor`, `id_carro`, `id_tipo_pagamento`, `id_financeira`, `id_banco`, `data_venda`, `valor_total`, `veiculo_nome`, `veiculo_marca`) VALUES
(1, NULL, NULL, 12, NULL, NULL, NULL, '2026-02-17', 158000.00, 'Hilux', 'Toyota'),
(2, NULL, NULL, 21, NULL, NULL, NULL, '2026-02-17', 65852.00, 'Onix 1.0 LT Turbo', 'Chevrolet ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendedor`
--

CREATE TABLE `vendedor` (
  `id_vendedor` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `vendedor`
--

INSERT INTO `vendedor` (`id_vendedor`, `nome`, `telefone`, `email`) VALUES
(1, 'Lucas Almeida', '11912345678', 'lucas@email.com'),
(2, 'Fernanda Rocha', '21912345679', 'fernanda@email.com'),
(3, 'Rafael Mendes', '31912345680', 'rafael@email.com'),
(4, 'Juliana Castro', '41912345681', 'juliana@email.com'),
(5, 'Bruno Ferreira', '51912345682', 'bruno@email.com');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `acessorio`
--
ALTER TABLE `acessorio`
  ADD PRIMARY KEY (`id_acessorio`);

--
-- Índices de tabela `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`id_banco`);

--
-- Índices de tabela `carro`
--
ALTER TABLE `carro`
  ADD PRIMARY KEY (`id_carro`),
  ADD KEY `id_loja` (`id_loja`),
  ADD KEY `id_modelo` (`id_modelo`);

--
-- Índices de tabela `carro_acessorio`
--
ALTER TABLE `carro_acessorio`
  ADD PRIMARY KEY (`id_carro`,`id_acessorio`),
  ADD KEY `id_acessorio` (`id_acessorio`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices de tabela `financeira`
--
ALTER TABLE `financeira`
  ADD PRIMARY KEY (`id_financeira`);

--
-- Índices de tabela `gerente`
--
ALTER TABLE `gerente`
  ADD PRIMARY KEY (`id_gerente`);

--
-- Índices de tabela `loja`
--
ALTER TABLE `loja`
  ADD PRIMARY KEY (`id_loja`),
  ADD KEY `id_gerente` (`id_gerente`);

--
-- Índices de tabela `manutencao_garantia`
--
ALTER TABLE `manutencao_garantia`
  ADD PRIMARY KEY (`id_manutencao`),
  ADD KEY `id_carro` (`id_carro`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`);

--
-- Índices de tabela `modelo`
--
ALTER TABLE `modelo`
  ADD PRIMARY KEY (`id_modelo`),
  ADD KEY `id_marca` (`id_marca`);

--
-- Índices de tabela `tipopagamento`
--
ALTER TABLE `tipopagamento`
  ADD PRIMARY KEY (`id_tipo_pagamento`);

--
-- Índices de tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`id_venda`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `id_carro` (`id_carro`),
  ADD KEY `id_tipo_pagamento` (`id_tipo_pagamento`),
  ADD KEY `id_financeira` (`id_financeira`),
  ADD KEY `id_banco` (`id_banco`);

--
-- Índices de tabela `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`id_vendedor`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `acessorio`
--
ALTER TABLE `acessorio`
  MODIFY `id_acessorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `banco`
--
ALTER TABLE `banco`
  MODIFY `id_banco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `carro`
--
ALTER TABLE `carro`
  MODIFY `id_carro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `financeira`
--
ALTER TABLE `financeira`
  MODIFY `id_financeira` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `gerente`
--
ALTER TABLE `gerente`
  MODIFY `id_gerente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `loja`
--
ALTER TABLE `loja`
  MODIFY `id_loja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `manutencao_garantia`
--
ALTER TABLE `manutencao_garantia`
  MODIFY `id_manutencao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `modelo`
--
ALTER TABLE `modelo`
  MODIFY `id_modelo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `tipopagamento`
--
ALTER TABLE `tipopagamento`
  MODIFY `id_tipo_pagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `venda`
--
ALTER TABLE `venda`
  MODIFY `id_venda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `id_vendedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carro`
--
ALTER TABLE `carro`
  ADD CONSTRAINT `carro_ibfk_1` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`),
  ADD CONSTRAINT `carro_ibfk_2` FOREIGN KEY (`id_modelo`) REFERENCES `modelo` (`id_modelo`);

--
-- Restrições para tabelas `carro_acessorio`
--
ALTER TABLE `carro_acessorio`
  ADD CONSTRAINT `carro_acessorio_ibfk_1` FOREIGN KEY (`id_carro`) REFERENCES `carro` (`id_carro`),
  ADD CONSTRAINT `carro_acessorio_ibfk_2` FOREIGN KEY (`id_acessorio`) REFERENCES `acessorio` (`id_acessorio`);

--
-- Restrições para tabelas `loja`
--
ALTER TABLE `loja`
  ADD CONSTRAINT `loja_ibfk_1` FOREIGN KEY (`id_gerente`) REFERENCES `gerente` (`id_gerente`);

--
-- Restrições para tabelas `manutencao_garantia`
--
ALTER TABLE `manutencao_garantia`
  ADD CONSTRAINT `manutencao_garantia_ibfk_1` FOREIGN KEY (`id_carro`) REFERENCES `carro` (`id_carro`),
  ADD CONSTRAINT `manutencao_garantia_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Restrições para tabelas `modelo`
--
ALTER TABLE `modelo`
  ADD CONSTRAINT `modelo_ibfk_1` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`);

--
-- Restrições para tabelas `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `venda_ibfk_2` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id_vendedor`),
  ADD CONSTRAINT `venda_ibfk_4` FOREIGN KEY (`id_tipo_pagamento`) REFERENCES `tipopagamento` (`id_tipo_pagamento`),
  ADD CONSTRAINT `venda_ibfk_5` FOREIGN KEY (`id_financeira`) REFERENCES `financeira` (`id_financeira`),
  ADD CONSTRAINT `venda_ibfk_6` FOREIGN KEY (`id_banco`) REFERENCES `banco` (`id_banco`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
