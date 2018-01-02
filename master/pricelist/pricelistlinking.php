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
	
	global $tname,$PriceListCode,$pricelistname ,$Country,$State,$Branch,$scode,$sname,$Franchisee;
	$scode = 'PriceListCode';
	$sname = 'Franchisee';
	$tname	= "pricelistlinking";
	require_once '../../searchfun.php';
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
		
	$pagename = "PricelistLink";
	$validuser = $_SESSION['username'];
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");//$news->getNewsById($validuser,$pagename);
	$row = mysql_fetch_array($selectvar);
  ///
  

  ///////////////
 	if (($row['viewrights'])== 'No')
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
	if(isset($_POST['permiss'])) // If the submit button was clicked
    {
		?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'pricelistlinking.php');	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'pricelistlinking.php');
			</script>
         <?
		
	}

if(isset($_POST['Save'])) // If the submit button was clicked
{
		unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$post['PriceListCode'] 		= trim($_POST['PriceListCode']);
$countrysmallCode=mysql_query("SELECT * FROM countrymaster where countryname='".$_POST['Country']."'");
$countrysmallfetch=mysql_fetch_array($countrysmallCode);
$post['Country'] = $countrysmallfetch['countrycode'];

// $post['Country'] 		= trim($_POST['Country']);
//$post['Region'] = trim($_POST['Region']);

$statesmallCode=mysql_query("SELECT * FROM state where statename='".$_POST['State']."'");
$statesmallfetch=mysql_fetch_array($statesmallCode);
$post['State'] = $statesmallfetch['statecode'];
//$post['State'] 	 = trim($_POST['State']);

$branchsmallCode=mysql_query("SELECT * FROM branch where branchname='".$_POST['Branch']."'");
$branchsmallfetch=mysql_fetch_array($branchsmallCode);
$post['Branch'] = $branchsmallfetch['branchcode'];
//$post['Branch']= trim($_POST['Branch']);
$post['Franchisecode'] = trim($_POST['Franchisee']);

	
	// This will make sure its displayed
	if(!empty($_POST['PriceListCode'])&&!empty($_POST['pricelistname'])&&!empty($_POST['Country']))
	{  
	$posting=0;
		
	if(!empty($_POST['Franchisee']))
	{
		$result="SELECT * FROM pricelistlinking where PriceListCode ='".$post['PriceListCode']."' AND Franchisee ='".$_POST['Franchisee']."'";
		$sql1 = mysql_query($result) or die (mysql_error());
		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
			if($myrow1>0)
			{
			?>
			<script type="text/javascript">
			alert("Duplicate entry!",'pricelistlinking.php');//document.location='pricelistlinking.php';
			</script>
			<?
			}
		else
		{
		$row =mysql_query( "select * from franchisemaster where Country = '".$post['Country']."'  and Franchisecode='".$post['Franchisecode']."'");
		$count = mysql_num_rows($row);
			if($count>0)
			{
				while( $myrow = mysql_fetch_array($row))
				{
				$outa['PriceListCode']=$post['PriceListCode'];
				//$outa['pricelistname']=$post['pricelistname'];
				$outa['Country']=$post['Country'];
				// $outa['Region']=$myrow['Region'];
				$outa['State']=$myrow['State'];
				$outa['Branch']=$myrow['Branch'];
				$outa['Franchisee']=$myrow['Franchisecode'];
				$outa['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$outa['m_date']= date("y/m/d : H:i:s", time());
				$news->addNews($outa,$tname);
				$row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
					while( $secrow = mysql_fetch_array($row2))
					{
					$outt['PriceListCode']=$post['PriceListCode'];
					$outt['pricelistname']=$post['pricelistname'];
					$outt['Country']=$post['Country'];
					//$outt['Region']=$myrow['Region'];
					$outt['State']=$myrow['State'];
					$outt['Branch']=$myrow['Branch'];
					$outt['Franchisee']=$outa['Franchisee'];
					$outt['effectivedate']=$secrow['effectivedate'];
					$outt['applicabledate']=$secrow['applicabledate'];
					$outt['productcode']=$secrow['productcode'];
					// $outt['productdescription']=$secrow['productdescription'];
					$outt['mrp']=$secrow['mrp'];
					$outt['fprice']=$secrow['fprice'];
					$outt['rprice']=$secrow['rprice'];
					$outt['iprice']=$secrow['iprice'];
					
					$outt['Status']="0";
					$outt['InsertDate']=date("Y/m/d");
					$outt['Deliverydae']=date("Y/m/d");
					
					$tablename='pricelistlinkinggrid';
					$news->addNews($outt,$tablename);
					$posting++; 
					}
				}
			}
		}
	}
	elseif(!empty($_POST['Branch']))
	{
	// "select * from franchisemaster where Country = '".$post['Country']."' and Region='".$post['Region']."'  and Branch='".$post['Branch']."'";
	$row =mysql_query( "select * from franchisemaster where Country = '".$post['Country']."' and Branch='".$post['Branch']."'");
	$count = mysql_num_rows($row);
		if($count>0)
		{
			while( $myrow = mysql_fetch_array($row))
			{
			$outa['PriceListCode']=$post['PriceListCode'];
			//$outa['pricelistname']=$post['pricelistname'];
			$outa['Country']=$post['Country'];
			//$outa['Region']=$myrow['Region'];
			$outa['State']=$myrow['State'];
			$outa['Branch']=$myrow['Branch'];
			$outa['Franchisee']=$myrow['Franchisecode'];
			$result="SELECT * FROM pricelistlinking where PriceListCode ='".$post['PriceListCode']."' AND Franchisee ='".$outa['Franchisee']."'";
			$sql1 = mysql_query($result) or die (mysql_error());
			$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
				if($myrow1>0)
				{
				?>
				<script type="text/javascript">
				alert("Duplicate entry!",'pricelistlinking.php');//document.location='pricelistlinking.php';
				</script>
				<?
				}
				else
				{
					
				$outa['Franchisee']=$myrow['Franchisecode'];
				$outa['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$outa['m_date']= date("y/m/d : H:i:s", time());
				$news->addNews($outa,$tname);
				$row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
					while( $secrow = mysql_fetch_array($row2))
					{
					$outt['PriceListCode']=$post['PriceListCode'];
					$outt['pricelistname']=$post['pricelistname'];
					$outt['Country']=$post['Country'];
					//$outt['Region']=$myrow['Region'];
					$outt['State']=$myrow['State'];
					$outt['Branch']=$myrow['Branch'];
					$outt['Franchisee']=$outa['Franchisee'];
					$outt['effectivedate']=$secrow['effectivedate'];
					$outt['applicabledate']=$secrow['applicabledate'];
					$outt['productcode']=$secrow['productcode'];
					// $outt['productdescription']=$secrow['productdescription'];
					$outt['mrp']=$secrow['mrp'];
					$outt['fprice']=$secrow['fprice'];
					$outt['rprice']=$secrow['rprice'];
					$outt['iprice']=$secrow['iprice'];
					
					$outt['Status']="0";
					$outt['InsertDate']=date("Y/m/d");
					$outt['Deliverydae']=date("Y/m/d");
					
					$tablename='pricelistlinkinggrid';
					$news->addNews($outt,$tablename);
					$posting++; 
					}
				
				}
			}
		}
		else
		{
			header('Location:pricelistlinking.php');
		}
	}
	elseif(!empty($_POST['State']))
	{
	$row =mysql_query( "select * from franchisemaster where Country = '".$post['Country']."' and State='".$post['State']."'");
	$count = mysql_num_rows($row);
		if($count>0)
		{
			while( $myrow = mysql_fetch_array($row))
			{
			$outa['PriceListCode']=$post['PriceListCode'];
			//$outa['pricelistname']=$post['pricelistname'];
			$outa['Country']=$post['Country'];
			//$outa['Region']=$myrow['Region'];
			$outa['State']=$myrow['State'];
			$outa['Branch']=$myrow['Branch'];
			$outa['Franchisee']=$myrow['Franchisecode'];
			$result="SELECT * FROM pricelistlinking where PriceListCode ='".$post['PriceListCode']."' AND Franchisee ='".$outa['Franchisee']."'";
			$sql1 = mysql_query($result) or die (mysql_error());
			$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
				if($myrow1>0)
				{
				?>
				<script type="text/javascript">
				alert("Duplicate entry!",'pricelistlinking.php');//document.location='pricelistlinking.php';
				</script>
				<?
				}
				else
				{
					
				$outa['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$outa['m_date']= date("y/m/d : H:i:s", time());
				$news->addNews($outa,$tname);
				$row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
					while( $secrow = mysql_fetch_array($row2))
					{
					$outt['PriceListCode']=$post['PriceListCode'];
					$outt['pricelistname']=$post['pricelistname'];
					$outt['Country']=$post['Country'];
					//$outt['Region']=$myrow['Region'];
					$outt['State']=$myrow['State'];
					$outt['Branch']=$myrow['Branch'];
					$outt['Franchisee']=$outa['Franchisee'];
					$outt['effectivedate']=$secrow['effectivedate'];
					$outt['applicabledate']=$secrow['applicabledate'];
					$outt['productcode']=$secrow['productcode'];
					// $outt['productdescription']=$secrow['productdescription'];
					$outt['mrp']=$secrow['mrp'];
					$outt['fprice']=$secrow['fprice'];
					$outt['rprice']=$secrow['rprice'];
					$outt['iprice']=$secrow['iprice'];
					
					$outt['Status']="0";
					$outt['InsertDate']=date("Y/m/d");
					$outt['Deliverydae']=date("Y/m/d");
					$tablename='pricelistlinkinggrid';
					$news->addNews($outt,$tablename); 
					$posting++;
					}
				}
			}
		}
		else
		{
			header('Location:pricelistlinking.php');
		}
	}
	else
	{
	$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."'");
	$count = mysql_num_rows($row);
		if($count>0)
		{
			while( $myrow = mysql_fetch_array($row))
			{
			$outa['PriceListCode']=$post['PriceListCode'];
			//$outa['pricelistname']=$post['pricelistname'];
			$outa['Country']=$post['Country'];
			//$outa['Region']='';
			$outa['State']=$myrow['State'];
			$outa['Branch']=$myrow['Branch'];
			$outa['Franchisee']=$myrow['Franchisecode'];
			$result="SELECT * FROM pricelistlinking where PriceListCode ='".$post['PriceListCode']."' AND Franchisee ='".$outa['Franchisee']."'";
			$sql1 = mysql_query($result) or die (mysql_error());
			$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
				if($myrow1>0)
				{
				?>
				<script type="text/javascript">
				alert("Duplicate entry!",'pricelistlinking.php');//document.location='pricelistlinking.php';
				</script>
				<?
				}
				else
				{
					
				$outa['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$outa['m_date']= date("y/m/d : H:i:s", time());
				$news->addNews($outa,$tname);
				$row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
					while( $secrow = mysql_fetch_array($row2))
					{
					$outt['PriceListCode']=$post['PriceListCode'];
					$outt['pricelistname']=$post['pricelistname'];
					$outt['Country']=$post['Country'];
					//$outt['Region']='';
					$outt['State']=$myrow['State'];;
					$outt['Branch']=$myrow['Branch'];;
					$outt['Franchisee']=$outa['Franchisee'];
					$outt['effectivedate']=$secrow['effectivedate'];
					$outt['applicabledate']=$secrow['applicabledate'];
					$outt['productcode']=$secrow['productcode'];
					//$outt['productdescription']=$secrow['productdescription'];
					$outt['mrp']=$secrow['mrp'];
					$outt['fprice']=$secrow['fprice'];
					$outt['rprice']=$secrow['rprice'];
					$outt['iprice']=$secrow['iprice'];
					
					$outt['Status']="0";
					$outt['InsertDate']=date("Y/m/d");
					$outt['Deliverydae']=date("Y/m/d");
					$tablename='pricelistlinkinggrid';
					$news->addNews($outt,$tablename); 
					$posting++;
					}
				}
			}
		}
	}			

	if($posting>0)
	{
	?>
	<script type="text/javascript">
	alert("Created Sucessfully!",'pricelistlinking.php');
	</script>
	<?
	}
	/*}*/
	}
	else
	{
	?>
	<script type="text/javascript">
	alert("Enter all the Mandatory Fields!");//document.location='pricelistlinking.php';
	</script>
	<?
	}
}
		
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$post['PriceListCode'] = $_POST['PriceListCode'];
		$post['pricelistname'] = $_POST['pricelistname'];
		$countrysmallCode=mysql_query("SELECT * FROM countrymaster where countryname='".$_POST['Country']."'");
		$countrysmallfetch=mysql_fetch_array($countrysmallCode);
		
		$post['Country'] = $countrysmallfetch['countrycode'];
		// $post['Country'] 		= trim($_POST['Country']);
		//$post['Region'] = trim($_POST['Region']);
		$statesmallCode=mysql_query("SELECT * FROM state where statename='".$_POST['State']."'");
		$statesmallfetch=mysql_fetch_array($statesmallCode);
		$post['State'] = $statesmallfetch['statecode'];
		//$post['State'] 	 = trim($_POST['State']);
		
		$branchsmallCode=mysql_query("SELECT * FROM branch where branchname='".$_POST['Branch']."'");
		$branchsmallfetch=mysql_fetch_array($branchsmallCode);
		$post['Branch'] = $branchsmallfetch['branchcode'];
		$post['Franchisecode'] = $_POST['Franchisee'];
		
				$post['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$post['m_date']= date("y/m/d : H:i:s", time());
		
				$outa['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$outa['m_date']= date("y/m/d : H:i:s", time());
        if(!empty($_POST['PriceListCode'])&&!empty($_POST['pricelistname'])&&!empty($_POST['Country']))
		{  
		
		$result="SELECT * FROM pricelistlinking where PriceListCode ='".$post['PriceListCode']."' AND Franchisee ='".$post['Franchisecode']."'";
	
		
		 $sql1 = mysql_query($result) or die (mysql_error());
 
		
		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
		if($myrow1==0)
		{
		?>
            <script type="text/javascript">
			alert("You are not allowed to save a new record using update!");//document.location='pricelistlinking.php';
			</script>
        <?
		}
		else
		{
        // This will make sure its displayed
        $posting=0;
		if(!empty($_POST['PriceListCode'])&&!empty($_POST['pricelistname'])&&!empty($_POST['Country'])&&!empty($_POST['Franchisee']))
		{ 
	
			if(!empty($_POST['State'])&&!empty($_POST['Branch'])&&!empty($_POST['Franchisee']))
			{
				//echo "select * from franchisemaster where country = '".$post['Country']."' and Branch='".$post['Branch']."' and State='".$post['State']."' and Region='".$post['Region']."' and Franchisecode='".$post['Franchisecode']."'";
				$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."' and Branch='".$post['Branch']."' and State='".$post['State']."' and Franchisecode='".$post['Franchisecode']."'");
					$count = mysql_num_rows($row);
					if($count>0)
				{
					$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
					$news->deleteNews($tname,$wherecon);
					$tablename='pricelistlinkinggrid';
					$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
//						  $outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									 // $outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									 // $outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
				
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
				 
			}
			elseif(!empty($_POST['State'])&&!empty($_POST['Branch']))
			{
				$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."' and Branch='".$post['Branch']."' and State='".$post['State']."'");
				$count = mysql_num_rows($row);
					if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									  //$outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
				
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
			}
			elseif(!empty($_POST['Branch'])&&!empty($_POST['State'])&&!empty($_POST['Franchisee']))
			{
				
			
			
				$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."' and Franchisecode='".$post['Franchisecode']."' and Branch='".$post['Branch']."' and State='".$post['State']."'");
							$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									  //$outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
				
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
			}
			elseif(!empty($_POST['State'])&&!empty($_POST['Franchisee']))
			{
				$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."' and Franchisecode='".$post['Franchisecode']."' and State='".$post['State']."'");
							$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									 // $outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									 // $outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
			}
			elseif(!empty($_POST['Branch'])&&!empty($_POST['Franchisee']))
			{
			
			$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."' and Franchisecode='".$post['Franchisecode']."' and Branch='".$post['Branch']."'");
						$count = mysql_num_rows($row);
					if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									//  $outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
				 
			}
				elseif(!empty($_POST['State'])&&!empty($_POST['Branch'])&&!empty($_POST['Franchisee']))
			{
			
				$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."' and Franchisecode='".$post['Franchisecode']."' and Branch='".$post['Branch']."' and State='".$post['State']."'");
							$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									  //$outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
			}
			elseif(!empty($_POST['State'])&&!empty($_POST['Branch']))
			{
			
				
				$row =mysql_query( "select * from franchisemaster where Country = '".$post['Country']."' and State='".$post['State']."'  and Branch='".$post['Branch']."'");
							$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						 // $outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									  //$outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
			}
			elseif(!empty($_POST['State']))
			{
			
				$row =mysql_query( "select * from franchisemaster where Country = '".$post['Country']."' and State='".$post['State']."'");
							$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						 // $outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']='';
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']='';
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									 // $outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
			}
			elseif(!empty($_POST['Branch ']))
			{
			
				$row =mysql_query( "select * from franchisemaster where Country = '".$post['Country']."' and Branch='".$post['Branch']."'");
				$count = mysql_num_rows($row);
				if($count>0)
				{
				$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
				$news->deleteNews($tname,$wherecon);
				$tablename='pricelistlinkinggrid';
				$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									 // $outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									  //$outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
			}
			elseif(!empty($_POST['Franchisee']))
			{
			
				$row =mysql_query( "select * from franchisemaster where Country = '".$post['Country']."' and Franchisecode='".$post['Franchisecode']."'");
							$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									  //$outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
				
			}
			elseif(!empty($_POST['Franchisee'])&&!empty($_POST['State']))
			{
				
				$row =mysql_query( "select * from franchisemaster where Country = '".$post['Country']."' and State='".$post['State']."'  and Franchisecode='".$post['Franchisecode']."'");
						$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									 // $outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
				
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
			}
			
			elseif(!empty($_POST['Franchisee'])&&!empty($_POST['Branch']))
			{
				
				$row =mysql_query( "select * from franchisemaster where Country = '".$post['Country']."' and Branch='".$post['Branch']."'  and Franchisecode='".$post['Franchisecode']."'");
						$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									 // $outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
				
			}
			elseif(!empty($_POST['Branch']))
			{
				
				$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."'  and Branch='".$post['Branch']."'");
						$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									 // $outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									 // $outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
				 
			}
			elseif(!empty($_POST['Franchisee']))
			{
				
				$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."' and Franchisecode='".$post['Franchisecode']."' ");
							$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']=$myrow['Branch'];
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']=$outa['Branch'];
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									 // $outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
			}
			
			
			
					elseif(!empty($_POST['State']))
			{
			
				$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."'  and State='".$post['State']."'");
							$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						 // $outa['Region']=$myrow['Region'];
						  $outa['State']=$myrow['State'];
						  $outa['Branch']='';
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									  //$outt['Region']=$outa['Region'];
									  $outt['State']=$outa['State'];
									  $outt['Branch']='';
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									  //$outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $outt['Status']="1";
									  $outt['InsertDate']=date("Y/m/d");
									  $outt['Deliverydae']=date("Y/m/d");
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
				 
			}
			//elseif(!empty($_POST['Region']))
//			{
//				
//				$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."'  and Region='".$post['Region']."'");
//							$count = mysql_num_rows($row);
//						if($count>0)
//				{
//		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
//		$news->deleteNews($tname,$wherecon);
//		$tablename='pricelistlinkinggrid';
//		$news->deleteNews($tablename,$wherecon);
//					 while( $myrow = mysql_fetch_array($row))
//					 {
//						  $outa['PriceListCode']=$post['PriceListCode'];
//						  $outa['pricelistname']=$post['pricelistname'];
//						  $outa['Country']=$post['Country'];
//						  $outa['Region']=$myrow['Region'];
//						  $outa['State']='';
//						  $outa['Branch']='';
//						  $outa['Franchisee']=$myrow['Franchisecode'];
//						  $i=0;
//						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
//						   while( $secrow = mysql_fetch_array($row2))
//							 {
//									  $outt['PriceListCode']=$outa['PriceListCode'];
//									  $outt['pricelistname']=$outa['pricelistname'];
//									  $outt['Country']=$outa['Country'];
//									  $outt['Region']=$outa['Region'];
//									  $outt['State']='';
//									  $outt['Branch']='';
//									  $outt['Franchisee']=$outa['Franchisee'];
//									  $outt['effectivedate']=$secrow['effectivedate'];
//									  $outt['applicabledate']=$secrow['applicabledate'];
//									  $outt['productcode']=$secrow['productcode'];
//									  $outt['productdescription']=$secrow['productdescription'];
//									  $outt['mrp']=$secrow['mrp'];
//									  $outt['fprice']=$secrow['fprice'];
//									  $outt['rprice']=$secrow['rprice'];
//									  $outt['iprice']=$secrow['iprice'];
//									  
//									  $outt['Status']="1";
//									  $outt['InsertDate']=date("Y/m/d");
//									  $outt['Deliverydae']=date("Y/m/d");
//									  
//									  $tablename='pricelistlinkinggrid';
//									  $news->addNews($outt,$tablename);
//									  $i++; 
//							 }
//							 if($i!=0)
//							 {
//						   $news->addNews($outa,$tname);
//							 }
//							 
//					 }
//		}
		
		else
			{
				
				$row =mysql_query( "select * from franchisemaster where country = '".$post['Country']."'");
						$count = mysql_num_rows($row);
						if($count>0)
				{
		$wherecon= "PriceListCode ='".$post['PriceListCode']."'";
		$news->deleteNews($tname,$wherecon);
		$tablename='pricelistlinkinggrid';
		$news->deleteNews($tablename,$wherecon);
					 while( $myrow = mysql_fetch_array($row))
					 {
						  $outa['PriceListCode']=$post['PriceListCode'];
						  $outa['pricelistname']=$post['pricelistname'];
						  $outa['Country']=$post['Country'];
						  //$outa['Region']='';
						  $outa['State']='';
						  $outa['Branch']='';
						  $outa['Franchisee']=$myrow['Franchisecode'];
						  $i=0;
						  $row2 =mysql_query( "select * from pricelistmaster where pricelistcode = '".$post['PriceListCode']."'");
						   while( $secrow = mysql_fetch_array($row2))
							 {
									  $outt['PriceListCode']=$outa['PriceListCode'];
									  $outt['pricelistname']=$outa['pricelistname'];
									  $outt['Country']=$outa['Country'];
									 // $outt['Region']='';
									  $outt['State']='';
									  $outt['Branch']='';
									  $outt['Franchisee']=$outa['Franchisee'];
									  $outt['effectivedate']=$secrow['effectivedate'];
									  $outt['applicabledate']=$secrow['applicabledate'];
									  $outt['productcode']=$secrow['productcode'];
									 // $outt['productdescription']=$secrow['productdescription'];
									  $outt['mrp']=$secrow['mrp'];
									  $outt['fprice']=$secrow['fprice'];
									  $outt['rprice']=$secrow['rprice'];
									  $outt['iprice']=$secrow['iprice'];
									  
									  $tablename='pricelistlinkinggrid';
									  $news->addNews($outt,$tablename);
									  $i++; 
							 }
							 if($i!=0)
							 {
						   $news->addNews($outa,$tname);
							 }
							 
					 }
		}
		else
		{
			?>
           <script type="text/javascript">
			alert("Invalid Franchise Information!!");//document.location='pricelistlinking.php';
			</script>
            <?
            $posting++;
		}
			}
            if($posting==0)
            {
		?>
           <script type="text/javascript">
			alert("Updated Sucessfully!",'pricelistlinking.php');
			//setInterval(function(){document.location='pricelistlinking.php';},2000);
			//document.location='pricelistlinking.php';
			</script>
         <?
            }
		}
		}
		}
		else
		{
			if(!empty($_POST['PriceListCode'])&&!empty($_POST['pricelistname'])&&!empty($_POST['Country']))
			{  
			
			$result="SELECT * FROM pricelistlinking where PriceListCode ='".$post['PriceListCode']."' AND pricelistname ='".$post['pricelistname']."'";
		
			
			 $sql1 = mysql_query($result) or die (mysql_error());
	 
			
			$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
				if($myrow1==0)
				{
				?>
					<script type="text/javascript">
					alert("Invalid Code to Update!");//document.location='pricelistlinking.php';
					</script>
				<?
				}
			}
			else
			{
			?>
           <script type="text/javascript">
			alert("Enter all the Mandatory Fields!");//document.location='pricelistlinking.php';
			</script>
            <?
			}
		}
		}
	
