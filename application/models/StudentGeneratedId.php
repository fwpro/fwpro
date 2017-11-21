<?php

class App_Model_StudentGeneratedId extends Zend_Db_Table_Abstract {
    protected $_name = 'tbl_student_generated_id';
    private $lobjprogrammodel;
    private $lobjintakemodel;
    private $lobjDbAdpt;

    public function init() {
        $this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
        $this->fnsetObj();
    }
    
    private function fnsetObj(){
        $this->lobjprogrammodel = new GeneralSetup_Model_DbTable_Program();
        $this->lobjintakemodel = new GeneralSetup_Model_DbTable_Intake();
    }
    
    public function fninsert($data) {
    	$this->insert($data);
    }


    public function fngetseqno($lstrresetvar,$lintidprogram="",$lintintakeid="") {
        switch($lstrresetvar) {
            case "program":
                $lobjprogram = $this->lobjprogrammodel->fnfetchProgramShortName($lintidprogram);
                $lstrshortname = $lobjprogram[0]['ShortName'];
                $lobjresult = $this->fetchAll("p = '$lstrshortname' AND iid = ''");
                $lobjresult = $lobjresult->toArray();
                if(count($lobjresult) == 0) {
                	return 1;
                }
                else {
                	return (intval($lobjresult[count($lobjresult) -1 ]['seqno']) + 1);	
                }
                break;
            case "intake":
                $lobjintake = $this->lobjintakemodel->fnfetchIntakeCode($lintintakeid);
                $lstrintakeid = $lobjintake[0]['IntakeId'];
                $lobjresult = $this->fetchAll("p = '' AND iid = '$lstrintakeid'");
                return (count($lobjresult) + 1);
                break;
            case "progintake":
                $lobjintake = $this->lobjintakemodel->fnfetchIntakeCode($lintintakeid);
                $lstrintakeid = $lobjintake[0]['IntakeId'];
                $lobjprogram = $this->lobjprogrammodel->fnfetchProgramShortName($lintidprogram);
                $lstrshortname = $lobjprogram[0]['ShortName'];
                $lobjresult = $this->fetchAll("p = '$lstrshortname' AND iid = '$lstrintakeid'");
                return (count($lobjresult) + 1);
                break;
            case "year":
                $lstryyyy = date("Y");
                $lstryy = date("y");
                $lobjresult = $this->fetchAll("yyyy = '$lstryyyy' OR yy = '$lstryy'");
                return (count($lobjresult) + 1);
                break;
        }
    }
    
}
