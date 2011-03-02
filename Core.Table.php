<?php
	
require_once('Database.Factory.php');
require_once('Builder.Common.php');
	
class Table
{
	public $name;
	public $text;
	
	public $value;
	public $columns;
	
	public $config;
		
	public function Table()
	{
		$this->ClearAttributes();
	}
	
	public function ClearAttributes()
	{
		$this->name = '';
		$this->columns = array();
		
		$this->text = '';
		$this->value = '';
		
		$this->config = null;
	}
	
	public function FillObject($row, $config = null)
	{
		$this->config = $config;
		
		$this->name = Common::Trim($row[0]);
		$this->GetColumns();
		
		$this->text = $this->name;
		$this->value = $this->name;
	}
	
	public function GetColumns()
	{
		$query = 'describe ' . $this->name . ';';
		$result = ConnectionFactory::ExecuteSelect($query, $this->config);

		if(!$result)
		{
			echo(mysql_error());
		}
		else
		{
			
			while($row = mysql_fetch_array($result, MYSQL_BOTH))
			{
				$column = new Column();
				$column->FillObject($row);
				array_push($this->columns, $column);
			}
		}
	}
	
	public function GetAll($config)
	{
		$list = array();
		$result = ConnectionFactory::ExecuteSelect('show tables;',$config);
		
		if(!$result)
		{
			echo(mysql_error());
		}
		else
		{
			
			while ($row = mysql_fetch_array($result, MYSQL_BOTH)) 
			{
				$table = new Table();
				$table->FillObject($row, $config);
				array_push($list, $table);				
				

			}
	
		}
		
		return $list;
	}
}

class Column
{
	public $text;
	public $value;
	
	public $name;
	public $type;
	public $key;
	
	public function Column()
	{
		
	}
	
	public function ClearAttributes()
	{		
		$this->name = '';
		$this->type = '';
		$this->key = '';
		
		$this->text = '';
		$this->value = '';
	
	}
	
	public function FillObject($row)
	{
		$this->name = Common::Trim($row['Field']);
		$this->type = Common::Trim($this->RemoveUnsigned($row['Type']));
		$this->key = ($row['Key'] == 'PRI') ? 1 : 0;
		
		$this->text = Common::Trim($this->name);
		$this->value = Common::Trim($this->name);
	}
	
	public function RemoveUnsigned($value)
	{
		return str_replace("unsigned","", $value);
	}
}


?>