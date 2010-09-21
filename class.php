<?php

	include_once('util/IO.php');
	$fieldsList = array();

try
{
	
	print_r($_POST);
	print_r('<br/><br/>');
	$project_name = $_POST['txtProject'];
	$total_fields = $_POST['hdnCurrentIndex'];
	$tablename = $_POST['txtTableName'];
	$classname = $_POST['txtClassName'];

	if($total_fields >  0)
	{
		for($i = 1; $i <= $total_fields; $i++)
		{

			try
			{
				$columnName = $_POST["txtColumnName_$i"];
				$propertieName = $_POST["txtPropertieName_$i"];
				$type = $_POST["hdnSelectedType_$i"];
				$primary = $_POST["hdnPrimaryField_$i"];
				$is_object = false;
				$object_value = '';
				$object_value_key = '';
				$object_value_label = '';
				
				try
				{
					if(isset($_POST["hdnIsObject_$i"]) && $_POST["hdnIsObject_$i"])
					{
						$is_object = $_POST["hdnIsObject_$i"];
						$object_value = $_POST["txtIsObject_$i"];
						$object_value_key = $_POST["txtIsObjectKey_$i"];
						$object_value_label = $_POST["txtIsObjectLabel_$i"];	
					}
	
				}
				catch(exception $err)
				{
	
				}

				if($columnName != '')
				{
					$field = array("column" => $columnName, "propertie" => $propertieName, "type" => $type, "primary" => $primary, "is_object" => $is_object, "object_value" => $object_value,"object_value_key" => $object_value_key,"object_value_label" => $object_value_label);
					array_push($fieldsList, $field);
				}
			}
			catch(Exception $err)
			{
				echo('erro<br/>');
			}
		}
	}
	
	echo("<h1>$project_name</h1>");
	echo("<h2>Table: $tablename</h2>");
	echo("<h2>Class: $classname</h2><br/>");	
	
	echo('<table>');
	echo('<tr><td>Column</td><td>Propertie</td><td>Type</td><td>Is primary</td></tr>');
	
	foreach($fieldsList as $field)
	{
		echo("<tr><td>" . $field['column'] . "</td><td>" . $field['propertie'] . "</td><td>" . $field['type'] . "</td><td>" . $field['primary'] . "</td></tr>");
	}
	
	echo('</table><br/><br/>');
	

	$master_path = 'reppo/' . $project_name . '/';
	echo("<h2>FilesPath: $master_path</h2>");
	
	if(!IO::ExistDir($master_path))
	{
		IO::CreateDirectory($master_path, 0777);
		IO::CreateDirectory('reppo/' . $project_name . "/Admin/",  0777);
		IO::CreateDirectory('reppo/' . $project_name . "/Class/", 0777);
	}
	

	
	$xmlClass = null;
	
	if (file_exists('template/class.xml')) 
	{
	    $xmlClass = simplexml_load_file('template/class.xml');
	}
	
	if($xmlClass != null)
	{
		$result = $xmlClass->xpath('//body');
		$body = '';
		foreach($result as $node)
		{
			$body = $node;
		}

		$fieldsFields = '';
		$clearFields = '';
		$fillFields = '';
		$databaseFields = '';
		$databasePrimary = '';
		$classPrimary = '';
		$typePrimary = '';
		
		foreach($fieldsList as $field)
		{
			
			$fieldsFields .= 'public $' . $field['propertie'] . ";\n";
			$field_clip = $field['type'];

			if($field['type'] == 'number' || $field['type'] == 'decimal' || $field['type'] == 'bool')
			{
				$clearFields .=	'$this->' . $field['propertie'] . " = 0;\n";
				if($field['primary'] == 'false'){
				$databaseFields .= 'array_push($fields, MySQLAux::' . "CreateField('" . $field['column'] . "'," . '$this->' . $field['propertie'] . ",'$field_clip'));\n";
				}
			}
			else
			{
				$clearFields .=	'$this->' . $field['propertie'] . " = '';\n";
				if($field['primary'] == 'false'){
				$databaseFields .= 'array_push($fields, MySQLAux::' . "CreateField('" . $field['column'] . "'" .',"' . '$this->' . $field['propertie'] . '",' . "'$field_clip'));\n";
				}
			}


			$fillFields .= '$this->' . $field['propertie'] . "= $" . "row['" . $field['column']  . "'];\n";
			

			if($field['primary'] == 'true')
			{
				$databasePrimary = $field['column'];
				$classPrimary = $field['propertie'];
				$typePrimary = $field['type'];
			}
		}
		
		$body = str_replace('#CLASS_NAME#',$classname,$body);
		$body = str_replace('#TABLE_NAME#',$tablename,$body);
		$body = str_replace('#FIELDS#',$fieldsFields,$body);
		$body = str_replace('#CLEAR_FIELDS#',$clearFields,$body);
		$body = str_replace('#FILL_FIELDS#',$fillFields,$body);
		$body = str_replace('#DATABASE_QUERY_FIELDS#',$databaseFields,$body);
		$body = str_replace('#PRIMARY_TABLE_FIELD#',$databasePrimary,$body);
		$body = str_replace('#PRIMARY_CLASS_FIELD#',$classPrimary,$body);
		$body = str_replace('#PRIMARY_CLASS_TYPE#',$typePrimary,$body);

		$fp = fopen("reppo/$project_name/Class/" . $classname . ".php", "w");
		fwrite($fp, $body);
		fclose($fp);
	}
	
	if (file_exists('template/form.xml')) 
	{
	    $xmlForm = simplexml_load_file('template/form.xml');
	}
	
	if($xmlForm != null)
	{
		$formXML = $xmlForm->xpath('//body');
		$controlXML = $xmlForm->xpath('//control');
		$form = '';
		$controls = array();
		
		foreach($formXML as $node)
		{
			$form = $node;
		}
		
		foreach($controlXML as $node)
		{
			array_push($controls,$node);
		}
		
		$classPrimary = '';
		$formFields = '';
		$logicalFields = '';
		
		foreach($fieldsList as $field)
		{
			if($field['primary'] == 'true')
			{
				$classPrimary = $field['propertie'];
			}
			else
			{
			
				$capitalizedName = ucwords($field['propertie']);
				$lowerName = $field['propertie'];
				
				var_dump($field['is_object']);
				if($field['is_object'] == 'true')
				{
					echo('come in:::<br/>');
					$cloneControl = GetTemplateById($controls, 'select');
				}
				else if($field['type'] == 'bool')
				{
					$cloneControl = GetTemplateById($controls, 'checkbox');
				}
				else
				{
					echo('in text<br/><br/>');
					$cloneControl = GetTemplateById($controls, 'text');
				}
				
				$cloneControl = str_replace('#CONTROL_NAME_CAPITALIZED#',$capitalizedName,$cloneControl);
				$cloneControl = str_replace('#CONTROL_NAME#',$lowerName,$cloneControl);
				$cloneControl = str_replace('#CLASS_NAME#',$field['object_value'],$cloneControl);
				$cloneControl = str_replace('#OBJECT_PRIMARY#',$field['object_value_key'],$cloneControl);
				$cloneControl = str_replace('#OBJECT_LABEL#',$field['object_value_label'],$cloneControl);
				
				$formFields .= $cloneControl . "\n\t\t\t";
				if($field['object_value'] != '')
				{
					$logicalFields .= '$item->' . "$lowerName" . '= $' . "_POST['ddl$capitalizedName'];\n\t\t\t";
				}
				else
				{
					$logicalFields .= '$item->' . "$lowerName" . '= $' . "_POST['txt$capitalizedName'];\n\t\t\t";
				}
			}
		}

		$form = str_replace('#CLASS_NAME#',$classname,$form);
		$form = str_replace('#PRIMARY_CLASS_FIELD#',$classPrimary,$form);
		$form = str_replace('#FIELDS_CONTROLS#',$formFields,$form);
		$form = str_replace('#FIELDS_TO_SAVE#',$logicalFields,$form);
		
		$fp = fopen("reppo/$project_name/Admin/" . $classname . "Details.php", "w");
		fwrite($fp, $form);
		fclose($fp);
		
	}
	
	if (file_exists('template/list.xml')) 
	{
	    $xmlList = simplexml_load_file('template/list.xml');
	}
	
	if($xmlList != null)
	{
		$listXML = $xmlList->xpath('//body');
		$list = '';
		
		foreach($listXML as $node)
		{
			$list = $node;
		}

		$classPrimary = '';
		$formFields = '';
		$header_fields = '';
		$fill_fields = '';
		
		foreach($fieldsList as $field)
		{
			if($field['primary'] == 'true')
			{
				$classPrimary = $field['propertie'];
			}

			$capitalizedName = ucwords($field['propertie']);
			$lowerName = $field['propertie'];

			$header_fields .= "<td>$capitalizedName</td>";
			$fill_fields .= '<td>$item->' ."$lowerName</td>";
		}
		
		$header_fields .= '';
		$fill_fields .= '';
		
		$list = str_replace('#CLASS_NAME#',$classname,$list);
		$list = str_replace('#PRIMARY_CLASS_FIELD#',$classPrimary,$list);
		$list = str_replace('#HEADER_FIELDS#',$header_fields,$list);
		$list = str_replace('#LINE_FIELDS#',$fill_fields,$list);
		$fp = fopen("reppo/$project_name/Admin/" . $classname . "List.php", "w");
		fwrite($fp, $list);
		fclose($fp);
		
	}

}
catch(Exception $err)
{
	
}

function GetTemplateById($pArray, $pId)
{
	foreach($pArray as $value)
	{
		if($value['id'] == $pId)
		{
			return $value;
		}
	}
}

?>
<a href="index.php">Add New</a>