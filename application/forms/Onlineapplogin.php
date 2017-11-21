<?php
/*
 *  Class for generating the form login screen in Online Application
 */
class App_Form_Onlineapplogin extends Zend_Form {
  public function init(){

    $Emaillogin = $this->createElement('text', 'Emaillogin');
    $Emaillogin->removeDecorator('label');
    $Emaillogin->removeDecorator('HtmlTag');    
    $Emaillogin->setRequired(true);
    $Emaillogin->setAttrib ( 'placeholder', 'Email' );


    $password = $this->createElement('password', 'password');
    $password->removeDecorator('label');
    $password->removeDecorator('HtmlTag');
    $password->setRequired(true);
    $password->setAttrib ( 'placeholder', 'password' );
    

    $this->addElements(
              array($Emaillogin,$password)
            );
  }
}


