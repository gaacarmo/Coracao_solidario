<?php

function novaConexao($banco = 'brecho'){
    $sever = '127.0.0.1:3306';
    $username = 'root';
    $password = '';

    $conexao = new mysqli($sever, $username, $password, $banco);

    if($conexao->connect_error){
        die ('Error'. $conexao->connect_error);
        
    }
    return $conexao;
}

