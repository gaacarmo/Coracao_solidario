<?php
ob_start(); // Inicia o buffer de saída
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = $_POST;
    require_once "conexao.php";
    $conexao = novaConexao();
?>
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