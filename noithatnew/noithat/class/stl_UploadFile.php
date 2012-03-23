<?php
class STL_UploadFile
{
	public $photo;
	public $uploadDIR;
	protected $type = array("image/jpg","image/jpeg","image/pjpeg","image/gif","image/png","image/x-png");
	protected $chars = "0123456789";
	
	function __construct($photo="")
	{
		define("MAX_FILE_SIZE",200); 
		define("MAX_LENGTH",10);
		define("FILE_MOD",0777);
		if ($photo != "")
			$this->photo = $photo;
		
	}
	function __destruct()
	{
	}

	function stl_MovedFile($source, $dest, $subFolder="")
	{
		if ($source != "" && $dest != "")
		{
			if ($subFolder != "")
				$dest .= $this->stl_CreateDirectory($subFolder);
			if (!copy($source, $dest)) {
				return FALSE;
			}
			return TRUE;
		}
	}
	
	function stl_UploadedFile($folderName="")
	{
		if (isset($this->photo))
		{
			if ($this->stl_CheckFileType($this->photo['type']))
			{
                if($this->stl_CheckFileSize($this->photo['size']))
                {
    				$dir = "";
    				$dir .= $this->uploadDIR.$this->photo['name'];					
                    
    				if (move_uploaded_file($this->photo['tmp_name'],$dir))
    				{
    					return $this->photo['name'];
    				}
    				else
    					return '4';
                }else{
					return '3'; 
				}	
			}else{
				return '2';
			}
		}
			return '1';
		
	}
		
	function stl_UploadedArrayFile()
	{
		if (isset($this->photo))
		{
			$arrayDIR =  array();
			foreach ($this->photo['error'] as $key=>$error)
			{
				if ($this->stl_CheckFileType($this->photo['type'][$key]) 
				    && $this->stl_CheckFileSize($this->photo['size'][$key]))
				{
					$newName = $this->stl_CreateFileName($this->photo['name'][$key]);
					$dir = $this->stl_CreateDirectory($id);
					$dir .= $newName;					
					if (move_uploaded_file($this->photo['tmp_name'][$key], $dir))
					{
						chmod($dir,FILE_MOD);
						array_push($arrayDIR, $newName);						
					}
				}
				else
					return FALSE;
			}
			return $arrayDIR;
		}
		return FALSE;
	}
	
	function stl_CheckArrFileType()
	{
		if (isset($this->photo))
		{
			$arrFlag =  array();
			foreach ($this->photo['error'] as $key=>$error)
			{
				$flag = $this->stl_CheckFileType($this->photo['type'][$key]);
				array_push($arrFlag, $flag);				
			}
			return $arrFlag;
		}
	}
	

	function stl_CheckFileType($fileType)
	{
		foreach ($this->type as $value)
		{
			if ($fileType == $value)
				return TRUE;			
		}
		return FALSE;
	}
	
	function stl_CheckFileSize($fileSize)
	{
		if ($fileSize > 0 && $fileSize/1024 <= 200)
			return TRUE;
		else
			return FALSE;
	}
	
	function stl_CreateFileName($fileName)
	{
		$ext = strrchr($fileName,".");
		$newName = $this->stl_RandomText() . $ext;
		return $newName;
	}
	
	function stl_RandomText($n=10)
	{
		srand(rand());
		$min = 0;
		$max = strlen($this->chars);		
		for ($i=0; $i<$n; $i++)
		{
			$x = floor((rand()/getrandmax())* $max + $min);
			$str .= $this->chars[$x];				
		}
		return $str;
	}
	
	function stl_CreateDirectory($subFolder)
	{
		if (!is_dir($this->uploadDIR))
			mkdir($this->uploadDIR,FILE_MOD);
		if ($subFolder!="")
			$dir = $this->uploadDIR . $subFolder . "/";
		else
			$dir = $this->uploadDIR;
		if (!is_dir($dir))
			mkdir($dir,FILE_MOD);
		return $dir;
	}
	
		function stl_CreateFolder($path)
	{
		$arrFolderName = explode("/",$path);
		$curFolder = "";		
		foreach($arrFolderName as $part)
		{
			$curFolder .= $part . "/";
			if (!is_dir($curFolder) && strlen($curFolder)>0)
				mkdir($curFolder, FILE_MOD);
		}		
	}
	function stl_AutoResize($fileName, $maxSize=50)
	{
	
		list($realWidth, $realHeight, $type, $attr) = getimagesize($fileName );
        if ($realWidth > $maxSize || $realHeight > $maxSize)
        {
            if ($realWidth > $realHeight)
            {
                $width = $maxSize.'px';
				$data['type'] = 'width';
				$data['size'] = $width; 
            }
            else
            {
                $height = $maxSize.'px';
				$data['type'] = 'height';
				$data['size'] = $height; 
            }
			
		}
		else
		{
			if ($realWidth > $realHeight)
            {
                $width = $realWidth.'px';
				$data['type'] = 'width';
				$data['size'] = $width; 
            }
            else
            {
                $height = $realHeight.'px';
				$data['type'] = 'height';
				$data['size'] = $height; 
            }
		}
		return $data;
	}
	
	function stl_DeleteFile($fileName)
	{
		/**SELECT from DB, unlink exist file**/		
		if (file_exists($fileName))
		{
			if (unlink($fileName))
				return TRUE;
			else
				return FALSE;
		}
		return FALSE;
	}
		
	function stl_DeleteAllFile($path,$matchString)
	{
		$file = glob("$path/*");
		foreach ($file as $fileName)
		{
			if (preg_match("/".$matchString."/",$fileName) > 0)
			{
				unlink($fileName);				
			}
		}
	}


}
?>