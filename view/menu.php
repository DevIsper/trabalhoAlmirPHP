<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: login.php');
    exit;
}

$userName = $_SESSION['username'] ?? 'Usuário';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fazenda - Menu Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,.1);
            flex-shrink: 0;
            background-image: url('img/imgFundoLogin.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            z-index: 1;
        }
        .sidebar::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(44, 62, 80, 0.85);
            z-index: -1;
        }
        .sidebar .list-group-item {
            background-color: transparent;
            color: white;
            border: none;
            padding: 10px 15px;
            position: relative;
            z-index: 1;
        }
        .sidebar .list-group-item:hover,
        .sidebar .list-group-item.active {
            background-color: rgba(52, 73, 94, 0.7);
            border-radius: 5px;
        }
        .sidebar .list-group-item i {
            margin-right: 10px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }
        .navbar-custom {
            background-color: #1a252f;
            color: white;
        }
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column">
        <div class="text-center mb-4">
            <h3 class="text-white">Fazenda</h3>
            <hr class="text-white-50">
        </div>
        <div class="list-group list-group-flush">
            <a href="menu.php" class="list-group-item list-group-item-action py-2 ripple active">
                <i class="bi bi-house-door-fill"></i> Home
            </a>
            <a href="fornecedorView.php" class="list-group-item list-group-item-action py-2 ripple">
                <i class="bi bi-truck"></i> Fornecedores
            </a>
            <a href="clienteView.php" class="list-group-item list-group-item-action py-2 ripple">
                <i class="bi bi-person-badge-fill"></i> Clientes
            </a>
            <a href="estoqueView.php" class="list-group-item list-group-item-action py-2 ripple">
                <i class="bi bi-box-seam-fill"></i> Estoque
            </a>
            <a href="movimentacaoVendaView.php" class="list-group-item list-group-item-action py-2 ripple">
                <i class="bi bi-cart-fill"></i> Mov. Venda
            </a>
            <a href="movimentacaoEstoqueView.php" class="list-group-item list-group-item-action py-2 ripple">
                <i class="bi bi-arrow-left-right"></i> Mov. Estoque
            </a>
            <a href="internoProdutoView.php" class="list-group-item list-group-item-action py-2 ripple">
                <i class="bi bi-boxes"></i> Produtos Internos
            </a>
        </div>
        <div class="mt-auto">
            <hr class="text-white-50">
            <a href="../controller/Usuario/logoutController.php" class="list-group-item list-group-item-action py-2 ripple text-danger">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>
    </div>

    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom rounded shadow-sm mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    Painel Administrativo
                </a>
                <div class="collapse navbar-collapse justify-content-end">
                    <span class="navbar-text text-white-50 me-3">
                        Olá, <strong><?= htmlspecialchars($userName); ?></strong>!
                    </span>
                    <a href="../controller/Usuario/logoutController.php" class="btn btn-outline-light btn-sm">Sair</a>
                </div>
            </div>
        </nav>

        <div class="p-4 bg-white rounded shadow-sm">
            <h3>Bem-vindo(a) ao Sistema de Gestão da Fazenda!</h3>
            <p class="lead">Utilize o menu lateral para navegar entre as diferentes seções do sistema.</p>
            <p>Aqui você pode gerenciar:</p>
            <ul>
                <li>**Fornecedores**: Cadastro e informações dos seus fornecedores.</li>
                <li>**Clientes**: Cadastro e informações dos seus clientes.</li>
                <li>**Estoque**: Gerenciamento de produtos e insumos.</li>
                <li>**Movimentações de Venda**: Registro e acompanhamento das suas vendas.</li>
                <li>**Movimentações de Estoque**: Entradas e saídas de produtos no seu estoque.</li>
                <li>**Produtos Internos**: Cadastro e controle de produtos gerados internamente.</li>
            </ul>
            <p>Tenha um ótimo trabalho!</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var path = window.location.pathname;
            var filename = path.split('/').pop();

            var navLinks = document.querySelectorAll('.sidebar .list-group-item');
            navLinks.forEach(function(link) {

                link.classList.remove('active');

                if (link.getAttribute('href') === filename) {
                    link.classList.add('active');
                }
            });

            if (filename === '' || filename === 'menu.php') {
                document.querySelector('.sidebar a[href="menu.php"]').classList.add('active');
            }
        });
    </script>
</body>
</html>