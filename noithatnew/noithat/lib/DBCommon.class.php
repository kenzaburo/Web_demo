<?php
	require_once('BaseDir.inc.php');
	require_once("Env.inc.php");
	require_once("Log.class.php");
	/*require_once("htmltemplate.inc");*/

	define("DB_HOST","localhost");
	define("DB_DATABASE","noithatnhatnghia");
	define("DB_USERNAME","root");
	define("DB_PASSWORD","030288");

	class DBCommon {
	    function __construct() {				
		}

		function stl_openConn() {	
			$host = DB_HOST;
			$username = DB_USERNAME;
			$password = DB_PASSWORD;
			$database = DB_DATABASE;
			$conn = mysql_connect($host, $username, $password);
			if ($conn) {
		 		$ret = mysql_select_db($database);
		 		if (!$ret) return false;
				mysql_query("SET NAMES utf8");
		 	}  else {
		 		return false;
		 	}
		    return $conn;
		}

		function stl_closeConn($conn) {
			if ($conn != null) {
				mysql_close($conn);
			}
			return true;
		}

		function stl_openRS($conn, $sQuery) {
			$rs = mysql_query($sQuery,$conn);
			return $rs;
		}

		function stl_closeRS($rs) {
			mysql_free_result($rs);
		}

		function stl_rowcount($rs){
			return mysql_num_rows($rs);
			
		}
			
		function stl_fetch($rs) {
			return mysql_fetch_array($rs, MYSQL_ASSOC);
		}	

		function stl_getErrorMessage($conn){
			return mysql_error($conn);
		}
		
		function stl_isNull($input)	{
			if(is_null($input) || strlen(trim($input))==0)
			{
				return true;
			}
			return false;
		}
		
		function stl_db_select($sQuery,$conn){
			if ($conn){
				$res = DBCommon::stl_openRS($conn, $sQuery);
				$temp = DBCommon::stl_rowcount($res);
				if (DBCommon::stl_rowcount($res) != 0){
					$arrFields = DBCommon::getFieldNameList($res);
					$intFields = count($arrFields);
					$index = 0;
					while ($data = DBCommon::stl_fetch($res)){
						$oStructData = null;
						for($i = 0; $i < $intFields; $i++){
							$sFieldName = $arrFields[$i];
							$oFieldName->$sFieldName = $sFieldName;
							$oStructData->$sFieldName = $data["".$sFieldName.""];
						}
						$arrData[$index] = $oStructData;
						$index++;
					}
				}
				DBCommon::stl_closeRS($res);
			}
			return $arrData;
			
		}
		
		function getFieldNameList($rs){
			$numField = mysql_num_fields($rs);
		    for ($i = 0; $i < $numField; $i++) {
				$arrFieldName[] = mysql_field_name($rs, $i);
			}
			return $arrFieldName;
} 
		function stl_db_change($cQuery,$conn){
			if (!DBCommon::stl_isNull($cQuery)) {
				$bRet = DBCommon::stl_openRS($conn, $cQuery);
				if (!$bRet) {
					return DBCommon::stl_getErrorMessage($conn);
				}
			} else {
				return ERR_CONVERT_QUERY;
			}
            if(mysql_affected_rows()>0)
            {
                return 'OK';
            }
            else 
            {
                return 'NG';
            }
	    }
	}
?>
