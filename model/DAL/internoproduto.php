<?php

namespace MODEL;

class InternoProduto
{
    private ?int $idInternoProdutos;
    private ?string $descricao;

    public function __construct()
    {
        $this->idInternoProdutos = null;
        $this->descricao = null;
    }

    // Getters
    public function getIdInternoProdutos(): ?int
    {
        return $this->idInternoProdutos;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    // Setters
    public function setIdInternoProdutos(?int $idInternoProdutos): void
    {
        $this->idInternoProdutos = $idInternoProdutos;
    }

    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }
}

?>