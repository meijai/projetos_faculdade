<?php
if (!class_exists('Produto')) {
    class Produto extends ItemBase {
        private $idCategoria;
        private $idSecaoNominal;
        private $quantidade;
        private $precoUnitario;

        public function __construct($id, $nome, $idCategoria, $idSecaoNominal = null, $quantidade = 0, $precoUnitario = 0.0) {
            // Inicializa $id e $nome na classe pai
            parent::__construct($id, $nome);
            $this->idCategoria = (int)$idCategoria;
            $this->idSecaoNominal = !empty($idSecaoNominal) ? (int)$idSecaoNominal : null;      //se 0 ou vazio, seta null
            // Garante que a quantidade nunca seja negativa na inicialização
            $this->quantidade = max(0, (int)$quantidade);
            $this->precoUnitario = (float)$precoUnitario;
        }

        public function getIdCategoria() {
            return $this->idCategoria;
        }

        public function setIdCategoria($idCategoria) {
            $this->idCategoria = (int)$idCategoria;
        }

        public function getIdSecaoNominal() {
            return $this->idSecaoNominal;
        }

        public function setIdSecaoNominal($idSecaoNominal) {
            $this->idSecaoNominal = !empty($idSecaoNominal) ? (int)$idSecaoNominal : null;
        }

        public function getQuantidade() {
            return $this->quantidade;
        }

        public function setQuantidade($quantidade) {
            $this->quantidade = max(0, (int)$quantidade);
        }

        public function getPrecoUnitario() {
            return $this->precoUnitario;
        }

        public function setPrecoUnitario($precoUnitario) {
            $this->precoUnitario = (float)$precoUnitario;
        }

        public function getNomeExibicao($secoes = []) {
            if ($this->idSecaoNominal && isset($secoes[$this->idSecaoNominal])) {
                $sec = $secoes[$this->idSecaoNominal];
                return $this->nome . " " . $sec->getNome() . $sec->getUnidadeMedida();
            }
            return $this->nome;
        }

        public function exibirDetalhes() {
            return "Material: " . $this->nome . " | Saldo: " . $this->quantidade;
        }
    }
}
