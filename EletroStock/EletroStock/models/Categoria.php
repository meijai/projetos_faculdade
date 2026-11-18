<?php
// classe Categoria.php herdada de ItemBase

if (!class_exists('Categoria')) {       // serve para confirmar que a classe não foi definida antes de ser declarada
    class Categoria extends ItemBase {
        private $descricao;

        public function __construct($id, $nome, $descricao = '') {
            // Chama o construtor da classe pai para inicializar id e nome
            parent::__construct($id, $nome);
            $this->descricao = $descricao;
        }

        public function getDescricao() {
            return $this->descricao;
        }

        public function setDescricao($descricao) {
            $this->descricao = $descricao;
        }
        // polimorfismo: sobrescrevendo o método exibirDetalhes da classe pai
        public function exibirDetalhes() {
            return "Categoria: " . $this->nome . " - " . $this->descricao;
        }
    }
}
