<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $salesinvoiceno,$TertiarySalesEntryDate,$BatterySlNo,$DateofSale,$Salestype,$ProductCode,$ProductDescription,$CustomerName,$CustomerAddress,$City,$CustomerPhoneNo,$RetailerName,$FranchiseeName,$VehicleorInverterModel,$VehicleorInverterMake,$VehicleSegment,$Enginetype,$VehicleNo,$ManufacturingDate,$checkbox,$tname,$scode,$batterystatus,$oldbatteryno,$oemname,$Category;
	$scode = 'BatterySlNo';
	$sname = '';
	$tname	= "serialnumbermaster";
    //require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	//$newsRecordSet = $news->getNews($tname);
	$pagename = "SerialNO";
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
            <script type="text/javascript">alert("you are not allowed to do this action..!!",'serialnumber.php');</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action..!!",'serialnumber.php');
			//setInterval(function(){document.location='serialnumber.php';},2000);
			//document.location='serialnumber.php';	
			</script>
         <?
	}
	
//search function


if(isset($_POST['Search']))
{
	if(isset($_POST['codes'])||isset($_POST['names']))
	{
		$_SESSION['codesval']=$_POST['codes'];
		$_SESSION['namesval']=$_POST['names'];
		if(empty($_POST['codes'])&&empty($_POST['names']))
		{
			$_SESSION['codesval']='';
			$_SESSION['namesval']='';
			$myrow1=1;
			?><script type="text/javascript">alert("Please enter some search criteria!");
				setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
				</script><?
		}
		else
		{
			if(!empty($_POST['codes'])&&!empty($_POST['names']))
			{
				$condition="SELECT * FROM ".$tname." WHERE ".$scode." like'".$_POST['codes']."%' OR ".$sname." like'".$_POST['names']."%' and voucherstatus!='CANCEL'";
			}
			else if(!empty($_POST['codes'])&&empty($_POST['names']))
			{
				$condition="SELECT * FROM ".$tname." WHERE ".$scode." like'".$_POST['codes']."%' and voucherstatus!='CANCEL'";
			}
			else if(!empty($_POST['names'])&&empty($_POST['codes']))
			{
				$condition="SELECT * FROM ".$tname." WHERE ".$sname." like'".$_POST['names']."%' and voucherstatus!='CANCEL'";
			}
			else
			{
				$condition="SELECT * FROM ".$tname." WHERE voucherstatus!='CANCEL'";
			}
			$refer=mysql_query($condition);
			$myrow1 = mysql_num_rows($refer);		
			$page = (int) (!isset($_GET["page"]) ? 1 : 1);
			$startpoint = ($page * $limit) - $limit;
			//to make pagination
			$statement = $tname;
			 //show records
			$starvalue = $myrow1;
		    $query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
			if($myrow1==0)	
			{
				?><script type="text/javascript">alert("Data not found!");
				setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
				</script><?
			}
		}
	}
}
else
{
	if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
	{		
		$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
		$startpoint = ($page * $limit) - $limit;
		$statement = $tname; 
		$startvalue= "";
		$condition="SELECT * FROM ".$tname." WHERE voucherstatus!='CANCEL'";
		$query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
	}
	else
	{
		if(!empty($_SESSION['codesval'])&&!empty($_SESSION['namesval']))
		{
			$condition="SELECT * FROM ".$tname." WHERE ".$scode." like'".$_SESSION['codesval']."%' OR ".$sname." like'".$_SESSION['namesval']."%' and voucherstatus!='CANCEL'";
		}
		else if(!empty($_SESSION['codesval'])&&empty($_SESSION['namesval']))
		{
			$condition="SELECT * FROM ".$tname." WHERE ".$scode." like'".$_SESSION['codesval']."%' and voucherstatus!='CANCEL'";
		}
		else if(!empty($_SESSION['namesval'])&&empty($_SESSION['codesval']))
		{
			$condition="SELECT * FROM ".$tname." WHERE ".$sname." like'".$_SESSION['namesval']."%' and voucherstatus!='CANCEL'";
		}
		else
		{
			$condition="SELECT * FROM ".$tname." WHERE voucherstatus!='CANCEL'";
		}
		$refer=mysql_query($condition);
		$myrow1 = mysql_num_rows($refer);
		$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
		$startpoint = ($page * $limit) - $limit;
		//to make pagination
		$statement = $tname;
		//show records
		$starvalue = $myrow1;
		$query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
	}
}
	

$_SESSION['query']=$condition;
$_SESSION['type']=NULL;

	//$_SESSION['query']=$serialnumbermasterqry;
	//$serialnumbermasterqry='select * from serialnumbermaster';
