<?php 
@ob_start();
include '../../functions.php';
sec_session_start();
 require_once '../../masterclass.php';
include("../../header.php");
// Include database connection and functions here.

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $effectivedate,$tname;
	$tname	= "retailertarget";
	require_once '../../searchfun.php';
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
/* need to modify the script block */

//Authentication Block Starts here

	// $pagename = "Branch Target";
	 $pagename = "Retailer Target";
	$validuser = $_SESSION['username'];
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");//$news->getNewsById($validuser,$pagename);
	$row = mysql_fetch_array($selectvar);
  
 	if (($row['viewrights'])== 'No')
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
	if(isset($_POST['permiss'])) // If the submit button was clicked
    {
		?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'retailer_target.php');//document.location='pricelistmaster.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'retailer_target.php');//document.location='pricelistmaster.php';	
			</script>
         <?
		
	}
/* need to modify the script block */

//Export AS CSV file starts here
// exit;
$_SESSION['type']=NULL;
if(isset($_POST['PDF']))
{
$select=$_POST['Type'];
/*Retailer Target Search Starts Here */

		$limit = 10;
		$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
		$startpoint = ($page * $limit) - $limit;
		// $statement = $tname; 
		$startvalue= "";
		if(!empty($_POST['name_retailer']) && !empty($_POST['code_retailer'])){
			$_SESSION['name_retailer'] = $_POST['name_retailer'];
			$_SESSION['code_retailer'] = $_POST['code_retailer'];
			$result =mysql_query("Select RetailerCode from retailermaster where RetailerName like'%".$_POST['name_retailer']."%'");
			$rownum = mysql_num_rows($result);
			if($rownum > 0){

				while($row= mysql_fetch_array($result)){
					$rname .= "'".$row['RetailerCode']."',";
				}
			$rname = substr($rname, 0, -1);
			}else{
				$rname = "''";
			}
			$retailer_target ="SELECT * FROM retailertarget where RetailerCode IN(".$rname.") OR RetailerCode='".$_POST['code_retailer']."'";
		}else if(!empty($_POST['name_retailer'])&&empty($_POST['code_retailer'])){
			$_SESSION['name_retailer'] = $_POST['name_retailer'];
			$_SESSION['code_retailer'] = $_POST['code_retailer'];
			$result =mysql_query("Select RetailerCode from retailermaster where RetailerName like'%".$_POST['name_retailer']."%'");
			$rownum = mysql_num_rows($result);
			if($rownum > 0){
				while($row= mysql_fetch_array($result)){
					$rname .= "'".$row['RetailerCode']."',";
				}
				$rname = substr($rname, 0, -1);
			}else{
				$rname = "''";
			}
			$retailer_target ="SELECT * FROM retailertarget where RetailerCode IN(".$rname.")";
		}else if(!empty($_POST['code_retailer'])&&empty($_POST['name_retailer'])){
			$_SESSION['name_retailer'] = $_POST['name_retailer'];
			$_SESSION['code_retailer'] = $_POST['code_retailer'];
			$retailer_target ="SELECT * FROM retailertarget where RetailerCode ='".$_POST['code_retailer']."'";
		}else{
			$retailer_target = "SELECT * FROM retailertarget";
		}
		// echo $retailer_target;
	/*Retailer Target Search Ends Here */
if($select=='CSV')
{
	$_SESSION['type']='CSV';
	$_SESSION['query']=$retailer_target;
	header('Location:Exportretailer_target.php');
 }	
unset($_SESSION['name_retailer']);
unset($_SESSION['code_retailer']);
}
//Export AS CSV file ends here

//Save functionality starts here

if(isset($_POST['Save'])) // If the submit button was clicked
{
	unset($_SESSION['name_retailer']);
	unset($_SESSION['code_retailer']);
	$ptype =array();
	$tarqty = array();
	$branchcode = array();
	$retname = array();
	$retcode = array();
	$signagesign = array();
	$signagetype = array();
	$doi = array();
	$franchisecode = '';
	$region = $_POST['region'];
	$branchname = $_POST['branch'];
	$Producttype = $_POST['Producttype'];
	
	
	+
	    if($selectedOption!="0")
	    {
	      $reg =  $selectedOption;
	    }
	    else
	    {
	       break;
	    }                   
	 }
    foreach ($_POST['branch'] as $selectedOption1){
      if($selectedOption1!="0")
      {
        $brn= $selectedOption1;
      }
      else
      {
         break;
      }
    }
    foreach ($_POST['franchise'] as $selectedOption2){
		if($selectedOption2!="0")
		{
		 $franchisename = $selectedOption2;
		}
		else{
			break;
		}
	}	
$i = 0;
   foreach ($_POST['RetailerName'] as $selectedOption5){
   	$retnm = $selectedOption5;
   	$retarray = explode("~",$retnm);

    	$retname[$i] = $retarray[0];
    $retcode[$i] = $retarray[1];

    $i++;
  }
foreach ($_POST['branchcode'] as $selectedOption5){
	$branchcode = $selectedOption5;
}
foreach($_POST['franchisecode'] as $selectedOption5) {
	$franchisecode = $selectedOption5;
}
$i = 0;

foreach ($_POST['signagesign'] as $selectedOption5){
	$signagesign[$i] = $selectedOption5;
	$i++;
}
$i = 0;
foreach ($_POST['signagetype'] as $selectedOption5){
	$signagetype[$i] = $selectedOption5;
	$i++;
}
$i = 0;
foreach ($_POST['target'] as $selectedOption5){
	$tarqty[$i] = $selectedOption5;
	$i++;
}

