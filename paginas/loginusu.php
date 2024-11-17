<?php
$erros = []; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = $_POST;

    if (empty($dados['Usuario_cliente']) || strlen(trim($dados['Usuario_cliente'])) < 3 || strlen(trim($dados['Usuario_cliente'])) > 20) {
        $erros['Usuario_cliente'] = "O nome de usuário deve ter entre 3 e 20 caracteres.";
    }

    if (empty($dados['Senha_cliente']) || strlen(trim($dados['Senha_cliente'])) < 3 || strlen(trim($dados['Senha_cliente'])) > 20) {
        $erros['Senha_cliente'] = "A senha deve ter entre 3 e 20 caracteres.";
    }

    if (count($erros) == 0) {
        require_once "conexao.php";
        $conexao = novaConexao();

        $sql = "SELECT * FROM Cliente WHERE Usuario_cliente=? AND Senha_cliente=?";
        $stmt = $conexao->prepare($sql);

        $params = [
            $dados['Usuario_cliente'],
            $dados['Senha_cliente'],
        ];

        $stmt->bind_param("ss", ...$params);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $_SESSION['username'] = $dados['Usuario_cliente'];
                $_SESSION['is_logged_in'] = true;
                
                $sql2 = "SELECT ID FROM Cliente WHERE Usuario_cliente = ?";
                $stmt2 = $conexao->prepare($sql2);
                $params2 = [
                    $dados['Usuario_cliente'],
                ];
                $stmt2 -> bind_param("s", ...$params2);
                if ($stmt2->execute()){
                    $result2 = $stmt2->get_Result();
                    if ($result2->num_rows > 0) {
                        $row = $result2->fetch_assoc();
                        $_SESSION['id_cliente'] = $row['ID'];
                    }
                }
                unset($dados);
                header("Location: index.php");
                exit;
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

<h1>Login</h1>
<a href="./index.php"><img class="voltar" src="assets/de-volta.png" alt="Voltar"></a>
<form class="form" method="post">
    <div class="flex-column">
        <label>Username </label>
        <div class="inputForm">
            <input type="text" name="Usuario_cliente" class="input" placeholder="Insira seu Username">
        </div>
    </div>
    <div class="flex-column">
        <label>Senha </label>
        <div class="inputForm">
            <input type="password" name="Senha_cliente" class="input" placeholder="Insira sua senha">
        </div>
    </div>
    <div class="flex-row">
        <span class="span"><a href="home.php?dir=paginas&file=esqueceu" class="esqueceu">Esqueceu a senha?</a></span>
            <p class="p">
                <span class="span">
                    <a href="home.php?dir=paginas&file=cadastrousu" class="conta">Não tem conta?</a>
                </span>
            </p>
    </div>

    <div>
        <p class="p"><span class="span"><a href="home.php?dir=paginas&file=loginadm" class="conta">Login Administrador</a></span>
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