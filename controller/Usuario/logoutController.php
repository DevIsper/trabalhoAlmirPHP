<?php
session_start();

// Destrói todas as variáveis de sessão
$_SESSION = array();

// Se o cookie de sessão for usado, ele também deve ser excluído.
// Nota: Isso removerá o cookie de sessão, não apenas os dados da sessão.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrói a sessão.
session_destroy();

$_SESSION['message'] = "Você foi desconectado com sucesso.";
$_SESSION['status'] = "info";

// Redireciona para a página de login
header('Location: ../../view/login.php');
exit;
?>