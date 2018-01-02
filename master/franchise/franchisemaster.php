<?php
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");
// Include database connection and functions here.

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $tname,$FranchiseCode,$FranchiseName,$tab,$address,$country,$state,$pincode,$telephoneno,$mobileno,$email,$branch,$contactperson,$designation,$suppliername,$scode,$sname,$Region,$City,$TINno,$CSTno;
  		$scode = 'FranchiseCode';
		$sname = 'FranchiseName';
		$tname	= "franchisemaster";
		require_once '../../searchfun.php';
		$stname="productvehiclemakeupload";		
		$stname1="productvehiclemodelupload";
		$stname2="productvehiclesegmentupload";
		//$stname3="retailermasterupload";
		$stname4="retailercategoryupload";
		$stname5="productfailuremodeupload";
		$tnameprice="pricelistlinkinggrid";
		
		$stname6="productgroupupload";
		$stname7="productsegmentupload";
		$stname8="producttypeupload";
		$stname9="productuomupload";
		$stname10="productmasterupload";
		$stname11="productwarrantyupload";
		$stname12="productmappingupload";
		$stname13="productserviceupload";
		$stname14="oemmasterupload";
		$stname15="schememasterupload";
		
		require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
    $pagename = "Franchise Master";
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
			alert("you are not allowed to do this action!",'franchisemaster.php');//setInterval(function(){document.location='franchisemaster.php';},2000);
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'franchisemaster.php');
			</script>
         <?
		
	}

