<h1>Login</h1><a href="home.php?dir=paginas&file=loginusu"><img class="voltar" src="assets/de-volta.png" alt=""></a>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


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

    if(isset($dados['nome_completo']) && trim($dados['nome_completo']) === ""){

        $erros['nome_completo'] = "Nome é obrigatório";
    }

    if(isset($dados[ "Data_nascimento"])) {
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
        if(strlen($senhaUsuario) < 3 || strlen($senhaUsuario) >15){
            $erros['Senha_cliente'] = "A senha deve ter entre 3 e 20 caracteres";
        }
    }

    
    if(isset($dados['CPF']) && !validaCPF($dados['CPF'])) {
        $erros['CPF'] = "CPF inválido";
    }
    $salario = str_replace(',', '.', $dados['Telefone']);
    if(isset($dados['Telefone'])&& !validaTelefone($dados['Telefone'])){
        $erros['Telefone'] = "Telefone inválido";
    }
    if(isset($dados['nome_usuario'])){
        $nomeUsuario = trim($dados['nome_usuario']);
        if(strlen($nomeUsuario) < 3 || strlen($nomeUsuario) > 20) {
            $erros['nome_usuario'] = "O nome de usuário deve ter entre 3 e 20 caracteres";
        }
    }

    if(count($erros) == 0){
        require_once "conexao.php";

        $sql= "INSERT INTO Usuario_geral
        (nome_completo,Data_nascimento, Email, senha_cliente, CPF, Telefone)
        VALUES (?,?,?,?,?,?)";

        $conexao = novaConexao();
        $stmt = $conexao->prepare($sql);
        $params = [
            $dados['nome_completo'],
            $dados ? $data->format('Y-m-d') : null, #caso data seja seja preeenchido ele passa para a verficaçao, caso nao, sera nulo
            $dados['Email'],
            $dados['Senha_cliente'],
            $dados['CPF'],
            $dados['Telefone'],
        ];
        $stmt->bind_param("ssssss", ...$params); #parametros s= string | i = int | d = decimal
        

        if($stmt->execute()){
            unset($dados); 
        }
    }
}
?>

<form action="#" method="post">
    <div class="form-row">
        <div class="form-group col-md-9">
            <label for="nome_completo">Nome</label>
            <input type="text" 
                class="form-control <?= isset($erros['nome_completo']) ? 'is-invalid' : '' ?>"
                id="nome_completo" name="nome_completo" placeholder="Digite seu Nome..."
                value="<?= isset($dados['nome_completo']) ? $dados['nome_completo'] : '' ?>">
            <div class="invalid-feedback">
                <?= isset($erros['nome_completo']) ? $erros['nome_completo'] : '' ?>
            </div>
        </div>
        <div class="form-group col-md-3">
            <label for="Data_nascimento">Data de nascimento</label>
            <input type="text" class="form-control <?= isset($erros['Data_nascimento']) ? 'is-invalid' : '' ?>" 
                id="Data_nascimento" name="Data_nascimento" placeholder="dd/mm/aaaa"
                value="<?= isset($dados['Data_nascimento']) ? $dados['Data_nascimento'] : '' ?>">
            <div class="invalid-feedback">
                <?= isset($erros['Data_nascimento']) ? $erros['Data_nascimento'] : '' ?>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="Email">Email</label>
            <input type="text" class="form-control <?= isset($erros['Email']) ? 'is-invalid' : '' ?>" 
                id="Email" name="Email" placeholder="Digite seu email..." value="<?= isset($dados['Email']) ? $dados['Email'] : '' ?>">
            <div class="invalid-feedback">
                <?= isset($erros['Email']) ? $erros['Email'] : '' ?>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="Senha_cliente">Senha</label>
            <input type="password" class="form-control <?= isset($erros['Senha_cliente']) ? 'is-invalid' : '' ?>" 
                id="Senha_cliente" name="Senha_cliente" placeholder="Digite sua Senha" value="<?= isset($dados['Senha_cliente']) ? $dados['Senha_cliente'] : '' ?>">
            <div class="invalid-feedback">
                <?= isset($erros['Senha_cliente']) ? $erros['Senha_cliente'] : '' ?>
            </div>
        </div>
    </div>

    <div class="form-row">
    <div class="form-group col-md-12">
            <label for="nome_usuario">Nome de Usuário</label>
            <input type="text" class="form-control <?= isset($erros['nome_usuario']) ? 'is-invalid' : '' ?>" 
                id="nome_usuario" name="nome_usuario" placeholder="Escolha um Nome de Usuário..." 
                value="<?= isset($dados['nome_usuario']) ? $dados['nome_usuario'] : '' ?>">
            <div class="invalid-feedback">
                <?= isset($erros['nome_usuario']) ? $erros['nome_usuario'] : '' ?>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="CPF">CPF</label>
            <input type="number" 
                class="form-control <?= isset($erros['CPF']) ? 'is-invalid' : '' ?>" 
                id="CPF" name="CPF" 
                placeholder="xxxxxxxxxxx" 
                value="<?= isset($dados['CPF']) ? $dados['CPF'] : '' ?>">
            <div class="invalid-feedback">
                <?= isset($erros['CPF']) ? $erros['CPF'] : '' ?>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="Telefone">Telefone</label>
            <input type="text" class="form-control <?= isset($erros['Telefone']) ? 'is-invalid' : '' ?>" 
                id="Telefone" name="Telefone" placeholder="dd000000000" value="<?= isset($dados['Telefone']) ? $dados['Telefone'] : '' ?>">
            <div class="invalid-feedback">
                <?= isset($erros['Telefone']) ? $erros['Telefone'] : '' ?>
            </div>

        </div>
    </div>

    <button class="btn btn-primary btn-lg">Enviar</button>
</form>
<style>
    a .voltar{
    height: 30px;
    margin: 0px;
    right: 100px;
}  
</style>