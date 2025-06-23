<?php
session_start();

// Proteger o acesso direto
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php'); // Ajuste o caminho conforme sua estrutura
    exit;
}

require_once "../../model/DAL/estoque.php"; // Caminho ajustado para DAL do estoque
require_once "../../model/Estoque.php";     // Caminho ajustado para Model do estoque

use DAL\Estoque;
use MODEL\Estoque as ModelEstoque;

$estoqueDAL = new Estoque();
$itemEstoque = new ModelEstoque();

// Funções para limpar e validar dados
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e sanitiza os dados do formulário
    $idEstoque = isset($_POST['idEstoque']) ? (int)sanitizeInput($_POST['idEstoque']) : null;
    $nome = isset($_POST['nome']) ? sanitizeInput($_POST['nome']) : null;
    $tipoEstoque = isset($_POST['tipoEstoque']) ? sanitizeInput($_POST['tipoEstoque']) : null;
    $unidadeMedida = isset($_POST['unidadeMedida']) ? sanitizeInput($_POST['unidadeMedida']) : null;
    $quantidade = isset($_POST['quantidade']) ? (float)str_replace(',', '.', sanitizeInput($_POST['quantidade'])) : null; // Converte vírgula para ponto e para float
    $precoVenda = isset($_POST['precoVenda']) ? (float)str_replace(',', '.', sanitizeInput($_POST['precoVenda'])) : null; // Converte vírgula para ponto e para float

    // Define os atributos do objeto Estoque
    $itemEstoque->setIdEstoque($idEstoque); // Será null para novas inserções
    $itemEstoque->setNome($nome);
    $itemEstoque->setTipoEstoque($tipoEstoque);
    $itemEstoque->setUnidadeMedida($unidadeMedida);
    $itemEstoque->setQuantidade($quantidade);
    $itemEstoque->setPrecoVenda($precoVenda);

    $success = false;
    $action = "";

    // Verifica se é uma edição (idEstoque presente e maior que 0) ou uma nova inserção
    if ($idEstoque > 0) {
        // Tentativa de atualizar
        $success = $estoqueDAL->Update($itemEstoque);
        $action = "atualizado";
    } else {
        // Tentativa de inserir
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

    header('Location: ../../view/estoqueView.php'); // Redireciona de volta para a lista de estoque
    exit;

} else {
    // Se não for POST, redireciona com erro
    $_SESSION['message'] = "Método de requisição inválido.";
    $_SESSION['status'] = "danger";
    header('Location: ../../view/estoqueView.php');
    exit;
}
?>