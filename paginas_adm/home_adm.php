<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="home_adm.css">
</head>
<body>
    <header>
    <div class="logo">
            <a href="#">
            <img class="logo-1" src="assets/logo coracao.png" alt="Coração Solidário">
            </a>
            
            <a class="texto-titulo">Coração Solidário</a>
    </div>
    <nav>
        <ul>
            <li><a href="#" class="active">Gerenciar usuários</a></li>
            <li><a href="#" class="active">Relatório estatístico</a></li>
            <li><a href="#" class="active">Analisar produtos</a></li>
        </ul>
    </nav>
    <div class="admin">
        <h3>ADMIN</h3>
    </div>
    </header>
</body>
</html>
<style>
    body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    box-sizing: border-box;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: white;
    padding: 10px 20px;
    border-bottom: 1px solid black;
}

.logo {
    display: flex;
    align-items: center;
}

.logo img {
    height: 40px;
    margin-right: 10px;
}

.logo .texto-titulo {
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
    margin-right: 20px;
}

nav ul li a {
    text-decoration: none;
    color: black;
    font-size: 16px;
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
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

</style>