if(isset($_POST['Save']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	$post['FranchiseCode'] =strtoupper(str_replace('&', 'and',$_POST['FranchiseCode']));
	$post['PrimaryFranchise'] =strtoupper(str_replace('&', 'and',$_POST['PrimaryFranchiseCode']));
	$post['FranchiseName'] = strtoupper(str_replace('&', 'and',$_POST['FranchiseName']));
	$post['Address'] =strtoupper($_POST['address']);
	$countrysmallCode=mysql_query("SELECT * FROM countrymaster where countryname='".$_POST['country']."'");
	$countrysmallfetch=mysql_fetch_array($countrysmallCode);
	$post['Country'] = $countrysmallfetch['countrycode'];
	// $post['Country']=($_POST['country']);
	$statesmallCode=mysql_query("SELECT * FROM state where statename='".$_POST['state']."'");
	$statesmallfetch=mysql_fetch_array($statesmallCode);
	$post['State'] = $statesmallfetch['statecode'];
	//$post['State'] =($_POST['state']);
	$post['Pincode'] =$_POST['pincode'];
	$post['TelephoneNo'] =$_POST['telephoneno'];
	$post['MobileNo'] =$_POST['mobileno'];
	$post['Email'] =$_POST['email'];
	$branchsmallCode=mysql_query("SELECT * FROM branch where branchname='".$_POST['branch']."'");
	$branchsmallfetch=mysql_fetch_array($branchsmallCode);
	$post['Branch'] = $branchsmallfetch['branchcode'];
	// $post['Branch'] =($_POST['branch']);
	$post['ContactPerson'] =strtoupper($_POST['contactperson']);
	$post['Designation'] =strtoupper($_POST['designation']);
	$smallCode=mysql_query("SELECT * FROM region where RegionName='".$_POST['Region']."'");
	$smallfetch=mysql_fetch_array($smallCode);
	$post['Region'] = $smallfetch['RegionCode'];
	// $post['Region'] =($_POST['Region']);
	
	//$City,$TINno,$CSTno
	$post['city']=$_POST['City'];
	$post['tinno']=$_POST['TINno'];
	$post['cstno']=$_POST['CSTno'];
	$City=$_POST['City'];
	$TINno=$_POST['TINno'];
	$CSTno=$_POST['CSTno'];
	
	
	$FranchiseCode = strtoupper($_POST['FranchiseCode']);
	$PrimaryFranchiseCode = strtoupper($_POST['PrimaryFranchiseCode']);
	$FranchiseName = strtoupper($_POST['FranchiseName']);
	$address=$_POST['address'];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$pincode = $_POST['pincode'];
	$telephoneno=$_POST['telephoneno'];
	$mobileno = $_POST['mobileno'];
	$email = $_POST['email'];
	$branch = $_POST['branch'];
	$contactperson=$_POST['contactperson'];
	$designation = $_POST['designation'];
	$Region = $_POST['Region'];
		   
	 if(!empty($_POST['PrimaryFranchiseCode'])&&!empty($_POST['FranchiseCode'])&&!empty($_POST['FranchiseName'])&&!empty($_POST['address'])&&!empty($_POST['country'])&&!empty($_POST['state'])&&!empty($_POST['designation'])&&!empty($_POST['pincode'])&&!empty($_POST['telephoneno'])&&!empty($_POST['branch'])&&!empty($_POST['contactperson'])&&!empty($_POST['email'])&&!empty($_POST['mobileno']))
	 {
	 $p1=strtoupper( preg_replace('/\s+/', '',$post['FranchiseCode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['FranchiseName']));
	$p3=strtoupper( preg_replace('/\s+/', '',$post['PrimaryFranchise']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `FranchiseCode` ,  ' ',  '' ) AS FranchiseCode, REPLACE(  `FranchiseName` ,  ' ',  '' ) AS FranchiseName FROM franchisemaster where FranchiseName = '".$p3."' or FranchiseName = '".$post['PrimaryFranchise']."' or FranchiseName = '".$p2."' or FranchiseName = '".$post['FranchiseName']."' or FranchiseCode = '".$p2."' or FranchiseCode = '".$post['FranchiseName']."' or FranchiseCode= '".$post['PrimaryFranchise']."' or FranchiseCode= '".$p3. "' or FranchiseCode = '".$p1."' or FranchiseCode = '".$post['FranchiseCode']."' or FranchiseName = '".$p1."' or PrimaryFranchise = '".$p2."' or PrimaryFranchise = '".$p1."' or PrimaryFranchise='".$post['FranchiseCode']."' or PrimaryFranchise = '".$post['FranchiseName']."' or  FranchiseName = '".$post['FranchiseCode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		if($cnduplicate>0 || ($post['FranchiseCode']==$post['FranchiseName']) || ($post['FranchiseCode']==$post['PrimaryFranchise']) || ($post['PrimaryFranchise']==$post['FranchiseName']))
		{
		?>
            <script type="text/javascript">
			alert("Duplicate entry!");
			</script>
         <?
		}
	    else
		{
	 					 $post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
		$news->addNews($post,$tname);
		
		$fields = array('productgroupmaster','productuom','productmaster');	
		
	$result = count($fields);
	for($cnt=0;$cnt<$result;$cnt++)
     {
		$spost['Masters']=$fields[$cnt];
   		$spost['Franchiseecode']  =$post['FranchiseCode'];
   		$spost['PrimaryFranchise']  =$post['PrimaryFranchise'];
		$spost['Status']="0";
		$spost['InsertDate']=date("Y/m/d");
	  	$spost['Deliverydae']=date("Y/m/d");
		
		$spost1['Franchisee']=$post['FranchiseCode'];
		$spost1['Status']="0";
		$spost1['InsertDate']=date("Y/m/d");
	  	$spost1['Deliverydae']=date("Y/m/d");
		
			/* if($spost['Masters']=="productgroupmaster")
			{
				$franqry6= mysql_query("SELECT `ProductCode` FROM `productgroupmaster`") or die (mysql_error());
				  while($frqry6 = mysql_fetch_array($franqry6))
				  {
					  $spost['Code']=$frqry6['ProductCode'];
					  $news->addNews($spost,$stname6);
				  }
			}
			else */ 
			if($spost['Masters']=="productuom")
			{

				$franqry9= mysql_query("SELECT `productuomcode` FROM `productuom`") or die (mysql_error());
				  while($frqry9 = mysql_fetch_array($franqry9))
				  {
					  $spost['Code']=$frqry9['productuomcode'];
					  $news->addNews($spost,$stname9);
				  }
			}

		else
		{
			
		}
		
	 }
			 
		?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'franchisemaster.php');//setInterval(function(){document.location='franchisemaster.php';},2000);
			</script>
            <?
	
			}	
			
	 }
	 else
	 {
		 	?>
			<script type="text/javascript">
            alert("Enter Mandatory Fields!");//document.location='franchisemaster.php';
            </script>
            <?
	 }
}

if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:franchisemaster.php');
}

if(isset($_POST['Update'])) // If the submit button was clicked
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		   $FranchiseCode = strtoupper($_POST['FranchiseCode']);
		   $PrimaryFranchiseCode = strtoupper($_POST['PrimaryFranchiseCode']);
		   $FranchiseName = strtoupper($_POST['FranchiseName']);
		   $address=strtoupper($_POST['address']);
		   $country = $_POST['country'];
		   $state = $_POST['state'];
		   $pincode = $_POST['pincode'];
		   $telephoneno=$_POST['telephoneno'];
		   $mobileno = $_POST['mobileno'];
		   $email = $_POST['email'];
		   $branch = $_POST['branch'];
		   $contactperson=strtoupper($_POST['contactperson']);
		   $designation = strtoupper($_POST['designation']);
		   $Region = $_POST['Region'];
		   $post['city']=$_POST['City'];
	 $post['tinno']=$_POST['TINno'];
	 $post['cstno']=$_POST['CSTno'];
	 $City=$_POST['City'];
	 $TINno=$_POST['TINno'];
	 $CSTno=$_POST['CSTno'];
	
	 $post['FranchiseCode'] =strtoupper(str_replace('&', 'and',$_POST['FranchiseCode']));
	 $post['PrimaryFranchise'] =strtoupper(str_replace('&', 'and',$_POST['PrimaryFranchiseCode']));
	 $post['FranchiseName'] =strtoupper(str_replace('&', 'and',$_POST['FranchiseName']));
	 $post['Address'] =strtoupper($_POST['address']);
	 $countrysmallCode=mysql_query("SELECT * FROM countrymaster where countryname='".$_POST['country']."'");
	$countrysmallfetch=mysql_fetch_array($countrysmallCode);
$post['Country'] = $countrysmallfetch['countrycode'];
	// $post['Country']=($_POST['country']);
	$statesmallCode=mysql_query("SELECT * FROM state where statename='".$_POST['state']."'");
	$statesmallfetch=mysql_fetch_array($statesmallCode);
	$post['State'] = $statesmallfetch['statecode'];
	// $post['Country']=($_POST['country']);
	 
	 //$post['State'] =($_POST['state']);
	 $post['Pincode'] =$_POST['pincode'];
	 $post['TelephoneNo'] =$_POST['telephoneno'];
	 $post['MobileNo'] =$_POST['mobileno'];
	 $post['Email'] =$_POST['email'];
	 $branchsmallCode=mysql_query("SELECT * FROM branch where branchname='".$_POST['branch']."'");
	$branchsmallfetch=mysql_fetch_array($branchsmallCode);
	$post['Branch'] = $branchsmallfetch['branchcode'];
	 //$post['Branch'] =($_POST['branch']);
	 $post['ContactPerson'] =strtoupper($_POST['contactperson']);
	 $post['Designation'] =strtoupper($_POST['designation']);
	 $smallCode=mysql_query("SELECT * FROM region where RegionName='".$_POST['Region']."'");
			$smallfetch=mysql_fetch_array($smallCode);
$post['Region'] = $smallfetch['RegionCode'];
 	 //$post['Region'] =($_POST['Region']);
        // This will make sure its displayed
	 if(!empty($_POST['PrimaryFranchiseCode'])&&!empty($_POST['FranchiseCode'])&&!empty($_POST['FranchiseName'])&&!empty($_POST['designation'])&&!empty($_POST['address'])&&!empty($_POST['country'])&&!empty($_POST['state'])&&!empty($_POST['designation'])&&!empty($_POST['pincode'])&&!empty($_POST['telephoneno'])&&!empty($_POST['branch'])&&!empty($_POST['contactperson'])&&!empty($_POST['email'])&&!empty($_POST['mobileno'])&&!empty($_POST['Region']))
	 { 
			$codenamedcheck=0;
		if($_SESSION['frsessionval']!=$FranchiseName)
		{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['FranchiseName']));
		$repqry="SELECT REPLACE(  `FranchiseName` ,  ' ',  '' ) AS FranchiseName  FROM  `franchisemaster` where PrimaryFranchise='".$p2."' or PrimaryFranchise = '".$post['FranchiseName']."' or FranchiseName = '".$p2."' or FranchiseName = '".$post['FranchiseName']."' or FranchiseCode = '".$p2."' or FranchiseCode = '".$post['FranchiseName']."'";
		$repres= mysql_query($repqry) or die (mysql_error());
		$codenamedcheck=mysql_num_rows($repres);
		}
			if($codenamedcheck>0)
			{
			?>
            <script type="text/javascript">
			alert("Duplicate entry!");
			</script>
       	  	<?
			}
			else{
				$post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
				$wherecon= "FranchiseCode ='".$post['FranchiseCode']."'";
			$news->editNews($post,$tname,$wherecon);
			
			
			
			
			
	/* Below operation performed to change the modification in report tables */
		  
		  if($_SESSION['FranchiseName']!=$post['FranchiseName'] || $_SESSION['Branch']!=$_POST['branch'] || $_SESSION['Region'] !=$_POST['Region'])
		  {
				$rvalues['franchisename']=$post['FranchiseName'];
				$rvalues['branchname']   =$_POST['branch'];
				$rvalues['regionname']   =$_POST['Region'];
				$wherecon= "franchisecode ='".$post['FranchiseCode']."'";
				$news->editNews($rvalues,"r_salesreport",$wherecon);
				$news->editNews($rvalues,"r_purchasereport",$wherecon);
				$news->editNews($rvalues,"r_purchasereturn",$wherecon);
				$news->editNews($rvalues,"r_salesreturn",$wherecon);
		  }
		unset($_SESSION['FranchiseName']);unset($_SESSION['Branch']);unset($_SESSION['Region']);
		
		
		
		
			
			
			
						unset($_SESSION['frsessionval']);
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'franchisemaster.php');//setInterval(function(){document.location='franchisemaster.php';},2000);
			</script>
            <?
					}
					
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");
			</script>
            <?
		}
	}

