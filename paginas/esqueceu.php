<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <link rel="stylesheet" href="./CSS/login.css">

<h1>Esqueceu sua Senha?</h1>
<form class="form" method="post">
    <div class="flex-column">
        <label>Email </label>
        <div class="inputForm">
            <input type="email" name="email" class="input" placeholder="Insira seu email">
        </div>
    </div>
    <div class="flex-column">
        <label>Código de Verificação </label>
        <div class="inputForm">
            <input type="text" name="codigo" class="input" placeholder="Insira o código de verificação">
        </div>
    </div>
    <div class="flex-column">
        <label>Nova Senha </label>
        <div class="inputForm">
            <input type="password" name="nova_senha" class="input" placeholder="Insira sua nova senha">
        </div>
    </div>
    <div class="flex-column">
        <label>Confirme sua senha</label>
        <div class="inputForm">
            <input type="password" name="nova_senha_c" class="input" placeholder="Confirme sua nova senha">
        </div>

    </div>

    <button class="button-entrar">Confirmar</button>
</form>


</body>
</html>
<style>
.form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    background-color: #ffffff;
    padding: 30px;
    width: 450px;
    border-radius: 20px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto;
}

h1 {
    text-align: center;
    color: #151717;
    margin-bottom: 20px;
}

::placeholder {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}

.flex-column > label {
    color: #151717;
    font-weight: 600;
    margin-bottom: 5px;
}

.inputForm {
    border: 1.5px solid #ecedec;
    border-radius: 10px;
    height: 50px;
    display: flex;
    align-items: center;
    padding-left: 10px;
    transition: 0.2s ease-in-out;
}

.input {
    margin-left: 10px;
    border-radius: 10px;
    border: none;
    width: 100%;
    height: 100%;
}

.input:focus {
    outline: none;
}

.inputForm:focus-within {
    border: 1.5px solid #2d79f3;
} 

.flex-row {
    display: flex;
    justify-content: center;
    margin-top: 10px;
}

.span {
    font-size: 14px;
    color: #2d79f3;
    font-weight: 500;
    cursor: pointer;
}

.button-entrar {
    margin-top: 20px;
    background-color: #151717;
    border: none;
    color: white;
    font-size: 15px;
    font-weight: 500;
    border-radius: 10px;
    height: 50px;
    width: 100%;
    cursor: pointer;
    transition: background-color 0.3s;
}

.button-entrar:hover {
    background-color: #252727;
}
</style>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmaSenha = $_POST['nova_senha_c'] ?? '';
    $email = $_POST['email'] ?? '';

    // Validação de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red; text-align: center;'>Formato de email inválido.</p>";
    } elseif ($novaSenha !== $confirmaSenha) {
        echo "<p style='color: red; text-align: center;'>As senhas não conferem. Por favor, tente novamente.</p>";
    } else {
        // Inclui o arquivo de conexão
        require_once 'conexao.php';

        // Estabelece conexão com o banco
        $conn = novaConexao();

        // Verifica se o email existe no banco de dados na tabela usuario_geral
        $sql = $conn->prepare("SELECT id FROM usuario_geral WHERE Email = ? LIMIT 1");
        $sql->bind_param('s', $email);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            // Atualiza a senha no banco de dados na tabela cliente
            $update = $conn->prepare("UPDATE cliente SET Senha_cliente = ? WHERE id = (SELECT id FROM usuario_geral WHERE Email = ?)");
            $update->bind_param('ss', $novaSenha, $email);

            if ($update->execute()) {
                echo "<p style='color: green; text-align: center;'>Senha alterada com sucesso!</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Erro ao atualizar a senha. Por favor, tente novamente.</p>";
            }

            $update->close();
        } else {
            echo "<p style='color: red; text-align: center;'>Email não encontrado no banco de dados.</p>";
        }

        // Fecha a conexão
        $sql->close();
        $conn->close();
    }
}
?>


