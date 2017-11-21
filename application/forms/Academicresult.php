<?php
class App_Form_Academicresult extends Zend_Form
{
	public function init()
    {
    	$gstrtranslate =Zend_Registry::get('Zend_Translate'); 
    	/*$this->_translate = Zend_Registry::get('samstranslate');
    	print_r($this->_translate);exit();*/
    	
        
        $UpdDate = new Zend_Form_Element_Hidden('UpdDate');
        $UpdDate->removeDecorator("DtDdWrapper");
        $UpdDate->removeDecorator("Label");
        $UpdDate->removeDecorator('HtmlTag');
        
        $UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
        $UpdUser->removeDecorator("DtDdWrapper");
        $UpdUser->removeDecorator("Label");
        $UpdUser->removeDecorator('HtmlTag');
   		
		$Active = new Zend_Form_Element_Checkbox('Active');
		$Active->  setAttrib('dojoType',"dijit.form.CheckBox") 
			        ->setChecked(true)
					->removeDecorator("DtDdWrapper")
					->removeDecorator("Label") 					
					->removeDecorator('HtmlTag');
					
		$Save = new Zend_Form_Element_Submit('Save');
        $Save->dojotype="dijit.form.Button";
        $Save->label = $gstrtranslate->_("Save");
        $Save->removeDecorator("DtDdWrapper");
        $Save->removeDecorator("Label");
        $Save->removeDecorator('HtmlTag')
         		->class = "NormalBtn";
         		
         		
        $Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('OnClick', 'addSubjectregistrationDetails()')
						->removeDecorator("Label")
						->removeDecorator("DtDdWrapper")
						->removeDecorator('HtmlTag');
		$Add->dojotype="dijit.form.Button";
		$Add->setAttrib('class','NormalBtn');
		$Add->label = $gstrtranslate->_("Add");
         				
        $Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
					
        
        $MarksExpected = new Zend_Form_Element_Text('MarksExpected',array('regExp'=>"^[1-9]\d*(\.\d+)?$",'invalidMessage'=>"Only digits"));
		$MarksExpected->setAttrib('dojoType',"dijit.form.ValidationTextBox");	
      	$MarksExpected->setAttrib('maxlength','4');  
        $MarksExpected->setAttrib('style','width:50px');   
        $MarksExpected->setAttrib('required','true');   
        $MarksExpected->removeDecorator("DtDdWrapper");
        $MarksExpected->removeDecorator("Label");
        $MarksExpected->removeDecorator('HtmlTag');
        
        $IdRegistration  = new Zend_Form_Element_Hidden('IdRegistration');
        $IdRegistration->removeDecorator("DtDdWrapper");
        $IdRegistration->removeDecorator("Label");
        $IdRegistration->removeDecorator('HtmlTag');
        
        $IdMarksDistributionMaster  = new Zend_Form_Element_Hidden('IdMarksDistributionMaster');
        $IdMarksDistributionMaster->removeDecorator("DtDdWrapper");
        $IdMarksDistributionMaster->removeDecorator("Label");
        $IdMarksDistributionMaster->removeDecorator('HtmlTag');
        
        $IdMarksDistributionDetails  = new Zend_Form_Element_Hidden('IdMarksDistributionDetails');
        $IdMarksDistributionDetails->removeDecorator("DtDdWrapper");
        $IdMarksDistributionDetails->removeDecorator("Label");
        $IdMarksDistributionDetails->removeDecorator('HtmlTag');
        
        
        
        $Idverifiermarks  = new Zend_Form_Element_Hidden('Idverifiermarks');
        $Idverifiermarks->removeDecorator("DtDdWrapper");
        $Idverifiermarks->removeDecorator("Label");
        $Idverifiermarks->removeDecorator('HtmlTag');
        
        
        $IdApplication  = new Zend_Form_Element_Hidden('IdApplication');
        $IdApplication->removeDecorator("DtDdWrapper");
        $IdApplication->removeDecorator("Label");
        $IdApplication->removeDecorator('HtmlTag');
        
        $IdSubject  = new Zend_Form_Element_Hidden('IdSubject');
        $IdSubject->removeDecorator("DtDdWrapper");
        $IdSubject->removeDecorator("Label");
        $IdSubject->removeDecorator('HtmlTag');
        
        $MarksObtained  = new Zend_Form_Element_Hidden('MarksObtained');
        $MarksObtained->removeDecorator("DtDdWrapper");
        $MarksObtained->removeDecorator("Label");
        $MarksObtained->removeDecorator('HtmlTag');
        
        $programid  = new Zend_Form_Element_Hidden('programid');
        $programid->removeDecorator("DtDdWrapper");
        $programid->removeDecorator("Label");
        $programid->removeDecorator('HtmlTag');
        
        
        $Comments = new Zend_Form_Element_Textarea('Comments');	
        $Comments ->setAttrib('cols', '30')
        				->setAttrib('rows','3')
        				->setAttrib('style','width = 10%;')
        				->setAttrib('maxlength','250')
        				->setAttrib('required','true') 
        				->setAttrib('dojoType',"dijit.form.SimpleTextarea")
        				->setAttrib('style','margin-top:10px;border:1px light-solid #666666;color:#666666;font-size:11px')
        				->removeDecorator("DtDdWrapper")
        				->removeDecorator("Label")
        				->removeDecorator('HtmlTag');
        				
        
	
        //form elements
        $this->addElements(array($IdSubject,
        						 $IdApplication,        						 
        						 $IdRegistration,        						
        						 $UpdDate,
        						 $UpdUser,
        						 $Active,$Comments,
        						 $Save,$IdMarksDistributionMaster,$IdMarksDistributionDetails,
        						 $Clear,$Add,$MarksObtained,$MarksExpected,$programid,$Idverifiermarks
                                 ));
    }
    


}

