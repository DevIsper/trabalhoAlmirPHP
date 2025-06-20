<?php

namespace DAL;

include_once __DIR__ . "/conexao.php";
include_once __DIR__ . "/../estoque.php"; // Inclui o modelo Estoque

use DAL\Conexao;
use MODEL\Estoque as ModelEstoque;

class Estoque
{
    /**
     * Insere um novo item de estoque no banco de dados.
     * @param ModelEstoque $estoque O objeto Estoque a ser inserido.
     * @return bool True se a inserção for bem-sucedida, false caso contrário.
     */
    public function Insert(ModelEstoque $estoque): bool
    {
        $sql = "INSERT INTO ESTOQUE (NOME, TIPO_ESTOQUE, UNIDADE_MEDIDA, QUANTIDADE, PRECO_VENDA) VALUES (?, ?, ?, ?, ?);";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $estoque->getNome(),
            $estoque->getTipoEstoque(),
            $estoque->getUnidadeMedida(),
            $estoque->getQuantidade(),
            $estoque->getPrecoVenda()
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Retorna um item de estoque pelo seu ID.
     * @param int $id O ID do item de estoque a ser pesquisado.
     * @return ModelEstoque|null O objeto Estoque se encontrado, ou null se não.
     */
    public function SelectById(int $id): ?ModelEstoque
    {
        $sql = "SELECT * FROM ESTOQUE WHERE idESTOQUE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute(array($id));
        $linha = $query->fetch(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        if ($linha === false) {
            return null;
        }

        $estoqueObj = new ModelEstoque();
        $estoqueObj->setIdEstoque($linha['idESTOQUE']);
        $estoqueObj->setNome($linha['NOME']);
        $estoqueObj->setTipoEstoque($linha['TIPO_ESTOQUE']);
        $estoqueObj->setUnidadeMedida($linha['UNIDADE_MEDIDA']);
        $estoqueObj->setQuantidade($linha['QUANTIDADE']);
        $estoqueObj->setPrecoVenda($linha['PRECO_VENDA']);

        return $estoqueObj;
    }

    /**
     * Retorna todos os itens de estoque cadastrados.
     * @return array Um array de objetos ModelEstoque.
     */
    public function Select(): array
    {
        $sql = "SELECT * FROM ESTOQUE;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute();
        $registros = $query->fetchAll(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        $estoques = array();
        foreach ($registros as $linha) {
            $estoqueObj = new ModelEstoque();
            $estoqueObj->setIdEstoque($linha['idESTOQUE']);
            $estoqueObj->setNome($linha['NOME']);
            $estoqueObj->setTipoEstoque($linha['TIPO_ESTOQUE']);
            $estoqueObj->setUnidadeMedida($linha['UNIDADE_MEDIDA']);
            $estoqueObj->setQuantidade($linha['QUANTIDADE']);
            $estoqueObj->setPrecoVenda($linha['PRECO_VENDA']);
            $estoques[] = $estoqueObj;
        }
        return $estoques;
    }

    /**
     * Atualiza os dados de um item de estoque existente.
     * @param ModelEstoque $estoque O objeto Estoque com os dados atualizados.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function Update(ModelEstoque $estoque): bool
    {
        $sql = "UPDATE ESTOQUE SET NOME = ?, TIPO_ESTOQUE = ?, UNIDADE_MEDIDA = ?, QUANTIDADE = ?, PRECO_VENDA = ? WHERE idESTOQUE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $estoque->getNome(),
            $estoque->getTipoEstoque(),
            $estoque->getUnidadeMedida(),
            $estoque->getQuantidade(),
            $estoque->getPrecoVenda(),
            $estoque->getIdEstoque()
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Exclui um item de estoque do banco de dados.
     * @param int $id O ID do item de estoque a ser excluído.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function Delete(int $id): bool
    {
        $sql = "DELETE FROM ESTOQUE WHERE idESTOQUE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array($id));
        Conexao::desconectar();
        return $result;
    }
}

?>