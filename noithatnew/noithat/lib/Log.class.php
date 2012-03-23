<?php
/**
 * 
 * @package	Log
 * @access		public
 * @author		r_futamura
 * @create		2006/07/26 1:29:08
 * @version	
 * 
 * @param		
 * @return		
 */

/**
 * Log variables
 */
define( "STL_LOG_DEBUG", 1 );   /* Debug*/
define( "STL_LOG_ERROR", 2 );   /* Error*/
define( "STL_LOG_FATAL", 4 );   /* Critical Error*/
define( "STL_LOG_SQL",   8 );   /* SQL*/

require_once('BaseDir.inc.php');
require_once('Env.inc.php');

class Log {

    /**
	 * put()
	 * ログ出力メソッド
     * STL_LOG_LEVEL_EACHとSTL_LOG_LEVEL_DEBUGの内容を参照して該当ログを出力する
     * 
	 * @access	public
	 * @author	r_futamura
	 * @create	2006/07/26 12:35:57
	 * @version	1.0
	 * 
	 * @param	
	 * @return	
	 */
    public function put($loglevel,$str,$isHalt=false,$classname,$funcname,$lineno) {
        switch ($loglevel) {
            case STL_LOG_DEBUG:
                    $logfile = STL_LOGFILE_DEBUG;
                    $level   = "DEBUG";
                    break;
            case STL_LOG_ERROR:
                    $logfile = STL_LOGFILE_ERROR;
                    $level   = "ERROR";
                    break;
            case STL_LOG_FATAL:
                    $logfile = STL_LOGFILE_FATAL;
                    $level   = "FATAL";
                    break;
            case STL_LOG_SQL:
                    $logfile = STL_LOGFILE_SQL;
                    $level   = "SQL";
                    break;
        }

        list($usec, $sec) = explode(" ", microtime());
        $usec_str = substr($usec, 1);
        $msg = date("Y/m/d H:i:s");
        $msg .= "[$level]".$usec_str." [".$_SERVER['REQUEST_URI']."] $classname::$funcname() [$lineno] $str\n";

        /* Write out reperate log file*/
/*
        if (OS_CHARSET!=STL_CHARSET) {
            $msg = mb_convert_encoding($msg,OS_CHARSET,STL_CHARSET);
        }
*/
        if ($loglevel!=STL_LOG_DEBUG ||
            ($loglevel==STL_LOG_DEBUG && defined('STL_LOG_DEBUG_FLG') && STL_LOG_DEBUG_FLG==true)) {
            error_log($msg,3,$logfile);
        }

        /* Output to debug log*/
        if (defined('STL_LOG_DEBUG_FLG') && STL_LOG_DEBUG_FLG==true) {
            if ($loglevel==STL_LOG_ERROR && ((STL_LOG_LEVEL_EACH & STL_LOG_ERROR)==STL_LOG_ERROR)) {
                error_log($msg,3,STL_LOGFILE_DEBUG);
            }
            if ($loglevel==STL_LOG_FATAL && ((STL_LOG_LEVEL_EACH & STL_LOG_FATAL)==STL_LOG_FATAL)) {
                error_log($msg,3,STL_LOGFILE_DEBUG);
            }
            if ($loglevel==STL_LOG_SQL && ((STL_LOG_LEVEL_EACH & STL_LOG_SQL)==STL_LOG_SQL)) {
                error_log($msg,3,STL_LOGFILE_DEBUG);
            }
        }

        if ($isHalt) {
            exit();
        }
    }

    /**
     * log()
     * output log to a file with file name
     */
    public function logput($logfile,$str,$classname=__CLASS__,$funcname=__FUNCTION__,$lineno=__LINE__)
    {
        list($usec, $sec) = explode(" ", microtime());
        $usec_str = substr($usec, 1);
        $msg = date("Y/m/d H:i:s");
        $msg .= $usec_str." [".$_SERVER['REQUEST_URI']."] $classname::$funcname() [$lineno] $str\n";

        error_log($msg,3,$logfile);

        if ($isHalt) {
            exit();
        }
    }


    /**
	 * debug()
	 * Output debug log
     * 
	 * @access	public
	 * @author	r_futamura
	 * @create	2006/07/26 16:53:31
	 * @version	1.0
	 * 
	 * @param	
	 * @return	
	 */
	public function debug($str,$classname=__CLASS__,$funcname=__FUNCTION__,$lineno=__LINE__)
	{
		self::put(STL_LOG_DEBUG,$str,false,$classname,$funcname,$lineno);
	}

    /**
	 * error()
	 * エラーログを出力
     *
	 * @access	public
	 * @author	r_futamura
	 * @create	2006/07/26 16:55:21
	 * @version	1.0
	 * 
	 * @param	
	 * @return	
	 */
	public function error($str,$classname=__CLASS__,$funcname=__FUNCTION__,$lineno=__LINE__)
	{
        self::put(STL_LOG_ERROR,$str,false,$classname,$funcname,$lineno);
	}

    /**
     * fatal()
     * 致命的エラーログを出力
     *
     * @access  public
     * @author  r_futamura
     * @create  2006/07/26 16:55:21
     * @version 1.0
     * 
     * @param   
     * @return  
     */
    public function fatal($str,$classname=__CLASS__,$funcname=__FUNCTION__,$lineno=__LINE__)
    {
        self::put(STL_LOG_FATAL,$str,false,$classname,$funcname,$lineno);
    }

    /**
     * sql()
     * SQLログを出力
     *
     * @access  public
     * @author  r_futamura
     * @create  2006/07/26 16:55:21
     * @version 1.0
     * 
     * @param   
     * @return  
     */
    public function sql($str,$classname=__CLASS__,$funcname=__FUNCTION__,$lineno=__LINE__)
    {
        self::put(STL_LOG_SQL,$str,false,$classname,$funcname,$lineno);
    }

    /**
	 * stdlog()
	 * 標準出力、標準エラー出力を行う
     * 
	 * @access	public
	 * @author	r_futamura
	 * @create	2006/08/08 17:45:43
	 * @version	1.0
	 * 
	 * @param	
	 * @return	
	 */
	public function stdlog($str,$type=STDOUT)
	{
        fwrite($type,__FILE__." ".__FUNCTION__."() [".__LINE__."]:$str\n");
	}

    /**
     * stdout()
     * 標準出力を行う
     * 
     * @access  public
     * @author  r_futamura
     * @create  2006/08/08 17:45:43
     * @version 1.0
     * 
     * @param   
     * @return  
     */
    public function stdout($str)
    {
        if (defined('STL_STDOUT') && STL_STDOUT==true) {
            fwrite(STDOUT,":$str\n");
        }
    }

    /**
     * stderr()
     * 標準エラー出力を行う
     * 
     * @access  public
     * @author  r_futamura
     * @create  2006/08/08 17:45:43
     * @version 1.0
     * 
     * @param   
     * @return  
     */
    public function stderr($str)
    {
        if (defined('STL_STDERR') && STL_STDERR==true) {
            fwrite(STDERR,":$str\n");
        }
    }
}
?>
