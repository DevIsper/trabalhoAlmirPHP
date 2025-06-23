<?php
session_start();

// Proteger o acesso direto
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php'); // Ajuste o caminho
    exit;
}

require_once "../../model/DAL/movimentacaoEstoque.php";

use DAL\MovimentacaoEstoque;

$movimentacaoEstoqueDAL = new MovimentacaoEstoque();

// Para exclusão, precisamos de ambas as partes da chave primária composta
if (isset($_GET['id']) && isset($_GET['data'])) {
    $id = (int)filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $data = filter_var($_GET['data'], FILTER_SANITIZE_STRING);

    if ($id > 0 && !empty($data)) {
        $success = $movimentacaoEstoqueDAL->Delete($data, $id);

        if ($success) {
            $_SESSION['message'] = "Movimentação de estoque excluída com sucesso!";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Erro ao excluir a movimentação de estoque. Verifique se ela não possui registros relacionados.";
            $_SESSION['status'] = "danger";
        }
    } else {
        $_SESSION['message'] = "ID ou Data da movimentação de estoque inválidos para exclusão.";
        $_SESSION['status'] = "danger";
    }
} else {
    $_SESSION['message'] = "ID ou Data da movimentação de estoque não fornecidos para exclusão.";
    $_SESSION['status'] = "warning";
}

header('Location: ../../view/movimentacaoEstoqueView.php'); // Redireciona de volta para a lista
exit;
?>