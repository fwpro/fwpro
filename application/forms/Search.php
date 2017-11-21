<?php
class App_Form_Search extends Zend_Dojo_Form {

	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');

		$lobjdeftype = new App_Model_Definitiontype();
		$status = $lobjdeftype->fnGetDefinationsStatus('Status');
		//    	echo "<pre>";
		//    	print_r($status);
		//    	die;

		$field1 = new Zend_Dojo_Form_Element_FilteringSelect('field1');
		$field1->setAttrib('required',"false");
		$field1->removeDecorator("DtDdWrapper");
		$field1->removeDecorator("Label");
		$field1->removeDecorator('HtmlTag');
		$field1->setRegisterInArrayValidator(false);
		$field1->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$field2 = new Zend_Form_Element_Text('field2');
		$field2 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field2->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field3 = new Zend_Form_Element_Text('field3');
		$field3 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field3->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field4 = new Zend_Form_Element_Text('field4');
		$field4->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field4->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field5 = new Zend_Form_Element_Select('field5');
		$field5->removeDecorator("DtDdWrapper");
		$field5->setAttrib('required',"false");
		$field5->removeDecorator("Label");
		$field5->removeDecorator('HtmlTag');
		$field5->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field6 = new Zend_Form_Element_Text('field6');
		$field6->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field6->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field7  = new Zend_Form_Element_Checkbox('field7');
		$field7->setAttrib('dojoType',"dijit.form.CheckBox");
		$field7->setvalue('1');
		$field7->removeDecorator("DtDdWrapper");
		$field7->removeDecorator("Label");
		$field7->removeDecorator('HtmlTag');


		$field8 = new Zend_Form_Element_Select('field8');

		$field8 ->removeDecorator("DtDdWrapper");
		$field8->setAttrib('required',"false");

		$field8 ->removeDecorator("Label");
		$field8 ->removeDecorator('HtmlTag');
		$field8 ->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field10 = new Zend_Dojo_Form_Element_DateTextBox('field10');
		$field10->setAttrib('dojoType',"dijit.form.DateTextBox");
		$field10->setAttrib('class', 'txt_put');
		$field10->removeDecorator("DtDdWrapper")
		->setAttrib('title',"dd-mm-yyyy");
		$field10->removeDecorator("Label");
		$field10->removeDecorator('HtmlTag');
		$field10->setAttrib('constraints', "{datePattern:'dd-MM-yyyy'}");

		$field11 = new Zend_Form_Element_Text('field11');
		$field11->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field11->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field12 = new Zend_Form_Element_Text('field12');
		$field12 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field12->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field13 = new Zend_Form_Element_Text('field13');
		$field13 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field13->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field14 = new Zend_Form_Element_Radio('field14');
		$field14->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('dojoType',"dijit.form.RadioButton")
		->setValue('0')
		->setSeparator('&nbsp');

		$field15 =new Zend_Form_Element_Select('field15');
		$field15->removeDecorator("DtDdWrapper");
		$field15->addMultiOption('','Select');
		$field15->removeDecorator("Label");
		$field15->removeDecorator('HtmlTag');
		$field15->setAttrib('OnChange', 'fnGetDetails');
		$field15->	setAttrib('required',"true");
		$field15->setRegisterInArrayValidator(false);
		$field15->setAttrib('dojoType',"dijit.form.FilteringSelect");

		/*$field15 = new Zend_Form_Element_Select('field15');
		 $field15->addMultiOption('','Select');
		$field15->setAttrib('class', 'txt_put');
		$field15->setAttrib('style','width:150px;');
		$field15->removeDecorator("DtDdWrapper");
		$field15->removeDecorator("Label");
		$field15->removeDecorator('HtmlTag');*/


		/*	$field16 = new Zend_Form_Element_Radio('field16');
		 $field16->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->addMultiOptions(array('1' => 'Name','0' => 'Id'))
		->setValue('1')
		->setSeparator('')
		->setAttrib('onclick', 'fnToggleStudentDetails(this.value)');*/

