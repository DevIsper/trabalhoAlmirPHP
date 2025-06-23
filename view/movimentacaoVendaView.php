<?php
session_start();

// Proteger a página
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: login.php'); // Caminho para a sua tela de login, pois movimentacaoVendaView.php está na mesma pasta view/
    exit;
}

require_once "../model/DAL/movimentacaoVenda.php";
require_once "../model/MovimentacaoVenda.php";
require_once "../model/DAL/cliente.php"; // Para popular o dropdown de clientes
require_once "../model/DAL/estoque.php"; // Para popular o dropdown de estoque

use DAL\MovimentacaoVenda;
use MODEL\MovimentacaoVenda as ModelMovimentacaoVenda;
use DAL\Cliente;
use DAL\Estoque;

$movimentacaoVendaDAL = new MovimentacaoVenda();
// Carrega as movimentações com os nomes dos relacionados
$listaMovimentacoes = $movimentacaoVendaDAL->Select(true);

// Instâncias das DALs para popular os dropdowns nos modais
$clienteDAL = new Cliente();
$listaClientes = $clienteDAL->Select();

$estoqueDAL = new Estoque();
$listaEstoque = $estoqueDAL->Select();

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
    <title>Movimentação de Venda - Fazenda</title>
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
            max-width: 1100px; /* Largura máxima para esta tabela com mais colunas */
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
        <h2>Lista de Movimentações de Venda</h2>
        <div>
            <a href="menu.php" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Voltar ao Menu
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovaMovimentacaoVenda">
                <i class="bi bi-plus-circle"></i> Nova Movimentação
            </button>
            </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>Cliente</th>
                <th>Data Mov.</th>
                <th>Quantidade</th>
                <th>Descrição</th>
                <th>Item Estoque</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($listaMovimentacoes as $mov): ?>
                <tr>
                    <td><?= htmlspecialchars($mov->clienteNome ?? 'N/A'); ?></td>
                    <td><?= date('d/m/Y', strtotime($mov->getDataMovimentacao())); ?></td>
                    <td><?= number_format($mov->getQuantidade(), 2, ',', '.'); ?></td>
                    <td><?= htmlspecialchars($mov->getDescricao()); ?></td>
                    <td><?= htmlspecialchars($mov->estoqueNome ?? 'N/A'); ?></td>
                    <td class="text-center">
                        <?php
                            // Crie um ID único para o modal de edição baseado na PK composta (agora 3 partes)
                            $modalId = "modalEditar" . $mov->getIdCliente() . str_replace(['-', ' '], '', $mov->getDataMovimentacao()) . $mov->getIdEstoque();
                            // Codifique os parâmetros da PK para a URL de exclusão (agora 3 partes)
                            // Certifique-se de que a dataMovimentacao está no formato YYYY-MM-DD para o link, se for a forma como o controlador espera
                            $deleteParams = "idC=" . urlencode($mov->getIdCliente()) .
                                            "&dtM=" . urlencode($mov->getDataMovimentacao()) .
                                            "&idE=" . urlencode($mov->getIdEstoque());
                        ?>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                        <a href="../controller/MovimentacaoVenda/excluirMovimentacaoVenda.php?<?= $deleteParams; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta movimentação?')">
                            <i class="bi bi-trash"></i> Excluir
                        </a>
                    </td>
                </tr>

                <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="tituloEditar<?= $modalId ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content shadow-lg rounded">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="tituloEditar<?= $modalId ?>">Editar Movimentação de Venda</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <form action="../controller/MovimentacaoVenda/processaMovimentacaoVenda.php" method="POST" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" name="oldIdCliente" value="<?= htmlspecialchars($mov->getIdCliente()); ?>">
                                    <input type="hidden" name="oldDataMovimentacao" value="<?= htmlspecialchars($mov->getDataMovimentacao()); ?>">
                                    <input type="hidden" name="oldIdEstoque" value="<?= htmlspecialchars($mov->getIdEstoque()); ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Cliente</label>
                                        <select name="idCliente" class="form-select" required>
                                            <option value="">Selecione um Cliente</option>
                                            <?php foreach ($listaClientes as $cli): ?>
                                                <option value="<?= htmlspecialchars($cli->getIdCliente()); ?>" <?= ($cli->getIdCliente() == $mov->getIdCliente()) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($cli->getNome()); ?> (ID: <?= htmlspecialchars($cli->getIdCliente()); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, selecione um cliente.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Data da Movimentação</label>
                                        <input type="date" name="dataMovimentacao" class="form-control" value="<?= htmlspecialchars($mov->getDataMovimentacao()); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, selecione a data da movimentação.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Quantidade</label>
                                        <input type="text" name="quantidade" class="form-control decimal-mask" value="<?= number_format($mov->getQuantidade(), 2, ',', '.'); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite a quantidade.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Descrição</label>
                                        <textarea name="descricao" class="form-control" rows="3"><?= htmlspecialchars($mov->getDescricao()); ?></textarea>
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
                    <td colspan="6" class="text-center text-muted">Nenhuma movimentação de venda encontrada.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalNovaMovimentacaoVenda" tabindex="-1" aria-labelledby="tituloModalVenda" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="tituloModalVenda">Nova Movimentação de Venda</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="../controller/MovimentacaoVenda/processaMovimentacaoVenda.php" method="POST" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cliente</label>
                        <select name="idCliente" class="form-select" required>
                            <option value="">Selecione um Cliente</option>
                            <?php foreach ($listaClientes as $cli): ?>
                                <option value="<?= htmlspecialchars($cli->getIdCliente()); ?>">
                                    <?= htmlspecialchars($cli->getNome()); ?> (ID: <?= htmlspecialchars($cli->getIdCliente()); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            Por favor, selecione um cliente.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data da Movimentação</label>
                        <input type="date" name="dataMovimentacao" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        <div class="invalid-feedback">
                            Por favor, selecione a data da movimentação.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantidade</label>
                        <input type="text" name="quantidade" class="form-control decimal-mask" required>
                        <div class="invalid-feedback">
                            Por favor, digite a quantidade.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control" rows="3"></textarea>
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