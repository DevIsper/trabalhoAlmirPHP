<?php

namespace DAL;

include_once __DIR__ . "/conexao.php";
include_once __DIR__ . "/../fornecedor.php";

use DAL\Conexao;
use MODEL\Fornecedor as ModelFornecedor;

class Fornecedor
{
    /**
     * Insere um novo fornecedor no banco de dados.
     * @param ModelFornecedor $fornecedor O objeto Fornecedor a ser inserido.
     * @return bool True se a inserção for bem-sucedida, false caso contrário.
     */
    public function Insert(ModelFornecedor $fornecedor): bool
    {
        $sql = "INSERT INTO FORNECEDOR (CNPJ, NOME, TELEFONE, EMAIL, ENDERECO) VALUES (?, ?, ?, ?, ?);";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $fornecedor->getCnpj(),
            $fornecedor->getNome(),
            $fornecedor->getTelefone(),
            $fornecedor->getEmail(),
            $fornecedor->getEndereco()
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Retorna um fornecedor pelo seu CNPJ.
     * @param int $cnpj O CNPJ do fornecedor a ser pesquisado.
     * @return ModelFornecedor|null O objeto Fornecedor se encontrado, ou null se não.
     */
    public function SelectByCnpj(int $cnpj): ?ModelFornecedor
    {
        $sql = "SELECT * FROM FORNECEDOR WHERE CNPJ = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute(array($cnpj));
        $linha = $query->fetch(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        if ($linha === false) {
            return null;
        }

        $fornecedorObj = new ModelFornecedor();
        $fornecedorObj->setCnpj($linha['CNPJ']);
        $fornecedorObj->setNome($linha['NOME']);
        $fornecedorObj->setTelefone($linha['TELEFONE']);
        $fornecedorObj->setEmail($linha['EMAIL']);
        $fornecedorObj->setEndereco($linha['ENDERECO']);

        return $fornecedorObj;
    }

    /**
     * Retorna todos os fornecedores cadastrados.
     * @return array Um array de objetos ModelFornecedor.
     */
    public function Select(): array
    {
        $sql = "SELECT * FROM FORNECEDOR;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute();
        $registros = $query->fetchAll(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        $fornecedores = array();
        foreach ($registros as $linha) {
            $fornecedorObj = new ModelFornecedor();
            $fornecedorObj->setCnpj($linha['CNPJ']);
            $fornecedorObj->setNome($linha['NOME']);
            $fornecedorObj->setTelefone($linha['TELEFONE']);
            $fornecedorObj->setEmail($linha['EMAIL']);
            $fornecedorObj->setEndereco($linha['ENDERECO']);
            $fornecedores[] = $fornecedorObj;
        }
        return $fornecedores;
    }

    /**
     * Atualiza os dados de um fornecedor existente.
     * @param ModelFornecedor $fornecedor O objeto Fornecedor com os dados atualizados.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function Update(ModelFornecedor $fornecedor): bool
    {
        $sql = "UPDATE FORNECEDOR SET NOME = ?, TELEFONE = ?, EMAIL = ?, ENDERECO = ? WHERE CNPJ = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $fornecedor->getNome(),
            $fornecedor->getTelefone(),
            $fornecedor->getEmail(),
            $fornecedor->getEndereco(),
            $fornecedor->getCnpj()
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Exclui um fornecedor do banco de dados.
     * @param int $cnpj O CNPJ do fornecedor a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function Delete(int $cnpj): bool
    {
        $sql = "DELETE FROM FORNECEDOR WHERE CNPJ = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array($cnpj));
        Conexao::desconectar();
        return $result;
    }
}

?>