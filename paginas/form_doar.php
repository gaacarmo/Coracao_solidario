<link rel="stylesheet" href="./CSS/form_doar.css">
<?php

// Verifica se o usuário está logado
if (!(isset($_SESSION['is_logged_in'])) || $_SESSION['is_logged_in'] !== true) {
    echo "<script>
    alert('Para acessar esta página, é necessário fazer login.');
    window.location.href = 'home.php?dir=paginas&&file=loginusu';
    </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = $_POST;
    require_once "conexao.php";
    $conexao = novaConexao();

    $sql1 = "INSERT INTO Produto (Nome, Categoria, Publico_alvo, Descricao, Condicao) VALUES (?, ?, ?, ?, ?)";
    $stmt1 = $conexao->prepare($sql1);
    $params1 = [
        $dados['NomeProduto'],
        $dados['Categoria'],
        $dados['Publico_alvo'],
        $dados['Descricao'],
        $dados['Condicao'],
        
    ];
    $stmt1->bind_param("sssss", ...$params1);
    

    if ($stmt1->execute()) {
        $produtoID = $conexao->insert_id;
        $id_cliente = $_SESSION['id_cliente'];
        $sql2 = "UPDATE Cliente SET Bairro = ?, Logradouro = ?, Numero = ? WHERE id= ?";
        $stmt2 = $conexao->prepare($sql2);
        $params2 = [
            $dados['Bairro'],
            $dados['Logradouro'],
            $dados['Numero'],
            $id_cliente,
        ];
        $stmt2->bind_param("sssi", ...$params2);
        if ($stmt2->execute()) {
            
            // Inserindo na tabela Cadastro_produto
            $sql3 = "INSERT INTO Cadastro_produto (ID_cliente, ID_produto) VALUES (?,?)";
            $stmt3 = $conexao->prepare($sql3);
            $params3 = [$id_cliente, $produtoID];
            $stmt3->bind_param("ii", ...$params3);

            if ($stmt3->execute()) {
                
                // Inserindo dados em Calcado ou Roupa com base na Categoria
                if ($dados['Categoria'] == 'Calçado') {
                    $sql4 = "INSERT INTO Calcado (Tamanho_calcado, Cor_calcado, ID_produto) VALUES (?, ?, ?)";
                    $stmt4 = $conexao->prepare($sql4);
                    $params4 = [
                        $dados['Tamanho'],
                        $dados['Cor'],
                        $produtoID
                    ];
                    $stmt4->bind_param("ssi", ...$params4);
                } else {
                    $sql4 = "INSERT INTO Roupa (Tamanho_roupa, Cor_roupa, ID_produto) VALUES (?, ?, ?)";
                    $stmt4 = $conexao->prepare($sql4);
                    $params4 = [
                        $dados['Tamanho'],
                        $dados['Cor'],
                        $produtoID
                    ];
                    $stmt4->bind_param("ssi", ...$params4);
                }

                if ($stmt4->execute()) {
                    $uploadDir = 'uploads/';
                    if (isset($_FILES['Imagem']) && is_array($_FILES['Imagem']['tmp_name'])) {
                        foreach ($_FILES['Imagem']['tmp_name'] as $key => $tmpName) {
                            $imageName = basename($_FILES['Imagem']['name'][$key]);
                            $targetFile = $uploadDir . $imageName;

                            if (move_uploaded_file($tmpName, $targetFile)) {
                                $sqlImage = "INSERT INTO Imagem (Caminho_imagem, ID_produto) VALUES (?, ?)";
                                $stmtImage = $conexao->prepare($sqlImage);
                                $stmtImage->bind_param("si", $targetFile, $produtoID);

                                if (!$stmtImage->execute()) {
                                    echo "Erro ao inserir caminho da imagem: " . $stmtImage->error;
                                    exit();
                                }
                            } else {
                                echo "Erro ao fazer upload da imagem: " . $_FILES['Imagem']['name'][$key];
                                exit();
                            }
                        }
                    }

                }else{
                    echo "Erro ao inserir na tabela Imagem: " . $stmt4->error;
                    exit();
                }    
            } else {
                echo "Erro ao inserir na tabela Cadastro produto: " . $stmt3->error;
                exit();
            }
        } else{
            echo "Erro ao inserir na tabela cliente: " . $stmt2->error;
            exit();
        }
    } else {
        echo "Erro ao inserir produto: " . $stmt1->error;
        exit();
    }
}