$i = 0;
foreach ($_POST['doi'] as $selectedOption5){
	$doi[$i] = $selectedOption5;
	$i++;
}
// exit;
		$test="";
		$test1="";
		if($franchisename==''||$franchisename=='0'||$reg==''||$reg=='0'||$brn==''||$brn=='0'){ ?>
			<script type="text/javascript">
   				alert("Enter Mandatory Fields!");
   			</script>
	<?	}
		
   		$count = count($retname);
   		$inflag = 0;
   		if($count == 1){
   			// $signagetype[$i]==''||$signagesign[$i]==''||$doi[$i]==''||$doi[$i]=='00/00/0000'||$doi[$i]=='00-00-0000'||
   			if($retname[0]=='0' || $signagetype[0]==''||$signagesign[0]==''||$doi[0]==''||$doi[0]=='00/00/0000'||$doi[0]=='00-00-0000'|| $tarqty[0]==''){ ?>
   				<script type="text/javascript">alert("Enter Mandatory Fields!");</script>
   			<? $inflag = 1;}
   		}
   		// echo $count;
   		// echo $retname[0].'~'.$signagetype[0].'~'.$signagesign[0].'~'.$doi[0].'~'.$tarqty[0];
   		// exit;
   		$j = 0;
   		$dateflag = 0;
   		if($franchisecode==''||$franchisecode=='undefined'||$franchisename==''||$franchisename=='0'||$reg==''||$reg=='0'||$brn==''||$brn=='0'){
   		}else{
   		for($i=0;$i<$count;$i++){
   			$date_regex = '/^(0[1-9]|[12][0-9]|3[01])[\-\/.](0[1-9]|1[012])[\-\/.](19|2\d)\d\d$/';
   			$target_regex = '/^([1-9]\d{0,10})$/';
   			if(!preg_match($date_regex,$doi[$i]) ||$tarqty[$i]==''||$retname[$i]=='0'||$retname[$i]==''||$signagetype[$i]==''||$signagesign[$i]==''||$doi[$i]==''||$doi[$i]=='00/00/0000'||$doi[$i]=='00-00-0000'|| !preg_match($target_regex,$tarqty[$i])){
   				$not_insert[$j][0] = $retname[$i];
   				$not_insert[$j][5] = $retcode[$i];
   				$not_insert[$j][1] = $tarqty[$i];
   				$not_insert[$j][2] = $signagetype[$i];
   				$not_insert[$j][3] = $signagesign[$i];
   				$not_insert[$j][4] = $doi[$i];
   				$not_insert[$j][6] = "Check All Mandatory Fields";
   				$j++;
   			}

   			else{
   				for($k=0;$k<$i;$k++){
   				 	if($retcode[$k]==$retcode[$i]){
   				 		$flag = 1;
   				 		break;
   				 	}else{
   				 		$flag = 0;
   				 	}
   				 }
   				if($flag == 0){
   				 	 $doiformat = date('Y-m-d',strtotime($doi[$i]));
   				     $check_data = mysql_query("select * from retailertarget where RetailerCode='".$retcode[$i]."'");
				     $check_row = mysql_num_rows($check_data);
				     if($check_row  == 0){
				     	$date_regex = '/^(0[1-9]|[12][0-9]|3[01])[\-\/.](0[1-9]|1[012])[\-\/.](19|2\d)\d\d$/';
    				if (preg_match($date_regex,$doi[$i])){
				     	$status = 0;
		   				$user_id = $_SESSION['username'];
		   				date_default_timezone_set ("Asia/Calcutta");
						$m_date = date("y/m/d : H:i:s", time());
						$insert_date = date("y/m/d", time());
	   				 	$statement = "insert into retailertarget(Franchisecode, RetailerCode, Target, SignageSize, Signagetype, DOI, Status, user_id, m_date,InsertDate) VALUES('".trim($franchisecode)."','".trim($retcode[$i])."','".trim($tarqty[$i])."','".trim($signagesign[$i])."','".trim($signagetype[$i])."','".trim($doiformat)."','".trim($status)."','".trim($user_id)."','".trim($m_date)."','".trim($insert_date)."')";
	   				 	$repres= mysql_query($statement) or die (mysql_error());
	   				 	// $_SESSION['retailer_code_error'] .= "Retailer Code : ".$retcode[$i]." , Retailer name : ".$retname[$i]." Saved\n";
	   				 	
	   				 	//$_SESSION['retailer_code_error'] .= "Retailer Code : ".$retcode[$i]." , Retailer name : ".$retname[$i]." , Target : ".$tarqty[$i]." , SignageSize : ".$signagesign[$i]." , SignageType : ".$signagetype[$i]." , DOI : ".$doi[$i]."  Created \n";
	   				 	// $statement = "insert into retailertarget(Franchisecode, RetailerCode, Target, SignageSize, Signagetype, DOI) VALUES('".trim($franchisecode)."','".trim($retcode[$i])."','".trim($tarqty[$i])."','".trim($signagesign[$i])."','".trim($signagetype[$i])."','".trim($doiformat)."')";
	   				 	// echo $repres;
	   				 }else{
	   				 	// $_SESSION['retailer_code_error'] .= "Retailer Code : ".$retcode[$i]." , Retailer name : ".$retname[$i]." , Target : ".$tarqty[$i]." , SignageSize : ".$signagesign[$i]." , SignageType : ".$signagetype[$i]." , DOI : ".$doi[$i]."  Check DOI Column - Failed \n";
	   				 	// $dateflag++;
	   				 	$not_insert[$j][0] = $retname[$i];
						$not_insert[$j][5] = $retcode[$i];
						$not_insert[$j][1] = $tarqty[$i];
						$not_insert[$j][2] = $signagetype[$i];
						$not_insert[$j][3] = $signagesign[$i];
						$not_insert[$j][4] = $doi[$i];
	   				 	$not_insert[$j][6] = "Check DOI Column - Failed";
	   				 }
	   				}else{
	   					// $_SESSION['retailer_code_error'] .= "Retailer Code : ".$retcode[$i]." , Retailer name : ".$retname[$i]." , Target : ".$tarqty[$i]." , SignageSize : ".$signagesign[$i]." , SignageType : ".$signagetype[$i]." , DOI : ".$doi[$i]."  Duplicate Entry \n";
	   					$not_insert[$j][0] = $retname[$i];
						$not_insert[$j][5] = $retcode[$i];
						$not_insert[$j][1] = $tarqty[$i];
						$not_insert[$j][2] = $signagetype[$i];
						$not_insert[$j][3] = $signagesign[$i];
						$not_insert[$j][4] = $doi[$i];
						$not_insert[$j][6] = "Duplicate Entry";
						$j++;
	   				}
	   		    }else{
	   		    	// $_SESSION['retailer_code_error'] .= "Retailer Code : ".$retcode[$i]." , Retailer name : ".$retname[$i]." , Target : ".$tarqty[$i]." , SignageSize : ".$signagesign[$i]." , SignageType : ".$signagetype[$i]." , DOI : ".$doi[$i]."  Repeated Entry \n";
	   		    	$not_insert[$j][0] = $retname[$i];
	   				$not_insert[$j][5] = $retcode[$i];
	   				$not_insert[$j][1] = $tarqty[$i];
	   				$not_insert[$j][2] = $signagetype[$i];
	   				$not_insert[$j][3] = $signagesign[$i];
	   				$not_insert[$j][4] = $doi[$i];
	   				$not_insert[$j][6] = "Repeated Entry";
	   				$j++;
	   		    }

   			}

   		} 
   		$insert_count  = count($not_insert);
   		if($insert_count >0 ){
   			for($i=0;$i<$insert_count;$i++){
   				if($inflag != 1)
   					$_SESSION['retailer_code_error'] .= "Retailer Code : ".$not_insert[$i][5]." , Retailer name : ".$not_insert[$i][0]." , Target : ".$not_insert[$i][1]." , SignageSize : ".$not_insert[$i][3]." , SignageType : ".$not_insert[$i][2]." , DOI : ".$not_insert[$i][4]."  ".$not_insert[$i][6]." --------------------------------- \n";
			} 
			if($count == $insert_count){ ?>
			<script type="text/javascript">
				alert("Enter Mandatory Fields!");
			</script>
			<? }else{ ?>
				<script type="text/javascript">
   				alert("Created Sucessfully!",'retailer_target.php');
   			</script>
			<? } ?>
    	
   	<? }else{ ?>
   			<script type="text/javascript">
   				alert("Created Sucessfully!",'retailer_target.php');
   			</script>
   		<? }
   
   	}
		
}
//Save functionality ends here	

//Update functionality starts here

