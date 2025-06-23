<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php');
    exit;
}

require_once "../../model/DAL/estoque.php";
require_once "../../model/Estoque.php";

use DAL\Estoque;
use MODEL\Estoque as ModelEstoque;

$estoqueDAL = new Estoque();
$itemEstoque = new ModelEstoque();

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idEstoque = isset($_POST['idEstoque']) ? (int)sanitizeInput($_POST['idEstoque']) : null;
    $nome = isset($_POST['nome']) ? sanitizeInput($_POST['nome']) : null;
    $tipoEstoque = isset($_POST['tipoEstoque']) ? sanitizeInput($_POST['tipoEstoque']) : null;
    $unidadeMedida = isset($_POST['unidadeMedida']) ? sanitizeInput($_POST['unidadeMedida']) : null;
    $quantidade = isset($_POST['quantidade']) ? (float)str_replace(',', '.', sanitizeInput($_POST['quantidade'])) : null;
    $precoVenda = isset($_POST['precoVenda']) ? (float)str_replace(',', '.', sanitizeInput($_POST['precoVenda'])) : null;

    $itemEstoque->setIdEstoque($idEstoque);
    $itemEstoque->setNome($nome);
    $itemEstoque->setTipoEstoque($tipoEstoque);
    $itemEstoque->setUnidadeMedida($unidadeMedida);
    $itemEstoque->setQuantidade($quantidade);
    $itemEstoque->setPrecoVenda($precoVenda);

    $success = false;
    $action = "";


    if ($idEstoque > 0) {

        $success = $estoqueDAL->Update($itemEstoque);
        $action = "atualizado";
    } else {

        $success = $estoqueDAL->Insert($itemEstoque);
        $action = "cadastrado";
    }

    if ($success) {
        $_SESSION['message'] = "Item de estoque " . $action . " com sucesso!";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Erro ao " . ($idEstoque > 0 ? "atualizar" : "cadastrar") . " o item de estoque.";
        $_SESSION['status'] = "danger";
    }

    header('Location: ../../view/estoqueView.php');
    exit;

} else {
    $_SESSION['message'] = "Método de requisição inválido.";
    $_SESSION['status'] = "danger";
    header('Location: ../../view/estoqueView.php');
    exit;
}
?>