<?php
class Dosen_Bootstrap extends Zend_Application_Module_Bootstrap 
{

	protected function _initAutoload() {
		$autoloader = new Zend_Application_Module_Autoloader(array (
				'namespace'	=> 'Dosen_'	,
				'basePath'	=> APPLICATION_PATH
		));
      
		return $autoloader;
	}
	
}
?>