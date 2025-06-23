<?php

namespace DAL;

include_once __DIR__ . "/conexao.php";
include_once __DIR__ . "/../movimentacaoestoque.php";
include_once __DIR__ . "/../fornecedor.php";
include_once __DIR__ . "/../estoque.php";
include_once __DIR__ . "/../internoproduto.php";

use DAL\Conexao;
use MODEL\MovimentacaoEstoque as ModelMovimentacaoEstoque;
use MODEL\Fornecedor as ModelFornecedor;
use MODEL\Estoque as ModelEstoque;
use MODEL\InternoProduto as ModelInternoProduto;

class MovimentacaoEstoque
{
    /**
     * Insere uma nova movimentação de estoque no banco de dados.
     * @param ModelMovimentacaoEstoque $movimentacao O objeto MovimentacaoEstoque a ser inserido.
     * @return bool True se a inserção for bem-sucedida, false caso contrário.
     */
    public function Insert(ModelMovimentacaoEstoque $movimentacao): bool
    {
        $sql = "INSERT INTO MOVIMENTACAO_ESTOQUE (FORNECEDOR_CNPJ, DATA_COMPRA, VALOR_TOTAL, OBSERVACAO, INTERNO_PRODUTOS_idINTERNO_PROD, idESTOQUE) VALUES (?, ?, ?, ?, ?, ?);";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $movimentacao->getFornecedorCnpj(),
            $movimentacao->getDataCompra(),
            $movimentacao->getValorTotal(),
            $movimentacao->getObservacao(),
            $movimentacao->getInternoProdutosIdInternoProd(),
            $movimentacao->getIdEstoque()
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Retorna uma movimentação de estoque pelo seu ID e data de compra.
     * @param string $dataCompra A data da compra (parte da PK).
     * @param int $idMovimentacaoEstoque O ID da movimentação (parte da PK).
     * @return ModelMovimentacaoEstoque|null O objeto MovimentacaoEstoque se encontrado, ou null se não.
     */
    public function SelectByIdAndDate(string $dataCompra, int $idMovimentacaoEstoque): ?ModelMovimentacaoEstoque
    {
        $sql = "SELECT * FROM MOVIMENTACAO_ESTOQUE WHERE DATA_COMPRA = ? AND idMOVIMENTACAO_ESTOQUE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute(array($dataCompra, $idMovimentacaoEstoque));
        $linha = $query->fetch(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        if ($linha === false) {
            return null;
        }

        $movimentacaoObj = new ModelMovimentacaoEstoque();
        $movimentacaoObj->setIdMovimentacaoEstoque($linha['idMOVIMENTACAO_ESTOQUE']);
        $movimentacaoObj->setFornecedorCnpj($linha['FORNECEDOR_CNPJ']);
        $movimentacaoObj->setDataCompra($linha['DATA_COMPRA']);
        $movimentacaoObj->setValorTotal($linha['VALOR_TOTAL']);
        $movimentacaoObj->setObservacao($linha['OBSERVACAO']);
        $movimentacaoObj->setInternoProdutosIdInternoProd($linha['INTERNO_PRODUTOS_idINTERNO_PROD']);
        $movimentacaoObj->setIdEstoque($linha['idESTOQUE']);

        return $movimentacaoObj;
    }

    /**
     * Retorna todas as movimentações de estoque.
     * Pode incluir dados relacionados (Fornecedor, Estoque, InternoProduto).
     * @param bool $incluirRelacionamentos Se true, tenta carregar objetos relacionados.
     * @return array Um array de objetos ModelMovimentacaoEstoque.
     */
    public function Select(bool $incluirRelacionamentos = false): array
    {
        $sql = "SELECT ME.*,
                       F.NOME AS FORNECEDOR_NOME,
                       E.NOME AS ESTOQUE_NOME,
                       IP.DESCRICAO AS INTERNO_PRODUTO_DESCRICAO
                FROM MOVIMENTACAO_ESTOQUE ME
                LEFT JOIN FORNECEDOR F ON ME.FORNECEDOR_CNPJ = F.CNPJ
                LEFT JOIN ESTOQUE E ON ME.idESTOQUE = E.idESTOQUE
                LEFT JOIN INTERNO_PRODUTOS IP ON ME.INTERNO_PRODUTOS_idINTERNO_PROD = IP.idINTERNO_PRODUTOS;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute();
        $registros = $query->fetchAll(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        $movimentacoes = array();
        foreach ($registros as $linha) {
            $movimentacaoObj = new ModelMovimentacaoEstoque();
            $movimentacaoObj->setIdMovimentacaoEstoque($linha['idMOVIMENTACAO_ESTOQUE']);
            $movimentacaoObj->setFornecedorCnpj($linha['FORNECEDOR_CNPJ']);
            $movimentacaoObj->setDataCompra($linha['DATA_COMPRA']);
            $movimentacaoObj->setValorTotal($linha['VALOR_TOTAL']);
            $movimentacaoObj->setObservacao($linha['OBSERVACAO']);
            $movimentacaoObj->setInternoProdutosIdInternoProd($linha['INTERNO_PRODUTOS_idINTERNO_PROD']);
            $movimentacaoObj->setIdEstoque($linha['idESTOQUE']);

            if ($incluirRelacionamentos) {
                $movimentacaoObj->fornecedorNome = $linha['FORNECEDOR_NOME'];
                $movimentacaoObj->estoqueNome = $linha['ESTOQUE_NOME'];
                $movimentacaoObj->internoProdutoDescricao = $linha['INTERNO_PRODUTO_DESCRICAO'];
            }
            $movimentacoes[] = $movimentacaoObj;
        }
        return $movimentacoes;
    }

    /**
     * Atualiza os dados de uma movimentação de estoque existente.
     * @param ModelMovimentacaoEstoque $movimentacao O objeto MovimentacaoEstoque com os dados atualizados.
     * @param string $oldDataCompra A data de compra original (parte da PK).
     * @param int $oldIdMovimentacaoEstoque O ID da movimentação original (parte da PK).
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function Update(ModelMovimentacaoEstoque $movimentacao, string $oldDataCompra, int $oldIdMovimentacaoEstoque): bool
    {
        $sql = "UPDATE MOVIMENTACAO_ESTOQUE SET FORNECEDOR_CNPJ = ?, DATA_COMPRA = ?, VALOR_TOTAL = ?, OBSERVACAO = ?, INTERNO_PRODUTOS_idINTERNO_PROD = ?, idESTOQUE = ? WHERE DATA_COMPRA = ? AND idMOVIMENTACAO_ESTOQUE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $movimentacao->getFornecedorCnpj(),
            $movimentacao->getDataCompra(),
            $movimentacao->getValorTotal(),
            $movimentacao->getObservacao(),
            $movimentacao->getInternoProdutosIdInternoProd(),
            $movimentacao->getIdEstoque(),
            $oldDataCompra,
            $oldIdMovimentacaoEstoque
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Exclui uma movimentação de estoque do banco de dados.
     * @param string $dataCompra A data da compra (parte da PK).
     * @param int $idMovimentacaoEstoque O ID da movimentação (parte da PK).
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function Delete(string $dataCompra, int $idMovimentacaoEstoque): bool
    {
        $sql = "DELETE FROM MOVIMENTACAO_ESTOQUE WHERE DATA_COMPRA = ? AND idMOVIMENTACAO_ESTOQUE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array($dataCompra, $idMovimentacaoEstoque));
        Conexao::desconectar();
        return $result;
    }
}

?>