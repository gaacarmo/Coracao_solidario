<link rel="stylesheet" href="./CSS/carrinho.css">
<div class="titulo">
    <h1>Carrinho</h1>
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
require_once 'conexao.php';
$conexao = novaConexao();

if (isset($_POST['finalizar'])) {
    if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
        $sqlCliente = "SELECT ID FROM Cliente WHERE Usuario_cliente = ?";
        $stmtCliente = $conexao->prepare($sqlCliente);
        $stmtCliente->bind_param("s", $_SESSION['username']);
        $stmtCliente->execute();
        $resultCliente = $stmtCliente->get_result();

        if ($resultCliente->num_rows > 0) {
            $cliente = $resultCliente->fetch_assoc();
            $idCliente = $cliente['ID'];

            $sqlVerificar = "SELECT COUNT(*) AS total FROM Entrega WHERE ID_produto = ? AND ID_cliente = ?";
            $sqlEntrega = "INSERT INTO Entrega (ID_produto, ID_cliente, Status_pedido) VALUES (?, ?, ?)";
            $stmtVerificar = $conexao->prepare($sqlVerificar);
            $stmtEntrega = $conexao->prepare($sqlEntrega);

            foreach ($_SESSION['carrinho'] as $idProduto) {
                $stmtVerificar->bind_param("ii", $idProduto, $idCliente);
                $stmtVerificar->execute();
                $resultVerificar = $stmtVerificar->get_result();
                $row = $resultVerificar->fetch_assoc();

                if ($row['total'] == 0) {
                    $statusPedido = "Em espera";
                    $stmtEntrega->bind_param("iis", $idProduto, $idCliente, $statusPedido);
                    $stmtEntrega->execute();
                }
            }

            unset($_SESSION['carrinho']);
            echo "<script>alert('Pedido finalizado com sucesso!'); window.location.href = './index.php';</script>";
        } else {
            echo "<script>alert('Cliente não encontrado. Por favor, tente novamente.');</script>";
        }
    } else {
        echo "<script>alert('Seu carrinho está vazio.');</script>";
    }
}

if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
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
} else {
    echo "<p>Seu carrinho está vazio.</p>";
}
?>

<form class="form_finalizar" method="POST">
    <button type="submit" name="finalizar">Finalizar pedido</button>
</form>

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

    footer{
        bottom: 0;
        position: relative;
        width: 100%;
        text-align: center;
    }
</style>
