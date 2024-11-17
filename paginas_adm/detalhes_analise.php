<?php


require_once './paginas/conexao.php';
$conexao = novaConexao();
if (!(isset($_SESSION['is_logged_admin'])) || $_SESSION['is_logged_admin'] !== true) {
    echo "<script>alert('Para acessar esta página, é necessário fazer login.');
    window.location.href = 'home.php?dir=paginas&&file=loginadm';
    </script>";
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!$conexao) {
    die("<p>Erro na conexão com o banco de dados.</p>");
}

if (isset($_GET['codigo']) && is_numeric($_GET['codigo'])) {
    $codigo = (int)$_GET['codigo'];

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
            $tamanho = !empty($dados['Tamanho_calcado']) ? "Calçado" : (!empty($dados['Tamanho_roupa']) ? "Roupa" : 'N/A');
        } else {
            echo "<p>Produto não encontrado.</p>";
            exit;
        }
    } else {
        echo "<p>Erro ao buscar o produto.</p>";
        exit;
    }
} else {
    echo "<p>Código do produto inválido ou não especificado.</p>";
    exit;
}

// Ações do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['username_admin'])) {
        $Usuario_admin = $_SESSION['username_admin'];

        if (isset($_POST['aceitar'])) {
            $sql = "UPDATE Produto SET Usuario_admin = ? WHERE ID = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('si', $Usuario_admin, $codigo);

            if ($stmt->execute()) {
                echo "<script>alert('Produto adicionado ao site com sucesso');</script>";
                echo "<script>window.location.href = 'home_adm.php?dir=paginas_adm&file=analise_produtos';</script>";
                exit;
            } else {
                echo "<script>alert('Erro ao adicionar o produto ao site');</script>";
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

                $deletaCadastro = "DELETE FROM Cadastro_produto WHERE ID_produto = ?";
                $stmt3 = $conexao->prepare($deletaCadastro);
                $stmt3->bind_param('i', $codigo);
                $stmt3->execute();

                $deletaProduto = "DELETE FROM Produto WHERE ID = ?";
                $stmt4 = $conexao->prepare($deletaProduto);
                $stmt4->bind_param('i', $codigo);
                $stmt4->execute();

                $conexao->commit();
                echo "<script>alert('Produto recusado e removido com sucesso');</script>";
            } catch (Exception $e) {
                $conexao->rollback();
                echo "<p>Erro ao recusar o produto: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    } else {
        echo "<p>Usuário administrador não autenticado.</p>";
    }
}
?>
<h1 class="Titulo">Detalhes produtos</h1>
<main>
    <div class="container_principal">
        <div>
            <img src="./<?php echo htmlspecialchars($dados['Caminho_imagem'] ?? 'imagens/placeholder.png'); ?>" class="imagem_principal" alt="Imagem do produto">
        </div>
        <div>
            <h1 class="nome-produto"><?php echo htmlspecialchars($dados['Nome']); ?></h1>
            <p class="tamanho-produto">Público alvo: <?php echo htmlspecialchars($dados['Publico_alvo']); ?></p>
            <p class="localizacao-produto">Condição do produto: <?php echo htmlspecialchars($dados['Condicao']); ?></p>
            <p class="condicao-produto">Categoria: <?php echo htmlspecialchars($dados['Categoria']); ?></p>
            <p>Tamanho: <?php echo htmlspecialchars($tamanho); ?></p>
            <p>Localização do produto: <?php echo htmlspecialchars($dados['Logradouro'] . ', ' . $dados['Numero'] . '/ ' . $dados['Bairro'] . '/ Curitiba'); ?></p>
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



h1.Titulo {
    text-align: center;
    margin-top: 20px;
    font-size: 2.5rem;
    color: #444;
}

.container_principal {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 800px;
    margin: 20px auto;
    background: #fff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

.container_principal img.imagem_principal {
    max-width: 100%;
    height: auto;

}

.imagem_principal {
    width: 350px;
    height: 300px;
}

.container_principal div:last-child {
    padding: 20px;
    width: 100%;
}

.container_principal h1.nome-produto {
    font-size: 2rem;
    color: #6200ea;
    margin-bottom: 10px;
}

.container_principal p {
    margin: 5px 0;
    line-height: 1.6;
    font-size: 1.1rem;
    color: #555;
}

.container_botao {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.container_botao form {
    display: flex;
    gap: 15px;
}

.container_botao button {
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.container_botao button[name="recusar"] {
    background-color: #e63946;
}

.container_botao button[name="recusar"]:hover {
    background-color: #d62839;
}

.container_botao button[name="aceitar"] {
    background-color: #38b000;
}

.container_botao button[name="aceitar"]:hover {
    background-color: #32a000;
}

@media (min-width: 768px) {
    .container_principal {
        flex-direction: row;
        gap: 20px;
    }

    .container_principal div:last-child {
        padding: 40px;
    }
}

</style>
