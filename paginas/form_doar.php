<h1>Fazer doação</h1>
<link rel="stylesheet" href="./form_doar.css">

<form class="form" method="POST" enctype="multipart/form-data">
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
            <option value="GG">GG</option>
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
        <button type="submit">Enviar</button>
        <button type="button" onclick="window.location.href='index.php'">Cancelar</button> <!-- Retorna para a página principal --->
    </div>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = $_POST;
    require_once "conexao.php";
    $conexao = novaConexao();

    $sql1 = "INSERT INTO Produto (Nome, Categoria, Publico_alvo, Descricao, Condicao) VALUES (?, ?, ?, ?,?)";
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
        $produtoID = $conexao->insert_id; // Como o ID é auto incrementado no sql, ele recupera o ID da última linha adicionada no $stmt1

        // Se a categoria escolhida pelo usuário é calçado, então os dados restantes serão adicionados na tabela calcado
        if ($dados['Categoria'] == 'Calçado') {
            $sql2 = "INSERT INTO Calcado (Tamanho_calcado, Cor_calcado, ID_produto) VALUES (?, ?, ?)";
            $stmt2 = $conexao->prepare($sql2);
            $params2 = [
                $dados['Tamanho'],
                $dados['Cor'],
                $produtoID // Adiciona o ID do produto aqui
            ];
            $stmt2->bind_param("ssi", ...$params2);

            //Aqui vai ser adicionado na tabela Roupa
        } else {
            $sql2 = "INSERT INTO Roupa (Tamanho_roupa, Cor_roupa, ID_produto) VALUES (?, ?, ?)";
            $stmt2 = $conexao->prepare($sql2);
            $params2 = [
                $dados['Tamanho'],
                $dados['Cor'],
                $produtoID // Adiciona o ID do produto aqui
            ];
            $stmt2->bind_param("ssi", ...$params2);
        }

        if ($stmt2->execute()) {
            // 4. Processamento das imagens
            $uploadDir = 'uploads/'; //Define a pasta em que será gravado a imagem, não esqueçam de criar uma pasta 'uploads' para testar o código
            if (isset($_FILES['Imagem']) && is_array($_FILES['Imagem']['tmp_name'])) {//$_FILES verifica se foi enviado do html alguma imagem, isso no input:file
                //o a outra parte do && verifica se é um array, pois permitimos que possa ser enviado mais de uma imagem
                foreach ($_FILES['Imagem']['tmp_name'] as $key => $tmpName) { // itera sobre cada elemento do array $_FILES['Imagem']['tmp_name']
                    $imageName = basename($_FILES['Imagem']['name'][$key]); //Acessa o nome original do arquivo enviado, usando o índice $key do arquivo que está sendo processado atualmente.
                    $targetFile = $uploadDir . $imageName; //define para onde exatamente vai ser enviado o arquivo

                    if (move_uploaded_file($tmpName, $targetFile)) { //verifica se o arquivo já foi movido para a pasta no diretório
                        // 5. Insere o caminho da imagem na tabela Imagem
                        $sqlImage = "INSERT INTO Imagem (Caminho_imagem, ID_produto) VALUES (?, ?)";
                        $stmtImage = $conexao->prepare($sqlImage);
                        $stmtImage->bind_param("si", $targetFile, $produtoID);

                        if (!$stmtImage->execute()) {
                            echo "Erro ao inserir caminho da imagem: " . $stmtImage->error;
                        }
                    } else {
                        echo "Erro ao fazer upload da imagem: " . $_FILES['Imagem']['name'][$key];
                    }
                }
            } else {
                echo "Nenhuma imagem foi enviada.";
            }
        } else {
            echo "Erro ao inserir na tabela de categoria: " . $stmt2->error;
        }
    } else {
        echo "Erro ao inserir produto: " . $stmt1->error;
    }
}
?>
