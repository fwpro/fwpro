<?php

class App_Form_Changepassword extends Zend_Dojo_Form
{
	/*
	 * Initalizing the elemtens
	 */	
    public function init()
    {        
    	/*
    	 * creating elements like password
    	 * removing attribute like dt, dd, label, htmltag
    	 */
        $oldPassword = $this->createElement('password','oldpassword');
        $oldPassword->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $oldPassword->setAttrib('class', 'txt_put');
        $oldPassword->setAttrib('required',"true") ;
        $oldPassword->setAttrib('maxlength','150');
        $oldPassword->removeDecorator("DtDdWrapper");
        $oldPassword->removeDecorator("Label");
        $oldPassword->removeDecorator('HtmlTag');
                        
        $newPassword = $this->createElement('password','newpassword',array('regExp'=>"[\w]+[@#$%^&*?>)(<!~+-/}{=_]+[\w@#$%^&*!~+-/]*",'invalidMessage'=>"No Spaces Allowed and One special Character required"));
        $newPassword->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $newPassword->setAttrib('class', 'txt_put');
        $newPassword->setAttrib('required',"true") ;
        $newPassword->setAttrib('maxlength','150');
        $newPassword->removeDecorator("DtDdWrapper");
        $newPassword->removeDecorator("Label");
        $newPassword->removeDecorator('HtmlTag');

        $retypePassword = $this->createElement('password','retypepassword',array('regExp'=>"[\w]+[@#$%^&*?>)(<!~+-/}{=_]+[\w@#$%^&*!~+-/]*",'invalidMessage'=>"No Spaces Allowed and One special Character required"));
        $retypePassword->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $retypePassword->setAttrib('class', 'txt_put');
        $retypePassword->setAttrib('required',"true") ;
        $retypePassword->setAttrib('maxlength','150');
        $retypePassword->removeDecorator("DtDdWrapper");
        $retypePassword->removeDecorator("Label");
        $retypePassword->removeDecorator('HtmlTag');


        $Add = new Zend_Form_Element_Submit('Add');
		$Add	->setAttrib('id', 'Add')
				->setAttrib('name', 'Add')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$Save = new Zend_Form_Element_Submit('Save');
     $Save->dojotype="dijit.form.Button";
          $Save->label = "Save";	
          $Save->removeDecorator("DtDdWrapper");
          $Save->class = "NormalBtn";				
        		
		$Search = new Zend_Form_Element_Submit('Search');
		$Search	->setAttrib('id', 'search')
				->setAttrib('name', 'search')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$Clear = new Zend_Form_Element_Submit('Clear');
		$Clear	->setAttrib('id', 'Clear')
				->setAttrib('name', 'Clear')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
				
		$Close = new Zend_Form_Element_Submit('Close');
		$Close	->setAttrib('id', 'Close')
				//->setAttrib('onclick', 'fnCloseLyteBox()')
				->setAttrib('class', 'NormalBtn')
				->removeDecorator("Label")
				->removeDecorator("DtDdWrapper")
				->removeDecorator('HtmlTag');
        

        // adding elements to cuttent object
        $this->addElements(array(
        						$oldPassword,$newPassword,$retypePassword,
        						$Add,$Save,$Clear,$Close
        	));
    }
}

