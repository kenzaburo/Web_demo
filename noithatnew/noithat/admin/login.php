<?php
	@session_start();
    require_once('define.inc.php');
	require_once(STL_CLASS.'STL_MainAdmin.php');
	$view = new View();
	$oDB = new STL_MainAdmin();
	$p = $_REQUEST["p"];
    switch($p){
        case 1:
			$oDB->attTableName = "p_user";
			$Data = $oDB->stl_GetUser($_REQUEST['username'], $_REQUEST['password']);
			if(null != $Data){
				$_SESSION['admin_User'] = $Data;
				echo "OK";
			}else{
				echo "NG";
			}
			break;
		default:
			if(!isset($_SESSION['admin_User'])){
				$view->showTmpl(STL_TMPL.'G_admin_login.tmpl', 'n', $data);
			}else{
				header('Location:'.'index.php');
			}
			break;
	}

?>