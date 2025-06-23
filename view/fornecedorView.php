<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {

    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: login.php');
    exit;
}

require_once "../model/DAL/fornecedor.php";
require_once "../model/Fornecedor.php";

use DAL\Fornecedor;
use MODEL\Fornecedor as ModelFornecedor;

$fornecedorDAL = new Fornecedor();
$listaFornecedores = $fornecedorDAL->Select();

$message = '';
$status = '';
if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
    $message = $_SESSION['message'];
    $status = $_SESSION['status'];

    unset($_SESSION['message']);
    unset($_SESSION['status']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Fornecedores - Fazenda</title>
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

            background-image: url('img/imgFundoLogin.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
            z-index: 0;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 1200px;
        }
        .table thead {
            background-color: #2c3e50;
            color: white;
        }
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #218838;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
        .modal-header.bg-primary {
            background-color: #3498db !important;
        }
        .modal-header.bg-success {
            background-color: #28a745 !important;
        }
        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
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
        <h2>Lista de Fornecedores</h2>
        <div>
            <a href="menu.php" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Voltar ao Menu
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovoFornecedor">
                <i class="bi bi-plus-circle"></i> Novo Fornecedor
            </button>
            </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>CNPJ</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Endereço</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($listaFornecedores as $fornecedor): ?>
                <tr>
                    <td><?= htmlspecialchars($fornecedor->getCnpj()); ?></td>
                    <td><?= htmlspecialchars($fornecedor->getNome()); ?></td>
                    <td><?= htmlspecialchars($fornecedor->getTelefone()); ?></td>
                    <td><?= htmlspecialchars($fornecedor->getEmail()); ?></td>
                    <td><?= htmlspecialchars($fornecedor->getEndereco()); ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $fornecedor->getCnpj(); ?>">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                        <a href="../controller/Fornecedor/excluirFornecedor.php?cnpj=<?= $fornecedor->getCnpj(); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                            <i class="bi bi-trash"></i> Excluir
                        </a>
                    </td>
                </tr>

                <div class="modal fade" id="modalEditar<?= $fornecedor->getCnpj(); ?>" tabindex="-1" aria-labelledby="tituloEditar<?= $fornecedor->getCnpj(); ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content shadow-lg rounded">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="tituloEditar<?= $fornecedor->getCnpj(); ?>">Editar Fornecedor</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <form action="../controller/Fornecedor/processaFornecedor.php" method="POST" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" name="is_update" value="true">
                                    <input type="hidden" name="cnpj" value="<?= htmlspecialchars($fornecedor->getCnpj()); ?>">
                                    <div class="mb-3">
                                        <label class="form-label">CNPJ (Não Editável)</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($fornecedor->getCnpj()); ?>" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nome</label>
                                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($fornecedor->getNome()); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite o nome.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Telefone</label>
                                        <input type="text" name="telefone" class="form-control telefone-mask" value="<?= htmlspecialchars($fornecedor->getTelefone()); ?>"
                                               pattern="^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$"
                                               title="Telefone inválido (Ex: (XX) XXXX-XXXX ou (XX) XXXXX-XXXX)" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite um telefone válido. (Ex: (DD) XXXX-XXXX ou (DD) XXXXX-XXXX)
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">E-mail</label>
                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($fornecedor->getEmail()); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite um e-mail válido.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Endereço</label>
                                        <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($fornecedor->getEndereco()); ?>">
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

            <?php if (count($listaFornecedores) === 0): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">Nenhum fornecedor encontrado.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalNovoFornecedor" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="tituloModal">Novo Fornecedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="../controller/Fornecedor/processaFornecedor.php" method="POST" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">CNPJ</label>
                        <input type="text" name="cnpj" class="form-control cnpj-mask"
                               pattern="\d{2}\.?\d{3}\.?\d{3}\/?\d{4}-?\d{2}"
                               title="CNPJ inválido (formato XX.XXX.XXX/XXXX-XX ou XXXXXXXXXXXXXX)" required>
                        <div class="invalid-feedback">
                            Por favor, digite um CNPJ válido (Ex: 12.345.678/0001-90).
                        </div>
                    </div>
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

        var forms = document.querySelectorAll('.needs-validation')

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


    $(document).ready(function(){
        $('.cnpj-mask').mask('00.000.000/0000-00', {reverse: true});

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