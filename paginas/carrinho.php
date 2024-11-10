<link rel="stylesheet" href="./CSS/carrinho.css">
<div class="titulo">
    <h1>Carrinho</h1>
    <?php
    if (!(isset($_SESSION['is_logged_in'])) || $_SESSION['is_logged_in'] !== true) {
        echo "<script>alert('Para acessar esta página, é necessário fazer login.');
        window.location.href = 'home.php?dir=paginas&&file=loginusu';
        </script>";
        exit;
    }?>
</div>
<?php
if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
    require_once 'conexao.php';
    $conexao = novaConexao();
    
    // Preparar a consulta para buscar os dados dos produtos
    $placeholders = implode(',', array_fill(0, count($_SESSION['carrinho']), '?'));
    $sql = "SELECT Produto.ID, Produto.Nome, Produto.Descricao, Imagem.Caminho_imagem
            FROM Produto
            INNER JOIN Imagem ON Imagem.ID_produto = Produto.ID
            WHERE Produto.ID IN ($placeholders)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($_SESSION['carrinho'])), ...$_SESSION['carrinho']);
    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        echo '<div class="container_produto">';
        while ($produto = $resultado->fetch_assoc()) {
            ?>
            <div class="produto">
                <div>
                    <img src="./<?php echo htmlspecialchars($produto['Caminho_imagem']); ?>" alt="Imagem do produto">
                </div>
                <div>
                    <h3><?php echo htmlspecialchars($produto['Nome']); ?></h3>
                    <p><?php echo htmlspecialchars($produto['Descricao']); ?></p>
                    <a href="home.php?dir=paginas&&file=remover_carrinho&codigo=<?php echo $produto['ID']; ?>">Remover do carrinho</a>
                </div>
            </div>
            <?php
        
        }
        echo "</div>";
    }
}
?>
<form class="form_finalizar">
    <button>Finalizar pedido</button>
</form>