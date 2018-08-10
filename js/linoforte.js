
/* Adição de funções no Jquery */

//Obtem valor de um parâmetro da URL
$.getUrlParam = function(parameter){
    
    //Procura o parâmetro desejado
    var results = new RegExp("[\?&]" + parameter + "=([^&#]*)").exec(window.location.href);
    
    //Retorna valor ou undefinido caso não ache o parâmetro
    if(results == null) return undefined;
    else return decodeURI(results[1]) || 0;
    
}

//Impressão de relatório em PDF ou Excel
$.fn.imprimir = function( opcoes ){
    var metodos = $.extend({
        titulo  : "Impressão",
        arquivo : "",
        tipo    : "pdf"
    }, opcoes );

    return this.each( function() {
        var strMensagem = ( opcoes.tipo === 'excel' ? 'Gerando arquivo em excel . . .' : 'Carregando Relat&oacute;rio . . .' );

        $( "#layRelatorio" ).html( "<div id='layCarregadoRelatorio'><img src='imagens/loading.gif'/> " + strMensagem + "</div>" );

        $( '<iframe name="ifrRelatorio" id="ifrRelatorio" width="100%" height="500" frameborder="0" src="relatorios/' + opcoes.arquivo + '" style="display:visible"></iframe>' ).load( function(){
            $( "#layCarregadoRelatorio" ).hide();
            if ( opcoes.tipo === 'excel' ) {
                $( "#layModalImprimir" ).modal( "hide" );
            }
            else {
                $( "#ifrRelatorio" ).fadeIn();
            }
        }).appendTo( $( '#layRelatorio' ) );

        $( "#layModalImprimirLabel" ).html( opcoes.titulo );
        $( "#layModalImprimir" ).modal();
    });
};



/* Adições de funções no Jquery Validator */

//Validação de CPF
jQuery.validator.addMethod("cpf", function(value, element) {
    value = jQuery.trim(value);
    if (value !== '') {
        value = value.replace('.', '');
        value = value.replace('.', '');
        cpf = value.replace('-', '');
        while (cpf.length < 11)
            cpf = "0" + cpf;
        var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
        var a = [];
        var b = new Number;
        var c = 11;
        for (i = 0; i < 11; i++) {
            a[i] = cpf.charAt(i);
            if (i < 9)
                b += (a[i] * --c);
        }
        if ((x = b % 11) < 2) {
            a[9] = 0
        } else {
            a[9] = 11 - x
        }
        b = 0;
        c = 11;
        for (y = 0; y < 10; y++)
            b += (a[y] * c--);
        if ((x = b % 11) < 2) {
            a[10] = 0;
        } else {
            a[10] = 11 - x;
        }
        if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg))
            return false;
    }
    return true;
}, "Informe um CPF v&aacute;lido."); // Mensagem padr�o 

//Validação de data brasileira
jQuery.validator.addMethod("dateBR", function(value, element) {
    //contando chars
    if (value.length != 10)
        return false;
    // verificando data
    var data = value;
    var dia = data.substr(0, 2);
    var barra1 = data.substr(2, 1);
    var mes = data.substr(3, 2);
    var barra2 = data.substr(5, 1);
    var ano = data.substr(6, 4);
    if (data.length != 10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12)
        return false;
    if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31)
        return false;
    if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 != 0)))
        return false;
    if (ano < 1900)
        return false;
    return true;
}, "Informe uma data v&aacute;lida");  // Mensagem padr�o 

//Validação de data e hora brasileira
jQuery.validator.addMethod("dateTimeBR", function(value, element) {
    //contando chars
    if (value.length != 16)
        return false;
    // dividindo data e hora
    if (value.substr(10, 1) != ' ')
        return false; // verificando se h� espa�o
    var arrOpcoes = value.split(' ');
    if (arrOpcoes.length != 2)
        return false; // verificando a divis�o de data e hora
    // verificando data
    var data = arrOpcoes[0];
    var dia = data.substr(0, 2);
    var barra1 = data.substr(2, 1);
    var mes = data.substr(3, 2);
    var barra2 = data.substr(5, 1);
    var ano = data.substr(6, 4);
    if (data.length != 10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12)
        return false;
    if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31)
        return false;
    if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 != 0)))
        return false;
    // verificando hora
    var horario = arrOpcoes[1];
    var hora = horario.substr(0, 2);
    var doispontos = horario.substr(2, 1);
    var minuto = horario.substr(3, 2);
    if (horario.length != 5 || isNaN(hora) || isNaN(minuto) || hora > 23 || minuto > 59 || doispontos != ":")
        return false;
    return true;
}, "Informe uma data e uma hora v&aacute;lida");

