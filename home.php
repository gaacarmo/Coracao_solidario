<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coração Solidário</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
    <header>
    <div class="logo">
        <a href="./index.php">
            <img class="logo-1" src="assets/logo coracao.png" alt="Coração Solidário">
        </a>
        <span>Coração Solidário</span>
    </div>
        <nav>
            <ul>
                <li><a href="home.php?dir=1_masculino&file=masculino_produtos">Masculino</a></li>
                <li><a href="home.php?dir=2_feminino&file=feminino_produtos" class="active">Feminino</a></li>
                <li><a href="home.php?dir=3_infantil&file=infantil_produtos" class="active">Infantil</a></li>
                <li><a href="home.php?dir=4_calcados&file=calcados_produtos" class="active">Calçados</a></li>
                <li><a href="home.php?dir=paginas&file=sobre_nos" class="active">Sobre Nós</a></li>
            </ul>
        </nav>
        <div class="login">
            <button><a href="home.php?dir=paginas&file=loginusu">Entrar</a></button>
        </div>
    </header>
    <div class="cart-icon">
        <a href="home.php?dir=paginas&file=carrinho"><img src="assets/shopping-cart.png" alt="Carrinho de compras"></a>
    </div>
 
    <main class="principal">
    <div class="conteudo">
        <?php
        // Verifica se 'dir' e 'file' estão definidos e não estão vazios
        if (isset($_GET['dir']) && isset($_GET['file']) && !empty($_GET['dir']) && !empty($_GET['file'])) {
            $dir = preg_replace('/[^a-zA-Z0-9-_]/', '', $_GET['dir']); // Sanitiza o nome do diretório
            $file = preg_replace('/[^a-zA-Z0-9-_]/', '', $_GET['file']); // Sanitiza o nome do arquivo 

            $path = __DIR__ . "/{$dir}/{$file}.php";

            // Verifica se o arquivo realmente existe antes de incluir
            if (file_exists($path)) {
                include($path);
            } else {
                echo "Arquivo não encontrado!";
            }
        } else {
            // Inclui uma página padrão ou exibe uma mensagem quando 'dir' e 'file' não são fornecidos
            include(__DIR__ . "/home.php"); // Ou substitua por um arquivo padrão da sua escolha
        }
        ?>
    </div>
</main>

    
        <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Redes sociais</h4>
                <div class="social-icons">
                    <img src="assets/instagram.png" alt="Instagram">
                    <img src="assets/twitter.png" alt="X (Twitter)">
                </div>
            </div>
            <div class="footer-section">
                <h4>Email de contato</h4>
                <p>coracao.solidario@gmail.com</p>
            </div>
            <div class="footer-section">
                <h4>Telefone</h4>
                <p>41 99999-9999</p>
            </div>
        </div>
        <div class="footer-bottom">
            "Cada um contribua conforme determinou no coração, não com pesar nem por obrigação, pois Deus ama a quem dá com alegria"
        </div>
        <br>
        <div class="footer-bottom">
            
            © Todos os direitos reservados | coração solidário | 2024</>
        </div>
    </footer>
</body>
</html>
