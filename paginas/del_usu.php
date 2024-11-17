<?php
require_once 'conexao.php';

session_start();
session_destroy();
unset($_COOKIE['username']);
setcookie('username','');

header('Location: home.php?dir=paginas&file=loginusu');