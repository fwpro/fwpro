<?php
class App_Form_Forgotpasswordapplicant extends Zend_Form {
  public function init(){

    $Emaillogin = $this->createElement('text', 'Emaillogin');    
    $Emaillogin->removeDecorator('label');
    $Emaillogin->removeDecorator('HtmlTag');
    $Emaillogin->setRequired(true);
    $Emaillogin->setAttrib ( 'placeholder', 'Email' );
    

    $this->addElements(
              array($Emaillogin)
            );
  }
}

?>
