<?php
require_once('util/IO.php');
require_once('Database.Factory.php');

class CRUD
{

	public $output_path = 'output/';
	
	public $entities;
	public $project;
	public $server;
	public $database;
	public $db_user;
	public $db_pass;
	
	public $project_path;
	public $project_build_path;
	public $project_build_version;
	public $project_controls_path;
	
	public $class_template;
	public $form_template;
	public $list_template;
	public $service_template;
	public $menu_template;
	public $factory_template;
	
	public function CRUD()
	{
	
	}
	
	public function Setup($entites, $project, $server, $database, $user, $pass)
	{
		$this->entities = $entites;
		$this->project = $project;
		$this->server = $server;
		$this->database = $database;
		$this->db_user = $user;
		$this->db_pass = $pass;
		
		$this->PreparateProjectName();
		$this->CreateProjectFolderName();
	}
	
	public function Generate()
	{
		$this->CreatFolderOutput();
		$this->LoadTempates();
		$this->CreateFiles();
	}
	
	public function CreatFolderOutput()
	{
		if(!IO::ExistDir($this->project_path))
		{
			IO::CreateDirectory($this->project_path,0777);
		}
		
		$this->CreateBuildPath();
		$this->CreateCommomStruct();
	}
	
	public function PreparateProjectName()
	{
		$this->project = str_replace(' ', '_', $this->project);
	}
	
	public function CreateProjectFolderName()
	{
		$this->project_path = $this->output_path . $this->project . '/';
	}
	
	public function CreateProjectFolder()
	{
		IO::CreateDirectory($this->project_path);
	}
	
	public function CreateBuildVersion()
	{
		$current_time = time();
		$this->project_build_version = $current_time['year'] . $current_time['mon'] . $current_time['mday'] . $current_time['hours'] . $current_time['minutes'] . $current_time['seconds'];
		//ONLY TO DEVELOPMENT
		//$this->project_build_version = 'development_version';
	}
	
	public function CreateBuildPath()
	{
		$this->CreateBuildVersion();
		$this->project_build_path = $this->project_path . $this->project_build_version . '/';
		IO::CreateDirectory($this->project_build_path, 0777);
	}
	
	public function CreateCommomStruct()
	{
		IO::CreateDirectory($this->project_build_path . 'admin' ,0777);
		IO::CreateDirectory($this->project_build_path . 'site' ,0777);
		IO::CreateDirectory($this->project_build_path . 'docs' ,0777);
		IO::CreateDirectory($this->project_build_path . 'database' ,0777);
		IO::CreateDirectory($this->project_build_path . 'class' ,0777);
		IO::CreateDirectory($this->project_build_path . 'common', 0777);
		
		//ADMIN BASIC STRUCT
		$css_path = $this->project_build_path . 'admin' . '/css';
		$js_path = $this->project_build_path . 'admin' . '/js';
		$images_path = $this->project_build_path . 'admin' . '/img';
		$controls_path = $this->project_build_path . 'admin' . '/controls';
		$this->project_controls_path = $controls_path;
		IO::CreateDirectory($css_path ,0777);
		IO::CreateDirectory($js_path ,0777);
		IO::CreateDirectory($images_path ,0777);
		IO::CreateDirectory($controls_path ,0777);
		
		//CHANGE ALL THIS FOR A LOOP ****TODO****
		//COPY ADMIN LAYOUT TEMPLATE
		//CSS FILES
		copy('template/admin/css/hack.css',$css_path . '/hack.css');
		copy('template/admin/css/ie6.css',$css_path . '/ie6.css');
		copy('template/admin/css/ie6.css',$css_path . '/ie6.css');
		copy('template/admin/css/layout.css',$css_path . '/layout.css');
		copy('template/admin/css/reset.css',$css_path . '/reset.css');
		copy('template/admin/css/transdmin.css',$css_path . '/transdmin.css');
		//JS FILES
		copy('template/admin/js/main.js',$js_path . '/main.js');
		//IMG FILES
		copy('template/admin/img/btn_left.gif',$images_path . '/btn_left.gif');
		copy('template/admin/img/btn_right.gif',$images_path . '/btn_right.gif');
		copy('template/admin/img/button-submit.gif',$images_path . '/button-submit.gif');
		copy('template/admin/img/content.gif',$images_path . '/content.gif');
		copy('template/admin/img/input-shaddow-hover.gif',$images_path . '/input-shaddow-hover.gif');
		copy('template/admin/img/input-shaddow.gif',$images_path . '/input-shaddow.gif');
		copy('template/admin/img/left-menu-bg.gif',$images_path . '/left-menu-bg.gif');
		copy('template/admin/img/logo.jpg',$images_path . '/logo.jpg');
		copy('template/admin/img/select_left.gif',$images_path . '/select_left.gif');
		copy('template/admin/img/select_right.gif',$images_path . '/select_right.gif');
		copy('template/admin/img/top-menu-bg.gif',$images_path . '/top-menu-bg.gif');
		copy('template/admin/img/top-menu-item-bg.gif',$images_path . '/top-menu-item-bg.gif');
		
		//CONTROLS FILES
		copy('template/admin/controls/assets.php',$controls_path . '/assets.php');
		copy('template/admin/controls/header.php',$controls_path . '/header.php');
		//NOW THE MENU IS CREATED WITH THE CRUD
		//copy('template/admin/controls/sidebar.php',$controls_path . '/sidebar.php');
		copy('template/admin/controls/footer.php',$controls_path . '/footer.php');
	}
	