if(isset($_POST['PDF']))
{
$select=$_POST['Type'];

if($select=='Excel')
{
	$_SESSION['type']='Excel';

	header('Location:ExportSerialno.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	header('Location:ExportSerialno.php');
}
}	
//Save function	
if(isset($_POST['Save']))
{
	unset($_SESSION['codesval']);
	$TertiarySalesEntryDate=$_POST['TertiarySalesEntryDate'];
	$DateofSale=$_POST['DateofSale'];
	$salesinvoiceno=$_POST['salesinvoiceno'];
	$batterystatus= $_POST['batterystatus'];
	$oldbatteryno= strtoupper($_POST['oldbatteryno']);
	$ProductCode=$_POST['ProductCode'];
	$ProductDescription=$_POST['ProductDescription'];
	$ManufacturingDate=$_POST['ManufacturingDate'];
	$BatterySlNo=strtoupper($_POST['BatterySlNo']);
	$CustomerName=$_POST['CustomerName'];
	$CustomerAddress=$_POST['CustomerAddress'];
	$CustomerPhoneNo=$_POST['CustomerPhoneNo'];
	$RetailerName=$_POST['RetailerName'];
	$FranchiseeName=$_POST['FranchiseeName'];
	$VehicleorInverterModel=$_POST['VehicleorInverterModel'];
	$VehicleorInverterMake=$_POST['VehicleorInverterMake'];
	$VehicleSegment=$_POST['VehicleSegment'];
	$Enginetype=$_POST['Enginetype'];
	$VehicleNo=$_POST['VehicleNo'];
	$Category= $_POST['Category'];
	$oemname= $_POST['oemname'];
	
	
	$post['Category'] =$_POST['Category'];
	$oemnameCode=mysql_query("SELECT * FROM oemmaster where oemname='".$_POST['oemname']."'");
	$oemnamefetch=mysql_fetch_array($oemnameCode);
	$post['oemname'] = $oemnamefetch['oemcode'];
	$test1 = $news->dateformat($_POST['TertiarySalesEntryDate']);
	$post['TertiarySalesEntryDate']=$test1;
	$post['BatterySlNo'] =strtoupper($_POST['BatterySlNo']);
	$test = $news->dateformat($_POST['DateofSale']);
	$post['DateofSale']=$test;
	$post['salesinvoiceno'] =$_POST['salesinvoiceno'];
	$post['ProductCode'] =$_POST['ProductCode'];
	$post['CustomerName'] =($_POST['CustomerName']);
	$post['CustomerAddress'] =($_POST['CustomerAddress']);
	$post['City'] =($_POST['City']);
	$post['CustomerPhoneNo'] =($_POST['CustomerPhoneNo']);
	$post['RetailerName']=($_POST['RetailerName']);
	$post['FranchiseeName'] =($_POST['FranchiseeName']);
	$countrysmallCode=mysql_query("SELECT * FROM vehiclemodel where modelname='".$_POST['VehicleorInverterModel']."'");
	$countrysmallfetch=mysql_fetch_array($countrysmallCode);
	$post['VehicleorInverterModel'] = $countrysmallfetch['modelcode'];
	$statesmallCode=mysql_query("SELECT * FROM vehiclemakemaster where MakeName='".$_POST['VehicleorInverterMake']."'");
	$statesmallfetch=mysql_fetch_array($statesmallCode);
	$post['VehicleorInverterMake'] = $statesmallfetch['MakeNo'];
	$branchsmallCode=mysql_query("SELECT * FROM vehiclesegmentmaster where segmentname='".$_POST['VehicleSegment']."'");
	$branchsmallfetch=mysql_fetch_array($branchsmallCode);
	$post['VehicleSegment'] = $branchsmallfetch['segmentcode'];
	$post['Enginetype'] =($_POST['Enginetype']);
	$post['VehicleNo'] =($_POST['VehicleNo']);
	$test2 = $news->dateformat($_POST['ManufacturingDate']);
	$post['ManufacturingDate'] =$test2;
	$post['batterystatus']= $_POST['batterystatus'];
	$post['oldProductCode'] =$_POST['oldProductCode'];
	$post['oldbatteryno']= strtoupper($_POST['oldbatteryno']);
	
	$post['user_id'] = $_SESSION['username'];
	date_default_timezone_set ("Asia/Calcutta");
	$post['m_date']= date("y/m/d : H:i:s", time());
	
	
	if(!empty($_POST['BatterySlNo'])&&!empty($_POST['DateofSale'])&&!empty($_POST['ProductCode'])&&!empty($_POST['ManufacturingDate'])&&!empty($_POST['Category'])&&!empty($_POST['batterystatus'])&&!empty($_POST['CustomerName'])&&!empty($_POST['VehicleorInverterModel'])&&!empty($_POST['VehicleorInverterMake'])&&!empty($_POST['VehicleSegment'])&&!empty($_POST['Enginetype']))
	{
		
	$oldProductCode=$_POST['oldProductCode'];
	$oldProductDescription=$_POST['oldProductDescription'];
		if($_POST['Category'] == 'OEM')//OEM
		{
			if(!empty($_POST['oemname']))
			{
				if($_POST['batterystatus'] == 'REPLACE')// Battery status as REPLACE
				{
					if(!empty($_POST['oldbatteryno']))
					{
						$result="SELECT * FROM serialnumbermaster where BatterySlNo ='".$post['BatterySlNo']."'";
						$sql1 = mysql_query($result) or die (mysql_error());
						$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
						if($myrow1>0)
						{
							?><script type="text/javascript">alert("Duplicate entry..!!");</script><?
						}
						else
						{
							
							$news->addNews($post,$tname);
							?><script type="text/javascript">alert("Created Sucessfully..!!",'serialnumber.php');</script><?
						}
					}
					else
					{
						?><script type="text/javascript">alert("Enter Mandatory Fields");</script><?
					}
				}
				else// Battery status as NEW
				{
					$post['oldProductCode'] ="";
					$post['oldbatteryno']= "";
					$result="SELECT * FROM serialnumbermaster where BatterySlNo ='".$post['BatterySlNo']."'";
					$sql1 = mysql_query($result) or die (mysql_error());
					$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
					if($myrow1>0)
					{
						?><script type="text/javascript">alert("Duplicate entry..!!");</script><?
					}
					else
					{
						
						$news->addNews($post,$tname);
						?><script type="text/javascript">alert("Created Sucessfully..!!",'serialnumber.php');</script><?
					}
				}
				
			}
			else
			{
				?><script type="text/javascript">alert("Enter Mandatory Fields");</script><?
			}
			
		}
		else//AFTER MArket
		{
			$post['oemname'] = '';
			if($_POST['batterystatus'] == 'REPLACE')// Battery status as REPLACE
				{
					if(!empty($_POST['oldbatteryno']))
					{
						$result="SELECT * FROM serialnumbermaster where BatterySlNo ='".$post['BatterySlNo']."'";
						$sql1 = mysql_query($result) or die (mysql_error());
						$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
						if($myrow1>0)
						{
							?><script type="text/javascript">alert("Duplicate entry..!!");</script><?
						}
						else
						{
							
							$news->addNews($post,$tname);
							?><script type="text/javascript">alert("Created Sucessfully..!!",'serialnumber.php');</script><?
						}
					}
					else
					{
						?><script type="text/javascript">alert("Enter Mandatory Fields");</script><?
					}
				}
				else// Battery status as NEW
				{
					$post['oldProductCode'] ="";
					$post['oldbatteryno']= "";
					$result="SELECT * FROM serialnumbermaster where BatterySlNo ='".$post['BatterySlNo']."'";
					$sql1 = mysql_query($result) or die (mysql_error());
					$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
					if($myrow1>0)
					{
						?><script type="text/javascript">alert("Duplicate entry..!!");</script><?
					}
					else
					{
						
						$news->addNews($post,$tname);
						?><script type="text/javascript">alert("Created Sucessfully..!!",'serialnumber.php');</script><?
					}
				}
		}
	
		
	}
	else
	{
		?><script type="text/javascript">alert("Enter Mandatory Fields");</script><?
	}
}

 if(isset($_POST['Update'])) // If the submit button was clicked
 {
	unset($_SESSION['codesval']);
	$TertiarySalesEntryDate=$_POST['TertiarySalesEntryDate'];
	$DateofSale=$_POST['DateofSale'];
	$salesinvoiceno=$_POST['salesinvoiceno'];
	$batterystatus= $_POST['batterystatus'];
	$oldProductCode=$_POST['oldProductCode'];
	$oldProductDescription=$_POST['oldProductDescription'];
	$oldbatteryno= $_POST['oldbatteryno'];
	$ProductCode=$_POST['ProductCode'];
	$ProductDescription=$_POST['ProductDescription'];
	$ManufacturingDate=$_POST['ManufacturingDate'];
	$BatterySlNo=strtoupper($_POST['BatterySlNo']);
	$CustomerName=$_POST['CustomerName'];
	$CustomerAddress=$_POST['CustomerAddress'];
	$CustomerPhoneNo=$_POST['CustomerPhoneNo'];
	$RetailerName=$_POST['RetailerName'];
	$FranchiseeName=$_POST['FranchiseeName'];
	$VehicleorInverterModel=$_POST['VehicleorInverterModel'];
	$VehicleorInverterMake=$_POST['VehicleorInverterMake'];
	$VehicleSegment=$_POST['VehicleSegment'];
	$Enginetype=$_POST['Enginetype'];
	$VehicleNo=$_POST['VehicleNo'];
	$Category= $_POST['Category'];
	$oemname= $_POST['oemname'];
	
	
	$post['Category'] =$_POST['Category'];
	$oemnameCode=mysql_query("SELECT * FROM oemmaster where oemname='".$_POST['oemname']."'");
	$oemnamefetch=mysql_fetch_array($oemnameCode);
	$post['oemname'] = $oemnamefetch['oemcode'];
	$post['salesinvoiceno'] =$_POST['salesinvoiceno'];
	$test1 = $news->dateformat($_POST['TertiarySalesEntryDate']);
	$post['TertiarySalesEntryDate']=$test1;
	$post['BatterySlNo'] =strtoupper($_POST['BatterySlNo']);
	$test = $news->dateformat($_POST['DateofSale']);
	$post['DateofSale']=$test;
	$post['ProductCode'] =$_POST['ProductCode'];
	$post['CustomerName'] =$_POST['CustomerName'];
	$post['CustomerAddress'] =$_POST['CustomerAddress'];
	$post['City'] =$_POST['City'];
	$post['CustomerPhoneNo'] =$_POST['CustomerPhoneNo'];
	$post['RetailerName']=$_POST['RetailerName'];
	$post['FranchiseeName'] =$_POST['FranchiseeName'];
	$countrysmallCode=mysql_query("SELECT * FROM vehiclemodel where modelname='".$_POST['VehicleorInverterModel']."'");
	$countrysmallfetch=mysql_fetch_array($countrysmallCode);
	$post['VehicleorInverterModel'] = $countrysmallfetch['modelcode'];
	$statesmallCode=mysql_query("SELECT * FROM vehiclemakemaster where MakeName='".$_POST['VehicleorInverterMake']."'");
	$statesmallfetch=mysql_fetch_array($statesmallCode);
	$post['VehicleorInverterMake'] = $statesmallfetch['MakeNo'];
	$branchsmallCode=mysql_query("SELECT * FROM vehiclesegmentmaster where segmentname='".$_POST['VehicleSegment']."'");
	$branchsmallfetch=mysql_fetch_array($branchsmallCode);
	$post['VehicleSegment'] = $branchsmallfetch['segmentcode'];
	$post['Enginetype'] =$_POST['Enginetype'];
	$post['VehicleNo'] =$_POST['VehicleNo'];
	$test2 = $news->dateformat($_POST['ManufacturingDate']);
	$post['ManufacturingDate'] =$test2;
	$post['batterystatus']= $_POST['batterystatus'];
	$post['oldProductCode'] =$_POST['oldProductCode'];
	$post['oldbatteryno']= strtoupper($_POST['oldbatteryno']);
	$post['user_id'] = $_SESSION['username'];
	date_default_timezone_set ("Asia/Calcutta");
	$post['m_date']= date("y/m/d : H:i:s", time());
	if(!empty($_POST['BatterySlNo'])&&!empty($_POST['DateofSale'])&&!empty($_POST['ProductCode'])&&!empty($_POST['ManufacturingDate'])&&!empty($_POST['Category'])&&!empty($_POST['batterystatus'])&&!empty($_POST['CustomerName'])&&!empty($_POST['VehicleorInverterModel'])&&!empty($_POST['VehicleorInverterMake'])&&!empty($_POST['VehicleSegment'])&&!empty($_POST['Enginetype']))
	{
		if($_POST['Category'] == 'OEM')//OEM
		{
			if(!empty($_POST['oemname']))
			{
				if($_POST['batterystatus'] == 'REPLACE')// Battery status as REPLACE
				{
					if(!empty($_POST['oldbatteryno']))
					{
						$wherecon= "BatterySlNo ='".$post['BatterySlNo']."'";
						$news->editNews($post,$tname,$wherecon);
						?><script type="text/javascript">alert("Updated Sucessfully..!!",'serialnumber.php');</script><?
					}
					else
					{
						?><script type="text/javascript">alert("Enter Mandatory Fields");</script><?
					}
				}
				else// Battery status as NEW
				{
					$wherecon= "BatterySlNo ='".$post['BatterySlNo']."'";
					$news->editNews($post,$tname,$wherecon);
					?><script type="text/javascript">alert("Updated Sucessfully..!!",'serialnumber.php');</script><?
				}
			}
			else
			{
				?><script type="text/javascript">alert("Enter Mandatory Fields");</script><?
			}
			
		}
		else//AFTER MArket
		{
			if($_POST['batterystatus'] == 'REPLACE')// Battery status as REPLACE
				{
					if(!empty($_POST['oldbatteryno']))
					{
					$wherecon= "BatterySlNo ='".$post['BatterySlNo']."'";
					$news->editNews($post,$tname,$wherecon);
					?><script type="text/javascript">alert("Updated Sucessfully..!!",'serialnumber.php');</script><?
					}
					else
					{
						?><script type="text/javascript">alert("Enter Mandatory Fields");</script><?
					}
				}
				else// Battery status as NEW
				{
					$wherecon= "BatterySlNo ='".$post['BatterySlNo']."'";
					$news->editNews($post,$tname,$wherecon);
					?><script type="text/javascript">alert("Updated Sucessfully..!!",'serialnumber.php');</script><?
				}
		}
	
		
	}
	else
	{
		?><script type="text/javascript">alert("Enter Mandatory Fields");</script><?
	}
 }
	
	// Check if delete button active, start this 
	
if(isset($_POST['Delete']))
{
	unset($_SESSION['codesval']);
	if(!isset($_POST['checkbox']))
	{
			?>
		    <script type="text/javascript">
			alert("Select data to delete!",'serialnumber.php');
			</script>
			<?
	}

else
{
		 $checkbox = $_POST['checkbox']; //from name="checkbox[]"
		 $countCheck = count($_POST['checkbox']);
		for($i=0;$i<$countCheck;$i++)
		{
		$prodidd = $checkbox[$i];
		///$prodid= $_POST['checkbox'];
		$wherecon= "BatterySlNo ='".$checkbox[$i]."'";
		$news->deleteNews($tname,$wherecon);
		}
			?><script type="text/javascript">alert("Deleted  Successfully!!",'serialnumber.php');<?
}
}
	


//EDIT
if(!empty($_GET['edi']))
{
	unset($_SESSION['codesval']);
$prmaster =$_GET['edi'];
//$cont->connect();
$result=mysql_query("SELECT * FROM serialnumbermaster where BatterySlNo ='".$prmaster."'");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);
		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!!",'serialnumber.php');
			</script>
   			<?
		}
		else
		{
			$myrow = mysql_fetch_array($result);
			
			$salesinvoiceno = $myrow['salesinvoiceno'];
			$TertiarySalesEntryDate =date("d/m/Y",strtotime($myrow['TertiarySalesEntryDate']));// $myrow['TertiarySalesEntryDate'];
			$BatterySlNo=$myrow['BatterySlNo'];
			$DateofSale = date("d/m/Y",strtotime($myrow['DateofSale']));
			$ProductCode=$myrow['ProductCode'];
			$pgg= mysql_query("select ProductDescription from productmaster where ProductCode='".$myrow['ProductCode']."' ")  ;
			$record11 = mysql_fetch_array($pgg);
			$ProductDescription = $record11['ProductDescription'];
			$CustomerName = $myrow['CustomerName'];
			$CustomerAddress = $myrow['CustomerAddress'];
			$City=$myrow['City'];
			$CustomerPhoneNo = $myrow['CustomerPhoneNo'];
			$RetailerName = $myrow['RetailerName'];
			$FranchiseeName = $myrow['FranchiseeName'];
			$countrypgg= mysql_query("select MakeName from vehiclemakemaster where MakeNo='".$myrow['VehicleorInverterMake']."' ")  ;
			$countryrecord11 = mysql_fetch_array($countrypgg);
			$VehicleorInverterMake = $countryrecord11['MakeName'];		
			$Statepgg= mysql_query("select modelname from vehiclemodel where modelcode='".$myrow['VehicleorInverterModel']."' ")  ;
			$Staterecord11 = mysql_fetch_array($Statepgg);
			$VehicleorInverterModel = $Staterecord11['modelname'];  	
			$branchpgg= mysql_query("select segmentname from vehiclesegmentmaster where segmentcode='".$myrow['VehicleSegment']."' ")  ;
			$branchrecord11 = mysql_fetch_array($branchpgg);
			$VehicleSegment = $branchrecord11['segmentname'];
			$Enginetype = $myrow['Enginetype'];
			$VehicleNo=$myrow['VehicleNo'];
			$ManufacturingDate = date("d/m/Y",strtotime($myrow['ManufacturingDate']));
			$batterystatus=$myrow['batterystatus'];
			$oldbatteryno= $myrow['oldbatteryno'];
			$oldProductCode=$myrow['oldProductCode'];
			$opgg= mysql_query("select ProductDescription from productmaster where ProductCode='".$myrow['oldProductCode']."' ")  ;
			$orecord11 = mysql_fetch_array($opgg);
			$oldProductDescription = $orecord11['ProductDescription'];
			$Category = $myrow['Category'];
			$oemnameCode=mysql_query("SELECT oemname FROM oemmaster where oemcode='".$myrow['oemname']."'");
			$oemnamefetch=mysql_fetch_array($oemnameCode);
			$oemname = $oemnamefetch['oemname'];
		}
		$prmaster = NULL;
}



