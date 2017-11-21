<?php

class App_Model_Onlineapplicationsubjectdetail extends Zend_Db_Table_Abstract {
	protected $_name = 'tbl_applicant_subject_detail'; // table name
	private $lobjDbAdpt;

	/**
	 * (non-PHPdoc)
	 * @see Zend_Db_Table_Abstract::init()
	 */
	public function init(){
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}

	public function fninsert($data) {
		$this->insert($data);
	}

	public function fngetsubjectdetails($IdAppQualificationId) {
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array('a'=>'tbl_applicant_subject_detail'),array('a.IdApplicationSubjectDetail','a.IdApplicationQualification'))
		->join(array('b' => 'tbl_subject'),'a.IdSubject =b.IdSubject',array('b.SubjectName as Subject','b.SubjectCode AS SubjectCode','a.IdSubject as IdSubject'))
		->join(array('c' => 'tbl_subjectgradepoint'),'a.IdSubjectGrade =c.Idsubjectgradepoint',array('c.subjectgrade as SubjectGrade','a.IdSubjectGrade as IdSubjectGrade'))
		->where('a.IdApplicationQualification = ?',$IdAppQualificationId)
		->order("a.IdApplicationQualification");
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	public function fndeleteSubjectDetailMappings($Id) {
		$where = $this->lobjDbAdpt->quoteInto('IdApplicationQualification = ?', $Id);
		$this->lobjDbAdpt->delete($this->_name, $where);
	}

}