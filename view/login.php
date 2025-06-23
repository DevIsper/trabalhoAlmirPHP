<?php
// Inicia a sessão para exibir mensagens flash, se houver.
session_start();

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
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* Caminho da imagem de fundo, confirmado como .jpg */
            background-image: url('img/imgFundoLogin.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0; /* Garante que não haja margens no body */
        }
        .login-container {
            /* --- INÍCIO DAS CONFIGURAÇÕES DE TAMANHO DO QUADRADO DO LOGIN --- */
            /* max-width: Define a largura máxima do contêiner. Ajuste este valor para maior ou menor. */
            /* Ex: 400px (padrão), 500px, 550px, 600px */
            max-width: 550px; /* Sugestão para deixá-lo maior, como na imagem */

            /* padding: Define o espaço interno do contêiner. Aumentar isso também o faz parecer maior. */
            /* Ajuste este valor (Ex: 30px, 40px, 50px) */
            padding: 55px; /* Sugestão para mais espaço interno e visual maior */
            /* --- FIM DAS CONFIGURAÇÕES DE TAMANHO DO QUADRADO DO LOGIN --- */
            
            border-radius: 10px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            background-color: rgba(0, 0, 0, 0.57); /* Preto com 50% de opacidade (para fundo escuro) */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px); /* Para compatibilidade com navegadores Webkit */
            border: 1px solid rgba(248, 242, 242, 0.8); /* Borda sutil branca */
        }
        .login-header {
            margin-bottom: 30px;
            text-align: center;
            color: white; /* Texto branco */
            font-weight: bold; /* Negrito para o título */
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5); /* Sombra no texto para melhor legibilidade */
        }

        /* Estilos para os campos de input do Bootstrap */
        .form-control {
            background-color: transparent !important; /* Fundo transparente, !important para sobrescrever Bootstrap */
            border: 1px solid rgba(255, 255, 255, 0.6) !important; /* Borda branca transparente */
            color: white !important; /* Cor do texto digitado nos campos */
            border-radius: 30px !important; /* Cantos mais arredondados */
            padding: 0.75rem 1rem !important; /* Ajuste de padding */
        }

        /* Estilo para o placeholder dos campos de input */
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.8) !important; /* Placeholder branco transparente */
            opacity: 1; /* Garante que a opacidade seja mantida em todos os navegadores */
        }

        /* Estilo para o estado de foco (quando o campo está selecionado) */
        .form-control:focus {
            border-color: white !important; /* Borda branca sólida no foco */
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25) !important; /* Sombra branca suave no foco */
            background-color: rgba(255, 255, 255, 0.05) !important; /* Pequeno fundo transparente no foco para contraste */
        }

        /* Estilos para o botão de login do Bootstrap */
        .btn-primary {
            background-color: white !important; /* Fundo branco, !important para sobrescrever Bootstrap */
            border-color: white !important; /* Borda branca */
            color: black !important; /* Texto preto */
            border-radius: 25px !important; /* Muito arredondado (estilo pílula) */
            font-weight: bold; /* Texto em negrito */
            padding: 0.75rem 1.5rem !important; /* Ajuste de padding */
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease; /* Transição suave para hover e click */
        }
        .btn-primary:hover {
            background-color: rgba(255, 255, 255, 0.9) !important; /* Levemente menos branco no hover */
            border-color: rgba(255, 255, 255, 0.9) !important;
            color: black !important;
            transform: translateY(-1px); /* Pequeno efeito de levantamento */
        }
        .btn-primary:active {
            background-color: rgba(255, 255, 255, 0.8) !important;
            border-color: rgba(255, 255, 255, 0.8) !important;
            transform: translateY(0); /* Retorna à posição normal */
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2 class="login-header">Login</h2>

    <?php if ($message): ?>
    <div class="alert alert-<?= $status ?> alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <form action="../controller/Usuario/loginController.php" method="POST">
        <div class="mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autocomplete="username">
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>