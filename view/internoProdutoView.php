<?php
session_start();

// Proteger a página
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: login.php'); // Caminho para a sua tela de login, pois internoProdutoView.php está na mesma pasta view/
    exit;
}

require_once "../model/DAL/internoproduto.php"; // Caminho ajustado para DAL do interno produto
require_once "../model/InternoProduto.php";// Caminho ajustado para Model do interno produto

use DAL\InternoProduto;
use MODEL\InternoProduto as ModelInternoProduto;

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
    <title>Produtos Internos - Fazenda</title>
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
            max-width: 900px; /* Largura máxima para esta tabela menor */
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
        <h2>Lista de Produtos Internos</h2>
        <div>
            <a href="menu.php" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Voltar ao Menu
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovoInternoProduto">
                <i class="bi bi-plus-circle"></i> Novo Produto Interno
            </button>
            </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($listaInternoProdutos as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item->getIdInternoProdutos()); ?></td>
                    <td><?= htmlspecialchars($item->getDescricao()); ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $item->getIdInternoProdutos(); ?>">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                        <a href="../controller/InternoProduto/excluirInternoProduto.php?id=<?= $item->getIdInternoProdutos(); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto interno?')">
                            <i class="bi bi-trash"></i> Excluir
                        </a>
                    </td>
                </tr>

                <div class="modal fade" id="modalEditar<?= $item->getIdInternoProdutos(); ?>" tabindex="-1" aria-labelledby="tituloEditar<?= $item->getIdInternoProdutos(); ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content shadow-lg rounded">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="tituloEditar<?= $item->getIdInternoProdutos(); ?>">Editar Produto Interno</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <form action="../controller/InternoProduto/processaInternoProduto.php" method="POST" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" name="idInternoProdutos" value="<?= htmlspecialchars($item->getIdInternoProdutos()); ?>">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">ID (Não Editável)</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($item->getIdInternoProdutos()); ?>" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Descrição</label>
                                        <input type="text" name="descricao" class="form-control" value="<?= htmlspecialchars($item->getDescricao()); ?>" required>
                                        <div class="invalid-feedback">
                                            Por favor, digite a descrição do produto interno.
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

            <?php if (count($listaInternoProdutos) === 0): ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">Nenhum produto interno encontrado.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalNovoInternoProduto" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="tituloModal">Novo Produto Interno</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="../controller/InternoProduto/processaInternoProduto.php" method="POST" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <input type="text" name="descricao" class="form-control" required>
                        <div class="invalid-feedback">
                            Por favor, digite a descrição do produto interno.
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

    // Não são necessárias máscaras de telefone, CNPJ, CEP ou decimal para este CRUD
    // Este bloco pode ser removido completamente se não houver campos com máscaras
    // ou deixado vazio caso queira adicionar máscaras futuras.
    $(document).ready(function(){
        // Se precisar de máscaras específicas para outros campos no futuro, adicione aqui.
    });
</script>

</body>
</html>