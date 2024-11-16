<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coração Solidário ADMIN</title>
    <link rel="stylesheet" href="home_adm.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="./index.php">
                <img class="logo" src="./assets/logo coracao.png" alt="Coração Solidário">
            </a>
            <a class="texto-titulo">Coração Solidário</a>
        </div>

        <nav>
            <ul>
                <li><a href="home_adm.php?dir=paginas_adm&file=lista_usuarios" class="active">Gerenciar usuários</a></li>
                <li><a href="home_adm.php?dir=paginas_adm&file=tela_inicial_graficos" class="active">Relatório estatístico</a></li>
                <li><a href="home_adm.php?dir=paginas_adm&file=analise_produtos" class="active">Analisar produtos</a></li>
            </ul>
        </nav>
        <div class="admin">
            <h3>ADMIN</h3>
        </div>
    </header>
    <main class="principal">
        <section class="welcome">
            <div class="welcome-text">
                <h1>Bem-vindo(a) ao Painel Administrativo</h1>
                <p>Esta é a área de administração do Coração Solidário, onde você pode gerenciar usuários, visualizar relatórios estatísticos e analisar produtos.</p>
                <p>Obrigado por sua dedicação à nossa causa!</p>
                <a href="home_adm.php?dir=paginas_adm&file=lista_usuarios" class="btn-welcome">Começar</a>
            </div>
        </section>
    </main>
</body>
</html>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        box-sizing: border-box;
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
        padding: 10px 20px;
        border-bottom: 5px solid black;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
    }

    .logo img {
        height: 70px;
        margin-right: 10px;
        transition: transform 0.3s ease;
    }

    .texto-titulo {
        font-size: 18px;
        font-weight: bold;
        color: black;
        text-decoration: none;
    }

    nav ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    nav ul li {
        margin: 0 15px;
        position: relative;
    }

    nav ul li a {
        text-decoration: none;
        color: black;
        font-size: 1.1rem;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: bold;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    nav ul li a.active {
        background-color: black;
        color: white;
    }

    nav ul li a.active:hover {
        background-color: #5A6C80;
        color: white;
    }

    .admin {
        font-size: 16px;
        font-weight: bold;
        color: black;
        text-transform: uppercase;
    }

    /* Welcome Section */
    .welcome {
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 100px);
        background: linear-gradient(135deg, #d5dde5, #f7f8fa);
        text-align: center;
    }

    .welcome-text {
        background-color: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 0 20px;
    }

    .welcome-text h1 {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 20px;
    }

    .welcome-text p {
        font-size: 1.2rem;
        color: #555;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .btn-welcome {
        display: inline-block;
        padding: 15px 30px;
        font-size: 1.2rem;
        color: white;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .btn-welcome:hover {
        background-color: #0056b3;
    }
</style>
