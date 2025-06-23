<?php
session_start();

// Proteger a página
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: login.php'); // Caminho para a sua tela de login, pois movimentacaoEstoqueView.php está na mesma pasta view/
    exit;
}

require_once "../model/DAL/movimentacaoEstoque.php";
require_once "../model/MovimentacaoEstoque.php";
require_once "../model/DAL/fornecedor.php"; // Para popular o dropdown de fornecedores
require_once "../model/DAL/estoque.php";    // Para popular o dropdown de estoque
require_once "../model/DAL/internoproduto.php"; // Para popular o dropdown de produtos internos

use DAL\MovimentacaoEstoque;
use MODEL\MovimentacaoEstoque as ModelMovimentacaoEstoque;
use DAL\Fornecedor;
use DAL\Estoque;
use DAL\InternoProduto;

$movimentacaoEstoqueDAL = new MovimentacaoEstoque();
// Carrega as movimentações com os nomes dos relacionados
$listaMovimentacoes = $movimentacaoEstoqueDAL->Select(true);

// Instâncias das DALs para popular os dropdowns nos modais
$fornecedorDAL = new Fornecedor();
$listaFornecedores = $fornecedorDAL->Select();

$estoqueDAL = new Estoque();
$listaEstoque = $estoqueDAL->Select();

$internoProdutoDAL = new InternoProduto();
$listaInternoProdutos = $internoProdutoDAL->Select();

