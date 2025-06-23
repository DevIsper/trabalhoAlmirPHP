<?php
session_start();

// --- NOVO CÓDIGO PARA PROTEGER A PÁGINA ---
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Se o usuário não estiver logado, redireciona para a página de login.
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: login.php'); // Caminho para a sua tela de login, pois clienteView.php está na mesma pasta view/
    exit;
}
// --- FIM DO CÓDIGO DE PROTEÇÃO ---
require_once "../model/DAL/cliente.php";
require_once "../model/Cliente.php";

use DAL\Cliente; // Não precisa de alias aqui se não houver conflito de nomes
use MODEL\Cliente as ModelCliente; // Opcional: pode usar um alias para a classe do Model

$clienteDAL = new Cliente();
$listaClientes = $clienteDAL->Select();

// --- Lógica para exibir mensagens flash ---
$message = '';
$status = '';
if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
    $message = $_SESSION['message'];
    $status = $_SESSION['status'];
    // Limpa as variáveis de sessão para que a mensagem não apareça novamente após um refresh
    unset($_SESSION['message']);
    unset($_SESSION['status']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Clientes - Fazenda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding-top: 30px;
            padding-bottom: 30px;

            /* Adiciona imagem de fundo ao body */
            background-image: url('img/imgFundoLogin.jpg'); /* Caminho para sua imagem */
            background-size: cover; /* Cobre todo o espaço */
            background-position: center; /* Centraliza a imagem */
            background-repeat: no-repeat; /* Não repete a imagem */
            background-attachment: fixed; /* Fixa a imagem para não rolar com o conteúdo */
            position: relative; /* Necessário para o overlay */
            z-index: 0; /* Garante que o body esteja sobre o overlay de si mesmo, se houver */
        }
        body::before { /* Adiciona uma camada de overlay semi-transparente sobre a imagem de fundo do body */
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Overlay preto com 50% de opacidade */
            z-index: -1; /* Garante que o overlay esteja abaixo do conteúdo do body */
        }
        .container {
            background-color: #ffffff; /* Fundo branco para o container principal */
            border-radius: 8px; /* Cantos arredondados */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Sombra suave */
            padding: 30px; /* Espaçamento interno */
            width: 100%; /* Largura total */
            max-width: 1200px; /* Largura máxima para tabelas grandes */
        }
        .table thead {
            background-color: #2c3e50; /* Cor escura do cabeçalho da tabela */
            color: white;
        }
        .btn-primary {
            background-color: #3498db; /* Azul mais vibrante */
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .btn-success {
            background-color: #28a745; /* Verde Bootstrap padrão */
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #218838;
        }
        .btn-danger {
            background-color: #dc3545; /* Vermelho Bootstrap padrão */
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
        .modal-header.bg-primary {
            background-color: #3498db !important; /* Assegura a cor do cabeçalho do modal */
        }
        .modal-header.bg-success {
            background-color: #28a745 !important;
        }
        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%); /* Torna o X branco para fundos escuros */
        }
    </style>
</head>
<body class="bg-light">

