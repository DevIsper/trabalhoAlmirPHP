<?php
session_start();

require_once "../../model/DAL/usuario.php";
require_once "../../model/Usuario.php";

use DAL\Usuario as UsuarioDAL;
use MODEL\Usuario as ModelUsuario;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        $_SESSION['message'] = "Por favor, preencha todos os campos.";
        $_SESSION['status'] = "danger";
        header('Location: ../../view/login.php');
        exit;
    }

    $usuarioDAL = new UsuarioDAL();
    $usuario = $usuarioDAL->SelectUsuario($username);

    // Modifique esta parte:
    if ($usuario && $usuario->getSenha() === $password) { // Ou password_verify() se você estiver usando hashes
        // Login bem-sucedido
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $usuario->getId();
        $_SESSION['username'] = $usuario->getUsuario();

        $_SESSION['message'] = "Login realizado com sucesso!";
        $_SESSION['status'] = "success";
        header('Location: ../../view/menu.php');
        exit;
    } else {
        // Credenciais inválidas - AQUI VOCÊ DEFINE A MENSAGEM
        $_SESSION['message'] = "Dados não aceitos."; // <-- ALtere esta linha
        $_SESSION['status'] = "danger";
        header('Location: ../../view/login.php'); // Redireciona de volta para a tela de login
        exit;
    }

} else {
    $_SESSION['message'] = "Acesso inválido.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php');
    exit;
}