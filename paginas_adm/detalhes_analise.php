<h1 class="Titulo">Detalhes do Produto</h1>
<link rel="stylesheet" href="./CSS/detalhes_produto.css">

<?php
require_once './paginas/conexao.php';
$conexao = novaConexao();

if (isset($_GET['codigo'])) {
    $codigo = (int)$_GET['codigo'];  // Recupera o código do produto da URL

    // Consulta para recuperar os dados do produto
    $sql = 'SELECT Cliente.Bairro, Cliente.Logradouro, Cliente.Numero, Usuario_geral.Telefone, 
                Produto.Nome, Produto.Categoria, Produto.Publico_alvo, Produto.Descricao, 
                Roupa.Tamanho_roupa, Calcado.Tamanho_calcado, Produto.Condicao, Imagem.Caminho_imagem
            FROM Produto
            INNER JOIN Cadastro_produto ON Cadastro_produto.ID_produto = Produto.ID
            INNER JOIN Cliente ON Cliente.ID = Cadastro_produto.ID_cliente
            INNER JOIN Usuario_geral ON Usuario_geral.ID = Cliente.ID_usuario_geral
            INNER JOIN Imagem ON Imagem.ID_produto = Produto.ID
            LEFT JOIN Calcado ON Calcado.ID_produto = Produto.ID
            LEFT JOIN Roupa ON Roupa.ID_produto = Produto.ID
            WHERE Produto.ID = ?';

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $codigo);

    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $dados = $resultado->fetch_assoc();
            $tamanho = $dados['Tamanho_calcado'] ? "Calçado" : ($dados['Tamanho_roupa'] ? "Roupa" : 'N/A');
        } else {
            echo "<p>Produto não encontrado.</p>";
            exit;
        }
    } else {
        echo "<p>Erro ao buscar o produto.</p>";
        exit;
    }
} else {
    echo "<p>Código do produto não especificado.</p>";
    exit;
}

// Botões do formulário
if (isset($_POST['aceitar'])) {
    $Usuario_admin = $_SESSION['username_admin']; // Defina o valor correto para o administrador
    $sql = "UPDATE Produto SET Usuario_admin = ? WHERE ID = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('si', $Usuario_admin, $codigo);

    if ($stmt->execute()) {
        echo "<script>window.alert("Produto adicionado ao site com sucesso")</script>";
        header("location: home_admin.php?dir=paginas_adm&file=analise_produtos")
    } else {
        echo "<script>window.alert("Erro ao adicionar o produto ao site")</script>";
    }
} elseif (isset($_POST['recusar'])) {
    // Inicia transação
    $conexao->begin_transaction();

    try {
        // Deleta imagens associadas
        $deletaImagem = "DELETE FROM Imagem WHERE ID_produto = ?";
        $stmt = $conexao->prepare($deletaImagem);
        $stmt->bind_param('i', $codigo);
        $stmt->execute();

        // Deleta categoria específica
        if ($tamanho === "Calçado") {
            $deletaCategoria = "DELETE FROM Calcado WHERE ID_produto = ?";
        } elseif ($tamanho === "Roupa") {
            $deletaCategoria = "DELETE FROM Roupa WHERE ID_produto = ?";
        } else {
            throw new Exception("Categoria desconhecida.");
        }

        $stmt2 = $conexao->prepare($deletaCategoria);
        $stmt2->bind_param('i', $codigo);
        $stmt2->execute();

        // Deleta da tabela de cadastro
        $deletaCadastro = "DELETE FROM Cadastro_produto WHERE ID_produto = ?";
        $stmt3 = $conexao->prepare($deletaCadastro);
        $stmt3->bind_param('i', $codigo);
        $stmt3->execute();

        // Deleta o produto
        $deletaProduto = "DELETE FROM Produto WHERE ID = ?";
        $stmt4 = $conexao->prepare($deletaProduto);
        $stmt4->bind_param('i', $codigo);
        $stmt4->execute();

        // Confirma a transação
        $conexao->commit();
        echo "<p>Produto recusado e removido com sucesso.</p>";
    } catch (Exception $e) {
        // Reverte a transação em caso de erro
        $conexao->rollback();
        echo "<p>Erro ao recusar o produto: " . $e->getMessage() . "</p>";
    }
}
?>

<main>
    <div class="container_principal">
        <div>
            <img src="./<?php echo htmlspecialchars($dados['Caminho_imagem']); ?>" class="imagem_principal" alt="Imagem do produto">
        </div>

        <div>
            <h1 class="nome-produto"><?php echo htmlspecialchars($dados['Nome']); ?></h1>
            <p class="tamanho-produto">Público alvo: <?php echo htmlspecialchars($dados['Publico_alvo']); ?></p>
            <p class="localizacao-produto">Condição do produto: <?php echo htmlspecialchars($dados['Condicao']); ?></p>
            <p class="condicao-produto">Categoria: <?php echo htmlspecialchars($dados['Categoria']); ?></p>
            <p>Tamanho: <?php echo htmlspecialchars($tamanho);?></p>
            <p>Localização do produto: <?php echo htmlspecialchars($dados['Logradouro']) . ', ' . htmlspecialchars($dados['Numero']) . '/ ' . htmlspecialchars($dados['Bairro']) . '/ Curitiba'; ?></p>
            <p>Telefone do doador: <?php echo htmlspecialchars($dados['Telefone']); ?></p>
            <p>Descrição do produto: <?php echo htmlspecialchars($dados['Descricao']); ?></p>
        </div>
    </div>

</main>

<div class="container_botao">
    <form method="POST">

        <button type="submit" name="recusar">Recusar produto</button>

        <button type="submit" name="aceitar">Adicionar produto</button>
    </form>
</div>

<style>
    .Titulo {
        text-align: center;
    }
</style>