// Check if delete button active, start this 
	
	if(isset($_POST['Delete']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	if(!isset($_POST['checkbox']))
	{
			?>
		    <script type="text/javascript">
			alert("Select data to delete!");
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
		//$repqry1="SELECT Franchisecode from franchisemaster where Franchisecode in(select Franchisee from pricelistlinking where Franchisee='".$prodidd."') ";
		$repqry1="select Franchisecode
FROM franchisemaster
WHERE Franchisecode='".$prodidd."' and EXISTS(
 SELECT Franchisee
FROM  `pricelistlinking` 
WHERE Franchisee='".$prodidd."'
)
OR exists(

SELECT fmexecutive
FROM retailermaster
WHERE fmexecutive =  '".$prodidd."'
) ";		
		
		 $repres= mysql_query($repqry1) or die (mysql_error());
		$myrow1 = mysql_num_rows($repres);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
		$fields=array('productgroupupload','productuomupload','productmasterupload');
		//$fields = array('vehiclemakemaster','vehiclemodel','vehiclesegmentmaster','retailermaster','retailercategory','failuremode'/*,'pricelistlinkinggrid'*/,'productgroupmaster','productsegmentmaster','producttypemaster','productuom','productmaster','productwarranty','productmapping','servicemaster','oemmaster','schememaster');	
		$result = count($fields);
		//$disval= array();
		//$a=0;
		$ace=0;
		for($cnt=0;$cnt<$result;$cnt++)
     	{
					 $tab=$fields[$cnt];
					 $mkrow = mysql_query("SELECT Status FROM $tab where Franchiseecode='".$prodidd."' and Status >'0' ");
					 $disp= mysql_num_rows($mkrow);
					 if($disp>0)
					 {
						 $ace++;
						 break;
					 }
					
		}
			if($ace == 0)
			{
				for($cnt1=0;$cnt1<$result;$cnt1++)
				{
				$tab=$fields[$cnt1];
					
				$wherec= "Franchiseecode='".$prodidd."'";				
				$news->deleteNews($tab,$wherec);
				}
				$wherecon= "Franchisecode ='".$prodidd."'";
				$news->deleteNews($tname,$wherecon);
				?>
				<script type="text/javascript">
				alert("Deleted  Successfully!",'franchisemaster.php');//setInterval(function(){document.location='franchisemaster.php';},2000);
				</script>
				<?
			}
			else
			{
				?>
						<script type="text/javascript">
						alert("You Can't delete already send to Franchisee!",'franchisemaster.php');
						</script>
						<?
			}
		
		}			
		else
		{

		?>
            <script type="text/javascript">
			alert("you can't delete already used in other masters!",'franchisemaster.php');
			</script>
   			<?
		}
		}
}
}