	public function LoadTempates()
	{
		if (file_exists('template/class.xml')) 
		{
		    $this->class_template = simplexml_load_file('template/class.xml');
		}
		
		if (file_exists('template/list.xml')) 
		{
		    $this->list_template = simplexml_load_file('template/list.xml');
		}
		
		if (file_exists('template/form.xml')) 
		{
		    $this->form_template = simplexml_load_file('template/form.xml');
		}
		
		if (file_exists('template/service.xml')) 
		{
		    $this->service_template = simplexml_load_file('template/service.xml');
		}
		
		if (file_exists('template/menu.xml')) 
		{
		    $this->menu_template = simplexml_load_file('template/menu.xml');
		}
		
		if (file_exists('template/factory.xml')) 
		{
		    $this->factory_template = simplexml_load_file('template/factory.xml');
		}
		
	}
	
	public function CreateFiles()
	{
		foreach($this->entities as $entity)
		{
			$entity_path = $this->CreateEntityDirectory($entity);
			$this->CreateClassFile($entity);
			$this->CreateListFile($entity_path,$entity);
			$this->CreateFormFile($entity_path,$entity);
			$this->CreateServiceFile($entity_path,$entity);
		}
		
		$this->CreateMenu();
		$this->CreateDatabaseFactory();
	}
	
	public function CreateEntityDirectory($entity)
	{
		$entity_path = $this->project_build_path . "admin/" . strtolower($entity->class_name);
		IO::CreateDirectory($entity_path, 0777);
		return $entity_path;
	}
	
	public function CreateClassFile($entity)
	{
		$file_path_name = $this->project_build_path . "class/" . $entity->class_name . ".php";
		$this->CreateGenericFile($file_path_name,$this->CreateClassContent($entity));
	}
	
