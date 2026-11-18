-- Dados iniciais (seed) para testes e demonstração do BibliotecaORM
USE biblioteca_orm;

-- Inserção de Autores
INSERT INTO autores (id, nome, nacionalidade) VALUES
(1, 'Machado de Assis', 'Brasileira'),
(2, 'Clarice Lispector', 'Brasileira'),
(3, 'George Orwell', 'Britânica')
ON DUPLICATE KEY UPDATE nome = VALUES(nome), nacionalidade = VALUES(nacionalidade);

-- Inserção de Livros
INSERT INTO livros (id, titulo, isbn, ano_publicacao) VALUES
(1, 'Dom Casmurro', '9788535914849', 1899),
(2, 'A Hora da Estrela', '9788532508119', 1977),
(3, '1984', '9788535914848', 1949)
ON DUPLICATE KEY UPDATE titulo = VALUES(titulo), isbn = VALUES(isbn), ano_publicacao = VALUES(ano_publicacao);

-- Associação N:N (livros_autores)
INSERT IGNORE INTO livros_autores (livro_id, autor_id) VALUES
(1, 1),
(2, 2),
(3, 3);
