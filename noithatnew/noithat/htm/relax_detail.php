<?php
    require_once('define.inc.php');
	require_once(STL_CLASS.'STL_MainUser.php');
	$view = new View();
	$oDB = new STL_MainUser();
	/*paging*/
    $numOfRecord = 9;
	if (isset($_REQUEST['Page'])){
		$page = $_REQUEST['Page'];
	}else{
		$page = 1;
	}
    $start  = ($page - 1) * $numOfRecord;
    $paging                     = new paging();
    $rowinpage                  = $numOfRecord;
    $paging->align_links_count  = 4;
	/*end paging*/
    $data['STL_DEVELOP'] = STL_DEVELOP;
    $dataRow['STL_DEVELOP'] = STL_DEVELOP;
    $p = $_REQUEST["p"];
	$data['keywords'] = "Nội thất,Noi that,phat huy, phát huy,do go,đồ gỗ,Thiết kế nội thất, thiet ke noi that,do go noi that, đồ gỗ nội thất";
    switch($p){
		case 1:/*paging*/
			
			$arrRelax = $oDB->stl_Get_9_Relax($start, $numOfRecord);
			$totalRecord = $oDB->stl_Get_Total_Relax();
			$dataContent["content"] = stl_printRelaxMenu($arrRelax, STL_TMPL, $view);
            $paging->assign($page,"onclick=\"hp_user.stl_PagingR([:page:])\"" ,  $totalRecord , $rowinpage );
			$arrResponse = array('content'=>$dataContent["content"],'paging'=>$paging->fetch());
			$arrResponse = json_encode($arrResponse);
			echo $arrResponse;
		break;
        default :
			$ID = $_REQUEST["ID"];
			$STT = $_REQUEST["STT"];
			$arrRelax = $oDB->stl_Get_Info_Relax($ID);
			$dataContent["content"] = stl_printRelaxDetail($STT, $arrRelax, STL_TMPL, $view);
            $dataContent['paging'] = "";
            $dataContent['catagoryTitle'] = "ĐỒ GỖ NỘI THẤT PHÁT HUY | GÓC GIẢI TRÍ | ".htmlspecialchars($arrRelax[0]->Name, ENT_QUOTES);
			$data['DescriptionPage'] =$arrRelax[0]->Name . ", " . rand() . ", relax noi that do go huy phat";
			$data['content'] = $view->returnTmpl( STL_TMPL.'allRelaxMenu.tmpl', 'n', $dataContent);
			$arrBestseller = $oDB->stl_Get_9_Bestseller();
			$arrRelax = $oDB->stl_Get_9_Relax(0, 9);
			$dataBest["allBestsellerRow"] = stl_printBestseller($arrBestseller, STL_TMPL, $view);
			$data["allBestsellerLeft"] = $view->returnTmpl( STL_TMPL.'allBestsellerLeft.tmpl', 'n', $dataBest);
			$dataRelax["allRelaxRow"] = stl_printRelax($arrRelax, STL_TMPL, $view);
			$data["allRelaxLeft"] = $view->returnTmpl( STL_TMPL.'allRelaxLeft.tmpl', 'n', $dataRelax);
			$data['mainTitle'] = "Noi That Do Go Phat Huy, " . rand() . " " . $dataContent['catagoryTitle'];
			
			$data['link_canonical'] ="<link rel=\"canonical\" href=\"http://noithathuyphat.com/noithat/htm/do-go-noi-that-huy-phat/$ID/$STT/\" />";
			$view->showTmpl( STL_TMPL.'G_UserMain.tmpl', 'n', $data);
        break;
    }
	
	function stl_printBestseller($arrData, $MOD_TMPL, $view){
        global $dataRow;
        $content = "";
        foreach($arrData as $row)
		{
            $dataRow["ID"]          = $row->ID;
            $dataRow["Name"]        = htmlspecialchars($row->Name, ENT_QUOTES);
            $dataRow["Description"]        = htmlspecialchars($row->Description, ENT_QUOTES);
            $dataRow["Price"]       = htmlspecialchars($row->Price, ENT_QUOTES);
            $dataRow["Image"]  = $row->Image == "" ? "default.gif":$row->Image;
            $content .= $view->returnTmpl($MOD_TMPL."bestsellerRowLeft.tmpl", 'n', $dataRow);
        }
        return $content;
    }
	function stl_printRelax($arrData, $MOD_TMPL, $view){
        global $dataRow;
        $content = "";
		$i = 1;
        foreach($arrData as $row)
		{
            $dataRow["STT"]          = $i; $i++;
			$dataRow["ID"]          = $row->ID;
            $dataRow["titleRelax"]        = htmlspecialchars($row->Name, ENT_QUOTES);
            $content .= $view->returnTmpl($MOD_TMPL."relaxRowLeft.tmpl", 'n', $dataRow);
        }
        return $content;
    }
	function stl_printRelaxMenu($arrData, $MOD_TMPL, $view){
        global $dataRow;
        $result = "";
		$i = 1;
        foreach($arrData as $row)
		{
            $dataRow["STT"]          = $i; $i++;
			$dataRow["ID"]          = $row->ID;
            $dataRow["titleRelax"]        = htmlspecialchars($row->Name, ENT_QUOTES);
			$content = strip_tags($row->Content);
			if(mb_strlen($content, 'utf-8') > 150){
				$shortContent = mb_substr($content, 0, 147, 'utf-8')."...";
			}else{
				$shortContent = $content;
			}
            $dataRow["shortRelax"]        = htmlspecialchars($shortContent, ENT_QUOTES);
            $result .= $view->returnTmpl($MOD_TMPL."relaxMenuRow.tmpl", 'n', $dataRow);
        }
        return $result;
    }
	function stl_printRelaxDetail($STT, $arrData, $MOD_TMPL, $view){
        global $dataRow;
        $result = "";
        foreach($arrData as $row)
		{
            $dataRow["STT"]          = $STT;
			$dataRow["ID"]          = $row->ID;
            $dataRow["titleRelax"]        = htmlspecialchars($row->Name, ENT_QUOTES);
		    $dataRow["shortRelax"]        = $row->Content;
            $result .= $view->returnTmpl($MOD_TMPL."relaxMenuDetail.tmpl", 'n', $dataRow);
        }
        return $result;
    }
   
?>