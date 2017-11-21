<?php
class App_Form_Preferredapplicant extends Zend_Form {
  public function init(){

    $IdIntake = $this->createElement('hidden', 'IdIntake',array('isArray' => true));
    $IdIntake->removeDecorator('label');
    $IdIntake->removeDecorator('HtmlTag');


    $awardLevelObj = new App_Model_Applicant();
    $awardList = $awardLevelObj->fnGetDefination('Award');    
    $IdProgramLevel = $this->createElement('select','IdProgramLevel');
    $IdProgramLevel->removeDecorator("DtDdWrapper");
    //$IdProgramLevel->setAttrib('required',"true") ;
    $IdProgramLevel->removeDecorator("Label");
    $IdProgramLevel->removeDecorator('HtmlTag');
    $IdProgramLevel->setRegisterInArrayValidator(false);
    $IdProgramLevel->class = "preferredselect";
    $IdProgramLevel->onChange = "getProgram(this.value,this.id)";
    //$IdProgramLevel->setAttrib('dojoType',"dijit.form.FilteringSelect");
    $IdProgramLevel->addMultiOptions($awardList);

    $programList[0]['key'] = '';
    $programList[0]['value'] = 'select';   
    $IdProgram = $this->createElement('select','IdProgram');
    $IdProgram->removeDecorator("DtDdWrapper");
    //$IdProgramLevel->setAttrib('required',"true") ;
    $IdProgram->removeDecorator("Label");
    $IdProgram->removeDecorator('HtmlTag');
    $IdProgram->setRegisterInArrayValidator(false);
    $IdProgram->onChange = "getBranch(this.value,this.id)";
    $IdProgram->class = "preferredselect";
    $IdProgram->class = "preferreddrop";
    //$IdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect");
    $IdProgram->addMultiOptions($programList);

    $BranchList[0]['key'] = '';
    $BranchList[0]['value'] = 'select';
    $IdBranch = $this->createElement('select','IdBranch');
    $IdBranch->removeDecorator("DtDdWrapper");
    $IdBranch->removeDecorator("Label");
    $IdBranch->removeDecorator('HtmlTag');
    $IdBranch->addMultiOptions($BranchList);
    $IdBranch->class = "preferredselect";

    $SchemeList[0]['key'] = '';
    $SchemeList[0]['value'] = 'select';
    $IdScheme = $this->createElement('select','IdScheme');
    $IdScheme->removeDecorator("DtDdWrapper");
    $IdScheme->removeDecorator("Label");
    $IdScheme->removeDecorator('HtmlTag');
    $IdScheme->addMultiOptions($SchemeList);
    $IdScheme->class = "preferredselect";

    $IdPriorityNo = $this->createElement('text','IdPriorityNo');
    $IdPriorityNo->removeDecorator("DtDdWrapper");
    $IdPriorityNo->removeDecorator("Label");
    $IdPriorityNo->removeDecorator('HtmlTag');
    $IdPriorityNo->setAttrib('dojoType',"dijit.form.TextBox");
    $IdPriorityNo->class = "preferredstatus";
    
//    $priority = array('' => 'select', '1' => '1', '2' => '2' , '3' => '3');
//
//    $IdPriorityNo = $this->createElement('select','IdPriorityNo');
//    $IdPriorityNo->removeDecorator("DtDdWrapper");
//    $IdPriorityNo->removeDecorator("Label");
//    $IdPriorityNo->removeDecorator('HtmlTag');
//    $IdPriorityNo->addMultiOptions($priority);
//    $IdPriorityNo->class = "preferredstatus";

    $Status  = $this->createElement('CheckBox','Status');
    //$Status->setAttrib('dojoType',"dijit.form.CheckBox");
    //$Status->setCheckedValue('0') ;
    $Status->setUnCheckedValue('0') ;
    $Status->setvalue(false);    
    $Status->class = "preferredstatus";
    $Status->removeDecorator("DtDdWrapper");
    $Status->removeDecorator("Label");
    $Status->removeDecorator('HtmlTag');

    
    /*$lobjintake = new GeneralSetup_Model_DbTable_Intake();
    $larrintakelist = $lobjintake->fngetallIntake();   
    $IdIntake = new Zend_Dojo_Form_Element_FilteringSelect('IdIntake');
    $IdIntake->removeDecorator("DtDdWrapper");
    $IdIntake->setAttrib('required',"true") ;
    $IdIntake->removeDecorator("Label");
    $IdIntake->removeDecorator('HtmlTag');
    $IdIntake->setRegisterInArrayValidator(false);
    $IdIntake->setAttrib('dojoType',"dijit.form.FilteringSelect");
    $IdIntake->addMultiOptions($larrintakelist);*/
    $this->addElements(array($IdIntake,$IdProgramLevel,$IdProgram,$IdBranch,$IdScheme,$IdPriorityNo,$Status));

  }
}

?>
