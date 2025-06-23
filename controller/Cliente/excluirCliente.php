<?php
session_start();

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


header('Location: ../../view/clienteView.php');
exit;
?>