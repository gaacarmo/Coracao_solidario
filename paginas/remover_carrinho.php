<?php
if (isset($_GET['codigo'])) {
    $codigo = (int)$_GET['codigo'];
    
    // Verificar se o produto está no carrinho
    if (isset($_SESSION['carrinho']) && in_array($codigo, $_SESSION['carrinho'])) {
        // Remover o produto do carrinho
        $_SESSION['carrinho'] = array_diff($_SESSION['carrinho'], [$codigo]);
        
        // Reindexar o array para manter a ordem dos itens
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
        
        // Redirecionar de volta para a página do carrinho
        header("Location: home.php?dir=paginas&&file=carrinho");
        exit;
    }
}

// Caso não haja código ou item para remover, redirecionar
header("Location: home.php?dir=paginas&&file=carrinho");
exit;

?>