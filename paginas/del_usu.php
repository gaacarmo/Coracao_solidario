<?php
require_once './paginas/conexao.php';

session_start();
session_destroy();
unset($_COOKIE['username']);
setcookie('username',''); #limpou todos os cookies 

header('Location: home.php?dir=paginas&file=loginusu');