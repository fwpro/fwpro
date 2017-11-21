<?php

class App_Model_Applicant extends Zend_Db_Table_Abstract {

  protected $_name = 'tbl_applicant'; // table name
  private $lobjDbAdpt;

  public function init() {
    $this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
  }
  
  public function checkemail($email,$formdata){
  	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
  	$lstrSelect = $lobjDbAdpt->select()
                    ->from(array("a" => "tbl_studentapplication"), array("a.IdApplication"))                   
                    ->where("a.PEmail = ?", $email);
    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    
    $lstrSelect = $lobjDbAdpt->select()
                    ->from(array("a" => "tbl_applicant"), array("a.IdApplicant"))
                    ->where("a.Emaillogin = ?", $email);
    $larrResult1 = $lobjDbAdpt->fetchAll($lstrSelect);
	if(isset($larrResult[0]['IdApplication']) || isset($larrResult1[0]['IdApplicant'])){
	   return false;
	}
	else{
		return true;
	}
  }
  


  public function fnTocheckUniqueEmailinOnline($email){
   
  }
  
  public function fnAgentauth($username, $password) {
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()
                    ->from(array("a" => "tbl_applicant"), array("a.IdApplicant"))
                    ->where("a.Emaillogin = ?", $username)
                    ->where("a.password = ?", md5($password));

    $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
    return $larrResult['IdApplicant'];
  }

  public function fnapplicantexistcheck1($emaillogin){
  	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
  	$lstrSelect = $lobjDbAdpt->select()
		  	->from(array("a" => "tbl_applicant"), array("a.IdApplicant"))
		  	->join(array("b" => "tbl_studentapplication"),'a.IdApplicant = b.IdApplicant',array())
		  	->join(array("c" => "tbl_studentregistration"),'b.PEmail = c.email',array())
		  	->where("a.Emaillogin = ?", $emaillogin);
  	 $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
  	 return $larrResult['IdApplicant'];
  }
  
 public function fngetdetails($extraid){
	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();        
	$lstrSelect =$lstrSelect = $lobjDbAdpt->select()
	->from(array("a" => "tbl_applicant_personal_detail"),array("a.email","a.status","a.ExtraIdField1 AS olExtraIdField1"))
	->joinLeft(array("b" => "tbl_studentapplication"),"b.ExtraIdField1 = '$extraid' ",array("b.Status","b.PEmail","b.ExtraIdField1 AS maExtraIdField1"))
	->orwhere("a.ExtraIdField1 = ?", $extraid)
	->orwhere("b.ExtraIdField1 = ?", $extraid )
	->group('a.status');
	$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
	return $larrResult;
}

  public function fnapplicantexistcheck2($emaillogin){
  	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
  	$lstrSelect = $lobjDbAdpt->select()
		  	->from(array("a" => "tbl_applicant_personal_detail"), array("a.IdApplicant"))
		  	->join(array("b" => "tbl_studentapplication"),'a.IdApplicant = b.IdApplicant',array())
		  	->where("a.email = ?", $emaillogin)
		  	->where("b.PEmail = ?", $emaillogin);
	$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
  	 //	print_r($larrResult);die;
  	 if(isset($larrResult['IdApplicant'])){
  	 	return true;
  	 }
  	 else{
  	 	return false;
  	 }
  }

  public function fnAddNewApplicant($post) {
    unset($post['confirmpassword']);
    $post['password'] = md5($post['password']);
    $this->insert($post);
    $insertId = $this->lobjDbAdpt->lastInsertId('tbl_applicant', 'IdApplicant');
    return $insertId;
  }

  public function changepassword($data) {
    $rand = substr(md5(microtime()), rand(0, 26), 5);
    $chars = "@*%$";
    $spclchar = $chars[rand(0, strlen($chars) - 1)];
    $newpassword = $rand . $spclchar;
    $md5newPass = md5($newpassword);
    //$array['Emaillogin'] =  $data['Emaillogin'];
    $array['password'] = $md5newPass;
    $lstrSelect = $this->lobjDbAdpt->select()
                    ->from(array('a' => 'tbl_applicant'), array("key" => "a.*"))
                    ->where('a.Emaillogin = ?', $data['Emaillogin']);
    $larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
    $where = 'IdApplicant= ' . $larrResult[0]['IdApplicant'];
    $this->update($array, $where);
    return $newpassword;
  }

  public function fnupdateApplicantDetails($applicantId,$status){
  	$db = Zend_Db_Table::getDefaultAdapter();
  	$data['status'] = $status;
  	$where = 'IdApplicant= ' . $applicantId;
  	$db->update('tbl_applicant_personal_detail',$data, $where);
  }