?>
<a href="./index.php"><img class="voltar" src="assets/de-volta.png" alt="Voltar"></a>
<form class="form"  method="POST" enctype="multipart/form-data">
    <div>
        <label class="espaçamento" for="NomeProduto">Qual o nome do produto?</label>
        <input type="text" name="NomeProduto" id="NomeProduto" placeholder="Ex: Camisa" class="input" required>
    </div>

    <div class="select">
        <label class="espaçamento" for="PublicoAlvo">Qual o público alvo de seu produto?</label>
        <select name="Publico_alvo" id="PublicoAlvo" required> <!-- Campo obrigatório --->
            <option value="">Selecione</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Infantil">Infantil</option>
        </select>
    </div>

    <div class="select">
        <label class="espaçamento" for="CategoriaProduto">Selecione a categoria do produto</label>
        <select name="Categoria" id="CategoriaProduto" required> <!-- Campo obrigatório --->
            <option value="">Selecione</option>
            <option value="Calçado">Calçado</option>
            <option value="Roupa">Roupa</option> 
        </select>
    </div>

    <div class="select">
        <label class="espaçamento" for="TamanhoProduto">Selecione o tamanho do produto</label>
        <select name="Tamanho" id="TamanhoProduto" required> <!-- Campo obrigatório --->
            <option value="">Selecione</option>
            <option value="P">P</option>
            <option value="M">M</option>
            <option value="G">G</option>
            <option value="36">36</option>
            <option value="38">38</option>
            <option value="40">40</option>
            <option value="42">42</option>
            <option value="outro">Outro</option>
        </select>
    </div>

    <div class="condicao">
        <label class="espaçamento" for="Condicao">Condição do produto</label>
        <div class="condicao_status">
            <div>
                <label for="">Usado</label>
                <input type="radio" name="Condicao" id="Usado" value="Usado" required> <!-- Campo obrigatório --->
            </div>
            <div>
                <label for="">Semiusado</label>
                <input type="radio" name="Condicao" id="Semiusado" value="Semiusado">
            </div>
            <div>
                <label for="">Novo</label>
                <input type="radio" name="Condicao" id="Novo" value="Novo">
            </div>
        </div>
    </div>

    <div>
        <label class="espaçamento" for="CorProduto">Qual a cor do produto?</label>
        <input type="text" name="Cor" id="CorProduto" placeholder="Ex: Azul" class="input" required> <!-- Campo obrigatório --->
    </div>
    
    <div class="endereco">
        <label for="Bairro">Bairro</label>
        <input type="text" name="Bairro" id="Bairro" placeholder="Ex: Novo Mundo" class="input">

        <label for="Rua">Rua</label>
        <input type="text" name="Logradouro" id="Logradouro" placeholder="Ex: Rua dos açoures" class="input">

        <label for="Numero">Número</label>
        <input type="text" name="Numero" id="Numero" placeholder="Ex: 205" class="input">
    </div>

    <div>
        <label class="espaçamento" for="DescricaoProduto">Descrição do produto</label>
        <textarea name="Descricao" id="DescricaoProduto" class="descrição" required></textarea> <!-- Campo obrigatório --->
    </div>

    <div>
        <label class="espaçamento" for="ImagemProduto">Coloque uma ou mais imagens</label>
        <input type="file" name="Imagem[]" id="ImagemProduto" accept="image/*" multiple required> <!-- Campo obrigatório --->
    </div>  

    <div>
        <button type="reset">Limpar formulário</button>
        <button type="submit">Enviar </button>
        <button type="button" onclick="window.location.href='index.php'">Cancelar</button> <!-- Retorna para a página principal --->
    </div>
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
    footer{
        bottom: 0;
        position: relative;
        width: 100%;
        text-align: center;
    }

</style>