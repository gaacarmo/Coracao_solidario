<link rel="stylesheet" href="./CSS/carrinho.css">
<div class="titulo">
    <h1>Minhas Doações</h1>
    <a href="./index.php"><img class="voltar" src="assets/de-volta.png" alt="Voltar"></a>
    <?php
    // Verifica se o usuário está logado
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

    // Verifica se foi enviado o formulário para atualizar o status
    if (isset($_POST['entregaProduto']) && isset($_POST['codigoProduto'])) {
        $codigoProduto = $_POST['codigoProduto'];
        error_log("Recebido para atualização: ID Produto = $codigoProduto");

        $sqlEntrega = "UPDATE Entrega SET Status_pedido = 'Entregue' WHERE ID_produto = ?";
        $stmtUpdate = $conexao->prepare($sqlEntrega);

        if ($stmtUpdate) {
            $stmtUpdate->bind_param("i", $codigoProduto);
            if ($stmtUpdate->execute()) {
                echo "<script>alert('Status atualizado com sucesso.');</script>";
            } else {
                echo "<script>alert('Erro ao atualizar o status: " . $stmtUpdate->error . "');</script>";
                error_log("Erro ao atualizar: " . $stmtUpdate->error);
            }
            $stmtUpdate->close();
        } else {
            die('Erro na preparação do UPDATE: ' . $conexao->error);
        }
    }

    // ID do cliente logado
    $idClienteLogado = $_SESSION['id_cliente'];
    error_log("Cliente logado: $idClienteLogado");

    // SQL para buscar os produtos cadastrados pelo cliente logado e solicitados por outro cliente
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

    // Bind do ID do cliente logado como doador e cliente solicitante
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
        error_log("Erro na execução da consulta: " . $stmt->error);
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
