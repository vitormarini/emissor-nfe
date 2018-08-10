<?php
    class log {
        public static function gravaLog( $strTabela, $strId, $strOperacao ) {
        }
//        public static function gravaLog( $strTabela, $strId, $strOperacao ) {
//            global $bd;
//            global $objBDLog;
//            
//            $strTabela = strtolower( $strTabela );
//            
//            // verifica se a tabela existe e se está de acordo com a atual
//            self::_procuraTabela( $strTabela );
//            
//            // pega campos para montar o insert no log
//            $objDados = $objBDLog->Execute( "SELECT LOWER( column_name ) AS column_name, column_type, column_key 
//                                             FROM information_schema.columns
//                                            WHERE ( table_schema        = 'lino' )
//                                              AND ( LOWER( table_name ) = '$strTabela' )
//                                           ORDER BY ordinal_position" );
//            $strColunas = "";
//            while ( !$objDados->EOF ) {
//                if ( $objDados->fields['column_key'] === 'PRI' ) {
//                    $strIdTabela = $objDados->fields['column_name'];
//                }
//                
//                $strColunas .= "{$objDados->fields['column_name']},";
//                $objDados->MoveNext();
//            }
//            $strColunas = substr( $strColunas, 0, -1 );
//
//            $objDadosTabela = $bd->Execute( "SELECT $strColunas
//                                               FROM $strTabela
//                                              WHERE ( {$strIdTabela} = {$strId} )" );
//                                              
//            $strInsert = "INSERT INTO {$strTabela} ( cod_usua, operacao, $strColunas )
//                                            VALUES ( {$_SESSION['cod_usua']}, '$strOperacao', ";
//            
//            $arrColunas = explode( ",", $strColunas );
//            
//
//            foreach ( $arrColunas as $strCampo ) {
//                $strInsert .= "'{$objDadosTabela->fields[$strCampo]}',";
//            }
//            $strInsert = str_replace( "''", "NULL", substr( $strInsert, 0, -1 ) . "  );" );
//
//            $objDados = $objBDLog->Execute( $strInsert );
//        }
        
        private static function _procuraTabela( $strTabela ) {
            global $objBDLog;

            // verifica se a tabela existe no banco de log
            $objDados = $objBDLog->Execute( "SELECT table_schema, table_name, column_name, ordinal_position, data_type, numeric_precision, column_type 
                                             FROM information_schema.columns
                                            WHERE ( table_schema = 'log' )
                                              AND ( table_name   = '$strTabela' )" );
            
            // se exitir a tabela
            if ( $objDados->RecordCount() > 0  ) {
                $objDados = $objBDLog->Execute( "SELECT table_name, LOWER( column_name ) AS column_name, column_type, column_key 
                                                 FROM information_schema.columns
                                                WHERE ( table_schema        = 'lino' )
                                                  AND ( LOWER( table_name ) = '$strTabela' )
                                                  AND ( column_name         NOT IN ( SELECT column_name
                                                                                       FROM information_schema.columns
                                                                                      WHERE ( table_schema        = 'log' )
                                                                                        AND ( LOWER( table_name ) = '$strTabela' )
                                                                                     ORDER BY ordinal_position ) )
                                               ORDER BY ordinal_position" );
                if ( $objDados->RecordCount() > 0 ) {
                    $strColunas .= "ALTER TABLE `{$objDados->fields['table_name']}` ADD (";
                    while ( !$objDados->EOF ) {
                        $strColunas .= "`{$objDados->fields['column_name']}` {$objDados->fields['column_type']},";
                        $objDados->MoveNext();
                    }
                    $strColunas = substr( $strColunas, 0, -1 ) . "  )";
                    
                    try {
                        $objBDLog->BeginTrans();
                        $objBDLog->Execute( $strColunas );
                        $objBDLog->CommitTrans();
                    }
                    catch ( ErrorException $err_Erro ) {
                        $objBDLog->RollbackTrans();
                        print $err_Erro->getMessage();
                    }    
                }
    	    }
            else { // se não achar a tabela cria ela
                // verifica se a tabela existe no banco de log
                $objDados = $objBDLog->Execute( "SELECT table_name, LOWER( column_name ) AS column_name, column_type, column_key 
                                                 FROM information_schema.columns
                                                WHERE ( table_schema        = 'lino' )
                                                  AND ( LOWER( table_name ) = '$strTabela' )
                                               ORDER BY ordinal_position" );
                $strCreate = "CREATE TABLE `{$objDados->fields['table_name']}` (
                                  `id_log` BIGINT NOT NULL AUTO_INCREMENT,
                                  `data_log` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                                  `cod_usua` MEDIUMINT NOT NULL,
                                  `operacao` CHAR(1),";    
                while ( !$objDados->EOF ) {
                    $strCreate .= "`{$objDados->fields['column_name']}` {$objDados->fields['column_type']},";
                    $objDados->MoveNext();
                }
                $strCreate .= "PRIMARY KEY ( `id_log` ) );";

        	    try {
                    $objBDLog->BeginTrans();
                    $objBDLog->Execute( $strCreate );
                    $objBDLog->CommitTrans();
                }
                catch ( ErrorException $err_Erro ) {
                    $objBDLog->RollbackTrans();
                    print $err_Erro->getMessage();
                }    
            }
        }
    }
?>