/// EDIT LINK FUNCTION 
if(!empty($_GET['PriceListCode']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
 $prmaster  = $_GET['PriceListCode'];
  $Franchisee  = $_GET['Franchisee'];
$result=mysql_query("SELECT * FROM pricelistlinking where PriceListCode ='".$prmaster."' AND Franchisee ='".$Franchisee."'");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'pricelistlinking.php');//document.location='pricelistlinking.php';
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		   $PriceListCode = $myrow['PriceListCode'];
		   $pricelistname = $myrow['pricelistname'];
		    $countrypgg= mysql_query("select countryname from countrymaster where countrycode='".$myrow['Country']."' ")  ;
		$countryrecord11 = mysql_fetch_array($countrypgg);
        $Country = $countryrecord11['countryname'];
		  // $Country = $myrow['Country'];
		   //$Region = $myrow['Region'];
		   $Statepgg= mysql_query("select statename from state where statecode='".$myrow['State']."' ")  ;
		$Staterecord11 = mysql_fetch_array($Statepgg);
        $State = $Staterecord11['statename'];
		  // $State = $myrow['State'];
		   $branchpgg= mysql_query("select branchname from branch where branchcode='".$myrow['Branch']."' ")  ;
		$branchrecord11 = mysql_fetch_array($branchpgg);
        $Branch = $branchrecord11['branchname'];
		  // $Branch = $myrow['Branch'];
		   $Franchisee = $myrow['Franchisee'];
		   $disable = "true";
		}
		$prmaster = NULL;
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
			alert("Select data to delete!",'pricelistlinking.php');//document.location='pricelistlinking.php';
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
		  $newvar=explode("~",$prodidd);
		  $var1=$newvar[0];
		  $var2=$newvar[1];
		//$prodidd = $checkbox[$i];
		///$prodid= $_POST['checkbox'];
		$statuschqry = mysql_query("SELECT Status FROM pricelistlinkinggrid where PriceListCode='".$var1."' AND Franchisee='".$var2."' and Status!='0'");
		$statuschqryre=mysql_num_rows($statuschqry);
		if($statuschqryre==0)
					{
					$wherecon1= "PriceListCode='".$var1."' AND Franchisee='".$var2."'";		
					$tablename='pricelistlinkinggrid';
					$news->deleteNews($tablename,$wherecon1);
					
					$wherecon= "PriceListCode ='".$var1."' AND Franchisee='".$var2."'";
					$news->deleteNews($tname,$wherecon);
					?>	<script type="text/javascript">	alert("Deleted  Successfully!!",'pricelistlinking.php');</script> <?	
					}
					else
					{
					?>
					<script type="text/javascript">
					alert("You Can't delete already send to Franchisee ",'pricelistlinking.php');
					</script>
					<?		
					}
		}
		}
}


