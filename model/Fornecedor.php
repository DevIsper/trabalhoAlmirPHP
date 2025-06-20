<?php

namespace MODEL;

class Fornecedor
{
    private ?string $cnpj;
    private ?string $nome;
    private ?string $telefone;
    private ?string $email;
    private ?string $endereco;

    public function __construct()
    {
        $this->cnpj = null;
        $this->nome = null;
        $this->telefone = null;
        $this->email = null;
        $this->endereco = null;
    }

    // Getters
    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getTelefone(): ?string
    {
        return $this->telefone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getEndereco(): ?string
    {
        return $this->endereco;
    }

    // Setters
    public function setCnpj(?string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function setTelefone(?string $telefone): void
    {
        $this->telefone = $telefone;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function setEndereco(?string $endereco): void
    {
        $this->endereco = $endereco;
    }
}

?>