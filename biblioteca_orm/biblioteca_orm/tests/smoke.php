<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Domain/Livro.php';
require_once __DIR__ . '/../src/Domain/Autor.php';
require_once __DIR__ . '/../src/Infrastructure/RepositoryInterface.php';
require_once __DIR__ . '/../src/Infrastructure/AbstractRepository.php';
require_once __DIR__ . '/../src/Infrastructure/LivroRepository.php';
require_once __DIR__ . '/../src/Infrastructure/AutorRepository.php';
require_once __DIR__ . '/../src/Infrastructure/Conexao.php';

use Biblioteca\Domain\Livro;
use Biblioteca\Domain\Autor;
use Biblioteca\Infrastructure\Conexao;
use Biblioteca\Infrastructure\LivroRepository;
use Biblioteca\Infrastructure\AutorRepository;
use Biblioteca\Infrastructure\RepositoryInterface;
use function Biblioteca\Infrastructure\consultarPorContrato;

$pdo = Conexao::abrir();
$livros = new LivroRepository($pdo);
$autores = new AutorRepository($pdo);

$tag = bin2hex(random_bytes(3));

// EVIDÊNCIA 1: Cadastro de Livro e Autor com IDs gerados pelo MySQL
echo "[EVIDENCIA 1] Cadastro de Livro e Autor com IDs gerados pelo MySQL:\n";
$autor1 = new Autor('Machado de Assis ' . $tag, 'Brasileira');
$autor2 = new Autor('Clarice Lispector ' . $tag, 'Brasileira');
$autores->salvar($autor1);
$autores->salvar($autor2);

$livro = new Livro('Antologia Literária ' . $tag, '978853590277' . rand(0,9), 2026);
$livros->salvar($livro);

echo "  -> Autor 1 cadastrado: ID = {$autor1->id()}, Nome = '{$autor1->nome()}'\n";
echo "  -> Autor 2 cadastrado: ID = {$autor2->id()}, Nome = '{$autor2->nome()}'\n";
echo "  -> Livro cadastrado:   ID = {$livro->id()}, Título = '{$livro->titulo()}'\n\n";

// EVIDÊNCIA 2: Busca de Livro por ID e reconstrução de propriedades
echo "[EVIDENCIA 2] Busca de Livro por ID e reconstrução de suas propriedades:\n";
/** @var Livro $livroBuscado */
$livroBuscado = $livros->buscar($livro->id());
echo "  -> Objeto recuperado: classe = " . get_class($livroBuscado) . "\n";
echo "  -> ID: {$livroBuscado->id()}\n";
echo "  -> Título: '{$livroBuscado->titulo()}'\n";
echo "  -> ISBN: '{$livroBuscado->isbn()}'\n";
echo "  -> Ano de Publicação: {$livroBuscado->anoPublicacao()}\n\n";

// EVIDÊNCIA 3: Atualização e nova consulta comprovando a alteração
echo "[EVIDENCIA 3] Atualização e nova consulta comprovando a alteração:\n";
$tituloAntigo = $livroBuscado->titulo();
$livroBuscado->alterarTitulo('Antologia Literária (Edição Revisada) ' . $tag);
$livros->salvar($livroBuscado);

/** @var Livro $livroAtualizado */
$livroAtualizado = $livros->buscar($livro->id());
echo "  -> Título anterior: '{$tituloAntigo}'\n";
echo "  -> Título após UPDATE no banco: '{$livroAtualizado->titulo()}'\n\n";

// EVIDÊNCIA 4: Associação de dois autores a um livro e recuperação da coleção
echo "[EVIDENCIA 4] Associação de dois autores a um livro e recuperação da coleção N:N:\n";
$livros->associarAutor($livro->id(), $autor1->id());
$livros->associarAutor($livro->id(), $autor2->id());

$autoresDoBanco = $livros->autoresDoLivro($livro->id());
foreach ($autoresDoBanco as $autorRecuperado) {
    $livroAtualizado->adicionarAutor($autorRecuperado);
}

