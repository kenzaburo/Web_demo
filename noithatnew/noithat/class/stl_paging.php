<?php

class paging
{
	/*--------------------------------------------------
	This will be used at links like example.php?page=5
	Default: page # Type: string
	--------------------------------------------------
	*/
	var $page_url_var = 'page';
	
	/*---------------------------------------
	How many links will be showed?
	Like 4-5-6-7-8-9-10-11-12
	Except first, last, back, forward links
	Default: 8 # Type: integer
	---------------------------------------*/
	var $align_links_count = '8';
	
	
	var $records_per_page = 15;
	
	
	var $use_back_forward = true;
	
	var $back_link_icon = '&laquo;'; 
	
	var $fwd_link_icon  = '&raquo;'; 
	
	
	var $use_first_last = true;
	
	
	var $active_page_class = 'paging_this_page';
	
	var $chars = '';
    

	function assign ( $page,$url , $total_records , $records_per_page = false  )
	{
		$this->total_records = $total_records;
		
	
		if ( $records_per_page != false )
			$this->records_per_page = $records_per_page;
		
	
		$this->current_page = $page;
		
		$this->check_page_is_int ( $this->current_page );
		

		if ( ! eregi ( '^onclick=' , $url ) )
		{
			if ( ! ereg ( '\?' , $url ) )
				$url .= '?';
			elseif ( ereg ( '\?.+' , $url ) && ! ereg ( '&$' , $url ) )
				$url .= '&';
				
			$url .= $this->page_url_var . '=';
		}
		else
			$this->onclick = true;
			
		$this->url = $url;

		
		if ( $this->active_page_class )
			$this->active_page_class = ' class="'.$this->active_page_class.'" ';
			

		unset ( $this->html );
	}
	
	
	function fetch ()
	{

		if ( $this->html )
			return $this->html;
		

	
		$this->generate_pages();
		$this->generate_html();
		if($this->total_records <= $this->records_per_page )
		return '';
		else
		return $this->html;
	}
	
	
	function generate_pages ()
	{
		
	
		
		$page_count = $this->total_records / $this->records_per_page;
		
		if ( $page_count != intval ( $page_count ) )
			$page_count = intval ( $page_count ) + 1;
		
		$max_link = $page_count > $this->align_links_count ? $this->align_links_count : $page_count;

		
		$start_page = $this->current_page;
		$end_page = $this->current_page;

		while ( $max_link > '0' )
		{			
			$looped = false;
			
			if ( $end_page < $page_count )
			{
				$end_page++;
				$max_link--;
				$looped = true;
			}
			
			if ( $start_page > '1' && $max_link != '0' )
			{
				$start_page--;
				$max_link--;
				$looped = true;
			}

			if ( $looped == false )
				break;
		}

		$i = $start_page;
		
		while ( $i <= $end_page )
		{
			if ( $i != $this->current_page )
			{
				$pagearray[] = $this->generate_link ( $i , $i ) ;
			}
			else
				$pagearray[] = '<span'.$this->active_page_class.'>' . $this->chars . $i.'</span>';

			$i++;
		}
		if ( $this->use_first_last == true )
		{		
			
			if ( $start_page > 1 )
			{
				$threedot_first = ( $start_page != '2' ) ? '...' : ' ';
				$this->page_first = $this->generate_link ( '1' , '1' ) . $threedot_first ;
			}
			
			if ( $end_page < $page_count  )
			{
				$threedot_last = ( $end_page != $page_count - 1 ) ? '...' : ' ';
				
				$this->page_last = $threedot_last . $this->generate_link ( $page_count , $page_count ) ;
			}
		}
			
		
		if ( $this->use_back_forward == true )
		{
			if ( $this->current_page != '1' )
				$this->page_back = $this->generate_link ( $this->back_link_icon , $this->current_page - 1 ) . ' ' ;
		
			
			if ( $this->current_page != $page_count )
				$this->page_fwd = ' ' . $this->generate_link ( $this->fwd_link_icon , $this->current_page + 1 ) ;

		}
		
		
		$this->page_count = $page_count;
		$this->pagearray = $pagearray;
	
	}
	
	
	function generate_html ()
	{
		$html = implode ( ' ' , $this->pagearray );
		
		$html = $this->page_back . $this->page_first . $html . $this->page_last . $this->page_fwd;
		
		$this->html = $html;
	}
	
	
	
	function generate_link ( $inner, $page_number )
	{
		if ( $this->onclick == true )
		{
			$onclick = ' ' . str_replace ( '[:page:]' , $page_number , $this->url );
			$url = '#';
		}
		else
			$url = $this->url .  $page_number;

        if ($page_number != 1 || $page_number != $this->page_count)
            $link = '<a '.$onclick.'>'. $inner.'</a>';
        else
            $link = '<a '.$onclick.'>'. $this->chars . $inner.'</a>';
		
		return $link;
	}
	
	
	function check_page_is_int ( $current_page )
	{
		if ( ! ereg ( '^[0-9]+$' , $current_page ) )
			die ( 'Page number is not integer.' );
	}
	
	
	function sql_limit ( $records_per_page = false )
	{
		$current_page = ( $this->current_page ) ? $this->current_page : $_GET[$this->page_url_var];
		
		$this->check_page_is_int ( $current_page );
		
		$records_per_page = ( $records_per_page == false ) ? $this->records_per_page : $records_per_page;
		
		$limit_start = ( $current_page - 1 ) * $records_per_page;
		
		
		$sql = $limit_start . ',' . $records_per_page;
		
		return $sql;
	}
	
	
	
}