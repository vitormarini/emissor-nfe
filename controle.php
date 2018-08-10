<?php
    /************************************
    *          Osvaldo Cruz/SP          *
    *                                   *
    * Criacao.: 08/08/2018              *    
    * Versao.: 1.0.0                    *
    ************************************/
    //Arquivos necessários
    require_once "bd.php";  
    require_once "paginacao.php";
//    require_once "verifyUserLogin.php"; - Habilitar após ter conexão com o banco
    include_once "funcoes.php";   
    
    //Set timezone padrão
    date_default_timezone_set("America/Sao_Paulo");
 
    
?>
    
<!DOCTYPE html>
<html lang="pt-br">
    <head>     
        <!-- Metas Tags -->
        <meta charset="UTF-8">
        <meta name="viewport"       content="width=device-width, initial-scale=1.0">
        <meta name="description"    content="Emissor Gratuito NF-e (https://github.com/nfephp-org/sped-emissor)">
        <meta name="author"         content="Vitor Hugo Marini">
        <meta name="contact"        content="vhmarini@gmail.com">
        <!-- Tags para não armazenamento de cache -->
        <meta http-equiv="Cache-Control" content="no-cache">
        <meta http-equiv="pragma"        content="no-cache">
        <meta http-equiv="Expires"       content="0"       >
        <!-- Retira a formatação do skype nos números impresso na tela do browser -->
        <meta name="SKYPE_TOOLBAR"  content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" >
                
        <title>EMISSOR NF-e Gratuito</title>
        <!-- Favicon -->
        <link rel="icon" href="imagens/nfe.jpg"> 
        <!-- CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">        
        <link rel="stylesheet" href="css/emissor.css">
        <link rel="stylesheet" href="css/jquery.scrollbar.css" >
        <link rel="stylesheet" href="css/jquery-ui-1.11.4.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="js/jqgrid/themes/ui.jqgrid-bootstrap.css"/>

        <!-- Hack para IE8 suportar elementos HTML5 e responsividade -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]--> 
        <!-- JS -->
        <script src="js/jquery-1.12.1.min.js"></script>
        <script src="js/jquery-ui-1.11.4.min.js"></script>
        <script src="js/jquery.ui.datepicker-pt-BR.js"></script>      
        <script src="js/bootstrap.min.js"></script>
        <script src="js/typeahead.js"></script>
        <script src="js/jquery.maskedinput.min.js"></script>
        <script src="js/jquery.price_format.1.8.min.js"></script>
        <script src="js/jquery.limit-1.2.source.js"></script>        
        <script src="js/moment-with-locales.js"></script>
        <script src="js/jquery.scrollbar.min.js"></script>
        <!-- Scripts de validação de formulários -->
        <script src="js/jquery-validate-1.15.0.min.js"></script>
        <script src="js/jquery-validate-additional-methods-1.15.0.min.js"></script>
        <script src="js/jquery-validate-messages-ptbr-1.15.0.min.js"></script>
        <script src="js/jqgrid/i18n/grid.locale-pt-br.js"></script>
        <script src="js/jqgrid/jquery.jqGrid.min.js"></script>
        <script type="text/javascript" charset="UTF-8">
            $.jgrid.defaults.responsive = true;
            $.jgrid.defaults.styleUI = 'Bootstrap';
        </script>
        <!-- Scripts próprios -->
        <script src="js/linoforte.js"></script>
        <script src="js/funcoes.js"></script>
    </head>
    <body>
        <!-- Menu Principal -->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".js-navbar-collapse" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Navegação</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="controle.php">
                        <img class="logo" src="imagens/nfe.jpg">
                    </a>         
                </div> 
                <?php require_once "menu.php"; ?>       
            </div>            
        </nav>           
        <!-- Conteúdo -->
        <div class="container">           
        <?php
            if(isset($_GET["exemplo"]))                            require_once("exemplo.php");//Aqui são colocados todos os "Apelidos feitos no MENU para as chamdas dos arquivos dentro da Pasta
            else if(isset($_GET["exemplo1"]))                      require_once("exemplo1.php");
            else if(isset($_GET["exemplo2"]))                      require_once("exemplo2.php");
            else if(isset($_GET["exemplo3"]))                      require_once("exemplo3.php");
            
            
//            $bd->Close(); - Conexão com o banco
//            $objBDNovo->Close(); - Fecha a conexão deste parâmetro
        ?>
            
        </div>

        
        <!-- Modal de Erro Personalizado -->
        <div id="modal_erro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titulo_modal_erro" aria-hidden="true">
            <div class="modal-dialog">        
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="margin-bottom-10">           
                            <h2 class="modal-title text-center" id="titulo_modal_erro">Ops! Parece que temos um problema...</h2>
                        </div>
                        <div>              
                            <div id="mensagem_erro" class="text-danger text-justify"></div>                
                        </div>
                        <div class="margin-top-10 text-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        
        <!-- Modal de Sucesso Personalizado -->
        <div id="modal_success" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="titulo_modal_success" aria-hidden="true">
            <div class="modal-dialog">        
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="margin-bottom-10">           
                            <h2 class="modal-title text-center text-success" id="titulo_modal_success">Operação realizada com sucesso!</h2>
                        </div>
                    </div>

                </div>
            </div>
        </div>     
        
        <!-- Modal para Impressão -->
        <div id="layModalImprimir" class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="layModalImprimirLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">                   
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h2 class="modal-title" id="layModalImprimirLabel">Impressão</h2>
                    </div>                  
                    <div class="modal-body">
                        <div id="layRelatorio"></div>
                    </div>           
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>                  
                </div>
            </div>
        </div>              
    </body>
</html>
