<?php

class Config
{
	public $server;
	public $database;
	public $user;
	public $pass;
	
	public function Config()
	{
		$this->Clear();
		$this->Set('LOCAL');
	}
	
	public function Clear()	
	{
		$this->server = '';
		$this->database = '';
		$this->user = '';
		$this->pass = '';
	}
	
	public function Set($value)
	{
		switch($value)
		{
			case 'LOCAL':
				$this->SetValues('localhost','DB_DEMO','root','123456');
				break;
			case 'HML':
				$this->SetValues('','','','');
				break;
			case 'PROD':
				$this->SetValues('','','','');
				break;
		}
		
	}
	
	public function SetValues($pServer, $pDatabase, $pUser, $pPass)
	{
		$this->server = $pServer;
		$this->database = $pDatabase;
		$this->user = $pUser;
		$this->pass = $pPass;
	}
}


class ConnectionFactory
{

	//attributes LOCAL
	public static $server;
	public static $database;
	public static $user;
	public static $pass;
	public static $connection;
	
	public static $environment = 'LOCAL';
	
	public function CreateConnection($pConfig = null)
	{
		try
		{
			$config = null;
			if($pConfig == null)
			{
				$config = new Config();
				$config->Set(self::$environment);
			}
			else
			{
				$config = $pConfig;
			}		
			
			self::$server = $config->server;
			self::$user = $config->user;
			self::$pass = $config->pass;
			self::$database = $config->database;

			self::$connection = mysql_connect(self::$server, self::$user,self::$pass);
			self::SetDataBase(self::$connection);		
			
						
		}
		catch(Exception $err)
		{
			echo("Connection error: " . mysql_error());
			return null;
		}
		
	}
	
	public function SetDataBase($connection)
	{
		mysql_select_db(self::$database, $connection);
	}
	
	public function ExecuteSelect($query, $config = null)
	{
		self::CreateConnection($config);
		try
		{
			$result = mysql_query($query);
		}
		catch(Exception $err)
		{
			echo mysql_errno(self::$connection);
			echo mysql_error(self::$connection);
			echo $err;
		}
		self::DisposeConnection();
		return $result;
	}
	
	public function ExecuteInsert($query, $config = null)
	{
		self::CreateConnection($config);
		mysql_query($query);
		try
		{
			$result = mysql_insert_id(self::$connection);
		}
		catch(Exception $err)
		{
			echo mysql_errno(self::$connection);
			echo mysql_error(self::$connection);
			echo $err;
		}
		self::DisposeConnection();
		return $result;
	}
	
	public function ExecuteAffected($query, $config = null)
	{
		self::CreateConnection($config);
		mysql_query($query);
		try
		{
			$result = mysql_affected_rows(self::$connection);
		}
		catch(Exception $err)
		{
			echo mysql_errno(self::$connection);
			echo mysql_error(self::$connection);
			echo $err;
		}
		self::DisposeConnection();
		return $result;
	}
	
	public function DisposeConnection()
	{
		mysql_close(self::$connection);
	}
}

class MySQLQuery
{
	public function MySQLQuery()
	{
		
	}
	
	public function Insert($pTable, $pFields)
	{
		$counter = count($pFields);
		if($counter > 0)
		{
			$query = '';
		
			$query .= 'INSERT INTO ' . $pTable . ' (';

			$counterVariable = $counter;
			//columns
			foreach($pFields as $field)
			{
				$query .= $field->name;
				
				if($counterVariable != 1)
				{
					$query .= ', ';
				}	
				
				$counterVariable--;
			}
			$counterVariable = $counter;
			$query .= ') VALUES (';
		
			//values
			foreach($pFields as $field)
			{
				$query .=  MySQLAux::AlterValueToPost($field->type, $field->value);
				
				if($counterVariable != 1)
				{
					$query .= ', ';
				}	
				
				$counterVariable--;
			}
			
			$query .= ')';
		}
		return $query;
		
	}
	
	public function Update($pTable, $pFields, $pWhere)
	{
		$counter = count($pFields);
		if($counter > 0)
		{
			$query = '';
		
			$query .= 'UPDATE ' . $pTable . ' SET ';

			$counterVariable = $counter;
			//columns
			foreach($pFields as $field)
			{
				$query .= $field->name . ' = ' . MySQLAux::AlterValueToPost($field->type, $field->value);
				
				if($counterVariable != 1)
				{
					$query .= ', ';
				}	
				
				$counterVariable--;
			}
			
			$query .= ' WHERE ' . $pWhere->name . ' = ' .  MySQLAux::AlterValueToPost($pWhere->type, $pWhere->value);
		

		}
		return $query;
	}
}

//Field Class
class MySQLField
{
	//attributes
	public $name;
	public $value;
	public $type;
	
	//contructor
	public function MySQLField()
	{
		
	}
	
	//methods
	public function Set($pName, $pValue, $pType)
	{
		$this->name = $pName;
		$this->value = $pValue;
		$this->type = $pType;
	}
	
}

class MySQLAux
{
	public function MySQLAux()
	{
		
	}
	
	public function DBDateTimeNow()
	{
		return date('Y-m-d H:i:s');
	}
	
	public function DBDateNow()
	{
		return date('Y-m-d');
	}
	
	public function CreateField($pName, $pValue, $pType)
	{
		$field = new MySQLField();
		$field->Set($pName, $pValue, $pType);
		
		return $field;
	}
	
