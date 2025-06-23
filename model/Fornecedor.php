<?php

namespace MODEL;

class Fornecedor
{
    private ?int $cnpj;
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


    public function getCnpj(): ?int
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


    public function setCnpj(?int $cnpj): void
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