if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	header('Location:serialnumber.php');
}
?> 
 
 
<script type="text/javascript">
$(function() {
  $("#DateofSale").datepicker({ changeYear:true, maxDate: '0', yearRange: '2006:3050',dateFormat:'dd/mm/yy'});
  });
	 var url12 = "inc/serialnologic.php?param=";
        var http;
function GetHttpObject()
{
if (window.ActiveXObject)
return new ActiveXObject("Microsoft.XMLHTTP");
else if (window.XMLHttpRequest)
return new XMLHttpRequest();
else
{
alert("Your browser does not support AJAX.");
return null;
}
}
        function serialnologicfun() 
        { 
			var soldValue = document.getElementById("oldbatteryno").value; 
			var serialValue = document.getElementById("BatterySlNo").value; 
			if(soldValue == serialValue)
			{
				alert("Old & New Battery Serial No. should not be same");
				document.getElementById("ManufacturingDate").value="";
				document.getElementById("BatterySlNo").value="";
				BatterySlNo.focus()
			}
            http=GetHttpObject();
              
if (http !=null)
{       //var idValue = document.getElementById("ProductCode").options.;
          
        var proValue = document.getElementById("ProductCode").value;
		var serialValue = document.getElementById("BatterySlNo").value; 
        var idValue = proValue+'~'+serialValue;
           var myRandom = parseInt(Math.random()*99999999); 
        
        // cache buster

        http.open("GET", url12 + escape(idValue)+  "&rand=" + myRandom, true); 
		
        http.onreadystatechange = handleHttpResponse6; 
        http.send(null);
        
}
        }
 function handleHttpResponse6()
  { 
  if (http.readyState == 4)
   { 
   results = http.responseText;
    var testing=results;
     
      var output=testing.replace("Resource id #5","");
	 // var display=output.format("mm/dd/yy");
	  var object =  document.getElementById('BatterySlNo');
	  
     //document.all("Productdescription").options.selectedIndex = results; 
	//  var object = document.getElementById('th');
    if(output=='')
	{
		alert("Enter the valid Battery Serial No");
		toutfun(object);
		document.getElementById("ManufacturingDate").value="";
		document.getElementById("BatterySlNo").value="";
		BatterySlNo.focus()
		
		
	}
	else
	{
	//	var dateformat = new Date(output);
//	var curr_date = dateformat.getDate();
//    var curr_month = dateformat.getMonth() + 1; //Months are zero based
//    var curr_year = dateformat.getFullYear();
//    var valuedate =curr_date + "/" + curr_month + "/" + curr_year;
		 document.getElementById("ManufacturingDate").value=output;
		 CustomerName.focus()
	}
 
    } 
    } 
	

