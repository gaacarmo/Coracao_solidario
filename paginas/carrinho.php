<div class="titulo">
    <h1>Carrinho</h1>
    <?php
    if (!(isset($_SESSION['is_logged_in'])) || $_SESSION['is_logged_in'] !== true) {
    echo "<script>alert('Para acessar esta página, é necessário fazer login.');
    window.location.href = 'home.php?dir=paginas&&file=loginusu';
    </script>";
    exit;
    }?>
</div>