if(isset($_POST['Update'])) // If the submit button was clicked
{
	$ptype =array();
	$tarqty = array();
	$branchcode = array();
	$retname = array();
	$retcode = array();
	$signagesign = array();
	$signagetype = array();
	$doi = array();
	$franchisecode = '';
	$region = $_POST['region'];
	$branchname = $_POST['branch'];
	$Producttype = $_POST['Producttype'];
	
	foreach ($_POST['region'] as $selectedOption){
	    if($selectedOption!="0")
	    {
	      $reg =  $selectedOption;
	    }
	    else
	    {
	       break;
	    }                   
	 }
    foreach ($_POST['branch'] as $selectedOption1){
      if($selectedOption1!="0")
      {
        $brn= $selectedOption1;
      }
      else
      {
         break;
      }
    }
    foreach ($_POST['franchise'] as $selectedOption2){
		if($selectedOption2!="0")
		{
		 $franchisename = $selectedOption2;
		}
		else{
			break;
		}
	}	
$i = 0;
   foreach ($_POST['RetailerName'] as $selectedOption5){
    $retname[$i] =  $selectedOption5;
    $i++;
  }
foreach ($_POST['branchcode'] as $selectedOption5){
	$branchcode = $selectedOption5;
}
foreach($_POST['franchisecode'] as $selectedOption5) {
	$franchisecode = $selectedOption5;
}
$i = 0;
foreach ($_POST['retailercode'] as $selectedOption5){
	$retcode[$i] = $selectedOption5;
	$i++;
}
$i = 0;
foreach ($_POST['signagesign'] as $selectedOption5){
	$signagesign[$i] = $selectedOption5;
	$i++;
}
$i = 0;
foreach ($_POST['signagetype'] as $selectedOption5){
	$signagetype[$i] = $selectedOption5;
	$i++;
}
$i = 0;
foreach ($_POST['target'] as $selectedOption5){
	$tarqty[$i] = $selectedOption5;
	$i++;
}

$i = 0;
foreach ($_POST['doi'] as $selectedOption5){
	$doi[$i] = $selectedOption5;
	$i++;
}
		$test="";
		$test1="";
	
   		$count = count($retname);
   		$j = 0;
   		$target_regex = '/^([1-9]\d{0,10})$/';
   	for($i=0;$i<$count;$i++){
   		$date_regex = '/^(0[1-9]|[12][0-9]|3[01])[\-\/.](0[1-9]|1[012])[\-\/.](19|2\d)\d\d$/';

   			// echo $retname[$i].' code '.$retcode[$i].' stype '.$signagetype[$i].' ssize '.$signagesign[$i].' doi '.$doi[$i].' tar '.$tarqty[$i];
   			if($tarqty[$i]==''||$retname[$i]=='0'||$retname[$i]==''||$signagetype[$i]==''||$signagesign[$i]==''||$doi[$i]==''||$doi[$i]=='00/00/0000'||$doi[$i]=='00-00-0000' || !preg_match($target_regex,$tarqty[$i])){
   				$not_insert[$j][0] = $retname[$i];
   				$not_insert[$j][1] = $tarqty[$i];
   				$not_insert[$j][2] = $signagetype[$i];
   				$not_insert[$j][3] = $signagesign[$i];
   				$not_insert[$j][4] = $doi[$i];
   				$not_insert[$j][5] = $retcode[$i];
   				$not_insert[$j][6] = "Check All Mandatory Fields";//"Check All Columns - Failed";
   				$j++;
   			}
   			else{
   				// $flag= 0;
   				// for($k=0;$k<$i;$k++){
   				//  	if($retcode[$k]==$retcode[$i]){
   				//  		$flag = 1;
   				//  		break;
   				//  	}else{
   				//  		$flag = 0;
   				//  	}
   				//  }
   				// if($flag == 0){
   				    // echo $doi[$i];/
    				// $date_regex = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/';
    				// '/^(0[1-9]|[12][0-9]|3[01])[\-\/.](0[1-9]|1[012])[\-\/.](19|20[0-9]|[0-9])$/'
    				// preg_match($date_regex,$doi[$i]);
    				$date_regex = '/^(0[1-9]|[12][0-9]|3[01])[\-\/.](0[1-9]|1[012])[\-\/.](19|2\d)\d\d$/';
    				if (preg_match($date_regex,$doi[$i])){
    					$retailer_delete = mysql_query("delete from retailertarget where FranchiseCode='".$franchisecode."' AND RetailerCode='".$retcode[$i]."'");
   				    	$doiformat = date('Y-m-d',strtotime($doi[$i]));
	   				    $status = 1;
		   				$user_id = $_SESSION['username'];
		   				date_default_timezone_set ("Asia/Calcutta");
						$m_date = date("y/m/d : H:i:s", time());
	   				 	$statement = "insert into retailertarget(Franchisecode, RetailerCode, Target, SignageSize, Signagetype, DOI, Status, user_id, m_date) VALUES('".trim($franchisecode)."','".trim($retcode[$i])."','".trim($tarqty[$i])."','".trim($signagesign[$i])."','".trim($signagetype[$i])."','".trim($doiformat)."','".trim($status)."','".trim($user_id)."','".trim($m_date)."')";
	   				    $repres= mysql_query($statement) or die (mysql_error());
	   				   // $_SESSION['retailer_code_error'] .= "Retailer Code : ".$retcode[$i]." , Retailer name : ".$retname[$i]." , Target : ".$tarqty[$i]." , SignageSize : ".$signagesign[$i]." , SignageType : ".$signagetype[$i]." , DOI : ".$doi[$i]."  Updated \n";
   				    }else{
   				    	// echo "failure";
   				    	// $_SESSION['retailer_code_error'] .= "Retailer Code : ".$retcode[$i]." , Retailer name : ".$retname[$i]." , Target : ".$tarqty[$i]." , SignageSize : ".$signagesign[$i]." , SignageType : ".$signagetype[$i]." , DOI : ".$doi[$i]."  Check DOI Column - Failed \n";
   				   		$not_insert[$j][0] = $retname[$i];
		   				$not_insert[$j][1] = $tarqty[$i];
		   				$not_insert[$j][2] = $signagetype[$i];
		   				$not_insert[$j][3] = $signagesign[$i];
		   				$not_insert[$j][4] = $doi[$i];
		   				$not_insert[$j][5] = $retcode[$i];
		   				$not_insert[$j][6] = "Check DOI Column - Failed";
		   				$j++;

   				    }
   				    // $statement = "insert into retailertarget(Franchisecode, RetailerCode, Target, SignageSize, Signagetype, DOI) VALUES('".trim($franchisecode)."','".trim($retcode[$i])."','".trim($tarqty[$i])."','".trim($signagesign[$i])."','".trim($signagetype[$i])."','".trim($doiformat)."')";
   				    // echo $statement;
   				    // echo $repres;
   				    // exit;


   				// }
   		   			}

   		} 
   		$insert_count  = count($not_insert);
   			// exit;
   		if($insert_count >0 ){
   			for($i=0;$i<$insert_count;$i++){
   				// $_SESSION['retailer_code_error'] .= "Retailer Code : ".$not_insert[$i][5]." , Retailer name : ".$not_insert[$i][0]." , Target : ".$not_insert[$i][1]." , SignageSize : ".$not_insert[$i][3]." , SignageType : ".$not_insert[$i][2]." , DOI : ".$not_insert[$i][4].' ' .$not_insert[$i][6]." \n";
			} 
			if($count == $insert_count){ ?>
			<script type="text/javascript">
				alert("Enter Mandatory Fields!");
			</script>
			<? }else{ ?>
				<script type="text/javascript">
   				alert("Updated Sucessfully!",'retailer_target.php');
   			</script>
			<? } ?>
  
   	<? }else{ ?>
   			<script type="text/javascript">
   				alert("Updated Sucessfully!",'retailer_target.php');
   			</script>
   		<? }
}
//Update functionality ends here
	
/// EDIT LINK FUNCTION 

