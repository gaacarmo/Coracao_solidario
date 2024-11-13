

<h1>Usuários</h1>

<?php

require_once "conexao.php";
$conexao = novaConexao();


if (isset($_GET['Excluir'])) {

    $excluirSQL = "DELETE FROM Cliente WHERE id = ?";
    $stmt = $conexao->prepare($excluirSQL);
    $stmt->bind_param("i", $_GET['Excluir']);
    $stmt->execute();


    $excluirUsuarioSQL = "DELETE FROM Usuario_geral WHERE ID = ?";
    $stmtUsuario = $conexao->prepare($excluirUsuarioSQL);
    $stmtUsuario->bind_param("i", $_GET['Excluir']);
    $stmtUsuario->execute();


    header("Location: home.php?dir=paginas_adm&file=lista_usuarios"); # a URL vai precisar ser alterada
    exit();  
}


$sql = "SELECT Cliente.id, Usuario_geral.Nome_completo, Cliente.Usuario_cliente, 
        Usuario_geral.Email, Usuario_geral.Telefone 
        FROM Cliente 
        INNER JOIN Usuario_geral ON Cliente.id = Usuario_geral.ID
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
    table.tabela {
        width: 100%;
        border-collapse: collapse;
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
