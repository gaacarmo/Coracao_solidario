

<h1 class="titulo">Usuários</h1>

<?php

require_once "./paginas/conexao.php";
$conexao = novaConexao();
if (!(isset($_SESSION['is_logged_admin'])) || $_SESSION['is_logged_admin'] !== true) {
    echo "<script>alert('Para acessar esta página, é necessário fazer login.');
    window.location.href = 'home.php?dir=paginas&&file=loginusu';
    </script>";
    exit;
}


if (isset($_GET['Excluir'])) {
    $conexao->autocommit(FALSE);
    $conexao->begin_transaction();
    $transacao_sucesso = true;

    $excluirProdutosSQL = "DELETE FROM cadastro_produto WHERE ID_cliente = ?";
    $stmtProdutos = $conexao->prepare($excluirProdutosSQL);
    $stmtProdutos->bind_param("i", $_GET['Excluir']);
    if (!$stmtProdutos->execute()) {
        $transacao_sucesso = false;
    }

    $excluirSQL = "DELETE FROM Cliente WHERE id = ?";
    $stmt = $conexao->prepare($excluirSQL);
    $stmt->bind_param("i", $_GET['Excluir']);
    if (!$stmt->execute()) {
        $transacao_sucesso = false;
    }


    $excluirUsuarioSQL = "DELETE FROM Usuario_geral WHERE ID = ?";
    $stmtUsuario = $conexao->prepare($excluirUsuarioSQL);
    $stmtUsuario->bind_param("i", $_GET['Excluir']);
    if (!$stmtUsuario->execute()) {
        $transacao_sucesso = false;
    }

    // Verifica o resultado da transação e executa COMMIT ou ROLLBACK
    if ($transacao_sucesso) {
        $conexao->commit();
    } else {
        $conexao->rollback();
        echo "Erro ao excluir dados.";
    }

    $conexao->autocommit(TRUE);

        header("Location: home.php?dir=paginas_adm&file=lista_usuarios");
        exit();
}



$sql = "SELECT Cliente.id, Usuario_geral.Nome_completo, Cliente.Usuario_cliente, 
        Usuario_geral.Email, Usuario_geral.Telefone 
        FROM Cliente 
        INNER JOIN Usuario_geral ON Cliente.ID_usuario_geral = Usuario_geral.ID
        ORDER BY id";
        
$resultado = $conexao->query($sql);
$registros = [];

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $registros[] = $row; 
    }
} elseif ($conexao->error) {
    echo "Erro: " . $conexao->error;
}

if (count($registros) > 0) {
    echo "<table class='tabela'>
            <tr>
                <th class='tabela'>ID</th>
                <th class='tabela'>Nome completo</th>
                <th class='tabela'>Nome de Usuário</th>
                <th class='tabela'>Email</th>
                <th class='tabela'>Telefone</th>
                <th class='tabela'>Ação</th>
            </tr>";
    
    foreach ($registros as $registro) {
        echo "<tr>
                <td class='registros-tabela'>{$registro['id']}</td>
                <td class='registros-tabela'>{$registro['Nome_completo']}</td>
                <td class='registros-tabela'>{$registro['Usuario_cliente']}</td>
                <td class='registros-tabela'>{$registro['Email']}</td>
                <td class='registros-tabela'>{$registro['Telefone']}</td>
                <td class='registros-tabela'>
                    <a class='botao-excluir' href='home.php?dir=paginas_adm&file=lista_usuarios&Excluir=" . $registro['id'] . "' 
           onclick='return confirm(\"Tem certeza que deseja excluir este usuário?\")'> 
           Excluir
        </a>
                </td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "Nenhum resultado encontrado.";
}
?>

<style>
    .titulo{
        text-align: center;
  
        font-weight: 1.2rem;
    }
    table.tabela {
        width: 100%;
        border-collapse: collapse;
        margin: 3% 0px 0px 30px;
    }

    /* Cabeçalho da Tabela */
    table.tabela th {
        background-color: black;
        color: white;
        padding: 10px;
        text-align: left;
    }

    /* Linhas com efeito de zebra */
    table.tabela tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table.tabela tr:nth-child(odd) {
        background-color: #ffffff;
    }

    /* Células de dados */
    table.tabela td {
        padding: 10px;
        text-align: left;
    }

 /* Botão vermelho de exclusão */
    .botao-excluir {
        display: inline-block;
        padding: 8px 16px;
        background-color: #FF4C4C;
        color: white;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
        cursor: pointer;
    }

    .botao-excluir:hover {
        background-color: #FF1C1C;
    }
</style>
</style>
