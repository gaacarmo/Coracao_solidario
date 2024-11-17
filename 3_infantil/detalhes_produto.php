<h1 class="titulo-pag">Infantil</h1>
<link rel="stylesheet" href="./CSS/detalhes_produto.css">

<?php
require_once './paginas/conexao.php';
$conexao = novaConexao();

if (isset($_GET['codigo'])) {
    $codigo = (int)$_GET['codigo']; 

    $sql = 'SELECT Cliente.Bairro, Cliente.Logradouro, Cliente.Numero, Usuario_geral.Telefone, 
                Produto.Nome, Produto.Categoria, Produto.Publico_alvo, Produto.Descricao, 
                Roupa.Tamanho_roupa, Produto.Condicao, Imagem.Caminho_imagem
            FROM Produto
            INNER JOIN Cadastro_produto ON Cadastro_produto.ID_produto = Produto.ID
            INNER JOIN Cliente ON Cliente.ID = Cadastro_produto.ID_cliente
            INNER JOIN Usuario_geral ON Usuario_geral.ID = Cliente.ID_usuario_geral
            INNER JOIN Imagem ON Imagem.ID_produto = Produto.ID
            INNER JOIN Roupa ON Roupa.ID_produto = Produto.ID
            WHERE Produto.ID = ?';

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

if (isset($_POST['adicionar'])) {
    $codigo_produto = (int)$_POST['codigo'];
    header('location: home.php?dir=paginas&&file=carrinho');


    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = array(); 
    }

    if (!in_array($codigo_produto, $_SESSION['carrinho'])) {
        $_SESSION['carrinho'][] = $codigo_produto;
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
            <p>Tamanho: <?php echo htmlspecialchars($dados['Tamanho_roupa']);?></p>
            <p>Localização do produto: <?php echo htmlspecialchars($dados['Logradouro']) . ', ' . htmlspecialchars($dados['Numero']) . '/ ' . htmlspecialchars($dados['Bairro']) . '/ Curitiba'; ?></p>
            <p>Telefone do doador: <?php echo htmlspecialchars($dados['Telefone']); ?></p>
            <p>Descrição do produto: <?php echo htmlspecialchars($dados['Descricao']); ?></p>
        </div>
    </div>

</main>

<div class="container_botao">
    <form method="POST">
        <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
        <button type="submit" name="adicionar">Adicionar ao carrinho</button>
    </form>
</div>