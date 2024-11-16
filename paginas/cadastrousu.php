<?php
function validaCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    

    if (strlen($cpf) != 11) {
        return false;
    }


    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    
    return true;
}
function validaTelefone($telefone) {
    
    $telefone = preg_replace('/[^0-9]/', '', $telefone);

    
    return (strlen($telefone) >= 10 && strlen($telefone) <= 11);
}


if(count($_POST) > 0){
    $dados = $_POST;
    $erros = [];

    // Validações
    if(isset($dados['nome_completo']) && trim($dados['nome_completo']) === ""){
        $erros['nome_completo'] = "Nome é obrigatório";
    }

    if(isset($dados["Data_nascimento"])) {
        $data = DateTime::createFromFormat('d/m/Y', $dados['Data_nascimento']);
        if (!$data || $data->format('d/m/Y') !== $dados['Data_nascimento']) {
            $erros['Data_nascimento'] = 'Data deve estar no padrão dd/mm/aaaa';
        }
    }

    if(!filter_var($dados['Email'], FILTER_VALIDATE_EMAIL)){
        $erros['Email'] = "Email inválido";
    }
    
    if(isset($dados['Senha_cliente'])){
        $senhaUsuario = trim($dados['Senha_cliente']);
        if(strlen($senhaUsuario) < 3 || strlen($senhaUsuario) > 20){
            $erros['Senha_cliente'] = "A senha deve ter entre 3 e 20 caracteres";
        }
    }

    if(isset($dados['CPF']) && !validaCPF($dados['CPF'])) {
        $erros['CPF'] = "CPF inválido";
    }

    if(isset($dados['Telefone']) && !validaTelefone($dados['Telefone'])){   
        $erros['Telefone'] = "Telefone inválido";
    }

    if(isset($dados['Usuario_cliente'])){
        $nomeUsuario = trim($dados['Usuario_cliente']);
        if(strlen($nomeUsuario) < 3 || strlen($nomeUsuario) > 20) {
            $erros['Usuario_cliente'] = "O nome de usuário deve ter entre 3 e 20 caracteres";
        }
    } 

    // Se não houver erros, prosseguir com a inserção
    if(count($erros) == 0){
        require_once "conexao.php";
        $conexao = novaConexao();   

        // Inserir em Usuario_geral
        $sql1 = "INSERT INTO Usuario_geral (nome_completo, Data_nascimento, Email, CPF, Telefone) VALUES (?, ?, ?, ?, ?)";
        $stmt1 = $conexao->prepare($sql1);
        $params1 = [
            $dados['nome_completo'],
            $data ? $data->format('Y-m-d') : null,
            $dados['Email'],
            $dados['CPF'],
            $dados['Telefone'],
        ];
        $stmt1->bind_param("sssss", ...$params1);

        // Executar a primeira inserção
        if($stmt1->execute()){
            $id_usuario = $conexao->insert_id;
            // Inserir em Cliente
            $sql2 = "INSERT INTO Cliente (Usuario_cliente, Senha_cliente, ID_usuario_geral) VALUES (?, ?,?)";
            $stmt2 = $conexao->prepare($sql2);

            $params2 = [
                $dados['Usuario_cliente'], // Garantido que não seja nulo
                $dados['Senha_cliente'],
                $id_usuario
            ];
            $stmt2->bind_param("ssi", ...$params2);

            // Executar a segunda inserção
            if($stmt2->execute()){
                unset($dados); 
                // Opcional: redirecionar ou mostrar mensagem de sucesso
            } else {
                // Tratar erros na segunda inserção
                $erros['insert'] = "Erro ao inserir cliente: " . $stmt2->error;
            }
        } else {
            // Tratar erros na primeira inserção
            $erros['insert'] = "Erro ao inserir usuário: " . $stmt1->error;
        }
    }
}


?>


