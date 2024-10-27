<h1>Fazer doação</h1>
<link rel="stylesheet" href="./form_doar.css">

<form class="form">
    <div>
        <label class="espaçamento" for="">Qual o nome do produto?</label>
        <input type="text" name="NomeProduto" id="NomeProduto" placeholder="Ex: Camisa" class="input">
    </div>

    <div class="select">
        <label class="espaçamento" for="">Qual o público alvo de seu produto?</label>
        <select name="" id="">
            <option value="">Selecione</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Infantil">Infantil</option>
        </select>
    </div>

    <div class="select">
        <label class="espaçamento" for="">Selecione a categoria do produto</label>
        <select name="categoria" id="categoria">
            <option value="">Selecione</option>
            <option value="Calçado">Calçado</option>
            <option value="Roupa">Roupa</option> 
        </select>
    </div>

    <div class="select">
        <label class="espaçamento" for="">Selecione o tamanho do produto</label>
        <select name="tamanho" id="tamanho">
            <option value="">Selecione</option>
            <option value="">xxx</option>
            <option value="">xxx</option>
            <option value="">xxx</option>
            <option value="">xxx</option>
            <option value="">xxx</option>
        </select>
    </div>

    <div class="condicao">
        <label class="espaçamento" for="">Condição do produto</label>
        <div class="condicao_status">
            <div>
                <label for="">Usado</label>
                <input type="radio" name="condicao" id="">
            </div>

            <div>
                <label for="">Semiusado</label>
                <input type="radio" name="condicao" id="">
            </div>

            <div>
                <label for="">Novo</label>
                <input type="radio" name="condicao" id="">
            </div>
        </div>
    </div>

    <div>
        <label class="espaçamento" for="">Qual a cor do produto?</label>
        <input type="text" name="" id="" placeholder="Ex: Azul" class="input">
    </div>

    <div>
        <label class="espaçamento" for="">Descrição do produto</label>
        <textarea name="descricao" id="descricao" class="descrição"></textarea>
    </div>

    <div>
        <label class="espaçamento" for="">Coloque uma ou mais imagens</label>
        <input type="file" name="imagem" id="imagem" accept="image/*" multiple>
    </div>  

    <div>
        <button type="reset">Limpar formulário</button>
        <button type="submit">Enviar</button>
        <button>Cancelar</button>
    </div>
</form>