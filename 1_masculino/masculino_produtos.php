<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'conexao.php';
    $conexao = novaConexao();

    if($_GET['codigo']){
        $sql = 'SELECT * FROM produto WHERE id =?';
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i",$_GET['codigo']);

        if($stmt->execute()){
            $resultado = $stmt->get_result();
            if($resultado->num_rows > 0){
                $dados = $resultado->fetch_assoc();
                    }
        }
    }
}
    ?>
<h1>Masculino</h1>
<main class="principal">
    <section class="products">
        <div class="product">
            <div class="img-produto">
                <img src="uploads/camisa do vasco.webp" alt="Camisa do Vasco da Gama">
            </div>
            <p class="texto-produto">Camisa do Vasco da Gama</p>
            <p class="sub-titulo">Tamanho: M</p>
            <a  class="btn-index" href='home.php?dir=1_masculino&file=detalhes_produto&codigo=22' >Saiba mais</a>
        </div>

        <div class="product">
            <div class="img-produto">
                <img src="uploads/camisa-flamengo.jpeg" alt="Camisa do Flamengo">
            </div>
            <p class="texto-produto">Camisa do Flamengo</p>
            <p class="sub-titulo">Tamanho: P</p>
            <a class="btn-index" href='home.php?dir=1_masculino&file=detalhes_produto&codigo=6'>Saiba mais</a>
        </div>

        <div class="product">
            <div class="img-produto">
                <img src="uploads/camisa-algodao-cinza.webp" alt="Camisa algodão cinza">
            </div>
            <p class="texto-produto">Camisa algodão cinza</p>
            <p class="sub-titulo">Tamanho: </p>
            <a class="btn-index" href='detalhes_produto.php'>Saiba mais</a>
        </div>

        <div class="product">
            <div class="img-produto">
                <img src="uploads/camisa-authenticity.webp" alt="Camisa Authenticity">
            </div>
            <p class="texto-produto">Camisa Authenticity</p>
            <p class="sub-titulo">Tamanho: P</p>
            <a class="btn-index" href='detalhes_produto.php'>Saiba mais</a>
        </div>

        <div class="product">
            <div class="img-produto">
                <img src="uploads/camisa-zaramen-g.jpeg" alt="Camisa Zara Men">
            </div>
            <p class="texto-produto">Camisa Zara Men</p>
            <p class="sub-titulo">Tamanho: G</p>
            <a class="btn-index" href='detalhes_produto.php'>Saiba mais</a>
        </div>

        <div class="product">
            <div class="img-produto">
                <img src="uploads/camisa-penalty-vermelha.webp" alt="Camisa Penalty Vermelha">
            </div>
            <p class="texto-produto">Camisa Penalty Vermelha</p>
            <p class="sub-titulo">Tamanho: M</p>
            <a class="btn-index" href='detalhes_produto.php'>Saiba mais</a>
        </div>
    </section>
</main>

<style>
    .sub-titulo {
        font-size: 1rem;
        text-decoration: none;
        font-weight: normal;
        margin-bottom: 10px;
    }
</style>
