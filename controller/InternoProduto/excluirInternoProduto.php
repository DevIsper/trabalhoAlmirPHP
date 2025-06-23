<?php
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php');
    exit;
}

require_once "../../model/DAL/internoproduto.php";

use DAL\InternoProduto;

$internoProdutoDAL = new InternoProduto();

if (isset($_GET['id'])) {
    $id = (int)filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    if ($id > 0) {
        $success = $internoProdutoDAL->Delete($id);

        if ($success) {
            $_SESSION['message'] = "Produto interno excluído com sucesso!";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Erro ao excluir o produto interno. Verifique se ele não possui registros relacionados.";
            $_SESSION['status'] = "danger";
        }
    } else {
        $_SESSION['message'] = "ID do produto interno inválido para exclusão.";
        $_SESSION['status'] = "danger";
    }
} else {
    $_SESSION['message'] = "ID do produto interno não fornecido para exclusão.";
    $_SESSION['status'] = "warning";
}

header('Location: ../../view/internoProdutoView.php');
exit;
?>