//EDIT
if(!empty($_GET['edi']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$prmaster =$_GET['edi'];

//$cont->connect();
$result=mysql_query("SELECT * FROM franchisemaster where FranchiseCode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!");//document.location='franchisemaster.php';
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		
		   $FranchiseCode = $myrow['Franchisecode'];
		   $PrimaryFranchiseCode = $myrow['PrimaryFranchise'];
		   $FranchiseName = $myrow['Franchisename'];
		   $address=$myrow['Address'];
		   $countrypgg= mysql_query("select countryname from countrymaster where countrycode='".$myrow['Country']."' ")  ;
		$countryrecord11 = mysql_fetch_array($countrypgg);
        $country = $countryrecord11['countryname'];
		   //$country = $myrow['Country'];
		   $Statepgg= mysql_query("select statename from state where statecode='".$myrow['State']."' ")  ;
		$Staterecord11 = mysql_fetch_array($Statepgg);
        $state = $Staterecord11['statename'];
		  // $state = $myrow['State'];
		   $pincode = $myrow['Pincode'];
		   $telephoneno=$myrow['TelephoneNo'];
		   $mobileno = $myrow['MobileNo'];
		   $email = $myrow['Email'];
		   $branchpgg= mysql_query("select branchname from branch where branchcode='".$myrow['Branch']."' ")  ;
		$branchrecord11 = mysql_fetch_array($branchpgg);
        $branch = $branchrecord11['branchname'];
		   //$branch = $myrow['Branch'];
		   $contactperson=$myrow['ContactPerson'];
		   $designation = $myrow['Designation'];
		   $pgg= mysql_query("select RegionName from region where RegionCode='".$myrow['Region']."' ")  ;
		$record11 = mysql_fetch_array($pgg);
        $Region = $record11['RegionName'];
		  // $Region =$myrow['Region'];
		   
		   $City=$myrow['city'];
		   $TINno=$myrow['tinno'];
		   $CSTno=$myrow['cstno'];
		   $_SESSION['frsessionval']= $myrow['Franchisename'];
		}
		
		$prmaster = NULL;
}

$_SESSION['type']=NULL;
	$franchisemaster='select * from franchisemaster';
