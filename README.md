# 🚗 AutoManager Pro: Sistema de Gestão de Concessionárias

Este projeto evoluiu de uma modelagem de banco de dados para uma **aplicação web completa**, permitindo o gerenciamento em tempo real de estoque, fotos de veículos e fluxo de vendas.

## 📊 Arquitetura de Dados
O sistema utiliza uma estrutura relacional sólida para garantir a integridade entre Marcas, Modelos, Lojas e Vendas.

![Diagrama de Entidade Relacionamento](diagrama_er.svg)

## 🚀 Funcionalidades Implementadas
- **Dashboard Gerencial:** Visualização de faturamento total e volume de vendas.
- **Gestão de Fotos:** Upload de imagens personalizadas com tratamento de nomes únicos (MD5).
- **CRUD Completo:** Cadastro, edição e exclusão de veículos e modelos.
- **Fluxo de Venda:** Processamento de vendas com histórico automatizado.
- **Relatórios:** Histórico de transações detalhado por loja e período.

## 🛠️ Tecnologias
- **Backend:** PHP 8.x
- **Banco de Dados:** MySQL / MariaDB
- **Frontend:** Bootstrap 5 & Bootstrap Icons
- **Modelagem:** phpMyAdmin Designer & dbdiagram.io

## 📁 Estrutura do Projeto
- `/uploads`: Armazenamento de imagens dos veículos.
- `/database`: Contém o arquivo `database.sql` para replicação do ambiente.
- `index.php`: Dashboard principal.
- `relatorio_vendas.php`: Módulo de histórico financeiro.

##  🔗 Acesse o projeto online:
http://awaldige.infinityfree.me/vendascarros/

## 📸 Prévia
![IMG_1640](https://github.com/user-attachments/assets/d175f6e3-162d-4f20-903d-d94842f82acb)
![IMG_1641](https://github.com/user-attachments/assets/4ef3239f-f3b1-4638-bfb2-5d54f00c6bfd)
![IMG_1642](https://github.com/user-attachments/assets/29e9f3e6-41e5-410a-a33a-675ff87c7c98)
![IMG_1644](https://github.com/user-attachments/assets/4d4ec2ad-e05b-4f17-b026-d304cc717622)




## ⚙️ Como Instalar e Rodar
1. Clone este repositório em seu servidor local (ex: `/htdocs` do XAMPP).
2. Importe o arquivo `database.sql` no seu MySQL.
3. Configure as credenciais de acesso no arquivo `conexao.php`.
4. Certifique-se de que a pasta `uploads/` possua permissões de escrita (CHMOD 777 em sistemas Linux).

---
*Projeto desenvolvido para fins de portfólio técnico - Full Stack (PHP/SQL).*
