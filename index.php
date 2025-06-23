<?php
// Configurações para exibir erros - Mantenha isso ativado durante o desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui todos os arquivos de modelo e DAL necessários
// Os caminhos estão corretos assumindo que este index.php está na raiz do projeto 'trabalhoAlmirPHP'.
include_once "model/DAL/conexao.php"; // Para garantir que Conexao esteja disponível para todos
include_once "model/DAL/usuario.php";
include_once "model/usuario.php";
include_once "model/DAL/cliente.php";
include_once "model/cliente.php";
include_once "model/DAL/estoque.php";
include_once "model/estoque.php";
include_once "model/DAL/fornecedor.php";
include_once "model/fornecedor.php";
include_once "model/DAL/internoproduto.php";
include_once "model/internoproduto.php";
include_once "model/DAL/movimentacaoestoque.php";
include_once "model/movimentacaoestoque.php";
include_once "model/DAL/movimentacaovenda.php";
include_once "model/movimentacaovenda.php";

// Importa as classes com alias para evitar conflitos de nome
use DAL\Usuario as DALUsuario;
use MODEL\Usuario as ModelUsuario;
use DAL\Cliente as DALCliente;
use MODEL\Cliente as ModelCliente;
use DAL\Estoque as DALEstoque;
use MODEL\Estoque as ModelEstoque;
use DAL\Fornecedor as DALFornecedor;
use MODEL\Fornecedor as ModelFornecedor;
use DAL\InternoProduto as DALInternoProduto;
use MODEL\InternoProduto as ModelInternoProduto;
use DAL\MovimentacaoEstoque as DALMovimentacaoEstoque;
use MODEL\MovimentacaoEstoque as ModelMovimentacaoEstoque;
use DAL\MovimentacaoVenda as DALMovimentacaoVenda;
use MODEL\MovimentacaoVenda as ModelMovimentacaoVenda;

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Página de Índice - Fazenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-4">
    <h1 class="mb-4">Página de Índice - Dados do Banco de Dados</h1>

    <h2 class="mt-5">Exemplo de Busca de Usuário (Individual)</h2>
    <?php
    $dalUsuario = new DALUsuario();
    $usernameToSearch = "admin"; // Nome de usuário para testar a busca

    try {
        $usuario = $dalUsuario->SelectUsuario($usernameToSearch);

        if ($usuario !== null) {
            echo "<div class='alert alert-success' role='alert'>";
            echo "<h3>Dados do Usuário Encontrado:</h3>";
            echo "<p><strong>ID:</strong> " . $usuario->getId() . "</p>";
            echo "<p><strong>Usuário:</strong> " . $usuario->getUsuario() . "</p>";
            echo "<p><strong>Senha (Hash):</strong> " . $usuario->getSenha() . "</p>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-warning' role='alert'>";
            echo "<p>Usuário '{$usernameToSearch}' não encontrado no banco de dados.</p>";
            echo "<p>Para testar, por favor, insira um usuário com '{$usernameToSearch}' no campo 'USERNAME' da tabela 'USER' no seu banco de dados.</p>";
            echo "</div>";
        }

    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>";
        echo "<p>Ocorreu um erro ao buscar o usuário: " . $e->getMessage() . "</p>";
        echo "<p>Verifique a conexão com o banco de dados e as configurações no arquivo 'conexao.php'.</p>";
        echo "</div>";
    }
    ?>

    <hr class="my-5">

    <h2 class="mt-5">Todos os Usuários</h2>
    <?php
    try {
        $usuarios = $dalUsuario->Select();
        if (count($usuarios) > 0) {
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead class='table-dark'><tr><th>ID</th><th>Usuário</th><th>Senha</th></tr></thead>";
            echo "<tbody>";
            foreach ($usuarios as $u) {
                echo "<tr>";
                echo "<td>" . $u->getId() . "</td>";
                echo "<td>" . $u->getUsuario() . "</td>";
                echo "<td>" . $u->getSenha() . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info' role='alert'>Nenhum usuário encontrado.</div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Erro ao listar usuários: " . $e->getMessage() . "</div>";
    }
    ?>

    <hr class="my-5">

    <h2 class="mt-5">Todos os Clientes</h2>
    <?php
    try {
        $dalCliente = new DALCliente();
        $clientes = $dalCliente->Select();
        if (count($clientes) > 0) {
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead class='table-dark'><tr><th>ID Cliente</th><th>Nome</th><th>Telefone</th><th>Email</th><th>Endereço</th><th>Cidade</th><th>Estado</th><th>CEP</th><th>Data Cadastro</th><th>CNPJ</th></tr></thead>";
            echo "<tbody>";
            foreach ($clientes as $c) {
                echo "<tr>";
                echo "<td>" . $c->getIdCliente() . "</td>";
                echo "<td>" . $c->getNome() . "</td>";
                echo "<td>" . $c->getTelefone() . "</td>";
                echo "<td>" . $c->getEmail() . "</td>";
                echo "<td>" . $c->getEndereco() . "</td>";
                echo "<td>" . $c->getCidade() . "</td>";
                echo "<td>" . $c->getEstado() . "</td>";
                echo "<td>" . $c->getCep() . "</td>";
                echo "<td>" . $c->getDataCadastro() . "</td>";
                echo "<td>" . ($c->getCnpj() ?? 'N/A') . "</td>"; // Usar ?? para caso CNPJ seja null
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info' role='alert'>Nenhum cliente encontrado.</div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Erro ao listar clientes: " . $e->getMessage() . "</div>";
    }
    ?>

    <hr class="my-5">

    <h2 class="mt-5">Todos os Itens de Estoque</h2>
    <?php
    try {
        $dalEstoque = new DALEstoque();
        $estoques = $dalEstoque->Select();
        if (count($estoques) > 0) {
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead class='table-dark'><tr><th>ID Estoque</th><th>Nome</th><th>Tipo</th><th>Unidade</th><th>Quantidade</th><th>Preço Venda</th></tr></thead>";
            echo "<tbody>";
            foreach ($estoques as $e) {
                echo "<tr>";
                echo "<td>" . $e->getIdEstoque() . "</td>";
                echo "<td>" . $e->getNome() . "</td>";
                echo "<td>" . $e->getTipoEstoque() . "</td>";
                echo "<td>" . $e->getUnidadeMedida() . "</td>";
                echo "<td>" . $e->getQuantidade() . "</td>";
                echo "<td>" . number_format($e->getPrecoVenda(), 2, ',', '.') . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info' role='alert'>Nenhum item de estoque encontrado.</div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Erro ao listar estoque: " . $e->getMessage() . "</div>";
    }
    ?>

    <hr class="my-5">

    <h2 class="mt-5">Todos os Fornecedores</h2>
    <?php
    try {
        $dalFornecedor = new DALFornecedor();
        $fornecedores = $dalFornecedor->Select();
        if (count($fornecedores) > 0) {
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead class='table-dark'><tr><th>CNPJ</th><th>Nome</th><th>Telefone</th><th>Email</th><th>Endereço</th></tr></thead>";
            echo "<tbody>";
            foreach ($fornecedores as $f) {
                echo "<tr>";
                echo "<td>" . $f->getCnpj() . "</td>";
                echo "<td>" . $f->getNome() . "</td>";
                echo "<td>" . $f->getTelefone() . "</td>";
                echo "<td>" . $f->getEmail() . "</td>";
                echo "<td>" . $f->getEndereco() . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info' role='alert'>Nenhum fornecedor encontrado.</div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Erro ao listar fornecedores: " . $e->getMessage() . "</div>";
    }
    ?>

    <hr class="my-5">

    <h2 class="mt-5">Todos os Produtos Internos</h2>
    <?php
    try {
        $dalInternoProduto = new DALInternoProduto();
        $internoProdutos = $dalInternoProduto->Select();
        if (count($internoProdutos) > 0) {
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead class='table-dark'><tr><th>ID Produto Interno</th><th>Descrição</th></tr></thead>";
            echo "<tbody>";
            foreach ($internoProdutos as $ip) {
                echo "<tr>";
                echo "<td>" . $ip->getIdInternoProdutos() . "</td>";
                echo "<td>" . $ip->getDescricao() . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info' role='alert'>Nenhum produto interno encontrado.</div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Erro ao listar produtos internos: " . $e->getMessage() . "</div>";
    }
    ?>

    <hr class="my-5">

    <h2 class="mt-5">Todas as Movimentações de Estoque</h2>
    <?php
    try {
        $dalMovimentacaoEstoque = new DALMovimentacaoEstoque();
        // Incluindo relacionamentos para exibir nomes mais descritivos
        $movimentacoesEstoque = $dalMovimentacaoEstoque->Select(true);
        if (count($movimentacoesEstoque) > 0) {
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead class='table-dark'><tr><th>ID Movimentação</th><th>CNPJ Fornecedor</th><th>Nome Fornecedor</th><th>Data Compra</th><th>Valor Total</th><th>Observação</th><th>ID Prod. Interno</th><th>Desc. Prod. Interno</th><th>ID Estoque</th><th>Nome Estoque</th></tr></thead>";
            echo "<tbody>";
            foreach ($movimentacoesEstoque as $me) {
                echo "<tr>";
                echo "<td>" . $me->getIdMovimentacaoEstoque() . "</td>";
                echo "<td>" . $me->getFornecedorCnpj() . "</td>";
                echo "<td>" . ($me->fornecedorNome ?? 'N/A') . "</td>"; // Exibe nome do fornecedor se disponível
                echo "<td>" . $me->getDataCompra() . "</td>";
                echo "<td>" . number_format($me->getValorTotal(), 2, ',', '.') . "</td>";
                echo "<td>" . $me->getObservacao() . "</td>";
                echo "<td>" . $me->getInternoProdutosIdInternoProd() . "</td>";
                echo "<td>" . ($me->internoProdutoDescricao ?? 'N/A') . "</td>"; // Exibe descrição do produto interno
                echo "<td>" . $me->getIdEstoque() . "</td>";
                echo "<td>" . ($me->estoqueNome ?? 'N/A') . "</td>"; // Exibe nome do estoque
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info' role='alert'>Nenhuma movimentação de estoque encontrada.</div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Erro ao listar movimentações de estoque: " . $e->getMessage() . "</div>";
    }
    ?>

    <hr class="my-5">

    <h2 class="mt-5">Todas as Movimentações de Vendas</h2>
    <?php
    try {
        $dalMovimentacaoVenda = new DALMovimentacaoVenda();
        // Incluindo relacionamentos para exibir nomes mais descritivos
        $movimentacoesVendas = $dalMovimentacaoVenda->Select(true);
        if (count($movimentacoesVendas) > 0) {
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead class='table-dark'><tr><th>ID Cliente</th><th>Nome Cliente</th><th>Data Movimentação</th><th>Quantidade</th><th>Descrição</th><th>ID Produto Venda (FK)</th><th>ID Estoque</th><th>Nome Estoque</th></tr></thead>";
            echo "<tbody>";
            foreach ($movimentacoesVendas as $mv) {
                echo "<tr>";
                echo "<td>" . $mv->getIdCliente() . "</td>";
                echo "<td>" . ($mv->clienteNome ?? 'N/A') . "</td>"; // Exibe nome do cliente
                echo "<td>" . $mv->getDataMovimentacao() . "</td>";
                echo "<td>" . $mv->getQuantidade() . "</td>";
                echo "<td>" . $mv->getDescricao() . "</td>";
                echo "<td>" . ($mv->getProdutoVendaIdProdutoVenda() ?? 'N/A') . "</td>"; // Pode ser null se a FK for opcional ou tiver problemas
                echo "<td>" . $mv->getIdEstoque() . "</td>";
                echo "<td>" . ($mv->estoqueNome ?? 'N/A') . "</td>"; // Exibe nome do estoque
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-info' role='alert'>Nenhuma movimentação de venda encontrada.</div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Erro ao listar movimentações de vendas: " . $e->getMessage() . "</div>";
    }
    ?>

    <hr class="my-5">

    <h2 class="mt-5">Navegação (Futuros CRUDs)</h2>
    <div class="list-group">
        <a href="#" class="list-group-item list-group-item-action disabled">CRUD Clientes (a ser criado)</a>
        <a href="#" class="list-group-item list-group-item-action disabled">CRUD Estoque (a ser criado)</a>
        <a href="#" class="list-group-item list-group-item-action disabled">CRUD Fornecedores (a ser criado)</a>
        <a href="#" class="list-group-item list-group-item-action disabled">CRUD Usuários (a ser criado)</a>
        <a href="#" class="list-group-item list-group-item-action disabled">Movimentação de Estoque (a ser criado)</a>
        <a href="#" class="list-group-item list-group-item-action disabled">Movimentação de Vendas (a ser criado)</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>