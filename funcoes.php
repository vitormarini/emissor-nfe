<?php 
    /************************************
    *          Osvaldo Cruz/SP          *
    *                                   *
    * Criacao.: 08/08/2018              *    
    * Versao.: 1.0.0                    *
    ************************************/
    date_default_timezone_set('America/Sao_Paulo');
    
    //Constantes para a função de máscara
    const RETIRA_MASCARA = 1;
    const MASCARA_CNPJ   = 2;
    const MASCARA_CPF    = 3;
    const MASCARA_CEP    = 4;
    const MASCARA_RG     = 5;
    const MASCARA_FONE   = 6;
    
    //Constantes para a função trataQuebraLinha
    const RECUPERACAO = 1;
    const ENVIO       = 2;
    
    /**
     * Converte a data de (YYYY-mm-dd) MySql para (dd-mm-YYYY) Brasil
     * @param string $dt Data a ser convertida
     * @return string Data formatada
     */
    function data_format(&$dt){
        return date('d/m/Y', strtotime($dt));
    }
    
    /**
     * Alias para a função data_format.<br>
     * Formata uma data no formato brasileiro.
     * @param string $data Data a ser formatada
     * @return string Data em formato brasileiro
     */
    function dataBrasil(&$data){
        return data_format($data);
    }
    
    /**
     * Converte a data de (dd-mm-YYYY) MySql para (YYYY-mm-dd) 
     * @param string $dt Data a ser convertida
     * @return string Data formatada
     */
    function data_format_mysql(&$dt){
        $dt = str_replace('/', '-', $dt);
        return date('Y-m-d', strtotime($dt));
    }
    
    /**
     * Alias para a função data_format_mysql.<br>
     * Formata uma data no formato do mysql (Y-m-d).
     * @param string $data Data a ser formatada
     * @return string Data em formato mysql
     */
    function dataBanco(&$data){
        return data_format_mysql($data);
    }
    
    /**
     * Alias para a função data_format_mysql.<br>
     * Formata uma data no formato do mysql (Y-m-d).
     * @param string $data Data a ser formatada
     * @return string Data em formato mysql
     */
    function dataHoraBanco($data){
        return date("Y-m-d H:i:s", strtotime(str_replace("/", "-", $data)));
    }
    
    /**
     * Retorna a diferença em dias entre duas datas...
     * @param Date $data_inicial A data inicial a ser comparado.
     * @param Date $data_final A data final a ser comparado.
     * @param String $formato_data Formato em que as datas estão. Ex: 'd/m/Y' ou 'd-m-Y' e etc...
     * @return Interger Retorna o valor da diferença em dias em caso de sucesso, e FALSE em caso de erro.
     */
    function diasEntreDatas($data_inicial, $data_final, $formato_data = null){  //Versão Beta.

        $formato = (!is_null($formato_data)) ? $formato_data : 'd/m/Y';

        $datetime1 = DateTime::createFromFormat($formato, $data_inicial);
        $datetime2 = DateTime::createFromFormat($formato, $data_final);
        $interval = $datetime1->diff($datetime2);
        return $interval->days;

    }
    
    /**
     * Converte os valores unitários tanto para moeda americana como brasileira...
     * @param string $get_valor - Valor a ser tratado
     * @return string Valor tratado
     */
    function moeda($get_valor) {
	$source = array('.', ',');
	$replace = array('', '.');
	$valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
	return $valor; //retorna o valor formatado para gravar no banco
    }
    
    /**
     * Converte os valores unitários tanto para moeda americana como brasileira...
     * @param string $get_valor - Valor a ser tratado
     * @return string Valor tratado
     */
    function moeda_brasileira($get_valor) {
	$source = array('.', ',');
	$replace = array(',', '.');
	$valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
	return $valor; //retorna o valor formatado para gravar no banco
    }
          
    /**
     * Retira máscara ou formata o dado passado para CNPJ, CPF, CEP, RG e TELEFONE.
     * @param string $objeto Dado a ser aplicado ou retirado a máscara
     * @param int $tipo Operação a ser realizada com os dados. Pode ser <b>RETIRA_MASCARA</b>, <b>MASCARA_CNPJ</b>, 
     * <b>MASCARA_CPF</b>, <b>MASCARA_CEP</b>, <b>MASCARA_RG</b>, <b>MASCARA_FONE</b>
     * @return string Dado formatado
     */
    function mascaras($objeto, $tipo){
        
        //Tratamentos
        $remover = array(".", "-", "/", "(", ")", " ");       
        $dados   = retira($remover, $objeto);
        
        //Retorna string vazia caso seja um valor validado como vazio
        if(empty($objeto)) return "";        
        
        //Valida o tipo de operação
        switch($tipo){
            
            case RETIRA_MASCARA :{ return $dados; }break;
            case MASCARA_CNPJ   :{ return substr($dados, 0, 2) . "." . substr($dados, 2, 3) . "." . substr($dados, 5, 3) . "/" . substr($dados, 8, 4) . "-" . substr($dados, 12, 2); }break;
            case MASCARA_CPF    :{ return substr($dados, 0, 3) . "." . substr($dados, 3, 3) . "." . substr($dados, 6, 3) . "-" . substr($dados, 9, 2)                              ; }break;
            case MASCARA_CEP    :{ return substr($dados, 0, 2) . "." . substr($dados, 2, 3) . "-" . substr($dados, 5, 3)                                                           ; }break;
            case MASCARA_RG     :{ return substr($dados, 0, 2) . "." . substr($dados, 2, 3) . "." . substr($dados, 5, 3) . "-" . substr($dados, 8, 1)                              ; }break;
            case MASCARA_FONE   :{ return mascaraTelefone($dados)                                                                                                                  ; }break;
            
        }
       
    }
    
    /**
     * Formata números telefônicos no formato ddd+número
     * @param string $objeto Número a ser formatado (somente digitos)
     */
    function mascaraTelefone($objeto){ 
        
        //Retira qualquer possível formatação existente
        $numero = mascaras($objeto, RETIRA_MASCARA);

        //Formata numero passado
        if(empty($numero)) return $numero;
        else if(strlen($numero) <= 10) return '('.substr($numero,0,2).') '.substr($numero,2,4).'-'.substr($numero,6,4);
        else return '('.substr($numero,0,2).') '.substr($numero,2,5).'-'.substr($numero,7,4);
        
    }
    
    /**
     * Formata o número do lançamento bancário no formato 999.999.999
     * @param string $objeto Número a ser formatado (somente digitos)
     */
    function mascaraLancamentoBancario($objeto){        
        return substr($objeto,0,3).'.'.substr($objeto,3,3).'.'.substr($objeto,6,3);
    }
    
    /**
     * Retira todas as ocorrências do elemento de uma string ou array. 
     * @param mixed $remover String ou Array contendo o elemento a ser removido do objeto
     * @param mixed $objeto String ou Array de onde o elemento será retirado.
     * @return string String ou Array sem ocorrência do elemento passado
     */
    function retira($remover, $objeto){
        return str_replace($remover, "", $objeto);        
    }
    
    /**
     * Verifica se uma data proveniente do banco é nula (0000-00-00)
     * @param string $data - Data a ser verificada
     * @param boolean $dataAtual - Indica se a data atual deve ser retornado em caso de data nula proveniente do banco.
     * @param boolean $incluiHora - Indica se a hora está presente no parâmetro $data informado ou será retornado quando a data for nula proveniente do banco.
     * @return string Data formatada, vazio se parâmetro $dataAtual for falso, ou data atual caso o parâmetro $dataAtual seja verdadeiro.
     */
    function validaData($data, $dataAtual = false, $incluiHora = false){
        if(!empty(trim($data)) && trim($data) !== '0000-00-00' && !$incluiHora) return date("d/m/Y", strtotime($data));
        else if(!empty(trim($data)) && trim($data) !== '0000-00-00' && $incluiHora) return date("d/m/Y H:i:s", strtotime($data));
        else if($dataAtual && !$incluiHora) return date("d/m/Y");
        else if($dataAtual && $incluiHora) return date("d/m/Y H:i:s");
        else return "";
    }
    
    /**
     * Coloca a primeira letra de todas as palavras em maiúscula.<br>
     * Por padrão ignorando as preposições presentes no texto.
     * Para não ignorar preposições, passe false no segundo parametro.
     * @param String $texto Texto a ser capitalizado
     * @param boolean $ignorar Ignora ou não preposições - Padrao: True
     * @return String Texto capitalizado
     */
    function capitalize($texto, $ignorar = true){
        
        $capitalize = ucwords(strtolower($texto));
        
        //Verifica se deve deixar as preposições normais
        if(!$ignorar) return $capitalize;
        
        //Preposições a serem mantidas   
        $antes = array( "E", "De", "Da", "Do", "Das", "Dos");
        $depois = array( "e", "de", "da", "do", "das", "dos");
        
        return str_replace($antes, $depois, $capitalize);       
 
    }
    
    
    /**
     *  Cálcula digito verificador para número de pedido.     
     * @example calculaDigitoVerificador(2014000001); 
     * 
     * @param int $numero_pedido Número a ser cálculado o digito verificador.
     * 
     * @return int Número do pedido com o digito verificador.
     */
    function calculaDigitoVerificador($numero_pedido){
        $peso = "1212121212";        
        $soma = 0;
       
        for($i = 0; $i < 10; $i++){ 
            $valor = substr($numero_pedido,$i,1) * substr($peso,$i,1); 

            if($valor>9) $soma = $soma + substr($valor,0,1) + substr($valor,1,1);
            else $soma = $soma + $valor;

        }

        $dv = (10 - ($soma % 10));
        if(($soma % 10)==0)$dv = 0;
        return $numero_pedido.$dv;
    }

    function extenso($valor = 0, $maiusculas = false) {

        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        $z = 0;
        $rt = "";
        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);

        for ($i = 0; $i < count($inteiro); $i++)
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];

        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
                $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")
                $z++;
            elseif ($z > 0)
                $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                    ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        if (!$maiusculas) {
            return($rt ? $rt : "zero");
        } else {

            if ($rt)
                $rt = ereg_replace(" E ", " e ", ucwords($rt));
            return (($rt) ? ($rt) : "Zero");
        }
    }
    
    /**
     * Aplica a máscara para o documento informado. Utiliza $pessoa para aplicar a máscara
     * correta ou calcula o tamanho do documento passado para determinar qual máscara será
     * usada. Quando utlizando o tamanho do documento a máscara será definida seguindo o 
     * seguinte princípio: 14 caracteres para CNPJ, 11 para CPF e o restante Exterior.
     * 
     * @param String $documento Número do documento onde aplicar a máscara
     * 
     * @param Char $pessoa Pessoa do documento, podendo ser: <br/> F - Pessoa Física<br/> 
     * J - Pessoa jurídica<br/> E - Exterior
     * 
     * @return String Retorna o documento com a máscara adequada aplicada ou string vazia
     * em caso de erro.
     */
    function formataCpfCnpj($documento, $pessoa = null){
        
        //Despresa valores maiores que 14, retornando erro
        if(strlen($documento) > 14) return "";       
            
        //Aplica máscara para pessoa física
        if($pessoa == "F" || strlen($documento) == 11) 
            return substr($documento, 0, 3). '.' .substr($documento, 3, 3). '.' .substr($documento, 6, 3). '-' .substr($documento, 9, 2);

        //Aplica máscara para pessoa jurídica
        else if($pessoa == "J" || strlen($documento) == 14)
            return substr($documento, 0, 2). '.' .substr($documento, 2, 3). '.' .substr($documento, 5, 3). '/' .substr($documento, 8, 4). '-' .substr($documento, 12, 2);

        //Devolve documento sem máscara. Nesse caso máscara não disponível
        else if($pessoa == "E" || strlen($documento) < 11 ) return $documento;
        
    }
    
    /**
     * Calcula o dígito verificador do código de barras - padrão EAN-13
     * @param string $numero Código de Barras
     * @return int Retorna o dígito verificador para o código passado
     */
    function digitoVerificadorEan13($numero){
        
        //Separa todos os digitos do código de barras
        $numeroSeparado = str_split($numero);
        
        $somaPar = $somaImpar = 0;
       
        //Soma as posições pares e ímpares
        foreach($numeroSeparado as $indice => $valor){
            
            //Verifica se o indice é par ou impar
            if(($indice % 2) == 0) $somaImpar += $valor;
            else $somaPar += $valor;
            
        }
        
        //Calcula o resto da divisão
        $resto = (($somaPar * 3) + $somaImpar) % 10;
        
        //Determina dígito verificador
        return  $dv = ($resto != 0) ? (10 - $resto) : $resto;
        
    }
    
    /**
     * Coloca null nos campos vazios nos script enviados ao banco
     */
    function replaceEmptyFields($script){
        return str_replace("''", "NULL", $script);
    }

    /**
     * Verifica se o valor passado é válido e formata o valor no padrão brasileiro.
     * Permite alterar a formatação do valor passando a quantidade de casas e os separadores de dezena e milhar.
     * @param float $valor Valor a ser validado
     * @param int $casasDecimais [Opcional] Número de casas decimais para formatar o valor passado
     * @param string $separadorDecimal [Opcional] Separador de casas decimais
     * @param string $separadorMilhar [Opcional] Separador de casas de milhar
     */
    function validaFormataValor($valor, $casasDecimais = 2, $separadorDecimal = ",", $separadorMilhar = "."){
        
        if(empty($valor)) return "0,00"; 
        else return number_format($valor, $casasDecimais, $separadorDecimal, $separadorMilhar);
        
    }
    
    /**
     * Trata a recuperação e envio do banco para campos texto quando existem quebras de linhas.
     * @param String $dado Dado a ser tratado
     * @param int $tipo Tipo de tratamento a ser realizado. Pode ser RECUPERACAO ou ENVIO
     */
    function trataQuebraLinha($dado, $tipo){
        
        if($tipo == RECUPERACAO) return str_replace("\\n", chr(13), $dado);
        else return str_replace(chr(13), "\\n", $dado);
        
    }
    
    /**
     * Retorno o número desejado sem formatações e com a quantidade corretas de casas depois da vírgula.
     * @param float $valor Valor a ser tratado. 
     * @param int $casasDecimais Quantidade de casas decimais desejada.
     * @return String Valor corrigido.
     */
    function valorIntegracao($valor, $casasDecimais = 2){        
        return number_format($valor, $casasDecimais, "", "");        
    }
   
    
    function dataIntegracao($data){        
        return substr($data, 4, 4) . "-" . substr($data, 2, 2) . "-" . substr($data, 0, 2) . " " . 
               substr($data, 8, 2) . ":" . substr($data, 10, 2) . ":". substr($data, 12, 2);        
    }
    
    /**
     * Verifica se um valor pertence ao array passado. Usado para validação dos 
     * checkbox do cadastro de perfil.
     * @param array $dados Local onde será realizado a busca.
     * @param mixed $valor Valor procurado
     * @return string Retorna 'checked' caso encontrado ou vazio caso contrário
     */
    function verificaCheck($dados, $valor, $verificaDesabilitado = false){
        
        $retorno = "";
        
        //Verifica se o checkbox deverá ser marcado quando for módulo ou ação
        if(array_search($valor, $dados) !== false) $retorno = "checked";
                    
        //Verifica se deverá marcar como desabilitado
        if($verificaDesabilitado) $retorno .= " disabled";

        //Retorna valor processado
        return trim($retorno);
        
    }