	public function CreateClassContent($entity)
	{
		//TEMPLATE
		$body_template = $this->class_template->xpath('//body');
		$body_template = trim($body_template[0]);
		
		//TEMPLATE
		$property_template = $this->class_template->xpath("//item[@id='property']");
		$property_template = trim($property_template[0]);
		
		//TEMPLATE
		$query_template = $this->class_template->xpath("//item[@id='query-property']");
		$query_template = trim($query_template[0]);
		
		//TEMPLATE
		$fill_template = $this->class_template->xpath("//item[@id='fill-property']");
		$fill_template = trim($fill_template[0]);
		
		//TEMPLATE
		$empty_template = $this->class_template->xpath("//item[@id='empty-property']");
		$empty_template = trim($empty_template[0]);
		

		//LOOP CONCAT PROPERTIES
		$properties = '';
		$query = '';
		$insert = '';
		$update = '';
		$fill = '';
		$empty = '';
		
		$primary_key = null;
		
		foreach($entity->properties as $property)
		{
			$isPrimaryProperty = ($property->key == '1');
			if($isPrimaryProperty)
			{
				$primary_key = $property;
			}
			
			$propertyItem = $property_template;
			$propertyItem = str_replace("#PROPERTY_NAME#",$property->name,$propertyItem);
			//ADD CORRECT TAB SPACE AND LINE BREAK
			$propertyItem .= "\n\t\t";
			$properties .= $propertyItem;
			
			if(!$isPrimaryProperty)
			{				
				$queryItem = $query_template;
				$queryItem = str_replace("#COLUMN_NAME#",$property->column,$queryItem);
				$queryItem = str_replace("#PROPERTY_NAME#",$property->name,$queryItem);
				$queryItem = str_replace("#TYPE#",$property->type,$queryItem);
				//ADD CORRECT TAB SPACE AND LINE BREAK
				$queryItem .= "\n\t\t\t\t";
				$query .= $queryItem;
			}

			
			$fillItem = $fill_template;
			$fillItem = str_replace("#COLUMN_NAME#",$property->column, $fillItem);
			$fillItem = str_replace("#PROPERTY_NAME#",$property->name, $fillItem);
			$fillItem = str_replace("#TYPE#",$property->type, $fillItem);	
			//ADD CORRECT TAB SPACE AND LINE BREAK		
			$fillItem .= "\n\t\t\t";
			$fill .= $fillItem;
			
			$emptyItem = $empty_template;
			$emptyItem = str_replace("#PROPERTY_NAME#",$property->name, $emptyItem);
			$emptyItem = str_replace("#EMPTY_VALUE#",MySQLAux::GetEmptyValue($property->type), $emptyItem);
			//ADD CORRECT TAB SPACE AND LINE BREAK		
			$emptyItem .= "\n\t\t\t";
			$empty .= $emptyItem;
			
		}
		
		$content = $body_template;
		$content = str_replace('#CLASS_NAME#',$entity->class_name,$content);
		$content = str_replace('#TABLE_NAME#',$entity->table_name,$content);
		$content = str_replace('#FIELDS#',$properties,$content);
		$content = str_replace('#DATABASE_QUERY_FIELDS#',$query,$content);
		$content = str_replace('#FILL_FIELDS#',$fill,$content);
		$content = str_replace('#CLEAR_FIELDS#',$empty,$content);
		$content = str_replace('#PRIMARY_TABLE_FIELD#',$primary_key->column,$content);
		$content = str_replace('#PRIMARY_CLASS_FIELD#',$primary_key->name,$content);
		$content = str_replace('#PRIMARY_CLASS_TYPE#',$primary_key->type,$content);		

		return $content;
	}
	
	public function CreateListFile($entity_path, $entity)
	{
		$file_path_name = $entity_path . "/list.php";
		$this->CreateGenericFile($file_path_name,$this->CreateListContent($entity));
	}		
	
