<?php
if (!class_exists('Movimentacao')) {
    class Movimentacao {
        // Primary Key, Foreign Key
        private $id;
        private $idProduto;
        private $tipo;
        private $quantidade;
        private $motivo;
        private $dataHora;

        public function __construct($id, $idProduto, $tipo, $quantidade, $motivo, $dataHora = null) {
            $this->id = (int)$id;
            $this->idProduto = (int)$idProduto;
            $this->tipo = $tipo;
            $this->quantidade = (int)$quantidade;
            $this->motivo = $motivo;
            
            // Converte a data do formato do MySQL
            $this->dataHora = $dataHora ? date('d/m/Y H:i:s', strtotime($dataHora)) : date('d/m/Y H:i:s');
        }

        public function getId() { 
            return $this->id; 
        }

        public function getIdProduto() { 
            return $this->idProduto; 
        }

        public function setIdProduto($idProduto) { 
            $this->idProduto = (int)$idProduto; 
        }

        public function getTipo() { 
            return $this->tipo; 
        }

        public function setTipo($tipo) { 
            $this->tipo = $tipo; 
        }

        public function getQuantidade() { 
            return $this->quantidade; 
        }

        public function setQuantidade($quantidade) { 
            $this->quantidade = (int)$quantidade; 
        }

        public function getMotivo() { 
            return $this->motivo; 
        }

        public function setMotivo($motivo) { 
            $this->motivo = $motivo; 
        }

        public function getDataHora() { 
            return $this->dataHora; 
        }
    }
}