echo "  -> Vínculos criados na tabela associativa livros_autores.\n";
echo "  -> Coleção reconstruída no objeto Livro (" . count($livroAtualizado->autores()) . " autores vinculados):\n";
foreach ($livroAtualizado->autores() as $a) {
    echo "     * [ID {$a->id()}] {$a->nome()} ({$a->nacionalidade()})\n";
}
echo "\n";

// EVIDÊNCIA 5: Demonstração polimórfica utilizando o mesmo contrato
echo "[EVIDENCIA 5] Demonstração polimórfica via RepositoryInterface:\n";
function demonstrarOperacaoPolimorfica(RepositoryInterface $repo, int $id): void {
    $objeto = $repo->buscar($id);
    $classe = $objeto !== null ? get_class($objeto) : 'null';
    echo "  -> Método polimórfico recebeu contrato RepositoryInterface (classe concreta: " . get_class($repo) . ")\n";
    echo "     Resultado da busca por ID {$id}: Objeto instanciado da classe '{$classe}'\n";
}

demonstrarOperacaoPolimorfica($livros, $livro->id());
demonstrarOperacaoPolimorfica($autores, $autor1->id());
echo "\n";

// EVIDÊNCIA 6: Demonstração de erro intencional em transação e rollback
echo "[EVIDENCIA 6] Demonstração de erro intencional em transação e rollback:\n";
$pdo->beginTransaction();
$idLivroRollback = null;
try {
    $livroTransacional = new Livro('Livro Que Sofrerá Rollback ' . $tag);
    $livros->salvar($livroTransacional);
    $idLivroRollback = $livroTransacional->id();
    echo "  -> Livro temporário salvo com ID {$idLivroRollback} dentro da transação aberta.\n";

    echo "  -> Forçando erro de integridade referencial (associando autor inexistente ID 999999)...\n";
    $livros->associarAutor($idLivroRollback, 999999);

    $pdo->commit();
} catch (\Throwable $e) {
    echo "  -> Exceção capturada: " . $e->getMessage() . "\n";
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
        echo "  -> Transação revertida com sucesso via rollBack()!\n";
    }
}

// Verificação de que o livro não permaneceu no banco
$verificacao = $livros->buscar($idLivroRollback);
if ($verificacao === null) {
    echo "  -> Comprovação de consistência: o Livro ID {$idLivroRollback} NÃO existe no banco de dados.\n\n";
} else {
    echo "  -> ERRO: o Livro ainda existe no banco!\n\n";
}

// EVIDÊNCIA 7: Exclusão controlada e explicação sobre restrições de chave estrangeira
echo "[EVIDENCIA 7] Exclusão controlada e explicação sobre restrições de FK:\n";
echo "  -> 1. Tentativa de exclusão do autor que possui vínculo em livros_autores:\n";
try {
    $autores->excluir($autor1->id());
    echo "     Inesperado: autor excluído sem respeitar FK.\n";
} catch (\PDOException $e) {
    echo "     Sucesso da restrição referencial: MySQL rejeitou a exclusão (código {$e->getCode()}).\n";
    echo "     Motivo: restrição de chave estrangeira (FOREIGN KEY) sem exclusão em cascata.\n";
}

echo "  -> 2. Exclusão controlada:\n";
// Remover primeiro os vínculos associativos
$stmtDeleteAssoc = $pdo->prepare('DELETE FROM livros_autores WHERE livro_id = :livro_id');
$stmtDeleteAssoc->execute(['livro_id' => $livro->id()]);
echo "     * Vínculos na tabela associativa livros_autores removidos.\n";

// Agora excluir livro e autores de forma segura
$livros->excluir($livro->id());
echo "     * Livro ID {$livro->id()} excluído com sucesso.\n";

$autores->excluir($autor1->id());
$autores->excluir($autor2->id());
echo "     * Autores IDs {$autor1->id()} e {$autor2->id()} excluídos com sucesso.\n\n";
