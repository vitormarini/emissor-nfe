$(document).ready(function(){
				
    //Ativa scroll personalizado
    $(".full-dropdown-menu-itens .scrollbar-macosx").scrollbar({ignoreOverlay : true});

    //Exibe / Esconde menu principal
    $(".hamburger-menu").on("click", function(e){

        //Previne redirecionamento do link
        e.preventDefault();

        //Ative ou desativa o menu principal
        if(!$(".full-dropdown-menu").hasClass("open")) $(".full-dropdown-menu").addClass("open");
        else{
            $(".full-dropdown-menu").removeClass("open");
            $(".full-dropdown-menu-itens > div > ul > li, .level-one > li, .full-dropdown-menu-itens .tab-pane").removeClass("active");
        }

        //Exibe o menu completo
        if($(".full-dropdown-menu-itens").hasClass("open")) $(".full-dropdown-menu-itens").removeClass("open");
        else $(".full-dropdown-menu-itens").addClass("open");					

    });
    
    //Exibe / Esconde menu de busca
    $(".search-menu").on("click", function(e){

        //Previne redirecionamento do link
        e.preventDefault();

        //Ative ou desativa a busca
        if(!$(".search-dropdown-menu").hasClass("open")) $(".search-dropdown-menu").addClass("open");
        else{
            $(".search-dropdown-menu").removeClass("open");
            $("#busca_menu").val("");
        }

        //Exibe o campo de busca
        if($(".search-dropdown-menu-field").hasClass("open")) $(".search-dropdown-menu-field").removeClass("open");
        else $(".search-dropdown-menu-field").addClass("open");					

    });

    //Retira a classe ative quando alternando entre os menus principais
    $(".full-dropdown-menu-itens > div > ul").on("click", function(){
        $(".level-one > li, .level-two").removeClass("active");
    });

    //Fecha o menu principal e a busca quando clicar em qualquer parte da página ou apertar a tecla ESC
    $("html").on("click keydown", function (e){					

        //Fecha o menu principal
        if((!$(".full-dropdown-menu").is(e.target) && $(".full-dropdown-menu").has(e.target).length === 0 && $(".full-dropdown-menu").hasClass("open")) ||
            ($(".full-dropdown-menu").hasClass("open") && /27/.test(e.which))) {

            //Ações adicionais para quando for telas pequenas
            if($(document).width() <= 767){
                $(".full-dropdown-menu-itens > div > ul, .full-dropdown-menu-itens .level-one").removeClass("escondido");
            }

            $(".full-dropdown-menu-itens > div > ul > li, .level-one > li, .full-dropdown-menu-itens .tab-pane").removeClass("active");
            $(".full-dropdown-menu, .full-dropdown-menu-itens").removeClass("open");

        }
        
        //Fecha a busca
        if((!$(".search-dropdown-menu").is(e.target) && $(".search-dropdown-menu").has(e.target).length === 0 && $(".search-dropdown-menu").hasClass("open")) ||
            ($(".search-dropdown-menu").hasClass("open") && /27/.test(e.which))) {

            $("#busca_menu").val("");
            $(".search-dropdown-menu, .search-dropdown-menu-field").removeClass("open");

        }
        
    });
    

    /* Navegação do menu principal para telas pequenas */

    //Esconde menu dos módulos
    $(".full-dropdown-menu-itens > div > ul").on("click", function(){
        if($(document).width() <= 767){
            $(this).closest("ul").addClass("escondido");
        }
    });

    //Esconde menu de ações dos módulos
    $(".level-one > li").not(".voltar-modulo").on("click", function(){
        if($(document).width() <= 767){
            $(this).parent().addClass("escondido");
        }
    });				

    //Botão voltar nível para módulos
    $(".voltar-modulo").on("click", function(){
        $(this).closest(".tab-pane").removeClass("active");
        $(".full-dropdown-menu-itens > div > ul").removeClass("escondido");
        $(".full-dropdown-menu-itens > div > ul > li").removeClass("active");
    });

    //Botão voltar nível para açãoes dos módulos
    $(".voltar-opcoes").on("click", function(){
        $(this).closest(".level-two").removeClass("active");
        $(this).closest(".tab-content").parent().children(".level-one").removeClass("escondido");
        $(this).closest(".tab-content").parent().children(".level-one").children(".active").removeClass("active");
    });

});