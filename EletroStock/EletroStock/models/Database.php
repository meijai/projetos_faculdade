<?php

if (!class_exists('Database')) {
    class Database {
        // Guarda a única instância ativa do objeto PDO
        private static $instancia = null;

        public static function getConexao() {
            // Verifica se a conexão ainda nao foi inicializada nesta requisicao
            // por ser uma classe estatica, deve ser usado self::$instancia ao inves de this->instancia
            if (self::$instancia === null) {
                // Parâmetros de acesso ao servidor MySQL local
                $host = '127.0.0.1';
                $port = '3306';
                $dbname = 'eletrostock';
                $usuario = 'root';
                $senha = '123qwe';

                try {
                    // se a instancia for nula, cria uma nova conexão PDO com o banco de dados
                    self::$instancia = new PDO(
                        "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
                        $usuario,
                        $senha,
                        [
                            // Configura o PDO para lançar exceções em caso de erro
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            // Configura o PDO para retornar resultados como arrays associativos por padrão
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                        ]
                    );
                } catch (PDOException $e) {
                    // Em caso de erro na conexão, exibe uma mensagem de erro e encerra a execução
                    die("Erro na conexao com o banco de dados: " . $e->getMessage());
                }
            }
            
            // Retorna o objeto PDO pronto para uso
            return self::$instancia;
        }
    }
}