var filter = /^[0-9-+]+$/
function validatePhoneno(th) {
    var object = document.getElementById('th');
    var returnvalph=filter.test(th.value);
    if (returnvalph==false) {
		alert("Please enter a valid Contact number")
		toutfun(object);
		th.value=''; 
		th.focus()
//ph.select()
// $(':text').val(''); 
        
    }
    return returnvalph;
    }
	
	function validatecontact(key)
{
//getting key code of pressed key
var phn = document.getElementById('th');
if (phn.value.length <20 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 20 Numbers");
	toutfun(phn);
return false;
}
}

function validateBatterySlNo(key)
{
	var object = document.getElementById('BatterySlNo');
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

function validatesalesinvoiceno(key)
{
	var object = document.getElementById('salesinvoiceno');
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

function validateCustomerName(key)
{
	var object = document.getElementById('CustomerName');
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

function validateCustomerAddress(key)
{
	var object = document.getElementById('CustomerAddress');
	if (object.value.length <250 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 250 characters");
	toutfun(object);
return false;
}
}

function validateCity(key)
{
	var object = document.getElementById('City');
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

function validateVehicleNo(key)
{
	var object = document.getElementById('VehicleNo');
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

function validateoldbatteryno(key)
{
	var object = document.getElementById('oldbatteryno');
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


function SetVis()
{
	try
   {
		var a = document.getElementById('batterystatus').value
		if(a =="REPLACE")
		{
		var div1 = document.getElementById('LABLEID');
		div1.style.visibility = 'visible';
		}
		else if(a == "NEW")
		{
			
		var div1 = document.getElementById('LABLEID'); 
		div1.style.visibility = 'hidden';
		$('select option[value=""]').attr("selected",true);
		oldproval();
		}
		
	}
	catch(Exception)
	{}
}

function oldproval()
{
	var ttlary= new Array();
		var RetailerCodelist = document.getElementById('productdeslist');
		var RetailerName = document.getElementById('ProductCode');
		var oldProductCode = document.getElementById('oldProductCode');
		cnt=0;
		RetailerName.options.length = 0;
		document.getElementById('ProductDescription').value='';
		document.getElementById('ManufacturingDate').value='';
		document.getElementById('BatterySlNo').value='';
		document.getElementById('oldProductDescription').value='';
		document.getElementById('oldbatteryno').value='';
		
		RetailerName.options.add(new Option('--- Select ---',''));
		//RetailerName.options.add(new Option(franchisecode,franchisecode));
		for (i = 0; i < RetailerCodelist.options.length; i++) 
		{
			optvalue = RetailerCodelist.options[i].value;
			
			var lstval=optvalue.split("~");
			if(lstval[0]!="")
			{
				//RetailerName.options.add(new Option('','--- Selet ---'));
				RetailerName.options.add(new Option(lstval[0],lstval[0]));
			}
		}SortOptions("ProductCode");
}

function SetVis1()
{
	try
   {
		var a = document.getElementById('Category').value
		if(a =="OEM")
		{
		var div1 = document.getElementById('oemnameid'); 
		/*var div2 = document.getElementById('div_txt2'); 
		div2.style.visibility = 'visible';*/
		div1.style.visibility = 'visible';
		}
		else 
		{
		var div1 = document.getElementById('oemnameid'); 
		/*var div2 = document.getElementById('div_txt2'); 
		div1.style.visibility = 'visible';*/
		div1.style.visibility = 'hidden';
		}

	}
	catch(Exception)
	{alert("Error");}
}


function getFuncs()
{
	SetVis();
	SetVis1();  
	document.frm1.salesinvoiceno.focus();
}
function getFuncs1()
{
	SetVis(); 
	SetVis1(); 
	document.frm1.Category.focus();
}

function generalfun()
{
	SetVis();
	SetVis1(); 
}
function selectvalue()
{
	var e = document.getElementById("ProductCode"); 
		var er=e.options[e.selectedIndex].value;
		var ddlArray= new Array();
		var ddl = document.getElementById('productdeslist');
		var tt;
		for (i = 0; i < ddl.options.length; i++) 
		{
			ddlArray[i] = ddl .options[i].value;
			var ty = ddlArray[i].split("~");
			var p =ty[0];
			var p2 =ty[1];
			
			if(p==er)
			{
				tt=p2;
			}
			else if(er=="")
			{
				tt="";
			}
			
			document.getElementById("ProductDescription").value=tt;
			document.getElementById('ManufacturingDate').value='';
			document.getElementById('BatterySlNo').value='';
		}
		pgvmodel();
}


function pgvmodel()
{
	var e = document.getElementById("ProductCode"); 
		var er=e.options[e.selectedIndex].value;
		var ddlArray= new Array();
		var ddl = document.getElementById('pgvs1');
		var tt;
		for (i = 0; i < ddl.options.length; i++) 
		{
			ddlArray[i] = ddl .options[i].value;
			var ty = ddlArray[i].split("~");
			var p =ty[0];
			var p2 =ty[1];
			
			if(p==er)
			{
				tt=p2;
			}
			else if(er=="")
			{
				tt="";
			}
			
			//document.getElementById("ProductDescription").value=tt;
		}
		pgvmodel1(tt);
}

function pgvmodel1(tt)
{
	//var e = document.getElementById("ProductCode"); 
	//strUser = e.selectedIndex;
	var franchisecode = tt;//e.options[strUser].value;
	var ttlary= new Array();
		var RetailerCodelist = document.getElementById('pgvs2');
		var RetailerName = document.getElementById('VehicleorInverterModel');
		cnt=0;
		RetailerName.options.length = 0;
		RetailerName.options.add(new Option("--- Select ---",""));
		for (i = 0; i < RetailerCodelist.options.length; i++) 
		{
			optvalue = RetailerCodelist.options[i].value;
			var lstval=optvalue.split("~");
			if(lstval[0]==franchisecode)
			{
				RetailerName.options.add(new Option(lstval[1],lstval[1]));
			}
		}
		document.getElementById("VehicleorInverterMake").value='';
		document.getElementById("VehicleSegment").value='';
}


function selectvalue122()
{
	var e = document.getElementById("oldProductCode"); 
	strUser = e.selectedIndex;
	var franchisecode = e.options[strUser].value;
	var ttlary= new Array();
		var RetailerCodelist = document.getElementById('prmaplist');
		var RetailerName = document.getElementById('ProductCode');
		cnt=0;
		RetailerName.options.length = 0;
		document.getElementById('ProductDescription').value='';
		document.getElementById('ManufacturingDate').value='';
		document.getElementById('BatterySlNo').value='';
		document.getElementById('oldProductDescription').value='';
		document.getElementById('oldbatteryno').value='';
		RetailerName.options.add(new Option('--- Select ---',''));
		RetailerName.options.add(new Option(franchisecode,franchisecode));
		for (i = 0; i < RetailerCodelist.options.length; i++) 
		{
			optvalue = RetailerCodelist.options[i].value;
			
			var lstval=optvalue.split("~");
			if(lstval[0]==franchisecode)
			{
				//RetailerName.options.add(new Option('','--- Selet ---'));
				RetailerName.options.add(new Option(lstval[1],lstval[1]));
			}
		}
		SortOptions("ProductCode");
}
function SortOptions(id) {
    var prePrepend = "#";
    if (id.match("^#") == "#") prePrepend = "";
    $(prePrepend + id).html($(prePrepend + id + " option").sort(
        function (a, b) { return a.text == b.text ? 0 : a.text < b.text ? -1 : 1 })
    );
}
function selectvalold()
{
	var e = document.getElementById("oldProductCode"); 
		var er=e.options[e.selectedIndex].value;
		var ddlArray= new Array();
		var ddl = document.getElementById('productdeslist');
		var tt;
		for (i = 0; i < ddl.options.length; i++) 
		{
			ddlArray[i] = ddl .options[i].value;
			var ty = ddlArray[i].split("~");
			var p =ty[0];
			var p2 =ty[1];
			
			if(p==er)
			{
				tt=p2;
			}
			else if(er=="")
			{
				tt="";
			}
			
			document.getElementById("oldProductDescription").value=tt;
		}
}


function selvalue()
{
	var e = document.getElementById("VehicleorInverterModel"); 
	var er=e.options[e.selectedIndex].value;
	var ddlArray= new Array();
	var ddl = document.getElementById('VehicleCodelist');
	var tt,tt2;
	for (i = 0; i < ddl.options.length; i++) 
	{
		ddlArray[i] = ddl .options[i].value;
		var ty = ddlArray[i].split("~");
		var p  = ty[0];
		var p2 = ty[1];
		var p3 = ty[2];
		
		if(p==er)
		{
			tt  =p2;
			tt2 =p3
		}
		else if(er=="")
		{
			tt  ="";
			tt2 ="";
		}
		
		document.getElementById("VehicleorInverterMake").value=tt;
		document.getElementById("VehicleSegment").value=tt2;
	}
}
function selectthevalue()
{
	var e = document.getElementById("FranchiseeName"); 
	strUser = e.selectedIndex;
	var franchisecode = e.options[strUser].value;
	var ttlary= new Array();
		var RetailerCodelist = document.getElementById('RetailerCodelist');
		var RetailerName = document.getElementById('RetailerName');
		cnt=0;
		RetailerName.options.length = 0;
		RetailerName.options.add(new Option("--- Select ---",""));
		for (i = 0; i < RetailerCodelist.options.length; i++) 
		{
			optvalue = RetailerCodelist.options[i].value;
			
			var lstval=optvalue.split("~");
			if(lstval[0]==franchisecode)
			{
				RetailerName.options.add(new Option(lstval[1],lstval[1]));
			}
		}
}



 var url12 = "inc/serialnologic.php?param=";
        var http;
function GetHttpObject()
{
if (window.ActiveXObject)
return new ActiveXObject("Microsoft.XMLHTTP");
else if (window.XMLHttpRequest)
return new XMLHttpRequest();
else
{
alert("Your browser does not support AJAX.");
return null;
}
}
        function serialnologic2fun() 
        { 
		
		var soldValue = document.getElementById("oldbatteryno").value; 
			var serialValue = document.getElementById("BatterySlNo").value; 
			if(soldValue == serialValue)
			{
				document.getElementById("ManufacturingDate").value="";
				document.getElementById("BatterySlNo").value="";
				document.getElementById("oldbatteryno").value="";
				oldbatteryno.focus();
				alert("Old & New Battery Serial No. should not be same");
			}
            http=GetHttpObject();
              
if (http !=null)
{       //var idValue = document.getElementById("ProductCode").options.;
          
        var proValue = document.getElementById("oldProductCode").value;
		var serialValue = document.getElementById("oldbatteryno").value; 
        var idValue = proValue+'~'+serialValue;
        var myRandom = parseInt(Math.random()*99999999); 
        
        // cache buster

        http.open("GET", url12 + escape(idValue)+  "&rand=" + myRandom, true); 
		http.onreadystatechange = handleHttpResponse16; 
        http.send(null);
        
}
        }
 function handleHttpResponse16()
  { 
  if (http.readyState == 4)
   { 
   results = http.responseText;
    var testing=results;
     
      var output=testing.replace("Resource id #5","");
	 // var display=output.format("mm/dd/yy");
	  var object =  document.getElementById('oldbatteryno');
	  
     //document.all("Productdescription").options.selectedIndex = results; 
	//  var object = document.getElementById('th');
   	if(output=='')
	{
	document.getElementById("oldbatteryno").value="";
	alert("Enter the valid Battery Serial No");
	toutfun(object);
	
	//oldbatteryno.focus()
	}
	
	
    }
    } 
</script>
    <title><?php echo $_SESSION['title']; ?> || Tertiary Sales</title>
</head>

<?php 
 if(empty($_SESSION['codesval']))
{
if(!empty($_GET['edi'])) {?>
 
 <body class="default" onLoad="return getFuncs()">

<? }else{?>


<body class="default" onLoad="return getFuncs1()">

 <? }  
}else{?>
<body class="default" onLoad="document.frm1.codes.focus()">

 <? } ?>
 <center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
			<form method="POST" action="<?php $_PHP_SELF ?>" id="frm1" name="frm1">
            <table id="default" style=" height:10px; display:none;" >

                                         <tr >
                                      <td>
                                    <select  name="productdeslist" id="productdeslist">
                                  <?
                                                                                
        $que = mysql_query("SELECT ProductCode,ProductDescription FROM productmaster order by id desc");
    
		while( $record = mysql_fetch_array($que))
		{
			echo "<option value=\"".$record['ProductCode']."~".$record['ProductDescription']."\">".$record['ProductCode']."~".$record['ProductDescription']."\n "; 
		}
    
    ?>
                                          </select>
                                      </td>
                                      <?php /*?> <td>
                                    <select  name="pgvehiclemodel" id="pgvehiclemodel">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT ProductCode,modelname FROM  `view_pgvehiclemodel`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									        
                                      echo "<option value=\"".$record['ProductCode']."~".$record['modelname']."\">".$record['ProductCode']."~".$record['modelname']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td><?php */?>
									<td>
                                    <select  name="pgvs1" id="pgvs1">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT ProductCode,pgroupcode FROM  `view_rptproductfin`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									        
                                      echo "<option value=\"".$record['ProductCode']."~".$record['pgroupcode']."\">".$record['ProductCode']."~".$record['pgroupcode']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td>
									<td>
                                    <select  name="pgvs2" id="pgvs2">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT ProductGroup,modelname FROM  `vehiclemodel`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									        
                                      echo "<option value=\"".$record['ProductGroup']."~".$record['modelname']."\">".$record['ProductGroup']."~".$record['modelname']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td>
                                      
                                      
                                       <td>
                                    <select  name="RetailerCodelist" id="RetailerCodelist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT Franchisecode,RetailerCode FROM  `view_francheese_retailer` ORDER BY id DESC");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									        
                                      echo "<option value=\"".$record['Franchisecode']."~".$record['RetailerCode']."\">".$record['Franchisecode']."~".$record['RetailerCode']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td>
                                      
                                      <td>
                                    <select  name="VehicleCodelist" id="VehicleCodelist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT modelname,makename,segmentname FROM `view_vehicledetails` ORDER BY id DESC");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									        
                                      echo "<option value=\"".$record['modelname']."~".$record['makename']."~".$record['segmentname']."\">".$record['modelname']."~".$record['makename']."~".$record['segmentname']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td>
                                      </tr>
                                      <tr>
                                       <td>
                                    <select  name="prmaplist" id="prmaplist">
                                  <?
                                                                                
        $que = mysql_query("SELECT ProductCode,MapProductCode,ProductDescription FROM view_pmap order by id desc");
    
		while( $record = mysql_fetch_array($que))
		{
			echo "<option value=\"".$record['ProductCode']."~".$record['MapProductCode']."~".$record['ProductDescription']."\">".$record['ProductCode']."~".$record['MapProductCode']."~".$record['ProductDescription']."\n "; 
		}
    
    ?>
                                          </select>
                                      </td>
                                      
                                      
                                      
                                      </tr>
</table>
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">

                    	
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Tertiary Sales</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:930px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:300px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                              <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;" >
								<label>Category  </label><label style="color:#F00;">*</label>
							 </div>
							  <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px; " >
								 <?php if(!empty($_GET['edi']))
						  {?>
						  <input type="text" name="Category" id="Category" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?=$Category?>" />
							  <? } 
						  else { ?>
							
								  <select name="Category" id="Category"  onchange="SetVis1()">
								 
                                  <option value="AFTER MARKET">AFTER MARKET</option>     
								  <option value="OEM">OEM</option>                                
								                                
																   
								 </select>
										
										<?php
						  }
						  ?>
							
							 </div>
 							<!--Row1 end-->
							 <!--Row2 -->  
                            
                               <div style="width:150px; height:30px; float:left; margin-top:5px;">
                                  <label>Tertiary Sales Entry Date</label><label id="frame1"></label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <?
                              if(!empty($_GET['edi']))
                              {
                              ?>
                              <input type="text" name="TertiarySalesEntryDate" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?php echo $TertiarySalesEntryDate;?>"  />
                             
                              <?
							  
                              }
							  else
							  { ?>
                                <input type="text" name="TertiarySalesEntryDate" id="TertiarySalesEntryDate" onFocus="salesinvoiceno.focus();" style="border-style:hidden; background:#f5f3f1;"  readonly="readonly" value="<?php $TertiarySalesEntryDate = date("d/m/Y"); PRINT "$TertiarySalesEntryDate";?>"/>
                                  <? }?>
                            </div>
 							<!--Row2 end-->
                             <!--Row3-->  
                             
 							<!--Row3 end-->   
                             <!--Row4-->  
                           
                               
                                <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Sales Invoice No</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                            
                               <input type="text" name="salesinvoiceno" id="salesinvoiceno" value="<? echo $salesinvoiceno ?>" onKeyPress="return validatesalesinvoiceno(event)" maxlength="50" onChange="return trim(this)"/>
                                      </div>
                                      <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Date of Sale</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="DateofSale" value="<? echo $DateofSale ?>" readonly="readonly" <?php if(!empty($_GET['edi'])){?>style="border-style:hidden; background:#f5f3f1;" <? } else {?>id="DateofSale" <? ;}?>/>
                               <?php /*?> <input type="text" name="DateofSale"  id="DateofSale"  value="<? echo $DateofSale ?>" readonly="readonly"/><?php */?>
                               </div>
                                  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Battery Status</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                               <?php if(!empty($_GET['edi']))
						  {?>
						  <input type="text" name="batterystatus" id="batterystatus" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?=$batterystatus?>"  />
							  <? } 
						  else { ?>
							
								  <select name="batterystatus" id="batterystatus"  onchange="SetVis()">
								  <option value="NEW">NEW</option>
                                       	<option value="REPLACE">REPLACE</option></select>                              
						</select>
										
					<?php } ?>
                                    
                               </div>
                               <div id="LABLEID">
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Old Product Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <?
                              if(!empty($_GET['edi']))
                              {
                              ?>
                                                                
                               <input type="text" name="oldProductCode" id="oldProductCode" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<? echo $oldProductCode ?>">
                               <?
							  
                              }
							  else
							  { ?>
                               <select name="oldProductCode" id="oldProductCode" onChange="selectvalue122(),selectvalold();" onBlur="oldbatteryno.focus()" >
                                       <option value="<?php echo $oldProductCode;?>"><? if(!empty($oldProductCode)){ echo $oldProductCode;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT ProductCode FROM productmaster order by ProductCode asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($ProductCode!=$record['ProductCode'])
									  {	      
                                       echo "<option value=\"".$record['ProductCode']."\">".$record['ProductCode']."\n ";
									  }
                                     }
                                    ?>
                                          </select>
                               <? }?>
                               
                               </div>
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Old Product Description</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <input type="text" name="oldProductDescription" id="oldProductDescription" style="border-style:hidden; background:#f5f3f1;"  readonly="readonly" value="<? echo $oldProductDescription ?>" />                                
                              
                              
                               </div>
                                <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Old Battery No</label> <label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                            <?   if(!empty($_GET['edi']))
                              {
                              ?>
                                <input type="text" name="oldbatteryno" id="oldbatteryno" value="<? echo $oldbatteryno ?>" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" onKeyPress="return validateoldbatteryno(event)" maxlength="20" onChange="serialnologic2fun()"/>
                                                           
                              <?
							  
                              }
							  else
							  { ?>
                              <input type="text" name="oldbatteryno" id="oldbatteryno" value="<? echo $oldbatteryno ?>" onKeyPress="return validateoldbatteryno(event)" maxlength="20" style="text-transform:uppercase" onChange="serialnologic2fun()"/>
                               <? }?>
                                   
                               </div>
                                 <!---->
                                 
                                     
                                      
 							<!--Row6 end-->       
                            
                             <!--Row7 -->  
                               </div>
                                 <!----->     
 							<!--Row4end-->   
                           
                             <!--Row5 -->  
                          
 							<!--Row5 end-->    
                            
                             <!--Row6 -->  
                       
                                  </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                                 
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Product Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <?
                              if(!empty($_GET['edi']))
                              {
                              ?>
                                                                
                               <input type="text" name="ProductCode" id="ProductCode" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<? echo $ProductCode ?>">
                               <?
							  
                              }
							  else
							  { ?>
                               <select name="ProductCode" id="ProductCode" onChange="selectvalue();" onBlur="BatterySlNo.focus()" >
                                       <option value="<?php echo $ProductCode;?>"><? if(!empty($ProductCode)){ echo $ProductCode;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT ProductCode FROM productmaster order by ProductCode asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($ProductCode!=$record['ProductCode'])
									  {	      
                                       echo "<option value=\"".$record['ProductCode']."\">".$record['ProductCode']."\n ";
									  }
                                     }
                                    ?>
                                          </select>
                               <? }?>
                               
                               </div>
 							<!--Row6 end-->       
                            
                             <!--Row7 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Product Description</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <input type="text" name="ProductDescription" id="ProductDescription" style="border-style:hidden; background:#f5f3f1;"  readonly="readonly" value="<? echo $ProductDescription ?>" />                                
                              
                              
                               </div>
 							<!--Row7 end-->      
                              
                                  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Battery Serial No</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <?php /*?> <input type="text" name="BatterySlNo" id="BatterySlNo" <? if(!empty($_GET['edi'])) { ?> readonly="readonly" <? }?>  value="<? echo $BatterySlNo ?>" onChange="return codetrim(this)" onKeyPress="return validateBatterySlNo(event)" maxlength="50"/>
                             <?php */?>  <?
                              if(!empty($_GET['edi']))
                              {
                              ?>
                              <input type="text" name="BatterySlNo" id="BatterySlNo" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<? echo $BatterySlNo ?>" onKeyPress="return validatesalesinvoiceno(event)" maxlength="20" onBlur="return trim(this)"/>
                             
                              <?
							  
                              }
							  else
							  { ?>
                               <input type="text" name="BatterySlNo" id="BatterySlNo" value="<? echo $BatterySlNo ?>" onKeyPress="return validatesalesinvoiceno(event)" maxlength="20" onBlur="return trim(this)" style="text-transform:uppercase" onChange="serialnologicfun()"/>
                               <? }?>
                               </div>
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Manufacturing Date</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="ManufacturingDate" id="ManufacturingDate" style="border-style:hidden; background:#f5f3f1;"  readonly="readonly"  value="<? echo $ManufacturingDate ?>"/>
                               </div>
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Customer Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="CustomerName" id="CustomerName" value="<? echo $CustomerName; ?>" onChange="return trim(this)" onKeyPress="return validateCustomerName(event)" maxlength="50"/>
                               </div>
 							<!--Row1 end-->                               
                       <!--Row2 -->  
                          
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Customer Address</label>
                               </div>
                              <div style="width:145px; height:65px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <textarea rows="4" cols="14" name="CustomerAddress" id="CustomerAddress" onChange="return trim(this)" onKeyPress="return validateCustomerAddress(event)" maxlength="250"><? echo $CustomerAddress;?></textarea>
                               </div>
 							<!--Row2 end-->
                             <!--Row3 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>City</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="City" id="City" value="<? echo $City ?>" onChange="return trim(this)" onKeyPress="return validateCity(this)" maxlength="50"/>
                               </div>
 							<!--Row3 end-->   
                            
 							<!--Row6 end-->                                							
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
		 <!--Row4 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Customer Phone No</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="CustomerPhoneNo"  onKeyUp="return validatecontact(event)" id="th" value="<? echo $CustomerPhoneNo ?>" onKeyPress="return trim(this)" onChange="return validatePhoneno(this)" maxlength="20"/>
                               </div>
 							<!--Row4 end-->   
                            <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Franchisee Code</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  
                                   <select name="FranchiseeName" class="FranchiseeName" id="FranchiseeName" onChange="selectthevalue();">
                                    <option value="<?php echo $FranchiseeName;?>"><? if(!empty($FranchiseeName)){ echo $FranchiseeName;}else{?> ----Select---- <? } ?></option>
                                   
                                     <?
                                                                                
                                        $que = mysql_query("SELECT Franchisecode FROM franchisemaster order by Franchisecode asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($FranchiseeName!=$record['Franchisecode'])
									  {     
									 	echo '<option value="'.$record['Franchisecode'].'">'.$record['Franchisecode'].'</option>';;
									  }
									 }
                                    ?>
                                          </select>
                               
                               
                               
                               </div>
                             <!--Row5 -->  
                           <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Retailer Code</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                            <select name="RetailerName" class="RetailerName" id="RetailerName">
                                        <option value="<? echo $RetailerName ?>"><? echo $RetailerName?></option>
            
                                          </select>
                               
                               
                               
                               
                               </div>
 							<!--Row5end-->   
                             <!--Row6 -->  
                                <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Vehicle/Inverter Model</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <!--<input type="text" name="VehicleorInverterModel" value="<? echo $VehicleorInverterModel ?>"/>-->
                                <select  onChange="selvalue();" name="VehicleorInverterModel" id="VehicleorInverterModel" onBlur="Enginetype.focus()" >
                                       <option value="<?php echo $VehicleorInverterModel;?>"><? if(!empty($VehicleorInverterModel)){ echo $VehicleorInverterModel;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT modelname FROM vehiclemodel order by modelname asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($VehicleorInverterModel!=$record['modelname'])
									  {	      
                                       echo "<option value=\"".$record['modelname']."\">".$record['modelname']."\n ";                      
									  }
                                     }
                                    ?>
                                          </select>   
                               </div>
                     <!--Row2 -->  
                           <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Vehicle/Inverter Make</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="VehicleorInverterMake" id="VehicleorInverterMake" style="border-style:hidden; background:#f5f3f1;"  readonly="readonly" value="<? echo $VehicleorInverterMake ?>"/>
                         
                         <?php /*?><select name="VehicleorInverterMake" id="VehicleorInverterMake" >
                                       <option value="<?php echo $VehicleorInverterMake;?>"><? if(!empty($VehicleorInverterMake)){ echo $VehicleorInverterMake;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT MakeName FROM vehiclemakemaster order by MakeName asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($VehicleorInverterMake!=$record['MakeName'])
									  {	      
                                       echo "<option value=\"".$record['MakeName']."\">".$record['MakeName']."\n "; 
									  }
                                     }
                                    ?>
                                          </select> <?php */?>
                         
                          </div>
 							<!--Row2 end-->
                             <!--Row3 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Vehicle Segment</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <input type="text" name="VehicleSegment" id="VehicleSegment" style="border-style:hidden; background:#f5f3f1;"  readonly="readonly" value="<? echo $VehicleSegment ?>"/>
                                   
                                   <?php /*?>  <select name="VehicleSegment" id="VehicleSegment">
                                       <option value="<?php echo $VehicleSegment;?>"><? if(!empty($VehicleSegment)){ echo $VehicleSegment;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT segmentname FROM vehiclesegmentmaster order by segmentname asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($VehicleSegment!=$record['segmentname'])
									  {	      
                                       echo "<option value=\"".$record['segmentname']."\">".$record['segmentname']."\n ";                      
									  }
                                     }
                                    ?>
                                          </select><?php */?>
                              
                              
                              
                              
                               </div>
 							<!--Row3 end-->   
                             <!--Row4 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Engine type</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <select name="Enginetype" id="Enginetype" ><?php echo $Enginetype ?>
                                     <option value="<?php echo $Enginetype; ?>"><? if(!empty($Enginetype)){ echo $Enginetype;}else{?> ----Select---- <? } ?></option>
                                        <option value="Petrol">Petrol</option>
                                    	<option value="Diesel">Diesel</option>
                                    	<option value="CNG">CNG</option>
                                    	<option value="LPG">LPG</option>
                                        <option value="Invertor">Invertor</option>
                                        <option value="Others">Others</option>
                                   </select>
                               </div>
 							<!--Row4 end-->   
                             <!--Row5 -->  
                           <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Vehicle No</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="VehicleNo" id="VehicleNo" value="<? echo $VehicleNo ?>" onChange="return trim(this)" onKeyPress="return validateVehicleNo(event)" maxlength="50"/>
                               </div>
 							<!--Row5end-->   
                             <!--Row6 -->  
                             
 							<!--Row6 end-->   
                            <div id="oemnameid">
                                  <div style="width:145px; VISIBILITY:true; height:30px; float:left;  margin-top:5px; margin-left:3px;">
								<label>OEM Name </label><label id="lbl" style="color:#F00;">*</label>
							 </div>
							  <div style="width:145px; VISIBILITY:true; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              	 <?php if(!empty($_GET['Category'])) {?>
						  	 <input type="text" name="oemname" id="oemname" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?=$oemname?>" />
							 <? }  else { ?>
							<select name="oemname" id="oemname" >
                             <option value="<? echo $oemname;?>"><? if(!empty($oemname)){ echo $oemname;}else{?> ----Select---- <? } ?></option>
                                     <? $que = mysql_query("SELECT oemname FROM oemmaster order by oemname asc");
                                      while( $record = mysql_fetch_array($que)){
									 if($oemname!=$record['oemname']){     
                                     echo "<option value=\"".$record['oemname']."\">".$record['oemname']."\n "; 
									}}?>
                              </select>
							<?php }?>
								 
                             </div> </div>
                           </div>
                                
                     <!-- col3 --> 
                        <script>// all scripts used to eliminate duplication in dropdown.
                                    
                                    // Set the present object
                                    var present = {};
                                    $('#Enginetype option').each(function(){
                                    // Get the text of the current option
                                    var text = $(this).text();
                                    // Test if the text is already present in the object
                                    if(present[text]){
                                    // If it is then remove it
                                    $(this).remove();
                                    }else{
                                    // Otherwise, place it in the object
                                    present[text] = true;
                                    }
                                    });
									
									// Set the present object
                                    var present = {};
                                    $('#batterystatus option').each(function(){
                                    // Get the text of the current option
                                    var text = $(this).text();
                                    // Test if the text is already present in the object
                                    if(present[text]){
                                    // If it is then remove it
                                    $(this).remove();
                                    }else{
                                    // Otherwise, place it in the object
                                    present[text] = true;
                                    }
                                    });
									</script>    
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                     <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:8px;">
                             
					<div style="width:235px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
                          <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                    <?php      if(!empty($_GET['edi']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" >
				          <? } ?>
				           </div>
                          
                           <div style="width:100px; height:32px; float:left;margin-top:16px; margin-left:10px;">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div>         
                                                   
				     </div>	
                         
                         <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1  
							  <br>
                              <!--  <div style="width:100px; height:32px; float:left;" class="cont">
						     <label>salesinvoiceno</label>
				           </div>	
                           
                           <div style="width:115px; height:32px; float:left;" class="cont">
						    <input type="text" name="codes" value="<?/* echo $Search1 */?>"/>
				           </div> -->  
                           <br />
                            <div style="width:133px; height:32px; float:left; margin-left:20px;" class="cont">
						     <label>Battery Serial No</label>
				           </div>
						   
						   <div style="width:185px; height:32px; margin-left:1px; float:left;" class="cont">
						    <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" style="text-transform:uppercase;"  value="<? echo $_SESSION['codesval'] ?>"/>
				           </div> 
						   
                           <div style="width:80px; height:32px; margin-left:20px; float:left;">
						  <input name="Search" type="submit" id="Search" class="button" value="Search">
				           </div>
                          </div> 
                </div>
                <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                   
                <table align="center" id="datatable1" class="sortable" bgcolor="#FF0000" border="1" width="900px" cellpadding="20%">
     			<tr style="white-space:nowrap;">
                 <?  if(($row['deleterights'])=='Yes')
	 {
	?> 
                <td class="sorttable_nosort" style="font-weight:bold; text-align:center">
                <input type='checkbox' id="checkall" name='checkall' onclick='checkedAll(frm1);'>
                <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
                </td>
                <td class="sorttable_nosort" style="font-weight:bold; text-align:center">Action</td>
                <? 
	  } 
	  ?>
      		   <td style="font-weight:bold; width:auto; text-align:center;">Category</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Tertiary Sales Entry Date</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Battery Sl No</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Date of Sale</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Sales Invoice No.</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Battery Status</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Old Product Code</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Old Product Description</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Old Battery Sl No.</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Manufacturing Date</td>
             <!--  <td style="font-weight:bold; width:auto; text-align:center;">Salestype</td>-->
               <td style="font-weight:bold; width:auto; text-align:center;">Product Code</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Product Description</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Customer Name</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Customer Address</td>
               <td style="font-weight:bold; width:auto; text-align:center;">City</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Customer Phone No.</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Retailer Code</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Franchisee Code</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Vehicle or Inverter Model</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Vehicle or Inverter Make</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Vehicle Segment</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Engine type</td>
               <td style="font-weight:bold; width:auto; text-align:center;">Vehicle No.</td>
               <td style="font-weight:bold; width:auto; text-align:center;">OEM Name</td>
               </tr>
               <?php
		
              // This while will loop through all of the records as long as there is another record left. 
               while( $record = mysql_fetch_array($query))
			  { // Basically as long as $record isn't false, we'll keep looping.
				// You'll see below here the short hand for echoing php strings.
				// <?=$record[key] - will display the value for that array.
			   ?>
    
			  <tr style="white-space:nowrap;">
               <?  if(($row['deleterights'])=='Yes')
	 { 
	 ?>
              
			   <td bgcolor="#FFFFFF" style="font-weight:bold; text-align:center">
               <input name="checkbox[]" type="checkbox" id="checkbox[]" onChange="test();" value="<? echo $record['BatterySlNo']; ?>"></td>
           <? } 
	 if(($row['editrights'])=='Yes') 
	 { 
	 ?>
			  <td bgcolor="#FFFFFF" style="font-weight:bold; text-align:center"> <a style=" color:#0360B2; font-weight:bold " name="edit" href="serialnumber.php?<?php if(($row['editrights'])=='Yes') { echo 'edi='; echo $record['BatterySlNo'];} else echo 'permiss'; ?>">Edit</a></td>
               <? 
	  } 
	  ?>
             
		      <td  bgcolor="#FFFFFF"><?=$record['Category']?></td>
              <td  bgcolor="#FFFFFF"><?=date("d/m/Y",strtotime($record['TertiarySalesEntryDate']))?></td>
	          <td  bgcolor="#FFFFFF"><?=$record['BatterySlNo']?></td>
	          <td  bgcolor="#FFFFFF"><?=date("d/m/Y",strtotime($record['DateofSale']))?></td>
              <td  bgcolor="#FFFFFF"><?=$record['salesinvoiceno']?></td>
              <td  bgcolor="#FFFFFF"><?=$record['batterystatus']?></td>
                <td  bgcolor="#FFFFFF"><?=$record['oldProductCode']?></td>
	          <td  bgcolor="#FFFFFF"><?php $opg= mysql_query("select ProductDescription from productmaster where ProductCode='".$record['oldProductCode']."' ")  ;
		$orecord1 = mysql_fetch_array($opg);
       echo $orecord1['ProductDescription']; ?></td>
              <td  bgcolor="#FFFFFF"><?=$record['oldbatteryno']?> </td>
	          <td  bgcolor="#FFFFFF"><?=date("d/m/Y",strtotime($record['ManufacturingDate']))?> </td>
			  <td  bgcolor="#FFFFFF"><?=$record['ProductCode']?></td>
	          <td  bgcolor="#FFFFFF"><?php $pg= mysql_query("select ProductDescription from productmaster where ProductCode='".$record['ProductCode']."' ")  ;
		$record1 = mysql_fetch_array($pg);
       echo $record1['ProductDescription']; ?></td>
	          <td  bgcolor="#FFFFFF"><?=$record['CustomerName']?></td>
			  <td  bgcolor="#FFFFFF"><?=$record['CustomerAddress']?></td>
              <td  bgcolor="#FFFFFF"><?=$record['City']?></td>
	          <td  bgcolor="#FFFFFF"><?=$record['CustomerPhoneNo']?> </td>
              <td  bgcolor="#FFFFFF"><?=$record['RetailerName']?></td>
			  <td  bgcolor="#FFFFFF"><?=$record['FranchiseeName']?> </td>
              <td  bgcolor="#FFFFFF">
			  <? $check3= mysql_query("select modelname from vehiclemodel where modelcode='".$record['VehicleorInverterModel']."' ")  ;
		$check3record = mysql_fetch_array($check3);
       echo $check3record['modelname']; ?>
      </td>
	          <td  bgcolor="#FFFFFF">
			  <?  $check2= mysql_query("select MakeName from vehiclemakemaster where MakeNo='".$record['VehicleorInverterMake']."' ")  ;
		$check2record = mysql_fetch_array($check2);
       echo $check2record['MakeName']; ?>
      </td>
	          <td  bgcolor="#FFFFFF">
               <? $check1= mysql_query("select segmentname from vehiclesegmentmaster where segmentcode='".$record['VehicleSegment']."' ")  ;
		$check1record = mysql_fetch_array($check1);
       echo $check1record['segmentname']; ?>
			</td>
            
			  <td  bgcolor="#FFFFFF"><?=$record['Enginetype']?></td>
              <td  bgcolor="#FFFFFF"><?=$record['VehicleNo']?> </td>
              <td  bgcolor="#FFFFFF">
              
               <? $checkoem= mysql_query("select oemname from oemmaster where oemcode='".$record['oemname']."' ")  ;
		$checkoemrecord = mysql_fetch_array($checkoem);
       echo $checkoemrecord['oemname']; ?>
              
              
              </td>
              </tr>

  <?php
      }
  ?>
  
  <?php
  if(isset($_POST['Search']))
{
if($myrow1==0)	
{?>
		<? echo '<tr ><td colspan="11" align="center" bgcolor="#FFFFFF" style="color:#F00" >No Records Found</td></tr>'; ?> <script>	generalfun();</script>
<? } }?>
</table>


</div>
<?php include("../../paginationdesign.php")?>

             <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
                               <div style="width:70px; height:32px; float:left; margin-left:25px; margin-top:20px;">
                              Export As
             				
                               </div> 
                               <div style="width:83px; height:32px; float:left; margin-left:5px; margin-top:12px;">
                                <select name="Type">
                                  <!-- <option value="PDF">PDF</option>-->
                                    <option value="Excel">Excel</option>
                                     <option value="Document">Document</option>
                                                                   </select>
             				
                               </div>  
                               <div style="width:63px; height:32px; float:right; margin-top:18px;">
             					  <input type="submit" name="PDF" value="Export" class="button"/>
                               </div ></div>

                <!--Main row 2 end-->
            
             <!-- form id start end-->  
</form>			 
          </div> 
          
     </div>       
</div>
<!--Third Block - Menu -Container -->


<!--Footer Block -->
<div id="footer-wrap1">
        <?php include("../../footer.php")?>
  </div>
<!--Footer Block - End-->
</center></body>
</html>
<?
}
?>
