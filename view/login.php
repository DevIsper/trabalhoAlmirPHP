<?php
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

            background-image: url('img/imgFundoLogin.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
        }
        .login-container {
            max-width: 550px;


            padding: 55px;

            
            border-radius: 10px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            background-color: rgba(0, 0, 0, 0.57);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(248, 242, 242, 0.8);
        }
        .login-header {
            margin-bottom: 30px;
            text-align: center;
            color: white;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }


        .form-control {
            background-color: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
            color: white !important;
            border-radius: 30px !important;
            padding: 0.75rem 1rem !important;
        }


        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.8) !important;
            opacity: 1;
        }


        .form-control:focus {
            border-color: white !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25) !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
        }


        .btn-primary {
            background-color: white !important;
            border-color: white !important;
            color: black !important;
            border-radius: 25px !important;
            font-weight: bold;
            padding: 0.75rem 1.5rem !important;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease; /* Transição suave para hover e click */
        }
        .btn-primary:hover {
            background-color: rgba(255, 255, 255, 0.9) !important;
            border-color: rgba(255, 255, 255, 0.9) !important;
            color: black !important;
            transform: translateY(-1px);
        }
        .btn-primary:active {
            background-color: rgba(255, 255, 255, 0.8) !important;
            border-color: rgba(255, 255, 255, 0.8) !important;
            transform: translateY(0);
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