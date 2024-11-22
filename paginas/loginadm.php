<?php
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = $_POST;

    if (empty($dados['Usuario_admin']) || strlen(trim($dados['Usuario_admin'])) < 3 || strlen(trim($dados['Usuario_admin'])) > 20) {
        $erros['Usuario_admin'] = "O nome de usuário deve ter entre 3 e 20 caracteres.";
    }

    if (empty($dados['Senha_admin']) || strlen(trim($dados['Senha_admin'])) < 3 || strlen(trim($dados['Senha_admin'])) > 20) {
        $erros['Senha_admin'] = "A senha deve ter entre 3 e 20 caracteres.";
    }

    if (count($erros) == 0) {
        require_once "conexao.php";
        $conexao = novaConexao();

        $sql = "SELECT * FROM Administrador_site WHERE Usuario_admin=? AND Senha_admin=?";
        $stmt = $conexao->prepare($sql);

        $params = [
            $dados['Usuario_admin'],
            $dados['Senha_admin'],
        ];

        $stmt->bind_param("ss", ...$params);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $_SESSION['username_admin'] = $dados['Usuario_admin'];
                $_SESSION['is_logged_admin'] = true;
                unset($dados);
                header("Location: index_adm.php");
            } else {
                $erros['login'] = "Nome de usuário ou senha incorretos.";
            }
        } else {
            $erros['db'] = "Erro ao consultar cliente: " . $stmt->error;
        }
    }
}
?>

<link rel="stylesheet" href="./CSS/login.css">

<h1>Login Administrador</h1>
<a href="home.php?dir=paginas&file=loginusu"><img class="voltar" src="assets/de-volta.png" alt="Voltar"></a>
<form class="form" method="post">
    <div class="flex-column">
        <label>Username Administrador</label>
        <div class="inputForm">
            <input type="text" name="Usuario_admin" class="input" placeholder="Insira seu Username">
        </div>
    </div>
    <div class="flex-column">
        <label>Senha </label>
        <div class="inputForm">
            <input type="password" name="Senha_admin" class="input" placeholder="Insira sua senha">
        </div>
    </div>
    
    <button class="button-entrar">Entrar</button>
</form>


<?php if (!empty($erros)): ?>
    <div class="erros">
        <?php foreach ($erros as $erro): ?>
            <p><?php echo htmlspecialchars($erro); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

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