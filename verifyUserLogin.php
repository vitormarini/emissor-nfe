<?php
    /************************************
    *          Osvaldo Cruz/SP          *
    *                                   *
    * Criacao.: 08/08/2018              *    
    * Versao.: 1.0.0                    *
    * Verifica se está logao            *
    ************************************/
    //Verifica login do usuário
    if(!isset($_SESSION["autoriza"])){
        session_destroy();
        $bd->Close();
        $objBDNovo->Close();
?>

    <script language="javascript" type="text/javascript" charset="UTF-8">
        alert('Você não tem permissão para acessar esse arquivo.');
        window.location.href = "../gestao/";
    </script>

<?php }