if(!empty($_GET['FranchiseCode']) && !empty($_GET['RetailerCode']))
{
	unset($_SESSION['name_retailer']);
	unset($_SESSION['code_retailer']);
  $edit_franchisecode = $_GET['FranchiseCode'];
  $edit_ReailerCode =  $_GET['RetailerCode'];
  $franchise_result = mysql_query("select Franchisename,branchname,RegionName from view_fbr where Franchisecode='".$edit_franchisecode."'");
 while($rows = mysql_fetch_array($franchise_result)){
 	$edit_branchname = $rows['branchname'];
 	$edit_regionname = $rows['RegionName'];
 	$edit_franchisename = $rows['Franchisename'];
 } 
$result=mysql_query("SELECT * FROM retailertarget where Franchisecode ='".$edit_franchisecode."'  AND RetailerCode='".$edit_ReailerCode."'");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);
		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!!",'retailer_target.php');//document.location='branch_target.php';
			</script>
   			<?
		}
		else
		{
	    $result1=mysql_query("SELECT * FROM retailertarget where Franchisecode ='".$edit_franchisecode."' AND RetailerCode='".$edit_ReailerCode."'") or die("cannot run ");
		$myrow = mysql_fetch_array($result);
	 	$franchisecode = $myrow['Franchisecode'];
	 	$retailercode =  $myrow['edit_ReailerCode'];
			$result12=$result1;
			$_SESSION['flistsession']= $myrow['Franchisecode'];
		}
		$prmaster = NULL;
}
	// Check if delete button active, start this 
	
	if(isset($_POST['Delete']))
{
	if(!isset($_POST['checkbox']))
	{
			?>
		    <script type="text/javascript">
			alert("Select data to delete!",'retailer_target.php');//document.location='branch_target.php';
			</script>
			<?
	}
	else
	{
		 $checkbox = $_POST['checkbox']; //from name="checkbox[]"
		 $countCheck = count($_POST['checkbox']);
         $message=NULL;
		for($i=0;$i<$countCheck;$i++)
		{
		$franchiseid = $checkbox[$i];
		$franchisearray = explode("~",$franchiseid);
		$delete_retailertarget = mysql_query("delete from retailertarget where Franchisecode='".$franchisearray[0]."' AND RetailerCode='".$franchisearray[1]."'") or die("Deletion Faliure in branchtarget");
		?>
		<script type="text/javascript">
			alert("Deleted  Successfully!",'retailer_target.php');
			</script>
		<? }
       
	}
}
//Check if reset button click
if(isset($_POST['Cancel']))
{
	unset($_SESSION['name_retailer']);
	unset($_SESSION['code_retailer']); ?>
	<script type="text/javascript">
		 localStorage.removeItem("RegName");
		  localStorage.removeItem("BranchName");
		  localStorage.removeItem("FrnName");
		  localStorage.removeItem("FrnCode");
	</script> <?
	header('Location:retailer_target.php');
}

?>

<script type="text/javascript">
$(function() {
    $('#start,#search_date_franchise').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: '2000:3050',
        dateFormat: 'mm-yy',
        onClose: function(dateText, inst,selectedDate) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
            $( "#mscrp_todate" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    // $(document).on("pageload",function(){
  // alert("pageload event fired!");
  var regName = localStorage.getItem("RegName");
  var BranchName = localStorage.getItem("BranchName");
  var frnname = localStorage.getItem("FrnName");
  var fcode = localStorage.getItem("FrnCode");
  // alert(regName +' '+BranchName+' '+frnname+' '+fcode);
  if(fcode != null){
	  $("#franchisecode").val(fcode);
	  $("#region").val(regName);
	  drpfunc();

	  $("#branch").val(BranchName);
	  drpfunc1();
	  $("#franchise").val(frnname);
	  drpfuncretailerselect();
	  localStorage.removeItem("RegName");
	  localStorage.removeItem("BranchName");
	  localStorage.removeItem("FrnName");
	  localStorage.removeItem("FrnCode");
  }


// });
});

function getpdsdetails(){
	var pds = $("#PDSNumber").val();
	var data = {pdsnumber : pds };
	if(pdsnumber.trim() != ""){
		var url = "GetPDSDetails.php";
		  $.ajax({
			type: 'POST',
			url: 'GetPDSDetails.php',
			data: data,
			dataType: 'json',
			success: function( resp ) {
			  console.log( resp );
			}
		  });
	}
}
function popup(mylink)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href,'_blank');
return false;
}


 function addRow(tableID) {
 
            var table = document.getElementById(tableID);
 
            var rowCount = table.rows.length;
			var srcElem = window.event.srcElement;
			var rowNum = srcElem.parentNode.parentNode.rowIndex ;
            var dateval =  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[4].value;
            var signtype =  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[3].value;
            var signsize =  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[2].value;
            var targetqty =  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[1].value;
			var retaname=document.getElementById('dataTable').rows[rowNum].getElementsByTagName("select")[0];
			var er=retaname.options[retaname.selectedIndex].value;
			var date_regex = /^(0[1-9]|[12][0-9]|3[01])[\-\/.](0[1-9]|1[012])[\-\/.](19|2\d)\d\d$/;
			// console.log(dateval);
			if(er == "" || er == 0 || er =='0'){
				document.getElementById('dataTable').rows[rowNum].getElementsByTagName("select")[0].focus();
				//return false;	
			}
			if(targetqty == ""){
				document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[1].focus();
			//	return false;
			}
			if(signsize == ""){
				document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[2].focus();
				//return false;
			}
			if(signtype == ""){
				document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[3].focus();
				//return false;
			}
			if(date_regex.test(dateval)){

			}else{
				document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[4].focus();
			//	return false;
			}
            var row = table.insertRow(rowCount-1);


			var delRow = parseInt(rowCount) - 2;	
            var colCount = table.rows[1].cells.length;
            for(var i=0; i<colCount; i++) {
 
                var newcell = row.insertCell(i);
				var deleteBtn = '';
				if(i == (colCount-1)) {
					htmlVal = "<img src='del_img.jpg' style='cursor: pointer;' onclick='removeRow(this, \"add\");'/>";   
				 }
				else {
					htmlVal = table.rows[1].cells[i].innerHTML;
				}
                newcell.innerHTML = htmlVal;
                // console.log(htmlVal);
				var controlType = newcell.childNodes[0].type;
                switch(controlType) {
                    case "text":
                            newcell.childNodes[0].value = "";
                            break;
                    
                    case "checkbox":
                            newcell.childNodes[0].checked = false;
                            break;
                    case "select-one":
                            newcell.childNodes[0].selectedIndex = 0;
                            // console.log(newcell);
                            break;
                }

            }	
            // Need To Change

            // var idcount = rowCount-1;
            // document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].id = "";
            // document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].id = "RetailerName"+idcount.toString()+"";
            
            // Need To Change

                // document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("input")[4].id = "";
                 // document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("input")[4].id = "doi"+idcount.toString()+""
   			// document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].trigger("chosen:updated");
   			// $("#RetailerName").trigger("chosen:updated");
   			document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].options.remove(0);
			document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].options.add(new Option("----Select----",""));
			document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].value="";
			
			$('#'+tableID+' select#RetailerName:last').focus();
			// drpfuncretailerselect();
        }

		function removeRow(src, type){
			var del = true;
			if(type == 'edit') {
				var del = confirm('Are you want to remove selected row?');
			}
			if(del) {
				var sourceTableID = 'dataTable';       
				var oRow = src.parentElement.parentElement;  
				document.getElementById(sourceTableID).deleteRow(oRow.rowIndex);  
			}
		}
 
        function deleteRow(tableID, row) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;
			if(rowCount <= 3) {
				alert("Cannot delete all the rows.");
			}
			else
					{
						
						 if(document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[0].value!=""||document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[1].value!=""||document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[2].value!="")
						 {
							  var deleting= confirm("Do you really want to delete the row containing information??");
						    if (deleting== true)
							{
							   table.deleteRow(rowCount-2);
							}
							else
							{
								
								if(document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[0].value!="")
								{
									if(document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[1].value!="")
								{
									document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[2].focus();
								}
								else
								{
								document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[1].focus();
								}
								}
								else
								{
								document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("Select")[0].focus();
								}
								
							}
						 }
						 else
						 {
						  		table.deleteRow(rowCount-2);
						 }
					}
			
			
            }
			catch(e) {
                alert(e);
            }
        }

