<?php
/**
 * Htmltemplate for PHP4
 * Copyright (C) 2003 Hiroshi Ayukawa
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the Artistic License version 2.0, 
 * or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the Artistic License version 2.0 for more details.
 * 
 * You should have received a copy of the Artistic License version 2.0
 * along with this program; if not, visit: LongNguyễn
 * 
 */

/**
 * -----  Class diagram ------------------
 * 
 * +--------------+     (use)
 * | htmltemplate |<----------  Client
 * +--------------+
 * <>
 * | (use to parse documents)
 * V
 * +--------------+
 * |StandardParser|
 * +--------------+
 * |
 * |
 * --
 * V
 * +----------------+
 * | TemplateParser |
 * +----------------+
 * <>
 * |
 * | (use to parse each tags)
 * |
 * V
 * +----------+     +----------+         +-------------+
 * | TagBasis |<|---| DataTag  |---------| ConcreteTags|
 * +----------+     | ArrayTag |         +-------------+
 * +----------+            tag_val,tag_each,,,,etc.
 */

define("FILE_READ_UNIT", 8192);

/**
 * Tag definition
 */

/**
 * the base class of all tags
 */
class TagBasis {
    /**
     * regular expression to scan source html file
     */
    var $matchregexp;

    /**
     * string to replace with
     */
    var $fromstring;

    /**
     * string to replace to
     */
    var $tostring;

    /**
     * close tag string
     */
    var $closestring;

    /**
     * a flag whether regard the index as an array or not
     */
    var $regardasarray = false;

    /**
     * the first function which scan html file to 
     * find all label used in current tag
     */
    function getLabelArray($str)
    {
        $ans = array();
        preg_match_all($this -> matchregexp, $str, $regans, PREG_SET_ORDER);
        foreach($regans as $x) {
            $ans[] = $x[1];
        } 
        return $ans;
    } 

    /**
     * parse htmlfile
     */
    function parse($str, $multilabels)
    {
        while (preg_match($this -> matchregexp, $str, $match)) {
            if (count($match) > 1) {
                $ind = $this -> getIndex($match[1], $multilabels);
            } else $ind = null;

            $str = str_replace(
                call_user_func_array("sprintf", array_merge(array($this -> fromstring), array_slice($match, 1))),
                call_user_func_array("sprintf", array_merge(array($this -> tostring), array($ind), array_slice($match, 1))),
                $str);
        } 
        $str = $this -> closeTag($str);
        return $str;
    } 

    function closeTag($str)
    {
        if ($this -> closestring) {
            $str = str_replace($this -> closestring,
                "<?php
				}
			?>",
                $str);
        } 
        return $str;
    } 

    function getIndex($m, $multilabels)
    {
    } 
} 

/**
 * the super class of tags which handle non-array data
 */
class DataTag extends TagBasis {
    function getIndex($m, $multilabels)
    {
        $ar = split("/", $m);
        $ind = "";
        $rui = array();
        foreach($ar as $x) {
            array_push($rui, $x);
            $ind .= "[\"$x\"]";
            if (in_array(join("/", $rui), $multilabels)) {
                $ind .= "[\$cnt[\"" . join("/", $rui) . "\"]]";
            } 
        } 
        return $ind;
    } 
} 

/**
 * the super class of tags which handle array structure
 * like {each *}
 */
class ArrayTag extends TagBasis {
    function getIndex($m, $multilabels)
    {
        $ar = split("/", $m);
        $ind = "";
        $rui = array();
        $mattan = 0;
        foreach($ar as $x) {
            array_push($rui, $x);
            $ind .= "[\"$x\"]";
            if ($mattan != count($ar)-1 && in_array(join("/", $rui), $multilabels)) {
                $ind .= "[\$cnt[\"" . join("/", $rui) . "\"]]";
            } 
            $mattan++;
        } 
        return $ind;
    } 
} 

/**
 * parser classes
 */

/**
 * main definition of parser
 */
class TemplateParser {
    /**
     * an array to store tag instances
     */
    var $tags = array("simple" => array(), "multi" => array());

    /**
     * add a tag instance to this parser
     */
    function add($tag)
    {
        if ($tag -> regardasarray) $this -> tags["multi"][] = $tag;
        else $this -> tags["simple"][] = $tag;
        return $this;
    } 

