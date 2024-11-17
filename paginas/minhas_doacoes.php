<link rel="stylesheet" href="./CSS/carrinho.css">
<div class="titulo">
    <h1>Minhas Doações</h1>
    <a href="./index.php"><img class="voltar" src="assets/de-volta.png" alt="Voltar"></a>
    <?php
    if (!(isset($_SESSION['is_logged_in'])) || $_SESSION['is_logged_in'] !== true) {
        echo "<script>alert('Para acessar esta página, é necessário fazer login.');
        window.location.href = 'home.php?dir=paginas&&file=loginusu';
        </script>";
        exit;
    }
    ?>
</div>

<?php
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    require_once 'conexao.php';
    $conexao = novaConexao();

    if (isset($_POST['entregaProduto']) && isset($_POST['codigoProduto'])) {
        $codigoProduto = $_POST['codigoProduto'];

        // Atualiza o status do pedido para "Entregue"
        $sqlEntrega = "UPDATE Entrega SET Status_pedido = 'Entregue' WHERE ID_produto = ?";
        $stmtUpdate = $conexao->prepare($sqlEntrega);
        if ($stmtUpdate) {
            $stmtUpdate->bind_param("i", $codigoProduto);
            if ($stmtUpdate->execute()) {
                $sqlCategoria = "SELECT Categoria FROM Produto WHERE ID = ?";
                $stmtCategoria = $conexao->prepare($sqlCategoria);
                $stmtCategoria->bind_param("i", $codigoProduto);
                $stmtCategoria->execute();
                $resultadoCategoria = $stmtCategoria->get_result();
                $produtoCategoria = $resultadoCategoria->fetch_assoc()['Categoria'];

                if ($produtoCategoria === 'Calçado') {
                    $sqlDeleteCategoria = "DELETE FROM Calcado WHERE ID_produto = ?";
                } else {
                    $sqlDeleteCategoria = "DELETE FROM Roupa WHERE ID_produto = ?";
                }

                $sqlDeleteImagem = "DELETE FROM Imagem WHERE ID_produto = ?";
                $sqlDeleteCadastro = "DELETE FROM Cadastro_produto WHERE ID_produto = ?";
                $sqlDeleteProduto = "DELETE FROM Produto WHERE ID = ?";
                $sqlDeleteEntrega = "DELETE FROM Entrega WHERE ID_produto = ?";

                $stmtDeleteCategoria = $conexao->prepare($sqlDeleteCategoria);
                $stmtDeleteCategoria->bind_param("i", $codigoProduto);
                $stmtDeleteCategoria->execute();

                $stmtDeleteImagem = $conexao->prepare($sqlDeleteImagem);
                $stmtDeleteImagem->bind_param("i", $codigoProduto);
                $stmtDeleteImagem->execute();

                $stmtDeleteCadastro = $conexao->prepare($sqlDeleteCadastro);
                $stmtDeleteCadastro->bind_param("i", $codigoProduto);
                $stmtDeleteCadastro->execute();

                $stmtDeleteEntrega = $conexao->prepare($sqlDeleteEntrega);
                $stmtDeleteEntrega->bind_param("i", $codigoProduto);
                $stmtDeleteEntrega->execute();

                // Excluir produto principal
                $stmtDeleteProduto = $conexao->prepare($sqlDeleteProduto);
                $stmtDeleteProduto->bind_param("i", $codigoProduto);

                if( $stmtDeleteProduto->execute()){
                    echo "<script>alert('Produto entregue e retirado do site com sucesso.');</script>";
                }

            } else {
                echo "<script>alert('Erro ao atualizar o status: " . $stmtUpdate->error . "');</script>";
            }
            $stmtUpdate->close();
        } else {
            die('Erro na preparação do UPDATE: ' . $conexao->error);
        }
    }

    $idClienteLogado = $_SESSION['id_cliente'];

    $sql = "SELECT 
                Produto.ID AS ID_Produto,
                Produto.Nome AS Nome_Produto,
                Produto.Descricao AS Descricao,
                Imagem.Caminho_imagem AS Caminho_imagem,
                Usuario_geral.Nome_completo AS Solicitante
            FROM 
                Entrega
            INNER JOIN 
                Produto ON Entrega.ID_produto = Produto.ID
            LEFT JOIN 
                Imagem ON Produto.ID = Imagem.ID_produto
            INNER JOIN 
                Cadastro_produto ON Produto.ID = Cadastro_produto.ID_produto
            INNER JOIN 
                Cliente AS Donatario ON Entrega.ID_cliente = Donatario.ID
            INNER JOIN 
                Usuario_geral ON Donatario.ID_usuario_geral = Usuario_geral.ID
            WHERE 
                Cadastro_produto.ID_cliente = ? 
                AND Entrega.ID_cliente != ? 
                AND Entrega.Status_pedido = 'Em espera'";

    $stmt = $conexao->prepare($sql);
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conexao->error);
    }

    $stmt->bind_param('ii', $idClienteLogado, $idClienteLogado);

    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        echo '<div class="container_produto">';
        if ($resultado->num_rows > 0) {
            while ($produto = $resultado->fetch_assoc()) {
                ?>
                <div class="produto">
                    <div>
                        <img src="./<?php echo htmlspecialchars($produto['Caminho_imagem']); ?>" alt="Imagem do produto">
                    </div>
                    <div>
                        <h3><?php echo htmlspecialchars($produto['Nome_Produto']); ?></h3>
                        <p><?php echo htmlspecialchars($produto['Descricao']); ?></p>
                        <small>Solicitado por: <?php echo htmlspecialchars($produto['Solicitante']); ?></small>
                    </div>
                    <div>
                        <form method="post">
                            <input type="hidden" name="codigoProduto" value="<?php echo $produto['ID_Produto']; ?>">
                            <button type="submit" name="entregaProduto">Produto entregue?</button>
                        </form>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>Você não possui doações em espera.</p>";
        }
        echo "</div>";
    } else {
        echo 'Erro na execução da consulta: ' . $stmt->error;
    }

    $stmt->close();
    $conexao->close();
} else {
    echo "<script>alert('É necessário fazer login para acessar esta página.');
    window.location.href = 'home.php?dir=paginas&&file=loginusu';
    </script>";
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

    .container_produto {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .produto {
        border: 1px solid #ccc;
        padding: 15px;
        text-align: center;
        width: 250px;
    }

    .produto img {
        max-width: 100%;
        height: auto;
    }
</style>
