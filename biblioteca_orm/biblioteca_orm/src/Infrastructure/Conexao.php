<?php
namespace Biblioteca\Infrastructure;

final class Conexao {
    public static function abrir(): \PDO {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $nome = getenv('DB_NAME') ?: 'biblioteca_orm';
        $usuario = getenv('DB_USER') ?: 'biblioteca';
        $senha = getenv('DB_PASS') ?: '';

        $opcoes = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            return new \PDO("mysql:host=$host;dbname=$nome;charset=utf8mb4", $usuario, $senha, $opcoes);
        } catch (\PDOException $e) {
            // Fallback automático para root do XAMPP caso o usuário local 'biblioteca' não exista
            if ($usuario === 'biblioteca' && getenv('DB_USER') === false) {
                return new \PDO("mysql:host=$host;dbname=$nome;charset=utf8mb4", 'root', '', $opcoes);
            }
            throw $e;
        }
    }
}
