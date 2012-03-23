<?php    @session_start();	    require_once('define.inc.php');	require_once(STL_CLASS.'STL_MainAdmin.php');	require_once (STL_CLASS . 'stl_UploadFile.php');	$view = new View();    $oDB = new STL_MainAdmin();	$oDB->attTableName = "p_relax ";	/*paging*/    $numOfRecord = 10;	if (isset($_REQUEST['Page'])){		$page = $_REQUEST['Page'];	}else{		$page = 1;	}    $start  = ($page - 1) * $numOfRecord;    $paging                     = new paging();    $rowinpage                  = $numOfRecord;    $paging->align_links_count  = 4;	/*end paging*/    $p = $_POST["p"];    switch($p){				case 7:					$dataRow["ID"]        = "";            $dataRow["Name"]       = "";            $dataRow["Content"]     = "";			echo $view->returnTmpl(STL_TMPL."admin_relax_updateinfo.tmpl", 'n', $dataRow);		break;    	case 6:			$ID = $_POST['ID'];			$row = $oDB->stl_GetRelaxInfo($ID);            $dataRow["ID"]        = $row->ID;            $dataRow["Name"]       = htmlspecialchars($row->Name, ENT_QUOTES);            $dataRow["Content"]     = htmlspecialchars($row->Content, ENT_QUOTES);			echo $view->returnTmpl(STL_TMPL."admin_relax_updateinfo.tmpl", 'n', $dataRow);		break;        case 5:            $arrinfo = stripslashes($_POST['arrInfo']);            $result = $oDB->stl_addNewRelax($arrinfo);            if("NG" == $result){                echo 1;            }else if("OK" == $result){                echo 2;            }else{				echo 3;			}        break;        case 4:			            $arrInfo = stripslashes($_POST['arrInfo']);            $arrData = $oDB->stl_GetRelaxS($arrInfo, $start, $numOfRecord);            $totalRecord = $oDB->stl_TotalRelaxS($arrInfo);            $content = stl_printData($arrData, STL_TMPL, $view, $start);            if("" == $content)            $content = "<div style='text-align:center;'>Không có kết quả nào được tìm thấy!</div>";            $paging->assign($page,"onclick=\"Orelax.stl_PagingS([:page:])\"" ,  $totalRecord , $rowinpage );            $arrResponse = array('content'=>$content,'paging'=>$paging->fetch());			$arrResponse = json_encode($arrResponse);                        echo $arrResponse;        break;        case 3:             $ID         = $_POST['ID'];			$IsDeleted  = $_POST['IsDeleted'];            $result = $oDB->stl_DeleteRelax($ID, $IsDeleted);            echo $result;        break;        case 2:            $arrData = $oDB->stl_GetAllRelax($start, $numOfRecord);            $totalRecord = $oDB->stl_TotalRelax();            $content = stl_printData($arrData, STL_TMPL, $view, $start);            $paging->assign($page,"onclick=\"Orelax.stl_Paging([:page:])\"" ,  $totalRecord , $rowinpage );            $arrResponse = array('content'=>$content,'paging'=>$paging->fetch());			$arrResponse = json_encode($arrResponse);                       echo $arrResponse;        break;        case 1:            $ID = $_POST['ID'];			            $arrInfo =  stripslashes($_POST['arrInfo']);            $result = $oDB->stl_UpdateRelax($ID, $arrInfo);            echo $result;        break;               default :			$arrData = $oDB->stl_GetAllRelax($start, $numOfRecord);			            $totalRecord = $oDB->stl_TotalRelax();        	$data["content"] = stl_printData($arrData, STL_TMPL, $view, $start);            $paging->assign($page,"onclick=\"Orelax.stl_Paging([:page:])\"" ,  $totalRecord , $rowinpage );            $data['paging'] = $paging->fetch();			$data['UserName'] = $_SESSION['admin_User'];			$view->showTmpl( STL_TMPL.'admin_relax.tmpl', 'n', $data);        break;    }	    function stl_printData( $arrData, $MOD_TMPL, $view, $start=1){        $i =  $start + 1; if($i<1) $i =1;        $content = "";	        foreach($arrData as $row)		{            $dataRow["num"]         = $i%2;            $dataRow["STT"]         = $i; $i++;            $dataRow["ID"]          = $row->ID;            $dataRow["Name"]        = htmlspecialchars($row->Name, ENT_QUOTES);            $dataRow["Content"]        = htmlspecialchars($row->Content, ENT_QUOTES);            $dataRow["CreateDate"]  = $row->CreateDate;            $dataRow["LastUpdate"]  = $row->LastUpdate;            $dataRow["IsDeleted"]  = $row->IsDeleted;			if(2 == $row->IsDeleted) $dataRow['titleD'] = "Phục hồi"; else $dataRow['titleD'] = "Xoá";			if(2 == $row->IsDeleted) $dataRow['displayE'] = "return; "; else $dataRow['displayE'] = "";			            $content .= $view->returnTmpl($MOD_TMPL."g_admin_relax_row.tmpl", 'n', $dataRow);        }        return $content;    }   ?>