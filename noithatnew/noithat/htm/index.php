<?php
    @session_start();
	
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
	$data['keywords'] = "Nội thất,Noi that,huy phat,huy phát,do go,đồ gỗ,sofa go,sofa gỗ,giường gỗ,giuong go,tu tivi,tủ tivi,tu giay dep,tủ giày dép,nội thất đồ gỗ,noi that do go,do go noi that,đồ gỗ nội thất";
	
    switch($p){
		case "lien-he":/*contact 5*/
			/////////////////////*****************************////////////////////////////////////
			$arrBestseller = $oDB->stl_Get_9_Bestseller();
			$arrRelax = $oDB->stl_Get_9_Relax(0, 9);
			/////////////////////**********left_content************////////////////////////////////////
			$dataBest["allBestsellerRow"] = stl_printBestseller($arrBestseller, STL_TMPL, $view);
			$data["allBestsellerLeft"] = $view->returnTmpl( STL_TMPL.'allBestsellerLeft.tmpl', 'n', $dataBest);
			
			$dataRelax["allRelaxRow"] = stl_printRelax($arrRelax, STL_TMPL, $view);
			$data["allRelaxLeft"] = $view->returnTmpl( STL_TMPL.'allRelaxLeft.tmpl', 'n', $dataRelax);
			/////////////////////*****************************////////////////////////////////////
			$data['content'] = $view->returnTmpl( STL_TMPL.'contact.tmpl', 'n', $dataContent);
			$data['mainTitle'] = "Liên hệ nội thất đồ gỗ Huy Phát";
			$data['DescriptionPage'] ="Liên hệ nội thất đồ gỗ Huy Phát " ;
			$data['link_canonical'] ="<link rel=\"canonical\" href=\"http://noithathuyphat.com/noithat/htm/noi-that-do-go-phat-huy/lien-he/\" />";
            $view->showTmpl( STL_TMPL.'G_UserMain.tmpl', 'n', $data);
		break;
		case "thu-gian":/*relax 4*/
			$arrRelax = $oDB->stl_Get_9_Relax($start, $numOfRecord);
			$totalRecord = $oDB->stl_Get_Total_Relax();
			/////////////////////**********middle_content************////////////////////////////////////
			$dataContent["content"] = stl_printRelaxMenu($arrRelax, STL_TMPL, $view);
            $paging->assign($page,"onclick=\"hp_user.stl_PagingR([:page:])\"" ,  $totalRecord , $rowinpage );
            $dataContent['paging'] = $paging->fetch();
            $dataContent['catagoryTitle'] = "ĐỒ GỖ NỘI THẤT PHÁT HUY | GÓC GIẢI TRÍ";
			/////////////////////*****************************////////////////////////////////////
			$arrBestseller = $oDB->stl_Get_9_Bestseller();
			$arrRelax = $oDB->stl_Get_9_Relax(0, 9);
			/////////////////////**********left_content************////////////////////////////////////
			$dataBest["allBestsellerRow"] = stl_printBestseller($arrBestseller, STL_TMPL, $view);
			$data["allBestsellerLeft"] = $view->returnTmpl( STL_TMPL.'allBestsellerLeft.tmpl', 'n', $dataBest);
			
			$dataRelax["allRelaxRow"] = stl_printRelax($arrRelax, STL_TMPL, $view);
			$data["allRelaxLeft"] = $view->returnTmpl( STL_TMPL.'allRelaxLeft.tmpl', 'n', $dataRelax);
			/////////////////////*****************************////////////////////////////////////
			$data['content'] = $view->returnTmpl( STL_TMPL.'allRelaxMenu.tmpl', 'n', $dataContent);
            $data['mainTitle'] = "Thư giản với nội thất đồ gỗ Huy Phát";
			$data['DescriptionPage'] ="Thư giản với nội thất đồ gỗ Huy Phát " ;
			$data['link_canonical'] ="<link rel=\"canonical\" href=\"http://noithathuyphat.com/noithat/htm/noi-that-do-go-phat-huy/thu-gian/\" />";
			$view->showTmpl( STL_TMPL.'G_UserMain.tmpl', 'n', $data);
		break;
		case "dich-vu":/*service 3*/
			/////////////////////*****************************////////////////////////////////////
			$arrBestseller = $oDB->stl_Get_9_Bestseller();
			$arrRelax = $oDB->stl_Get_9_Relax(0, 9);
			/////////////////////**********left_content************////////////////////////////////////
			$dataBest["allBestsellerRow"] = stl_printBestseller($arrBestseller, STL_TMPL, $view);
			$data["allBestsellerLeft"] = $view->returnTmpl( STL_TMPL.'allBestsellerLeft.tmpl', 'n', $dataBest);
			
			$dataRelax["allRelaxRow"] = stl_printRelax($arrRelax, STL_TMPL, $view);
			$data["allRelaxLeft"] = $view->returnTmpl( STL_TMPL.'allRelaxLeft.tmpl', 'n', $dataRelax);
			/////////////////////*****************************////////////////////////////////////
			$data['content'] = $view->returnTmpl( STL_TMPL.'service.tmpl', 'n', $dataContent);
            $data['mainTitle'] = "Dịch vụ nội thất đồ gỗ Huy Phát ";
			$data['DescriptionPage'] ="Dịch vụ nội thất đồ gỗ Huy Phát " ;
			$data['link_canonical'] ="<link rel=\"canonical\" href=\"http://noithathuyphat.com/noithat/htm/noi-that-do-go-phat-huy/dich-vu/\" />";
			$view->showTmpl( STL_TMPL.'G_UserMain.tmpl', 'n', $data);
		break;
		case "gioi-thieu":/*introduce 2*/
			/////////////////////*****************************////////////////////////////////////
			$arrBestseller = $oDB->stl_Get_9_Bestseller();
			$arrRelax = $oDB->stl_Get_9_Relax(0, 9);
			/////////////////////**********left_content************////////////////////////////////////
			$dataBest["allBestsellerRow"] = stl_printBestseller($arrBestseller, STL_TMPL, $view);
			$data["allBestsellerLeft"] = $view->returnTmpl( STL_TMPL.'allBestsellerLeft.tmpl', 'n', $dataBest);
			
			$dataRelax["allRelaxRow"] = stl_printRelax($arrRelax, STL_TMPL, $view);
			$data["allRelaxLeft"] = $view->returnTmpl( STL_TMPL.'allRelaxLeft.tmpl', 'n', $dataRelax);
			/////////////////////*****************************////////////////////////////////////
			$data['content'] = $view->returnTmpl( STL_TMPL.'introduce.tmpl', 'n', $dataContent);
            $data['mainTitle'] = "Giới thiệu nội thất đồ gỗ Huy Phát ";
			$data['DescriptionPage'] ="Giới thiệu nội thất đồ gỗ Huy Phát " ;
			$data['link_canonical'] ="<link rel=\"canonical\" href=\"http://noithathuyphat.com/noithat/htm/noi-that-do-go-phat-huy/gioi-thieu/\" />";
			$view->showTmpl( STL_TMPL.'G_UserMain.tmpl', 'n', $data);
		break;
        case 1://paging content
            $oDB->attTableName = "p_whathot";
			$dirPhoto = STL_DEVELOP . "data/whathot/";
			$arrData = $oDB->stl_GetAllImage($start, $numOfRecord);
		    $totalRecord = $oDB->stl_TotalImage();
			$dataContent["content"] = stl_printData($dirPhoto, $arrData, STL_TMPL, $view);
            $paging->assign($page,"onclick=\"hp_user.stl_Paging([:page:])\"" ,  $totalRecord , $rowinpage );
			$arrResponse = array('content'=>$dataContent["content"],'paging'=>$paging->fetch());
			$arrResponse = json_encode($arrResponse);
			echo $arrResponse;
        break;
       
        default :
			$oDB->attTableName = "p_whathot";
			$dirPhoto = STL_DEVELOP . "data/whathot/";
			$arrData = $oDB->stl_GetAllImage($start, $numOfRecord);
		    $totalRecord = $oDB->stl_TotalImage();
        	/////////////////////**********middle_content************////////////////////////////////////
			$dataContent["content"] = stl_printData($dirPhoto, $arrData, STL_TMPL, $view);
            $paging->assign($page,"onclick=\"hp_user.stl_Paging([:page:])\"" ,  $totalRecord , $rowinpage );
            $dataContent['paging'] = $paging->fetch();
            $dataContent['catagoryTitle'] = "ĐỒ GỖ NỘI THẤT PHÁT HUY | Sản phẩm được ưa chuộng";
			/////////////////////*****************************////////////////////////////////////
			$arrBestseller = $oDB->stl_Get_9_Bestseller();
			$arrRelax = $oDB->stl_Get_9_Relax(0, 9);
			/////////////////////**********left_content************////////////////////////////////////
			$dataBest["allBestsellerRow"] = stl_printBestseller($arrBestseller, STL_TMPL, $view);
			$data["allBestsellerLeft"] = $view->returnTmpl( STL_TMPL.'allBestsellerLeft.tmpl', 'n', $dataBest);
			
			$dataRelax["allRelaxRow"] = stl_printRelax($arrRelax, STL_TMPL, $view);
			$data["allRelaxLeft"] = $view->returnTmpl( STL_TMPL.'allRelaxLeft.tmpl', 'n', $dataRelax);
			/////////////////////*****************************////////////////////////////////////
			$data['content'] = $view->returnTmpl( STL_TMPL.'all_content.tmpl', 'n', $dataContent);
            if(isset($_REQUEST["p"])){
				$random = rand() . " ";
			}else{
				$random = "";
			}
			$data['mainTitle'] = "Nội thất đồ gỗ Huy Phát " .$random;
			$data['DescriptionPage'] ="Phát Huy, đồ gỗ " .$random;
			$data['link_canonical'] ="<link rel=\"canonical\" href=\"http://noithathuyphat.com/noithat/htm/\" />";
			$view->showTmpl( STL_TMPL.'G_UserMain.tmpl', 'n', $data);
        break;
    }
	
    function stl_printData($dirPhoto, $arrData, $MOD_TMPL, $view){
        global $dataRow;
        $content = "";
        foreach($arrData as $row)
		{
            $dataRow["ID"]          = $row->ID;
            $dataRow["Name"]        = htmlspecialchars($row->Name, ENT_QUOTES);
            $dataRow["Description"]        = htmlspecialchars($row->Description, ENT_QUOTES);
            $dataRow["Price"]       = htmlspecialchars($row->Price, ENT_QUOTES);
            $dataRow["Image"]  = $row->Image == "" ? "default.gif":$row->Image;
			$dataRow["Path"]   = $dirPhoto;
			$dataRow["alt_text"]   = "nội thất đồ gỗ phát huy, thiết kế nội thất | Sản phẩm đồ gỗ " .$dataRow["Name"]." ";
            $content .= $view->returnTmpl($MOD_TMPL."content_detail.tmpl", 'n', $dataRow);
        }
        return $content;
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
		$i=1;
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
   
?>