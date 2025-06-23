<?php
session_start();

// Proteger o acesso direto
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php'); // Ajuste o caminho conforme sua estrutura
    exit;
}

require_once "../../model/DAL/internoproduto.php"; // Caminho ajustado para DAL do interno produto
require_once "../../model/InternoProduto.php";     // Caminho ajustado para Model do interno produto

use DAL\InternoProduto;
use MODEL\InternoProduto as ModelInternoProduto;

$internoProdutoDAL = new InternoProduto();
$internoProduto = new ModelInternoProduto();

// Funções para limpar e validar dados
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e sanitiza os dados do formulário
    $idInternoProdutos = isset($_POST['idInternoProdutos']) ? (int)sanitizeInput($_POST['idInternoProdutos']) : null;
    $descricao = isset($_POST['descricao']) ? sanitizeInput($_POST['descricao']) : null;

    // Define os atributos do objeto InternoProduto
    $internoProduto->setIdInternoProdutos($idInternoProdutos); // Será null para novas inserções
    $internoProduto->setDescricao($descricao);

    $success = false;
    $action = "";

    // Verifica se é uma edição (idInternoProdutos presente e maior que 0) ou uma nova inserção
    if ($idInternoProdutos > 0) {
        // Tentativa de atualizar
        $success = $internoProdutoDAL->Update($internoProduto);
        $action = "atualizado";
    } else {
        // Tentativa de inserir
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

    header('Location: ../../view/internoProdutoView.php'); // Redireciona de volta para a lista de produtos internos
    exit;

} else {
    // Se não for POST, redireciona com erro
    $_SESSION['message'] = "Método de requisição inválido.";
    $_SESSION['status'] = "danger";
    header('Location: ../../view/internoProdutoView.php');
    exit;
}
?>