if(isset($_POST['PDF']))
{

	
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM pricelistlinkinggrid WHERE PriceListCode like'".$_POST['codes']."%' AND pricelistname like'".$_POST['names']."%'  order by id desc";$pricelistlinkinggrid=$condition;
		
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM pricelistlinkinggrid WHERE PriceListCode like'".$_POST['codes']."%'  order by id desc";
		$pricelistlinkinggrid=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM pricelistlinkinggrid WHERE pricelistname like'".$_POST['names']."%'  order by id desc";
		$pricelistlinkinggrid=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM pricelistlinkinggrid  order by id desc";$pricelistlinkinggrid=$condition;
	}
	
$select=$_POST['Type'];
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$pricelistlinkinggrid;
	
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($pricelistlinkinggrid);
	while( $myrecord = mysql_fetch_array($myquery))
   {
	   $plnameselct="SELECT pricelistname FROM masterpricelist where PriceListCode='".$myrecord['PriceListCode']."'";
	   $plnameselct1 = mysql_query($plnameselct);
	   $cntno114=mysql_num_rows($plnameselct1);
	   if($cntno114==1)
   		{
		   	$plnameselct12 = mysql_fetch_array($plnameselct1);
			$plnametempp=$plnameselct12['pricelistname'];
	   }
	    else
	   {
		   $plnametempp ="";
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
	   $productgroupselct="SELECT ProductDescription FROM productmaster where ProductCode='".$myrecord['productcode']."'";
	   $productgroupselct1 = mysql_query($productgroupselct);
	   $productcntno1=mysql_num_rows($productgroupselct1);
	   if($productcntno1==1)
   		{
		   	$productgroupselct12 = mysql_fetch_array($productgroupselct1);
			$producttesttempp=$productgroupselct12['ProductDescription'];
	   }
	    else
	   {
		   $producttesttempp ="";
	   }
	    $stringData =$myrecord[0]."\t ;".$plnametempp."\t ;".$myrecord[5]."\t ;".$countrytempp."\t ;".$statetempp."\t ;".$testtempp."\t ;".date("d/m/Y",strtotime($myrecord[6]))."\t ;".date("d/m/Y",strtotime($myrecord[7]))."\t ;".$myrecord[8]."\t ;".$producttesttempp."\t ;".$myrecord[9]."\t ;".$myrecord[10]."\t ;".$myrecord[11]."\t ;".$myrecord[12]."\t ;\n";
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportPriceLink.php');
}
if($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$pricelistlinkinggrid;
	header('Location:ExportPriceLink.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$pricelistlinkinggrid;
	header('Location:ExportPriceLink.php');
}
}?>
<?

if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:pricelistlinking.php');
}?>
   