  public function fngetregisterdate($applicantId){
  	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()
                    ->from(array("a" => "tbl_applicant"), array("a.RegisteredDate"))
                    ->joinLeft(array("b" => "tbl_application_config"),'b.ApplicationVal != 0',array('b.ApplicationValidity','b.ApplicationVal'))
                    ->where("a.IdApplicant = ?", $applicantId);
    $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
    return $larrResult;//date('Y-m-d',strtotime($larrResult['RegisteredDate']));

  }

  public function fngetapplicantdetails($idapplicant) {
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()
                    ->from(array("a" => "tbl_applicant_personal_detail"), array("a.*"))
                    ->where("a.IdApplicant = ?", $idapplicant);
    $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
    return $larrResult;
  }

  public function fnupdateapplicantstatus($idapplicant) {
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $formData['status'] = 193;
    $where = 'IdApplicant = ' . $idapplicant;
    $lobjDbAdpt->update('tbl_applicant_personal_detail', $formData, $where);
  }

  public function fngetemailaddress($idapplicant) {
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()
                    ->from(array("a" => "tbl_applicant"), array("a.Emaillogin"))
                    ->where("a.IdApplicant = ?", $idapplicant);

    $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
    return $larrResult['Emaillogin'];
  }

 public function fncheckextraid($extraid){
  	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()
                    ->from(array("b" => "tbl_studentregistration"),array('b.ExtraIdField1','b.email'))
                    ->where("b.ExtraIdField1= ?", $extraid);
	$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
	return $larrResult;
  	
  }


 public function fncheckextraid1($idapplicant,$extraid){
  		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();	
        $lstrSelect = $lobjDbAdpt->select()
                    ->from(array("a" => "tbl_applicant_personal_detail"),array("a.IdApplicant","a.email","a.status","a.ExtraIdField1 AS ExtraIdField"))
                    ->join(array("b" => "tbl_studentapplication"),"a.ExtraIdField1 = b.ExtraIdField1 OR b.ExtraIdField1 = '$extraid' OR a.ExtraIdField1 = '$extraid'",array("b.ExtraIdField1"))
                   	->where("a.ExtraIdField1 = ?", $extraid)
                    ->orwhere("b.ExtraIdField1 = ?", $extraid )
                    ->group('a.IdApplicant');
        $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
        return $larrResult;
  }
  
  public function fnaddapplicant($idapplicant, $lobjFormData) {
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();

    //to find the status of applicant
    $lstrSelect = $lobjDbAdpt->select()
                    ->from(array("a" => "tbl_applicant_personal_detail"), array("a.status"))
                    ->where("a.IdApplicant = ?", $idapplicant);
    $larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
    if ($larrResult['status'] == 191) {
      $lobjFormData['status'] = 192;
    }
    $lobjFormData['DOB'] = date('Y-m-d', strtotime($lobjFormData['DOB']));
    if(isset($lobjFormData['PPIssueDt'])){
    $lobjFormData['PPIssueDt'] = date('Y-m-d', strtotime($lobjFormData['PPIssueDt']));
    }
    if(isset($lobjFormData['PPExpDt'])){
    $lobjFormData['PPExpDt'] = date('Y-m-d', strtotime($lobjFormData['PPExpDt']));
    }
    $lobjFormData['MobileNumber'] = $lobjFormData['MobileCountryCode'] . "-" . $lobjFormData['MobileStateCode'] . "-" . $lobjFormData['MobileNumber'];
    unset($lobjFormData['MobileCountryCode']);
    unset($lobjFormData['MobileStateCode']);

    $lobjFormData['HomeNumber'] = $lobjFormData['HomeCountryCode'] . "-" . $lobjFormData['HomeStateCode'] . "-" . $lobjFormData['HomeNumber'];
    unset($lobjFormData['HomeCountryCode']);
    unset($lobjFormData['HomeStateCode']);

    $lobjFormData['OfficeNumber'] = $lobjFormData['OfficeCountryCode'] . "-" . $lobjFormData['OfficeStateCode'] . "-" . $lobjFormData['OfficeNumber'];
    unset($lobjFormData['OfficeCountryCode']);
    unset($lobjFormData['OfficeStateCode']);
    $where = 'IdApplicant = ' . $idapplicant;
    $lobjDbAdpt->update('tbl_applicant_personal_detail', $lobjFormData, $where);
  }

  public function chekemailExist($email) {
    $lstrSelect = $this->lobjDbAdpt->select()
                    ->from(array('a' => 'tbl_applicant'), array("key" => "a.*"))
                    ->where('a.Emaillogin = ?', $email);
    $larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
    if (empty($larrResult)) {
      return false;
    }
    return true;
  }

