<?php
class App_Model_Applicantpersonaldetail extends Zend_Db_Table_Abstract {
  protected $_name = 'tbl_applicant_personal_detail'; // table name
  private $lobjDbAdpt;

  public function init(){
    $this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
  }

  public function addNewapplicantdetail($data){
    $this->insert($data);
  }
}

?>
