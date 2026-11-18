# BibliotecaORM: desenvolvimento de um mapeador objeto-relacional didático com PHP e MySQL

**Acadêmico:** Bruno Furlan | **Matrícula:** 429274  
**Disciplina:** Programação II | **Professor:** Leandro Otavio Cordova Vieira  
**Instituição:** UNOESC — Ciência da Computação  

Projeto e implementação de um mini-ORM para a persistência de entidades de uma biblioteca, conforme a **Avaliação Prática de Programação II (UNOESC)**.

## Organização dos arquivos
- `sql/schema.sql`: Definição do banco e tabelas relacionais (`livros`, `autores`, `livros_autores`, `exemplares`).
- `sql/seed.sql`: Dados iniciais para testes e visualização.
- `src/Domain/Livro.php`: Entidade Livro com encapsulamento e associação N:N.
- `src/Domain/Autor.php`: Entidade Autor com validação e controle de identidade.
- `src/Infrastructure/RepositoryInterface.php`: Contrato polimórfico dos repositórios.
- `src/Infrastructure/AbstractRepository.php`: Classe abstrata com operações CRUD base e algoritmo de persistência.
- `src/Infrastructure/LivroRepository.php`: Especialização para Livro e mapeamento da relação N:N.
- `src/Infrastructure/AutorRepository.php`: Especialização para Autor e função `consultarPorContrato`.
- `src/Infrastructure/Conexao.php`: Fábrica de conexões PDO configurada para MySQL (com fallback automático para XAMPP).
- `tests/smoke.php`: Script de demonstração que executa e valida **todas as 7 evidências** exigidas na avaliação.
- `RELATORIO_TECNICO.pdf`: Relatório técnico oficial em PDF para entrega ao professor.
- `RELATORIO_TECNICO.html`: Relatório técnico formatado em HTML para visualização e impressão.
- `APRESENTACAO.md`: Roteiro da apresentação em tópicos Markdown.
- `APRESENTACAO.html`: Apresentação visual interativa em slides para o navegador.

## Requisitos e Execução
- PHP 8.2+ com `pdo_mysql` habilitado
- MySQL 8 / MariaDB no XAMPP com suporte a InnoDB

### 1. Criar o banco de dados
Importe o script `sql/schema.sql` pelo phpMyAdmin ou terminal:
```bash
mysql -u root < sql/schema.sql
```

### 2. Executar a demonstração (7 evidências avaliativas)
```bash
php tests/smoke.php
```