if(isset($_POST['PDF']))
{

$select=$_POST['Type'];
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM franchisemaster WHERE FranchiseCode like'".$_POST['codes']."%' OR FranchiseName like'".
		$_POST['names']."%'order by m_date desc";$franchisemaster=$condition;
		
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM franchisemaster WHERE FranchiseCode like'".$_POST['codes']."%'order by m_date desc";$franchisemaster=$condition;
		
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM franchisemaster WHERE FranchiseName like'".$_POST['names']."%'order by m_date desc";$franchisemaster=$condition;
		
	}
	else
	{
		
		$condition="SELECT * FROM franchisemaster order by m_date desc";$franchisemaster=$condition;
	}
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$franchisemaster;
	//echo  $productwarranty;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	
	$myquery = mysql_query($franchisemaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
	   $groupselct="SELECT branchname FROM branch where branchcode='".$myrecord['Branch']."'";
	   $groupselct1 = mysql_query($groupselct);
	   $cntno1=mysql_num_rows($groupselct1);
	   if($cntno1==1)
   		{
		   	$groupselct12 = mysql_fetch_array($groupselct1);
			$testtempp=$groupselct12['branchname'];
	   }
	    else
	   {
		   $testtempp ="";
	   }
	   $stateselct="SELECT statename FROM state where statecode='".$myrecord['State']."'";
	   $stateselct1 = mysql_query($stateselct);
	   $cntno2=mysql_num_rows($stateselct1);
	   if($cntno2==1)
   		{
		   	$stateselct12 = mysql_fetch_array($stateselct1);
			$statetempp=$stateselct12['statename'];
	   }
	    else
	   {
		   $statetempp ="";
	   }
	    $regionselct="SELECT RegionName FROM region where RegionCode='".$myrecord['Region']."'";
	   $regionselct1 = mysql_query($regionselct);
	   $cntno3=mysql_num_rows($regionselct1);
	   if($cntno3==1)
   		{
		   	$regionselct12 = mysql_fetch_array($regionselct1);
			$regiontempp=$regionselct12['RegionName'];
	   }
	    else
	   {
		   $regiontempp ="";
	   }
	    $countryselct="SELECT countryname FROM countrymaster where countrycode='".$myrecord['Country']."'";
	   $countryselct1 = mysql_query($countryselct);
	   $cntno4=mysql_num_rows($countryselct1);
	   if($cntno4==1)
   		{
		   	$countryselct12 = mysql_fetch_array($countryselct1);
			$countrytempp=$countryselct12['countryname'];
	   }
	    else
	   {
		   $countrytempp ="";
	   }
$stringData =$myrecord[0]."\t ;".$myrecord[1]."\t ;".$myrecord[2]."\t ;".$myrecord[3]."\t ;".$myrecord[4]."\t  ;".$myrecord[5]."\t ;".$myrecord[6]."\t ;".$myrecord[7]."\t ;".$myrecord[8].";\t".$myrecord[9]."\t  ;". $testtempp."\t ;".$statetempp."\t ;".$regiontempp."\t ;".$myrecord[15]." ;".$myrecord[16]." ;\t\n";//
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportFranchisee.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$franchisemaster;

	header('Location:ExportFranchisee.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$franchisemaster;
	header('Location:ExportFranchisee.php');
}
}

?>

<script type="text/javascript"> 
 
	

var filter = /^[0-9-+() ]+$/
function validateMobno(ph) {
    var object = document.getElementById('ph');
    var returnvalph=filter.test(ph.value)
    if (returnvalph==false) {
		alert("Please enter a valid Mobile number")
		toutfun(object);
		ph.value=''; 
		ph.focus()
//ph.select()
// $(':text').val(''); 
        
    }
    return returnvalph;
    }

var filter = /^[0-9-+() ]+$/
function validatePhoneno(th) {
    var object = document.getElementById('th');
    var returnvalph=filter.test(th.value);
    if (returnvalph==false) {
		alert("Please enter a valid Telephone number")
		toutfun(object);
		th.value=''; 
		th.focus()
//ph.select()
// $(':text').val(''); 
        
    }
    return returnvalph;
    }

	
		function validate(form_id,email) {
 
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   var address = document.forms[form_id].elements[email].value;
   if(reg.test(address) == false) {
 
      alert('Invalid Email Address (for eg. xyz@abc.com)');
	  
	  document.forms[form_id].elements[email].value="";
	  toutfun(email);
      return false;
   }
}

function validateFranchiseCode(key)
{
	var phn = document.getElementById('FranchiseCode');
	if (phn.value.length <15 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 15 characters");
return false;
}
} 

function validateFranchiseCode1(key)
{
	var phn = document.getElementById('PrimayFranchiseCode');
	if (phn.value.length <15 || key.keycode==8 || key.keycode==46)
	{
		return true;
	}
	else
	{
		alert("Enter only 15 characters");
		return false;
	}
}

