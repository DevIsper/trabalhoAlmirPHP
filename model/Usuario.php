<?php

namespace MODEL;

class Usuario {
    private ?int $id;
    private ?string $usuario;
    private ?string $senha;

    public function __construct() {
        $this->id = null;
        $this->usuario = null;
        $this->senha = null;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getUsuario(): ?string {
        return $this->usuario;
    }

    public function setUsuario(?string $usuario): self {
        $this->usuario = $usuario;
        return $this;
    }

    public function getSenha(): ?string {
        return $this->senha;
    }

    public function setSenha(?string $senha): self {
        $this->senha = $senha;
        return $this;
    }
}