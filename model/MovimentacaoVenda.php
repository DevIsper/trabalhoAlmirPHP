<?php

namespace MODEL;

class MovimentacaoVenda
{
    private ?int $idCliente;
    private ?string $dataMovimentacao; // DATE no BD
    private ?float $quantidade;       // DECIMAL(10,2) no BD
    private ?string $descricao;
    private ?int $idEstoque;

    public function __construct()
    {
        $this->idCliente = null;
        $this->dataMovimentacao = null;
        $this->quantidade = null;
        $this->descricao = null;
        $this->idEstoque = null;
    }

    // Getters
    public function getIdCliente(): ?int
    {
        return $this->idCliente;
    }

    public function getDataMovimentacao(): ?string
    {
        return $this->dataMovimentacao;
    }

    public function getQuantidade(): ?float
    {
        return $this->quantidade;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function getIdEstoque(): ?int
    {
        return $this->idEstoque;
    }

    // Setters
    public function setIdCliente(?int $idCliente): void
    {
        $this->idCliente = $idCliente;
    }

    public function setDataMovimentacao(?string $dataMovimentacao): void
    {
        $this->dataMovimentacao = $dataMovimentacao;
    }

    public function setQuantidade(?float $quantidade): void
    {
        $this->quantidade = $quantidade;
    }

    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function setIdEstoque(?int $idEstoque): void
    {
        $this->idEstoque = $idEstoque;
    }
}