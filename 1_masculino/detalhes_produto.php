<h1>Masculino</h1>

<?php
require_once 'conexao.php';
$conexao = novaConexao();


if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];
    $sql = 'SELECT produto.Nome, produto.Categoria,produto.Publico_alvo,  produto.Condicao, imagem.Caminho_imagem
    FROM produto
    JOIN imagem ON produto.ID = imagem.ID_produto
    WHERE produto.ID = ?;
';

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $codigo);

    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $dados = $resultado->fetch_assoc();
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
?>

<h3 class="nome-produto"><?php echo htmlspecialchars($dados['Nome']); ?></h3>
<p class="tamanho-produto">Tamanho: <?php echo htmlspecialchars($dados['Publico_alvo']); ?></p>
<p class="localizacao-produto">Localização: <?php echo htmlspecialchars($dados['Condicao']); ?></p>
<p class="condicao-produto">Condição: <?php echo htmlspecialchars($dados['Condicao']); ?></p>
<img src="uploads/<?php echo htmlspecialchars($dados['Caminho_imagem']); ?>" alt="Imagem do produto">

<style>

    .nome-produto {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .tamanho-produto, .localizacao-produto, .condicao-produto {
        font-size: 1rem;
        margin: 5px 0;
    }
</style>