    /**
     * remove a tag from this parser
     */
    function remove($tagname)
    {
        $this -> _remove($tagname, $this -> tags["multi"]);
        $this -> _remove($tagname, $this -> tags["simple"]);
    } 

    /**
     * clear tag storage of this parser
     */
    function clear()
    {
        $this -> tags = array("simple" => array(), "multi" => array());
    } 

    /**
     * private method to remove tag isntance
     */
    function _remove($tagname, &$tags)
    {
        for($i = 0;$i < count($tags);$i++) {
            if (get_class($tags[$i]) == strtolower($tagname)) {
                array_splice($tags, $i, 1);
                $i--;
            } 
        } 
    } 

    function parse($str)
    { 
        reset($this -> tags["multi"]);
        $multilabels = array();
        foreach($this -> tags["multi"] as $x) {
            $multilabels = array_merge($multilabels, $x -> getLabelArray($str));
        } 

        reset($this -> tags["multi"]);
        foreach($this -> tags["multi"] as $x) {
            $str = $x -> parse($str, $multilabels);
        } 

        reset($this -> tags["simple"]);
        foreach($this -> tags["simple"] as $x) {
            $str = $x -> parse($str, $multilabels);
        } 

        return $str;
    } 
} 
/**
 * Default tag classes
 *         these tags are defined as previous version of htmltemplate
 */

class tag_val extends DataTag {
    var $matchregexp = '/\{val ([^\}]+)\}/i';
    var $fromstring = "{val %s}";
    /*     var $tostring = "<?php @print nl2br(\$val%1\$s); ?>\n";   */
    var $tostring = "<?php @print \$val%1\$s; ?>\n";   
} 