		$field16  = new Zend_Form_Element_Radio('field16');
		$field16->setAttrib('dojoType',"dijit.form.RadioButton");
		$field16->addMultiOptions(array('1' => 'Name','0' => 'Id'))
		->setvalue('1')
		->setSeparator('&nbsp;')
		->setAttrib('onClick','fnToggleStudentDetails(this.value)')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');




		/*  $field17 = new Zend_Form_Element_Select('field17');
		 $field17->addMultiOption('','Select');
		$field17->setAttrib('class', 'txt_put MakeEditable');
		$field17->setAttrib('ComboBoxOnChange','fnGetDetails');
		$field17->setAttrib('style','width:150px;');
		$field17->removeDecorator("DtDdWrapper");
		$field17->removeDecorator("Label");
		$field17->removeDecorator('HtmlTag');*/


		$field17 = new Zend_Dojo_Form_Element_FilteringSelect('field17');
		$field17->removeDecorator("DtDdWrapper");
		$field17->addMultiOption('','Select');
		$field17->removeDecorator("Label");
		$field17->removeDecorator('HtmlTag');
		$field17->setAttrib('OnChange', 'fnGetDetails')
		->setAttrib('class', 'txt_put');
		$field17->setRegisterInArrayValidator(false);
		$field17->setAttrib('dojoType',"dijit.form.FilteringSelect");


		/* $field18 = new Zend_Form_Element_Text('field18');
		 $field18->setAttrib('class', 'txt_put');
		$field18->setAttrib('style','width:150px;');
		$field18->setAttrib('readonly',true);
		$field18->removeDecorator("DtDdWrapper");
		$field18->removeDecorator("Label");
		$field18->removeDecorator('HtmlTag');	*/

		$field18 = new Zend_Form_Element_Text('field18');
		$field18->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field18->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$field19 = new Zend_Dojo_Form_Element_FilteringSelect('field19');
		// $field8 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field19 ->removeDecorator("DtDdWrapper");
		$field19 ->addMultiOption('','Select');
		$field19 ->removeDecorator("Label");
		$field19 ->removeDecorator('HtmlTag')
		->setAttrib('class', 'txt_put') ;
		$field19 ->setRegisterInArrayValidator(false);
		$field19 ->setAttrib('dojoType',"dijit.form.FilteringSelect")->setAttrib('required',"false");


		$field20 = new Zend_Dojo_Form_Element_FilteringSelect('field20');
		$field20->removeDecorator("DtDdWrapper");
		$field20->setAttrib('required',"false") ;
		$field20->removeDecorator("Label");
		$field20->removeDecorator('HtmlTag');
		$field20->setRegisterInArrayValidator(false);
		$field20->setAttrib('dojoType',"dijit.form.FilteringSelect")->setAttrib('required',"false");

		$field21 = new Zend_Form_Element_Radio('field21');
		$field21->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('dojoType',"dijit.form.RadioButton")
		->setValue('0')
		->setSeparator('&nbsp');

		$field22 = new Zend_Form_Element_MultiCheckbox('field22');
		$field22->removeDecorator("DtDdWrapper");
		$field22->removeDecorator("Label");
		$field22->removeDecorator('HtmlTag');
		$field22->setAttrib('dojoType',"dijit.form.CheckBox");


		$Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->dojotype="dijit.form.Button";
		$Clear->label = $gstrtranslate->_("Clear");
		$Clear->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$Close = new Zend_Form_Element_Submit('Close');
		$Close	->setAttrib('id', 'Close')
		->setAttrib('onclick', 'fnCloseLyteBox()')
		->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$Add = new Zend_Form_Element_Submit('Add');
		$Add->dojotype="dijit.form.Button";
		$Add->label = $gstrtranslate->_("Add");
		$Add->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');


		$submit = new Zend_Form_Element_Submit('Search');
		$submit->dojotype="dijit.form.Button";
		$submit->label = $gstrtranslate->_("Search");
		$submit->removeDecorator("DtDdWrapper");
		$submit->removeDecorator("Label");
		$submit->removeDecorator('HtmlTag')
		->class = "NormalBtn";

