<?php
	require_once('BaseDir.inc.php');
	require_once("Env.inc.php");
	require_once("Log.class.php");
	require_once("htmltemplate.inc");	

	class View extends Log {
	

	    function __construct() {

	    }


	    public function showTmpl($tmpl, $type, $data){
			
	    	$temp  = new htmltemplate();
			if($type=="n"){ 
		    	$header = STL_TMPL.'empty.tmpl';
	    		$footer = STL_TMPL.'empty.tmpl';
			} else {
		    	$header = STL_TMPL.'header.tmpl';
	    		$footer = STL_TMPL.'footer.tmpl';
			}
	    	
	    	if(file_exists($header) && file_exists($footer)){
				$temp->t_include($header, $data);
	    		$temp->t_include($tmpl, $data);
	    		$temp->t_include($footer, $data);
	    	}else{
	    		parent::fatal("There is no template : ".$tmpl);
	    		$temp->t_include($header, $data);
	    		$temp->t_include(STL_TMPL.'/'.'err_nofile.tmpl', $data);
	    		$temp->t_include($footer, $data);
	    		exit();
	    	}
	    }
      
        public function returnTmpl($tmpl, $type, $data){
	    	
	    	$temp  = new htmltemplate();
			if($type=="n"){ 
		    	$header = STL_TMPL.'empty.tmpl';
	    		$footer = STL_TMPL.'empty.tmpl';
			} else {
		    	$header = STL_TMPL.'header.tmpl';
	    		$footer = STL_TMPL.'footer.tmpl';
			}
	    	
            $returnTemplate = "";
	    	if(file_exists($header) && file_exists($footer)){
				
				$returnTemplate = $temp->stl_include($header, $data);
	    		$returnTemplate .= $temp->stl_include($tmpl, $data);
	    		$returnTemplate .= $temp->stl_include($footer, $data);
                return $returnTemplate;
                
	    	}else{
	    		parent::fatal("There is no template : ".$tmpl);
	    		$returnTemplate = $temp->stl_include($header, $data);
	    		$returnTemplate .= $temp->stl_include(STL_TMPL.'/'.'err_nofile.tmpl', $data);
	    		$returnTemplate .= $temp->stl_include($footer, $data);
                return $returnTemplate;
	    		exit();
	    	}            
	    }
	}
?>