	public function GetEmptyValue($type)
	{
		switch(strtoupper($type))
		{
			case 'INT':
				$result = 0;
				break;
			case 'NUMERIC':
				$result = 0;
				break;
			case 'TINYINT':
				$result = 0;
				break;
			case 'SMALLINT':
				$result = 0;
				break;
			case 'MEDIUMINT':
				$result = 0;
				break;
			case 'BIGINT':
				$result = 0;
				break;
			case 'DECIMAL':
				$result = 0;
				break;
			case 'FLOAT':
				$result = 0;
				break;
			case 'DOUBLE':
				$result = 0;
				break;
			case 'REAL':
				$result = 0;
				break;
			case 'BIT':
				$result = 0;
				break;
			case 'BOOLEAN':
				$result = 0;
				break;
			case 'SERIAL':
				$result = "''";
				break;
			case 'DATE':
				$result = "''";
				break;
			case 'DATETIME':
				$result = "''";
				break;
			case 'TIMESTAMP':
				$result = "''";
				break;
			case 'TIME':
				$result = "''";
				break;
			case 'YEAR':
				$result = 1900;
				break;
			case 'CHAR':
				$result = "''";
				break;
			case 'VARCHAR':
				$result = "''";
				break;
			case 'TINYTEXT':
				$result = "''";
				break;
			case 'TEXT':
				$result = "''";
				break;
			case 'MEDIUMTEXT':
				$result = "''";
				break;
			case 'LONGTEXT':
				$result = "''";
				break;
			case 'BINARY':
				$result = 0;
				break;
			case 'VARBINARY':
				$result = "''";
				break;
			case 'TINYBLOB':
				$result = "''";
				break;
			case 'MEDIUMBLOB':
				$result = "''";
				break;
			case 'BLOB':
				$result = "''";
				break;
			case 'LONGBLOB':
				$result = "''";
				break;
			case 'ENUM':
				$result = "''";
				break;
			case 'SET':
				$result = "''";
				break;
			case 'GEOMETRY':
				$result = 0;
				break;
			case 'POINT':
				$result = 0;
				break;
			case 'LINESTRING':
				$result = "''";
				break;
			case 'POLYGON':
				$result = "''";
				break;
			case 'MULTIPOINT':
				$result = "''";
				break;
			case 'MULTILINESTRING':
				$result = "''";
				break;
			case 'MULTIPOLYGON':
				$result = "''";
				break;
			case 'GEOMETRYCOLLECTION':
				$result = "''";
				break;
			default :
				$result = "''";
				break;
		}
		
		return $result;
	}
	
	public function AlterValueToPost($type, $value)
	{
		switch(strtoupper($type))
		{
			case 'INT':
				$result = $value;
				break;
			case 'NUMERIC':
				$result = $value;
				break;
			case 'TINYINT':
				$result = $value;
				break;
			case 'SMALLINT':
				$result = $value;
				break;
			case 'MEDIUMINT':
				$result = $value;
				break;
			case 'BIGINT':
				$result = $value;
				break;
			case 'DECIMAL':
				$result = $value;
				break;
			case 'FLOAT':
				$result = $value;
				break;
			case 'DOUBLE':
				$result = $value;
				break;
			case 'REAL':
				$result = $value;
				break;
			case 'BIT':
				$result = $value;
				break;
			case 'BOOLEAN':
				$result = $value;
				break;
			case 'SERIAL':
				$result = "'" . $value . "'";
				break;
			case 'DATE':
				$result = "'" . $value . "'";
				break;
			case 'DATETIME':
				$result = "'" . $value . "'";
				break;
			case 'TIMESTAMP':
				$result = "'" . $value . "'";
				break;
			case 'TIME':
				$result = "'" . $value . "'";
				break;
			case 'YEAR':
				$result = $value;
				break;
			case 'CHAR':
				$result = "'" . $value . "'";
				break;
			case 'VARCHAR':
				$result = "'" . $value . "'";
				break;
			case 'TINYTEXT':
				$result = "'" . $value . "'";
				break;
			case 'TEXT':
				$result = "'" . $value . "'";
				break;
			case 'MEDIUMTEXT':
				$result = "'" . $value . "'";
				break;
			case 'LONGTEXT':
				$result = "'" . $value . "'";
				break;
			case 'BINARY':
				$result = "'" . $value . "'";
				break;
			case 'VARBINARY':
				$result = "'" . $value . "'";
				break;
			case 'TINYBLOB':
				$result = "'" . $value . "'";
				break;
			case 'MEDIUMBLOB':
				$result = "'" . $value . "'";
				break;
			case 'BLOB':
				$result = "'" . $value . "'";
				break;
			case 'LONGBLOB':
				$result = "'" . $value . "'";
				break;
			case 'ENUM':
				$result = "'" . $value . "'";
				break;
			case 'SET':
				$result = "'" . $value . "'";
				break;
			case 'GEOMETRY':
				$result = $value;
				break;
			case 'POINT':
				$result = $value;
				break;
			case 'LINESTRING':
				$result = "'" . $value . "'";
				break;
			case 'POLYGON':
				$result = "'" . $value . "'";
				break;
			case 'MULTIPOINT':
				$result = "'" . $value . "'";
				break;
			case 'MULTILINESTRING':
				$result = "'" . $value . "'";
				break;
			case 'MULTIPOLYGON':
				$result = "'" . $value . "'";
				break;
			case 'GEOMETRYCOLLECTION':
				$result = "'" . $value . "'";
				break;

		}
		
		return $result;
	}

}

?>