function validatePricelistCode(key)
{
	var object = document.getElementById('pricelistcode');
	if (object.value.length <15 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 15 characters");
	toutfun(object);
return false;
}
}

function validatePricelistName(key)
{
	var object = document.getElementById('pricelistname');
	if (object.value.length <50 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 50 characters");
	toutfun(object);
	
return false;
}
}

  var element;
 function isDecimal(str){
        if(isNaN(str)){
          if(element=="mrp")
                             {  
                                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value='';   
                                    form.num.focus();
                              }
          else if(element=="fprice")
                               {
                                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value='';    
                                  }
                                   else if(element=="rprice")
                                   {
                                    document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value='';       
                                     }  
                                      else if(element=="iprice")
                                       {
                                        document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value='';   
                                          }  
                                           // form.num.focus();
        }
        else{
        str=parseFloat(str);
                
              if(element=="mrp")
                  {
                  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value=str.toFixed(2);
                     form.num.focus();
                  }
                  else if(element=="fprice")
                  {
                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value=str.toFixed(2);   
                  }
                   else if(element=="rprice")
                  {
                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value=str.toFixed(2);  
                  }  
                 else if(element=="iprice")
                  {
                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value=str.toFixed(2);  
                  }
            }
            }
function validate()
{
   
    var srcElem = window.event.srcElement;
              element=  srcElem.id;
              
             rowNum = srcElem.parentNode.parentNode.rowIndex ;
         	
                  if(element=="mrp")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value;
                     
                  }
                  else if(element=="fprice")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value;    
                  }
                   else if(element=="rprice")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value;    
                  }  
                 else if(element=="iprice")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value;    
                  }
                 
                 if (dec == "")
                 {
                     
                         if(element=="mrp")
                             {
                                  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value='';   
                                form.num.focus();
                             }
                              else if(element=="fprice")
                               {
                                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value='';    
                                  }
                                   else if(element=="rprice")
                                   {
                                    document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value='';       
                                     }  
                                      else if(element=="iprice")
                                       {
                                        document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value='';   
                                          }  
                           
          form.num.focus();
             
                    return false;
                }
                if (isDecimal(dec)==false)
                {
                   num="";
                   form.num.focus();
                    return false;
                 }
                      return true;
   }

        function getagentids()
        { 
	// 	  var table = document.getElementById('dataTable');
 //    var rowCount = table.rows.length;
	// var idcount = rowCount-2;
		var srcElem = window.event.srcElement;
		rowNum = srcElem.parentNode.parentNode.rowIndex ;
		// var chosen = document.getElementsByClassName('chosen-results');
		// console.log(chosen);
		// var elements = document.getElementById('dataTable').rows[rowNum].getElementsByTagName('*');
		// console.log(elements);
		// console.log(document.getElementById('dataTable').rows[rowNum].children);
		// var e=document.getElementById('dataTable').rows[idcount].getElementsByTagName("select")[0];
		var e=document.getElementById('dataTable').rows[rowNum].getElementsByTagName("select")[0];
		var er=e.options[e.selectedIndex].value;
		// console.log(er);
		var ty = er.split("~");
		tt = ty[1];
		document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[0].value=tt;
					// document.getElementById('dataTable').rows[idcount].getElementsByTagName("input")[0].value=tt;
		}

		function datecheck(e,ele){
			var srcElem = window.event.srcElement;
			var rowNum = srcElem.parentNode.parentNode.rowIndex;
			var returnval;
			var element=  srcElem.id;
			// console.log(e.which);
		    if((e.which >=48 && e.which<= 57) || (e.which >=96 && e.which<= 105) || e.which == 8 ){
				var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[ele].value;
		    	var n = dec.length;
		    	var date_regex = /^(0[1-9]|[12][0-9]|3[01])[\-\/.](0[1-9]|1[012])[\-\/.](19|2\d)\d\d$/;
		    	var dayreg = /^(0[1-9]|[12][0-9]|3[01])$/;
		    	var monthreg = /^(0[1-9]|1[012])$/;
		    	var yearreg = /^(19|2\d)\d\d$/;

		    	if(n==2){
		    		if(dayreg.test(dec)){
		    			dec = dec+'-';
		    			document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
		    		}else{
		    			document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value='';
		    		}	
		    	}
		    	if(n==3){
		    		// var dayfield=dec.split("-")[0];
		    		// str.indexOf("e", 5);
		    		// str.substring(1, 4);
		    		var dayfield = dec.indexOf('-');
		    		if(dayfield == -1){
		    			var day = dec.substring(0,2)+'-'+dec.substring(2);
		    			document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=day;
		    		}

		    		// console.log(dayfield);
		    	}
		    	if(n==5){
		    		var dayfield=dec.split("-")[0];
					var monthfield=dec.split("-")[1];
					if(dayreg.test(dayfield) && monthreg.test(monthfield)){
		    			dec = dec+'-';	
						document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
					}else if(!monthreg.test(monthfield)){
						dec = dayfield+'-';
						document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
					}else if(!dayreg.test(dayfield)){
						dec = '-'+monthfield;
						document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
					}else{
						document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value="";	
					}
		    	}
		    	if(n==6){
		    		// var dayfield=dec.split("-")[0];
		    		// str.indexOf("e", 5);
		    		// str.substring(1, 4);
		    		var dayextract = dec.substring(0,3);
		    		var dayfield = dayextract.indexOf('-');
		    		var monthfield = dec.indexOf('-',3)
		    		if(dayfield == -1 && monthfield == -1){
		    			document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value="";
		    		}else if(monthfield == -1){
		    			var day = dec.substring(0,2)+'-';
		    			// var month = dec.substring('')
		    			document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=day;
		    		
		    		}else if(dayfield == -1){
		    			// var day = dec.substring(0,2)+'-'+dec.substring(2);
		    			document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value="";
		    		}
		    		else{
		    			var dayfield=dec.split("-")[0];
						var monthfield=dec.split("-")[1];
						if(dayreg.test(dayfield) && monthreg.test(monthfield)){
			    			// dec = dec+'-';	
							document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
						}else if(!monthreg.test(monthfield)){
							dec = dayfield+'-';
							document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
						}else if(!dayreg.test(dayfield)){
							dec = '-'+monthfield;
							document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
						}else{
							document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value="";	
						}
		    		}


		    		// console.log(dayfield);
		    	}
		    	if(n==10){
		    		var dayfield=dec.split("-")[0];
					var monthfield=dec.split("-")[1];
					var yearfield=dec.split("-")[2];
					if(dayreg.test(dayfield) && monthreg.test(monthfield) && yearreg.test(yearfield) ){
						document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
					}else{
						dec = dayfield+'-'+monthfield;	
						document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
					}
		    	}
		    }
		    else if(e.which==37||e.which==189||e.which==38||e.which==39||e.which==40|| e.which==8 || e.which==9|| e.which==46 || e.which==36 || e.which==35){
		    	// dec = dec.substring(0,n-1);
		    	var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[ele].value;
		    	var n = dec.length;
		    	document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
		    	
		    }else{
		    	var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[ele].value;
		    	var n = dec.length;
		    	dec = dec.substring(0,n-1);
		    	document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
		    	// return false;
		    }
		    if(e.which==8){
		    	var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[ele].value;
		    	var n = dec.length;
		    	document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].value=dec;
		    	
		    }
		    if(n != 10){
		    	document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[ele].focus();
		    	return false;
		    }
		    return returnval;
		    
		}
		// function checkdate(){
		// 	var srcElem = window.event.srcElement;
		// 	var rowNum = srcElem.parentNode.parentNode.rowIndex ;
  //           var dateval =  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[4].value;
            
		// }

