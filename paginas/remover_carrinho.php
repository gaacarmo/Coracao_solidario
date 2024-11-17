<?php
if (isset($_GET['codigo'])) {
    $codigo = (int)$_GET['codigo'];
    
    if (isset($_SESSION['carrinho']) && in_array($codigo, $_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = array_diff($_SESSION['carrinho'], [$codigo]);
        
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
        
        header("Location: home.php?dir=paginas&&file=carrinho");
        exit;
    }
}

header("Location: home.php?dir=paginas&&file=carrinho");
exit;

?>