  public function getApplicantDetails($id) {
    $idUniversity = 1;
    $result = array();
    $select = $this->lobjDbAdpt->select()
                    ->from(array('a' => 'tbl_config'))
                    ->where('a.idUniversity = ?', $idUniversity);
    $confdet = $this->lobjDbAdpt->fetchAll($select);

    $lstrSelect = $this->lobjDbAdpt->select()
                    ->from(array('b' => 'tbl_applicant_personal_detail'))
                    ->join(array('a' => 'tbl_applicant'), 'a.IdApplicant = b.IdApplicant', array())
                    //->join(array('c' => 'tbl_program'),'c.IdProgram  = b.IdProgram',array())
                    ->join(array('d' => 'tbl_countries'), 'd.idCountry = b.Nationality', array())
                    ->where('b.IdApplicant = ?', $id);
    $personalDetail = $this->lobjDbAdpt->fetchAll($lstrSelect);
    $result[0]['personaldet'] = $personalDetail;
    $result[0]['conf'] = $confdet[0];

    $selectpreffered = $this->lobjDbAdpt->select()
                    ->from(array('a' => 'tbl_applicant_preffered'))
                    ->join(array('b' => 'tbl_intake'), 'b.IdIntake = a.IdIntake')
                    ->join(array('c' => 'tbl_definationms'), 'a.IdProgramLevel = c.idDefinition')
                    ->join(array('d' => 'tbl_program'), 'a.IdProgram = d.IdProgram')
                    ->join(array('e' => 'tbl_branchofficevenue'), 'a.IdBranch = e.IdBranch')
                    ->join(array('f' => 'tbl_scheme'), 'a.IdScheme = f.IdScheme')
                    ->where('a.IdApplicant = ?', $id)
                    ->order("a.IdPriorityNo");
    $preffered = $this->lobjDbAdpt->fetchAll($selectpreffered);
    $result[0]['preferreddet'] = $preffered;

    $qualificationmodelObj = new App_Model_OnlineapplicationQualification();

    $result[0]['qualificationdet'] = $qualificationmodelObj->fngetapplicantQualification($id);
    return $result[0];
  }

  public function getAppdet($Idapplicant) {
    $lstrSelect = $this->lobjDbAdpt->select()
                    ->from(array('a' => 'tbl_applicant'))
                    ->where('a.IdApplicant = ?', $Idapplicant);
    return $this->lobjDbAdpt->fetchRow($lstrSelect);
  }

  public function getIntake($date) {
    $temp = array();
    $lstrSelect = $this->lobjDbAdpt->select()
                    ->from(array('a' => 'tbl_intake'));
    $result = $this->lobjDbAdpt->fetchAll($lstrSelect);
    $registrationdate = date('Y-m-d', strtotime($date));
    $i = 1;
    foreach ($result as $res) {
      $startDate = $res['ApplicationStartDate'];
      $endtDate = $res['ApplicationEndDate'];
      if ($registrationdate >= $startDate && $registrationdate <= $endtDate) {
        $temp[$i] = $res;
      }
      $i++;
    }
    return $temp;
  }

  public function getIntakeList($date) {
    $temp = array();
    $lstrSelect = $this->lobjDbAdpt->select()
                    ->from(array('a' => 'tbl_intake'));
    $result = $this->lobjDbAdpt->fetchAll($lstrSelect);
    $currentdate = $date;
    $i = 0;
    foreach ($result as $res) {
      $startDate = $res['ApplicationStartDate'];
      $endtDate = $res['ApplicationEndDate'];
      if ($currentdate >= $startDate && $currentdate <= $endtDate) {
        $temp[$i] = $res;
        $temp[$i]['key'] = $res['IdIntake'];
        $temp[$i]['value'] = $res['IntakeDesc'];
      }
      $i++;
    }
    return $temp;
  }

  public function fngetcertificate() {
    $lstrSelect = $this->lobjDbAdpt->select()
                    ->from(array('a' => 'tbl_autocredittransfer'), array("key" => "a.IdSpecialization"))
                    ->join(array('b' => 'tbl_specialization'), 'a.IdSpecialization = b.IdSpecialization', array("value" => "b.Description"))
                    ->distinct(true);
    $larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
    return $larrResult;
  }

