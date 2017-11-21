<?php
class App_Form_Onlineregistration extends Zend_Form {
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

    $confirmpassword = $this->createElement('password', 'confirmpassword');
    $confirmpassword->removeDecorator('label');
    $confirmpassword->removeDecorator('HtmlTag');
    $confirmpassword->setRequired(true);
    $confirmpassword->setAttrib ( 'placeholder', 'Confirm Password' );



    $this->addElements(
              array($Emaillogin,$password,$confirmpassword)
            );
  }
}

?>
