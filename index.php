<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Página de Indice</title>
</head>
<body>

<h1>Página de Indice</h1>

<?php
include_once "model/DAL/usuario.php";
include_once "model/usuario.php";

$dalUsuario = new \DAL\Usuario();

$usernameToSearch = "isper";

try {
    $usuario = $dalUsuario->SelectUsuario($usernameToSearch);

    if ($usuario !== null) {
        echo "<p>ID: " . $usuario->getId() . "</p>";
        echo "<p>Usuário: " . $usuario->getUsuario() . "</p>";
        echo "<p>Senha: " . $usuario->getSenha() . "</p>";
    } else {
        echo "<p>Usuário '{$usernameToSearch}' não encontrado.</p>";
    }

} catch (Exception $e) {
    echo "<p>Ocorreu um erro ao buscar o usuário: " . $e->getMessage() . "</p>";
}
?>

</body>
</html>