<script type="text/javascript">
 function numericFilter(txb){
	 if(txb.value.match(/[^\0-9]/ig)){
	 txb.value = txb.value.replace(/[^\0-9]/ig,"");
	 alert(" Enter only Numbers!!!");
	 }
 }

<!--
/*function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=600,height=255,scrollbars=yes, resizable=0,fullscreen=no,location=no,menubar=no');
return false;
}*/


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


function nospaces(t){
if(t.value.match(/\s/g)){
alert('Sorry, you are not allowed to enter any spaces');
t.value=t.value.replace(/\s/g,'');
}
}

	function selectvalue()
{
	var e = document.getElementById("PriceListCode"); 
		var er=e.options[e.selectedIndex].value;
		var ddlArray= new Array();
		var ddl = document.getElementById('pricelistnamelist');
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
			
			document.getElementById("pricelistname").value=tt;
		}
}


function selvalue()
{
	var e = document.getElementById("Franchisees"); 
	var er=e.options[e.selectedIndex].value;
	var ddlArray= new Array();
	var ddl = document.getElementById('citystatelist');
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
		
		document.getElementById("State").value=tt;
		document.getElementById("Branch").value=tt2;
	}

}

</script>
<title><?php echo $_SESSION['title']; ?> || Price List Linking</title>
</head>
<?php 
 if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
