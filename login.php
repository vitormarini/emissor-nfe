<?php
    /************************************
    *          Osvaldo Cruz/SP          *
    *                                   *
    * Criacao.: 08/08/2018              *    
    * Versao.: 1.0.0                    *
    ************************************/
    include "bd.php";
    session_start();
    converteCaracteres();

    $login = function_exists("pg_escape_string") ? pg_escape_string(limparTexto($_POST['usuario'])) : limparTexto($_POST['usuario']) ;
    $senha = md5($_POST['senha']);
        
    $usuario = $objBDNovo->Execute("
        Consulta banco
    ");
    
    //Usuário válido
    if($usuario->RecordCount() == 1){ 
        //Dados do usuário logado
        $_SESSION["autoriza"]      = 1;
        $_SESSION["codigo"]        = $usuario->fields["codigo"];
        $_SESSION["nome"]          = $usuario->fields["nome"];
        $_SESSION["apelido"]       = $usuario->fields["apelido"];
        $_SESSION["administrador"] = $usuario->fields["administrador"];
        $_SESSION["id_usuario"]    = $usuario->fields["id_usuario"];
        $_SESSION["sexo"]          = $usuario->fields["sexo"];
        $_SESSION["cargo"]         = $usuario->fields["cargo"];
        
        //Verifica se necessita alteração da senha
        if($usuario->fields['provisoria'] == 'N') header("Location: controle.php");
        else header("Location: alteraSenha.php");  
    }
    else {
        //Destroi sessão
        if(@session_start()) session_destroy();?>
        <script language='javascript' type='text/javascript'>
            alert( 'Usuario e/ou senha incorreto(s)' );
            history.back();
        </script>
    <?php
    }
