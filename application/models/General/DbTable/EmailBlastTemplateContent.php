<?php 
class App_Model_General_DbTable_EmailBlastTemplateContent extends Zend_Db_Table_Abstract
{
	protected $_name = 'email_blast_tmpl_content';
	protected $_primary = "ebtc_id";
	
	
	
	public function insert(array $data) {
		
		return parent::insert($data);
	}
	
	
	public function update(array $data, $where){
		
		return parent::update($data, $where);
	}
	
	
	public function deleteData($id){
		$this->delete($this->_primary .' =' . (int)$id);
	}
}
?>