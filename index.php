<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coração Solidário</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marmelad&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&display=swap" rel="stylesheet">
</head>
<div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper></div>
</div>
<body>
    <header>
        <div class="logo">
            <a href="./index.php "><img class="logo-1" src="assets/logo coracao.png" alt="Coração Solidário">
            </a>
            
            <a class="texto-titulo">Coração Solidário</a>
        </div>
        <nav>
            <ul>
                <li><a href="home.php?dir=1_masculino&file=masculino_produtos" class="active">Masculino</a></li>
                <li><a href="home.php?dir=2_feminino&file=feminino_produtos" class="active">Feminino</a></li>
                <li><a href="home.php?dir=3_infantil&file=infantil_produtos" class="active">Infantil</a></li>
                <li><a href="home.php?dir=4_calcado&file=calcados_produtos" class="active">Calçados</a></li>
                <li><a href="home.php?dir=paginas&file=sobre_nos" class="active">Sobre Nós</a></li>
            </ul>
        </nav>
        <div class="login">
            <?php
                session_start();
                // Verifica se o usuário está logado
                if (!(isset($_SESSION['is_logged_in'])) || $_SESSION['is_logged_in'] !== true) {
                    echo '<button><a href="home.php?dir=paginas&file=loginusu">Entrar</a></button>';
                    
                }else{
                    echo '<button><a href="home.php?dir=paginas&file=minhas_doacoes">Minhas Doações</a></button>';
                    echo '<button><a href="home.php?dir=paginas&file=del_usu">Sair</a></button>';
                   
                }
            ?> 
           
        </div>
    </header>
    <div class="cart-icon">
        <a href="home.php?dir=paginas&file=carrinho"><img src="assets/shopping-cart.png" alt="Carrinho de compras"></a>
    </div>

    
    <?php
    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
        echo "<h1>Coração Solidário - Bem-vindo,  {$_SESSION['username']} !</h1>";
        // Aqui você pode exibir conteúdo restrito ou dados do usuário
    } else{
        echo "<h1>Coração Solidário</h1>";
    } ?>
    
    <main class="principal">
        <section class="products">
            <div class="product">
                <div class="img-produto ">
                    <img src="assets/doacao.jpg" alt="doação">
                </div>
                <p class="texto-produto">Quero doar!</p>
                <a class="btn-index" href='home.php?dir=paginas&file=form_doar'>Clique para doar</a>
            </div>
            <div class="product">
                <div class="img-produto">
                <img src="assets/masculino.webp" alt="">
                </div>
                <p class="texto-produto">Masculino</p>
                <a class="btn-index" href="home.php?dir=1_masculino&file=masculino_produtos">Acessar</a>
            </div>
            <div class="product">
                <div class="img-produto ">
                <img src="assets/feminino.jpg" alt="">    
                </div>
                <p class="texto-produto">Feminino</p>
                <a class="btn-index" href="home.php?dir=2_feminino&file=feminino_produtos">Acessar</a>
            </div>
            <div class="product">
                <div class="img-produto ">
                <img src="assets/infantil.jpg" alt="">
                </div>
                <p class="texto-produto">Infantil</p>
                <a class="btn-index" href="home.php?dir=3_infantil&file=infantil_produtos">Acessar</a>
            </div>
            <div class="product">
                <div class="img-produto ">
                    <img src="assets/calçados.webp" alt="">
                </div>
                <p class="texto-produto">Calçados</p>
                <a class="btn-index" href="home.php?dir=4_calcado&file=calcados_produtos">Acessar</a>
            </div>
            <div class="product">
                <div class="img-produto ">
                    <img src="assets/Sobre nós.png" alt="">
                </div>
                <p class="texto-produto">Sobre nós</p>
                <a class="btn-index" href="home.php?dir=paginas&file=sobre_nos">Acessar</a> 
            </div>
        </section>
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
<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
</script>
<style>
    .texto-titulo{
        color: white;
        font-weight: bold;
    }
</style>