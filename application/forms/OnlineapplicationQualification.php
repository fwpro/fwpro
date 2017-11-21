<?php
class App_Form_OnlineapplicationQualification extends Zend_Dojo_Form {

	/**
	 *
	 * @see Zend_Form::init()
	 */
	public function init() {
		$lobjaward = new GeneralSetup_Model_DbTable_Awardlevel();
		$lobjapplicant = new App_Model_Applicant();
		$lobjinstitution = new Application_Model_DbTable_Institutionsetup();
		$lobjstate = new GeneralSetup_Model_DbTable_State();
		$lobjcity = new GeneralSetup_Model_DbTable_City();
		$lobjcountry = new GeneralSetup_Model_DbTable_Country();
		$lobjentryrequirement = new Application_Model_DbTable_Programentry();
		$lobjqualification = new Application_Model_DbTable_Qualificationsetup();
		$larrqualificationlist = $lobjqualification->fngetQualificationList();
		$larrawardlist = $lobjaward->fnGetDefinations("Award");
		$larrplaceobtainedlist = $lobjaward->fnGetDefinations("Place Obtained");
		$larrcertificatelist = $lobjapplicant->fngetcertificate();
		$larrinstitutionlist = $lobjinstitution->fnGetInstitutionList();
		$larrstatelist = $lobjstate->fnGetStateDetails();
		$larrcountrylist = $lobjcountry->fnGetCountryDetails();
		$larrprogramentrylist = $lobjaward->fnGetDefinations("Result Item");

		$PlaceObtained = new Zend_Dojo_Form_Element_FilteringSelect('PlaceObtained');
		$PlaceObtained->removeDecorator("DtDdWrapper");
		$PlaceObtained->removeDecorator("Label");
		$PlaceObtained->removeDecorator('HtmlTag');
		$PlaceObtained->setRegisterInArrayValidator(false);
		$PlaceObtained->setAttrib('required', "true");
		$PlaceObtained->setAttrib('dojoType', "dijit.form.FilteringSelect");
		$PlaceObtained->addMultiOptions($larrplaceobtainedlist);
		foreach ($larrplaceobtainedlist as $larrdefmsresult) {
			$PlaceObtained->addMultiOption($larrdefmsresult['idDefinition'], $larrdefmsresult['DefinitionDesc']);
		}

		$year = date("Y");
		$YearObtained = new Zend_Form_Element_Text('YearObtained', array (
			'regExp' => "[0-9]+",
			'invalidMessage' => "Numbers Only"
		));
		$YearObtained->setAttrib('invalidMessage', 'Please enter atleast 4 digits');
		$YearObtained->setAttrib('constraints', '{min:1900,max:' . $year . ',pattern:"####"}');
		$YearObtained->removeDecorator("DtDdWrapper");
		$YearObtained->setAttrib('required', "true");
		$YearObtained->setAttrib('maxlength', "4");
		$YearObtained->removeDecorator("Label");
		$YearObtained->removeDecorator('HtmlTag');
		$YearObtained->setAttrib('dojoType', "dijit.form.NumberTextBox");

		$QualificationLevel = new Zend_Dojo_Form_Element_FilteringSelect('QualificationLevel');
		$QualificationLevel->removeDecorator("DtDdWrapper");
		$QualificationLevel->setAttrib('required', "true");
		$QualificationLevel->removeDecorator("Label");
		$QualificationLevel->removeDecorator('HtmlTag');
		$QualificationLevel->setRegisterInArrayValidator(false);
		$QualificationLevel->setAttrib('dojoType', "dijit.form.FilteringSelect");
		$QualificationLevel->addMultiOptions($larrqualificationlist);

		$Certificate = new Zend_Dojo_Form_Element_ComboBox("Certificate");
		$Certificate->removeDecorator("DtDdWrapper");
		$Certificate->setAttrib('required', "true");
		$Certificate->removeDecorator("Label");
		$Certificate->removeDecorator('HtmlTag');
		$Certificate->setAttrib('dojoType', "dijit.form.FilteringSelect");
		$Certificate->setAutocomplete(true);
		$Certificate->addMultiOptions($larrcertificatelist);

		$Institution = new Zend_Dojo_Form_Element_ComboBox('Institution');
		$Institution->removeDecorator("DtDdWrapper");
		$Institution->setAttrib('required', "true");
		$Institution->setAttrib('onChange', "loadInstitutionAddress()");
		$Institution->removeDecorator("Label");
		$Institution->removeDecorator('HtmlTag');
		$Institution->setAutocomplete(true);
		$Institution->setAttrib('dojoType', "dijit.form.FilteringSelect");
		$Institution->addMultiOptions($larrinstitutionlist);

		/*$Certificate = new Zend_Form_Element_Textarea('Certificate');
		$Certificate->removeDecorator("DtDdWrapper");
		$Certificate->setAttrib('required',"false");
		$Certificate->removeDecorator("Label");
		$Certificate->removeDecorator('HtmlTag');
		$Certificate->setAttrib('dojoType',"dijit.form.TextBox");*/

		$InstitutionAddress1 = new Zend_Form_Element_Text('InstitutionAddress1');
		$InstitutionAddress1->removeDecorator("DtDdWrapper");
		$InstitutionAddress1->setAttrib('required', "true");
		$InstitutionAddress1->removeDecorator("Label");
		$InstitutionAddress1->removeDecorator('HtmlTag');
		$InstitutionAddress1->setAttrib('dojoType', "dijit.form.TextBox");

		$InstitutionAddress2 = new Zend_Form_Element_Text('InstitutionAddress2');
		$InstitutionAddress2->removeDecorator("DtDdWrapper");
		$InstitutionAddress2->setAttrib('required', "false");
		$InstitutionAddress2->removeDecorator("Label");
		$InstitutionAddress2->removeDecorator('HtmlTag');
		$InstitutionAddress2->setAttrib('dojoType', "dijit.form.TextBox");

		$SpecialTreatment = new Zend_Form_Element_Checkbox('SpecialTreatment');
		$SpecialTreatment->setAttrib('dojoType', "dijit.form.CheckBox");
		$SpecialTreatment->setvalue('0');
		$SpecialTreatment->removeDecorator("DtDdWrapper");
		$SpecialTreatment->removeDecorator("Label");
		$SpecialTreatment->removeDecorator('HtmlTag');

		$City = new Zend_Dojo_Form_Element_FilteringSelect('City');
		$City->removeDecorator("DtDdWrapper");
		$City->setAttrib('required', "true");
		$City->removeDecorator("Label");
		$City->removeDecorator('HtmlTag');
		$City->setRegisterInArrayValidator(false);
		$City->setAttrib('dojoType', "dijit.form.FilteringSelect");

		$Province = new Zend_Dojo_Form_Element_FilteringSelect('Province');
		$Province->removeDecorator("DtDdWrapper");
		$Province->setAttrib('required', "true");
		$Province->removeDecorator("Label");
		$Province->removeDecorator('HtmlTag');
		$Province->setRegisterInArrayValidator(false);
		$Province->setAttrib('OnChange', 'fnGetStateCityList');
		$Province->setAttrib('dojoType', "dijit.form.FilteringSelect");
		$Province->addMultiOptions($larrstatelist);

		$PostCode = new Zend_Form_Element_Text('PostCode', array (
			'regExp' => "[0-9]+",
			'invalidMessage' => "Numbers Only"
		));
		$PostCode->removeDecorator("DtDdWrapper");
		$PostCode->setAttrib('required', "true");
		$PostCode->setAttrib('maxlength', "8");
		$PostCode->removeDecorator("Label");
		$PostCode->removeDecorator('HtmlTag');
		$PostCode->setAttrib('dojoType', "dijit.form.ValidationTextBox");

		$Country = new Zend_Dojo_Form_Element_FilteringSelect('Country');
		$Country->removeDecorator("DtDdWrapper");
		$Country->setAttrib('required', "true");
		$Country->removeDecorator("Label");
		$Country->removeDecorator('HtmlTag');
		$Country->setRegisterInArrayValidator(false);
		$Country->setAttrib('OnChange', "fnGetCountryStateList(this,'Province')");
		$Country->setAttrib('dojoType', "dijit.form.FilteringSelect");
		$Country->addMultiOptions($larrcountrylist);

		$ResultItem = new Zend_Dojo_Form_Element_FilteringSelect('ResultItem');
		$ResultItem->removeDecorator("DtDdWrapper");
		$ResultItem->setAttrib('required', "false");
		$ResultItem->removeDecorator("Label");
		$ResultItem->removeDecorator('HtmlTag');
		$ResultItem->setRegisterInArrayValidator(false);
		$ResultItem->setAttrib('dojoType', "dijit.form.FilteringSelect");
		foreach ($larrprogramentrylist as $larrdefmsresult) {
			$ResultItem->addMultiOption($larrdefmsresult['idDefinition'], $larrdefmsresult['DefinitionDesc']);
		}

		$Result = new Zend_Form_Element_Text('Result');
		$Result->removeDecorator("DtDdWrapper");
		$Result->setAttrib('required', "false");
		$Result->removeDecorator("Label");
		$Result->removeDecorator('HtmlTag');
		$Result->setAttrib('dojoType', "dijit.form.TextBox");

		$Next = new Zend_Form_Element_Button('Next');
		$Next->setAttrib('class', 'NormalBtn');
		$Next->setAttrib('dojoType', "dijit.form.Button");
		$Next->setAttrib('OnClick', '')->removeDecorator("Label")->removeDecorator("DtDdWrapper")->removeDecorator('HtmlTag');

		$this->addElements(array (
			$PlaceObtained,
			$YearObtained,
			$QualificationLevel,
			$Certificate,
			$SpecialTreatment,
			$Institution,
			$Certificate,
			$InstitutionAddress1,
			$InstitutionAddress2,
			$City,
			$Province,
			$Country,
			$ResultItem,
			$Result,
			$Next,
			$PostCode
		));

	}
}