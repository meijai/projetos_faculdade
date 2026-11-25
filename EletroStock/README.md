# ⚡ EletroStock

> **Sistema Web Orientado a Objetos para Gestão e Controle de Estoque de Materiais Elétricos**

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![Arquitetura](https://img.shields.io/badge/Padrão-MVC-blueviolet?style=for-the-badge)](#-arquitetura-e-engenharia-de-software)
[![UNOESC](https://img.shields.io/badge/Acadêmico-UNOESC-1E3A8A?style=for-the-badge)](https://www.unoesc.edu.br/)

---

## 📌 Sumário
- [Sobre o Projeto](#-sobre-o-projeto)
- [Funcionalidades Principais](#-funcionalidades-principais)
- [Arquitetura e Engenharia de Software](#-arquitetura-e-engenharia-de-software)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Estrutura do Repositório](#-estrutura-do-repositório)
- [Instalação e Execução](#-instalação-e-execução)
- [Modelagem de Dados e Integridade](#-modelagem-de-dados-e-integridade)
- [Documentação e Artefatos Acadêmicos](#-documentação-e-artefatos-acadêmicos)
- [Autores e Docentes Orientadores](#-autores-e-docentes-orientadores)
- [Licença](#-licença)

---

## 📖 Sobre o Projeto

O **EletroStock** é uma solução completa desenvolvida para suprir as dores operacionais no gerenciamento de estoques de materiais elétricos (cabos unipolares, disjuntores DIN, eletrodutos, lâmpadas, dispositivos de comando e tomadas). Empresas e almoxarifados do setor elétrico enfrentam desafios como controle inadequado de bitolas técnicas, falta de rastreabilidade de saídas e saldos negativos acidentais.

Este sistema foi concebido no âmbito do **Seminário Interdisciplinar** do curso de Ciência da Computação / Sistemas de Informação da **Universidade do Oeste de Santa Catarina (UNOESC - Campus Videira)**, integrando as disciplinas de:
- **Engenharia de Software I**
- **Programação II (Programação Orientada a Objetos)**
- **Banco de Dados II**

---

## 🚀 Funcionalidades Principais

- 📦 **Painel Geral de Estoque (Dashboard):**
  - Visão consolidada de saldo físico e financeiro (valor em estoque).
  - Alertas visuais para produtos com baixo estoque ou esgotados.
  - Filtros rápidos por texto e categorias.

- 🏷️ **Gestão de Categorias e Seções Nominais (Bitolas):**
  - Cadastro, edição e consulta de categorias (ex: *Cabos e Fios*, *Proteção*, *Iluminação*).
  - Controle especializado de bitolas elétricas com unidades padronizadas (ex: `1.5 mm²`, `2.5 mm²`, `4.0 mm²`, `6.0 mm²`, `10.0 mm²`).

- ⚡ **Catálogo de Materiais Elétricos:**
  - Associação obrigatória à categoria e opcional à bitola técnica.
  - Exibição de nomes compostos padronizados com cálculo de preço unitário e valor total.

- 🔄 **Movimentações Atômicas de Entrada e Saída:**
  - Registro de entradas com motivo de movimentação.
  - Registro de saídas com validação de estoque remanescente (bloqueio de saldo negativo).
  - Atualização automática e imediata no saldo do material.

- 🛡️ **Histórico Completo e Estorno Seguro:**
  - Rastreabilidade de cada transação (data, hora, tipo e motivo).
  - Mecanismo de estorno/remoção de movimentação:
    - O estorno de uma saída recompõe automaticamente o saldo.
    - O estorno de uma entrada valida se a devolução não deixará o saldo atual negativo.

---

## 🏛️ Arquitetura e Engenharia de Software

O projeto adota o padrão de arquitetura **MVC (Model-View-Controller)** puro, sem dependência de frameworks pesados, aplicando rigorosamente os pilares da **Programação Orientada a Objetos (POO)**:

```
                  ┌───────────────────────┐
                  │       Navegador       │
                  └───────────┬───────────┘
                              │ Requisição HTTP
                              ▼
                  ┌───────────────────────┐
                  │       index.php       │
                  └───────────┬───────────┘
                              │
                              ▼
                  ┌───────────────────────┐
                  │   EstoqueController   │ ◄──── Controla fluxo e ações
                  └───┬───────────────┬───┘
                      │               │
    Alimenta/Consulta │               │ Carrega View ativa
                      ▼               ▼
           ┌─────────────────┐   ┌─────────────────────────┐
           │     Models      │   │          Views          │
           │  - ItemBase     │   │  - estoque.php          │
           │  - Produto      │   │  - produtos.php         │
           │  - Categoria    │   │  - categorias.php       │
           │  - SecaoNominal │   │  - secoes.php           │
           │  - Movimentacao │   │  - movimentacoes.php    │
           │  - Repositorio  │   │  - historico.php        │
           │  - Database     │   └─────────────────────────┘
           └────────┬────────┘
                    │ PDO
                    ▼
           ┌─────────────────┐
           │ MySQL Database  │
           └─────────────────┘
```

### 🧩 Pilares de POO Aplicados
1. **Abstração e Herança:**
   - Classe abstrata base `ItemBase` contendo identificadores e comportamentos comuns, herdada por `Produto`, `Categoria` e `SecaoNominal`.
2. **Polimorfismo:**
   - Assinatura do método abstrato `exibirDetalhes()` em `ItemBase`, implementado com saídas especializadas nas classes filhas.
3. **Encapsulamento:**
   - Atributos com visibilidade `protected`/`private`, com acesso controlado por `getters` e `setters`, garantindo validações de negócio em nível de objeto (ex: `setQuantidade` protegendo valores negativos).
4. **Design Patterns:**
   - **Singleton (`Database.php`):** Garante conexão única com o banco de dados via PDO por ciclo de requisição.
   - **Repository (`Repositorio.php`):** Isola a lógica de negócio e as consultas SQL do Controller, centralizando regras e transações.

---

## 💻 Tecnologias Utilizadas

- **Back-end:** [PHP 8.x](https://www.php.net/) com PDO (extensão nativa para conexão segura e *Prepared Statements*).
- **Banco de Dados:** [MySQL 8.x / MariaDB](https://www.mysql.com/) com InnoDB (suporte a transações ACID e Foreign Keys).
- **Front-end:** [HTML5](https://developer.mozilla.org/pt-BR/docs/Web/HTML), [Tailwind CSS 3.x](https://tailwindcss.com/) (via CDN com plugins de formulários e tipografia), SVG Icons e Google Fonts (*Inter* e *Roboto*).
- **Ambiente de Desenvolvimento:** [XAMPP](https://www.apachefriends.org/pt_br/index.html) / Apache HTTP Server.

---

## 📁 Estrutura do Repositório

```text
EletroStock/
├── database/
│   ├── eletrostock.sql             # Script DDL de criação do schema e tabelas
│   └── eletrostock_inserts.sql     # Script DML com dados iniciais para testes
│
├── Documentos/
│   ├── Diagramas/                  # Diagramas UML (MVC, Casos de Uso, Classes, Sequência, DER)
│   ├── 00_requisitos_*.pdf         # Especificação de requisitos (Eng. Software e Programação)
│   ├── 01_artigo_*.pdf             # Artigos técnico-científicos completos
│   └── 02_Apresentacao_EletroStock.html # Slides interativos de apresentação do seminário
│
├── EletroStock/                    # Código-fonte da aplicação
│   ├── controllers/
│   │   └── EstoqueController.php   # Controlador das requisições e roteamento
│   ├── models/
│   │   ├── Database.php            # Conexão PDO (Singleton)
│   │   ├── ItemBase.php            # Classe abstrata base
│   │   ├── Categoria.php           # Modelo de Categorias
│   │   ├── SecaoNominal.php        # Modelo de Seções Nominais / Bitolas
│   │   ├── Produto.php             # Modelo de Produtos / Materiais
│   │   ├── Movimentacao.php        # Modelo de Movimentações
│   │   └── Repositorio.php         # Camada de Repositório e persistência
│   ├── views/
│   │   ├── header.php              # Topo da aplicação e menu lateral (Sidebar)
│   │   ├── footer.php              # Rodapé
│   │   ├── estoque.php             # Painel/Dashboard de saldo e resumo
│   │   ├── produtos.php            # CRUD e catálogo de produtos
│   │   ├── categorias.php          # CRUD de categorias
│   │   ├── secoes.php              # CRUD de seções nominais
│   │   ├── movimentacoes.php       # Registro de entradas e saídas
│   │   ├── historico.php           # Auditoria e log de movimentações
│   │   └── style.css               # Estilos complementares
│   └── index.php                   # Ponto de entrada (Front Controller)
│
└── README.md                       # Documentação do projeto
```

---

## ⚙️ Instalação e Execução

### Pré-requisitos
- [XAMPP](https://www.apachefriends.org/) (ou WampServer / LAMP) com **PHP 7.4+** ou **PHP 8.x** e **MySQL/MariaDB**.
- Navegador web moderno (Chrome, Firefox, Edge, etc.).

### Passo a Passo

1. **Clonar ou copiar os arquivos para o servidor local:**
   Coloque o diretório do projeto dentro da pasta web do seu servidor (ex: `htdocs` no XAMPP):
   ```text
   C:\xampp\htdocs\projetos_faculdade\EletroStock
   ```

2. **Inicializar o Apache e o MySQL:**
   Abra o painel do XAMPP e inicie os módulos **Apache** e **MySQL**.

3. **Importar o Banco de Dados:**
   - Acesse o [phpMyAdmin](http://localhost/phpmyadmin) ou seu cliente MySQL de preferência (ex: DBeaver, MySQL Workbench).
   - Execute o script de criação do banco:
     ```sql
     database/eletrostock.sql
     ```
   - Em seguida, execute a carga inicial de dados:
     ```sql
     database/eletrostock_inserts.sql
     ```

4. **Configurar as credenciais do Banco:**
   Verifique o arquivo [EletroStock/models/Database.php](file:///c:/xampp/htdocs/projetos_faculdade/EletroStock/EletroStock/models/Database.php):
   ```php
   $host = '127.0.0.1';
   $port = '3306';
   $dbname = 'eletrostock';
   $usuario = 'root';
   $senha = 'sua_senha'; // Ajuste caso seu ambiente local possua senha diferente
   ```

5. **Acessar a Aplicação:**
   Abra seu navegador e acerte a URL correspondente:
   ```url
   http://localhost/projetos_faculdade/EletroStock/EletroStock/
   ```

---

## 🗄️ Modelagem de Dados e Integridade

O banco de dados relacional foi modelado sob a engine **InnoDB**, com restrições explícitas de integridade referencial:

- **Tabela `CATEGORIA`:** Agrupamento de itens.
- **Tabela `SECAO_NOMINAL`:** Bitolas de cabos e condutores com chave única composta `(NOME, UNIDADE_MEDIDA)` evitando duplicidades.
- **Tabela `PRODUTO`:**
  - Foreign Key para `CATEGORIA` (`ON DELETE RESTRICT`).
  - Foreign Key para `SECAO_NOMINAL` (`ON DELETE RESTRICT`).
- **Tabela `MOVIMENTACAO`:**
  - Foreign Key para `PRODUTO` (`ON DELETE RESTRICT`).
  - Controle de tipos de operação via enumeração: `'ENTRADA'` ou `'SAIDA'`.

As transações críticas (entradas, saídas e estornos) utilizam blocos transacionais com `beginTransaction()`, `commit()` e `rollBack()`, prevenindo inconsistências de saldo em caso de falhas no banco.

---

## 📄 Documentação e Artefatos Acadêmicos

A pasta [`Documentos/`](file:///c:/xampp/htdocs/projetos_faculdade/EletroStock/Documentos) reúne todos os artefatos produzidos para o seminário:

- 📊 **Diagramas UML:**
  - Arquitetura MVC
  - Casos de Uso
  - Diagrama de Classes
  - Diagramas de Sequência (Entradas, Saídas e Estornos)
  - Diagrama Entidade-Relacionamento (DER)
- 📝 **Artigos Científicos:** Artigos com fundamentação teórica, análise de requisitos e avaliação de resultados.
- 🖥️ **Apresentação de Slides:** Arquivo [`Documentos/02_Apresentacao_EletroStock.html`](file:///c:/xampp/htdocs/projetos_faculdade/EletroStock/Documentos/02_Apresentacao_EletroStock.html) executável em qualquer navegador moderno para a apresentação oral de 15 minutos.

---

## 👥 Autores e Docentes Orientadores

### Autores
- **Bruno Furlan**
- **Higor Afonso Kleemann**
- **Lucas Castanheiro**
- **Lucas Piva Bellaver**

### Docentes Orientadores
- **Prof. Leandro Otavio Cordova Vieira**
- **Profª. Tatiele Bolson Moro**

**Instituição:** Universidade do Oeste de Santa Catarina (UNOESC) - Campus Videira

---

## 📜 Licença

Projeto desenvolvido para fins acadêmicos e educacionais. Todos os direitos reservados aos autores e à instituição.
