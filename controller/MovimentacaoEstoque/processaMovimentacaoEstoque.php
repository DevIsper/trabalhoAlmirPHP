<?php
session_start();

// Proteger o acesso direto
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php'); // Ajuste o caminho conforme sua estrutura
    exit;
}

require_once "../../model/DAL/movimentacaoEstoque.php";
require_once "../../model/MovimentacaoEstoque.php";

use DAL\MovimentacaoEstoque;
use MODEL\MovimentacaoEstoque as ModelMovimentacaoEstoque;

$movimentacaoEstoqueDAL = new MovimentacaoEstoque();
$movimentacao = new ModelMovimentacaoEstoque();

// Funções para limpar e validar dados
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e sanitiza os dados do formulário
    $idMovimentacaoEstoque = isset($_POST['idMovimentacaoEstoque']) ? (int)sanitizeInput($_POST['idMovimentacaoEstoque']) : null;
    $dataCompra = isset($_POST['dataCompra']) ? sanitizeInput($_POST['dataCompra']) : null;
    $fornecedorCnpj = isset($_POST['fornecedorCnpj']) ? (int)sanitizeInput($_POST['fornecedorCnpj']) : null;
    $valorTotal = isset($_POST['valorTotal']) ? (float)str_replace(',', '.', sanitizeInput($_POST['valorTotal'])) : null;
    $observacao = isset($_POST['observacao']) ? sanitizeInput($_POST['observacao']) : null;
    $internoProdutosIdInternoProd = isset($_POST['internoProdutosIdInternoProd']) ? (int)sanitizeInput($_POST['internoProdutosIdInternoProd']) : null;
    $idEstoque = isset($_POST['idEstoque']) ? (int)sanitizeInput($_POST['idEstoque']) : null;

    // Campos para PK antiga (apenas para Update)
    $oldIdMovimentacaoEstoque = isset($_POST['oldIdMovimentacaoEstoque']) ? (int)sanitizeInput($_POST['oldIdMovimentacaoEstoque']) : null;
    $oldDataCompra = isset($_POST['oldDataCompra']) ? sanitizeInput($_POST['oldDataCompra']) : null;

    // Define os atributos do objeto MovimentacaoEstoque
    $movimentacao->setIdMovimentacaoEstoque($idMovimentacaoEstoque);
    $movimentacao->setDataCompra($dataCompra);
    $movimentacao->setFornecedorCnpj($fornecedorCnpj);
    $movimentacao->setValorTotal($valorTotal);
    $movimentacao->setObservacao($observacao);
    $movimentacao->setInternoProdutosIdInternoProd($internoProdutosIdInternoProd);
    $movimentacao->setIdEstoque($idEstoque);

    $success = false;
    $action = "";

    // Lógica para determinar se é uma atualização ou inserção, baseada na presença da PK antiga
    if ($oldIdMovimentacaoEstoque > 0 && !empty($oldDataCompra)) {
        // Tentativa de atualizar
        $success = $movimentacaoEstoqueDAL->Update($movimentacao, $oldDataCompra, $oldIdMovimentacaoEstoque);
        $action = "atualizada";
    } else {
        // Tentativa de inserir
        $success = $movimentacaoEstoqueDAL->Insert($movimentacao);
        $action = "cadastrada";
    }

    if ($success) {
        $_SESSION['message'] = "Movimentação de estoque " . $action . " com sucesso!";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Erro ao " . ($idMovimentacaoEstoque > 0 ? "atualizar" : "cadastrar") . " a movimentação de estoque.";
        $_SESSION['status'] = "danger";
    }

    header('Location: ../../view/movimentacaoEstoqueView.php'); // Redireciona de volta para a lista
    exit;

} else {
    // Se não for POST, redireciona com erro
    $_SESSION['message'] = "Método de requisição inválido.";
    $_SESSION['status'] = "danger";
    header('Location: ../../view/movimentacaoEstoqueView.php');
    exit;
}
?>