//Validação de CNPJ
jQuery.validator.addMethod("cnpj", function(cnpj, element) {
    cnpj = jQuery.trim(cnpj);

    // DEIXA APENAS OS N�MEROS
    cnpj = cnpj.replace('/', '');
    cnpj = cnpj.replace('.', '');
    cnpj = cnpj.replace('.', '');
    cnpj = cnpj.replace('-', '');
    if (cnpj !== '') {
        var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
        digitos_iguais = 1;

        if (cnpj.length < 14 && cnpj.length < 15) {
            return false;
        }
        for (i = 0; i < cnpj.length - 1; i++) {
            if (cnpj.charAt(i) != cnpj.charAt(i + 1)) {
                digitos_iguais = 0;
                break;
            }
        }

        if (!digitos_iguais) {
            tamanho = cnpj.length - 2
            numeros = cnpj.substring(0, tamanho);
            digitos = cnpj.substring(tamanho);
            soma = 0;
            pos = tamanho - 7;

            for (i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) {
                    pos = 9;
                }
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0)) {
                return false;
            }
            tamanho = tamanho + 1;
            numeros = cnpj.substring(0, tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2) {
                    pos = 9;
                }
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1)) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
    return true;
}, "Informe um CNPJ válido.");

//Validação Não Igual
$.validator.addMethod("notEqual", function(value, element, param){
    return value == $(param).val() ? false : true;
}, "Este valor não pode ser igual"); 

//Validação Diferente De
$.validator.addMethod("diferenteDe", function(value, element, strCompara){
    return value == strCompara ? false : true;
}, "Este valor não foi alterado");

//Validação dos campos autocomplete quando existir ID
$.validator.addMethod("idValido", function(value, element, param){
    return this.optional(element) || $(element).parent().find(".validate-id").val() != param; 
}, "Por favor refaça a pesquisa e selecione um dos itens exibidos.");

//Validação de data brasileira usando biblioteca moment.js
$.validator.addMethod("dataBrasil", function (value, element, param){
    return this.optional(element) || (moment($(element).val(), "DD/MM/YYYY").isValid() && moment($(element).val(), "DD/MM/YYYY") >= moment("01/01/1900", "DD/MM/YYYY") );
}, "Digite uma data válida maior ou igual a 01/01/1900.");

//Validação de valor mínimo quando existir máscara
$.validator.addMethod("minMascara", function (value, element, param){
    return this.optional(element) || (  parseFloat(unformatNumber(value)) >= parseFloat(unformatNumber(param)) );
}, $.validator.format("O valor informado precisa ser maior que {0}"));  

//Validação de valor máximo quando existir máscara
$.validator.addMethod("maxMascara", function (value, element, param){
    return this.optional(element) || ( parseFloat(unformatNumber(value)) <= parseFloat(unformatNumber(param)) );
}, $.validator.format("O valor informado precisa ser menor que {0}")); 



/* Funções Linoforte */

//Salva os dados no banco de dados
function salvar(){                
    if($("#frmDados").valid()){
        $.ajax({
            url     : $("#frmDados").prop("action"),
            method  : "post",
            dataType: "json",
            data    : $("#frmDados").serialize(),
            error: function(){
                $("#mensagem_erro").html("Ocorreu um erro imprevisto ao enviar os dados para o banco. Por favor, contate o administrador do sistema.");
                $("#modal_erro").modal("show");
            },
            success: function(retorno){
                if(retorno.status == "OK") {
                    $("#modal_success").modal("show");
                    setTimeout(function(){
                        $("#modal_success").modal("hide");
                        location.href = "?" + retorno.redirecionamento;
                    }, 2000);
                } else {
                    $("#mensagem_erro").html("Não foi possível completar a operação, tente novamente!<br/><br/>" + retorno.mensagem);
                    $("#modal_erro").modal("show");
                }
            }
        });
    } 


    return false;
}



/* Tratamento durante a execução */

//Fecha modal de impressão e limpa os elementos na tela
$(document).ready(function(){
    $("#layModalImprimir").on("hide.bs.modal", function(){
        $("#ifrRelatorio").attr("href", "relatorios/");
        $("#layModalImprimirLabel").html("");
    });
});