<?php

class App_Model_Definitiontype extends Zend_Db_Table {

  protected $_name = 'tbl_definationtypems';

  public function fnPagination($larrresult, $page, $lintpagecount) { // Function for pagination
    $paginator = Zend_Paginator::factory($larrresult); //instance of the pagination
    $paginator->setItemCountPerPage($lintpagecount);
    $paginator->setCurrentPageNumber($page);
    return $paginator;
  }

  public function fnGetDefinationMs($defms) {
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('dtms' => 'tbl_definationtypems'), array())
                    ->join(array('dms' => 'tbl_definationms'), 'dms.idDefType = dtms.idDefType', array('key' => 'dms.idDefinition', 'value' => 'dms.DefinitionDesc'))
                    ->where('dtms.defTypeDesc = ?', $defms)
                    ->where('dms.Status = 1')
                    ->where('dtms.Active = 1')
                    ->order('dms.DefinitionDesc');
    $result = $this->fetchAll($select);
    return $result->toArray();
  }
  
  public function fnGetStudentStatusCT($defms) {
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('dtms' => 'tbl_definationtypems'), array())
                    ->join(array('dms' => 'tbl_definationms'), 'dms.idDefType = dtms.idDefType', array('key' => 'dms.idDefinition', 'value' => 'UCASE(dms.DefinitionDesc)'))
                    ->where('dtms.defTypeDesc = ?', $defms)
                    ->where('dms.Status = 1')
                    ->where('dtms.Active = 1')
                    ->where('dms.idDefinition IN (92,248,253)')
                    ->order('dms.DefinitionDesc');
    $result = $this->fetchAll($select);
    return $result->toArray();
  }

  //----------------
  public function fnGetGradeEntry($defm) {
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('a' => 'tbl_definationms'), array())
                    ->join(array('b' => 'tbl_definationtypems'), 'a.idDefType=b.idDefType', array("key" => "a.idDefinition", "value" => "a.DefinitionCode"))
                    ->where('a.Status = 1')
                    ->where('b.Active = 1')
                    ->where('b.defTypeDesc= "Grade"', $defm)
                    ->order("b.defTypeDesc");

    $result = $this->fetchAll($select);
    return $result->toArray();
  }

  //---------------

  public function fnGetDefinations($defms) {
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('dtms' => 'tbl_definationtypems'), array())
                    ->join(array('dms' => 'tbl_definationms'), 'dms.idDefType = dtms.idDefType')
                    ->where('dtms.defTypeDesc = ?', $defms)
                    ->where('dms.Status = 1')
                    ->where('dtms.Active = 1')
                    ->order('dms.DefinitionDesc');
    $result = $this->fetchAll($select);
    return $result->toArray();
  }
  
  
  public function fnGetProfilestatus($defms) {
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('dtms' => 'tbl_definationtypems'), array(),array())
                    ->join(array('dms' => 'tbl_definationms'),'dms.idDefType = dtms.idDefType',array('key'=>'dms.idDefinition','value'=>'DefinitionDesc'))
                    ->where('dtms.defTypeDesc = ?', $defms)
                    ->where('dms.Status = 1')
                    ->where('dtms.Active = 1')
                    ->order('dms.DefinitionDesc');
    $result = $this->fetchAll($select);
    return $result->toArray();
  }
  
  public function fnGetDefinationsStatus($defms) {
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('dtms' => 'tbl_definationtypems'), array(), array())
                    ->join(array('dms' => 'tbl_definationms'), 'dms.idDefType = dtms.idDefType', array('key' => 'dms.idDefinition', 'value' => 'dms.DefinitionDesc'))
                    ->where('dtms.defTypeDesc = ?', $defms)
                    ->where('dms.Status = 1')
                    ->where('dtms.Active = 1')
                    ->order('dms.DefinitionDesc');
    $result = $this->fetchAll($select);
    return $result->toArray();
  }

  public function fnGetDefinationdata($defms) {
    $select = $this->select()
                    ->from(array('dms' => 'tbl_definationtypems'))
                    ->where('dms.defTypeDesc = ?', $defms);
    $result = $this->fetchAll($select);
    return $result->toArray();
  }

  public function fnSearchRoles($defms, $post = array()) {
    $field7 = "dms.Status = " . $post["field7"];
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('dtms' => 'tbl_definationtypems'), array())
                    ->join(array('dms' => 'tbl_definationms'), 'dms.idDefType = dtms.idDefType')
                    ->where('dtms.defTypeDesc = ?', $defms)
                    ->where('dtms.defTypeDesc like "%" ? "%"', $post['field3'])
                    ->where($field7)
                    ->order('dms.idDefinition');
    $result = $this->fetchAll($select);
    return $result->toArray();
  }

  public function fnGetDefinationMs2($defms) {
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('dtms' => 'tbl_definationtypems'), array())
                    ->join(array('dms' => 'tbl_definationms'), 'dms.idDefType = dtms.idDefType',
                            array('key' => 'dms.DefinitionCode', 'value' => 'dms.DefinitionDesc'))
                    ->where('dtms.defTypeDesc = ?', $defms)
                    ->order('dms.idDefinition');
    $result = $this->fetchAll($select);
    return $result->toArray();
  }

  public function fnGetDefinationTypeString($idDefType) {
    $db = Zend_Db_Table::getDefaultAdapter();
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from('tbl_definationtypems')
                    ->where('idDefType = ?', $idDefType);
    $result = $db->fetchRow($select);
    return $result;
  }

  public function getLanguageDetailms() {
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('a' => 'tbl_definationms'), array('idDefType'))
                    ->join(array('b' => 'tbl_definationtypems'), 'a.idDefType = b.idDefType')
                    ->where('b.defTypeDesc = ?', "Languages");
    $result = $this->fetchAll($select);
    return $result->toArray();
  }

  public function fnaddAwardLevel($formdata) {
    $definitiontypetable = new Zend_Db_Table('tbl_definationms');
    if ($definitiontypetable->insert($formdata)) {
      return true;
    }
    return false;
  }

  public function fetchDetailAward($id) {
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()->from(array('a' => 'tbl_definationms'))->where('a.idDefinition = ?', $id);
    $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
    return $larrResult;
  }

  public function fnupdateAwardLevel($id, $formData) {
    $where = 'idDefinition = ' . $id;
    $db = Zend_Db_Table::getDefaultAdapter();
    $db->update('tbl_definationms', $formData, $where);
  }

  public function fnGetDefinationNameString($idDefType) {
    $db = Zend_Db_Table::getDefaultAdapter();
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from('tbl_definationms')
                    ->where('idDefinition = ?', $idDefType);
    $result = $db->fetchRow($select);
    return $result;
  }

}
