<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initSession() {
        //Zend_Session::start();
    	$sis = new Zend_Session_Namespace('sis');
	    if (!isset($sis->initialized)) :    
			Zend_Session::regenerateId();
			$sis->initialized = true;
		endif;
		Zend_Registry::set('sis',$sis);

            $applicant = new Zend_Session_Namespace('applicant');
	    if (!isset($sis->initialized)) :
			Zend_Session::regenerateId();
			$sis->initialized = true;
		endif;
		Zend_Registry::set('applicant',$applicant);
	
        Zend_Locale::setDefault('id_ID');
        
    }

    protected function _initAppAutoload() {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'App',
            'basePath'  => dirname(__FILE__),));
        Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(false);
        
        return $autoloader;
    }
      
   
	
	protected function _initViewHelpers() {
		
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();
		
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
	    $view->addHelperPath ( 'Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper' );
		 		
		$view->doctype ('XHTML1_TRANSITIONAL');
		$view->headMeta()->appendHttpEquiv ('Content-Type','text/html;charset=UTF-8');
		$view->headMeta()->appendHttpEquiv ('Cache-control','no-cache');
		$view->headMeta()->appendHttpEquiv ('Pragma','no-cache');
		$view->headTitle()->setSeparator (' - ');
		$view->headTitle(APPLICATION_ENTERPRISE_SHORT ." - ". APPLICATION_TITLE_SHORT);
		
		$view->headLink()->headLink( array( 'rel' => 'shortcut icon',
				'href' => 'images/animated_favicon1.gif',
				'type' => 'image/x-icon' ));

		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
       
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);		
	}
	
	 
	protected function setconstants($constants){
        foreach ($constants as $key=>$value){
            if(!defined($key)){
                define($key, $value);
            }
        }
	}
	/*
	protected function _initTranslate(){
		$registry = Zend_Registry::getInstance();	
		
		 // Create Session block and save the locale
        $session = new Zend_Session_Namespace('session'); 

		    	
		$locale = new Zend_Locale('id_ID');		
		$file = APPLICATION_PATH . DIRECTORY_SEPARATOR .'languages'. DIRECTORY_SEPARATOR . "id_ID.php";

		$translate = new Zend_Translate('array',
            $file, $locale,
            array(
            'disableNotices' => true,    // This is a very good idea!
            'logUntranslated' => false,  // Change this if you debug
            )
        );

        $registry->set('Zend_Locale', $locale);
        $registry->set('Zend_Translate', $translate);
       
        
        return $registry;
	}
	*/
	
	 /*
	protected function _initDatabase(){
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'development');		
		$parameters = array('host'=>$config->resources->db->params->host,
					'username' => $config->resources->db->params->username,
					'password'=>$config->resources->db->params->password,
					'dbname'=>$config->resources->db->params->dbname,
					'unix_socket'    => $config->resources->db->params->unix_socket,
					'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8')
				);
		
		try {
		    $db = Zend_Db::factory('Pdo_Mysql', $parameters);
		    $db->getConnection();
		} catch (Zend_Db_Adapter_Exception $e) {
		    echo $e->getMessage();
		    die('Could not connect to database.');
		} catch (Zend_Exception $e) {
		    echo $e->getMessage();
		    die('Could not connect to database.');
		}
		
		Zend_Registry::set('dbapp', $db);
		
		 $resource = $this->getPluginResource('multidb');
      	 Zend_Registry::set("multidb", $resource);	
      	
    }
    */
	protected function _initDomPdf(){
		//set_include_path(APPLICATION_PATH . "/../../library/dompdf" . PATH_SEPARATOR . get_include_path());
		set_include_path("/var/www/html/sis/library/dompdf/" . PATH_SEPARATOR . get_include_path());
		
	}
}