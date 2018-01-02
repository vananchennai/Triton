<?PHP	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");
	require_once '../../paginationfunction.php';
	require_once '../../searchfun.php';
	$news = new News(); // Create a new News Object
	
	
	$selectvar =mysql_query( "SELECT userid FROM usercreation");
	while( $record = mysql_fetch_array($selectvar))
    {
	$reporthead=array("Serial Number History","Data Exchange","Purchase Order","Purchase Report","Purchase Summary","Purchase Returns", "Sales Register","Sales Report","Weekly Sales Report","Retailer Category Detailed","Retailer Category Summary","Sales Returns","Stock Ledger","ServiceCallRegister","Warranty Administration","Item Wise Sales Summary Report","Sales Summary report","Division Wise","Zone State Sales Summary","Sales with Sales Return Report","Sub-Product Wise Sales Report","Purchase with Purchase Return Report","Location Wise Stock Summary","Consolidated Stock Summary","Division Wise Closing Stock Report","Product Wise Category Wise Transaction Report","Stockist Monthly Comparison Report","Usage Web Log Report","Day Wise Synch Status - Master Upload","Day Wise Synch Status - Transaction Download","Month Wise Synch Status - Master Upload","Month Wise Synch Status - Transaction Download");
	$reporthead1=$reporthead;

	$reps['userid'] = $record['userid'];
		for($loopval=0;$loopval<count($reporthead);$loopval++)
		{
		$reps['r_screen'] = $reporthead1[$loopval];
			switch($reporthead1[$loopval])
			{
				case "Serial Number History": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Data Exchange": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Purchase Order": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break; 
				case "Purchase Report": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break; 
				case "Purchase Summary": 
					$reps['access_right'] =  'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Purchase Returns": 
					$reps['access_right'] =  'No';
					$reps['branch_right'] = 'Others';
					break;						
				case "Sales Register": 
					$reps['access_right'] ='No';
					$reps['branch_right'] = 'Others';
					break;
				case "Sales Report": 
					$reps['access_right'] =  'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Weekly Sales Report": 
					$reps['access_right'] ='No';
					$reps['branch_right'] = 'Others';
					break;
				case "Retailer Category Detailed": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;			
				case "Retailer Category Summary": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Sales Returns": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Stock Ledger": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "ServiceCallRegister": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Warranty Administration": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Item Wise Sales Summary Report": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Sales Summary report": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Division Wise": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Zone State Sales Summary": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Sales with Sales Return Report": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Sub-Product Wise Sales Report": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Purchase with Purchase Return Report": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Location Wise Stock Summary": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Consolidated Stock Summary": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Division Wise Closing Stock Report": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Product Wise Category Wise Transaction Report": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Stockist Monthly Comparison Report": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Usage Web Log Report": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Day Wise Synch Status - Master Upload": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Day Wise Synch Status - Transaction Download": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Month Wise Synch Status - Master Upload": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				case "Month Wise Synch Status - Transaction Download": 
					$reps['access_right'] = 'No';
					$reps['branch_right'] = 'Others';
					break;
				default : 
					//Do nothing
			}		
		mysql_query("INSERT INTO `reportrights` VALUES ('".$reps['userid']."','".$reps['r_screen']."','".$reps['access_right']."','".$reps['branch_right']."')");	
		}
		} ?>