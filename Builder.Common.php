<?php

class Common
{
	public function Common()
	{
	
	}
	
	public function Trim($value)
	{
		$result = $value;
		$result = rtrim($result);
		$result = ltrim($result);
		
		return $result;
	}
}

?>