  public function fnGetDefination($defms) {
    $tem[0]['key'] = '';
    $tem[0]['value'] = 'select';
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('dtms' => 'tbl_definationtypems'), array())
                    ->join(array('dms' => 'tbl_definationms'), 'dms.idDefType = dtms.idDefType',
                            array('key' => 'dms.idDefinition', 'value' => 'dms.DefinitionDesc'))
                    ->where('dtms.defTypeDesc = ?', $defms)
                    ->order('dms.idDefinition');
    $result = $this->fetchAll($select);
    $i = 1;
    foreach ($result->toArray() as $ret) {
      $tem[$i]['key'] = $ret['key'];
      $tem[$i]['value'] = $ret['value'];
      $i++;
    }
    return $tem;
  }

  public function getProgram($IdIntake, $Idprogramlevel) {
     $select = $this->select()->distinct()
                    ->setIntegrityCheck(false)
                    ->joinLeft(array('ib' => 'tbl_intake_branch_mapping'), array())
                    ->joinLeft(array('p' => 'tbl_program'), 'ib.IdProgram = p.IdProgram', array("key" => "p.IdProgram", "value" => "p.ProgramName"))
                    ->where('p.Award = ?', $Idprogramlevel)
                    ->where('ib.IdIntake = ?', $IdIntake)
                    ->group('p.IdProgram');
                    
    $result = $this->fetchAll($select);
    $array = $result->toArray();
    //$array = array_unique($array);
//    for ($e = 0; $e < count($array); $e++) {
//      $duplicate = null;
//      for ($ee = $e; $ee < count($array); $ee++) {
//        if (strcmp($array[$ee]['key'], $array[$e]['key']) === 0) {
//          $duplicate = $ee;
//          break;
//        }
//      }
//      if (!is_null($duplicate))
//        array_splice($array, $duplicate, 1);
//    }
    return $array;
  }

  public function getBranch($IdIntake, $Idprogram) {
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->joinLeft(array('b' => 'tbl_branchofficevenue'), array('b.*'))
                    ->joinLeft(array('ib' => 'tbl_intake_branch_mapping'), 'ib.IdBranch = b.IdBranch', array(''))
                    ->where('ib.IdIntake = ?', $IdIntake)
                    ->where('ib.IdProgram = ?', $Idprogram);
    $result = $this->fetchAll($select);
    return $result->toArray();
  }

  public function getScheme($IdProgram) {
    $lstrSelect = $this->select()
                    ->setIntegrityCheck(false)
                    ->join(array('b' => 'tbl_program'), array())
                    ->join(array('a' => 'tbl_scheme'), 'a.IdScheme = b.IdScheme')
                    ->where('b.IdProgram = ?', $IdProgram);
    $result = $this->fetchAll($lstrSelect);
    $res = $result[0];
    return array_unique($result->toArray());
  }

  public function addPreferredApplicant($data) {
    $lstrTable = "tbl_applicant_preffered";
    $this->lobjDbAdpt->insert($lstrTable, $data);
  }

  public function fnEducationdetaillist($id) { //Function to get the applicant personal details details
    $lstrSelect = $this->lobjDbAdpt->select()
                    ->from(array('a' => 'tbl_applicant'))
                    ->join(array('b' => 'tbl_applicant_personal_detail'), 'b.IdApplicant = a.IdApplicant')
                    ->join(array('c' => 'tbl_program'), 'c.IdProgram  = b.IdProgram')
                    ->join(array('d' => 'tbl_countries'), 'd.idCountry = b.Nationality')
                    ->where('a.IdApplicant = ?', $id);
    $personalDetail = $this->lobjDbAdpt->fetchAll($lstrSelect);
    return $personalDetail;
  }

  public function getapplicantpreferreddetail($idapplicant) {
    $lstrSelect = $this->lobjDbAdpt->select()
                    ->from(array('a' => 'tbl_applicant_preffered'))
                    ->where('a.IdApplicant = ?', $idapplicant)
                    ->order("a.IdPriorityNo");
    $ret = $this->lobjDbAdpt->fetchAll($lstrSelect);
    return $ret;
  }

  public function deletepreffered($idapplicant) {
    $where = $this->lobjDbAdpt->quoteInto('IdApplicant = ?', $idapplicant);
    $this->lobjDbAdpt->delete('tbl_applicant_preffered', $where);
  }

  public function fngetapplicantList() {
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()
                    ->from(array("a" => 'tbl_applicant'), array("key" => "a.IdApplicant", "value" => "Emaillogin"));

    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    return $larrResult;
  }

  public function fngetstudentList() {
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()
                    ->from(array("a" => 'tbl_studentapplication'), array("key" => "a.IdApplication", "value" => "IdApplicant"));

    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    return $larrResult;
  }

  /**
   * Function get Applicants Tab Details
   * @author Vipul
   */

  public function fngetapplicantTabDetails($id) {
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $lstrSelect = $lobjDbAdpt->select()
                    ->from(array('tbl_applicant') ,array("tabValue" => "tab_value"))
 					->where('IdApplicant = ?', $id);
    $larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
    return $larrResult;
  }


  /**
   * Function update Applicants Tab Details
   * @author Vipul
   */

  public function fnupdapplicantTabDetails($id,$tabvalue) {
    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    $formData['tab_value'] = $tabvalue;
    $where = 'IdApplicant = ' . $id;
    $lobjDbAdpt->update('tbl_applicant', $formData, $where);
  }





}

?>