<link rel="stylesheet" href="./CSS/cadastro.css">
<h1>Cadastro</h1>
<a href="home.php?dir=paginas&file=loginusu"><img class="voltar" src="assets/de-volta.png" alt=""></a>

<form action="#" method="post" class="form">
    <div class="form-row">
        <div class="form-group col-md-9">
            <label for="nome_completo">Nome</label>
            <br>
            <input type="text" 
                class="input <?= isset($erros['nome_completo']) ? 'is-invalid' : '' ?>"
                id="nome_completo" name="nome_completo" placeholder="Digite seu Nome..."
                value="<?= isset($dados['nome_completo']) ? $dados['nome_completo'] : '' ?>">
            <div class="alerta">
                <?= isset($erros['nome_completo']) ? $erros['nome_completo'] : '' ?>
            </div>
        </div>
        <div class="form-group col-md-3">
            <label for="Data_nascimento">Data de nascimento</label>
            
            <input type="text" class="input <?= isset($erros['Data_nascimento']) ? 'is-invalid' : '' ?>" 
                id="Data_nascimento" name="Data_nascimento" placeholder="dd/mm/aaaa"
                value="<?= isset($dados['Data_nascimento']) ? $dados['Data_nascimento'] : '' ?>">
            <div class="alerta">
                <?= isset($erros['Data_nascimento']) ? $erros['Data_nascimento'] : '' ?>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="Email">Email</label>
            <br>
            <input type="text" class="input <?= isset($erros['Email']) ? 'is-invalid' : '' ?>" 
                id="Email" name="Email" placeholder="Digite seu email..." value="<?= isset($dados['Email']) ? $dados['Email'] : '' ?>">
            <div class="alerta">
                <?= isset($erros['Email']) ? $erros['Email'] : '' ?>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="Senha_cliente">Senha</label>
            <br>
            <input type="password" class="input <?= isset($erros['Senha_cliente']) ? 'is-invalid' : '' ?>" 
                id="Senha_cliente" name="Senha_cliente" placeholder="Digite sua Senha" value="<?= isset($dados['Senha_cliente']) ? $dados['Senha_cliente'] : '' ?>">
            <div class="alerta">
                <?= isset($erros['Senha_cliente']) ? $erros['Senha_cliente'] : '' ?>
            </div>
        </div>
    </div>

    <div class="form-row">
    <div class="form-group col-md-12">
            <label for="Usuario_cliente">Nome de Usuário</label>
            <br>
            <input type="text" class="input <?= isset($erros['Usuario_cliente']) ? 'is-invalid' : '' ?>" 
                id="Usuario_cliente" name="Usuario_cliente" placeholder="Escolha um Nome de Usuário..." 
                value="<?= isset($dados['Usuario_cliente']) ? $dados['Usuario_cliente'] : '' ?>">
            <div class="alerta">
                <?= isset($erros['Usuario_cliente']) ? $erros['Usuario_cliente'] : '' ?>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="CPF">CPF</label>
            
            <input type="number" 
                class="input <?= isset($erros['CPF']) ? 'is-invalid' : '' ?>" 
                id="CPF" name="CPF" 
                placeholder="xxx.xxx.xxx-xx" 
                value="<?= isset($dados['CPF']) ? $dados['CPF'] : '' ?>">
            <div class="alerta">
                <?= isset($erros['CPF']) ? $erros['CPF'] : '' ?>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="Telefone">Telefone</label>
            <input type="text" class="input <?= isset($erros['Telefone']) ? 'is-invalid' : '' ?>" 
                id="Telefone" name="Telefone" placeholder="(DDD)00000000" value="<?= isset($dados['Telefone']) ? $dados['Telefone'] : '' ?>">
            <div class="alerta">
                <?= isset($erros['Telefone']) ? $erros['Telefone'] : '' ?>
            </div>

        </div>
    </div>

        <div class= "botao">
            <button class="btn" type="submit">Cadastrar</button>
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
</style>