<?php
require_once __DIR__ . '/ItemBase.php';
require_once __DIR__ . '/Categoria.php';
require_once __DIR__ . '/SecaoNominal.php';
require_once __DIR__ . '/Produto.php';
require_once __DIR__ . '/Movimentacao.php';
require_once __DIR__ . '/Database.php';

if (!class_exists('Repositorio')) {
    class Repositorio {
        // Conexao ativa com o banco de dados PDO/MySQL
        private $db;

        public function __construct() {
            // se nao houver sessao, inicia uma nova
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $this->db = Database::getConexao();     // puxa a conexao da classe Database
        }

        public function getCategorias() {
            // Executa consulta ordenando os nomes de A a Z
            $stmt = $this->db->query("SELECT ID, NOME, DESCRICAO FROM CATEGORIA ORDER BY NOME ASC");
            $categorias = [];
            
            // Itera sobre as linhas do banco criando as instâncias da entidade Categoria
            while ($row = $stmt->fetch()) {
                $categorias[(int)$row['ID']] = new Categoria(
                    (int)$row['ID'],
                    $row['NOME'],
                    $row['DESCRICAO'] !== null ? $row['DESCRICAO'] : ''
                );
            }
            return $categorias;
        }

        public function salvarCategoria($nome, $descricao, $id = null) {
            if ($id) {
                // atualiza o registro do banco de dados conforme parametros passados na aplicacao web
                // fazendo a triangulacao do parametro usado no banco de dados com o usado aqui
                // ex: se no banco for "NAME" e aqui for "NOME", o parametro ":nome" faz a ponte entre os dois
                $stmt = $this->db->prepare("UPDATE CATEGORIA SET NOME = :nome, DESCRICAO = :descricao WHERE ID = :id");
                $stmt->execute([
                    ':nome' => trim($nome),
                    ':descricao' => trim($descricao),
                    ':id' => (int)$id
                ]);
            } else {
                // caso nao tenha registro, vai criar um novo no bnco
                $stmt = $this->db->prepare("INSERT INTO CATEGORIA (NOME, DESCRICAO) VALUES (:nome, :descricao)");
                $stmt->execute([
                    ':nome' => trim($nome),
                    ':descricao' => trim($descricao)
                ]);
            }
        }

        public function excluirCategoria($idCategoria) {
            $id = (int)$idCategoria;

            // verifica se existe algum produto vinculado a esta categoria
            $stmtCheck = $this->db->prepare("SELECT NOME FROM PRODUTO WHERE ID_CATEGORIA = :id LIMIT 1");
            $stmtCheck->execute([':id' => $id]);
            $prod = $stmtCheck->fetch();
            
            if ($prod) {
                return "Não é possível excluir a categoria pois há materiais cadastrados nela (ex: '{$prod['NOME']}').";
            }

            // realiza a exclusao segura no banco
            $stmt = $this->db->prepare("DELETE FROM CATEGORIA WHERE ID = :id");
            $stmt->execute([':id' => $id]);
            return true;
        }

        public function getSecoes() {
            // CAST do nome para DECIMAL garante ordenacao numerica correta
            // ex: 2.5 antes de 10
            $stmt = $this->db->query("SELECT ID, NOME, UNIDADE_MEDIDA FROM SECAO_NOMINAL ORDER BY CAST(NOME AS DECIMAL(10,2)) ASC, ID ASC");
            $secoes = [];
            
            while ($row = $stmt->fetch()) {
                $secoes[(int)$row['ID']] = new SecaoNominal(
                    (int)$row['ID'],
                    $row['NOME'],
                    $row['UNIDADE_MEDIDA']
                );
            }
            return $secoes;
        }

        public function salvarSecao($nome, $unidadeMedida, $id = null) {
            $nomeLimpo = trim($nome);

            // Impede uso de virgula para manter compatibilidade com padrao decimal
            if (strpos($nomeLimpo, ',') !== false) {
                return "Formato inválido! Não use vírgula (,). Utilize ponto (.) para valores decimais (ex: 2.5).";
            }

            // Deve ser um numero positivo
            if (!is_numeric($nomeLimpo) || (float)$nomeLimpo <= 0) {
                return "O valor da seção deve ser um número positivo (ex: 2.5 ou 4).";
            }

            // Normaliza o numero 
            // ex: 4.0 -> 4
            $nomeNormalizado = SecaoNominal::formatarValor($nomeLimpo);

            // Previne duplicacao de mesma bitola com mesma unidade
            if ($id) {
                $stmtDup = $this->db->prepare("SELECT ID FROM SECAO_NOMINAL WHERE NOME = :nome AND UNIDADE_MEDIDA = :unidade AND ID != :id");
                $stmtDup->execute([':nome' => $nomeNormalizado, ':unidade' => $unidadeMedida, ':id' => (int)$id]);
            } else {
                $stmtDup = $this->db->prepare("SELECT ID FROM SECAO_NOMINAL WHERE NOME = :nome AND UNIDADE_MEDIDA = :unidade");
                $stmtDup->execute([':nome' => $nomeNormalizado, ':unidade' => $unidadeMedida]);
            }

            if ($stmtDup->fetch()) {
                return "A seção nominal '{$nomeNormalizado}{$unidadeMedida}' já está cadastrada!";
            }

            // Persiste no banco 
            // UPDATE se for edicao, INSERT se for novo
            if ($id) {
                $stmt = $this->db->prepare("UPDATE SECAO_NOMINAL SET NOME = :nome, UNIDADE_MEDIDA = :unidade WHERE ID = :id");
                $stmt->execute([
                    ':nome' => $nomeNormalizado,
                    ':unidade' => $unidadeMedida,
                    ':id' => (int)$id
                ]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO SECAO_NOMINAL (NOME, UNIDADE_MEDIDA) VALUES (:nome, :unidade)");
                $stmt->execute([
                    ':nome' => $nomeNormalizado,
                    ':unidade' => $unidadeMedida
                ]);
            }

            return true;
        }

        public function excluirSecao($idSecao) {
            $id = (int)$idSecao;

            // Checa se algum material elétrico utiliza esta bitola
            $stmtCheck = $this->db->prepare("SELECT NOME FROM PRODUTO WHERE ID_SECAO_NOMINAL = :id LIMIT 1");
            $stmtCheck->execute([':id' => $id]);
            $prod = $stmtCheck->fetch();
            
            if ($prod) {
                return "Não é possível excluir esta seção nominal pois ela está associada ao produto '{$prod['NOME']}'.";
            }

            // Exclui a seção nominal
            $stmt = $this->db->prepare("DELETE FROM SECAO_NOMINAL WHERE ID = :id");
            $stmt->execute([':id' => $id]);
            return true;
        }

        public function getProdutos() {
            $stmt = $this->db->query("SELECT ID, NOME, ID_CATEGORIA, ID_SECAO_NOMINAL, QUANTIDADE, PRECO_UNITARIO FROM PRODUTO ORDER BY ID ASC");
            $produtos = [];
            
            while ($row = $stmt->fetch()) {
                $produtos[(int)$row['ID']] = new Produto(
                    (int)$row['ID'],
                    $row['NOME'],
                    (int)$row['ID_CATEGORIA'],
                    $row['ID_SECAO_NOMINAL'] !== null ? (int)$row['ID_SECAO_NOMINAL'] : null,
                    (int)$row['QUANTIDADE'],
                    (float)$row['PRECO_UNITARIO']
                );
            }
            return $produtos;
        }

        public function salvarProduto($nome, $idCategoria, $idSecao, $preco, $id = null) {
            $secaoValida = !empty($idSecao) ? (int)$idSecao : null;
            $precoFormatado = (float)$preco;

            if ($id) {
                // Atualiza apenas os metadados do produto, sem alterar diretamente o saldo
                $stmt = $this->db->prepare("UPDATE PRODUTO SET NOME = :nome, ID_CATEGORIA = :cat, ID_SECAO_NOMINAL = :sec, PRECO_UNITARIO = :preco WHERE ID = :id");
                $stmt->execute([
                    ':nome' => trim($nome),
                    ':cat' => (int)$idCategoria,
                    ':sec' => $secaoValida,
                    ':preco' => $precoFormatado,
                    ':id' => (int)$id
                ]);
            } else {
                // Insere o novo produto com saldo inicial zerado (0)
                $stmt = $this->db->prepare("INSERT INTO PRODUTO (NOME, ID_CATEGORIA, ID_SECAO_NOMINAL, QUANTIDADE, PRECO_UNITARIO) VALUES (:nome, :cat, :sec, 0, :preco)");
                $stmt->execute([
                    ':nome' => trim($nome),
                    ':cat' => (int)$idCategoria,
                    ':sec' => $secaoValida,
                    ':preco' => $precoFormatado
                ]);
            }
        }

        public function excluirProduto($idProduto) {
            $id = (int)$idProduto;
            
            // Busca o produto e seu saldo atual
            $stmt = $this->db->prepare("SELECT NOME, QUANTIDADE FROM PRODUTO WHERE ID = :id");
            $stmt->execute([':id' => $id]);
            $prod = $stmt->fetch();
            if (!$prod) {
                return false;
            }

            // Regra 1: Bloqueia exclusão de produto com saldo em estoque
            if ((int)$prod['QUANTIDADE'] > 0) {
                return "Não é possível excluir o produto '{$prod['NOME']}' enquanto houver saldo em estoque ({$prod['QUANTIDADE']} un.). Zere o saldo antes de excluir.";
            }

            // Regra 2: Bloqueia exclusão de produto que possui movimentações passadas no histórico
            $stmtMov = $this->db->prepare("SELECT COUNT(*) FROM MOVIMENTACAO WHERE ID_PRODUTO = :id");
            $stmtMov->execute([':id' => $id]);
            if ((int)$stmtMov->fetchColumn() > 0) {
                return "Não é possível excluir o produto '{$prod['NOME']}' pois existem registros de movimentação associados a ele no histórico.";
            }

            // Produto atende aos critérios de segurança: executa exclusão
            $stmtDel = $this->db->prepare("DELETE FROM PRODUTO WHERE ID = :id");
            $stmtDel->execute([':id' => $id]);
            return true;
        }

        public function getMovimentacoes() {
            $stmt = $this->db->query("SELECT ID, ID_PRODUTO, TIPO, QUANTIDADE, MOTIVO, DATA_HORA FROM MOVIMENTACAO ORDER BY DATA_HORA DESC, ID DESC");
            $movimentacoes = [];
            
            while ($row = $stmt->fetch()) {
                $movimentacoes[(int)$row['ID']] = new Movimentacao(
                    (int)$row['ID'],
                    (int)$row['ID_PRODUTO'],
                    $row['TIPO'],
                    (int)$row['QUANTIDADE'],
                    $row['MOTIVO'],
                    $row['DATA_HORA']
                );
            }
            return $movimentacoes;
        }

        public function registrarMovimentacao($idProduto, $tipo, $quantidade, $motivo) {
            $id = (int)$idProduto;
            $qtd = (int)$quantidade;
            $tipo = strtoupper(trim($tipo));

            // Inicia a transação atômica
            $this->db->beginTransaction();
            try {
                // Bloqueia a linha do produto no banco para leitura e escrita concorrente segura
                $stmt = $this->db->prepare("SELECT QUANTIDADE FROM PRODUTO WHERE ID = :id FOR UPDATE");
                $stmt->execute([':id' => $id]);
                $prod = $stmt->fetch();
                
                if (!$prod) {
                    $this->db->rollBack();
                    return "Produto selecionado não existe.";
                }

                $saldoAtual = (int)$prod['QUANTIDADE'];

                // Regra de Validação de Saída: Impede que o saldo fique negativo
                if ($tipo === 'SAIDA' && $saldoAtual < $qtd) {
                    $this->db->rollBack();
                    return "Saldo insuficiente! Saldo atual: {$saldoAtual} un.";
                }

                // Calcula o novo saldo com base no tipo de movimentação
                $novoSaldo = ($tipo === 'ENTRADA') ? ($saldoAtual + $qtd) : ($saldoAtual - $qtd);

                // 1. Atualiza o saldo físico na tabela PRODUTO
                $stmtUpd = $this->db->prepare("UPDATE PRODUTO SET QUANTIDADE = :saldo WHERE ID = :id");
                $stmtUpd->execute([':saldo' => $novoSaldo, ':id' => $id]);

                // 2. Cria o registro de auditoria na tabela MOVIMENTACAO com a data e hora atual do servidor MySQL (NOW())
                $stmtMov = $this->db->prepare("INSERT INTO MOVIMENTACAO (ID_PRODUTO, TIPO, QUANTIDADE, MOTIVO, DATA_HORA) VALUES (:id_prod, :tipo, :qtd, :motivo, NOW())");
                $stmtMov->execute([
                    ':id_prod' => $id,
                    ':tipo' => $tipo,
                    ':qtd' => $qtd,
                    ':motivo' => trim($motivo)
                ]);

                // Confirma todas as operações da transação
                $this->db->commit();
                return true;
            } catch (Exception $e) {
                // Em caso de falha inesperada, desfaz qualquer alteração pendente
                $this->db->rollBack();
                return "Erro ao registrar movimentação: " . $e->getMessage();
            }
        }
 function editarMovimentacao($idMovimentacao, $idNovoProduto, $novoTipo, $novaQuantidade, $novoMotivo) {
            $idMov = (int)$idMovimentacao;
            $idNovoProd = (int)$idNovoProduto;
            $novoTipo = strtoupper(trim($novoTipo));
            $novaQtd = (int)$novaQuantidade;

            $this->db->beginTransaction();
            try {
                // 1. Carrega os dados originais da movimentação sob bloqueio
                $stmtMov = $this->db->prepare("SELECT ID, ID_PRODUTO, TIPO, QUANTIDADE FROM MOVIMENTACAO WHERE ID = :id FOR UPDATE");
                $stmtMov->execute([':id' => $idMov]);
                $mov = $stmtMov->fetch();
                if (!$mov) {
                    $this->db->rollBack();
                    return "Movimentação não encontrada.";
                }

                $idAntigoProd = (int)$mov['ID_PRODUTO'];
                $antigoTipo = $mov['TIPO'];
                $antigaQtd = (int)$mov['QUANTIDADE'];

                // 2. Carrega o produto antigo sob bloqueio para reverter o saldo prévio
                $stmtProdAntigo = $this->db->prepare("SELECT QUANTIDADE FROM PRODUTO WHERE ID = :id FOR UPDATE");
                $stmtProdAntigo->execute([':id' => $idAntigoProd]);
                $prodAntigo = $stmtProdAntigo->fetch();
                $saldoAntigo = $prodAntigo ? (int)$prodAntigo['QUANTIDADE'] : 0;

                // Reversão do efeito da movimentação antiga:
                // Se era ENTRADA, remove do estoque; se era SAÍDA, devolve ao estoque.
                if ($antigoTipo === 'ENTRADA') {
                    $saldoRevertidoAntigo = $saldoAntigo - $antigaQtd;
                    if ($saldoRevertidoAntigo < 0) {
                        $this->db->rollBack();
                        return "Não é possível alterar: a remoção do saldo desta entrada deixaria o estoque negativo.";
                    }
                } else {
                    $saldoRevertidoAntigo = $saldoAntigo + $antigaQtd;
                }

                // 3. Caso o produto tenha sido trocado na edição:
                if ($idAntigoProd !== $idNovoProd) {
                    // Grava o saldo do produto antigo revertido
                    $stmtUpdAntigo = $this->db->prepare("UPDATE PRODUTO SET QUANTIDADE = :saldo WHERE ID = :id");
                    $stmtUpdAntigo->execute([':saldo' => $saldoRevertidoAntigo, ':id' => $idAntigoProd]);

                    // Bloqueia e lê o saldo do novo produto selecionado
                    $stmtProdNovo = $this->db->prepare("SELECT QUANTIDADE FROM PRODUTO WHERE ID = :id FOR UPDATE");
                    $stmtProdNovo->execute([':id' => $idNovoProd]);
                    $prodNovo = $stmtProdNovo->fetch();
                    if (!$prodNovo) {
                        $this->db->rollBack();
                        return "Produto selecionado não existe.";
                    }
                    $saldoBaseNovo = (int)$prodNovo['QUANTIDADE'];
                } else {
                    $saldoBaseNovo = $saldoRevertidoAntigo;
                }

                // 4. Aplica os novos valores da movimentação no produto de destino
                if ($novoTipo === 'SAIDA') {
                    if ($saldoBaseNovo < $novaQtd) {
                        $this->db->rollBack();
                        return "Saldo insuficiente para a saída! Saldo disponível no momento: {$saldoBaseNovo} un.";
                    }
                    $saldoFinalNovo = $saldoBaseNovo - $novaQtd;
                } else {
                    $saldoFinalNovo = $saldoBaseNovo + $novaQtd;
                }

                // Atualiza o saldo do produto de destino
                $stmtUpdNovo = $this->db->prepare("UPDATE PRODUTO SET QUANTIDADE = :saldo WHERE ID = :id");
                $stmtUpdNovo->execute([':saldo' => $saldoFinalNovo, ':id' => $idNovoProd]);

                // Atualiza a tabela de movimentação com os novos dados
                $stmtUpdMov = $this->db->prepare("UPDATE MOVIMENTACAO SET ID_PRODUTO = :id_prod, TIPO = :tipo, QUANTIDADE = :qtd, MOTIVO = :motivo WHERE ID = :id");
                $stmtUpdMov->execute([
                    ':id_prod' => $idNovoProd,
                    ':tipo' => $novoTipo,
                    ':qtd' => $novaQtd,
                    ':motivo' => trim($novoMotivo),
                    ':id' => $idMov
                ]);

                $this->db->commit();
                return true;
            } catch (Exception $e) {
                $this->db->rollBack();
                return "Erro ao editar movimentação: " . $e->getMessage();
            }
        }

        public function removerMovimentacao($idMovimentacao) {
            $idMov = (int)$idMovimentacao;

            $this->db->beginTransaction();
            try {
                // Bloqueia e busca a movimentação a ser estornada
                $stmtMov = $this->db->prepare("SELECT ID, ID_PRODUTO, TIPO, QUANTIDADE FROM MOVIMENTACAO WHERE ID = :id FOR UPDATE");
                $stmtMov->execute([':id' => $idMov]);
                $mov = $stmtMov->fetch();
                if (!$mov) {
                    $this->db->rollBack();
                    return false;
                }

                $idProd = (int)$mov['ID_PRODUTO'];
                $tipo = $mov['TIPO'];
                $qtd = (int)$mov['QUANTIDADE'];

                // Bloqueia e busca o produto vinculado
                $stmtProd = $this->db->prepare("SELECT QUANTIDADE FROM PRODUTO WHERE ID = :id FOR UPDATE");
                $stmtProd->execute([':id' => $idProd]);
                $prod = $stmtProd->fetch();

                if ($prod) {
                    $saldoAtual = (int)$prod['QUANTIDADE'];

                    // Regra de estorno:
                    if ($tipo === 'ENTRADA') {
                        // Se remove uma entrada, os itens são subtraídos do saldo
                        if ($saldoAtual < $qtd) {
                            $this->db->rollBack();
                            return "Não é possível remover esta entrada pois os itens já foram utilizados e o saldo ficaria negativo.";
                        }
                        $novoSaldo = $saldoAtual - $qtd;
                    } else {
                        // Se remove uma saída, os itens voltam para o estoque
                        $novoSaldo = $saldoAtual + $qtd;
                    }

                    // Atualiza o saldo estornado no produto
                    $stmtUpd = $this->db->prepare("UPDATE PRODUTO SET QUANTIDADE = :saldo WHERE ID = :id");
                    $stmtUpd->execute([':saldo' => $novoSaldo, ':id' => $idProd]);
                }

                // Exclui o registro da tabela MOVIMENTACAO
                $stmtDel = $this->db->prepare("DELETE FROM MOVIMENTACAO WHERE ID = :id");
                $stmtDel->execute([':id' => $idMov]);

                $this->db->commit();
                return true;
            } catch (Exception $e) {
                $this->db->rollBack();
                return "Erro ao remover movimentação: " . $e->getMessage();
            }
        }
    }
}
