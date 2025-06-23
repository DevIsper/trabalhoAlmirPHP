<?php
session_start();

// Proteger o acesso direto
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php'); // Ajuste o caminho
    exit;
}

require_once "../../model/DAL/fornecedor.php"; // Caminho ajustado

use DAL\Fornecedor;

$fornecedorDAL = new Fornecedor();

if (isset($_GET['cnpj'])) {
    $cnpj = str_replace(['.', '/', '-'], '', $_GET['cnpj']); // Remove formatação do CNPJ

    if (!empty($cnpj) && is_numeric($cnpj)) {
        $success = $fornecedorDAL->Delete((int)$cnpj);

        if ($success) {
            $_SESSION['message'] = "Fornecedor excluído com sucesso!";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Erro ao excluir o fornecedor. Verifique se ele não possui registros relacionados.";
            $_SESSION['status'] = "danger";
        }
    } else {
        $_SESSION['message'] = "CNPJ do fornecedor inválido para exclusão.";
        $_SESSION['status'] = "danger";
    }
} else {
    $_SESSION['message'] = "CNPJ do fornecedor não fornecido para exclusão.";
    $_SESSION['status'] = "warning";
}

header('Location: ../../view/fornecedorView.php'); // Redireciona de volta para a lista de fornecedores
exit;
?>