	public function CreateListContent($entity)
	{
		//TEMPLATE
		$body_template = $this->list_template->xpath('//body');
		$body_template = trim($body_template[0]);

		//TEMPLATE
		$headerTemplate = $this->list_template->xpath("//item[@id='header-item']");
		$headerTemplate = trim($headerTemplate[0]);
		
		//TEMPLATE
		$valueTemplate = $this->list_template->xpath("//item[@id='value-item']");
		$valueTemplate = trim($valueTemplate[0]);
		
		//TEMPLATE
		$rowTemplate = $this->list_template->xpath("//item[@id='row-item']");
		$rowTemplate = trim($rowTemplate[0]);
		
		$headers = '';
		$values = '';
		$primary_key = null;
		
		foreach($entity->properties as $property)
		{
			if($property->key == '1')
			{
				$primary_key = $property;
			}
			
			//HEADER LOOP
			$headerItem = $headerTemplate;
			$headerItem = str_replace('#NAME#',$property->name,$headerItem);
			$headerItem .= "\n\t\t\t\t\t\t\t\t";
			$headers .= $headerItem;
			
			//VALUE LOOP
			$valueItem = $valueTemplate;
			$valueItem = str_replace('#VALUE#',$property->name,$valueItem);
			$valueItem .= "\n\t\t\t\t\t\t\t\t";
			$values .= $valueItem;
		}
		
		//ROW REPLACES
		$rows = $rowTemplate;
		$rows = str_replace('#VALUES#',$values,$rows);
		$rows = str_replace('#KEY#',$primary_key->name,$rows);
		
		//BODY REPLACES
		$content = $body_template;
		$content = str_replace('#CLASS_NAME#',$entity->class_name,$content);
		$content = str_replace('#HEADER#',$headers,$content);
		$content = str_replace('#ROWS#',$rows,$content);
		
		return $content;
	}
	
	public function CreateFormFile($entity_path, $entity)
	{
		$file_path_name = $entity_path . "/index.php";
		$this->CreateGenericFile($file_path_name,$this->CreateFormContent($entity));
	}
	
	public function CreateFormContent($entity)
	{
		//TEMPLATE
		$body_template = $this->form_template->xpath('//body');
		$body_template = trim($body_template[0]);
		
		//TEMPLATE
		$textFieldTemplate = $this->form_template->xpath("//control[@id='text']");
		$textFieldTemplate = trim($textFieldTemplate[0]);
		
		$checkboxFieldTempalte = $this->form_template->xpath("//control[@id='checkbox']");
		$checkboxFieldTempalte = trim($checkboxFieldTempalte[0]);
		
		$selectFieldTempalte = $this->form_template->xpath("//control[@id='select']");
		$selectFieldTempalte = trim($selectFieldTempalte[0]);
		
		$fields = '';
		$extra_includes = '';
		$primary_key = null;
		
		foreach($entity->properties as $property)
		{
			if($property->key == '1')
			{
				$primary_key = $property;
			}
			else
			{
				if($property->is_object)
				{
					$fieldItem = $selectFieldTempalte;
					$other_class_name = '';
					$other_value_property = '';
					$other_text_property = '';
					
					foreach($this->entities as $other_entity)
					{
						if($other_entity->table_name == $property->object_table)
						{
							$other_class_name = $other_entity->class_name;
							foreach($other_entity->properties as $other_property)
							{

								if($other_property->name == strtolower($property->object_value))
								{
									$other_value_property = $other_property->name;
								}
								elseif($other_property->name == strtolower($property->object_text))
								{
									$other_text_property = $other_property->name;
								}
							}
							
							break;
						}
						
					}
					
					$fieldItem = str_replace('#OTHER_CLASS_NAME#',$other_class_name,$fieldItem);
					$fieldItem = str_replace('#OTHER_CLASS_VALUE_PROPERTY#',$other_value_property,$fieldItem);
					$fieldItem = str_replace('#OTHER_CLASS_TEXT_PROPERTY#',$other_text_property,$fieldItem);
					$extra_includes .= "require_once('../../class/$other_class_name.php');\t"; 
						
				}
				elseif($property->type == 'bit' || $property->type == 'bool' || $property->type == 'boolean')
				{
					$fieldItem = $checkboxFieldTempalte;
				}
				else
				{
					$fieldItem = $textFieldTemplate;
				}
				
				$fieldItem = str_replace('#PROPERTY_NAME#',$property->name,$fieldItem);
				$fieldItem .= "\n\t\t\t\t\t\t\t";
				$fields .= $fieldItem;

			}
		}
		
		//BODY REPLACES
		$content = $body_template;
		$content = str_replace('#CLASS_NAME#',$entity->class_name,$content);
		$content = str_replace('#EXTRA_INCLUDES#',$extra_includes,$content);
		
		$content = str_replace('#PRIMARY_PROPERTY#',$primary_key->name,$content);
		$content = str_replace('#FIELDS#',$fields,$content);
		
		return $content;
	}
	
