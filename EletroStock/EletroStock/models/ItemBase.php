<?php
if (!class_exists('ItemBase')) {
    abstract class ItemBase {
        // Identificador único do item (PK) e nome
        protected $id;
        protected $nome;

        public function __construct($id, $nome) {
            $this->id = $id;
            $this->nome = $nome;
        }

        public function getId() {
            return $this->id;
        }

        public function getNome() {
            return $this->nome;
        }

        public function setNome($nome) {
            $this->nome = $nome;
        }

        abstract public function exibirDetalhes();
    }
}
