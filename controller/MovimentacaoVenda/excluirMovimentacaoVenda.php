<?php
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php');
    exit;
}

require_once "../../model/DAL/movimentacaoVenda.php";

use DAL\MovimentacaoVenda;

$movimentacaoVendaDAL = new MovimentacaoVenda();


if (isset($_GET['idC']) && isset($_GET['dtM']) && isset($_GET['idE'])) {
    $idCliente = (int)filter_var($_GET['idC'], FILTER_SANITIZE_NUMBER_INT);
    $dataMovimentacao = filter_var($_GET['dtM'], FILTER_SANITIZE_STRING);

    $idEstoque = (int)filter_var($_GET['idE'], FILTER_SANITIZE_NUMBER_INT);


    if ($idCliente > 0 && !empty($dataMovimentacao) && $idEstoque > 0) {
        $success = $movimentacaoVendaDAL->Delete($idCliente, $dataMovimentacao, $idEstoque);

        if ($success) {
            $_SESSION['message'] = "Movimentação de venda excluída com sucesso!";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Erro ao excluir a movimentação de venda. Verifique se ela não possui registros relacionados.";
            $_SESSION['status'] = "danger";
        }
    } else {
        $_SESSION['message'] = "Dados da movimentação de venda inválidos para exclusão.";
        $_SESSION['status'] = "danger";
    }
} else {
    $_SESSION['message'] = "Dados da movimentação de venda não fornecidos para exclusão.";
    $_SESSION['status'] = "warning";
}

header('Location: ../../view/movimentacaoVendaView.php');
exit;
?>