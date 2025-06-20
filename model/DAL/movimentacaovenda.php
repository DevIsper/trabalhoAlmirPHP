<?php

namespace DAL;

include_once __DIR__ . "/conexao.php";
include_once __DIR__ . "/../movimentacaovenda.php"; // Inclui o modelo MovimentacaoVenda
include_once __DIR__ . "/../cliente.php"; // Necessário para joins/relacionamentos
include_once __DIR__ . "/../estoque.php"; // Necessário para joins/relacionamentos

use DAL\Conexao;
use MODEL\MovimentacaoVenda as ModelMovimentacaoVenda;
use MODEL\Cliente as ModelCliente;
use MODEL\Estoque as ModelEstoque;

class MovimentacaoVenda
{
    /**
     * Insere uma nova movimentação de venda no banco de dados.
     * @param ModelMovimentacaoVenda $movimentacao O objeto MovimentacaoVenda a ser inserido.
     * @return bool True se a inserção for bem-sucedida, false caso contrário.
     */
    public function Insert(ModelMovimentacaoVenda $movimentacao): bool
    {
        $sql = "INSERT INTO MOVIMENTACAO_VENDAS (idCLIENTE, DATA_MOVIMENTACAO, QUANTIDADE, DESCRICAO, PRODUTO_VENDA_idPRODUTO_VENDA, idESTOQUE) VALUES (?, ?, ?, ?, ?, ?);";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $movimentacao->getIdCliente(),
            $movimentacao->getDataMovimentacao(),
            $movimentacao->getQuantidade(),
            $movimentacao->getDescricao(),
            $movimentacao->getProdutoVendaIdProdutoVenda(),
            $movimentacao->getIdEstoque()
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Retorna uma movimentação de venda pela sua chave primária composta.
     * @param int $idCliente O ID do cliente (parte da PK).
     * @param string $dataMovimentacao A data da movimentação (parte da PK).
     * @param int $produtoVendaIdProdutoVenda O ID do produto de venda (parte da PK).
     * @param int $idEstoque O ID do estoque (parte da PK).
     * @return ModelMovimentacaoVenda|null O objeto MovimentacaoVenda se encontrado, ou null se não.
     */
    public function SelectByPK(int $idCliente, string $dataMovimentacao, int $produtoVendaIdProdutoVenda, int $idEstoque): ?ModelMovimentacaoVenda
    {
        $sql = "SELECT * FROM MOVIMENTACAO_VENDAS WHERE idCLIENTE = ? AND DATA_MOVIMENTACAO = ? AND PRODUTO_VENDA_idPRODUTO_VENDA = ? AND idESTOQUE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute(array($idCliente, $dataMovimentacao, $produtoVendaIdProdutoVenda, $idEstoque));
        $linha = $query->fetch(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        if ($linha === false) {
            return null;
        }

        $movimentacaoObj = new ModelMovimentacaoVenda();
        $movimentacaoObj->setIdCliente($linha['idCLIENTE']);
        $movimentacaoObj->setDataMovimentacao($linha['DATA_MOVIMENTACAO']);
        $movimentacaoObj->setQuantidade($linha['QUANTIDADE']);
        $movimentacaoObj->setDescricao($linha['DESCRICAO']);
        $movimentacaoObj->setProdutoVendaIdProdutoVenda($linha['PRODUTO_VENDA_idPRODUTO_VENDA']);
        $movimentacaoObj->setIdEstoque($linha['idESTOQUE']);

        return $movimentacaoObj;
    }

    /**
     * Retorna todas as movimentações de vendas.
     * Pode incluir dados relacionados (Cliente, Estoque).
     * @param bool $incluirRelacionamentos Se true, tenta carregar objetos relacionados.
     * @return array Um array de objetos ModelMovimentacaoVenda.
     */
    public function Select(bool $incluirRelacionamentos = false): array
    {
        $sql = "SELECT MV.*,
                       C.NOME AS CLIENTE_NOME,
                       E.NOME AS ESTOQUE_NOME
                FROM MOVIMENTACAO_VENDAS MV
                LEFT JOIN CLIENTE C ON MV.idCLIENTE = C.idCLIENTE
                LEFT JOIN ESTOQUE E ON MV.idESTOQUE = E.idESTOQUE;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $query->execute();
        $registros = $query->fetchAll(\PDO::FETCH_ASSOC);
        Conexao::desconectar();

        $movimentacoes = array();
        foreach ($registros as $linha) {
            $movimentacaoObj = new ModelMovimentacaoVenda();
            $movimentacaoObj->setIdCliente($linha['idCLIENTE']);
            $movimentacaoObj->setDataMovimentacao($linha['DATA_MOVIMENTACAO']);
            $movimentacaoObj->setQuantidade($linha['QUANTIDADE']);
            $movimentacaoObj->setDescricao($linha['DESCRICAO']);
            $movimentacaoObj->setProdutoVendaIdProdutoVenda($linha['PRODUTO_VENDA_idPRODUTO_VENDA']);
            $movimentacaoObj->setIdEstoque($linha['idESTOQUE']);

            if ($incluirRelacionamentos) {
                $movimentacaoObj->clienteNome = $linha['CLIENTE_NOME'];
                $movimentacaoObj->estoqueNome = $linha['ESTOQUE_NOME'];
            }
            $movimentacoes[] = $movimentacaoObj;
        }
        return $movimentacoes;
    }

    /**
     * Atualiza os dados de uma movimentação de venda existente.
     * @param ModelMovimentacaoVenda $movimentacao O objeto MovimentacaoVenda com os dados atualizados.
     * @param int $oldIdCliente O ID do cliente original (parte da PK).
     * @param string $oldDataMovimentacao A data da movimentação original (parte da PK).
     * @param int $oldProdutoVendaIdProdutoVenda O ID do produto de venda original (parte da PK).
     * @param int $oldIdEstoque O ID do estoque original (parte da PK).
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function Update(ModelMovimentacaoVenda $movimentacao, int $oldIdCliente, string $oldDataMovimentacao, int $oldProdutoVendaIdProdutoVenda, int $oldIdEstoque): bool
    {
        $sql = "UPDATE MOVIMENTACAO_VENDAS SET idCLIENTE = ?, DATA_MOVIMENTACAO = ?, QUANTIDADE = ?, DESCRICAO = ?, PRODUTO_VENDA_idPRODUTO_VENDA = ?, idESTOQUE = ? WHERE idCLIENTE = ? AND DATA_MOVIMENTACAO = ? AND PRODUTO_VENDA_idPRODUTO_VENDA = ? AND idESTOQUE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array(
            $movimentacao->getIdCliente(),
            $movimentacao->getDataMovimentacao(),
            $movimentacao->getQuantidade(),
            $movimentacao->getDescricao(),
            $movimentacao->getProdutoVendaIdProdutoVenda(),
            $movimentacao->getIdEstoque(),
            $oldIdCliente,
            $oldDataMovimentacao,
            $oldProdutoVendaIdProdutoVenda,
            $oldIdEstoque
        ));
        Conexao::desconectar();
        return $result;
    }

    /**
     * Exclui uma movimentação de venda do banco de dados.
     * @param int $idCliente O ID do cliente (parte da PK).
     * @param string $dataMovimentacao A data da movimentação (parte da PK).
     * @param int $produtoVendaIdProdutoVenda O ID do produto de venda (parte da PK).
     * @param int $idEstoque O ID do estoque (parte da PK).
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function Delete(int $idCliente, string $dataMovimentacao, int $produtoVendaIdProdutoVenda, int $idEstoque): bool
    {
        $sql = "DELETE FROM MOVIMENTACAO_VENDAS WHERE idCLIENTE = ? AND DATA_MOVIMENTACAO = ? AND PRODUTO_VENDA_idPRODUTO_VENDA = ? AND idESTOQUE = ?;";
        $con = Conexao::conectar();
        $query = $con->prepare($sql);
        $result = $query->execute(array($idCliente, $dataMovimentacao, $produtoVendaIdProdutoVenda, $idEstoque));
        Conexao::desconectar();
        return $result;
    }
}

?>