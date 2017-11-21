<?php

class App_Model_DefModel 
{

	public function init()
	{
		
	}

	public function select($sql)
	{
		$db =  Zend_Db_Table::getDefaultAdapter();		
		return $db->fetchAll($sql);
	}
	
	public function selectrow($sql)
	{
		$db =  Zend_Db_Table::getDefaultAdapter();
		return $db->fetchRow($sql);
	}
	
	public function insert($table,$bind)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->insert($table,$bind);		
	}
	
	public function update($table,$bind,$where)
	{
		$db =  Zend_Db_Table::getDefaultAdapter();		
		return $db->update($table,$bind,$where);		
	}	
	
	public function lastID()
	{
		$db =  Zend_Db_Table::getDefaultAdapter();
		return $db->lastInsertId();
	}
	
	public function showColumns($table)
	{
		$db =  Zend_Db_Table::getDefaultAdapter();
		return $db->describeTable($table);
	}		
}