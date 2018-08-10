/**
 * Converte um valor para moeda Brasileira( Real R$ ).
 * 
 * @example number_format(560.90,2,",",".");
 * 
 * @param {float}   numero   - Parâmetro obrigatório.
 * @param {number}  decimais - Parâmetro obrigatório. 
 * @param {char}    sep_dec  - Parâmetro opcional.
 * @param {char}    sep_mil  - Parâmetro opcional.
 * @returns {float}
 */
function number_format(numero, decimais, sep_dec, sep_mil) 
{
    var n = !isFinite(+numero) ? 0 : +numero,
    prec  = ((!isFinite(+decimais)) ? 0 : Math.abs(decimais)),
    sep   = ((typeof sep_mil === 'undefined') ? ',' : sep_mil),
    dec   = ((typeof sep_dec === 'undefined') ? '.' : sep_dec),
    s     = '',
    toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
    };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    
    if (s[0].length > 3){
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec){
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}



/** 
 *  Converte o formato moeda Brasileira( Real R$ ) para moeda Americana( Dolar U$S).
 *  
 *  @description Além da conversão entre moedas, esta função funciona como conversão para porcentagens, metros e quilogramas.
 *  @example UnNumber_format(1.056,00);  
 *  @param {float} - Valor a ser convertido.
 *  @return {float} - 1056.00
 */
function UnNumber_format(valor){
    var aux = valor.trim();
    aux = aux.replace(/[/.\-/R/$/%/M/Kg]/g,"");
    aux = aux.replace(",",".");
    return aux.trim();
}

/**
 *  Retira todos os espaços anterior e posterior de uma string.
 *  
 *  @example trim('  Lorem Ipsum   ');
 *  @param {strign} Valor a ser filtrado.
 *  @return {string} Lorem Ipsum
 */
function trim(str){
    return str.replace(/^\s+|\s+$/g,"");
}


/**
 *  Formata uma data vinda do banco de dados em formato americano(0000-00-00) para brasileira(00-00-0000).
 *  
 *  @example data_format('0000-00-00');
 *  @param {date} Data a ser formatada.
 *  @return {string} Retorna uma string concatenada.
 */
function data_format(data){
    var dt = data.split('-');
    return dt[2]+'/'+dt[1]+'/'+dt[0];
}


/**
 *  Retorna o valor da variável enviada pela URL.
 *  
 *  @example getParameterByName('nome_variavel');
 *  @param {String} Nome da variável pela qual deseja obter o seu valor.
 *  @return {String} Valor da variável.
 */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");    
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),    
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

/**
 * Formata o valor passado para o padrão brasileiro.
 * 
 * @param {float}   numero   - Número a ser formatado.
 * @param {int}     casas - Quantidade de casas decimais.
 * @param {char}    decimal  - [Opcional] Separador decimal a ser utilizado. Padrão é a virgula.
 * @param {char}    milhar  - [Opcional] Separador de milhar a ser utilizado. Padrão é o ponto.
 * @returns {string} Valor formatado no padrão brasileiro.
 */
function formatNumber(numero, casas, decimal, milhar){    
    
    //Inicialização das variáveis
    var n      = !isFinite(numero) ? 0 : numero;
    var prec   = (!isFinite(casas)) ? 0 : Math.abs(casas);
    var sep    = milhar || ".";
    var dec    = decimal || ",";
    var solved = '';
    
    //Declaração de função
    var toFixedFix = function(n, prec){
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
    };    
    
    solved = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    
    if (solved[0].length > 3){
        solved[0] = solved[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    
    if ((solved[1] || '').length < prec){
        solved[1] = solved[1] || '';
        solved[1] += new Array(prec - solved[1].length + 1).join('0');
    }
    
    //Retorna o valor formatado
    return solved.join(dec);
}

/** 
 *  Converte o valor passado de moeda brasileira para moeda americana.
 *  @example unformatNumber("1.056,00");  
 *  @param {string} valor - Valor a ser convertido.
 *  @return {float} Valor convertido
 */
function unformatNumber(valor){
    return valor.replace(/[/./R/$/%/M]/g, "").replace(",", ".");
}

/**
 * Arredonda o numero com maior precisão. Não ocorre o erro do 1.005
 * ser arredondado para 1.00
 * @param {float} value Valor a ser arredondado
 * @param {int} decimals Quantidade de casas decimais
 * @returns {float} Retorna o numero dado arredondado para a quantidade
 * de casas solicitadas
 */
function arredondar(value, decimals){
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}

/**
 * Valida os valores de percentuais entre 0 e 100.
 * @param {float} value Valor da porcentagem a ser verificado.
 * @returns {boolean} Retorna TRUE em caso de sucesso onde o valor esteje 
 * entre 0 e 100(%) e FALSE em caso o valor seje diferente.
 */
function valida_porcentagem(x) {
    var parts = x.split(".");
    if (typeof parts[1] == "string" && (parts[1].length == 0 || parts[1].length > 2))
        return false;
    var n = parseFloat(x);
    if (isNaN(n))
        return false;
    if (n < 0 || n > 100)
        return false;

    return true;
}

//Retorna a string passada com a primeira letra de cada palavra maiúscula
function capitalizeEachWord(str) {
    return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}