// Lógica para exibir mensagens flash
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
    <title>Movimentação de Estoque - Fazenda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Alinhar ao topo para permitir scroll */
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
            max-width: 1300px; /* Largura máxima para esta tabela com mais colunas */
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
        <h2>Lista de Movimentações de Estoque</h2>
        <div>
            <a href="menu.php" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Voltar ao Menu
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovaMovimentacao">
                <i class="bi bi-plus-circle"></i> Nova Movimentação
            </button>
            </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Data Compra</th>
                <th>Fornecedor</th>
                <th>Valor Total</th>
                <th>Observação</th>
                <th>Produto Interno</th>
                <th>Item Estoque</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($listaMovimentacoes as $mov): ?>
                <tr>
                    <td><?= htmlspecialchars($mov->getIdMovimentacaoEstoque()); ?></td>
                    <td><?= date('d/m/Y', strtotime($mov->getDataCompra())); ?></td>
                    <td><?= htmlspecialchars($mov->fornecedorNome ?? 'N/A'); ?></td>
                    <td>R$ <?= number_format($mov->getValorTotal(), 2, ',', '.'); ?></td>
                    <td><?= htmlspecialchars($mov->getObservacao()); ?></td>
                    <td><?= htmlspecialchars($mov->internoProdutoDescricao ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($mov->estoqueNome ?? 'N/A'); ?></td>
                    <td class="text-center">
                        <?php
                            // Crie um ID único para o modal de edição baseado na PK composta (idMovimentacaoEstoque e dataCompra)
                            // Remove caracteres não alfanuméricos da data para uso no ID HTML
                            $modalId = "modalEditar" . $mov->getIdMovimentacaoEstoque() . str_replace(['-', ' '], '', $mov->getDataCompra());
                            // Codifique os parâmetros da PK para a URL de exclusão
                            $deleteParams = "id=" . urlencode($mov->getIdMovimentacaoEstoque()) .
                                            "&data=" . urlencode($mov->getDataCompra());
                        ?>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                        <a href="../controller/MovimentacaoEstoque/excluirMovimentacaoEstoque.php?<?= $deleteParams; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta movimentação?')">
                            <i class="bi bi-trash"></i> Excluir
                        </a>
                    </td>
                </tr>

                <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="tituloEditar<?= $modalId ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content shadow-lg rounded">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="tituloEditar<?= $modalId ?>">Editar Movimentação de Estoque</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <form action="../controller/MovimentacaoEstoque/processaMovimentacaoEstoque.php" method="POST" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" name="oldIdMovimentacaoEstoque" value="<?= htmlspecialchars($mov->getIdMovimentacaoEstoque()); ?>">
                                    <input type="hidden" name="oldDataCompra" value="<?= htmlspecialchars($mov->getDataCompra()); ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">ID da Movimentação</label>
                                        <input type="number" name="idMovimentacaoEstoque" class="form-control" value="<?= htmlspecialchars($mov->getIdMovimentacaoEstoque()); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite o ID da movimentação.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Data da Compra</label>
                                        <input type="date" name="dataCompra" class="form-control" value="<?= htmlspecialchars($mov->getDataCompra()); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, selecione a data da compra.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fornecedor</label>
                                        <select name="fornecedorCnpj" class="form-select" required>
                                            <option value="">Selecione um Fornecedor</option>
                                            <?php foreach ($listaFornecedores as $forn): ?>
                                                <option value="<?= htmlspecialchars($forn->getCnpj()); ?>" <?= ($forn->getCnpj() == $mov->getFornecedorCnpj()) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($forn->getNome()); ?> (<?= htmlspecialchars($forn->getCnpj()); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, selecione um fornecedor.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Valor Total</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="text" name="valorTotal" class="form-control decimal-mask" value="<?= number_format($mov->getValorTotal(), 2, ',', '.'); ?>" required>
                                            <div class="invalid-feedback">
                                                Por favor, digite o valor total.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Observação</label>
                                        <textarea name="observacao" class="form-control" rows="3"><?= htmlspecialchars($mov->getObservacao()); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Produto Interno</label>
                                        <select name="internoProdutosIdInternoProd" class="form-select" required>
                                            <option value="">Selecione um Produto Interno</option>
                                            <?php foreach ($listaInternoProdutos as $prodInt): ?>
                                                <option value="<?= htmlspecialchars($prodInt->getIdInternoProdutos()); ?>" <?= ($prodInt->getIdInternoProdutos() == $mov->getInternoProdutosIdInternoProd()) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($prodInt->getDescricao()); ?> (ID: <?= htmlspecialchars($prodInt->getIdInternoProdutos()); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, selecione um produto interno.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Item de Estoque</label>
                                        <select name="idEstoque" class="form-select" required>
                                            <option value="">Selecione um Item de Estoque</option>
                                            <?php foreach ($listaEstoque as $est): ?>
                                                <option value="<?= htmlspecialchars($est->getIdEstoque()); ?>" <?= ($est->getIdEstoque() == $mov->getIdEstoque()) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($est->getNome()); ?> (ID: <?= htmlspecialchars($est->getIdEstoque()); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, selecione um item de estoque.
                                        </div>
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

            <?php if (count($listaMovimentacoes) === 0): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">Nenhuma movimentação de estoque encontrada.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalNovaMovimentacao" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="tituloModal">Nova Movimentação de Estoque</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="../controller/MovimentacaoEstoque/processaMovimentacaoEstoque.php" method="POST" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID da Movimentação</label>
                        <input type="number" name="idMovimentacaoEstoque" class="form-control" required>
                        <div class="invalid-feedback">
                            Por favor, digite o ID da movimentação.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data da Compra</label>
                        <input type="date" name="dataCompra" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        <div class="invalid-feedback">
                            Por favor, selecione a data da compra.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fornecedor</label>
                        <select name="fornecedorCnpj" class="form-select" required>
                            <option value="">Selecione um Fornecedor</option>
                            <?php foreach ($listaFornecedores as $forn): ?>
                                <option value="<?= htmlspecialchars($forn->getCnpj()); ?>">
                                    <?= htmlspecialchars($forn->getNome()); ?> (<?= htmlspecialchars($forn->getCnpj()); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            Por favor, selecione um fornecedor.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Valor Total</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="text" name="valorTotal" class="form-control decimal-mask" required>
                            <div class="invalid-feedback">
                                Por favor, digite o valor total.
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observação</label>
                        <textarea name="observacao" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Produto Interno</label>
                        <select name="internoProdutosIdInternoProd" class="form-select" required>
                            <option value="">Selecione um Produto Interno</option>
                            <?php foreach ($listaInternoProdutos as $prodInt): ?>
                                <option value="<?= htmlspecialchars($prodInt->getIdInternoProdutos()); ?>">
                                    <?= htmlspecialchars($prodInt->getDescricao()); ?> (ID: <?= htmlspecialchars($prodInt->getIdInternoProdutos()); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            Por favor, selecione um produto interno.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item de Estoque</label>
                        <select name="idEstoque" class="form-select" required>
                            <option value="">Selecione um Item de Estoque</option>
                            <?php foreach ($listaEstoque as $est): ?>
                                <option value="<?= htmlspecialchars($est->getIdEstoque()); ?>">
                                    <?= htmlspecialchars($est->getNome()); ?> (ID: <?= htmlspecialchars($est->getIdEstoque()); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            Por favor, selecione um item de estoque.
                        </div>
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
        $('.decimal-mask').mask('000.000.000.000,00', {reverse: true, placeholder: "0,00"});
    });
</script>

</body>
</html>