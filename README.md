# 🚗 Sistema de Gestão de Vendas - Concessionária

Este projeto apresenta uma solução completa de banco de dados para uma rede de concessionárias de veículos, cobrindo desde o estoque até o pós-venda.

## 📊 Modelo de Dados (DER)
Abaixo, a representação visual das tabelas e seus relacionamentos. O diagrama foi exportado em formato vetorial para garantir a máxima clareza na visualização dos atributos e chaves.

![Diagrama de Entidade Relacionamento](diagrama_er.svg)

## 🛠️ Tecnologias
- **MySQL / MariaDB**
- **phpMyAdmin** (Administração e Modelagem no Designer)
- **dbdiagram.io** (Opcional para visualização)

## 📁 Estrutura do Projeto
- `/database`: Contém o script SQL para criação e população do banco.
- `diagrama_er.svg`: Visualização técnica da arquitetura do banco em formato vetorial.

## 🚀 Como utilizar
1. Certifique-se de ter um servidor MySQL ativo (XAMPP, WAMP ou Docker).
2. Importe o arquivo em `database/script_vendas.sql` através do terminal ou do phpMyAdmin.
3. O banco `VendasCarros` será criado, as tabelas estruturadas e populadas com dados de teste automaticamente.

---
*Projeto desenvolvido para fins de portfólio técnico.*
