<?php
namespace DAL;

use PDO;

class Conexao {
    private static $dbHost = 'localhost';
    private static $dbNome = 'fazenda';
    private static $dbUsuario = 'root';
    private static $dbSenha = '';
    private static $cont = null;

    public static function conectar() {
        if (self::$cont == null) {
            try {
                self::$cont = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbNome, self::$dbUsuario, self::$dbSenha);
                self::$cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$cont;
    }

    public static function desconectar() {
        self::$cont = null;
        return self::$cont;
    }
}
