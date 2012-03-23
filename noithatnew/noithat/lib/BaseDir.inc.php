<?php
	error_reporting(0); 
    define("STL_DEVELOP",         'http://localhost/noithatnew/noithat/'); 

	$pathinfo = pathinfo(dirname(__FILE__));  
	define("STL_HOME",            $pathinfo['dirname'] . '/');
	
    define("STL_LIB",             STL_HOME . 'lib/');         
    define("STL_CLASS",           STL_HOME  . 'class/');      
    define("STL_DATA",            STL_DEVELOP . 'data/');     
    define("STL_CSS",            STL_DEVELOP . 'css/');
    define("STL_IMG",            STL_DEVELOP . 'img/');
    define("STL_HTM",            STL_DEVELOP . 'htm/');
    define("STL_TMPL",            STL_HOME . 'tmpl/');
    define("STL_JS",            STL_DEVELOP . 'js/');
    define("STL_DATA_IMG",        STL_DATA . 'img/');  
    define("STL_DATA_IMG_TMP",    STL_DATA . 'temp/');
 
?>