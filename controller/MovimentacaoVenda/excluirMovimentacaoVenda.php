<?php
session_start();

// Proteger o acesso direto
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php'); // Ajuste o caminho
    exit;
}

require_once "../../model/DAL/movimentacaoVenda.php";

use DAL\MovimentacaoVenda;

$movimentacaoVendaDAL = new MovimentacaoVenda();

// Para exclusão, precisamos de 3 partes da chave primária composta
if (isset($_GET['idC']) && isset($_GET['dtM']) && isset($_GET['idE'])) { // REMOVIDO: isset($_GET['idP'])
    $idCliente = (int)filter_var($_GET['idC'], FILTER_SANITIZE_NUMBER_INT);
    $dataMovimentacao = filter_var($_GET['dtM'], FILTER_SANITIZE_STRING);
    // REMOVIDO: $produtoVendaIdProdutoVenda = (int)filter_var($_GET['idP'], FILTER_SANITIZE_NUMBER_INT);
    $idEstoque = (int)filter_var($_GET['idE'], FILTER_SANITIZE_NUMBER_INT);

    // Validação básica para garantir que os IDs são positivos e a data não está vazia
    if ($idCliente > 0 && !empty($dataMovimentacao) && $idEstoque > 0) { // REMOVIDO: $produtoVendaIdProdutoVenda > 0
        $success = $movimentacaoVendaDAL->Delete($idCliente, $dataMovimentacao, $idEstoque); // REMOVIDO: $produtoVendaIdProdutoVenda

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

header('Location: ../../view/movimentacaoVendaView.php'); // Redireciona de volta para a lista
exit;
?>