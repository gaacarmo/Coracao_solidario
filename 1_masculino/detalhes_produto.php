<h1>Masculino</h1>
<link rel="stylesheet" href="./CSS/detalhes_produto.css">

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
            <p>Tamanho: </p>
            <p>Localização do produto: ./ ./ Curitiba</p>
            <p>Telefone do doador: </p>
            <p>Descrição do produto: </p>
        </div>
    </div>

    
</main>

<div class="container_botao">
    <button type="button">
        <a href="index.php">Adicionar ao carrinho</a>
    </button>
</div>