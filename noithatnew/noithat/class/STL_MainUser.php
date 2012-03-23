<?php
/*
* Stagly Soft
* Author: phuong.ho
* Description:
* Created on: 10:28 AM 11/12/2009
* Last Updated:
* Version: 1.0
*/

class STL_MainUser
{
    var $attTableName;
   
    function STL_MainUser()
    {
		/*require_once('../lib/'.'BaseDir.inc.php');*/
		require_once(STL_LIB.'DBCommon.class.php');
    }
	function stl_Get_9_Bestseller(){
		$sql  =" SELECT ID, Name, Description, Price, Image, date_format(CreateDate,'%Y-%m-%d') AS CreateDate, LastUpdate,IsDeleted ";
        $sql .=" FROM p_bestseller WHERE IsDeleted = 1 ";
		$sql .=" ORDER BY LastUpdate DESC ";
        $sql .=" LIMIT 0, 9 ";
		
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);
		return $result;
	}
	function stl_Get_Info_Relax($ID){
		$sql  =" SELECT ID, Name, Content, date_format(CreateDate,'%Y-%m-%d') AS CreateDate, LastUpdate ";
        $sql .=" FROM p_relax WHERE IsDeleted = 1 AND ID = '$ID' LIMIT 0, 1 ";
        	
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);
		return $result;
	}
	function stl_Get_9_Relax($start, $numOfRecord){
		$sql  =" SELECT ID, Name, Content, date_format(CreateDate,'%Y-%m-%d') AS CreateDate, LastUpdate ";
        $sql .=" FROM p_relax WHERE IsDeleted = 1 ";
		$sql .=" ORDER BY LastUpdate DESC ";
        $sql .=" LIMIT $start, $numOfRecord ";
		
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);
		return $result;
	}
	/* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_TotalImage()
	*     Description: total file of user
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_Get_Total_Relax()
    {
        $sql  =" SELECT COUNT(*) AS Total ";
        $sql .=" FROM p_relax ";
        $sql .=" WHERE IsDeleted = 1 ";
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);
        if(null == $result){
            return 0;
        }
        return $result[0]->Total;
    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_GetAllImage()
	*     Description: 
	*     Input Parameter:
	*     Output:
	*	  Update: 
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_GetAllImage($start, $numOfRecord)
    {
        $sql  =" SELECT ID, Name, Description, Price, Image, date_format(CreateDate,'%Y-%m-%d') AS CreateDate, LastUpdate,IsDeleted ";
        $sql .=" FROM " . $this->attTableName . " ";
		$sql .=" WHERE IsDeleted = 1 ";
		$sql .=" ORDER BY LastUpdate DESC ";
        $sql .=" LIMIT $start, $numOfRecord ";
		
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);

        return $result;

    }
	
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_TotalImage()
	*     Description: total file of user
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_TotalImage()
    {
        $sql  =" SELECT COUNT(*) AS Total ";
        $sql .=" FROM " . $this->attTableName . " ";
        $sql .=" WHERE IsDeleted = 1 ";
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);
        if(null == $result){
            return 0;
        }
        return $result[0]->Total;
    }
    
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: EndsWith()
	*     Description:
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function EndsWith($Haystack, $Needle){
        return strrpos($Haystack, $Needle) === strlen($Haystack)-strlen($Needle);
    }

}
?>