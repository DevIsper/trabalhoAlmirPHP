<?php

namespace MODEL;

class Cliente
{
    private ?int $idCliente;
    private ?string $nome;
    private ?string $telefone;
    private ?string $email;
    private ?string $endereco;
    private ?string $cidade;
    private ?string $estado;
    private ?string $cep;

    private ?string $dataCadastro; // Usar string para DATETIME/DATE do BD
    private ?string $cnpj; // ALTERADO: CNPJ agora é string

    private ?string $dataCadastro;
    private ?string $cnpj;

    public function __construct()
    {
        $this->idCliente = null;
        $this->nome = null;
        $this->telefone = null;
        $this->email = null;
        $this->endereco = null;
        $this->cidade = null;
        $this->estado = null;
        $this->cep = null;
        $this->dataCadastro = null;
        $this->cnpj = null;
    }

    // Getters
    public function getIdCliente(): ?int
    {
        return $this->idCliente;
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

    public function getCidade(): ?string
    {
        return $this->cidade;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function getCep(): ?string
    {
        return $this->cep;
    }

    public function getDataCadastro(): ?string
    {
        return $this->dataCadastro;
    }

    public function getCnpj(): ?string // ALTERADO: Retorna string

    public function getCnpj(): ?string

    {
        return $this->cnpj;
    }

    // Setters
    public function setIdCliente(?int $idCliente): void
    {
        $this->idCliente = $idCliente;
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

    public function setCidade(?string $cidade): void
    {
        $this->cidade = $cidade;
    }

    public function setEstado(?string $estado): void
    {
        $this->estado = $estado;
    }

    public function setCep(?string $cep): void
    {
        $this->cep = $cep;
    }

    public function setDataCadastro(?string $dataCadastro): void
    {
        $this->dataCadastro = $dataCadastro;
    }


    public function setCnpj(?string $cnpj): void // ALTERADO: Aceita string

    public function setCnpj(?string $cnpj): void

    {
        $this->cnpj = $cnpj;
    }
}

?>