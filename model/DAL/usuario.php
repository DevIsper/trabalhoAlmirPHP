<?php

namespace DAL;


include_once __DIR__ . "/conexao.php";
include_once __DIR__ . "/../usuario.php";

use DAL\Conexao;
use MODEL\Usuario as ModelUsuario;

class Usuario
{

    /**
     * Insere um novo usuário no banco de dados.
     * @param ModelUsuario $usuario O objeto Usuario a ser inserido.
     * @return bool True se a inserção for bem-sucedida, false caso contrário.
     */
    public function Insert(ModelUsuario $usuario): bool
    {
        $sql = "INSERT INTO USUARIO (USERNAME, PASSWORD) VALUES (?, ?);";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array($usuario->getUsuario(), $usuario->getSenha()));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Retorna um usuário pelo seu nome de usuário.
     * @param string $username O nome de usuário a ser pesquisado.
     * @return ModelUsuario|null O objeto Usuario se encontrado, ou null se não.
     */
    public function SelectUsuario(string $username): ?ModelUsuario
    {
        $sql = "SELECT * FROM USUARIO WHERE USERNAME = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute(array($username));
        $linha = $query->fetch(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        if ($linha === false) {
            return null;
        }

        $usuarioObj = new ModelUsuario();
        $usuarioObj->setId($linha['idUSER']);
        $usuarioObj->setUsuario($linha['USERNAME']);
        $usuarioObj->setSenha($linha['PASSWORD']);

        return $usuarioObj;
    }

    /**
     * Retorna um usuário pelo seu ID.
     * @param int $id O ID do usuário a ser pesquisado.
     * @return ModelUsuario|null O objeto Usuario se encontrado, ou null se não.
     */
    public function SelectById(int $id): ?ModelUsuario
    {
        $sql = "SELECT * FROM USUARIO WHERE idUSER = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute(array($id));
        $linha = $query->fetch(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        if ($linha === false) {
            return null;
        }

        $usuarioObj = new ModelUsuario();
        $usuarioObj->setId($linha['idUSER']);
        $usuarioObj->setUsuario($linha['USERNAME']);
        $usuarioObj->setSenha($linha['PASSWORD']);

        return $usuarioObj;
    }

    /**
     * Retorna todos os usuários cadastrados.
     * @return array Um array de objetos ModelUsuario.
     */
    public function Select(): array
    {
        $sql = "SELECT * FROM USUARIO;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute();
        $registros = $query->fetchAll(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        $usuarios = array();
        foreach ($registros as $linha) {
            $usuarioObj = new ModelUsuario();
            $usuarioObj->setId($linha['idUSER']);
            $usuarioObj->setUsuario($linha['USERNAME']);
            $usuarioObj->setSenha($linha['PASSWORD']);
            $usuarios[] = $usuarioObj;
        }
        return $usuarios;
    }

    /**
     * Atualiza os dados de um usuário existente.
     * @param ModelUsuario $usuario O objeto Usuario com os dados atualizados.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function Update(ModelUsuario $usuario): bool
    {
        $sql = "UPDATE USUARIO SET USERNAME = ?, PASSWORD = ? WHERE idUSER = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array($usuario->getUsuario(), $usuario->getSenha(), $usuario->getId()));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Exclui um usuário do banco de dados.
     * @param int $id O ID do usuário a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function Delete(int $id): bool
    {
        $sql = "DELETE FROM USUARIO WHERE idUSER = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array($id));
        Conexao::desconectar();
        return $result;
    }
}

?>