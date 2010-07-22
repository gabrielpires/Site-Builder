<?php
	
	if (file_exists('template/form.xml')) 
	{
	    $xmlForm = simplexml_load_file('template/form.xml');
	}
	
	if($xmlForm != null)
	{
		$formXML = $xmlForm->xpath('//body');
		$controlXML = $xmlForm->xpath('//control');
		$form = '';
		$control = array();
		foreach($formXML as $node)
		{
			$form = $node;
		}
		
		foreach($controlXML as $node)
		{
			//$control = $node;
			array_push($control,$node);
		}
		
		echo(GetById($control, 'text'));
		
	}
	
	function GetById($array, $id)
	{
		foreach($array as $value)
		{
			if($value['id'] == $id)
			{
				return $value;
			}
		}
	}
?>