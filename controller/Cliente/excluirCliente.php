<?php
session_start();

// Caminho correto para a classe DAL\Cliente, agora que 'excluirCliente.php' está em 'controller/Cliente/'
// Precisa subir dois níveis (../../) para chegar na raiz do projeto, e então descer para 'model/DAL/'.
require_once "../../model/DAL/cliente.php";

use DAL\Cliente as ClienteDAL;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idCliente = (int)$_GET['id'];
    $clienteDAL = new ClienteDAL();

    if ($clienteDAL->Delete($idCliente)) {
        $_SESSION['message'] = "Cliente excluído com sucesso!";
        $_SESSION['status'] = "success";
    } else {
        $_SESSION['message'] = "Erro ao excluir cliente. Tente novamente.";
        $_SESSION['status'] = "danger";
    }
} else {
    $_SESSION['message'] = "ID do cliente inválido para exclusão.";
    $_SESSION['status'] = "warning";
}

// Redirecionamento CORRIGIDO: Sobe dois níveis (../../) para a raiz do projeto, então entra em 'view/'
header('Location: ../../view/clienteView.php');
exit;
?>