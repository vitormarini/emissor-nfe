<?php
    /************************************
    *          Osvaldo Cruz/SP          *
    *                                   *
    * Criacao.: 08/08/2018              *    
    * Versao.: 1.0.0                    *
    ************************************/
    session_start();
    ini_set('display_errors', 1);
    error_reporting( E_ALL );
    //Includes obrigatórios
    require_once "adodb/adodb-exceptions.inc.php";
    require_once "adodb/adodb.inc.php";
    include_once( "config.inc.php" );

    class gridJSON {
        private $_arrDados = array(); // array com os dados
        private $_arrJSON  = null; //representacao string da Array(JSON)
        public $obj_Conecta;

        function __construct(){
            $this->ResetJSON();
            $this->converteCaracteres();
        }

        public function criaConexao() {
            $this->obj_Conecta               = ADONewConnection("postgres");
            $this->obj_Conecta->debug        = false;
            $this->obj_Conecta->autoRollback = true;
            $this->obj_Conecta->PConnect("host=" . configuracoes::$strCaminhoBD . " port=5432 dbname=" . configuracoes::$strNomeBD . " user=" . configuracoes::$strUsuarioBD . " password=" . configuracoes::$strSenhaBD);
            //Previne erros de codificação - Postgres
            $this->obj_Conecta->Execute("SET NAMES 'utf8'");
            $this->obj_Conecta->Execute("SET CLIENT_ENCODING TO utf8");
        }

        public static function criaNovaConexao() {
            $obj_NovaConexao               = ADONewConnection("postgres");
            $obj_NovaConexao->debug        = false;
            $obj_NovaConexao->autoRollback = true;
            $obj_NovaConexao->PConnect("host=" . configuracoes::$strCaminhoBD . " port=5432 dbname=" . configuracoes::$strNomeBD . " user=" . configuracoes::$strUsuarioBD . " password=" . configuracoes::$strSenhaBD);
            $obj_NovaConexao->Execute("SET NAMES 'utf8'");
            $obj_NovaConexao->Execute("SET CLIENT_ENCODING TO utf8");
            return $obj_NovaConexao;
        }

        public static function criaNovaConexaoPesquisa() {
            $obj_NovaConexao = ADONewConnection("postgres");
            $obj_NovaConexao->debug = false;
            $obj_NovaConexao->autoRollback = true;
            $obj_NovaConexao->PConnect("host=" . configuracoes::$strCaminhoBD . " port=5432 dbname=pesquisas user=" . configuracoes::$strUsuarioBD . " password=" . configuracoes::$strSenhaBD);
            return $obj_NovaConexao;
        }

        private function ResetJSON() {
            $this->_arrDados = null;
            $this->_arrJSON = null;
            $this->setLimit(10);
        }

        public function constroiJSON($strSQL, $intPage = 1, $strOrdem = null, $strOrderTipo = null, $strConexao = null) {
            if (is_null($strConexao)) {
                $this->criaConexao();
            } else {
                $this->criaConexaoPesquisa();
            }
            //funcao principal da classe - constroi o JSON com paginacao dos dados
            //obtidos a partir do $strSQL passado e os criterios de ordenacao

            try {
                if (!$objConsulta = $this->obj_Conecta->Execute($strSQL)) {
                    throw new Exception("Erro");
                }

                $intContador = $objConsulta->RecordCount();

                $intLimit = $this->getLimit();
                if ($intContador > 0) {
                    $intTotalPaginas = ceil($intContador / $intLimit);
                } else {
                    $intTotalPaginas = 0;
                }

                if ($intPage > $intTotalPaginas) {
                    $intPage = $intTotalPaginas;
                }

                $intInicio = $intLimit * $intPage - $intLimit;

                if ($intInicio < 0) {
                    $intInicio = 0;
                }
                if (!is_null($strOrdem)) {
                    $strOrdenarPor = " ORDER BY $strOrdem $strOrderTipo ";
                }

                $strSQL .= " $strOrdenarPor LIMIT $intLimit OFFSET $intInicio ";
                //$strSQL .= "$strOrdenarPor LIMIT $intInicio, $intLimit";
                //exit($strSQL);
                //busco os registros
                $this->obj_Conecta->SetFetchMode(ADODB_FETCH_ASSOC);

                if (!$objConsulta = $this->obj_Conecta->Execute($strSQL)) {
                    throw new Exception(pg_result_error());
                }

                $this->addItemArray($intTotalPaginas, 'total');
                $this->addItemArray($intPage, 'page');
                $this->addItemArray("$intContador", 'records');

                $arr = array();
                $c = 0;
                $intCampos = $objConsulta->FieldCount();
                while ($arrCampos = $objConsulta->FetchRow()) {
                    $arr[$c]['id'] = "$c";
                    foreach ($arrCampos as $chave => $valor) {
                        $arr[$c]['cell'][] = iconv("ISO-8859-1", "UTF-8//TRANSLIT", "$valor");
                    }
                    $c++;
                }
                $this->addItemArray($arr, 'rows');

                return $this->_arrJSON = json_encode($this->_arrDados);
            } catch (Exception $err) {
                $this->trataErro($err->getMessage(), true, __METHOD__, $err->getLine());
            }
        }

        private function addItemArray($valor = 0, $key = null) {
            //adiciona um item ao  array dos dados
            if (is_null($key)) {
                $this->_arrDados[] = $valor;
            } else {
                $this->_arrDados[$key] = $valor;
            }
        }

        public function getArrJSON() {
            return $this->_arrJSON;
        }

        public function getArrDados() {
            return $this->_arrDados;
        }

        public function setLimit($limit) {
            $this->_limit = $limit;
        }

        public function getLimit() {
            return $this->_limit;
        }

        private function trataErro($erro, $die = false, $metodo = "nenhum", $linha = 1) {
            //pensar em fazer classe trata_erros
            $msg = "Erro em $metodo; Linha " . $linha . ": " . $erro;
            if ($die) {
                die($msg);
            } else {
                return $msg;
            }
        }

        public function converteCaracteres() {
            $this->retiraEspacos();
            foreach ($_POST as $chave => $valor) {
                $valor = str_replace("'", "", $valor);
                $_POST[$chave] = trim(utf8_decode(trim($valor)));
            }
        }

        public function retiraEspacos() {
            foreach ($_POST as $chave => $valor) {
                $_POST[$chave] = trim($valor);
            }
        }

        public function montaFiltro($strTabela, $strCondicao) {
            // quando a pesquisa na grade for limitada a uma condicao
            if (isset($_GET['searchField']) && trim($_GET['searchField']) !== '') {
                $strTabela = ( $strTabela != '' ? $strTabela . '.' : '' );
                $_GET['searchString'] = utf8_decode(trim($_GET['searchString']));

                $arrOperacao['eq'] = " = '{$_GET['searchString']}'";
                $arrOperacao['ne'] = " <> '{$_GET['searchString']}'";
                $arrOperacao['lt'] = " < '{$_GET['searchString']}'";
                $arrOperacao['le'] = " <= '{$_GET['searchString']}'";
                $arrOperacao['gt'] = " > '{$_GET['searchString']}'";
                $arrOperacao['ge'] = " >= '{$_GET['searchString']}'";
                $arrOperacao['bw'] = " iLIKE '%{$_GET['searchString']}' ";
                $arrOperacao['ew'] = " iLIKE '{$_GET['searchString']}%'";
                $arrOperacao['cn'] = " iLIKE '%{$_GET['searchString']}%'";

                $strFiltro = $arrOperacao[$_GET['searchOper']];

                return " $strCondicao ( $strTabela{$_GET['searchField']} $strFiltro ) ";
            }
            // quando a pesquisa foi por N campos
            else if (isset($_GET['filters']) && trim($_GET['filters']) !== '') {
                $arrFiltro = json_decode($_GET['filters']);
                $strTabela = ( $strTabela != '' ? $strTabela . '.' : '' );

                $arrOperacao['eq'] = " = 'busca'";
                $arrOperacao['ne'] = " <> 'busca'";
                $arrOperacao['lt'] = " < 'busca'";
                $arrOperacao['le'] = " <= 'busca'";
                $arrOperacao['gt'] = " > 'busca'";
                $arrOperacao['ge'] = " >= 'busca'";
                $arrOperacao['bw'] = " iLIKE '%busca' ";
                $arrOperacao['ew'] = " iLIKE 'busca%'";
                $arrOperacao['cn'] = " iLIKE '%busca%'";

                $strFiltro = "";
                foreach ($arrFiltro->rules AS $objeto) {
                    $strOperacao = str_replace('busca', utf8_decode(trim($objeto->data)), $arrOperacao[$objeto->op]);
                    $strFiltro .= " {$strCondicao} ( {$strTabela}{$objeto->field} {$strOperacao} )";
                    $strCondicao = ( trim(strtoupper($strCondicao)) === 'WHERE' ? "{$arrFiltro->groupOp}" : "{$arrFiltro->groupOp}" );
                }
    //                exit($strFiltro);
                return "{$strFiltro}";
            }
        }
    }
?>