<div class="container">

    <?php if ($message): ?>
    <div class="alert alert-<?= $status ?> alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Clientes</h2>
        <div>
            <a href="menu.php" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Voltar ao Menu
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovoCliente">
                <i class="bi bi-plus-circle"></i> Novo Cliente
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Cidade</th>
                <th>CNPJ</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($listaClientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente->getIdCliente()); ?></td>
                    <td><?= htmlspecialchars($cliente->getNome()); ?></td>
                    <td><?= htmlspecialchars($cliente->getTelefone()); ?></td>
                    <td><?= htmlspecialchars($cliente->getEmail()); ?></td>
                    <td><?= htmlspecialchars($cliente->getCidade()); ?></td>
                    <td><?= htmlspecialchars($cliente->getCnpj()); ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $cliente->getIdCliente(); ?>">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                        <a href="../controller/Cliente/excluirCliente.php?id=<?= $cliente->getIdCliente(); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
                            <i class="bi bi-trash"></i> Excluir
                        </a>
                    </td>
                </tr>

                <div class="modal fade" id="modalEditar<?= $cliente->getIdCliente(); ?>" tabindex="-1" aria-labelledby="tituloEditar<?= $cliente->getIdCliente(); ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content shadow-lg rounded">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="tituloEditar<?= $cliente->getIdCliente(); ?>">Editar Cliente</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <form action="../controller/Cliente/processaCliente.php" method="POST" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" name="idCliente" value="<?= htmlspecialchars($cliente->getIdCliente()); ?>">
                                    <input type="hidden" name="is_update" value="true"> <div class="mb-3">
                                        <label class="form-label">Nome</label>
                                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($cliente->getNome()); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite o nome.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Telefone</label>
                                        <input type="text" name="telefone" class="form-control telefone-mask" value="<?= htmlspecialchars($cliente->getTelefone()); ?>"
                                               pattern="^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$"
                                               title="Telefone inválido (Ex: (XX) XXXX-XXXX ou (XX) XXXXX-XXXX)" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite um telefone válido. (Ex: (DD) XXXX-XXXX ou (DD) XXXXX-XXXX)
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">E-mail</label>
                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($cliente->getEmail()); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite um e-mail válido.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Endereço</label>
                                        <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($cliente->getEndereco()); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Cidade</label>
                                        <input type="text" name="cidade" class="form-control" value="<?= htmlspecialchars($cliente->getCidade()); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Estado</label>
                                        <input type="text" name="estado" class="form-control" value="<?= htmlspecialchars($cliente->getEstado()); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">CEP</label>
                                        <input type="text" name="cep" class="form-control cep-mask" value="<?= htmlspecialchars($cliente->getCep()); ?>" pattern="\d{5}-?\d{3}" title="CEP inválido (formato XXXXX-XXX ou XXXXXXXX)" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite um CEP válido (Ex: 12345-678).
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Data de Cadastro</label>
                                        <input type="date" name="dataCadastro" class="form-control" value="<?= htmlspecialchars($cliente->getDataCadastro()); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">CNPJ</label>
                                        <input type="text" name="cnpj" class="form-control cnpj-mask"
                                            value="<?= htmlspecialchars($cliente->getCnpj()); ?>"
                                            title="CNPJ deve seguir o formato XX.XXX.XXX/XXXX-XX ou XXXXXXXXXXXXXX (sem validação de conteúdo)">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Salvar Alterações</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (count($listaClientes) === 0): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Nenhum cliente encontrado.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalNovoCliente" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="tituloModal">Novo Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="../controller/Cliente/processaCliente.php" method="POST" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome" class="form-control" required>
                        <div class="invalid-feedback">
                            Por favor, digite o nome.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control telefone-mask"
                               pattern="^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$"
                               title="Telefone inválido (Ex: (XX) XXXX-XXXX ou (XX) XXXXX-XXXX)" required>
                        <div class="invalid-feedback">
                            Por favor, digite um telefone válido. (Ex: (DD) XXXX-XXXX ou (DD) XXXXX-XXXX)
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" required>
                        <div class="invalid-feedback">
                            Por favor, digite um e-mail válido.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Endereço</label>
                        <input type="text" name="endereco" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cidade</label>
                        <input type="text" name="cidade" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <input type="text" name="estado" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CEP</label>
                        <input type="text" name="cep" class="form-control cep-mask" pattern="\d{5}-?\d{3}" title="CEP inválido (formato XXXXX-XXX ou XXXXXXXX)" required>
                        <div class="invalid-feedback">
                            Por favor, digite um CEP válido (Ex: 12345-678).
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data de Cadastro</label>
                        <input type="date" name="dataCadastro" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CNPJ</label>
                        <input type="text" name="cnpj" class="form-control cnpj-mask"
                               title="CNPJ deve seguir o formato XX.XXX.XXX/XXXX-XX ou XXXXXXXXXXXXXX (sem validação de conteúdo)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Salvar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()

    // Inicialização do jQuery Mask Plugin
    $(document).ready(function(){
        $('.cep-mask').mask('00000-000');
        $('.cnpj-mask').mask('00.000.000/0000-00', {reverse: true});

        // Máscara para telefone com 9º dígito opcional
        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.telefone-mask').mask(SPMaskBehavior, spOptions);
    });

</script>

</body>
</html>