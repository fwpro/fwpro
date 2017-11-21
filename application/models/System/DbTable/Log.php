<?php

class App_Model_System_DbTable_Log extends Zend_Log
{
    const ACCESS = 1;
    const ACTION = 2;

    public function __construct($user_id, $logTypeID = self::ACCESS, $priority=Zend_Log::INFO)
    {        
        $application = new Zend_Application(APPLICATION_ENV,APPLICATION_PATH . '/configs/application.ini');

        // Initialize and retrieve DB resource
        $bootstrap = $application->getBootstrap();
        $bootstrap->bootstrap('db');
        $db = $bootstrap->getResource('db');

        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
        $dbTableName = 'tbl_log';
        
        $columnMapping = array(
        	'user_id' => 'user_id',
         	'level' => 'priority',
        	'hostname' => 'hostname',
         	'ip' => 'ip',
         	'message' => 'message',
         	'Description' => 'Description'
         );
         
		$db 	= 	Zend_Db_Table::getDefaultAdapter();	
		$select =   $db->select()
					->from(array("a"=>"tbl_user"),array("CONCAT(a.fName,' ',IFNULL(a.mName,' '),' ',IFNULL(a.lName,' ')) as UserName"))
					->  where('a.iduser  = ?',$user_id);  				 
		$result = 	$db->fetchRow($select);		


         
        //register writer
        $dbWriter = new Zend_Log_Writer_Db($db, $dbTableName,$columnMapping);
        $description = "wqe";
        
        
        
        //register logger
        parent::__construct($dbWriter);
        $this->setEventItem('user_id', $user_id);
        $this->setEventItem('hostname', gethostbyaddr($_SERVER['REMOTE_ADDR']));
        $this->setEventItem('ip', $_SERVER['REMOTE_ADDR']);
        $this->setEventItem('Description', Zend_Log::DEBUG);
    }
}