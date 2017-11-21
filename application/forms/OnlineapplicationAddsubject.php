<?php
class App_Form_OnlineapplicationAddsubject extends Zend_Dojo_Form {

	/**
	 *
	 * @see Zend_Form::init()
	 */
	public function init() {
		$lobjsubject = new Application_Model_DbTable_Subjectsetup();
		$lobjsubjectgrade = new Application_Model_DbTable_Subjectgradetype();
		$larrsubjectlist = $lobjsubject->fngetsubjectList();
		$larrsubjectgradelist = $lobjsubjectgrade->fngetsubjectgradeList();
		
		$Subject = new Zend_Dojo_Form_Element_FilteringSelect('Subject');
		$Subject->removeDecorator("DtDdWrapper");
		$Subject->setAttrib('required',"true") ;
		$Subject->removeDecorator("Label");
		$Subject->removeDecorator('HtmlTag');
		$Subject->setRegisterInArrayValidator(false);
		$Subject->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$Subject->addMultiOptions($larrsubjectlist);
		
		$SubjectGrade = new Zend_Dojo_Form_Element_FilteringSelect('SubjectGrade');
		$SubjectGrade->removeDecorator("DtDdWrapper");
		$SubjectGrade->setAttrib('required',"true") ;
		$SubjectGrade->removeDecorator("Label");
		$SubjectGrade->removeDecorator('HtmlTag');
		$SubjectGrade->setRegisterInArrayValidator(false);
		$SubjectGrade->setAttrib('dojoType',"dijit.form.FilteringSelect");
		//$SubjectGrade->addMultiOptions($larrsubjectgradelist);
		
		$this->addElements(array(	$Subject,$SubjectGrade	));
		
	}
}