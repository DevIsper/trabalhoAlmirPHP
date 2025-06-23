<?php

namespace MODEL;

class MovimentacaoEstoque
{
    private ?int $idMovimentacaoEstoque;
    private ?int $fornecedorCnpj;
    private ?string $dataCompra;
    private ?float $valorTotal;
    private ?string $observacao;
    private ?int $internoProdutosIdInternoProd;
    private ?int $idEstoque;

    public function __construct()
    {
        $this->idMovimentacaoEstoque = null;
        $this->fornecedorCnpj = null;
        $this->dataCompra = null;
        $this->valorTotal = null;
        $this->observacao = null;
        $this->internoProdutosIdInternoProd = null;
        $this->idEstoque = null;
    }


    public function getIdMovimentacaoEstoque(): ?int
    {
        return $this->idMovimentacaoEstoque;
    }

    public function getFornecedorCnpj(): ?int
    {
        return $this->fornecedorCnpj;
    }

    public function getDataCompra(): ?string
    {
        return $this->dataCompra;
    }

    public function getValorTotal(): ?float
    {
        return $this->valorTotal;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function getInternoProdutosIdInternoProd(): ?int
    {
        return $this->internoProdutosIdInternoProd;
    }

    public function getIdEstoque(): ?int
    {
        return $this->idEstoque;
    }


    public function setIdMovimentacaoEstoque(?int $idMovimentacaoEstoque): void
    {
        $this->idMovimentacaoEstoque = $idMovimentacaoEstoque;
    }

    public function setFornecedorCnpj(?int $fornecedorCnpj): void
    {
        $this->fornecedorCnpj = $fornecedorCnpj;
    }

    public function setDataCompra(?string $dataCompra): void
    {
        $this->dataCompra = $dataCompra;
    }

    public function setValorTotal(?float $valorTotal): void
    {
        $this->valorTotal = $valorTotal;
    }

    public function setObservacao(?string $observacao): void
    {
        $this->observacao = $observacao;
    }

    public function setInternoProdutosIdInternoProd(?int $internoProdutosIdInternoProd): void
    {
        $this->internoProdutosIdInternoProd = $internoProdutosIdInternoProd;
    }

    public function setIdEstoque(?int $idEstoque): void
    {
        $this->idEstoque = $idEstoque;
    }
}

?>