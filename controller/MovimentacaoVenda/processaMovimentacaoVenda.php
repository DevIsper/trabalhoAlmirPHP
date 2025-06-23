<?php
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php');
    exit;
}

require_once "../../model/DAL/movimentacaoVenda.php";
require_once "../../model/MovimentacaoVenda.php";

use DAL\MovimentacaoVenda;
use MODEL\MovimentacaoVenda as ModelMovimentacaoVenda;

$movimentacaoVendaDAL = new MovimentacaoVenda();
$movimentacao = new ModelMovimentacaoVenda();


function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idCliente = isset($_POST['idCliente']) ? (int)sanitizeInput($_POST['idCliente']) : null;
    $dataMovimentacao = isset($_POST['dataMovimentacao']) ? sanitizeInput($_POST['dataMovimentacao']) : null;
    $quantidade = isset($_POST['quantidade']) ? (float)str_replace(',', '.', sanitizeInput($_POST['quantidade'])) : null;
    $descricao = isset($_POST['descricao']) ? sanitizeInput($_POST['descricao']) : null;

    $idEstoque = isset($_POST['idEstoque']) ? (int)sanitizeInput($_POST['idEstoque']) : null;


    $oldIdCliente = isset($_POST['oldIdCliente']) ? (int)sanitizeInput($_POST['oldIdCliente']) : null;
    $oldDataMovimentacao = isset($_POST['oldDataMovimentacao']) ? sanitizeInput($_POST['oldDataMovimentacao']) : null;

    $oldIdEstoque = isset($_POST['oldIdEstoque']) ? (int)sanitizeInput($_POST['oldIdEstoque']) : null;



    $movimentacao->setIdCliente($idCliente);
    $movimentacao->setDataMovimentacao($dataMovimentacao);
    $movimentacao->setQuantidade($quantidade);
    $movimentacao->setDescricao($descricao);

    $movimentacao->setIdEstoque($idEstoque);

    $success = false;
    $action = "";



    if ($oldIdCliente > 0 && !empty($oldDataMovimentacao) && $oldIdEstoque > 0) {

        $success = $movimentacaoVendaDAL->Update($movimentacao, $oldIdCliente, $oldDataMovimentacao, $oldIdEstoque);
        $action = "atualizada";
    } else {

        $success = $movimentacaoVendaDAL->Insert($movimentacao);
        $action = "cadastrada";
    }

    if ($success) {
        $_SESSION['message'] = "Movimentação de venda " . $action . " com sucesso!";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Erro ao " . ($action == "atualizada" ? "atualizar" : "cadastrar") . " a movimentação de venda. Verifique os dados e chaves duplicadas.";
        $_SESSION['status'] = "danger";
    }

    header('Location: ../../view/movimentacaoVendaView.php');
    exit;

} else {

    $_SESSION['message'] = "Método de requisição inválido.";
    $_SESSION['status'] = "danger";
    header('Location: ../../view/movimentacaoVendaView.php');
    exit;
}
?>