<?php
echo '<h1>Página análise produtos</h1>';

require_once './paginas/conexao.php';
$conexao = novaConexao();
?>
<link rel="stylesheet" href="./CSS/style.css">
<a href="./index_adm.php"><img class="voltar" src="assets/de-volta.png" alt="Voltar"></a>
<?php
$sql = "SELECT Produto.ID, Produto.Nome, Calcado.Tamanho_calcado, Roupa.Tamanho_roupa, Imagem.Caminho_imagem 
        FROM Produto 
        LEFT JOIN Imagem ON Imagem.ID_produto = Produto.ID 
        LEFT JOIN Calcado ON Calcado.ID_produto = Produto.ID
        LEFT JOIN Roupa ON Roupa.ID_produto = Produto.ID
        WHERE Produto.Usuario_admin IS NULL";

$stmt = $conexao->prepare($sql);

if ($stmt->execute()) {
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        echo '<main class="principal">';
        echo '<section class="products">';
        while ($produto = $resultado->fetch_assoc()) {
            $imagem = $produto['Caminho_imagem'] ? htmlspecialchars($produto['Caminho_imagem']) : 'assets/default.png';
            $tamanho = $produto['Tamanho_roupa'] ?? $produto['Tamanho_calcado'] ?? 'N/A';
            ?>
            <div class="product">
                <div class="img-produto">
                    <img src="<?php echo $imagem; ?>" alt="<?php echo htmlspecialchars($produto['Nome']); ?>">
                </div>
                <p class="texto-produto"><?php echo htmlspecialchars($produto['Nome']); ?></p>
                <p class="sub-titulo">Tamanho: <?php echo htmlspecialchars($tamanho); ?></p>
                <a class="btn-index" href="home_adm.php?dir=paginas_adm&file=detalhes_analise&codigo=<?php echo $produto['ID']; ?>">Saiba mais</a>
            </div>
            <?php
        }
        echo '</section>';
        echo '</main>';
    } else {
        echo '<p>Nenhum produto encontrado.</p>';
    }
} else {
    echo '<p>Erro na consulta ao banco de dados: ' . htmlspecialchars($stmt->error) . '</p>';
}
?>
<style>
    .voltar {
        position: absolute;
        top: 120px;
        bottom: 10px;
        left: 20px;
        width: 30px;
        height: 30px;
        cursor: pointer;
    }
</style>
