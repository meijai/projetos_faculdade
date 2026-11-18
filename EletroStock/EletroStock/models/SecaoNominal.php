<?php
if (!class_exists('SecaoNominal')) {
    class SecaoNominal extends ItemBase {
        private $unidadeMedida;

        public function __construct($id, $nome, $unidadeMedida = 'mm²') {
            // Formata e limpa o valor da bitola antes de repassar para a classe pai
            $nomeFormatado = self::formatarValor($nome);
            parent::__construct($id, $nomeFormatado);
            $this->unidadeMedida = $unidadeMedida;
        }

        public function setNome($nome) {
            $this->nome = self::formatarValor($nome);
        }

        public function getUnidadeMedida() {
            return $this->unidadeMedida;
        }

        public function setUnidadeMedida($unidadeMedida) {
            $this->unidadeMedida = $unidadeMedida;
        }

        public static function formatarValor($valor) {
            $valor = trim($valor);
            if (is_numeric($valor)) {
                $num = (float)$valor;
                // Se o número for inteiro (ex: 4.0), remove a casa decimal exibindo '4'
                if (floor($num) == $num) {
                    return (string)(int)$num;
                }
                // Se possuir parte decimal relevante (ex: 2.5), mantém o valor float
                return (string)$num;
            }
            return $valor;
        }

        public function exibirDetalhes() {
            return "Bitola / Seção: " . $this->nome . " " . $this->unidadeMedida;
        }
    }
}
