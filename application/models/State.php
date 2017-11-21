<?php
class App_Model_State extends Zend_Db_Table {
	
	protected $_name = 'tbl_state';
	

	public function fnGetStateDetails() { // Function to fetch the State  for dropdown
        	$db = Zend_Db_Table::getDefaultAdapter();
			$sql = $db->select()->from('tbl_state',array('key' => 'idState','value' => 'StateName'))->where('Active=?',1)->order('StateName');
			$result = $db->fetchAll($sql); 
			return $result;
		}
        
	public function fnSearchState($post = array()) {
		 $db = Zend_Db_Table::getDefaultAdapter();
		    $field7 = $post["field3"];
		    $select = $this->select()
			->setIntegrityCheck(false)  	
			->join(array('a' => 'tbl_countries'),array('idCountry'))
			->where('a.CountryName like  ? "%"',$post['field3']);
			//->where($field7);
			//echo $select;exit();
		$result = $this->fetchAll($select);
		
		return $result->toArray();
	}
	
	public function fnName($idCountry)
	{
	    $db = Zend_Db_Table::getDefaultAdapter();
	    $select =$db->select('CountryName')
	            ->from(array('a' => 'tbl_countries'),'CountryName')
				->where('a.idCountry='.$idCountry);
	$result = $db->fetchRow($select);
	return $result;
    }
	
    
    public function fnAddState($insertData) {
		return $this->insert($insertData);
	}
	
   public function fnUpdateState($UpdateData,$idState) { //Function for updating the advancereceipt
 	 $where = "idState = '$idState'";
	//print_r($updateData);exit();
	$this->update($UpdateData,$where);
    }
	
	public function fnGetstatedetailslist($idCountry) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = "SELECT a.CountryName,b.* ";
		$select .=" FROM tbl_countries a, tbl_state b";
		$select .=" WHERE a.idCountry = b.idCountry and a.Active ='1' and b.Active ='1'";
		$select .=" AND b.idCountry =".$idCountry;
		//echo $select;exit();$idCountry
		$result = $db->fetchAll($select);
		
		return $result;
	}
	
    public function fnstateinfodtl($idCountry,$idState) {
    //fuction for updating in the grid
        $result = $this->fetchRow( "idCountry = '$idCountry' and idState = '$idState'") ;
        return $result->toArray();
    }
        
	public function fnGetCurrencies(){
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'tbl_Countries'),array('idCountry','CurrencyShortName'))
                        ->group('CurrencyShortName');
	return $this->fetchAll($select);		
	}    
       

	public function fnGetCurrency($countryid){
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'tbl_Countries'),array('idCountry','CurrencyShortName'))
			->where('idCountry = '.$countryid);			
	return $this->fetchAll($select);		
	} 

}
