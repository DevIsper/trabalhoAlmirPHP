<?php
session_start();

require_once "../../model/DAL/cliente.php";
require_once "../../model/Cliente.php";

use DAL\Cliente as ClienteDAL;
use MODEL\Cliente as ModelCliente;

// --- FUNÇÕES DE VALIDAÇÃO (MANTER APENAS AS NECESSÁRIAS) ---

// A função validaCNPJ será removida.
// Manter as outras se forem usadas:

/**
 * Valida um endereço de e-mail.
 * @param string $email O e-mail a ser validado.
 * @return bool True se o e-mail for válido, false caso contrário.
 */
function validaEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida um CEP.
 * @param string $cep O CEP a ser validado (com ou sem formatação).
 * @return bool True se o CEP for válido, false caso contrário.
 */
function validaCEP(string $cep): bool {
    // Remove caracteres não numéricos
    $cep = preg_replace('/[^0-9]/', '', (string) $cep);

    // Verifica se tem 8 dígitos
    return strlen($cep) === 8;
}

// --- FIM DAS FUNÇÕES DE VALIDAÇÃO ---


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clienteDAL = new ClienteDAL();
    $cliente = new ModelCliente();

    // Limpa e sanitiza os dados de entrada
    $nome         = isset($_POST['nome']) ? htmlspecialchars(trim($_POST['nome'])) : null;
    $telefone     = isset($_POST['telefone']) ? htmlspecialchars(trim($_POST['telefone'])) : null;
    $email        = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : null;
    $endereco     = isset($_POST['endereco']) ? htmlspecialchars(trim($_POST['endereco'])) : null;
    $cidade       = isset($_POST['cidade']) ? htmlspecialchars(trim($_POST['cidade'])) : null;
    $estado       = isset($_POST['estado']) ? htmlspecialchars(trim($_POST['estado'])) : null;
    $cep          = isset($_POST['cep']) ? htmlspecialchars(trim($_POST['cep'])) : null;
    $dataCadastro = isset($_POST['dataCadastro']) ? htmlspecialchars(trim($_POST['dataCadastro'])) : null;
    $cnpj         = isset($_POST['cnpj']) ? htmlspecialchars(trim($_POST['cnpj'])) : null; // CNPJ será aceito sem validação

    // --- REALIZA AS VALIDAÇÕES PHP (REMOVENDO A VALIDAÇÃO DO CNPJ) ---
    $errors = [];

    if (empty($nome)) {
        $errors[] = "Nome é obrigatório.";
    }

    if (!validaEmail($email)) {
        $errors[] = "E-mail inválido.";
    }

    if (!validaCEP($cep)) {
        $errors[] = "CEP inválido. Deve conter 8 dígitos numéricos.";
    }

    // REMOVIDO: A VALIDAÇÃO DO CNPJ AQUI
    /*
    if (!validaCNPJ($cnpj)) {
        $errors[] = "CNPJ inválido.";
    }
    */
    // Adicione mais validações aqui conforme necessário para outros campos

    // Se houver erros, armazena na sessão e redireciona de volta
    if (!empty($errors)) {
        $_SESSION['message'] = "Erro(s) de validação: <br>" . implode("<br>", $errors);
        $_SESSION['status'] = "danger";
        header('Location: ../../view/clienteView.php');
        exit;
    }
    // --- FIM DAS VALIDAÇÕES PHP ---


    $cliente->setNome($nome);
    $cliente->setTelefone($telefone);
    $cliente->setEmail($email);
    $cliente->setEndereco($endereco);
    $cliente->setCidade($cidade);
    $cliente->setEstado($estado);
    $cliente->setCep($cep);
    $cliente->setDataCadastro($dataCadastro);
    $cliente->setCnpj($cnpj); // CNPJ será salvo como recebido, sem validação de conteúdo

    if (isset($_POST['idCliente']) && !empty($_POST['idCliente'])) {
        $idCliente = (int)$_POST['idCliente'];
        $cliente->setIdCliente($idCliente);

        if ($clienteDAL->Update($cliente)) {
            $_SESSION['message'] = "Cliente atualizado com sucesso!";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Erro ao atualizar cliente. Por favor, tente novamente.";
            $_SESSION['status'] = "danger";
        }
    } else {
        if ($clienteDAL->Insert($cliente)) {
            $_SESSION['message'] = "Cliente cadastrado com sucesso!";
            $_SESSION['status'] = "success";
        } else {
            $_SESSION['message'] = "Erro ao cadastrar cliente. Por favor, tente novamente.";
            $_SESSION['status'] = "danger";
        }
    }

    header('Location: ../../view/clienteView.php');
    exit;

} else {
    $_SESSION['message'] = "Acesso inválido. O formulário deve ser submetido via POST.";
    $_SESSION['status'] = "warning";
    header('Location: ../../view/clienteView.php');
    exit;
}