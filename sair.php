<?php
    /************************************
    *          Osvaldo Cruz/SP          *
    *                                   *
    * Criacao.: 08/08/2018              *    
    * Versao.: 1.0.0                    *
    * Tela que redireciona para sair    *
    ************************************/ 
    //Obtem a sessão iniciada
    session_start();
    
    //Destrói os valores armazenados na sessão
    $_SESSION = array();
    
    //Destroi a sessão
    session_destroy();
    
    //Redireciona para login
    header("location: index.php" );