</script>
<script type="text/javascript">

/**--------------------------
//* Validate Date Field script- By JavaScriptKit.com
//* For this script and 100s more, visit http://www.javascriptkit.com
//* This notice must stay intact for usage
---------------------------**/

function checkdate(input){
var validformat=/^\d{2}\-\d{2}\-\d{4}$/;//Basic check for format validity
var returnval=false;
// console.log(input);
var srcElem = window.event.srcElement;
rowNum = srcElem.parentNode.parentNode.rowIndex ;
if(input.length == 10){
	if (!validformat.test(input))
	alert("Invalid Date Format. Please correct and submit again.");
	else{ //Detailed check for valid date ranges
	var dayfield=input.split("-")[0];
	var monthfield=input.split("-")[1];
	var yearfield=input.split("-")[2];
	var dayobj = new Date(yearfield, monthfield-1, dayfield)
	if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield)){
		alert("Invalid Day, Month, or Year range detected. Please correct and submit again.");
		document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].focus();
	}
	else
	returnval=true;
	}
}else{
	document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].focus();
}
// if (returnval==false) input.select()
return returnval;
}
</script>
<script type="text/javascript"> 

function resetvalue(retailervalue){
	 var table = document.getElementById("dataTable");
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount-1);
	var ty = retailervalue.split("~");
	tt = ty[1];
	
}
// function fsetvalue()
// {
	
// 	var e = document.getElementById("branch"); 
// 	var er=e.options[e.selectedIndex].value;
// 	var ddlArray= new Array();
// 	var ddl = document.getElementById('branchlist');
// 	var tt,tt2;
// 	for (i = 0; i < ddl.options.length; i++) 
// 	{
// 		ddlArray[i] = ddl .options[i].value;
// 		var ty = ddlArray[i].split("~");
// 		var p  = ty[0];
// 		var p2 = ty[1];
		
// 		// console.log(p+''+p2);
// 		if(p2==er)
// 		{
// 			tt  = p;
			
// 		}
// 		else if(er=="")
// 		{
// 			tt  ="";
// 			tt2 ="";
// 		}
		
// 	}
// }
// function fransetvalue()
// {
	
// 	var e = document.getElementById("franchise"); 
// 	var er=e.options[e.selectedIndex].value;
// 	var ddlArray= new Array();
// 	var ddl = document.getElementById('franlist');
// 	var tt,tt2;
// 	for (i = 0; i < ddl.options.length; i++) 
// 	{
// 		ddlArray[i] = ddl .options[i].value;
// 		var ty = ddlArray[i].split("~");
// 		var p  = ty[0];
// 		var p2 = ty[1];
		
// 		// console.log(p+''+p2);
// 		if(p2==er)
// 		{
// 			tt  = p;
			
// 		}
// 		else if(er=="")
// 		{
// 			tt  ="";
// 			tt2 ="";
// 		}
// 		document.getElementById("franchisecode").value=tt;
		
// 	}
// }
function fransetvalue()
{
	
	var e = document.getElementById("franchise"); 
	var er=e.options[e.selectedIndex].value;
	document.getElementById("franchisecode").value=er;
}
    </script>
<title>Amara Raja|| Retailer Target Master</title>
</head>
 <?php  
 	?>
<? if(!empty($_GET['FranchiseCode']) && !empty($_GET['RetailerCode'])){?>
<body class="default" onLoad="document.form1.target.focus()">
 <? 
}else if(!empty($_GET['name_retailer'])){?>
<body class="default" onLoad="document.form1.name_retailer.focus()">
 <? }else if(!empty($_GET['code_retailer'])){?>
<body class="default" onLoad="document.form1.code_retailer.focus()">
 <? }else{ ?>
 	<body class="default" onLoad="document.form1.region.focus()">
  <? }
  ?>
 <center>

<?php include("../../menu.php")  ?>
<script src="inc/multiselect.js" type="text/javascript"></script>
<!-- <link rel="stylesheet" href="inc/chosen.css"> -->
<!-- chosen.jquery.min -->


 <!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="form1"  >
             <?php
echo '<table id="default" style="height:10px; display:none;" ><tr><td><select  name="emplist" id="emplist">';
$que = mysql_query("SELECT Distinct(RegionName),branchname FROM view_rptfrnfin");
while ($record = mysql_fetch_array($que)) {
    echo "<option value=\"" . $record['RegionName'] . "~" . $record['branchname'] . "\">" . $record['RegionName'] . "~" . $record['branchname'] . "\n ";
}
echo '</select></td></tr><tr><td><select  name="branchlist" id="branchlist"  >';
$que = mysql_query("SELECT branchcode, branchname FROM branch order by branchname asc");
while ($record = mysql_fetch_array($que)) {
    echo "<option value=\"" . $record['branchcode'] . "~" . $record['branchname'] . "\">" . $record['branchcode'] . "~" . $record['branchname'] . "\n ";
}
echo '</select></td><td><select  name="franlist" id="franlist">';
$que = mysql_query("SELECT Franchisecode,Franchisename FROM franchisemaster");
while ($record = mysql_fetch_array($que)) {
    echo "<option value=\"" . $record['Franchisecode'] . "~" . $record['Franchisename'] . "\">" . $record['Franchisecode'] . "~" . $record['Franchisename'] . "\n ";
}
echo '</select></td><td><select  name="rclist" id="rclist">';
$retque = mysql_query("SELECT fmexecutive,RetailerName,RetailerCode FROM retailermaster where isretailer='Yes'");
while ($record = mysql_fetch_array($retque)) {
    echo "<option value=\"" . $record['fmexecutive'] . "~" . $record['RetailerName'] . "~" . $record['RetailerCode'] . "\">" . $record['Franchisename'] . "~" . $record['RetailerName'] . "~" . $record['RetailerCode'] . "\n ";
}
echo '</select></td><td><select  name="forlist" id="forlist">';
$que = mysql_query("SELECT branchname,Franchisecode,Franchisename FROM view_rptfrnfin");
while ($record = mysql_fetch_array($que)) {
    echo "<option value=\"" . $record['branchname'] . "~" . $record['Franchisecode'] . "~" . $record['Franchisename'] . "\">" . $record['branchname'] . "~" . $record['Franchisecode'] . "~" . $record['Franchisename'] . "\n ";
}
echo '</select></td></tr></table>';
?>
                                          
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Retailer Target </p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Region  Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              
							  <?php
