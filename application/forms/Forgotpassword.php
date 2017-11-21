<?php

class App_Form_Forgotpassword extends Zend_Form
{
	public function init()
    {
    	
    	$email = new Zend_Form_Element_Text('email',array('regExp'=>"^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$",'invalidMessage'=>""));
		$email->setAttrib('dojoType',"dijit.form.ValidationTextBox");
        $email->setAttrib('required',"true")  			 
        		->setAttrib('maxlength','50')         		     
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag')
        		->setAttrib('validator', 'validateEmail');
          
          $retrievepassword = $this->createElement('submit','retrievepassword');
          $retrievepassword->dojotype="dijit.form.Button";
          $retrievepassword->label = "Retrieve Password";	
          $retrievepassword->removeDecorator("DtDdWrapper");
          $retrievepassword->class = "NormalBtnauto";

          
          $this->addElements(array($retrievepassword,$email));
    }
    


}