if(!empty($_GET['PriceListCode'])){?>
 
 <body class="default" onLoad="document.form1.Country.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.PriceListCode.focus()">

 <? } 
}else{?>
<body class="default" onLoad="document.form1.codes.focus()">

 <? } ?>
 <center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->

<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" id="frm1" name="form1">
             <table style="display:none;">
<tr>
<td>
 <select name="pricelistnamelist" id="pricelistnamelist"  >
                                  <?php
								  $result=mysql_query("SELECT pricelistcode,pricelistname FROM masterpricelist order by m_date desc");
									while($myrow1price = mysql_fetch_array($result))
									{
										 echo "<option value=\"".$myrow1price['pricelistcode']."~".$myrow1price['pricelistname']."\">".$myrow1price['pricelistcode']."~".$myrow1price['pricelistname']."\n ";
									}
								  ?>
                                  </select>
</td>
<td>
                                    <select  name="citystatelist" id="citystatelist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT Franchisecode,statename,branchname FROM `view_rbrs`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  echo "<option value=\"".$record['Franchisecode']."~".$record['statename']."~".$record['branchname']."\">".$record['Franchisecode']."~".$record['statename']."~".$record['branchname']."\n "; 
									  }
                              		 
                                    ?>
                                          </select>
                                      </td>
