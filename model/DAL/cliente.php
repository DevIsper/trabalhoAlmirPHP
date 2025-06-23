<?php

namespace DAL;

include_once __DIR__ . "/conexao.php";
include_once __DIR__ . "/../cliente.php";

use DAL\Conexao;
use MODEL\Cliente as ModelCliente;

class Cliente
{
    /**
     * Insere um novo cliente no banco de dados.
     * @param ModelCliente $cliente O objeto Cliente a ser inserido.
     * @return bool True se a inserção for bem-sucedida, false caso contrário.
     */
    public function Insert(ModelCliente $cliente): bool
    {
        $sql = "INSERT INTO CLIENTE (NOME, TELEFONE, EMAIL, ENDERECO, CIDADE, ESTADO, CEP, DATA_CADASTRO, CNPJ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $cliente->getNome(),
            $cliente->getTelefone(),
            $cliente->getEmail(),
            $cliente->getEndereco(),
            $cliente->getCidade(),
            $cliente->getEstado(),
            $cliente->getCep(),
            $cliente->getDataCadastro(),
            $cliente->getCnpj()
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Retorna um cliente pelo seu ID.
     * @param int $id O ID do cliente a ser pesquisado.
     * @return ModelCliente|null O objeto Cliente se encontrado, ou null se não.
     */
    public function SelectById(int $id): ?ModelCliente
    {
        $sql = "SELECT * FROM CLIENTE WHERE idCLIENTE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute(array($id));
        $linha = $query->fetch(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        if ($linha === false) {
            return null;
        }

        $clienteObj = new ModelCliente();
        $clienteObj->setIdCliente($linha['idCLIENTE']);
        $clienteObj->setNome($linha['NOME']);
        $clienteObj->setTelefone($linha['TELEFONE']);
        $clienteObj->setEmail($linha['EMAIL']);
        $clienteObj->setEndereco($linha['ENDERECO']);
        $clienteObj->setCidade($linha['CIDADE']);
        $clienteObj->setEstado($linha['ESTADO']);
        $clienteObj->setCep($linha['CEP']);
        $clienteObj->setDataCadastro($linha['DATA_CADASTRO']);
        $clienteObj->setCnpj($linha['CNPJ']);

        return $clienteObj;
    }

    /**
     * Retorna todos os clientes cadastrados.
     * @return array Um array de objetos ModelCliente.
     */
    public function Select(): array
    {
        $sql = "SELECT * FROM CLIENTE;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute();
        $registros = $query->fetchAll(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        $clientes = array();
        foreach ($registros as $linha) {
            $clienteObj = new ModelCliente();
            $clienteObj->setIdCliente($linha['idCLIENTE']);
            $clienteObj->setNome($linha['NOME']);
            $clienteObj->setTelefone($linha['TELEFONE']);
            $clienteObj->setEmail($linha['EMAIL']);
            $clienteObj->setEndereco($linha['ENDERECO']);
            $clienteObj->setCidade($linha['CIDADE']);
            $clienteObj->setEstado($linha['ESTADO']);
            $clienteObj->setCep($linha['CEP']);
            $clienteObj->setDataCadastro($linha['DATA_CADASTRO']);
            $clienteObj->setCnpj($linha['CNPJ']);
            $clientes[] = $clienteObj;
        }
        return $clientes;
    }

    /**
     * Atualiza os dados de um cliente existente.
     * @param ModelCliente $cliente O objeto Cliente com os dados atualizados.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function Update(ModelCliente $cliente): bool
    {
        $sql = "UPDATE CLIENTE SET NOME = ?, TELEFONE = ?, EMAIL = ?, ENDERECO = ?, CIDADE = ?, ESTADO = ?, CEP = ?, DATA_CADASTRO = ?, CNPJ = ? WHERE idCLIENTE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $cliente->getNome(),
            $cliente->getTelefone(),
            $cliente->getEmail(),
            $cliente->getEndereco(),
            $cliente->getCidade(),
            $cliente->getEstado(),
            $cliente->getCep(),
            $cliente->getDataCadastro(),
            $cliente->getCnpj(),
            $cliente->getIdCliente()
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Exclui um cliente do banco de dados.
     * @param int $id O ID do cliente a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function Delete(int $id): bool
    {
        $sql = "DELETE FROM CLIENTE WHERE idCLIENTE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array($id));
        Conexao::desconectar();
        return $result;
    }
}

?>