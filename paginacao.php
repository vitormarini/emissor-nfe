<?php
    /************************************
    *          Osvaldo Cruz/SP          *
    *                                   *
    * Criacao.: 08/08/2018              *    
    * Versao.: 1.0.0                    *
    ************************************/
    function paginacao( $strTela, $intPagina, $intLimite, $intTotal, $tipoRetorno = "TELA") {
        $intProxima    = $intPagina + 1;
        $intAnterior   = $intPagina - 1;
        $intUltima     = ceil( $intTotal / $intLimite );
        $intPenultima  = $intUltima - 1;
        $intAdjacentes = 2;
        
        $strTela .= ( isset($_GET['txtNome']) ? "&txtNome={$_GET['txtNome']}" : (isset($_GET["filtro"]) ? "&filtro={$_GET['filtro']}" : "") );
        $strTela .= ( isset( $_GET['txtSituacao'] ) ? "&txtSituacao={$_GET['txtSituacao']}" : "" );

        $strPaginacao = "
                <nav>
                   <ul class='pagination'>";
        if ( $intPagina > 1 ) {
            $strPaginacao .= "<li aria-label='Anterior'><a href='?{$strTela}&p={$intAnterior}'><span aria-hidden='true'>&laquo;</span></a></li>";
        }
        
        if ( $intUltima <= 7 ) {
            for ( $intCont = 1; $intCont < ( $intUltima + 1 ); $intCont++ ) {
                $strAtiva      = ( $intCont == $intPagina ? "class='active'" : '' );
                $strPaginacao .= "    <li $strAtiva><a href='?$strTela&p={$intCont}'>{$intCont}</a></li>";
            }
        }
        
        if ( $intUltima > 7 ) {
            if ( $intPagina < ( 1 + ( 2 * $intAdjacentes ) ) ) {
                for ( $intCont = 1; $intCont < ( 2 + ( 2 * $intAdjacentes ) ); $intCont++ ) {
                    $strAtiva      = ( $intCont == $intPagina ? "class='active'" : '' );
                    $strPaginacao .= "    <li $strAtiva><a href='?$strTela&p={$intCont}'>{$intCont}</a></li>";
                }
                $strPaginacao .= "    <li><a>...</a></li>";
                $strPaginacao .= "    <li><a href='?$strTela&p={$intPenultima}'>{$intPenultima}</a></li>";
                $strPaginacao .= "    <li><a href='?$strTela&p={$intUltima}'>{$intUltima}</a></li>";
            } 
            else if ( $intPagina > ( 2 * $intAdjacentes ) && $intPagina < ( $intUltima - 3 ) ) {
                $strPaginacao .= "    <li><a href='?$strTela&p=1'>1</a></li>";
                $strPaginacao .= "    <li><a href='?$strTela&p=2'>2</a></li>";
                $strPaginacao .= "    <li><a>...</a></li>";
                for ( $intCont = ( $intPagina - $intAdjacentes ); $intCont <= ( $intPagina + $intAdjacentes ); $intCont++) {
                    $strAtiva      = ( $intCont == $intPagina ? "class='active'" : '' );
                    $strPaginacao .= "    <li $strAtiva><a href='?$strTela&p={$intCont}'>{$intCont}</a></li>";
                }
                $strPaginacao .= "    <li><a>...</a></li>";
                $strPaginacao .= "    <li><a href='?$strTela&p={$intPenultima}'>{$intPenultima}</a></li>";
                $strPaginacao .= "    <li><a href='?$strTela&p={$intUltima}'>{$intUltima}</a></li>";
            } 
//            else if ( $intUltima == 7 ) {
//                for ( $intCont = 1; $intCont <= $intUltima; $intCont++ ) {
//                    $strAtiva      = ( $intCont == $intPagina ? "class='active'" : '' );
//                    $strPaginacao .= "    <li $strAtiva><a href='?$strTela&p={$intCont}'>{$intCont}</a></li>";
//                }
//            }
            else {
                $strPaginacao .= "    <li><a href='?$strTela&p=1'>1</a></li>";
                $strPaginacao .= "    <li><a href='?$strTela&p=2'>2</a></li>";
                $strPaginacao .= "    <li><a>...</a></li>";
                for ( $intCont = ( $intUltima - ( 1 + ( 2 * $intAdjacentes ) ) ); $intCont <= $intUltima; $intCont++ ) {
                    $strAtiva      = ( $intCont == $intPagina ? "class='active'" : '' );
                    $strPaginacao .= "    <li $strAtiva><a href='?$strTela&p={$intCont}'>{$intCont}</a></li>";
                }
            }
        }
        
        if ( $intProxima <= $intUltima && $intUltima > 2) {
            $strPaginacao .= "<li aria-label='Pr&oacute;xima'><a href='?{$strTela}&p={$intProxima}'><span aria-hidden='true'>&raquo;</span></a></li>";
        }
        $strPaginacao .= "    
                   </ul>
               </nav>";
        
        if($tipoRetorno == "TELA") print $strPaginacao;
        else return $strPaginacao;
    }
?>