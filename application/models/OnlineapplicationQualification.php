<?php 

class App_Model_OnlineapplicationQualification extends Zend_Db_Table_Abstract {
	protected $_name = 'tbl_applicant_qualification'; // table name
        private $lobjDbAdpt;
        private $lobjsubjectdetail;
  
	/**
	 * (non-PHPdoc)
	 * @see Zend_Db_Table_Abstract::init()
	 */
  public function init(){
    $this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $this->lobjsubjectdetail = new App_Model_Onlineapplicationsubjectdetail();
  }
  
  public function fninsert($data) {
  	$this->insert($data);
  	$insertId = $this->lobjDbAdpt->lastInsertId('tbl_applicant_qualification','IdApplicationQualification');	
	return $insertId;
  }
  
  public function fncheckspecialtreatment($idqualification){
  	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
  	$lstrSelect = $lobjDbAdpt->select()
  			->from(array('a'=>'tbl_qualificationmaster'),array('a.SpecialTreatment'))
  			->where('a.IdQualification = ?',$idqualification);
        $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);        
  	return $larrResult['SpecialTreatment'];	
  }

  public function fnupdate($data,$Id) {
    $where = 'IdApplicationQualification = '.$Id;
	$this->update($data,$where);
  }

  public function fndeletequalification($Id,$IdApplicant) {
       $where = 'IdApplicationQualification NOT IN ('.implode(",",$Id).') AND IdApplicant ='.$IdApplicant;
        $this->lobjDbAdpt->delete($this->_name, $where);
  }

  public function fngetapplicantQualification($IdApplicant) {
      $lstrSelect = $this->lobjDbAdpt->select()
		->from(array('a'=>'tbl_applicant_qualification'),array('a.IdApplicationQualification','a.IdApplicant','a.yearobtained','a.instituteaddress1','a.instituteaddress2','a.zipcode','a.resulttotal'))
		->join(array('b' => 'tbl_definationms'),'a.IdPlaceObtained =b.idDefinition',array('b.DefinitionDesc as PlaceObtained','a.IdPlaceObtained as IdPlaceObtained'))
                ->join(array('c' => 'tbl_qualificationmaster'),'a.IdEducationalLevel =c.IdQualification',array('c.QualificationLevel as QualificationLevel','a.IdEducationalLevel as IdEducationalLevel'))
                ->join(array('d' => 'tbl_definationms'),'a.IdResultItem =d.idDefinition',array('d.DefinitionDesc as ResultItem','a.IdResultItem as IdResultItem'))
                ->join(array('e' => 'tbl_specialization'),'a.certificatename = e.IdSpecialization',array('e.Specialization as Certificate','a.certificatename as IdCertificate'))
                ->join(array('f' => 'tbl_institution'),'a.IdInstitute = f.idInstitution',array('f.InstitutionName as Institution','a.IdInstitute as IdInstitute'))
                ->join(array('g' => 'tbl_countries'),'a.country = g.idCountry',array('g.CountryName as Country','a.country as IdCountry'))
                ->join(array('h' => 'tbl_state'),'a.state = h.idState',array('h.StateName as State','a.state as IdState'))
                ->join(array('i' => 'tbl_city'),'a.city = i.idCity',array('i.CityName as City','a.city as IdCity'))
//		->where('b.Status = 1')
//		->where('d.Status = 1')
                ->where('a.IdApplicant = ?',$IdApplicant)
		->order("a.IdApplicant");
     $larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
     $subjectdetails = array();
     $rowid = 0;
                foreach($larrResult as $item) {
                    //$subjectdetails[$item['IdApplicationQualification']][] = $this->lobjsubjectdetail->fngetsubjectdetails($item['IdApplicationQualification']);
                    $larrResult[$rowid]['subjectdetails'] = $this->lobjsubjectdetail->fngetsubjectdetails($item['IdApplicationQualification']);
                    $rowid++;
                }
     return $larrResult;
      
  }
  
}