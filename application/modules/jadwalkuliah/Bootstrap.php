<?php
class Jadwalkuliah_Bootstrap extends Zend_Application_Module_Bootstrap 
{

	protected function _initAutoload() {
		$autoloader = new Zend_Application_Module_Autoloader(array (
				'namespace'	=> 'Jadwalkuliah_'	,
				'basePath'	=> APPLICATION_PATH
		));
      
		return $autoloader;
	}
	
}
?>