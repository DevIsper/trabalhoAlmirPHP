<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php');
    exit;
}

require_once "../../model/DAL/fornecedor.php";
require_once "../../model/Fornecedor.php";

use DAL\Fornecedor;
use MODEL\Fornecedor as ModelFornecedor;

$fornecedorDAL = new Fornecedor();
$fornecedor = new ModelFornecedor();

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cnpj = isset($_POST['cnpj']) ? str_replace(['.', '/', '-'], '', sanitizeInput($_POST['cnpj'])) : null;
    $nome = isset($_POST['nome']) ? sanitizeInput($_POST['nome']) : null;
    $telefone = isset($_POST['telefone']) ? sanitizeInput($_POST['telefone']) : null;
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : null;
    $endereco = isset($_POST['endereco']) ? sanitizeInput($_POST['endereco']) : null;


    $fornecedor->setCnpj($cnpj);
    $fornecedor->setNome($nome);
    $fornecedor->setTelefone($telefone);
    $fornecedor->setEmail($email);
    $fornecedor->setEndereco($endereco);

    $isUpdate = false;
    if (isset($_POST['is_update']) && $_POST['is_update'] === 'true') {
        $isUpdate = true;
    }

    $success = false;
    if ($isUpdate) {

        $success = $fornecedorDAL->Update($fornecedor);
        $action = "atualizado";
    } else {

        if ($fornecedorDAL->SelectByCnpj($cnpj)) {
            $_SESSION['message'] = "Erro: Já existe um fornecedor cadastrado com este CNPJ.";
            $_SESSION['status'] = "danger";
            header('Location: ../../view/fornecedorView.php');
            exit;
        }
        $success = $fornecedorDAL->Insert($fornecedor);
        $action = "cadastrado";
    }

    if ($success) {
        $_SESSION['message'] = "Fornecedor " . $action . " com sucesso!";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Erro ao " . ($isUpdate ? "atualizar" : "cadastrar") . " o fornecedor.";
        $_SESSION['status'] = "danger";
    }

    header('Location: ../../view/fornecedorView.php');
    exit;

} else {
    $_SESSION['message'] = "Método de requisição inválido.";
    $_SESSION['status'] = "danger";
    header('Location: ../../view/fornecedorView.php');
    exit;
}
?>