function validateFranchiseName(key)
{
	var object = document.getElementById('FranchiseName');
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
function validatePersonName(key)
{
	var object = document.getElementById('contactperson');
	if (object.value.length <50 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 50 characters");
	toutfun(object);
return false;
}s
}
function validateDesignation(key)
{
	var object = document.getElementById('designation');
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
function validateAddress(key)
{
	var object = document.getElementById('address');
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

function validatecity(key)
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
function validatetinno(key)
{
	var object = document.getElementById('TINno');
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
function validatecstno(key)
{
	var object = document.getElementById('CSTno');
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
function validatecontac1(key)
{
	var object = document.getElementById('ph');
	if (object.value.length <20 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 20 numbers");
	toutfun(object);
return false;
}
}

function validatecontac(key)
{
	var object = document.getElementById('th');
	if (object.value.length <20 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 20 numbers");
	toutfun(object);
return false;
}
}

var emailfilter=/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i

function checkmail(e){
	var object =document.getElementById('email');
var returnval=emailfilter.test(e.value)
if (returnval==false){
alert("Please enter a valid email address.")
toutfun(object);
document.getElementById('email').value="";
e.select()
}
return returnval
}


     function isPincode(evt) 
        {  var object = document.getElementById('pincode');         
            var le=document.getElementById("pincode").value.length;           
            if( le < 6 )
            {
                   alert("Enter six digit pincode!");
				   toutfun(object);
				   document.getElementById("pincode").value='';
				   return false;
				  
            }      
            return true;                      
        }   

        function isLenChk() 
        {
			var object = document.getElementById('FranchiseCode');         
            var le=document.getElementById("FranchiseCode").value.length; 
			// var can = document.getElementById('cancel').click();      
			
            if( le < 6 )
            {
                   alert("Distributor code should be 6 characters!");
				   document.getElementById("FranchiseCode").value='';
                   toutfun(object);
            }                   
        }   
         function isLenChk1() 
        {
			var object = document.getElementById('PrimaryFranchiseCode');         
            var le=document.getElementById("PrimaryFranchiseCode").value.length; 
			// var can = document.getElementById('cancel').click();      
			
            if( le < 6 )
            {
                   alert("Distributor code should be 6 characters!");
				   document.getElementById("PrimaryFranchiseCode").value='';
                   toutfun(object);
            }                   
        }
		
function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}

  function trim (el) {
    el.value = el.value.
       replace (/(^\s*)|(\s*$)/gi, ""). // removes leading and trailing spaces
       replace (/[ ]{2,}/gi," ").       // replaces multiple spaces with one space 
       replace (/\n +/,"\n");           // Removes spaces after newlines
    return;
}


 function setvalu()
{
	var e = document.getElementById("branch"); 
	var er=e.options[e.selectedIndex].value;
	var ddlArray= new Array();
	var ddl = document.getElementById('emplist');
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
		
		document.getElementById("Region").value=tt;
		document.getElementById("country").value=tt2;
	}
}
    </script>
     <title><?php echo $_SESSION['title']; ?> || Distributor Master</title>
</head>

<?php 
if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.FranchiseName.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.PrimaryFranchiseCode.focus()">

 <? }  
}else{?>
<body class="default" onLoad="document.form1.codes.focus()">

 <? } ?>
 <center>

<?php include("../../menu.php") ?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
			<form id="frm1" name="form1" method="POST" action="<?php $_PHP_SELF ?>">
              <table id="default" style=" height:10px; display:none;" >
            <tr>
                <td>
                                    <select  name="emplist" id="emplist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT branchname,RegionName,countryname FROM `view_branch1`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									        
                                      echo "<option value=\"".$record['branchname']."~".$record['RegionName']."~".$record['countryname']."\">".$record['branchname']."~".$record['RegionName']."~".$record['countryname']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td>
            </tr></table>
            
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Distributor Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:300px; height:auto; padding-bottom:5px; float:left; " class="cont">
                           	<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Primary DistributorCode</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?php if(!empty($_GET['edi']))
							{?>
                          <input type="text" name="PrimaryFranchiseCode" id="PrimaryFranchiseCode" style="border-style:hidden; background:#f5f3f1;text-transform:uppercase;" readonly="readonly" onKeyPress="return validateFranchiseCode1(event)" value="<? echo $PrimaryFranchiseCode?>" onChange="return trim(this)"/>
                            <? } 
							else { ?>
                                 <input type="text" name="PrimaryFranchiseCode" id="PrimaryFranchiseCode" maxlength="6" value="<? echo $PrimaryFranchiseCode?>" onKeyPress="return codetrim(this)" style="text-transform:uppercase;" onChange="isLenChk1();"/>
                                   <?
							}
							?>
                               </div>
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Distributor Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?php if(!empty($_GET['edi']))
							{?>
                          <input type="text" name="FranchiseCode" id="FranchiseCode" style="border-style:hidden; background:#f5f3f1;text-transform:uppercase;" readonly="readonly" onKeyPress="return validateFranchiseCode(event)" value="<? echo $FranchiseCode?>" onChange="return trim(this)"/>
                            <? } 
							else { ?>
                                 <input type="text" name="FranchiseCode" id="FranchiseCode" maxlength="6" value="<? echo $FranchiseCode?>" onKeyPress="return codetrim(this)" style="text-transform:uppercase;" onChange="isLenChk();"/>
                                   <?
							}
							?>
                               </div>
 							<!--Row1 end-->
                             <!--Row2 -->  
                               
 							<!--Row2 end-->   
                             <!--Row3 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Distributor Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="FranchiseName" id="FranchiseName" maxlength="50" onKeyPress="return validateFranchiseName(event)" value="<? echo $FranchiseName?>" onChange="return trim(this)" style="text-transform:uppercase;" />
                               </div>
 							<!--Row3 end-->   
                             <!--Row4 -->  
                              
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Contact Person Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                     <input type="text" onKeyPress="return validatePersonName(event)" style="text-transform:uppercase;" name="contactperson" id="contactperson" value="<? echo $contactperson?>" onChange="return trim(this)" maxlength="50"/>
                               </div>

                               		 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Designation</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <input type="text" name="designation" id="designation" onKeyPress="return validateDesignation(event)" value="<? echo $designation?>" onChange="return trim(this)" maxlength="50" style="text-transform:uppercase;"/>
                               </div>
                                <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Address</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:65px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <textarea rows="4" cols="14" name="address" id="address" style="text-transform:uppercase;width:129px" onKeyPress="return validateAddress(event)" onChange="return trim(this)" maxlength="250"><? echo $address?></textarea>
                               </div>
                         
							   
							   
 							<!--Row1 end-->
                            <!--Row5 -->  
                              
 							<!--Row5 end--> 
                            
                                                  
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                            <!--Row5 -->  
                               
 							<!--Row5 end-->          
                           
                             <!--Row1 -->  
                              <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Pincode</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="pincode" id="pincode" value="<? echo $pincode?>" onKeyUp="numericFilter(this)" onChange="return isPincode(event)"  maxlength="6" onBlur="return codetrim(this)"/>
                               </div>
							   <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Telephone No</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                     <input type="text" name="telephoneno" id="th" onKeyUp="return validatecontac(event)" onChange="return validatePhoneno(this)" value="<? echo $telephoneno?>" maxlength="20" onBlur="return trim(this)"/>
                               </div>

                               		 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Mobile No</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <input type="text" name="mobileno" id="ph" value="<? echo $mobileno?>" onKeyUp="return validatecontac1(event)" maxlength="20" onChange="return validateMobno(this)"  onBlur="return trim(this)"/>
                               </div>

                               	<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Email</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <input type="text" name="email" id="email" onChange="return checkmail(this)" value="<? echo $email?>" onBlur="return codetrim(this)"/>
                               </div>		
 						   <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label >Branch</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                         <!-- getagentids1-->
                                    <select name="branch" onChange="setvalu();" id="branch" >
                                       <option value="<?php echo $branch;?>"><? if(!empty($branch)){ echo $branch;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT Branchname FROM branch order by Branchname asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     { 
									  if($branch!=$record['Branchname'])
									  {     
                                      echo "<option value=\"".$record['Branchname']."\">".$record['Branchname']."\n ";
									  }
                                     }
                                    ?>
                                          </select>
                               </div>
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>State</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <?php /*?><input Type="text" readonly="readonly" value="<?php echo $state;?>" name="state" id="state" ><?php */?>
                                      <select name="state" id="state" >
                                       <option value="<? echo $state;?>"><? if(!empty($state)){ echo $state;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT statename FROM state order by statename asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($state!=$record['statename'])
									  {        
                                      echo "<option value=\"".$record['statename']."\">".$record['statename']."\n "; 
									  }
									 }
                              ?>
                                          </select>
                               </div>
                                 
                                <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Region</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text"  name="Region" value="<?php echo $Region;?>" id="Region" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" onFocus="City.focus();" >
                                  
                               </div> 
                               
                              
                            
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                            	<!--Row4end-->   
                              
 							<!--Row4end--> 
                          
                                <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Country</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                <input type="text" name="country" id="country" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?php echo $country;?>" onFocus="City.focus();" >
                                      <!--
                                     <select name="country" id="country" >
                                       <option value="<?php /*echo $country;?>"><? if(!empty($country)){ echo $country;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT countryname FROM countrymaster");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {     
                                      echo "<option value=\"".$record['countryname']."\">".$record['countryname']."\n ";                      
                                     }
                                    */?>
                                          </select>-->
                               </div> 
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>City</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="City" id="City" value="<? echo $City?>" onChange="return trim(this)" onKeyUp="return validatecity(event)" maxlength="50"/>
                               </div>
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>TIN No</label>
                               </div>
                               
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="TINno" id="TINno" value="<? echo $TINno?>" onChange="return trim(this)" onKeyPress="return validatetinno(event)" maxlength="15"/>
                               </div>
                              <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>CST No</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="CSTno" id="CSTno" value="<? echo $CSTno?>" onChange="return trim(this)" onKeyPress="return validatecstno(event)" maxlength="15"/>
                               </div>
                            </div>      
                     <!-- col3 --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                     <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:-5px;">
                             
						<div style="width:235px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
                          <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                    <?php  if(!empty($_GET['edi']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" >
				          <? } ?>
				           </div>
                          
                           <div style="width:100px; height:32px; float:left;margin-top:16px; margin-left:10px;">
						  <input name="Cancel" type="submit" id="Cancel" class="button" value="Reset">
				           </div>    
                           
                                                      
                                               
				     </div>	
                         
                          <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
							  <br/>
                               <div style="width:100px; height:32px; margin-left:20px; float:left;"  class="cont">
						     <label>Distributor Code </label>
				           </div>	
                           
                           <div style="width:130px; height:32px; margin-left:10px; float:left;" class="cont">
						    <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
				           </div> 
                           
                            <div style="width:110px; height:32px; margin-left:20px; float:left;" class="cont">
						     <label>Distributor Name</label>
				           </div>	
                           
                           <div style="width:130px; height:32px; margin-left:10px; float:left;" class="cont">
						    <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
				           </div> 
						   
						   <div style="width:80px; height:32px; margin-left:10px; float:left;">
						  <input name="Search" type="submit" id="Search" class="button" value="Search">
				           </div>    
                          </div> 
                </div>
                
                <!--Main row 2 end-->
            <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                   
                <table id="datatable1" align="center" class="sortable" bgcolor="#FF0000" border="1" width="900px" cellpadding="20%">
     			<tr style="white-space:nowrap;">
                 <?  if(($row['deleterights'])=='Yes')
	 {
	?> 
               <td class="sorttable_nosort" style="font-weight:bold; text-align:center"><input type='checkbox' name='checkall' id="checkall" onclick='checkedAll(frm1);'></td>
               <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
               <td class="sorttable_nosort" style="font-weight:bold; text-align:center">Action</td>
               <? 
	  } 
	  ?>		<td style="font-weight:bold; width:auto; text-align:center;">PrimaryDistributorCode</td>
                <td style="font-weight:bold; width:auto; text-align:center;">DistributorCode</td>
                <td style="font-weight:bold; width:auto; text-align:center;">DistributorName</td>
                <td style="font-weight:bold; width:auto; text-align:center;">ContactPersonName</td>
                <td style="font-weight:bold; width:auto; text-align:center;">Designation</td>
                <td style="font-weight:bold; width:auto; text-align:center;">Address</td>
                <td style="font-weight:bold; width:auto; text-align:center;">Pincode</td>
                <td style="font-weight:bold; width:auto; text-align:center;">TelephoneNo</td>
                <td style="font-weight:bold; width:auto; text-align:center;">MobileNo</td>
                <td style="font-weight:bold; width:auto; text-align:center;">Email</td>
                <td style="font-weight:bold; width:auto; text-align:center;">Branch</td>
                <td style="font-weight:bold; width:auto; text-align:center;">State</td>
                <td style="font-weight:bold; width:auto; text-align:center;">Region</td>
                <td style="font-weight:bold; width:auto; text-align:center;">Country</td>
                <td style="font-weight:bold; width:auto; text-align:center;">City</td>
                <td style="font-weight:bold; width:auto; text-align:center;">TIN No</td>
                <td style="font-weight:bold; width:auto; text-align:center;">CST No</td>
               
               
               
               
               
               <!--<td style="font-weight:bold;">SupplierName</td>-->
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
			  <td align="center" bgcolor="#FFFFFF" style="font-weight:bold; text-align:center" ><input name="checkbox[]" onChange="test();" type="checkbox" id="checkbox[]" value="<? echo $record['Franchisecode'];
			  ?>"></td>
               <? } 
	 if(($row['editrights'])=='Yes') 
	 { 
	 ?>
			  <td bgcolor="#FFFFFF" style="font-weight:bold; text-align:center" > <a style=" color:#0360B2; " name="edit" href="franchisemaster.php?<?php if(($row['editrights'])=='Yes') { echo 'edi='; echo $record['Franchisecode'];} else echo 'permiss'; ?>">Edit</a></td>
              <? 
	  } 
	  ?>	
	  		  <td  bgcolor="#FFFFFF">
              <?=$record['PrimaryFranchise']?>
              </td>
		      <td  bgcolor="#FFFFFF">
              <?=$record['Franchisecode']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['Franchisename']?>
              </td>
               <td  bgcolor="#FFFFFF" >
              <?=$record['ContactPerson']?>
              </td>
               <td  bgcolor="#FFFFFF" >
              <?=$record['Designation']?>
              </td>
	          <td  bgcolor="#FFFFFF" >
              <?=$record['Address']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['Pincode']?>
              </td>
                <td  bgcolor="#FFFFFF" >
              <?=$record['TelephoneNo']?>
              </td>
	          <td  bgcolor="#FFFFFF" >
              <?=$record['MobileNo']?>
              </td>
	          <td  bgcolor="#FFFFFF" >
			  <?=$record['Email']?>
			  </td>
			  <td  bgcolor="#FFFFFF">
              <? $check3= mysql_query("select branchname from branch where branchcode='".$record['Branch']."' ")  ;
		$check3record = mysql_fetch_array($check3);
       echo $check3record['branchname']; ?>
              
              </td>
             <td  bgcolor="#FFFFFF">
             <?  $check2= mysql_query("select statename from state where statecode='".$record['State']."' ")  ;
		$check2record = mysql_fetch_array($check2);
       echo $check2record['statename']; ?>
             
              </td>
               <td  bgcolor="#FFFFFF" >
               <? $check1= mysql_query("select RegionName from region where RegionCode='".$record['Region']."' ")  ;
		$check1record = mysql_fetch_array($check1);
       echo $check1record['RegionName']; ?>
              
              </td>
	          <td  bgcolor="#FFFFFF" >
              <? $check= mysql_query("select countryname from countrymaster where countrycode='".$record['Country']."' ")  ;
		$checkrecord = mysql_fetch_array($check);
       echo $checkrecord['countryname'];  ?>
			 
			  </td>
          	 <td  bgcolor="#FFFFFF">
              <?=$record['city']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['tinno']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['cstno']?>
              </td>
              
            
	         
              </td>
	          
              <!--<td  bgcolor="#FFFFFF" >
              <?php /*?><?=$record['suppliername']?><?php */?>
              </td></tr>
         -->

  <?php
      }
  ?>
                   <?php
  if(isset($_POST['Search']))

{
if($myrow1==0)	
{?>
		<? echo '<tr ><td colspan="21" align="center" bgcolor="#FFFFFF" style="color:#F00"  >No Records Found</td></tr>'; ?>	
<? } }?>
</table>
</div>

<?php include("../../paginationdesign.php")
?>

             <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
                               <div style="width:70px; height:32px; float:left; margin-left:25px; margin-top:20px;">
                              Export As
             				
                               </div> 
                               <div style="width:83px; height:32px; float:left; margin-left:5px; margin-top:12px;">
                                <select name="Type"><option value="PDF">PDF</option>
                                     <option value="Excel">Excel</option>
                                     <option value="Document">Document</option>
                                                                   </select>
             				
                               </div>  
                               <div style="width:63px; height:32px; float:right; margin-top:18px;">
             					  <input type="submit" name="PDF" value="Export" class="button"/>
                               </div ></div>
</div>
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
