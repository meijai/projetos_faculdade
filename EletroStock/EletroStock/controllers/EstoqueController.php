<?php
require_once __DIR__ . '/../models/Repositorio.php';

class EstoqueController {
    
    // Armazena a instancia do repositorio
    private $repositorio;

    // Construtor da classe, instancia o repositorio assim que o controller e criado no index.php
    public function __construct() {
        $this->repositorio = new Repositorio();
    }

    // Metodo responsavel por receber e processar todas as acoes do usuario
    public function processarAcao() {
        // Captura o parametro 'acao' da URL via GET
        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';

        // Requisicoes POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // ACAO: Salvar Categoria, serve para criar ou editar uma categoria
            if ($acao === 'salvar_categoria') {
                // Se entrar um ID null é uma edicao, senao é um cadastro
                $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

                // Envia os dados para o repositorio salvar no banco de dados (criar ou atualizar)
                $this->repositorio->salvarCategoria($_POST['nome'], $_POST['descricao'], $id);

                // Envia de volta para a lista de categorias
                header("Location: index.php?aba=categorias");
                exit;
            }

            // ACAO: Salvar Secao Nominal / Bitola, serve para criar ou editar uma secao
            if ($acao === 'salvar_secao') {
                // Se entrar um ID null é uma edicao, senao é um cadastro
                $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

                // Envia os dados para o repositorio salvar no banco de dados (criar ou atualizar)
                $res = $this->repositorio->salvarSecao($_POST['nome'], $_POST['unidade_medida'], $id);
                
                // se o retorno for diferente de 'true', significa que houve algum erro de validacao
                if ($res !== true) {
                    $_SESSION['mensagem_erro'] = $res; // Guarda o erro na sessao para exibir na tela
                }
                
                // Redireciona mantendo o ID na tela se estiver editando, ou para a listagem limpa
                header("Location: index.php?aba=secoes" . ($id ? "&editar={$id}" : ""));
                exit;
            }

            // ACAO: Salvar Produto / Material Eletrico
            if ($acao === 'salvar_produto') {
                $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

                // Passa os dados recebidos do formulario
                $this->repositorio->salvarProduto(
                    $_POST['nome'],
                    $_POST['id_categoria'],
                    $_POST['id_secao'],
                    $_POST['preco'],
                    $id
                );

                // Redireciona para a aba de produtos atualizada
                header("Location: index.php?aba=produtos");
                exit;
            }

            // ACAO: Movimentar Estoque
            if ($acao === 'movimentar') {
                $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

                if ($id) {
                    // Se possui ID, estamos EDITANDO uma movimentacao ja existente
                    // O repositorio recalcula o saldo antigo do produto e valida o novo saldo
                    $resultado = $this->repositorio->editarMovimentacao(
                        $id,
                        (int)$_POST['id_produto'],
                        $_POST['tipo'],
                        (int)$_POST['quantidade'],
                        $_POST['motivo']
                    );
                } else {
                    // Se nao possui ID, estamos REGISTRANDO uma NOVA movimentacao
                    // Se for SAIDA, valida se ha estoque suficiente antes de debitar
                    $resultado = $this->repositorio->registrarMovimentacao(
                        (int)$_POST['id_produto'],
                        $_POST['tipo'],
                        (int)$_POST['quantidade'],
                        $_POST['motivo']
                    );
                }

                // Se o retorno nao for verdadeiro, armazena a mensagem de erro
                if ($resultado !== true) {
                    $_SESSION['mensagem_erro'] = $resultado;
                    header("Location: index.php?aba=movimentacoes" . ($id ? "&editar={$id}" : ""));
                    exit;
                }

                // Se a operacao for concluida com sucesso, redireciona para a aba de movimentacoes
                header("Location: index.php?aba=movimentacoes");
                exit;
            }
        }

        // Requisicoes GET
        if ($acao === 'excluir_categoria' && isset($_GET['id'])) {
            // O repositorio impede excluir se houver produtos cadastrados nesta categoria
            $res = $this->repositorio->excluirCategoria((int)$_GET['id']);
            
            // Se retornou texto, e a mensagem de bloqueio por restricao de integridade
            if ($res !== true && is_string($res)) {
                $_SESSION['mensagem_erro'] = $res;
            }

            header("Location: index.php?aba=categorias");
            exit;
        }


        // ACAO: Excluir Produto
        if ($acao === 'excluir_produto' && isset($_GET['id'])) {
            // O repositorio impede excluir se houver movimentacoes registradas para este produto
            $res = $this->repositorio->excluirProduto((int)$_GET['id']);
            
            if ($res !== true && is_string($res)) {
                $_SESSION['mensagem_erro'] = $res;
            }

            header("Location: index.php?aba=produtos");
            exit;
        }

        // ACAO: Excluir Secao Nominal / Bitola
        if ($acao === 'excluir_secao' && isset($_GET['id'])) {
            // O repositorio impede excluir se houver produtos cadastrados utilizando esta bitola
            $res = $this->repositorio->excluirSecao((int)$_GET['id']);
            
            if ($res !== true && is_string($res)) {
                $_SESSION['mensagem_erro'] = $res;
            }

            header("Location: index.php?aba=secoes");
            exit;
        }

        // ACAO: Remover Movimentacao
        if ($acao === 'remover_movimentacao' && isset($_GET['id'])) {
            // Remove o registro do historico e estorna o saldo correspondente do produto
            $res = $this->repositorio->removerMovimentacao((int)$_GET['id']);
            
            if ($res !== true && is_string($res)) {
                $_SESSION['mensagem_erro'] = $res;
            }

            header("Location: index.php?aba=movimentacoes");
            exit;
        }
    }

    // Metodo responsavel por carregar a view correspondente a aba selecionada
    public function carregarView($aba) {
        // 1. Busca todos os dados da sessao/repositorio para disponibilizar para as telas
        $categorias = $this->repositorio->getCategorias();
        $secoes = $this->repositorio->getSecoes();
        $produtos = $this->repositorio->getProdutos();
        $movimentacoes = $this->repositorio->getMovimentacoes();

        // 2. Inclui o cabecalho padrao da aplicacao (topo da pagina HTML e menu)
        require_once __DIR__ . '/../views/header.php';

        // 3. Seleciona dinamicamente qual arquivo de visualizacao carregar com base na aba ativa
        switch ($aba) {
            case 'categorias':
                require_once __DIR__ . '/../views/categorias.php';
                break;
            case 'secoes':
                require_once __DIR__ . '/../views/secoes.php';
                break;
            case 'produtos':
                require_once __DIR__ . '/../views/produtos.php';
                break;
            case 'movimentacoes':
                require_once __DIR__ . '/../views/movimentacoes.php';
                break;
            case 'historico':
                require_once __DIR__ . '/../views/historico.php';
                break;
            case 'estoque':
            default:
                require_once __DIR__ . '/../views/estoque.php';
                break;
        }

        // 4. Inclui o rodape padrao da aplicacao (fechamento do HTML)
        require_once __DIR__ . '/../views/footer.php';
    }
}
