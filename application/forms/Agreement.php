<?php

class App_Form_Agreement extends Zend_Form
{
	public function init()
    {
    	/*$this->_translate = Zend_Registry::get('samstranslate');
    	print_r($this->_translate);exit();*/

    	$agree = new Zend_Dojo_Form_Element_FilteringSelect('agree');
    	$agree->removeDecorator("DtDdWrapper");
    	//$marital->addMultiOptions(array("key"=>"01","name"=>"asdas"));
    	$agree->removeDecorator("Label");
    	$agree->removeDecorator('HtmlTag');
    	$agree->setRegisterInArrayValidator(false);
    	$agree->setAttrib('dojoType',"dijit.form.FilteringSelect");
    	
    	$maker = new Zend_Dojo_Form_Element_FilteringSelect('decisionmaker');
    	$maker->removeDecorator("DtDdWrapper");
    	//$marital->addMultiOptions(array("key"=>"01","name"=>"asdas"));
    	$maker->removeDecorator("Label");
    	$maker->removeDecorator('HtmlTag');
    	$maker->setRegisterInArrayValidator(false);
    	$maker->setAttrib('dojoType',"dijit.form.FilteringSelect");
    	
        $username = new Zend_Form_Element_Text('username');
		$username->setAttrib('dojoType',"dijit.form.ValidationTextBox");
                $username->setAttrib('class', 'txt_put') ;
                $username->setAttrib('required',"true")       
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag')
        		->setAttrib('style','height:19px')
        		->setAttrib('placeholder','Username');
                
                $note = new Zend_Form_Element_TextArea('note');
                $note->setAttrib('dojoType',"dijit.form.ValidationTextBox");
               // $username->setAttrib('class', 'txt_put') ;
                $note->setAttrib('required',"true")
                ->removeDecorator("DtDdWrapper")
                ->removeDecorator("Label")
                ->removeDecorator('HtmlTag')
                ->setAttrib('style','height:19px')
               
                        
        $password = $this->createElement('password','password');
		$password->setAttrib('dojoType',"dijit.form.ValidationTextBox");
                $password->setAttrib('class', 'txt_put') ;
                $password	->setAttrib('required',"true")      
        		->removeDecorator("DtDdWrapper")
        	    ->removeDecorator("Label")
        		->removeDecorator('HtmlTag')
        		->setAttrib('style','height:19px')
        		->setAttrib('placeholder','Password');

          $IdUniversity = new Zend_Dojo_Form_Element_FilteringSelect('IdUniversity');
          $IdUniversity->removeDecorator("DtDdWrapper");
          $IdUniversity->removeDecorator("Label");
          $IdUniversity->removeDecorator('HtmlTag')
          		   ->setAttrib('required','true')
          		   ->setAttrib('style','height:19px');
          $IdUniversity->setRegisterInArrayValidator(false);
          $IdUniversity->setAttrib('dojoType',"dijit.form.FilteringSelect");
          
          $Login = $this->createElement('submit','Login');
          $Login->dojotype="dijit.form.Button";
          $Login->label = "Login";	
          $Login->removeDecorator("DtDdWrapper");
          $Login->class = "NormalBtn";
          
          $Reset = $this->createElement('Reset','Reset');
          $Reset->dojotype="dijit.form.Button";
          $Reset->label = "Reset";
          $Reset->removeDecorator("DtDdWrapper");
          $Reset->class = "NormalBtn";
          
          $this->addElements(array($username,
                                   $password,$IdUniversity,
                                   $Login,$Reset
        ));
    }
    


}

