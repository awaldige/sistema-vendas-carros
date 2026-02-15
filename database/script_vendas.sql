DROP DATABASE IF EXISTS VendasCarros;
CREATE DATABASE VendasCarros;
USE VendasCarros;

-- 2. Criação das Tabelas (Sem dependências de FK primeiro)

CREATE TABLE Cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100)
);

CREATE TABLE Vendedor (
    id_vendedor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100)
);

CREATE TABLE Gerente (
    id_gerente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100)
);

CREATE TABLE Acessorio (
    id_acessorio INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(100) NOT NULL
);

CREATE TABLE Financeira (
    id_financeira INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE Banco (
    id_banco INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE TipoPagamento (
    id_tipo_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    descricao ENUM('À Vista', 'Financiamento') NOT NULL
);

-- 3. Tabelas com Chaves Estrangeiras (Relacionamentos)

CREATE TABLE Loja (
    id_loja INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    endereco VARCHAR(255),
    id_gerente INT,
    FOREIGN KEY (id_gerente) REFERENCES Gerente(id_gerente)
);

CREATE TABLE Carro (
    id_carro INT AUTO_INCREMENT PRIMARY KEY,
    modelo VARCHAR(100) NOT NULL,
    ano INT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    id_loja INT,
    FOREIGN KEY (id_loja) REFERENCES Loja(id_loja)
);

CREATE TABLE Carro_Acessorio (
    id_carro INT,
    id_acessorio INT,
    PRIMARY KEY (id_carro, id_acessorio),
    FOREIGN KEY (id_carro) REFERENCES Carro(id_carro),
    FOREIGN KEY (id_acessorio) REFERENCES Acessorio(id_acessorio)
);

CREATE TABLE Venda (
    id_venda INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    id_vendedor INT,
    id_carro INT,
    id_tipo_pagamento INT,
    id_financeira INT NULL,
    id_banco INT NULL,
    data_venda DATE NOT NULL,
    valor_total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_cliente) REFERENCES Cliente(id_cliente),
    FOREIGN KEY (id_vendedor) REFERENCES Vendedor(id_vendedor),
    FOREIGN KEY (id_carro) REFERENCES Carro(id_carro),
    FOREIGN KEY (id_tipo_pagamento) REFERENCES TipoPagamento(id_tipo_pagamento),
    FOREIGN KEY (id_financeira) REFERENCES Financeira(id_financeira),
    FOREIGN KEY (id_banco) REFERENCES Banco(id_banco)
);

CREATE TABLE Manutencao_Garantia (
    id_manutencao INT AUTO_INCREMENT PRIMARY KEY,
    id_carro INT,
    id_cliente INT,
    descricao VARCHAR(255) NOT NULL,
    data_servico DATE NOT NULL,
    custo DECIMAL(10,2),
    FOREIGN KEY (id_carro) REFERENCES Carro(id_carro),
    FOREIGN KEY (id_cliente) REFERENCES Cliente(id_cliente)
);

-- 4. Inserção de Dados (População do Banco)

INSERT INTO Cliente (nome, telefone, email) VALUES
('João Silva', '11987654321', 'joao@email.com'),
('Maria Oliveira', '21987654322', 'maria@email.com'),
('Carlos Santos', '31987654323', 'carlos@email.com'),
('Ana Souza', '41987654324', 'ana@email.com'),
('Pedro Lima', '51987654325', 'pedro@email.com');

INSERT INTO Vendedor (nome, telefone, email) VALUES
('Lucas Almeida', '11912345678', 'lucas@email.com'),
('Fernanda Rocha', '21912345679', 'fernanda@email.com'),
('Rafael Mendes', '31912345680', 'rafael@email.com'),
('Juliana Castro', '41912345681', 'juliana@email.com'),
('Bruno Ferreira', '51912345682', 'bruno@email.com');

INSERT INTO Gerente (nome, telefone, email) VALUES
('Roberto Nunes', '31912345670', 'roberto@email.com'),
('Mariana Costa', '41912345671', 'mariana@email.com'),
('Eduardo Lima', '51912345672', 'eduardo@email.com'),
('Fernanda Soares', '61912345673', 'fernanda@email.com'),
('Gustavo Pereira', '71912345674', 'gustavo@email.com');

INSERT INTO Loja (nome, endereco, id_gerente) VALUES
('AutoCar Center', 'Av. Principal, 1000', 1),
('Top Veículos', 'Rua Secundária, 200', 2),
('Mega Autos', 'Av. das Nações, 300', 3),
('Elite Motors', 'Rua das Flores, 400', 4),
('Luxo Carros', 'Av. Central, 500', 5);

INSERT INTO Carro (modelo, ano, preco, id_loja) VALUES
('Toyota Corolla', 2022, 120000.00, 1),
('Honda Civic', 2021, 110000.00, 2),
('Chevrolet Onix', 2023, 90000.00, 3),
('Ford Ranger', 2022, 180000.00, 4),
('Jeep Compass', 2021, 150000.00, 5),
('Volkswagen Golf', 2023, 130000.00, 1),
('Nissan Kicks', 2022, 95000.00, 2),
('Fiat Toro', 2023, 120000.00, 3),
('Hyundai Creta', 2021, 105000.00, 4),
('Renault Duster', 2022, 98000.00, 5);

INSERT INTO Acessorio (descricao) VALUES
('Ar-condicionado'), ('Teto solar'), ('Banco de couro'), ('Vidros elétricos'),
('Câmbio automático'), ('Freios ABS'), ('Sensor de estacionamento'),
('Central multimídia'), ('Faróis de LED'), ('Airbags laterais');

INSERT INTO Carro_Acessorio (id_carro, id_acessorio) VALUES
(1, 1), (2, 2), (3, 3), (4, 4), (5, 5), (6, 6), (7, 7), (8, 8);

INSERT INTO Financeira (nome) VALUES
('Financeira XYZ'), ('Financeira Alfa'), ('Financeira Beta'),
('Financeira Omega'), ('Financeira Delta'), ('Financeira Sigma');

INSERT INTO Banco (nome) VALUES
('Banco ABC'), ('Banco Itaú'), ('Banco Bradesco'),
('Banco Santander'), ('Banco do Brasil'), ('Caixa Econômica Federal');

INSERT INTO TipoPagamento (descricao) VALUES
('À Vista'),
('Financiamento');

-- Inserir Vendas (Ajustado para bater com IDs existentes)
INSERT INTO Venda (id_cliente, id_vendedor, id_carro, id_tipo_pagamento, id_financeira, id_banco, data_venda, valor_total) VALUES
(1, 1, 1, 2, 1, NULL, '2025-03-20', 120000.00),
(2, 2, 2, 1, NULL, 1, '2025-03-21', 110000.00),
(3, 3, 3, 2, 1, 1, '2025-03-22', 90000.00),  
(4, 4, 4, 1, NULL, NULL, '2025-03-23', 180000.00), 
(5, 5, 5, 2, 2, 2, '2025-03-24', 150000.00), 
(1, 1, 6, 1, NULL, 1, '2025-03-25', 130000.00), 
(2, 2, 7, 2, 3, NULL, '2025-03-26', 95000.00); 

INSERT INTO Manutencao_Garantia (id_carro, id_cliente, descricao, data_servico, custo) VALUES
(1, 1, 'Troca de óleo e revisão geral', '2025-03-22', 500.00),
(1, 1, 'Revisão de motor e troca de filtros', '2025-03-27', 800.00),  
(2, 2, 'Substituição de pastilhas de freio', '2025-03-28', 400.00),  
(3, 3, 'Ajuste na suspensão e alinhamento', '2025-03-29', 300.00),  
(4, 4, 'Troca de pneus e alinhamento', '2025-03-30', 600.00),  
(5, 5, 'Instalação de som e ar-condicionado', '2025-03-31', 700.00);
