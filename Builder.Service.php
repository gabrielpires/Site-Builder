<?php
	require_once('Database.Factory.php');
	require_once('Core.Struct.php');
	require_once('Core.Table.php');
	require_once('Core.CRUD.php');
	
	if(isset($_GET['f']))
	{
		$function = $_GET['f'];
		
		switch($function)
		{
			case "connection":
				ConnectionTest();
				break;
			case "get-tables":
				GetTables();
				break;
			case "generate-crud":
				GenerateCRUD();
				break;
		}
	}
	else
	{
		$object->error = "No Function";
		echo(json_encode($object));
	}
	
	function ConnectionTest()
	{
		$server = $_REQUEST['server'];
		$user = $_REQUEST['user'];
		$pass = $_REQUEST['pass'];

		$config = new Config();
		$config->SetValues($server, 'mysql', $user, $pass);
		
		$result = ConnectionFactory::ExecuteSelect('show databases;', $config);
		$list = array();
		
		if($result)
		{
			while ($row = mysql_fetch_array($result, MYSQL_BOTH)) 
			{
				$database = new Database($row["Database"]);
				if($database->text != 'information_schema')
				{
					array_push($list, $database);				
				}
			}
		
			echo(json_encode($list));
		}
		else
		{
			$result->error = 'Connection Fail';
			$result->cod = 1;
			echo(json_encode($result));
		}
			

	}
	
	function GetTables()
	{
		$server = $_POST['server'];
		$user = $_POST['user'];
		$pass = $_POST['pass'];
		$database = $_POST['database'];
		
		$config = new Config();
		$config->SetValues($server, $database, $user, $pass);
		
		$result = Table::GetAll($config);
		
		echo(json_encode($result));
		
	}
	
	function GenerateCRUD()
	{
		if(isset($_POST['hdnTotalTables']))
		{
			$entities = array();
			$total_tables = $_POST['hdnTotalTables'];
			
			for($i = 0; $i < $total_tables; $i++)
			{
				if(isset($_POST['chkTableSelect_'. $i]))
				{
					$entity = new Entity();
					$entity->FillObject($_POST['hdnTable_' . $i], 
										$_POST['txtClassName_' . $i], 
										((int)$_POST['hdnTotalColumns_' . $i]), 
										$_POST['chkTableSelect_'. $i]);
					
					for($p = 0; $p < $entity->total_columns; $p++)
					{
						
						$entity->AddProperty($_POST['hdnFieldName_' . $i . '_' . $p],
											$_POST['txtPropertyName_' . $i . '_' . $p],
											$_POST['hdnFieldType_' . $i . '_' . $p],
											null,
											((int)$_POST['hdnFieldKey_' . $i . '_' . $p]),
											(isset($_POST['chkIsObject_' . $i . '_' . $p]) ? $_POST['chkIsObject_' . $i . '_' . $p] : 0),
											$_POST['ddlTables_' . $i . '_' . $p],
											$_POST['ddlTextField_' . $i . '_' . $p],
											$_POST['ddlValueField_' . $i . '_' . $p]);
					}
					
					array_push($entities, $entity);
				}
			}
			
			$crud = new CRUD();
			$crud->Setup($entities, $_POST['txtProjectName'], $_POST['txtServer'], $_POST['txtDatabase'], $_POST['txtUser'], $_POST['txtPass']);
			$crud->Generate();
			echo(json_encode($entities));
			
		}
		else
		{
			$result->error = 'No table(s) to generate code.';
			$result->cod = 1;
			echo(json_encode($result));
		}
		
		
	}
	
?>