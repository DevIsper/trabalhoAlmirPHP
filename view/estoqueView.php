<?php
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../view/login.php');
    exit;
}

require_once "../model/DAL/estoque.php";
require_once "../model/Estoque.php";

use DAL\Estoque;
use MODEL\Estoque as ModelEstoque;

$estoqueDAL = new Estoque();
$listaItensEstoque = $estoqueDAL->Select();

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
    <title>Estoque - Lista</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <?php if ($message): ?>
    <div class="alert alert-<?= $status ?> alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Itens em Estoque</h2>
        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovoItemEstoque">+ Novo Item</button>
            <a href="../controller/Usuario/logoutController.php" class="btn btn-outline-secondary ms-2">Sair</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Unidade</th>
                <th>Quantidade</th>
                <th>Preço Venda</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($listaItensEstoque as $item): ?>
                <tr>
                    <td><?= $item->getIdEstoque(); ?></td>
                    <td><?= $item->getNome(); ?></td>
                    <td><?= $item->getTipoEstoque(); ?></td>
                    <td><?= $item->getUnidadeMedida(); ?></td>
                    <td><?= number_format($item->getQuantidade(), 2, ',', '.'); ?></td> <td>R$ <?= number_format($item->getPrecoVenda(), 2, ',', '.'); ?></td> <td class="text-center">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $item->getIdEstoque(); ?>">
                            Editar
                        </button>
                        <a href="../controller/Estoque/excluirEstoque.php?id=<?= $item->getIdEstoque(); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este item do estoque?')">Excluir</a>
                    </td>
                </tr>

                <div class="modal fade" id="modalEditar<?= $item->getIdEstoque(); ?>" tabindex="-1" aria-labelledby="tituloEditar<?= $item->getIdEstoque(); ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content shadow-lg rounded">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="tituloEditar<?= $item->getIdEstoque(); ?>">Editar Item de Estoque</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <form action="../controller/Estoque/processaEstoque.php" method="POST" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" name="idEstoque" value="<?= $item->getIdEstoque(); ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">ID (Não Editável)</label>
                                        <input type="text" class="form-control" value="<?= $item->getIdEstoque(); ?>" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nome</label>
                                        <input type="text" name="nome" class="form-control" value="<?= $item->getNome(); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite o nome do item.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Estoque</label>
                                        <input type="text" name="tipoEstoque" class="form-control" value="<?= $item->getTipoEstoque(); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Unidade de Medida</label>
                                        <input type="text" name="unidadeMedida" class="form-control" value="<?= $item->getUnidadeMedida(); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Quantidade</label>
                                        <input type="text" name="quantidade" class="form-control decimal-mask" value="<?= number_format($item->getQuantidade(), 2, ',', '.'); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite a quantidade.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Preço de Venda</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="text" name="precoVenda" class="form-control decimal-mask" value="<?= number_format($item->getPrecoVenda(), 2, ',', '.'); ?>" required>
                                            <div class="invalid-feedback">
                                                Por favor, digite o preço de venda.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (count($listaItensEstoque) === 0): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Nenhum item em estoque encontrado.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalNovoItemEstoque" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="tituloModal">Novo Item de Estoque</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="../controller/Estoque/processaEstoque.php" method="POST" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome" class="form-control" required>
                        <div class="invalid-feedback">
                            Por favor, digite o nome do item.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Estoque</label>
                        <input type="text" name="tipoEstoque" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unidade de Medida</label>
                        <input type="text" name="unidadeMedida" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantidade</label>
                        <input type="text" name="quantidade" class="form-control decimal-mask" required>
                        <div class="invalid-feedback">
                            Por favor, digite a quantidade.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preço de Venda</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" name="precoVenda" class="form-control decimal-mask" required>
                            <div class="invalid-feedback">
                                Por favor, digite o preço de venda.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
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

        $('.decimal-mask').mask('000.000.000.000,00', {reverse: true, placeholder: "0,00"});

        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('.telefone-mask').mask(SPMaskBehavior, spOptions);


        $('.cnpj-mask').mask('00.000.000/0000-00', {reverse: true});
        

        $('.cep-mask').mask('00000-000');
    });
</script>

</body>
</html>