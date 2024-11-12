

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
        INNER JOIN Usuario_geral ON Cliente.id = Usuario_geral.ID";
        
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
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nome completo</th>
                <th>Nome de Usuário</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ação</th>
            </tr>";
    
    foreach ($registros as $registro) {
        echo "<tr>
                <td>{$registro['id']}</td>
                <td>{$registro['Nome_completo']}</td>
                <td>{$registro['Usuario_cliente']}</td>
                <td>{$registro['Email']}</td>
                <td>{$registro['Telefone']}</td>
                <td>
                    <a href='home.php?dir=paginas_adm&file=lista_usuarios&Excluir=" . $registro['id'] . "'> 
                        Excluir 
                    </a> a URL vai precisar ser alterada
                </td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "Nenhum resultado encontrado.";
}
?>

