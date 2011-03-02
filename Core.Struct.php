<?php

class Entity
{
	public $table_name;
	public $class_name;
	public $total_columns;
	public $properties;
	public $to_process;
	
	public function Entity()
	{
		$this->ClearProperties();
	}
	
	public function ClearProperties()
	{
		$this->table_name = '';
		$this->class_name = '';
		$this->to_process = 1;
		$this->properties = array();
	}
	
	public function FillObject($table_name, $class_name, $total_columns, $to_process)
	{
		$this->table_name = $table_name;
		$this->class_name = $class_name;
		$this->total_columns = $total_columns;
		$this->to_process = $to_process;
	}
	
	public function AddProperty($column, $name, $type, $field_type, $key, $is_object, $object_table, $object_value, $object_text)
	{
		$property = new Property();
		$property->FillObject($column, $name, $type, $field_type, $key, $is_object, $object_table, $object_value, $object_text);
		array_push($this->properties, $property);
	}
}

class Property
{
	public $column;
	public $name;
	public $type;
	public $field_type;
	public $key;
	public $is_object;
	public $object_table;
	public $object_value;
	public $object_text;
	

	public function Property()
	{
		$this->ClearProperties();
	}
	
	public function ClearProperties()
	{
		$this->column = '';
		$this->name = '';
		$this->type = '';
		$this->field_type = '';
		$this->key = 0;
		$this->is_object = 0;
		$this->object_table = '';
		$this->object_value = '';
		$this->object_text = '';
	}
	
	public function FillObject($column, $name, $type, $field_type, $key, $is_object, $object_table, $object_value, $object_text)
	{
		$this->column = $column;
		$this->name = $name;
		$this->type = $type;
		$this->field_type = $field_type;
		$this->key = $key;
		$this->is_object = $is_object;
		$this->object_table = $object_table;
		$this->object_value = $object_text;
		$this->object_text = $object_value;
	}
}

class Database
{
	public $text;
	public $value;
	
	public function Database($name)
	{
		$this->text = $name;
		$this->value = $name;
	}
}

?>