class tag_rval extends DataTag {
    var $matchregexp = '/\{rval ([^\}]+)\}/i';
    var $fromstring = "{rval %s}";
    var $tostring = "<?php @print \$val%1\$s; ?>\n";
} 
class tag_def extends ArrayTag {
    var $matchregexp = '/<!--\{def ([^\}]+)\}-->/i';
    var $fromstring = "<!--{def %s}-->";
    var $tostring = "<?php
		if(@count(\$val%1\$s) && (is_array(\$val%1\$s) || strlen(\$val%1\$s))){ ?>";
    var $closestring = "<!--{/def}-->";
} 
class tag_ndef extends ArrayTag {
    var $matchregexp = '/<!--\{ndef ([^\}]+)\}-->/i';
    var $fromstring = "<!--{ndef %s}-->";
    var $tostring = "<?php
		if(!(@count(\$val%1\$s) && (is_array(\$val%1\$s) || strlen(\$val%1\$s)))){ ?>";
    var $closestring = "<!--{/ndef}-->";
} 
class tag_vdef extends DataTag {
    var $matchregexp = '/<!--\{vdef ([^\}]+)\}-->/i';
    var $fromstring = "<!--{vdef %s}-->";
    var $tostring = "<?php
		if(@\$val%1\$s && ((gettype(\$val%1\$s)!='array' && \$val%1\$s!=\"\") or (gettype(\$val%1\$s)=='array' && count(\$val%1\$s)>0))){ ?>";
    var $closestring = "<!--{/vdef}-->";
    var $regardasarray = 1;
} 

class tag_each extends ArrayTag {
    var $matchregexp = '/<!--\{each ([^\}]+)\}-->/i';
    var $fromstring = "<!--{each %s}-->";
    var $tostring = "<?php
			for(@\$cnt[\"%2\$s\"]=0;@\$cnt[\"%2\$s\"]<count(@\$val%1\$s);@\$cnt[\"%2\$s\"]++){
				?>";
    var $closestring = "<!--{/each}-->";
    var $regardasarray = true;
} 

/**
 * <!--{comment}--> *** <!--{/comment}-->
 * comment
 * remove all characters between these tags.
 */
class tag_comment extends TagBasis {
    var $matchregexp = '/<!--\{comment\}-->/i';
    var $fromstring = "<!--{comment}-->";
    var $tostring = "<?php if(FALSE){?>";
    var $closestring = "<!--{/comment}-->";
} 

class tag_xml_declare extends TagBasis {
    var $matchregexp = '/<\?xml([\s\t\r\n\-])/';
    var $fromstring = "<?xml%s";
    var $tostring = "<?php print \"<\".\"?xml%2\$s\"; ?>";
    
    function getLabelArray($str){
    	return array();
    }
} 

/**
 * StandardParser
 *         parser defined with above tags.
 *         behave as previous htmltemplate
 */
class StandardParser extends TemplateParser {
    function StandardParser()
    {
    	$this -> add(new tag_include());
       
    	
        $this -> add(new tag_val());
        $this -> add(new tag_rval());
        $this -> add(new tag_def());
        $this -> add(new tag_ndef());
        $this -> add(new tag_vdef());
        $this -> add(new tag_each());
        $this -> add(new tag_comment()); 
        $this -> add(new tag_xml_declare());
    } 
} 

/**
 * htmltemplate
 *        the APIs defined after the manner of htmltemplate for PHP4
 *        tmp file generation has not been implemented yet.(2003-07-08)
 */

class htmltemplate {
    var $parser; 
    function htmltemplate()
    {
        $this -> parser = new StandardParser();
    } 
    function &getInstance()
    {
        static $instance;
        if (! $instance) $instance = new htmltemplate();
        return $instance;
    } 
    function add($tag)
    { 
        $inst = &htmltemplate :: getInstance();
        $inst -> parser -> add($tag);
    } 
    function addTag($tagclassname)
    {
        $inst = &htmltemplate :: getInstance();
        $inst -> parser -> add(new $tagclassname());
    } 
    function removeTag($tagclassname)
    {
        $inst = &htmltemplate :: getInstance();
        $inst -> parser -> remove($tagclassname);
    } 
    function removeAllTag()
    {
        $inst = &htmltemplate :: getInstance();
        $inst -> parser -> clear();
    } 
    function setParser($parser)
    {
        $inst = &htmltemplate :: getInstance();
        $inst -> parser = $parser;
    } 
    function parse($str)
    {
        $inst = &htmltemplate :: getInstance();
        return $inst -> parser -> parse($str);
    } 
    function t_include($file, &$data) 
{ 
   $inst = &htmltemplate :: getInstance(); 
   $all = $inst -> t_fread($file); 
   print $inst -> t_buffer($all, $data); 
} 
    function stl_include($file, &$data) 
    { 
   $inst = &htmltemplate :: getInstance(); 
   $all = $inst -> t_fread($file); 
   return $inst -> t_buffer($all, $data); 
    } 


    function t_buffer(&$all, &$data) 
{ 
   $val = &$data; 
   $inst = &htmltemplate :: getInstance(); 
   return $inst -> buffer($all, $val); 
} 

    
    function t_fread($file) 
{ 
   $handle = fopen($file, "rb"); 
   $all = ""; 
   do { 
      $bytedata = fread($handle, FILE_READ_UNIT); 
      if (strlen($bytedata) == 0) { 
         break; 
      } 
      $all .= $bytedata; 
   } while (true); 
    fclose($handle);
    
   return $all; 
} 
    

    function buffer($template_str, &$val)
    {
        $inst = &htmltemplate :: getInstance();
        $code = $inst -> parse($template_str);

        ob_start();
        eval('?>' . $code);
        $cnt = ob_get_contents();
        ob_end_clean();
        return $cnt;
    } 
} 

class tag_include extends DataTag{ 
   var $matchregexp = '/<include ([^>]+)>/i'; 
   var $caches = array(); 
    
   function replace_callback($matches){ 
      if (!isset($this -> caches[$matches[1]])) { 
         $inst = &htmltemplate :: getInstance(); 
         $str = $inst -> t_fread ($matches[1]); 
         $this -> caches[$matches[1]] = preg_replace_callback($this -> matchregexp,array(&$this,'replace_callback'),$str); 
      } 
      return $this -> caches[$matches[1]]; 
   } 
    
   function parse($str,$multilabels){ 
      $this -> caches = array(); 
      return preg_replace_callback($this -> matchregexp,array(&$this,'replace_callback'),$str); 
   } 
} 


?>
