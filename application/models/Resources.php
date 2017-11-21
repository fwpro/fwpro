<?php
/**
 * Resources
 * 
 * @author Arun
 * @version 
 */

class App_Model_Resources extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'tbl_resources';
    
    public function init()
    {
    	$this->lobjdb = Zend_Db_Table::getDefaultAdapter();
    }
    
	public function fngetAllRows()
    {
    	$lobjresultset = $this->fetchAll();
    	return $lobjresultset->toArray();
    }     
    
    public function fngetControllers()
    {
    	$lstrselect = $this->lobjdb->select()
    						->from(array('res'=>'tbl_resources'),array("res.RouteName"))
    						->group("res.RouteName"); 
    			
    	$lobjresultset = $this->lobjdb->fetchAll($lstrselect);
    	
    	return $lobjresultset;
    }
    
}
