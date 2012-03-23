<?php
    @session_start();
	
    require_once('define.inc.php');
	require_once(STL_CLASS.'STL_MainUser.php');
	$view = new View();
	$oDB = new STL_MainUser();
	if(isset($_REQUEST['pTable'])){
		$_SESSION['pTable'] = $_REQUEST['pTable'];
		$pTable = $_REQUEST['pTable'];
	}else{
		$pTable = $_SESSION['pTable'];
	}
    $data['STL_DEVELOP'] = STL_DEVELOP;
    $dataRow['STL_DEVELOP'] = STL_DEVELOP;
	switch($pTable){
		case "ban-an": 
			$oDB->attTableName = "p_table";
			$dirPhoto = "table";
			$title = "Ban go, bàn gỗ";
		break;
		case "ghe-sofa": /*sofa 2*/
			$oDB->attTableName = "p_sofa";
			$dirPhoto = "sofa";
			$title = "ghe sofa, sofa gỗ";
		break;
		case "marquetry": /*marquetry 3*/
			$oDB->attTableName = "p_marquetry";
			$dirPhoto = "marquetry";
			$title = "marquetry";
		break;
		case "cua-di": /*door 4*/
			$oDB->attTableName = "p_door";
			$dirPhoto = "door";
			$title = "cua go, cửa gỗ";
		break;
		case "ghe-ngoi": /*chair 5*/
			$oDB->attTableName = "p_chair";
			$dirPhoto = "chair";
			$title = "ghe ngoi, ghế ngồi";
		break;
		case "tu-tivi": /*cabinet_television 6*/
			$oDB->attTableName = "p_cabinet_television";
			$dirPhoto = "cabinet_television";
			$title = "tu tivi, tủ gỗ tivi";
		break;
		case "tu-giay-dep": /*cabinet_shoe 7*/
			$oDB->attTableName = "p_cabinet_shoe";
			$dirPhoto = "cabinet_shoe";
			$title = "tu giay dep, tủ gỗ giày dép";
		break;
		case "tu-bep": /*cabinet_cooker 8*/
			$oDB->attTableName = "p_cabinet_cooker";
			$dirPhoto = "cabinet_cooker";
			$title = "tu bep, tủ bếp";
		break;
		case "tu-ao": /*cabinet_coat 9*/
			$oDB->attTableName = "p_cabinet_coat";
			$dirPhoto = "cabinet_coat";
			$title = "tu ao, tủ gỗ áo";
		break;
		case "ke-sach": /*bookshelf 10*/
			$oDB->attTableName = "p_bookself";
			$dirPhoto = "bookshelf";
			$title = "ke sach, kệ sách";
		break;
		case "giuong-ngu": /*bed 11*/
			$oDB->attTableName = "p_bed";
			$dirPhoto = "bed";
			$title = "giuong ngu, giường ngủ";
		break;
		default:
			$oDB->attTableName = "p_table";
			$dirPhoto = "table";
			$title =   rand() . ", tu tivi, tủ tivi, tu giay dep, tủ giày dép, ban an, bàn ăn ";
			
		break;
	}
	$data['keywords'] = "Nội thất,Noi that,huy phat, huy phát,$title,do go,đồ gỗ,Thiet ke noi that, thiết kế nội thất,do go noi that, đồ gỗ nội thất";
	
	$data['link_canonical'] ="<link rel=\"canonical\" href=\"http://noithathuyphat.com/noithat/htm/noi-that-do-go-huy-phat/$pTable/\" />";
	define ('DIR_PHOTO',STL_DEVELOP . "data/".$dirPhoto."/");
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
    $p = $_REQUEST["p"];
	
    switch($p){
        case 1:/*paging content*/
			$dirPhoto = DIR_PHOTO;
			$arrData = $oDB->stl_GetAllImage($start, $numOfRecord);
		    $totalRecord = $oDB->stl_TotalImage();
			$dataContent["content"] = stl_printData($dirPhoto, $arrData, STL_TMPL, $view);
            $paging->assign($page,"onclick=\"hp_user.stl_Paging([:page:])\"" ,  $totalRecord , $rowinpage );
			$arrResponse = array('content'=>$dataContent["content"],'paging'=>$paging->fetch());
			$arrResponse = json_encode($arrResponse);
			echo $arrResponse;
        break;
       
        default :
			$dirPhoto = DIR_PHOTO;
			$arrData = $oDB->stl_GetAllImage($start, $numOfRecord);
		    $totalRecord = $oDB->stl_TotalImage();
			$dataContent["content"] = stl_printData($dirPhoto, $arrData, STL_TMPL, $view);
            $paging->assign($page,"onclick=\"hp_user.stl_Paging([:page:])\"" ,  $totalRecord , $rowinpage );
            $dataContent['paging'] = $paging->fetch();
            $dataContent['catagoryTitle'] = "ĐỒ GỖ NỘI THẤT PHÁT HUY | Sản Phẩm Nội thất | " . $title;
			$data['content'] = $view->returnTmpl( STL_TMPL.'all_content.tmpl', 'n', $dataContent);
			$arrBestseller = $oDB->stl_Get_9_Bestseller();
			$arrRelax = $oDB->stl_Get_9_Relax(0, 9);

			$dataBest["allBestsellerRow"] = stl_printBestseller($arrBestseller, STL_TMPL, $view);
			$data["allBestsellerLeft"] = $view->returnTmpl( STL_TMPL.'allBestsellerLeft.tmpl', 'n', $dataBest);
			
			$dataRelax["allRelaxRow"] = stl_printRelax($arrRelax, STL_TMPL, $view);
			$data["allRelaxLeft"] = $view->returnTmpl( STL_TMPL.'allRelaxLeft.tmpl', 'n', $dataRelax);
			$data['mainTitle'] = "Huy Phát nội thất đồ gỗ | ".$title;
			$data['DescriptionPage'] ="Phat Huy " .$title;
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
            $dataRow["Price"]       = $row->Price > 4000000 ? $row->Price : 4000000;
            $dataRow["Image"]  = $row->Image == "" ? "default.gif":$row->Image;
			$dataRow["Path"]   = $dirPhoto;
			$dataRow["alt_text"]   = "nội thất đồ gỗ phát huy, thiết kế nội thất | Sản phẩm đồ gỗ  " .$dataRow["Name"]."";
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
   
?>