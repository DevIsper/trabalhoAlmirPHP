<?php

namespace DAL;

include_once __DIR__ . "/conexao.php";
include_once __DIR__ . "/../usuario.php";

use DAL\Conexao;

class Usuario
{
    public function SelectUsuario(string $usuario) : ?\MODEL\Usuario
    {
        $sql = "Select * from USER where username = ?;";
        $con =  Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute(array($usuario));
        $linha = $query->fetch(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        if ($linha === false) {

            return null;
        }

        $usuarioObj = new \MODEL\Usuario();
        $usuarioObj->setId($linha['idUSER']);
        $usuarioObj->setUsuario($linha['USERNAME']);
        $usuarioObj->setSenha($linha['PASSWORD']);

        return $usuarioObj;
    }
}