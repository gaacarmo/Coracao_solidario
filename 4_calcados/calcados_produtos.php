<?php
    echo '<h1>Calçados</h1>';
    require_once './paginas/conexao.php';
    $conexao = novaConexao();

    $sql = "SELECT Produto.ID,Produto.Nome,Calcado.Tamanho_calcado,Imagem.Caminho_imagem FROM Produto 
    INNER JOIN Imagem ON Imagem.ID_produto = Produto.ID 
    INNER JOIN Calcado ON Calcado.ID_produto = produto.ID
    WHERE Produto.Categoria = 'Calçado'";
    $stmt = $conexao->prepare($sql);
    if($stmt->execute()){
        $resultado = $stmt->get_result();
        if ($resultado -> num_rows > 0){
            echo '<main class="principal">';
            echo '<section class="products">';
            while ($produto = $resultado->fetch_assoc()) {
                ?>
                <div class="product">
                    <div class="img-produto">
                        <img src="<?php echo htmlspecialchars($produto['Caminho_imagem']); ?>" alt="<?php echo htmlspecialchars($produto['Nome']); ?>">
                    </div>
                    <p class="texto-produto"><?php echo htmlspecialchars($produto['Nome']); ?></p>
                    <p class="sub-titulo">Tamanho: <?php echo htmlspecialchars($produto['Tamanho_calcado']); ?></p>
                    <a class="btn-index" href="home.php?dir=4_calcados&file=detalhes_produto&codigo=<?php echo $produto['ID']; ?>">Saiba mais</a>
                </div>
                <?php
            }
            echo '</section>';
            echo '</main>';
        } else {
            echo '<p>Nenhum produto encontrado.</p>';
        }
    } else {
        echo '<p>Erro na consulta ao banco de dados.</p>';
    }
?>