<?php

namespace DAL;

include_once __DIR__ . "/conexao.php";
include_once __DIR__ . "/../internoproduto.php"; // Inclui o modelo InternoProduto

use DAL\Conexao;
use MODEL\InternoProduto as ModelInternoProduto;

class InternoProduto
{
    /**
     * Insere um novo produto interno no banco de dados.
     * @param ModelInternoProduto $internoProduto O objeto InternoProduto a ser inserido.
     * @return bool True se a inserção for bem-sucedida, false caso contrário.
     */
    public function Insert(ModelInternoProduto $internoProduto): bool
    {
        $sql = "INSERT INTO INTERNO_PRODUTOS (DESCRICAO) VALUES (?);";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $internoProduto->getDescricao()
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Retorna um produto interno pelo seu ID.
     * @param int $id O ID do produto interno a ser pesquisado.
     * @return ModelInternoProduto|null O objeto InternoProduto se encontrado, ou null se não.
     */
    public function SelectById(int $id): ?ModelInternoProduto
    {
        $sql = "SELECT * FROM INTERNO_PRODUTOS WHERE idINTERNO_PRODUTOS = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute(array($id));
        $linha = $query->fetch(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        if ($linha === false) {
            return null;
        }

        $internoProdutoObj = new ModelInternoProduto();
        $internoProdutoObj->setIdInternoProdutos($linha['idINTERNO_PRODUTOS']);
        $internoProdutoObj->setDescricao($linha['DESCRICAO']);

        return $internoProdutoObj;
    }

    /**
     * Retorna todos os produtos internos cadastrados.
     * @return array Um array de objetos ModelInternoProduto.
     */
    public function Select(): array
    {
        $sql = "SELECT * FROM INTERNO_PRODUTOS;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute();
        $registros = $query->fetchAll(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        $internoProdutos = array();
        foreach ($registros as $linha) {
            $internoProdutoObj = new ModelInternoProduto();
            $internoProdutoObj->setIdInternoProdutos($linha['idINTERNO_PRODUTOS']);
            $internoProdutoObj->setDescricao($linha['DESCRICAO']);
            $internoProdutos[] = $internoProdutoObj;
        }
        return $internoProdutos;
    }

    /**
     * Atualiza os dados de um produto interno existente.
     * @param ModelInternoProduto $internoProduto O objeto InternoProduto com os dados atualizados.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function Update(ModelInternoProduto $internoProduto): bool
    {
        $sql = "UPDATE INTERNO_PRODUTOS SET DESCRICAO = ? WHERE idINTERNO_PRODUTOS = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $internoProduto->getDescricao(),
            $internoProduto->getIdInternoProdutos()
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Exclui um produto interno do banco de dados.
     * @param int $id O ID do produto interno a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function Delete(int $id): bool
    {
        $sql = "DELETE FROM INTERNO_PRODUTOS WHERE idINTERNO_PRODUTOS = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array($id));
        Conexao::desconectar();
        return $result;
    }
}

?>