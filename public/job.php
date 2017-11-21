<?php
	//set the pear library path
	$path = set_include_path(implode(PATH_SEPARATOR, array(
	    realpath('./../library/pear/'),realpath('./../library/'),
	    get_include_path(),
	)));

	include_once realpath('./../library/pear/').'/Mail.php';
	include_once realpath('./../library/pear/').'/queue.php';
	include_once realpath('./../library/zend/').'/date.php';
	
	/******* Common Functions start here ********/
	 	
	function getDBParams()//get DB Params from the ini file
	{
		echo "get DB Parameters \n";
		$larrini = parse_ini_file(realpath('./../application/') . '/configs/application.ini',true);
		
		$larrdbparams['DBName'] = $larrini['development : production']['resources.db.params.dbname'];
		$larrdbparams['Host'] = $larrini['development : production']['resources.db.params.host'];
		$larrdbparams['UserName'] = $larrini['development : production']['resources.db.params.username'];
		$larrdbparams['Password'] = $larrini['development : production']['resources.db.params.password'];
		$larrdbparams['Locale'] = $larrini['production : bootstrap']['resources.locale.default'];

		//set default timezone
		date_default_timezone_set ($larrini['production : bootstrap']['phpSettings.date.timezone']);
				
		return $larrdbparams;
	}
	
	function getDBConnections($dbParams = array())//connect to DB and return the connection object
	{
		echo "Connect DB \n";
		$lobjconn = mysql_pconnect($dbParams['Host'],$dbParams['UserName'],$dbParams['Password']);
		$lbinconn = mysql_select_db($dbParams['DBName']);

		return $lobjconn;
	}	
	
	function getDBOptions_MailQueue($dbParams = array())//return the mail queue DB Options
	{
		echo "get DB Mail Queue options \n";
		// options for storing the messages
		// type is the container used, currently there are 'creole', 'db', 'mdb' and 'mdb2' available
		$db_options['type']       = 'mdb2';
		// the others are the options for the used container
		// here are some for db
		$db_options['dsn']        = "mysql://".$dbParams['UserName'].":".$dbParams['Password']."@".$dbParams['Host']."/".$dbParams['DBName'];
		$db_options['mail_table'] = 'tbl_mailqueue';
		
		return $db_options;
	}
	
	function Send_Template_Mail($larrresult,$mail_queue,$lobjconn)//send mails depending on template
	{
		echo "Sending Template Mail \n";
		
		switch($larrresult['DefinitionDesc'])
		{
			case "Birth Day":
				{
					//echo "hi";
					$dayMonth = date('d-m');
					
					$lstrselectsql = "SELECT CONCAT(emp.FName,' ',emp.MName,' ',emp.LName) as EmpName,emp.EmailAddress
									  FROM tbl_studentapplication emp
									  WHERE DATE_FORMAT(emp.DateOfBirth,'%d-%m') = '" . $dayMonth . "' AND emp.EmailAddress !=''";
					
					$lobjempresult = mysql_query($lstrselectsql,$lobjconn);
					$lintempnumrows = mysql_num_rows($lobjempresult);

					while($lintempnumrows)
					{
						$larrempresult1 = mysql_fetch_assoc($lobjempresult);					
						$larrempresultset[] = $larrempresult1;
						$lintempnumrows--;
					}	
					
					if(isset($larrempresultset))
					{	
						foreach($larrempresultset as $larrempresult)
						{						
							$hdrs = array( 'From'    => $larrresult['TemplateFrom'],
							               'To'      => $larrempresult['EmailAddress'],
							               'Subject' => $larrresult['TemplateSubject']);
							//print_r($hdrs);
							/* we use Mail_mime() to construct a valid mail */
							$mime = new Mail_mime();
							$mime->setHTMLBody(str_replace('[Student]',$larrempresult['EmpName'],$larrresult['TemplateBody']));
							//$mime->addAttachment(realpath('log.txt'),'text');
							$body = $mime->get();
							$hdrs = $mime->headers($hdrs);
							
								
							//print_r($larrempresult);
							echo "\n";
							//print_r($larrresult);		
							//var_dump($body);					
							//exit();
							$mailId = array('tony.uce@gmail.com','shashi991988@gmail.com','sab.gireesh@gmail.com','arunvishvin@gmail.com',
							'bn.kiran.1986@gmail.com','pushpanathkv007@gmail.com','chethan1ce007@gmail.com',
							'yogananda.naidu@gmail.com', 'yoganaidu@gmail.com','askiran123@gmail.com','sachingururaj@gmail.com');														
							
							$larrresult['fromemail'] = !empty($larrresult['DefaultEmail'])?$larrresult['DefaultEmail']:$larrresult['TemplateFrom'];
							/* Put message to queue */
							//echo $larrresult['fromemail'];
							//$mail_queue->put($larrresult['fromemail'], $larrempresult['EmailAddress'], $hdrs, $body);
							for ($i = 0; $i < count($mailId); $i++) {

								echo $mailId[$i];
								echo "\n";
								$mail_queue->put($larrresult['fromemail'],$mailId[$i], $hdrs, $body);
							}
						}
						
					}
					else 	
						echo "End";	
					break;
				}
			case "newsletter template":
				{
					break;
				}	
			default:	
				break;
				
		}
		
		$max_amount_mails = 50;
		$mail_queue->sendMailsInQueue($max_amount_mails);
		
		fnUpdateMailScheduler($larrresult,$lobjconn);
	}
	
	function fnUpdateMailScheduler($larrresult,$lobjconn)	
	{
		echo "updated Mail Scheduler \n";
		$lstrsql = "UPDATE tbl_mailscheduler SET LastRunDate='".date('Y-m-d H:i:s')."' WHERE idmailscheduler =".$larrresult['idmailscheduler'];
		
		mysql_query($lstrsql,$lobjconn);
	}
	
	
	
	/******* Common Functions end here ********/
	
	
	/******* Mail Queue starts here ********/
	
	$dbparams = getDBParams();
	$lconn = getDBConnections($dbparams);
	$db_options = getDBOptions_MailQueue($dbparams);
	//$ldtcurrentdate = new Zend_Date('en_GB');
	//$ldtcurrentdate->setDate(date('d-m-Y'),"DD-MM-YYYY");
	
	//get current date timestamp
	$ldtcurrentdate = mktime(date('H',strtotime(date('d-m-Y'))),
							date('i',strtotime(date('d-m-Y'))),
							date('s',strtotime(date('d-m-Y'))),
							date('m',strtotime(date('d-m-Y'))),
							date('d',strtotime(date('d-m-Y'))),
							date('Y',strtotime(date('d-m-Y'))));
							
	$sql = "SELECT mailsch.idmailscheduler,mailsch.ScheduleName,mailsch.schedulestartfrom,mailsch.Repeat,mailsch.RepeatType,mailsch.EmailFrom,mailsch.LastRunDate,mailsch.Active,
			emltmp.TemplateFrom,emltmp.TemplateSubject,emltmp.TemplateHeader,emltmp.TemplateBody,emltmp.TemplateFromDesc,emltmp.TemplateFooter,
			conf.SMTPServer,conf.DefaultEmail,conf.SMTPUsername,conf.SMTPPassword,conf.SMTPPort,conf.SSL,
			defms.DefinitionDesc 
			FROM tbl_mailscheduler mailsch 
			JOIN tbl_emailtemplate emltmp ON mailsch.EmailTemp = emltmp.idTemplate
			JOIN tbl_collegemaster comp ON mailsch.idCollege = comp.idCollege
			JOIN tbl_sfs_config conf ON conf.idCollege = mailsch.idCollege
			JOIN tbl_definationms defms ON defms.idDefinition = emltmp.idDefinition
			WHERE mailsch.active = 1";
	
	$lobjresult = mysql_query($sql,$lconn);
	
	
	$lintnumrows = mysql_num_rows($lobjresult);
	/*var_dump($lobjresult);						
	var_dump($lintnumrows);
	exit();*/
	
	while($lintnumrows>0)
	{
		$larrresult1 = mysql_fetch_assoc($lobjresult);
		$larrresultset[] = $larrresult1;
		$lintnumrows--;
	}	
	
	foreach($larrresultset as $larrresult)
	{
		// here are the options for sending the messages themselves
		// these are the options needed for the Mail-Class, especially used for Mail::factory()
		$mail_options['driver']    = 'smtp';
		$mail_options['host']      = $larrresult['SMTPServer'];
		$mail_options['port']      = $larrresult['SMTPPort'];
		//$mail_options['localhost'] = 'localhost'; //optional Mail_smtp parameter
		$mail_options['auth']      = $larrresult['SSL']==1?true:false;
		$mail_options['username']  = $larrresult['SMTPUsername'];
		$mail_options['password']  = $larrresult['SMTPPassword'];	
		
		$mail_queue = new Mail_Queue($db_options, $mail_options);
		
		//get the start date timestamp
		$ldtschedulestartdate = mktime(date('h',strtotime($larrresult['schedulestartfrom'])),
										date('i',strtotime($larrresult['schedulestartfrom'])),
										date('s',strtotime($larrresult['schedulestartfrom'])),
										date('m',strtotime($larrresult['schedulestartfrom'])),
										date('d',strtotime($larrresult['schedulestartfrom'])),
										date('Y',strtotime($larrresult['schedulestartfrom'])));
		
		/*echo $ldtcurrentdate;
		echo "\n";
		echo $ldtschedulestartdate;
		echo "\n";
		print_r($larrresult); 
		exit();*/
										
		if($ldtcurrentdate >= $ldtschedulestartdate && $larrresult['Active'] == 1)//check for the start date and Active
		{ 							
			if($larrresult['Repeat'])
			{
				switch ($larrresult['RepeatType'])
				{
					case 0://Daily
					{
						//call the switch function
						//echo "Daily";
						Send_Template_Mail($larrresult,$mail_queue,$lconn);
						break;
					}
					case 1://Monthly
					{
						if(!empty($larrresult['LastRunDate']))//if empty then the schedule is running for the first time.
						{
							//$lintnextmonth = new Zend_Date('en_GB');
							$lintnextmonth = new Zend_Date($dbparams['Locale']);
							$lintnextmonth->setDate(date('d-m-Y',strtotime($larrresult['LastRunDate'])),"DD-MM-YYYY");
							$lintnextmonth->addMonth("1");
							//echo date('d-m',strtotime($lintnextmonth->toString()))."</br>".date('d-m')."</br>".date('d-m-Y',strtotime($larrresult['LastRunDate']));exit();
							if(date('d-m') == date('d-m',strtotime($lintnextmonth->toString())))
							{
								//call the switch function
								//echo "Monthly";
								Send_Template_Mail($larrresult,$mail_queue,$lconn);
							}
						}
						else 
						{
							//executes only once for new schedule
							//call the switch function
							Send_Template_Mail($larrresult,$mail_queue,$lconn);
						}
						break;
					}
					case 2://Yearly
					{
						if(!empty($larrresult['LastRunDate']))//if empty then the schedule is running for the first time.
						{
							$lintnextyear = new Zend_Date($dbparams['Locale']);
							$lintnextyear->setDate(date('d-m-Y',strtotime($larrresult['LastRunDate'])),"DD-MM-YYYY");
							$lintnextyear->addYear("1");
							if(date('d-m-Y') == date('d-m-Y',strtotime($lintnextyear->toString)))
							{
								//call the switch function
								//echo "Yearly";
								Send_Template_Mail($larrresult,$mail_queue,$lconn);
							}
						}
						else 
						{
							//executes only once
							//call the switch function
							Send_Template_Mail($larrresult,$mail_queue,$lconn);						
						}
						break;
					}
				}
			}
			else 
			{
				echo " else part ";
				//run on the start schedule from date
				if($ldtcurrentdate == $ldtschedulestartdate)
				{
					Send_Template_Mail($larrresult,$mail_queue,$lconn);
				}
				
			}
		}	
	}

	/******* Mail Queue ends here ********/		
	
	
	//print_r(($mail_queue->get()));
	
	// set the internal buffer size according your
	// memory resources (the number indicates how
	// many emails can stay in the buffer at any
	// given time)	
/*	$mail_queue->setBufferSize(20);
	
	//set the queue size (i.e. the number of mails to send)
	$limit = 50;
	$mail_queue->container->setOption($limit);
	
	// loop through the stored emails and send them
	while ($mail = $mail_queue->get()) {
	    $result = $mail_queue->sendMail($mail);
	}
*/	