		$field9  = new Zend_Form_Element_Checkbox('field9');
		$field9->setAttrib('dojoType',"dijit.form.CheckBox");
		$field9->setvalue('1');
		$field9->removeDecorator("DtDdWrapper");
		$field9->removeDecorator("Label");
		$field9->removeDecorator('HtmlTag');

		$field10 = new Zend_Dojo_Form_Element_FilteringSelect('field10');
		$field10->setAttrib('required',"false");
		$field10->removeDecorator("DtDdWrapper");
		$field10->removeDecorator("Label");
		$field10->removeDecorator('HtmlTag');
		$field10->setRegisterInArrayValidator(false);
		$field10->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field11 = new Zend_Dojo_Form_Element_FilteringSelect('field11');
		$field11->setAttrib('required',"false");
		$field11->removeDecorator("DtDdWrapper");
		$field11->removeDecorator("Label");
		$field11->removeDecorator('HtmlTag');
		$field11->setRegisterInArrayValidator(false);
		$field11->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field50 = new Zend_Dojo_Form_Element_FilteringSelect('field50');
		$field50->setAttrib('required',"false");
		$field50->removeDecorator("DtDdWrapper");
		$field50->removeDecorator("Label");
		$field50->removeDecorator('HtmlTag');
		$field50->setRegisterInArrayValidator(false);
		$field50->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field12 = new Zend_Dojo_Form_Element_FilteringSelect('field12');
		$field12->setAttrib('required',"false");

		$field12->removeDecorator("DtDdWrapper");
		$field12->removeDecorator("Label");
		$field12->removeDecorator('HtmlTag');
		$field12->setRegisterInArrayValidator(false);
		$field12->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$field12->setAttrib('Onchange','fnFacultyDetails(this.value)');

		$field14 = new Zend_Dojo_Form_Element_DateTextBox('field14');
		$field14->setAttrib('dojoType',"dijit.form.DateTextBox");
		$field14->setAttrib('constraints', "{datePattern:'dd-MM-yyyy'}");
		$field14->setAttrib('required',"true");
		$field14->removeDecorator("DtDdWrapper")
		->setAttrib('title',"dd-mm-yyyy");
		$field14->removeDecorator("Label");
		$field14->removeDecorator('HtmlTag');

		$field15 = new Zend_Dojo_Form_Element_DateTextBox('field15');
		$field15->setAttrib('dojoType',"dijit.form.DateTextBox");
		$field15->setAttrib('constraints', "{datePattern:'dd-MM-yyyy'}");
		$field15->setAttrib('required',"true");
		$field15->removeDecorator("DtDdWrapper")
		->setAttrib('title',"dd-mm-yyyy");
		$field15->removeDecorator("Label");
		$field15->removeDecorator('HtmlTag');

		$Search = new Zend_Form_Element_Submit('Search');
		$Search->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$Ative = new Zend_Form_Element_Checkbox('Ative');
		$Ative->setAttrib('dojoType',"dijit.form.CheckBox")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field16 = new Zend_Dojo_Form_Element_DateTextBox('$field16');
		$field16->setAttrib('dojoType',"dijit.form.DateTextBox");
		$field16->setAttrib('constraints', "{datePattern:'dd-MM-yyyy'}");
		$field16->setAttrib('required',"true");
		$field16->removeDecorator("DtDdWrapper")
		->setAttrib('title',"dd-mm-yyyy");
		$field16->removeDecorator("Label");
		$field16->removeDecorator('HtmlTag');


