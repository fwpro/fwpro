<?php

    $argument1 = $argv[1];

    ini_set('display_errors',1);
  	
	date_default_timezone_set('Asia/Jakarta');
	
	$paths = array(
	    realpath(dirname(__FILE__) . '/../library'),
	    '.',
	);
	set_include_path(implode(PATH_SEPARATOR, $paths));
	
	
	require_once('/var/www/html/sis/library/Zend/Loader/Autoloader.php');
	//require_once('/Users/alif/git/sis/library/Zend/Loader/Autoloader.php');
	$loader = Zend_Loader_Autoloader::getInstance();
	
	$config = new Zend_Config_Ini('/var/www/html/sis/application/configs/application.ini',"development");
	
	//get database
	$db = new Zend_Db_Adapter_Pdo_Mysql(array(
	    'host'     => $config->resources->db->params->host,
	    'username' => $config->resources->db->params->username,
	    'password' => $config->resources->db->params->password,
	    'dbname'   => $config->resources->db->params->dbname
	));
	
	//get report data
	$select = $db->select()->from(array('ssp'=>'rpt_student_semester_payment'))->where('ssp.id = ?',$argument1);
	                     
	$stmt = $db->query($select);
    $report_data = $stmt->fetch();
    
    
    //get fee item list
    $select_fee_item = $db->select()->from(array('fi'=>'fee_item'))->where('fi_active = 1')->order('fi_id');
    $fee_item_list = $db->fetchAll($select_fee_item);
    
    //get student
    if($report_data['paid_status']==1){
    
      $select = $db->select()
      ->from(array('sr'=>'tbl_studentregistration'))
      ->join(array('sp'=>'student_profile'), 'sp.appl_id = sr.IdApplication', array("concat_ws(' ',sp.appl_fname,sp.appl_mname,sp.appl_lname)fullname") )
      ->join(array('im'=>'invoice_main'), 'im.IdStudentRegistration = sr.IdStudentRegistration and im.semester = '.$report_data['semester_id'], array())
      ->join(array('pm'=>'payment_main'), 'pm.billing_no = im.bill_number')
      ->where('sr.IdProgram = ?',$report_data['program_id']);
       
      $row = $db->fetchAll($select);
      
      //loop each student
      foreach ($row as $index=>$student){
      
        $data_payment_detail = array();
      
        $data_payment_detail['IdStudentRegistration'] = $student['IdStudentRegistration'];
        $data_payment_detail['appl_id'] = $student['IdApplication'];
        $data_payment_detail['NIM'] = $student['registrationId'];
        $data_payment_detail['fullname'] = $student['fullname'];
      
        //set fi array
        foreach ($fee_item_list as $fi){
          $data_payment_detail['fee_item'][$fi['fi_id']]['fee_item'] = $fi['fi_name'];
          $data_payment_detail['fee_item'][$fi['fi_id']]['fee_item_short'] = $fi['fi_name_short'];
          $data_payment_detail['fee_item'][$fi['fi_id']]['amount'] = 0;
        }
        
        //get invoice
        $select_invoice = $db->select()
        ->from(array('im'=>'invoice_main'), array('bill_amount','bill_paid','bill_balance','cn_amount'))
        ->where('im.IdStudentRegistration = ?', $student['IdStudentRegistration'])
        ->where('im.semester = '.$report_data['semester_id']);
        
        $invoice_row = $db->fetchAll($select_invoice);
        $data_payment_detail['invoice'] = $invoice_row;
      
        //get payment detail
        $select_payment_detail = $db->select()
        ->from(array('pm'=>'payment_main'), array('billing_no'=>'billing_no', 'payment_amount'=>'amount'))
        ->join(array('im'=>'invoice_main'), 'pm.billing_no = im.bill_number and im.semester = '.$report_data['semester_id'], array('bill_amount','bill_paid','bill_balance','cn_amount'))
        ->join(array('id'=>'invoice_detail'), 'id.invoice_main_id = im.id')
        ->where('pm.IdStudentRegistration = ?', $student['IdStudentRegistration']);
      
        $payment_detail_row = $db->fetchAll($select_payment_detail);
      
      
        //loop payment and set amount
        $feeitem = array();
        foreach ($payment_detail_row as $fee_item){
          $data_payment_detail['fee_item'][$fee_item['fi_id']]['amount'] += $fee_item['amount'];
        }
      
        $data_payment_detail['payment_detail_item'] = $payment_detail_row;
      
      
      
        //insert record detail
        $data_detail = array(
            'rpt_student_semester_payment_id' => $argument1,
            'nim' => $data_payment_detail['NIM'],
            'fullname' => $data_payment_detail['fullname'],
        );
      
        $i=1;
        foreach ($data_payment_detail['fee_item'] as $f_id=>$f_data){
          $data_detail['item_'.$i] = $f_data['amount'];
          $i++;
        }
        
        //invoice data
        $data_detail['invoice_total_amount'] = 0;
        $data_detail['invoice_total_paid'] = 0;
        $data_detail['invoice_total_balance'] = 0;
        //$data_detail['invoice_total_cn'] = 0;
        
        foreach ($data_payment_detail['invoice'] as $invoice){
          $data_detail['invoice_total_amount'] += $invoice['bill_amount'];
          $data_detail['invoice_total_paid'] += $invoice['bill_paid'];
          $data_detail['invoice_total_balance'] += $invoice['bill_balance'];
          //$data_detail['total_invoice_cn'] += $invoice['cn_amount'];
        }
        
      
        $db->insert('rpt_student_semester_payment_dtl',$data_detail);
      
        //update progress
        $data_update = array('total_processed'=> $index+1);
        $db->update('rpt_student_semester_payment', $data_update,'id = '.$argument1);
        
      
      }
    
    }else{
      //get student
      $select = $db->select()
      ->from(array('sr'=>'tbl_studentregistration'),array("distinct(sr.IdStudentRegistration)", "IdApplication","registrationId"))
      ->join(array('sp'=>'student_profile'), 'sp.appl_id = sr.IdApplication', array("concat_ws(' ',sp.appl_fname,sp.appl_mname,sp.appl_lname)fullname") )
      ->join(array('im'=>'invoice_main'), 'im.IdStudentRegistration = sr.IdStudentRegistration and im.semester = '.$report_data['semester_id'], array())
      ->joinLeft(array('pm'=>'payment_main'), 'pm.billing_no = im.bill_number', array())
      ->where('sr.IdProgram = ?',$report_data['program_id'])
      ->where('pm.id is null');
    
      $row_student_notpaid = $db->fetchAll($select);
      
      //loop each student
      foreach ($row_student_notpaid as $index=>$student){
      
        $data = array();
      
        $data['IdStudentRegistration'] = $student['IdStudentRegistration'];
        $data['appl_id'] = $student['IdApplication'];
        $data['NIM'] = $student['registrationId'];
        $data['fullname'] = $student['fullname'];
        
        
      
        //set fi array
        foreach ($fee_item_list as $fi){
          $data['fee_item'][$fi['fi_id']]['fee_item'] = $fi['fi_name'];
          $data['fee_item'][$fi['fi_id']]['fee_item_short'] = $fi['fi_name_short'];
          $data['fee_item'][$fi['fi_id']]['amount'] = 0;
        }
        
      
        //get invoice
        $select_invoice = $db->select()
        ->from(array('im'=>'invoice_main'), array('bill_amount','bill_paid','bill_balance','cn_amount'))
        ->where('im.IdStudentRegistration = ?', $student['IdStudentRegistration'])
        ->where('im.semester = '.$report_data['semester_id']);
      
        $invoice_row = $db->fetchAll($select_invoice);
        $data['invoice'] = $invoice_row;
      
        
        //get invoice detail
        $select_invoice_detail = $db->select()
        ->from(array('im'=>'invoice_main'), array('id','bill_number'))
        ->join(array('id'=>'invoice_detail'), 'id.invoice_main_id = im.id')
        ->where('im.IdStudentRegistration = ?', $student['IdStudentRegistration'])
        ->where('im.semester = '.$report_data['semester_id']);;
      
        $invoice_detail_row = $db->fetchAll($select_invoice_detail);
        $data['invoice_detail'] = $invoice_detail_row;
      
      
        //insert record detail
        $data_detail = array(
            'rpt_student_semester_payment_id' => $argument1,
            'nim' => $data['NIM'],
            'fullname' => $data['fullname'],
        );
      
        $i=1;
        foreach ($fee_item_list as $f_data){
          $data_detail['item_'.$i] = 0.00;
          $i++;
        }
        
        //invoice data
        $data_detail['invoice_total_amount'] = 0;
        $data_detail['invoice_total_paid'] = 0;
        $data_detail['invoice_total_balance'] = 0;
        //$data_detail['invoice_total_cn'] = 0;
        
        foreach ($data['invoice'] as $invoice){
          $data_detail['invoice_total_amount'] += $invoice['bill_amount'];
          $data_detail['invoice_total_paid'] += $invoice['bill_paid'];
          $data_detail['invoice_total_balance'] += $invoice['bill_balance'];
          //$data_detail['total_invoice_cn'] += $invoice['cn_amount'];
        }
      
      
        $db->insert('rpt_student_semester_payment_dtl',$data_detail);
      
        //update progress
        $data_update = array('total_processed'=> $index+1);
        $db->update('rpt_student_semester_payment', $data_update,'id = '.$argument1);
      
      
      }
      
    }
    
    
    
    
    
	exit;
?>
