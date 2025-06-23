<?php
session_start();

// Proteger o acesso direto
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php'); // Ajuste o caminho conforme sua estrutura
    exit;
}

require_once "../../model/DAL/movimentacaoVenda.php";
require_once "../../model/MovimentacaoVenda.php";

use DAL\MovimentacaoVenda;
use MODEL\MovimentacaoVenda as ModelMovimentacaoVenda;

$movimentacaoVendaDAL = new MovimentacaoVenda();
$movimentacao = new ModelMovimentacaoVenda();

// Funções para limpar e validar dados
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e sanitiza os dados do formulário
    $idCliente = isset($_POST['idCliente']) ? (int)sanitizeInput($_POST['idCliente']) : null;
    $dataMovimentacao = isset($_POST['dataMovimentacao']) ? sanitizeInput($_POST['dataMovimentacao']) : null;
    $quantidade = isset($_POST['quantidade']) ? (float)str_replace(',', '.', sanitizeInput($_POST['quantidade'])) : null;
    $descricao = isset($_POST['descricao']) ? sanitizeInput($_POST['descricao']) : null;
    // REMOVIDO: $produtoVendaIdProdutoVenda = isset($_POST['produtoVendaIdProdutoVenda']) ? (int)sanitizeInput($_POST['produtoVendaIdProdutoVenda']) : null;
    $idEstoque = isset($_POST['idEstoque']) ? (int)sanitizeInput($_POST['idEstoque']) : null;

    // Campos para PK antiga (apenas para Update)
    $oldIdCliente = isset($_POST['oldIdCliente']) ? (int)sanitizeInput($_POST['oldIdCliente']) : null;
    $oldDataMovimentacao = isset($_POST['oldDataMovimentacao']) ? sanitizeInput($_POST['oldDataMovimentacao']) : null;
    // REMOVIDO: $oldProdutoVendaIdProdutoVenda = isset($_POST['oldProdutoVendaIdProdutoVenda']) ? (int)sanitizeInput($_POST['oldProdutoVendaIdProdutoVenda']) : null;
    $oldIdEstoque = isset($_POST['oldIdEstoque']) ? (int)sanitizeInput($_POST['oldIdEstoque']) : null;


    // Define os atributos do objeto MovimentacaoVenda
    $movimentacao->setIdCliente($idCliente);
    $movimentacao->setDataMovimentacao($dataMovimentacao);
    $movimentacao->setQuantidade($quantidade);
    $movimentacao->setDescricao($descricao);
    // REMOVIDO: $movimentacao->setProdutoVendaIdProdutoVenda($produtoVendaIdProdutoVenda);
    $movimentacao->setIdEstoque($idEstoque);

    $success = false;
    $action = "";

    // Lógica para determinar se é uma atualização ou inserção, baseada na presença da PK antiga
    // Agora a PK tem 3 campos.
    if ($oldIdCliente > 0 && !empty($oldDataMovimentacao) && $oldIdEstoque > 0) {
        // Tentativa de atualizar
        $success = $movimentacaoVendaDAL->Update($movimentacao, $oldIdCliente, $oldDataMovimentacao, $oldIdEstoque);
        $action = "atualizada";
    } else {
        // Tentativa de inserir
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

    header('Location: ../../view/movimentacaoVendaView.php'); // Redireciona de volta para a lista
    exit;

} else {
    // Se não for POST, redireciona com erro
    $_SESSION['message'] = "Método de requisição inválido.";
    $_SESSION['status'] = "danger";
    header('Location: ../../view/movimentacaoVendaView.php');
    exit;
}
?>