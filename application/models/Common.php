<?php
	class App_Model_Common {

		public function fnPagination($larrresult,$page,$lintpagecount) { // Function for pagination
			$paginator = Zend_Paginator::factory($larrresult); //instance of the pagination
			$paginator->setItemCountPerPage($lintpagecount);
			$paginator->setCurrentPageNumber($page);
			return $paginator;
		}
		//marital
		public function fnGetMarital(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"tbl_definationms"),array("key"=>"a.definitionCode","value"=>"a.definitionDesc"))
			->where("a.idDefType=111")
			->order("a.definitionDesc");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}

		//Get Student Id
		public function fnGetStudentId($IdStudent){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
    	   							->from(array("a" => "tbl_studentapplication"),array("StudentId"))
    	   							->where("a.IDApplication = ?",$IdStudent)
    	   							->where("a.Termination = 0")
    	   							->where("a.Active = 1");
			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			return $larrResult['StudentId'];
		}

		//Get Student Name
		public function fnGetStudentNamebyid($IdStudent){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
    	   							->from(array("a" => "tbl_studentapplication"),array("CONCAT(a.FName,' ', IFNULL(a.MName,' '),' ',IFNULL(a.LName,' ')) AS StudentName"))
    	   							->where("a.IDApplication = ?",$IdStudent)
    	   							->where("a.Termination = 0")
    	   							->where("a.Active = 1");
			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			return $larrResult['StudentName'];
		}

		//Get Student Details
		public function fnGetStudentDetailsByid($IdStudent){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
    	   							->from(array("a" => "tbl_studentapplication"),array("CONCAT(a.FName,' ', IFNULL(a.MName,' '),' ',IFNULL(a.LName,' ')) AS StudentName","a.StudentId"))
    	   							->where("a.IDApplication = ?",$IdStudent)
    	   							->where("a.Termination = 0")
    	   							->where("a.Active = 1");
			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			return $larrResult;
		}

		//Get Registration Offer Template
		public function fnGetRegistrationOffer(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
    	   							->from(array("a"=>"tbl_emailtemplate"))
       								->join(array("b" => "tbl_definationms"),"a.idDefinition = b.idDefinition",array(""))
       								->where("b.DefinitionDesc LIKE ?","%"."Student Registration");
       		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
       		return $larrResult;
		}

			//Get Registration Email Template
		public function  fnGetRegistrationEmailTemplate(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
    	   							->from(array("a"=>"tbl_emailtemplate"))
       								->join(array("b" => "tbl_definationms"),"a.idDefinition = b.idDefinition",array(""))
       								->where("b.DefinitionDesc LIKE ?","%"."Portal Login Template");
       		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
       		return $larrResult;
		}

		//Function To Get SMTP Settings From Initial Config
		public function fnGetInitialConfigDetails($iduniversity){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
       								->from(array("a"=>"tbl_config"),array("a.SMTPServer","a.SMTPUsername","a.SMTPPassword","a.SMTPPort","a.SSL","a.DefaultEmail") )
       								->where("a.idUniversity = ?",$iduniversity);
			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			return $larrResult;
		}

		//Get List Of States From Country's Id
		public function fnGetCountryStateList($idCountry){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   		$lstrSelect = $lobjDbAdpt->select()
					 				 ->from(array("a"=>"tbl_state"),array("key"=>"a.idState","value"=>"StateName","name"=>"StateName"))
					 				 ->where("a.idCountry = ?",$idCountry)
					 				 ->where("a.Active = 1")
					 				 ->order("a.StateName");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		public function fnGetCityList($lintidState){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
				 				 ->from(array("a"=>"tbl_city"),array("key"=>"a.idCity","value"=>"a.CityName","name"=>"a.CityName"))
				 				  ->where("a.idState= ?",$lintidState)
				 				 ->where("a.Active = 1")
				 				 ->order("a.CityName");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		//Get Countries List
		public function fnGetCountryList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   		$lstrSelect = $lobjDbAdpt->select()
					 				 ->from(array("a"=>"tbl_countries"),array("key"=>"a.idCountry","value"=>"CountryName"))
					 				 ->where("a.Active = 1")
					 				 ->order("a.CountryName");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		//Get School Namres

	public function fnGetSchoolList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   		$lstrSelect = $lobjDbAdpt->select()
					 				 ->from(array("a"=>"tbl_schoolmaster"),array("key"=>"a.idSchool","value"=>"SchoolName"))
					 				 ->where("a.Active = 1")
					 				 ->order("a.SchoolName");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}

		//Get All Active Student Names List
		public function fnGetAllActiveStudentNamesList() {
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("a"=>"tbl_studentapplication"),array("key"=>"a.IDApplication","value"=>"CONCAT(a.fName,' ', IFNULL(a.mName,' '),' ',IFNULL(a.lName,' '))") )
					 				->where("a.Active = 1")
					  				->where("a.Termination = 0");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}

		//Get All Active Student Ids List
		public function fnGetAllActiveStudentIdsList() {
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   		$lstrSelect = $lobjDbAdpt->select()
       								->from(array("a"=>"tbl_studentapplication"),array("key"=>"a.IDApplication","value"=>"a.StudentId") )
					 				->where("a.Active = 1")
					  				->where("a.Termination = 0");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}

		//Get State List
		public function fnGetStateList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	   		$lstrSelect = $lobjDbAdpt->select()
					 				 ->from(array("a"=>"tbl_state"),array("key"=>"a.idState","value"=>"StateName","name"=>"StateName"))
					 				 ->where("a.Active = 1")
					 				 ->order("a.StateName");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}

		public function fnGetActiveUnitList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"R_UNIT"),array("key"=>"a.UNIT_ID","value"=>"a.NAMA_UNIT","name"=>"a.NAMA_UNIT"))
			->join(array("b"=>"STRUKTUR_ORG"),"a.UNIT_ID=b.UNIT_ID")
			->where("b.Sts_Aktif = 1")
			->order("a.NAMA_UNIT");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		public function fnGetAllUnitList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"R_UNIT"),array("key"=>"a.UNIT_ID","value"=>"a.NAMA_UNIT","name"=>"a.NAMA_UNIT"))
			->order("a.NAMA_UNIT");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
	public function fnGetFacultyList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"R_UNIT"),array("key"=>"a.UNIT_ID","value"=>"a.NAMA_UNIT","name"=>"a.NAMA_UNIT"))
			->join(array("b"=>"STRUKTUR_ORG"),"a.UNIT_ID=b.UNIT_ID")
			->where("b.Sts_Aktif = 1")
			->where("a.status = F")
			->order("a.NAMA_UNIT");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		public function fnGetProgramStudyList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"R_UNIT"),array("key"=>"a.UNIT_ID","value"=>"a.NAMA_UNIT","name"=>"a.NAMA_UNIT"))
			->join(array("b"=>"STRUKTUR_ORG"),"a.UNIT_ID=b.UNIT_ID")
			->where("b.Sts_Aktif = 1")
			->where("a.status = P")
			->order("a.NAMA_UNIT");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}

		public function fnGetRectorDecreeList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"R_SKR"),array("key"=>"a.SKR_ID","value"=>"a.NO_SKR","name"=>"a.NO_SKR"))
			->where("a.Sts_Aktif = 1")
			->order("a.NO_SKR");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		
		public function fnGetGolonganEmpList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"R_GOLONGAN"),array("key"=>"a.GOL_ID","value"=>"a.nm_gol","name"=>"a.nm_gol"))
			->where("a.status = 1")
			->order("a.urut");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		
		public function fnGetGolonganDLBList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"R_GOLONGAN"),array("key"=>"a.GOL_ID","value"=>"a.nm_gol","name"=>"a.nm_gol"))
			->where("a.status != 1")
			->order("a.urut");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		
		public function fnGetAcademicLevelEmpList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"R_PANGKAT"),array("key"=>"a.GOL_ID","value"=>"a.nm_pangkat","name"=>"a.nm_pangkat"))
			->where("a.sts_aktif = 1")
			->order("a.urut");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		
		
		
		//Function To Reset The Array ie., from name to key
		public function fnResetArrayFromNamesToValues($OrginialArray){
			$larrNewArr = array();
			$OrgnArray = @array_values($OrginialArray);
			for($lintI=0;$lintI<count($OrgnArray);$lintI++){
				$larrNewArr[$lintI]["value"] = $OrgnArray[$lintI]["name"];
				$larrNewArr[$lintI]["key"] = $OrgnArray[$lintI]["key"];
			}
			return $larrNewArr;
		}

		//Function To Reset The Array ie., from Value to Name
		public function fnResetArrayFromValuesToNames($OrginialArray){
			$larrNewArr = array();
			$OrgnArray = @array_values($OrginialArray);
			for($lintI=0;$lintI<count($OrgnArray);$lintI++){
				$larrNewArr[$lintI]["name"] = $OrgnArray[$lintI]["value"];
				$larrNewArr[$lintI]["key"] = $OrgnArray[$lintI]["key"];
			}
			return $larrNewArr;
		}

		public function fngetLanguage($lintIdCountry){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
       								->from(array("a"=>"tbl_countries"),array("a.DefaultLanguage"))
       								->join(array('b' => 'tbl_definationms'),'a.DefaultLanguage = b.idDefinition',array('b.DefinitionDesc'))
       								->where('a.idCountry = ?',$lintIdCountry)
       								->order("b.DefinitionDesc");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}


		//Function To Reset The Array ie., from Value to Name Along With Status
		public function fnResetArrayFromValuesToNamesWithStatus($OrginialArray){
			$larrNewArr = array();
			$OrgnArray = @array_values($OrginialArray);
			for($lintI=0;$lintI<count($OrgnArray);$lintI++){
				$larrNewArr[$lintI]["key"] = $OrgnArray[$lintI]["key"];
				$larrNewArr[$lintI]["name"] = $OrgnArray[$lintI]["value"];
				$larrNewArr[$lintI]["Status"] = $OrgnArray[$lintI]["Status"];
			}
			return $larrNewArr;
		}

		public function fnGetRoleDetails() {
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$select = $lobjDbAdpt->select()
				->join(array('a' => 'tbl_definationms'),array('idDefinition'))
				->join(array('b' => 'tbl_definationtypems'),'a.idDefType = b.idDefType',array('b.idDefType'))
				->where('b.defTypeDesc = "Role"')
				->where('a.Status = 1')
                ->where('b.Active = 1')
                ->order("b.defTypeDesc");
			$result = $lobjDbAdpt->fetchAll($select);
			return $result;
		}

		public function fnGetdocumentuploadDetails() {
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$select = $lobjDbAdpt->select()
				->join(array('a' => 'tbl_definationms'),array('idDefinition'))
				->join(array('b' => 'tbl_definationtypems'),'a.idDefType = b.idDefType',array('b.idDefType'))
				->where('b.defTypeDesc = "Documents Upload Type"')
				->where('a.Status = 1')
                ->where('b.Active = 1')
                ->order("b.defTypeDesc");
			$result = $lobjDbAdpt->fetchAll($select);
			return $result;
		}

		public function fnGetRoleName($idrole){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
       								->from(array("a"=>"tbl_definationms"),array("a.DefinitionDesc") )
       								->where('a.idDefinition = ?',$idrole);
			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			return $larrResult;
		}

		public function fnGetStaff($idStaff){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
       								->from(array("a"=>"tbl_staffmaster"),array("a.StaffType","a.IdCollege") )
       								->where('a.IdStaff = ?',$idStaff);
       								
			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			return $larrResult;
		}

		public function fnGetUniversity($idCollege){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
       								->from(array("a"=>"tbl_collegemaster"),array("a.AffiliatedTo"))
       								->where('a.IdCollege = ?',$idCollege);
			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			return $larrResult;
		}


		public function fnGetStaffDetails($idStaff){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
       								->from(array("a"=>"tbl_staffmaster"),array("a.StaffType","a.IdCollege"))
       								->where('a.IdStaff = ?',$idStaff);
			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			return $larrResult;
		}

		public function fnGetCollegeDetails($idCollege){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
       								->from(array("a"=>"tbl_collegemaster"),array("a.AffiliatedTo"))
       								->where('a.IdCollege = ?',$idCollege);
			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			return $larrResult;
		}

		public function fnGetApplicationEmailTemplate(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
    	   							->from(array("a"=>"tbl_emailtemplate"))
       								->join(array("b" => "tbl_definationms"),"a.idDefinition = b.idDefinition",array(""))
       								->where("b.DefinitionDesc LIKE ?","%"."Student Approval");
       		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
       		return $larrResult;
		}
		
		public function fnGetReligionList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect=$lobjDbAdpt->select()
			->from(array("a"=>"tbl_definationms"),array("key"=>"a.idDefinition","value"=>"a.DefinitionCode"))
			->join(array("b"=>"tbl_definationtypems"),"a.idDefType = b.idDefType AND defTypeDesc='Religion'")
			->where("a.Status = 1")
			->order("a.DefinitionDesc");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		
		public function fnGetBloodGroupList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect=$lobjDbAdpt->select()
			->from(array("a"=>"tbl_definationms"),array("key"=>"a.idDefinition","value"=>"a.DefinitionCode"))
			->join(array("b"=>"tbl_definationtypems"),"a.idDefType = b.idDefType AND defTypeDesc='Blood Group'")
			->where("a.Status = 1")
			->order("a.DefinitionDesc");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		
		public function fnGetEmpList(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"tbl_employeeProfile"),array("key"=>"a.idemp","value"=>"a.NAMA","name"=>"a.NAMA","nama"=>"a.NAMA","alamat"=>"a.ALAMAT","geldep"=>"a.GEL_DEP","gelbel"=>"a.GEL_BEL"))
			->where("a.sts_aktif = 1")
			->order("a.nama");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		public function fngetEmpDetails() { //Function to get the user details
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$select = $lobjDbAdpt->select()
			->from(array('emp'=>$this->_name))
			->where('emp.sts_aktif = 1')
			->order('emp.nama ASC');
			 
			$result = $lobjDbAdpt->fetchAll($select);
			//$result = $this->fetchAll('UserStatus = 1',"loginName ASC");
			return $result;
		}
		
		public function fnviewEmp($iduser) { //Function for the view user
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$select = $lobjDbAdpt->select()
			->from(array('emp'=>'tbl_employeeProfile'))
			->join(array('bio'=>'BIOSDM'),'bio.idEmp=emp.idEmp')
			->joinLeft(array('unit'=>'R_UNIT'),'bio.unit_id=unit.unit_id')
			->joinLeft(array('gol'=>'R_GOLONGAN'),'gol.gol_id=bio.gol_id')
			->where('emp.idemp = ?',$iduser)
			->where('bio.STS_AKTIF="00"')	;
			$result = $lobjDbAdpt->fetchAll($select);
			return $result[0];
		}
		
		public function fnEmpIdToNik($empid) { //Function to get the user details
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$select = $lobjDbAdpt->select()
			->from(array('emp'=>"BIOSDM"))
			->where('emp.sts_aktif = "00"')
			->where('emp.idEmp=?',$empid);		
			$result = $lobjDbAdpt->fetchAll($select);
			$nikid = $result[0]['NIK_ID'];
			return $nikid;
		}
		public function fnEmpIdToHome($empid) { //Function to get the user details
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$select = $lobjDbAdpt->select()
			->from(array('emp'=>"BIOSDM"))
			->where('emp.sts_aktif = "00"')
			->where('emp.idEmp=?',$empid);
			$result = $lobjDbAdpt->fetchAll($select);
			$nikid = $result[0]['KD_HOMEBASE'];
			return $nikid;
		}
		public function fnNikIdToEmpId($nikid) { //Function to get the user details
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$select = $lobjDbAdpt->select()
			->from(array('emp'=>"BIOSDM"))
			->where('emp.sts_aktif = "00"')
			->where('emp.NIK_ID=?',$nikid);
			$result = $lobjDbAdpt->fetchAll($select);
			$empid = $result[0]['idEmp'];
			return $empid;
		}
		public function fnSearchEmp($post = array()) { //Function for searching the user details
			$db = Zend_Db_Table::getDefaultAdapter();
			//$field7 = "UserStatus = ".$post["field7"];
			$select = $db->select()
			//->setIntegrityCheck(false)
			->from(array('emp'=>'tbl_employeeProfile'),array("key"=>"emp.idemp",'nama'=>'emp.nama',
					'alamat'=>'emp.alamat',
					'gelbel'=>'emp.gel_bel',
					'geldep'=>'emp.gel_dep'))
					->join(array('bio'=>'BIOSDM'),'bio.idEmp=emp.idEmp')
					->where('bio.nik like "%" ? "%"',$post['field3'])
					->where('emp.f_Nama like  "%" ? "%"',$post['field2'])
					->where('emp.m_Nama like "%" ? "%"',$post['field4'])
					->where('emp.l_Nama like "%" ? "%"',$post['field6'])
					->order("emp.nama");
			$result = $db->fetchAll($select);
			//echo var_dump($result);
			return $result;
		}
		public function fnGetFamilyRelation(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"tbl_definationms"),array("key"=>"a.definitionCode","value"=>"a.definitionDesc"))
			->where("a.idDefType=90")
			->order("a.definitionDesc");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		public function fnGetEductionLevel(){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"R_JJGDIDIK"),array("key"=>"a.JJG_ID","value"=>"CONCAT(a.NM_JJG,' (',a.KD_JJG,')')","name"=>"a.NM_JJG"))
			->order("a.urut");
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			return $larrResult;
		}
		
		public function fnGetDecisionMaker($home){
			
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"R_UNIT"),array("unit"=>"a.NAMA_UNIT","key"=>"bio.NIK","value"=>"CONCAT(emp.GEL_DEP,' ',emp.NAMA,',',emp.GEL_BEL,'-',a.NAMA_UNIT)","name"=>"CONCAT(emp.GEL_DEP,' ',emp.NAMA,',',emp.GEL_BEL,'-',a.NAMA_UNIT)"))
			->join(array("org"=>"EMP_ORG"),'a.UNIT_ID=org.UNIT_ID',array())
			->join(array("bio"=>"BIOSDM"),'org.NIK_id=bio.NIK_ID',array())
			->join(array("emp"=>"tbl_employeeProfile"),'emp.idEmp=bio.idEmp',array())
			->where('bio.STS_AKTIF="00"')
			->where('a.KD_HOME=?',$home)
			->where('a.TTD="1"')
			->where('org.sts_aktif="1"')
			->where('org.POSISI_EMP="1"');
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			//echo $lstrSelect;
			return $larrResult;
		}
		
		
	}