if (empty($_GET['FranchiseCode']) && empty($_GET['RetailerCode'])) {
    echo '<select name="region[]" id="region" onChange="drpfunc();"  ><option value="0">----Select----</option>';
    $list = mysql_query("SELECT regioncode, regionname FROM region order by regionname asc");
    foreach ($_POST['region'] as $selectedOption2) {
        if ($selectedOption2 != "0") {
            $regionname = $selectedOption2;
        }
    }
    while ($row_list = mysql_fetch_assoc($list)) {
        $selected = '';
        if ($row_list['regionname'] == $regionname) {
            $selected = ' selected ';
        }
?>
                    <option value="<? echo $row_list['regionname']; ?>"<? echo $selected; ?>> <? echo $row_list['regionname']; ?> </option>
                    <?
                                            }
                                            ?>
                    </select>
                    <? }else { ?>
                    	<input type="text" id="region"  value="<?php echo $edit_regionname; ?>" name="region[]" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" />
                    <? } ?>
                             </div>
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Franchisee  Name</label><label style="color:#F00;">*</label>
                               </div>
                             	<div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                             		<? if(empty($_GET['FranchiseCode']) && empty($_GET['RetailerCode'])) { ?>
                             			  <select  name='franchise[]' id='franchise' onChange="fransetvalue();drpfuncretailerselect();">
                                            <option value="0">----Select----</option>
                                            <?
                                            $add_qry = '';
                                            $franchise_select = ($_POST['franchise']) ? $_POST['franchise'] : '';
                                            foreach ($_POST['franchise'] as $selectedOption2){
                                                if($selectedOption2!="0")
                                                {
                                                    $franchise_select = $selectedOption2;
                                                }
                                            }
                                            $list = mysql_query("SELECT Franchisecode, Franchisename FROM franchisemaster order by Franchisename asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';
                                                if ($row_list['Franchisecode'] == $franchise_select) {
                                                    $selected = ' selected ';
                                                ?>
                                                <option value="<? echo $row_list['Franchisecode']; ?>" <? echo $selected; ?>><? echo trim($row_list['Franchisename']); ?></option>
                                                <?
                                                }
                                            }
                                            ?>
                                        </select>
                                        <select name="RetailerSelect" id="RetailerSelect" style="display:none;" >

                                        </select>
                             		<? }else{ ?>
                             		<input type="text" id="franchise"  value="<?php echo $edit_franchisename; ?>" name="franchise[]" style="margin-left:18px;border-style:hidden; background:#f5f3f1; width:350px;" readonly="readonly" />      	
                                       <? } ?>
                             	</div>
                             	

                           </div>                             
                     <!-- col1 end -->  
                     
                     <!-- col2 -->   
  		<div style="width:400px; overflow:auto; height:auto; float:left; padding-left:150px,padding-bottom:5px; margin-left:100px;" class="cont">
    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Branch  Name</label><label style="color:#F00;">*</label>
                               </div>
                             	<div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                             		<?if(empty($_GET['FranchiseCode']) && empty($_GET['RetailerCode'])) { ?>
                             		<!-- <select name='branch[]' id='branch'  onChange="fsetvalue();drpfunc1();"> -->
                             		<select name='branch[]' id='branch'  onChange="drpfunc12();">
                                            <option value="0">----Select----</option>
                                       		 <? $list = mysql_query("SELECT branchcode, branchname FROM branch order by branchname asc");
                                           	 foreach ($_POST['branch'] as $selectedOption2){
                                                if($selectedOption2!="0")
                                                {
                                                    $branchname = $selectedOption2;
                                                }
                                            }
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['branchname'] == $branchname){
                                                    $selected = ' selected ';
                                                
                                                ?>
                                                <option value="<? echo $row_list['branchname']; ?>"<? echo $selected; ?>>
    													<? echo $row_list['branchname']; ?>
                                                </option>

                                                <? }
                                            }
                                            ?>
                                        </select>
                                        <?}else{ ?>
                                  				<input type="text" id="branch"  value="<?php echo $edit_branchname; ?>" name="branch[]" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" />      	
                                       <? } ?>

                             	</div>
			   
              <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Franchisee Code</label>
              </div> 
               <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
              	<input type="text" id="franchisecode"  value="<?php echo $edit_franchisecode; ?>" name="franchisecode[]" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" />
              </div>                     
            </div>
                   
   <div style="width:925px; height:200px; overflow:auto; float:left;  margin-top:8px; margin-left:5px;">
    <TABLE  id="dataTable"  width="350px;" border='1'>
  	<tr>
	  	<td  style=" font-weight:bold; width:60px; text-align:center;">Retailer Name<label style="color:#F00;">*</label></td> 
	    <td  style=" font-weight:bold; width:60px; text-align:center;">Retailer Code<label style="color:#F00;">*</label></td>
	    <td  style=" font-weight:bold; width:60px; text-align:center;">Target Qty<label style="color:#F00;">*</label></td>
	    <td  style=" font-weight:bold; width:60px; text-align:center;">Signage Size<label style="color:#F00;">*</label></td>
	    <td  style=" font-weight:bold; width:60px; text-align:center;">Signage Type<label style="color:#F00;">*</label></td>
	    <td  style=" font-weight:bold; width:60px; text-align:center;">Date of Installation<label style="color:#F00;">*</label></td>
		<td  style=" font-weight:bold; width:10px;">&nbsp;</td>
    </tr>
    <?
    
    
	// if(!isset($_POST['Search'])){
	// 	$displaydown = mysql_query("SELECT * FROM retailertarget order by m_date desc");
	// }
	if($result12!="")
	{
		$i = 0;
	while($myretailertarget = mysql_fetch_array($result12))
			{
				$productcode='';
	     $target = $myretailertarget['Target'];
		 $signage_type= $myretailertarget['Signagetype'];
		 $SignageSize =	$myretailertarget['SignageSize'];
		 $doi = date('d-m-Y',strtotime($myretailertarget['DOI']));;
		 $retailercode = $myretailertarget['RetailerCode'];
		 $i++;
	?>
        <TR>
         <TD style='text-align:center'> 
		<? $retailer_result_grid = mysql_query("select RetailerName from retailermaster where RetailerCode='".$retailercode."'"); 
			while ($row_list = mysql_fetch_assoc($retailer_result_grid)){ ?>

          	 <input type="text" id="RetailerName"  value="<?php echo $row_list['RetailerName']; ?>" name="RetailerName[]" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" />
			<? }
		?>
            </TD>
             <TD style='text-align:center'><INPUT type="text" name="retailercode[]" id="retailercode1" value='<? echo trim($retailercode); ?>' onChange="validate(this)" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly"/></TD>
            <TD style='text-align:center'><INPUT type="text" name="target[]" id="target" style="width:80px;" value='<? echo trim($target); ?>'  onChange="validate(this)" /></TD>
            <TD style='text-align:center'><INPUT type="text" name="signagesign[]" id="signagesign" onChange="validate(this)" value='<? echo trim($SignageSize); ?>' style="width:80px;"/></TD>
            <TD style='text-align:center'><INPUT type="text" name="signagetype[]" id="signagetype" onChange="validate(this)" value='<? echo trim($signage_type); ?>' style="width:80px;"/></TD>
            <TD style='text-align:center'><INPUT type="text" name="doi[]" id="doi1" onkeyup="datecheck(event,5);" onChange="validate(this)" style="width:80px;" value='<? echo trim($doi); ?>' placeholder='dd-mm-yyyy' maxlength="10"/></TD>
			<TD  class="remove_btn">
			<?php if($i>1) { ?> <img src="del_img.jpg" style='cursor: pointer; width:20px;' onclick='removeRow(this, "edit");'/>
			<?php } ?>
			</TD>
			
        </TR>
        <?
				}
				$retailercode='';
		}
		else
		{
			$j = 0;
		?>
        <TR>
             <TD style='text-align:center'>
             	<select name='RetailerName[]' id = 'RetailerName' class='chosen-select-no-results' style='margin-top:5px;width:300px;' onChange="getagentids();" />
                     <option value="0">----Select----</option>
                </select></TD>
            <TD style='text-align:center'><INPUT type="text" name="retailercode[]" id="retailercode" onChange="validate(this)" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" /></TD>
            <TD style='text-align:center'><INPUT type="text" name="target[]" id="target" style="width:80px;"  onChange="validate(this)" /></TD>
            <TD style='text-align:center'><INPUT type="text" name="signagesign[]" id="signagesign" onChange="validate(this)" style="width:80px;"/></TD>
            <TD style='text-align:center'><INPUT type="text" name="signagetype[]" id="signagetype" onChange="validate(this)"  style="width:80px;"/></TD>
            <TD style='text-align:center'><INPUT type="text" name="doi[]" id="doi1" onkeyup="datecheck(event,4);" onChange="validate(this)" style="width:80px;" placeholder='dd-mm-yyyy' class="table_last_field doidate" maxlength="10"/></TD>
      <TD  class="remove_btn">&nbsp;</TD>
         
        </TR>
        <?
        $j++;
		}
		?>
		<tr><td colspan="7" style="height: 0px;"></td></tr>
         </TABLE></div>
    
			   </div>
               
                <!-- main row 1 end-->
                
                  <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:10px;">     
                   
					<div style="width:225px; height:50px; float:left;  margin-left:14px; margin-top:-3px;" id="center1">
                    
                        <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                        
                    <?php      if(!empty($_GET['FranchiseCode']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button save_button" id="addbutton" value="Save" onclick="changedisable();" >
				          <? } ?>
		              </div>
                           
                          <div style="width:80px; height:32px; float:left;margin-top:16px; margin-left:10px; ">
						  <input name="Cancel" type="submit" class="button" value="Reset">
		              </div>    
	              </div>                          
                                                   
		       
                         
               <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:95px; height:30px; float:left; margin-left:3px; margin-top:16px;" >
                                <label>Retailer Name</label>
                               </div>
                               <div style="width:130px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                 <input type="text" name="name_retailer" id="name_retailer" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['name_retailer']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:95px; height:30px; float:left; margin-left:3px; margin-top:16px;">
                                  <label>Retailer Code</label>
                               </div>
                               <div style="width:130px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                 <input type="text"   name="code_retailer" id='code_retailer' onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['code_retailer']?>"/>
                               </div>
                               <div style="width:83px; height:32px; float:left; margin-top:16px;">
                                <input id="Search" type="submit" name="Search" value="" class="button1"/>
                               </div>  
                               </div>
                               </div>	
                          <!--Row2 end-->
          <!--  grid start here-->
             
              <div style="width:900px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:19px; overflow:auto;" class="grid">
                    
                  <table id="datatable1" align="center" class="sortable" border="1" width="870px">
    <tr > 
 	 <?  
	 if(($row['deleterights'])=='Yes')
		{
	?>    
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="8px">
     <input type='checkbox' id="checkall" name='checkall' onclick='checkedAll(form1);'></td>
   	<? 
   		}
    if(($row['editrights'])=='Yes') 
	  	{ 
	 ?>
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="12px">Action</td>
      <? 
		} 
	  ?>
  <td style=" font-weight:bold;">Franchisee Name</td>  
  <td style=" font-weight:bold;">Retailer Name</td>
  <td style=" font-weight:bold;">Retailer Code</td>
  <td style=" font-weight:bold;">Target </td>
  <td style=" font-weight:bold;">Signage Size</td>
  <td style=" font-weight:bold;">Signage Type</td>
  <td style=" font-weight:bold;">Date Of Installation</td>
  </tr>
 <?php
 	// $rownum = mysql_num_rows($displaydown);
 	if($myrow1 > 0){
      while( $record = mysql_fetch_array($query))
    { 
    ?>
    
     <tr>
      <?  
	 if(($row['deleterights'])=='Yes')
		{
	?> 
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $record['Franchisecode'].'~'.$record['RetailerCode']; ?>"  onchange="test();"></td>
       	<? 
   		}
    if(($row['editrights'])=='Yes') 
	  	{ 
	 ?>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"> <a style="color:#FF2222" name="edit" href="retailer_target.php?<? echo 'FranchiseCode=';echo $record['Franchisecode'];echo '&RetailerCode=';echo $record['RetailerCode'];?>">Edit</a></td>
     <? 
		} 
	  ?>
    
     <td  bgcolor="#FFFFFF"  align="left">
     	<? $franchise_result = mysql_query("select Franchisename FROM franchisemaster where Franchisecode = '".$record['Franchisecode']."'"); 
     	while($rows = mysql_fetch_array($franchise_result)){

     	?>
        <?=$rows['Franchisename']; } ?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
    	<? $retailer_result = mysql_query("select RetailerName FROM retailermaster where RetailerCode='".$record['RetailerCode']."'");
       while($rows = mysql_fetch_array($retailer_result)){

     	?>
        <?=$rows['RetailerName']; } ?>


    </td>
    <td  bgcolor="#FFFFFF" align="left">
       <?=$record['RetailerCode'] ?>

    </td>
    <td  bgcolor="#FFFFFF" align="left">
       <?=$record['Target'] ?>

    </td>
    <td  bgcolor="#FFFFFF" align="left">
       <?=$record['SignageSize'] ?>

    </td>
    <td  bgcolor="#FFFFFF" align="left">
       <?=$record['Signagetype'] ?>

    </td>
    <td  bgcolor="#FFFFFF" align="left">
       <?=date('d-m-Y',strtotime($record['DOI'])); ?>

    </td>
    </tr>  
  <?php
      }
  }	
    
  ?>
    
<?php
  if(isset($_POST['Search']))
{
	
if($myrow1==0)	
{?>
		<? echo '<tr ><td colspan="11" align="center" bgcolor="#FFFFFF" style="color:#F00"  >No Records Found</td></tr>'; ?>	
<? } }?>
</table>
 </div> 
                <?php include("../../paginationdesign.php") ?>
  <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
   <div style="width:70px; height:32px; float:left; margin-left:25px; margin-top:20px;">
  		Export As
	
   </div> 
 <div style="width:83px; height:32px; float:left; margin-left:5px; margin-top:12px;">
    <select name="Type">
       <option value="CSV">CSV</option>
    </select>
	
 </div>
<div style="width:63px; height:32px; float:right; margin-top:18px;">
	  <input type="submit" name="PDF" value="Export" class="button"/>
  
</div ></div>
               <!--  grid end here-->
        
             <!-- form id start end-->      
       <br /><br />
       <input type="hidden" value="0" id="last_inc_count" />  
       <!--Third Block - Menu -Container -->
    </form>
</div>
</div>
</div>
<!--Footer Block --><!--Footer Block - End-->

<div id="footer-wrap1">
  <?php include("../../footer.php") ?>
</div>
</center></body>
</html>
<?
$productcode='';
} ?>
<!-- <script src="inc/chosen.jquery.min.js" type="text/javascript"></script> -->
<!-- <script src="inc/chosen.jquery.js" type="text/javascript"></script> -->
<!-- <script src="inc/prism.js" type="text/javascript" charset="utf-8"></script> -->
  <script type="text/javascript">
    // var config = {
    //   '.chosen-select'           : {},
    //   '.chosen-select-deselect'  : {allow_single_deselect:true},
    //   '.chosen-select-no-single' : {disable_search_threshold:10},
    //   '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
    //   '.chosen-select-width'     : {width:"95%"}
    // }
    // for (var selector in config) {
    //   $(selector).chosen(config[selector]);
    // }
  </script>
<?
if(!empty($_SESSION['retailer_code_error'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=errorlogfile.php">';
    exit;
    
}
?>

<script type="text/javascript">
	$("#Search").click(function(){
		var ret_name = $('#name_retailer').val();
    var ret_code = $('#code_retailer').val();
    if(ret_name == "" && ret_code=="" ){
    	alert("Enter Search Fields!");
       return false;
    }
	});
</script>
