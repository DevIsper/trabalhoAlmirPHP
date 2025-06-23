<?php
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php');
    exit;
}

require_once "../../model/DAL/internoproduto.php";
require_once "../../model/InternoProduto.php";

use DAL\InternoProduto;
use MODEL\InternoProduto as ModelInternoProduto;

$internoProdutoDAL = new InternoProduto();
$internoProduto = new ModelInternoProduto();


function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idInternoProdutos = isset($_POST['idInternoProdutos']) ? (int)sanitizeInput($_POST['idInternoProdutos']) : null;
    $descricao = isset($_POST['descricao']) ? sanitizeInput($_POST['descricao']) : null;


    $internoProduto->setIdInternoProdutos($idInternoProdutos);
    $internoProduto->setDescricao($descricao);

    $success = false;
    $action = "";

    if ($idInternoProdutos > 0) {
        $success = $internoProdutoDAL->Update($internoProduto);
        $action = "atualizado";
    } else {
        $success = $internoProdutoDAL->Insert($internoProduto);
        $action = "cadastrado";
    }

    if ($success) {
        $_SESSION['message'] = "Produto interno " . $action . " com sucesso!";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Erro ao " . ($idInternoProdutos > 0 ? "atualizar" : "cadastrar") . " o produto interno.";
        $_SESSION['status'] = "danger";
    }

    header('Location: ../../view/internoProdutoView.php');
    exit;

} else {
    $_SESSION['message'] = "Método de requisição inválido.";
    $_SESSION['status'] = "danger";
    header('Location: ../../view/internoProdutoView.php');
    exit;
}
?>