	public function CreateServiceFile($entity_path, $entity)
	{
		$file_path_name = $entity_path . "/service.php";
		$this->CreateGenericFile($file_path_name,$this->CreateServiceContent($entity));
	}
	
	public function CreateServiceContent($entity)
	{
		//TEMPLATE
		$body_template = $this->service_template->xpath('//body');
		$body_template = trim($body_template[0]);
		
		//TEMPLATE
		$requestTemplate = $this->service_template->xpath("//item[@id='request-item']");
		$requestTemplate = trim($requestTemplate[0]);
		
		$setTemplate = $this->service_template->xpath("//item[@id='set-item']");
		$setTemplate = trim($setTemplate[0]);
		
		
		$requests = '';
		$sets = '';
		$primary_key = null;
		
		foreach($entity->properties as $property)
		{
			if($property->key == '1')
			{
				$primary_key = $property;
			}
			else
			{
				$requestItem = $requestTemplate;
				$requestItem = str_replace('#PROPERTY_NAME#',$property->name,$requestItem);
				$requestItem .= "\n\t\t\t";
				$requests .= $requestItem;
				
				$setItem = $setTemplate;
				$setItem = str_replace('#PROPERTY_NAME#',$property->name,$setItem);
				$setItem .= "\n\t\t\t";
				$sets .= $setItem;
			}

		}
		
		//BODY REPLACES
		$content = $body_template;
		$content = str_replace('#CLASS_NAME#',$entity->class_name,$content);
		$content = str_replace('#REQUEST_FIELDS#',$requests,$content);
		$content = str_replace('#SET_FIELDS#',$sets,$content);
		$content = str_replace('#PRIMARY_PROPERTY#',$primary_key->name,$content);
		
		return $content;
	}
	
	public function CreateMenu()
	{
		
		//TEMPLATE
		$body_template = $this->menu_template->xpath('//body');
		$body_template = trim($body_template[0]);
		
		//TEMPLATE
		$menuItemTemplate = $this->menu_template->xpath("//item[@id='menu-item']");
		$menuItemTemplate = trim($menuItemTemplate[0]);
		

		$items = '';
		foreach($this->entities as $entity)
		{
			$menuItem = $menuItemTemplate;
			$menuItem = str_replace('#CLASS_NAME#',strtolower($entity->class_name),$menuItem);
			$items .= $menuItem;
		}
		
		$content = $body_template;
		$content = str_replace('#ITEMS#',$items,$content);
		
		$this->CreateMenuFile($content);
	}
	
	public function CreateMenuFile($content)
	{
		$file_path_name = $this->project_controls_path . "/sidebar.php";
		$this->CreateGenericFile($file_path_name,$content);
	}
	
	public function CreateDatabaseFactory()
	{
		//TEMPLATE
		$body_template = $this->factory_template->xpath('//body');
		$body_template = trim($body_template[0]);
		
		$content = $body_template;
		$content = str_replace('#SERVER#',$this->server,$content);
		$content = str_replace('#DB_NAME#',$this->database,$content);
		$content = str_replace('#DB_USER#',$this->db_user,$content);
		$content = str_replace('#DB_PASS#',$this->db_pass,$content);
		$this->CreateGenericFile($this->project_build_path . '/database/factory.php', $content);				
	}
	
	public function CreateGenericFile($file_path_name, $content)
	{
		$fp = fopen($file_path_name, "w");
		fwrite($fp, $content);
		fclose($fp);
	}
	

	
}

?>