</tr>
</table>
        
             
             
            <div style="width:930px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Pricelist Linking Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:140px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Price List Code</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                <?php      if(!empty($_GET['PriceListCode']))
					{?>
                               <input type="text" name="PriceListCode" value="<?php echo $PriceListCode;?>" readonly style="border-style:hidden; background:#f5f3f1;" id="PriceListCode" />
                                  
                                  
                                <? }
					else
					{?>
						  <select name="PriceListCode" id="PriceListCode" onChange="selectvalue();" >
                                   <option value="<?php echo $PriceListCode; ?>"><? if(!empty($PriceListCode)){ echo $PriceListCode;}else{?> ----Select---- <? } ?></option>
                                  <?php
								  $result=mysql_query("SELECT pricelistcode FROM masterpricelist order by pricelistcode asc");
									while($myrow1price = mysql_fetch_array($result))
									{
									 if($PriceListCode!=$myrow1price['pricelistcode'])
									 {
										echo "<option value=\"".$myrow1price['pricelistcode']."\">".$myrow1price['pricelistcode']."</option>";
									 }
									}
								  ?>
                                  </select>
				          <? } ?>  
                                  
                               </div>
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                            <div style="width:140px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Price List Name</label><!--<label style="color:#F00;">*</label>-->
                               </div>
                               <div style="width:200px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                    <input type="text" name="pricelistname" value="<?php echo $pricelistname;?>" readonly style="border-style:hidden; background:#f5f3f1;" id="pricelistname" />
        
                               </div>
                             <!--Row2 end--> 
                               <!--Row 4-->  
                               <div style="width:140px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Country</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                   <select name="Country">
                                    <option value="<?php echo $Country; ?>"><? if(!empty($Country)){ echo $Country;}else{?> ----Select---- <? } ?></option>
                                     	 <?php
								  $result=mysql_query("SELECT countryname FROM countrymaster order by countryname asc");
									while($myrow1price = mysql_fetch_array($result))
									{
									 if($Country!=$myrow1price['countryname'])
									 {
										echo "<option value=\"".$myrow1price['countryname']."\">".$myrow1price['countryname']."</option>";
									 }
									}
								  ?>
                                   </select>
                               </div>
                             <!--Row4 end-->
                              <!---Row 5-->
                           
                           <!--Row 5 end--> 
                               
                           </div>                             
                             <!-- col 1 end --> 
                             <!-- col2 -->   
                               <div style="width:400px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                               <!--Row 1-->
                                  <div style="width:140px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                      <label>Distributor</label>
                                   </div>
                                   <div style="width:200px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                  
                                 <!-- getagent();-->
                                    <select name="Franchisee" id="Franchisees" onChange="selvalue();">
                                    <option value="<?php echo $Franchisee; ?>"><? if(!empty($Franchisee)){ echo $Franchisee;}else{?> ----Select---- <? } ?></option>
                                  <?php
								  $result=mysql_query("SELECT DISTINCT (Franchisecode) FROM franchisemaster order by Franchisecode asc");
									while($myrow1price = mysql_fetch_array($result))
									{
									 if($Franchisee!=$myrow1price['Franchisecode'])
									 {	
										echo "<option value=\"".$myrow1price['Franchisecode']."\">".$myrow1price['Franchisecode']."</option>";
									 }
									}
								  ?>
                                  </select>
                                   
                                   </div>   
                       
 							<!--Row 1 end--> 
                            <!--Row 2-->  
                             <div style="width:140px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label>State</label>
                               </div>
                               <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                     <select name="State" id="State">
                                            <option value="<?php echo $State; ?>"><? if(!empty($State)){ echo $State;}else{?> ----Select---- <? } ?></option>
                                             <?php
                                      $result=mysql_query("SELECT statename FROM state order by statename asc");
                                        while($myrow1price = mysql_fetch_array($result))
                                        {
										 if($State!=$myrow1price['statename'])
									 	 {	
                                            echo "<option value=\"".$myrow1price['statename']."\">".$myrow1price['statename']."</option>";
										 }
                                        }
                                      ?>
                                       </select>
									
                                  </div>
                               <!--Row 2 end-->
                                <!-- Row 3 -->
                                 <div style="width:140px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                    <label>Branch</label>
                                 </div>
                               <div style="width:200px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                  
                                 <select name="Branch" id="Branch">
                                    <option value="<?php echo $Branch; ?>"><? if(!empty($Branch)){ echo $Branch;}else{?> ----Select---- <? } ?></option>
                                     	 <?php
								  $result=mysql_query("SELECT branchname FROM branch order by branchname asc");
									while($myrow1price = mysql_fetch_array($result))
									{
									 if($Branch!=$myrow1price['branchname'])
									 {		
										echo "<option value=\"".$myrow1price['branchname']."\">".$myrow1price['branchname']."</option>";
									 }
									}
								  ?>
                                    
                                   </select>
                               </div>
                           </div>  
                                                                 
                           <!-- Row 3 end -->
                             
                             
                               
                                                   
                                                         
                     <!-- col2 end--> 
                              <div style="width:100px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                                          
                               </div>
                  
                
				</div>
                <!-- main row 1 end-->
         
                
               <!--Main row 2 start-->
                <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:8px;">
                             
					<div style="width:225px; height:50px; float:left;  margin-left:1px; margin-top:0px;" id="center1">
						   
                           <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                    <?php      if(!empty($_GET['PriceListCode']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" >
				          <? } ?>
				           </div>
                           
                           <div style="width:80px; height:32px; float:left;margin-top:16px; margin-left:10px;">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div>
                           
                               
                                                   
				     </div>	
                         
                          <div style="width:640px; height:50px; float:left;  margin-left:15px; margin-top:0px;" class="cont" id="center2">
                               <!--Row1 -->  
                               <div style="width:100px; height:30px; float:left; margin-left:3px; margin-top:14px;" >
                                     <label>Price list Code</label>
                               </div>
                               <div style="width:135px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                     <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                               <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:100px; height:30px; float:left; margin-left:15px; margin-top:14px;">
                                     <label>Distributor Code</label>
                               </div>
                               <div style="width:135px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                     <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
                               </div>
                               <!--Row2 end-->
                             
                               <div style="width:83px; height:32px; float:left;  margin-left:15px; margin-top:16px;">
                                     <input type="submit" name="Search" id="Search" value="Search" class="button"/>
                               </div>
                                 
                          </div> 
                </div>
        <div style="width:900px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">         
                 <table id="datatable1" align="center" bgcolor="#00FF99" class="sortable" border="1" width="900px" style="overflow:auto; " >   
                   <tr >                    
     <?  if(($row['deleterights'])=='Yes')
	 {
	?>    
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="8px">
     <input type='checkbox' id="checkall" name='checkall' onclick='checkedAll(frm1);' ></td>
       <? 
   }
     /*?>if(($row['editrights'])=='Yes') 
	  { 
	 ?>
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="12px">Action</td>
      <? 
	  } 
	  ?><?php */?>
   <td style=" font-weight:bold;">PriceListCode</td>
   <td style=" font-weight:bold;">PriceListName</td>
   
   <td style=" font-weight:bold;">Country</td>
   
   <td style=" font-weight:bold;" >State</td>
   <td style=" font-weight:bold;">Branch</td>
   <td style=" font-weight:bold;">Distributor</td>
   <td style=" font-weight:bold;">View</td></tr>
 <!--  <td style=" font-weight:bold;">EffectiveDate</td>
  <td style=" font-weight:bold;">ApplicableTill</td>
  <td style=" font-weight:bold;">ProductCode</td>
  <td style=" font-weight:bold;">ProductDescription</td>
	<td style=" font-weight:bold;">MRP</td>
    <td style=" font-weight:bold;">FranchisePrice</td>
  <td style=" font-weight:bold;">RetailerPrice</td>
	<td style=" font-weight:bold;">InstitutionalPrice</td>-->
   
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($query))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
    ?>
    
     <tr>
      <?  if(($row['deleterights'])=='Yes')
	 {
	?> 
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" id="checkbox[]" onChange="test();" value="<? echo $record['PriceListCode']."~".$record['Franchisee']; ?> "></td>
	    <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF"> <?=$record['PriceListCode']?> </td>
    <td  bgcolor="#FFFFFF"><? $checkname= mysql_query("select pricelistname from masterpricelist where pricelistcode='".$record['PriceListCode']."'");
		$checkrecordn = mysql_fetch_array($checkname);
       echo $checkrecordn['pricelistname'];  ?>  </td>
    
    <td  bgcolor="#FFFFFF"><? $check= mysql_query("select countryname from countrymaster where countrycode='".$record['Country']."' ")  ;
		$checkrecord = mysql_fetch_array($check);
       echo $checkrecord['countryname'];  ?>  </td>
    
    <td  bgcolor="#FFFFFF"> <?  $check2= mysql_query("select statename from state where statecode='".$record['State']."' ")  ;
		$check2record = mysql_fetch_array($check2);
       echo $check2record['statename']; ?>   </td>
   <td  bgcolor="#FFFFFF"> <? $check3= mysql_query("select branchname from branch where branchcode='".$record['Branch']."' ")  ;
		$check3record = mysql_fetch_array($check3);
       echo $check3record['branchname']; ?>  </td>
   <td  bgcolor="#FFFFFF"> <?=$record['Franchisee']?> </td>
 <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><a style="color:#0360B2" HREF="view" onClick="return popup('pricelistlinkinggrid.php?<? echo 'pricelistcode='; echo $record['PriceListCode']; echo '&fnchisee='; echo $record['Franchisee'];?>')">View</a></td>
  
   </tr>
  <?php
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
<?php include("../../paginationdesign.php")?>
             <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
                               <div style="width:70px; height:32px; float:left; margin-left:25px; margin-top:20px;">
                              Export As
             				
                               </div> 
                               <div style="width:83px; height:32px; float:left; margin-left:5px; margin-top:12px;">
                                <select name="Type">
                                  <option value="PDF">PDF</option>
                                   <option value="Excel">Excel</option>
                                     <option value="Document">Document</option>
                                                                   </select>
             				
                               </div>  
                               <div style="width:63px; height:32px; float:right; margin-top:18px;">
             					  <input type="submit" name="PDF" value="Export" class="button"/>
                               </div ></div>
</div>

</form>
  <!--Main row 2 end-->
                
             <!-- form id start end-->      
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