<?php
/*
* Stagly Soft
* Author: phuong.ho
* Description:
* Created on: 10:28 AM 11/12/2009
* Last Updated:
* Version: 1.0
*/

class STL_MainAdmin
{
    var $attTableName;
   
    function STL_MainAdmin()
    {
		require_once('../lib/'.'BaseDir.inc.php');
		require_once(STL_LIB.'DBCommon.class.php');
    }
   
     /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_addNewP()
	*     Description: 
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_addNewP($arrInfo){
		$arrInfo = json_decode($arrInfo);
        $oDB = new DBCommon();
		$conn 	= $oDB->stl_openConn();
		$sql  = " INSERT INTO " . $this->attTableName . "(Name, Description, Price, Image, CreateDate ) ";
        $sql .= " VALUES ( ";
        $sql .= " 	'" .addslashes($arrInfo[0]). "', ";
        $sql .= " 	'" .addslashes($arrInfo[1]). "', ";
        $sql .= " 	'" .addslashes($arrInfo[2]). "', ";
        $sql .= " 	'" .addslashes($arrInfo[3]). "', ";
        $sql .= " 	now() ";
        $sql .= ") ";
		
        $result=$oDB->stl_db_change($sql,$conn);
		$oDB->stl_closeConn($conn);
        return $result;
    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_GetAllRecord()
	*     Description: 
	*     Input Parameter:
	*     Output:
	*	  Update: 
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_GetAllRecord($start, $numOfRecord)
    {
        $sql  =" SELECT ID, Name, Description, Price, Image, date_format(CreateDate,'%Y-%m-%d') AS CreateDate, LastUpdate,IsDeleted ";
        $sql .=" FROM " . $this->attTableName . " ";
		$sql .=" ORDER BY LastUpdate DESC ";
        $sql .=" LIMIT $start, $numOfRecord ";
		
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);

        return $result;

    }
	/* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_GetRecordInfo()
	*     Description: 
	*     Input Parameter:
	*     Output:
	*	  Update: 
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_GetRecordInfo($ID)
    {
        $sql  =" SELECT ID, Name, Description, Price, Image, date_format(CreateDate,'%Y-%m-%d') AS CreateDate, LastUpdate,IsDeleted ";
        $sql .=" FROM " . $this->attTableName . " WHERE ID = '$ID' ";
        $sql .=" LIMIT 0, 1 ";
		
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);

        return $result[0];

    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_TotalRecord()
	*     Description: total file of user
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_TotalRecord()
    {
        $sql  =" SELECT COUNT(*) AS Total ";
        $sql .=" FROM " . $this->attTableName . " ";
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
	*     Funtion Name: stl_UpdateRecord()
	*     Description:
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_UpdateRecord($ID, $arrInfo){
        $arrInfo = json_decode($arrInfo);
		$sql  =" UPDATE " . $this->attTableName . " SET ";
        $sql .=" Name = '".addslashes($arrInfo[0])."', ";
        $sql .=" Description = '".addslashes($arrInfo[1])."', ";
        $sql .=" Price = '".addslashes($arrInfo[2])."', ";
        $sql .=" Image = '".addslashes($arrInfo[3])."' ";
        $sql .=" WHERE ID = '$ID' ";

        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_change($sql, $conn);
        $oDB->stl_closeConn($conn);
        return $result;
    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_DeleteF()
	*     Description: delete file
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_DeleteRecord($ID, $IsDeleted){
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
		if(1 == $IsDeleted){
            $sql = " UPDATE " . $this->attTableName . " SET IsDeleted=2 WHERE ID = '$ID' ";
        }else{
            $sql = " UPDATE " . $this->attTableName . " SET IsDeleted=1 WHERE ID = '$ID' ";
        }
        
        $result	= $oDB->stl_db_change($sql, $conn);
        $oDB->stl_closeConn($conn);
        return $result;
    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_GetRecordS()
	*     Description:
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_GetRecordS($str, $start, $numOfRecord){

        $arrInfo = json_decode($str);
        $condition = "";
        if("" != $arrInfo[0]) $condition = " Name LIKE '%". addslashes($arrInfo[0]) ."%' AND";
        if("" != $arrInfo[1]) $condition .= " Price <= '". $arrInfo[1] ."' AND";
		if("" != $arrInfo[2]) $condition .= " CreateDate >= '". $arrInfo[2] ."' AND";
		if("" != $arrInfo[3]) $condition .= " CreateDate <= DATE_ADD(\"".$arrInfo[3]."\", INTERVAL 1 DAY)". " AND";
        
        if($this->EndsWith($condition, "AND")){
            $condition = substr($condition, 0, strlen($condition)-3);
        }
		$sql  =" SELECT ID, Name, Description, Price, Image, date_format(CreateDate,'%Y-%m-%d') AS CreateDate, LastUpdate,IsDeleted ";
        $sql .=" FROM " . $this->attTableName . " ";
        $sql .=" WHERE ID > 0 ";
        if($condition != "")
        $sql .= " AND $condition ";
		$sql .=" ORDER BY LastUpdate DESC ";
        $sql .=" LIMIT $start, $numOfRecord ";

        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);
        return $result;
    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_TotalRecordS()
	*     Description: paging
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_TotalRecordS($str){
        
        $arrInfo = json_decode($str);
        $condition = "";
        if("" != $arrInfo[0]) $condition = " Name LIKE '%". addslashes($arrInfo[0]) ."%' AND";
        if("" != $arrInfo[1]) $condition .= " Price <= '". $arrInfo[1] ."' AND";
		if("" != $arrInfo[2]) $condition .= " CreateDate >= '". $arrInfo[2] ."' AND";
		if("" != $arrInfo[3]) $condition .= " CreateDate <= DATE_ADD(\"".$arrInfo[3]."\", INTERVAL 1 DAY)". " AND";
        
        if($this->EndsWith($condition, "AND")){
            $condition = substr($condition, 0, strlen($condition)-3);
        }
        $sql  =" SELECT COUNT(*) AS Total ";
        $sql .=" FROM " . $this->attTableName . " ";
        $sql .=" WHERE ID > 0 ";
        if($condition != "")
        $sql .= " AND $condition ";
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);
        if(null == $result) return 0;
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
	  /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_addNewP()
	*     Description: 
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_addNewRelax($arrInfo){
		$arrInfo = json_decode($arrInfo);
        $oDB = new DBCommon();
		$conn 	= $oDB->stl_openConn();
		$sql  = " INSERT INTO " . $this->attTableName . "(Name, Content, CreateDate ) ";
        $sql .= " VALUES ( ";
        $sql .= " 	'" .addslashes($arrInfo[0]). "', ";
        $sql .= " 	'" .addslashes($arrInfo[1]). "', ";
        $sql .= " 	now() ";
        $sql .= ") ";
		
        $result=$oDB->stl_db_change($sql,$conn);
		$oDB->stl_closeConn($conn);
        return $result;
    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_GetAllRecord()
	*     Description: 
	*     Input Parameter:
	*     Output:
	*	  Update: 
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_GetAllRelax($start, $numOfRecord)
    {
        $sql  =" SELECT ID, Name, Content, date_format(CreateDate,'%Y-%m-%d') AS CreateDate, LastUpdate,IsDeleted ";
        $sql .=" FROM " . $this->attTableName . " ";
		$sql .=" ORDER BY LastUpdate DESC ";
        $sql .=" LIMIT $start, $numOfRecord ";
		
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);

        return $result;

    }
	/* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_GetRelaxInfo()
	*     Description: 
	*     Input Parameter:
	*     Output:
	*	  Update: 
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_GetRelaxInfo($ID)
    {
        $sql  =" SELECT ID, Name, Content, date_format(CreateDate,'%Y-%m-%d') AS CreateDate, LastUpdate,IsDeleted ";
        $sql .=" FROM " . $this->attTableName . " WHERE ID = '$ID' ";
        $sql .=" LIMIT 0, 1 ";
		
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);

        return $result[0];

    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_TotalRelax()
	*     Description: total file of user
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_TotalRelax()
    {
        $sql  =" SELECT COUNT(*) AS Total ";
        $sql .=" FROM " . $this->attTableName . " ";
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
	*     Funtion Name: stl_UpdateRelax()
	*     Description:
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_UpdateRelax($ID, $arrInfo){
        $arrInfo = json_decode($arrInfo);
		$sql  =" UPDATE " . $this->attTableName . " SET ";
        $sql .=" Name = '".addslashes($arrInfo[0])."', ";
        $sql .=" Content = '".addslashes($arrInfo[1])."' ";
        $sql .=" WHERE ID = '$ID' ";

        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_change($sql, $conn);
        $oDB->stl_closeConn($conn);
        return $result;
    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_DeleteF()
	*     Description: delete file
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_DeleteRelax($ID, $IsDeleted){
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
		if(1 == $IsDeleted){
            $sql = " UPDATE " . $this->attTableName . " SET IsDeleted=2 WHERE ID = '$ID' ";
        }else{
            $sql = " UPDATE " . $this->attTableName . " SET IsDeleted=1 WHERE ID = '$ID' ";
        }
        
        $result	= $oDB->stl_db_change($sql, $conn);
        $oDB->stl_closeConn($conn);
        return $result;
    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_GetRelaxS()
	*     Description:
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_GetRelaxS($str, $start, $numOfRecord){

        $arrInfo = json_decode($str);
        $condition = "";
        if("" != $arrInfo[0]) $condition = " Name LIKE '%". addslashes($arrInfo[0]) ."%' AND";
        if("" != $arrInfo[1]) $condition .= " Content LIKE '%". addslashes($arrInfo[1]) ."%' AND";
		if("" != $arrInfo[2]) $condition .= " CreateDate >= '". $arrInfo[2] ."' AND";
		if("" != $arrInfo[3]) $condition .= " CreateDate <= DATE_ADD(\"".$arrInfo[3]."\", INTERVAL 1 DAY)". " AND";
        
        if($this->EndsWith($condition, "AND")){
            $condition = substr($condition, 0, strlen($condition)-3);
        }
		$sql  =" SELECT ID, Name, Content, date_format(CreateDate,'%Y-%m-%d') AS CreateDate, LastUpdate,IsDeleted ";
        $sql .=" FROM " . $this->attTableName . " ";
        $sql .=" WHERE ID > 0 ";
        if($condition != "")
        $sql .= " AND $condition ";
		$sql .=" ORDER BY LastUpdate DESC ";
        $sql .=" LIMIT $start, $numOfRecord ";

        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);
        return $result;
    }
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_TotalRelaxS()
	*     Description: paging
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    function stl_TotalRelaxS($str){
        
        $arrInfo = json_decode($str);
        $condition = "";
       if("" != $arrInfo[0]) $condition = " Name LIKE '%". addslashes($arrInfo[0]) ."%' AND";
        if("" != $arrInfo[1]) $condition .= " Content LIKE '%". addslashes($arrInfo[1]) ."%' AND";
		if("" != $arrInfo[2]) $condition .= " CreateDate >= '". $arrInfo[2] ."' AND";
		if("" != $arrInfo[3]) $condition .= " CreateDate <= DATE_ADD(\"".$arrInfo[3]."\", INTERVAL 1 DAY)". " AND";
        
        if($this->EndsWith($condition, "AND")){
            $condition = substr($condition, 0, strlen($condition)-3);
        }
        $sql  =" SELECT COUNT(*) AS Total ";
        $sql .=" FROM " . $this->attTableName . " ";
        $sql .=" WHERE ID > 0 ";
        if($condition != "")
        $sql .= " AND $condition ";
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);
        if(null == $result) return 0;
        return $result[0]->Total;
    }
	function stl_GetUser($username, $password){
		$sql  =" SELECT UserName ";
        $sql .=" FROM " . $this->attTableName . " WHERE UserName like '%". addslashes($username). "%' AND PassWord like '%". addslashes($password) . "%' AND IsDeleted=1 ";
        $sql .=" LIMIT 0, 1 ";
		
        $oDB = new DBCommon();
        $conn 	= $oDB->stl_openConn();
        $result	= $oDB->stl_db_select($sql, $conn);
        $oDB->stl_closeConn($conn);

        return $result[0]->UserName;
	}
}
?>