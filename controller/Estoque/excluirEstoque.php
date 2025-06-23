<?php
session_start();

// Proteger o acesso direto
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php'); // Ajuste o caminho
    exit;
}

require_once "../../model/DAL/estoque.php"; // Caminho ajustado

use DAL\Estoque;

$estoqueDAL = new Estoque();

if (isset($_GET['id'])) {
    $id = (int)filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT); // Garante que é um inteiro

    if ($id > 0) {
        $success = $estoqueDAL->Delete($id);

        if ($success) {
            $_SESSION['message'] = "Item de estoque excluído com sucesso!";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Erro ao excluir o item de estoque. Verifique se ele não possui registros relacionados.";
            $_SESSION['status'] = "danger";
        }
    } else {
        $_SESSION['message'] = "ID do item de estoque inválido para exclusão.";
        $_SESSION['status'] = "danger";
    }
} else {
    $_SESSION['message'] = "ID do item de estoque não fornecido para exclusão.";
    $_SESSION['status'] = "warning";
}

header('Location: ../../view/estoqueView.php'); // Redireciona de volta para a lista de estoque
exit;
?>