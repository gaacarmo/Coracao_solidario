<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coração Solidário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img class="logo-1" src="assets/logo coracao.png" alt="Coração Solidário">
            <a>Coração Solidário</a>
        </div>
        <nav>
            <ul>
                <li><a href="#" class="active">Masculino</a></li>
                <li><a href="#">Feminino</a></li>
                <li><a href="#">Infantil</a></li>
                <li><a href="#">Calçados</a></li>
                <li><a href="#">Sobre nós</a></li>
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
                include(__DIR__ . "/{$_GET['dir']}/{$_GET['file']}.php");
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
            <>© Todos os direitos reservados | coração solidário | 2024</>
        </div>
    </footer>
</body>
</html>