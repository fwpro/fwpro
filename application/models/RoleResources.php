<?php
/**
 * RoleResources
 * 
 * @author Arun
 * @version 
 */

class App_Model_RoleResources extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_name = 'tbl_roleresources';
    
	public function fngetAllRows()
    {
    	$lobjselectsql = $this->select()
    					->setIntegrityCheck(false)
    					->from(array('role'=>'tbl_definationms'),'role.idDefinition as idRoles')
    					->join(array('rlrc'=>'tbl_roleresources'),'role.idDefinition = rlrc.idRoles')
    					->join(array('res'=>'tbl_resources'),'rlrc.idResources = res.idResources',array('res.Module','res.Controller','res.Action'));
    	$lobjresultset = $this->fetchAll($lobjselectsql);
    	return $lobjresultset->toArray();
    }     
}
