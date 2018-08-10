<?php
    /************************************
    *          Osvaldo Cruz/SP          *
    *                                   *
    * Criacao.: 08/08/2018              *    
    * Versao.: 1.0.0                    *
    ************************************/
    //Report de erros
    error_reporting(E_ERROR);
    
    //Includes necessários
    include_once "bd.php";
    include_once "funcoes.php";
?>
<style>
    
    .mega-dropdown {
        position: static !important;
    }
    
    .mega-dropdown > a:hover,
    .mega-dropdown > a:focus {
        border-bottom: 1px #c9302c solid;
    }

    .mega-dropdown-menu {
        padding: 20px 0px;
        width: 100%;
        box-shadow: none;
        -webkit-box-shadow: none;
    }
    
    .mega-dropdown-menu > li > ul {
        padding: 0;
        margin: 0px;
    }
    
    .mega-dropdown-menu > li > ul > li {
        margin-left: 10px;
        list-style: none;
    }
    
    .mega-dropdown-menu > li > ul > li > a {
        display: block;
        color: #222;
        padding: 3px 5px;        
    }
    
    .mega-dropdown-menu > li ul > li > a:hover,
    .mega-dropdown-menu > li ul > li > a:focus {
        text-decoration: none;
        background: #D9EDF7;
        border-left: 3px #c9302c solid;
    }
    
    .mega-dropdown-menu .dropdown-header {
        font-size: 14px;
        padding: 5px 60px 5px 5px !important;
        line-height: 30px;
        font-weight: bold;
    }
    
    .dropdown-header {
        border-bottom: 1px #ddd dotted;
    }
    
    .carousel-control {
        width: 30px;
        height: 30px;
        top: -35px;

    }
    
    .left.carousel-control {
        right: 30px;
        left: inherit;
    }
    
    .carousel-control .glyphicon-chevron-left, 
    .carousel-control .glyphicon-chevron-right {
        font-size: 12px;
        background-color: #fff;
        line-height: 30px;
        text-shadow: none;
        color: #333;
        border: 1px solid #ddd;
    }
    
    
    /* Media Query */   

    @media (max-width: 768px){ 
        .mega-dropdown-menu > li > ul          { border-left: 0px;               }
        .mega-dropdown-menu > li > ul > li > a { border-left: 3px #f8f8f8 solid; }
    }         

    @media (min-width: 768px){ 
        .mega-dropdown-menu > li > ul          { border-left: 1px #ddd dotted; }
        .mega-dropdown-menu > li > ul > li > a { border-left: 3px #FFF solid;  }
    }
    
</style>
        <meta charset="UTF-8">
        <meta name="viewport"       content="width=device-width, initial-scale=1.0">
        <meta name="description"    content="Emissor Gratuito NF-e (https://github.com/nfephp-org/sped-emissor)">
        <meta name="author"         content="Vitor Hugo Marini">
        <meta name="contact"        content="vhmarini@gmail.com">

<div class="collapse navbar-collapse js-navbar-collapse">
    
    <ul class="nav navbar-nav"> 
       
                
        <!-- Menu Notas Fiscais -->
        <li class="dropdown mega-dropdown">
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-list-alt"></span> Nota Fiscal 
                <span class="caret"></span>
            </a>
            
            <ul class="dropdown-menu mega-dropdown-menu">
                
                <li class="col-sm-3">
                    <ul>
                        <li class="dropdown-header">
                            <span class="text-info">
                                <span class="glyphicon glyphicon-list-alt"></span> Cadastros
                            </span>
                        </li>
                        <li><a href="?teste"                       > Teste                          </a></li>                                               
                    </ul>
                </li>
                
                <li class="col-sm-3">
                    <ul>
                        <li class="dropdown-header">
                            <span class="text-info">
                                <span class="glyphicon glyphicon-cog"></span> Movimenta&ccedil;&amacr;o
                            </span>
                        </li>
                    </ul>
                </li>
                
                <li class="col-sm-3">
                    <ul>
                        <li class="dropdown-header">
                            <span class="text-info">
                                <span class="glyphicon glyphicon-print"></span> Relat&oacute;rios
                            </span>
                        </li>                      
                    </ul>
                </li>
                
            </ul>
            
        </li>
        
        
        <!-- Menu Emitente -->
        <li class="dropdown mega-dropdown">
           
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-briefcase"></span> Emitente 
                <span class="caret"></span>
            </a>
            
            <ul class="dropdown-menu mega-dropdown-menu">                
                <li class="col-sm-3">
                    <ul>
                        <li class="dropdown-header">
                            <span class="text-info">
                                <span class="glyphicon glyphicon-list-alt"></span> Cadastros
                            </span>
                        </li>
                        <li><a href="?tester"> Teste </a></li>
                    </ul>
                </li>
            </ul>
            
        </li>
        
        <!-- Menu Cadastros -->
        <li class="dropdown mega-dropdown">
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-globe"></span> Cadastros 
                <span class="caret"></span>
            </a>
            
            <ul class="dropdown-menu mega-dropdown-menu">                
                <li class="col-sm-3">
                    <ul>
                        <li class="dropdown-header">
                            <span class="text-info">
                                <span class="glyphicon glyphicon-list-alt"></span> Cadastros
                            </span>
                        </li>
                    </ul>
                </li>
            </ul>
            
        </li>
        
        <!-- Menu Sistema -->
        <li class="dropdown mega-dropdown">
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-wrench"></span> Sistema
                <span class="caret"></span>
            </a>
            
            <ul class="dropdown-menu mega-dropdown-menu">
                
                <li class="col-sm-3">
                    <ul>
                        <li class="dropdown-header">
                            <span class="text-info">
                                <span class="glyphicon glyphicon-list-alt"></span> Submenu
                            </span>
                        </li>                        
                        <li><a href="?testeLinha"> Teste Linha </a></li>
                    </ul>
                </li>
            </ul>
        </li>

        <!-- Menu Ajuda -->
        <li class="dropdown mega-dropdown">
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-signal"></span> Ajuda
                <span class="caret"></span>
            </a>
            
            <ul class="dropdown-menu mega-dropdown-menu">
                
                <li class="col-sm-3">
                    <ul>
                        <li class="dropdown-header">
                            <span class="text-info">
                                <span class="glyphicon glyphicon-list-alt"></span> Cadastros
                            </span>
                        </li>                                                
                    </ul>
                </li>
                
                <li class="col-sm-3">
                    <ul>
                        <li class="dropdown-header">
                            <span class="text-info">
                                <span class="glyphicon glyphicon-print"></span> Relat&oacute;rios
                            </span>
                        </li>                                                
                    </ul>
                </li>
                
            </ul>
            
        </li>
              
        <!-- Menu Manutenção (Esta aba seria para dar manutenção do Cliente que está usando este emissor)  -->
        <li class="dropdown mega-dropdown">
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-cog"></span> Manuten&ccedil;&amacr;o 
                <span class="caret"></span>
            </a>
            
            <ul class="dropdown-menu mega-dropdown-menu">
                
                <li class="col-sm-3">
                    <ul>
                        <li class="dropdown-header">
                            <span class="text-info">
                                <span class="glyphicon glyphicon-list-alt"></span> Cadastros
                            </span>
                        </li>                                                
                    </ul>
                </li>                
            </ul>            
        </li>               
    </ul>
    
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-user"></span> Usuário 
                <span class="caret"></span>
            </a>
            
            <ul class="dropdown-menu">                        
                <li>
                    <a href="?cadastro_usuario">
                        <span class="glyphicon glyphicon-book"></span> Usu&aacute;rios
                    </a>
                </li>                    
               
                <li>
                    <a href="alteraSenha.php">
                        <span class="glyphicon glyphicon-pencil"></span> Alterar Senha
                    </a>
                </li>
                <li>
                    <a href="sair.php">
                        <span class="glyphicon glyphicon-off"></span> Sair
                    </a>
                </li>
                
            </ul>
        </li>
    </ul>
    
</div>