		$field23 = new Zend_Form_Element_Select('field23');
		$field23->removeDecorator("DtDdWrapper");
		$field23->setAttrib('required',"false");
		$field23->removeDecorator("Label");
		$field23->removeDecorator('HtmlTag');
		$field23->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field24 = new Zend_Form_Element_Select('field24');
		$field24->removeDecorator("DtDdWrapper");
		$field24->setAttrib('required',"false");
		$field24->removeDecorator("Label");
		$field24->removeDecorator('HtmlTag');
		$field24->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field25 = new Zend_Form_Element_Select('field25');
		$field25->removeDecorator("DtDdWrapper");
		$field25->setAttrib('required',"false");
		$field25->removeDecorator("Label");
		$field25->removeDecorator('HtmlTag');
		$field25->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field26 = new Zend_Form_Element_Select('field26');
		$field26->removeDecorator("DtDdWrapper");
		$field26->setAttrib('required',"false");
		$field26->removeDecorator("Label");
		$field26->removeDecorator('HtmlTag');
		$field26->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$Status = new Zend_Dojo_Form_Element_FilteringSelect('Status');
		$Status ->setAttrib('required',"false");
		$Status ->addMultiOptions($status);
		$Status ->removeDecorator("DtDdWrapper");
		$Status ->removeDecorator("Label");
		$Status ->removeDecorator('HtmlTag');
		$Status ->setRegisterInArrayValidator(false);
		$Status ->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field27 = new Zend_Form_Element_Select('field27');
		$field27->removeDecorator("DtDdWrapper");
		$field27->setAttrib('required',"false");
		$field27->removeDecorator("Label");
		$field27->removeDecorator('HtmlTag');
		$field27->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$field28 = new Zend_Form_Element_Text('field28');
		$field28 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field28->setAttrib('required',"false");
		$field28->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field29 = new Zend_Form_Element_Select('field29');
		$field29->removeDecorator("DtDdWrapper");
		$field29->setAttrib('required',"false");
		$field29->removeDecorator("Label");
		$field29->removeDecorator('HtmlTag');
		$field29->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$Save = new Zend_Form_Element_Button('Save');
		$Save->dojotype="dijit.form.Button";
		$Save->label = $gstrtranslate->_("Save");
		$Save->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$Revert = new Zend_Form_Element_Button('Revert');
		$Revert->dojotype="dijit.form.Button";
		$Revert->label = $gstrtranslate->_("Revert");
		$Revert->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$field50 = new Zend_Form_Element_Text('field50');
		$field50 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field50->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field51 = new Zend_Form_Element_Text('field51');
		$field51 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field51->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field52 = new Zend_Form_Element_Text('field52');
		$field52 ->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field52->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$field53  = new Zend_Form_Element_Text('field53');
		$field53->setAttrib('dojoType',"dijit.form.DateTextBox");
		$field53->setAttrib('required',"false");
		$field53->removeDecorator("DtDdWrapper");
		$field53->setAttrib('constraints',"{datePattern:'dd-MM-yyyy'}");
		$field53->removeDecorator("Label");
		$field53->removeDecorator('HtmlTag');

		$field54  = new Zend_Form_Element_Text('field54');
		$field54->setAttrib('dojoType',"dijit.form.DateTextBox");
		$field54->setAttrib('required',"false");
		$field54->removeDecorator("DtDdWrapper");
		$field54->setAttrib('constraints',"{datePattern:'dd-MM-yyyy'}");
		$field54->removeDecorator("Label");
		$field54->removeDecorator('HtmlTag');

		$field55  = new Zend_Form_Element_Text('field55');
		$field55->setAttrib('dojoType',"dijit.form.DateTextBox");
		$field55->setAttrib('required',"false");
		$field55->removeDecorator("DtDdWrapper");
		$field55->setAttrib('constraints',"{datePattern:'dd-MM-yyyy'}");
		$field55->removeDecorator("Label");
		$field55->removeDecorator('HtmlTag');




		$this->addElements(array(
				$Status,$field50,$field1,$field2,$field3,$field4,$field5,$field6,$field7,$Search,$field8,$submit,$field10,$field11,$field12,
				$field13,$field14,$field15,$field16,$field17,$Ative,$field18,$field19,$field20,$field21,$field22,
				$Clear,$Close,$Add,$field9,$field10,$field23,$field24,$field25,$field26,$field27,$field28,$field29,$Save,$Revert
				,$field50,$field51,$field52,$field53,$field54,$field55
		));

	}
}