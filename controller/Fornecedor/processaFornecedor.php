<?php
session_start();

// Proteger o acesso direto, se necessário (replicando a lógica de clienteView)
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['message'] = "Você precisa fazer login para acessar esta página.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/login.php'); // Ajuste o caminho conforme sua estrutura
    exit;
}

require_once "../../model/DAL/fornecedor.php"; // Caminho ajustado para DAL do fornecedor
require_once "../../model/Fornecedor.php";     // Caminho ajustado para Model do fornecedor

use DAL\Fornecedor;
use MODEL\Fornecedor as ModelFornecedor;

$fornecedorDAL = new Fornecedor();
$fornecedor = new ModelFornecedor();

// Funções para limpar e validar dados
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e sanitiza os dados do formulário
    $cnpj = isset($_POST['cnpj']) ? str_replace(['.', '/', '-'], '', sanitizeInput($_POST['cnpj'])) : null; // Remove formatação do CNPJ
    $nome = isset($_POST['nome']) ? sanitizeInput($_POST['nome']) : null;
    $telefone = isset($_POST['telefone']) ? sanitizeInput($_POST['telefone']) : null;
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : null;
    $endereco = isset($_POST['endereco']) ? sanitizeInput($_POST['endereco']) : null;

    // Define os atributos do objeto Fornecedor
    $fornecedor->setCnpj($cnpj);
    $fornecedor->setNome($nome);
    $fornecedor->setTelefone($telefone);
    $fornecedor->setEmail($email);
    $fornecedor->setEndereco($endereco);

    // Verifica se é uma edição (CNPJ já existe) ou uma nova inserção
    // O CNPJ é a chave primária e não deve ser alterado em uma atualização
    $isUpdate = false;
    if (isset($_POST['is_update']) && $_POST['is_update'] === 'true') {
        $isUpdate = true;
    }

    $success = false;
    if ($isUpdate) {
        // Tentativa de atualizar
        $success = $fornecedorDAL->Update($fornecedor);
        $action = "atualizado";
    } else {
        // Tentativa de inserir
        // Antes de inserir, verifique se o CNPJ já existe para evitar duplicidade
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

    header('Location: ../../view/fornecedorView.php'); // Redireciona de volta para a lista de fornecedores
    exit;

} else {
    // Se não for POST, redireciona com erro
    $_SESSION['message'] = "Método de requisição inválido.";
    $_SESSION['status'] = "danger";
    header('Location: ../../view/fornecedorView.php');
    exit;
}
?>