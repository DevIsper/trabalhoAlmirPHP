<?php

namespace MODEL;

class Estoque
{
    private ?int $idEstoque;
    private ?string $nome;
    private ?string $tipoEstoque;
    private ?string $unidadeMedida;
    private ?float $quantidade; // DECIMAL(10,2) no BD
    private ?float $precoVenda; // DECIMAL(10,2) no BD

    public function __construct()
    {
        $this->idEstoque = null;
        $this->nome = null;
        $this->tipoEstoque = null;
        $this->unidadeMedida = null;
        $this->quantidade = null;
        $this->precoVenda = null;
    }

    // Getters
    public function getIdEstoque(): ?int
    {
        return $this->idEstoque;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getTipoEstoque(): ?string
    {
        return $this->tipoEstoque;
    }

    public function getUnidadeMedida(): ?string
    {
        return $this->unidadeMedida;
    }

    public function getQuantidade(): ?float
    {
        return $this->quantidade;
    }

    public function getPrecoVenda(): ?float
    {
        return $this->precoVenda;
    }

    // Setters
    public function setIdEstoque(?int $idEstoque): void
    {
        $this->idEstoque = $idEstoque;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function setTipoEstoque(?string $tipoEstoque): void
    {
        $this->tipoEstoque = $tipoEstoque;
    }

    public function setUnidadeMedida(?string $unidadeMedida): void
    {
        $this->unidadeMedida = $unidadeMedida;
    }

    public function setQuantidade(?float $quantidade): void
    {
        $this->quantidade = $quantidade;
    }

    public function setPrecoVenda(?float $precoVenda): void
    {
        $this->precoVenda = $precoVenda;
    }
}

?>