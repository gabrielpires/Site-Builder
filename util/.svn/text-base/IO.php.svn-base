<?php

class IO
{
	public $has_error;
	public $error_message;
	
	function IO()
	{
		
	}
		
	function GetDirectories($target)
	{
		try
		{
			$list = array();
		
			if ($handle = opendir($target)) {

			    while (false !== ($file = readdir($handle))) {
				
					$pos = strrpos($file, ".");
					if ($pos === false) 
					{ 
						$path = self::AddSlash($target) . $file;
						$object = array("path" => $path, "name" => $file);
					    array_push($list, $object);
					}
				}
			
			    closedir($handle);
			}
		
			return $list;
		}
		catch(Exception $err)
		{
			self::$has_error = true;
			self::$error_message = $err;
			return array();
		}
	}
	
	function GetFiles($target)
	{
		try
		{
			$list = array();
			$files = scandir($target);
			foreach($files as $file)
			{

				if(!is_dir($target . $file))
				{
					$pos = strrpos($file, ".");
				
					if ($pos != 0) 
					{
						$path = self::AddSlash($target) . $file;
						$object = array("path" => $path, "name" => $file);
						array_push($list, $object);
					}
				}	
			}
		
			return $list;
		}
		catch(Exception $err)
		{
			self::$has_error = true;
			self::$error_message = $err;
			return array();
		}
	}
	
	function CreateDirectory($target,$permission)
	{
		try
		{
			mkdir($target, $permission, true);
			return true;
		}
		catch(Exception $err)
		{
			self::$has_error = true;
			self::$error_message = $err;
			return false;
		}	 
	}
	
	function DeleteDirectory($target)
	{
		echo("In:$target<br/><br/>");
		try
		{
			$files = self::GetFiles($target);
			$directories = self::GetDirectories($target);
		
			if(count($files) > 0)
			{
				foreach($files as $file)
				{
					unlink($file['path']);
					echo("Deleting File: " . $file['path']);
				}
			}
		
			if(count($directories) > 0)
			{
				foreach($directories as $directory)
				{
					self::DeleteDirectory($directory['path']);
				}
			}
			
			rmdir($target);
			echo("Deleting Folder: " . $target);
			
			return true;
		}
		catch(Exception $err)
		{
			self::$has_error = true;
			self::$error_message = $erro;
			return false;
		}
	}
	
	function DeleteFile($target)
	{
		
	}
	
	function CopyFile($file, $to)
	{
		
	}
	
	function CopyAllFiles($from,$to)
	{
		
	}
	
	function CopyDirectory($from, $to)
	{
		
	}
	
	function CopyAllDirectories($from, $to)
	{
		
	}
	
	function AddSlash($target)
	{
		$reverse = strrev($target);
		$plus = '';
		if($reverse{0} != '/')
		{
			$plus = '/';
		}

		return ($target . $plus);
	}
	
	function ExistDir($target)
	{
		if(is_dir($target))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}


?>