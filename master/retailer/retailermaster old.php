<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
  if (!isset($_SESSION['pagecount']))
		{
	 	$limit = 10;
		}
		else
	  	{
	    $limit =$_SESSION['pagecount'] ;
	 	}
    $tname	= "retailermaster";
	$stname="retailermasterupload";
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);

global $RetailerCode,$RetailerName,$Address,$City,$Districtname,$fmexecutive,$Category,$ContactName,$ContactNo,$CreditDays,$CreditLimit,$TinNo,$TinDate,$checkbox,$flag,$tname,$search1,$search2,$Bankname,$Branchname,$IFSCcode,$Accountholder;
global $AccNo; 

		$pagename = "Retailer";
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
			alert("you are not allowed to do this action!",'retailermaster.php');
			//setInterval(function(){document.location='retailermaster.php';},2000);
			//document.location='retailermaster.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'retailermaster.php');
			//setInterval(function(){document.location='retailermaster.php';},2000);
			//document.location='retailermaster.php';	
			</script>
         <?
		
	}



if(isset($_POST['Save']))
{
	$post['RetailerCode']  =str_replace('&', 'and',$_POST['RetailerCode']);
	 $post['RetailerName'] =str_replace('&', 'and',$_POST['RetailerName']);
	 $post['Address']=$_POST['Address'];
	 $post['City']=$_POST['City'];
	 $post['Districtname'] =$_POST['Districtname'];
	$post['fmexecutive'] =$_POST['fmexecutive'];
	 //$post['Category'] =($_POST['Category']);
	  $smallCode=mysql_query("SELECT * FROM retailercategory where RetailerCategory='".$_POST['Category']."'");
			$smallfetch=mysql_fetch_array($smallCode);
			
			$post['Category'] = $smallfetch['CategoryCode'];
	 $post['ContactName'] =$_POST['ContactName'];
	 $post['ContactNo'] =$_POST['ContactNo'];
	 $post['CreditDays'] =$_POST['CreditDays'];
	 $post['CreditLimit'] =$_POST['CreditLimit'];
	 $post['TinNo'] =$_POST['TinNo'];
	 echo $_POST['TinDate'];
	 if($_POST['TinDate']!="" && $_POST['TinDate']!="00/00/0000" && $_POST['TinDate']!="0000-00-00" )
		{
			$test = $news->dateformat($_POST['TinDate']);
			$TinDate=$_POST['TinDate'];//$test;
		}
		
		else
		{
			$test="00/00/0000";
			$TinDate='00/00/0000';
		}

		  $post['TinDate'] =$test;
	 
	 $post['bankname']=$_POST['Bankname'];	
	 $Bankname= $_POST['Bankname'];	
	 $post['branchname']=$_POST['Branchname'];
	 $Branchname=$_POST['Branchname'];
	 $post['ifsccode']=$_POST['IFSCcode'];
	 $IFSCcode=$_POST['IFSCcode'];
	 $post['accountholdersname']=$_POST['Accountholder'];
	 $Accountholder=$_POST['Accountholder'];
	 $post['accno'] = $_POST['AccNo'];
	 $AccNo = $_POST['AccNo'];
	 //Bankname,Branchname,IFSCcode,Accountholder
	
	 $RetailerCode =($_POST['RetailerCode']);
	 $RetailerName =($_POST['RetailerName']);
	 $Address =($_POST['Address']);
	 $City=($_POST['City']);
	 $Districtname =($_POST['Districtname']);
	 $fmexecutive =($_POST['fmexecutive']);
	 $Category =($_POST['Category']);
	 $ContactName =($_POST['ContactName']);
	 $ContactNo =($_POST['ContactNo']);
	 $CreditDays =($_POST['CreditDays']);
	 $CreditLimit =($_POST['CreditLimit']);
	 $TinNo =($_POST['TinNo']);
	 
	 if(!empty($_POST['RetailerCode'])&&!empty($_POST['RetailerName'])&&!empty($_POST['Address'])&&!empty($_POST['City'])&&!empty($_POST['Districtname'])&&!empty($_POST['fmexecutive'])&&!empty($_POST['Category'])&&!empty($_POST['ContactNo'])&&!empty($_POST['ContactName']))
	 {
	            $result="SELECT * FROM retailermaster where RetailerCode ='".$post['RetailerCode']."'";
	
		
		 $sql1 = mysql_query($result) or die (mysql_error());
 
		
		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
		if($myrow1>0)
		{
		?>
            <script type="text/javascript">
			alert("Duplicate entry!");//document.location='retailermaster.php';
			</script>
         <?
		}
	    else
		{
	 	$post['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$post['m_date']= date("y/m/d : H:i:s", time());
				
		 $news->addNews($post,$tname);
		 
		$spost['Code']  =$post['RetailerCode'];
		$spost['Masters']="retailermaster";
		$spost['Status']="0";
		$spost['InsertDate']=date("Y/m/d");
		$spost['Deliverydae']=date("Y/m/d");
				
		$franqry= mysql_query("SELECT Franchisecode  FROM  `franchisemaster`") or die (mysql_error());
		while($frqry = mysql_fetch_array($franqry))
		  {
			  $spost['Franchiseecode']=$frqry['Franchisecode'];
			  $news->addNews($spost,$stname);
		  }
		 
			 
		?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'retailermaster.php');
			//setInterval(function(){document.location='retailermaster.php';},2000);
			//document.location='retailermaster.php';
			</script>
            <?
	    }
				
			
	 }
	 else
	 {
		 	?>
			<script type="text/javascript">
            alert("Enter Mandatory Fields!");//document.location='retailermaster.php';
            </script>
            <?
	 }
}



// Check if delete button active, start this 

	if(isset($_POST['Delete']))
{
	if(!isset($_POST['checkbox']))
	{
			?>
		    <script type="text/javascript">
			alert("Select data to delete!",'retailermaster.php');
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
			
		$repqry1="SELECT RetailerCode from retailermaster where RetailerCode in(select RetailerName from serialnumbermaster where RetailerName='".$var1."') ";
		$repres= mysql_query($repqry1) or die (mysql_error());
		$myrow1 = mysql_num_rows($repres);//mysql_fetch_array($retval);

			if($myrow1==0)	
			{
			
				$mkrow = mysql_query("SELECT Status FROM retailermasterupload where Code='".$var1."'");
				$val=mysql_fetch_array($mkrow);
				if($val[0]==0)
				{
				$wherec= "Code='".$var1."' and Status='0'";
				$news->deleteNews($stname,$wherec);		
				
				$wherecon= "RetailerCode ='".$var1."'";
				$news->deleteNews($tname,$wherecon);
				?>
				<script type="text/javascript">
				alert("Deleted  Successfully!",'retailermaster.php');
				//setInterval(function(){document.location='retailermaster.php';},2000);
				</script>
				<?		
				}
				else
				{
				?>
				<script type="text/javascript">
				alert("You Can't delete already send to Franchisee ",'retailermaster.php');
				</script>
				<?	
				}
			}
		else
		{

		?>
            <script type="text/javascript">
			alert("you can't delete already used in other masters!",'retailermaster.php');
			</script>
   			<?
		}
		}
}
}

	   $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	//$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
        $statement = "`retailermaster`"; 
		$startvalue="";
        $query = mysql_query("SELECT * FROM {$statement} order by id desc LIMIT {$startpoint} , {$limit}");
//EDIT
if(!empty($_GET['edi']))
{
$prmaster =$_GET['edi'];

//$cont->connect();
$result=mysql_query("SELECT * FROM retailermaster where RetailerCode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'retailermaster.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		
		   $RetailerCode = $myrow['RetailerCode'];
		   $RetailerName = $myrow['RetailerName'];
		   $Address=$myrow['Address'];
		   $City = $myrow['City'];
		   $Districtname = $myrow['Districtname'];
		   $fmexecutive = $myrow['fmexecutive'];
		   $gg=mysql_query("SELECT * FROM retailercategory where CategoryCode='".$myrow['Category']."'");
			$rec=mysql_fetch_array($gg);
			
			$Category = $rec['RetailerCategory'];
		  // $Category=$myrow['Category'];
		   $ContactName = $myrow['ContactName'];
		   $ContactNo = $myrow['ContactNo'];
		   $CreditDays = $myrow['CreditDays'];
		   $CreditLimit=$myrow['CreditLimit'];
		   $TinNo = $myrow['TinNo'];
		   
		   $Bankname=$myrow['bankname'];
		   $Branchname=$myrow['branchname'];
		   $IFSCcode=$myrow['ifsccode'];
		   $Accountholder=$myrow['accountholdersname'];
		   $AccNo = $myrow['accno'];
		   $TinDate =  date("d/m/Y",strtotime($myrow['TinDate']));
		
		  if($TinDate == '01/01/1970')
		  {
		  	$TinDate="";
		  }
		}
		$prmaster = NULL;
}
if(isset($_POST['Update'])) // If the submit button was clicked
    {
		$post['RetailerCode']  =$_POST['RetailerCode'];
	 $post['RetailerName'] =$_POST['RetailerName'];
	 $post['Address']=$_POST['Address'];
	 $post['City']=$_POST['City'];
	 $post['Districtname'] =$_POST['Districtname'];
	$post['fmexecutive'] =$_POST['fmexecutive'];
	// $post['Category'] =($_POST['Category']);
	 $smallCode=mysql_query("SELECT * FROM retailercategory where RetailerCategory='".$_POST['Category']."'");
			$smallfetch=mysql_fetch_array($smallCode);
			
			$post['Category'] = $smallfetch['CategoryCode'];
	 $post['ContactName'] =$_POST['ContactName'];
	 $post['ContactNo'] =$_POST['ContactNo'];
	 $post['CreditDays'] =$_POST['CreditDays'];
	 $post['CreditLimit'] =$_POST['CreditLimit'];
	 $post['TinNo'] =$_POST['TinNo'];
	 $test = $news->dateformat($_POST['TinDate']);
	 $post['TinDate'] =$test;
	 
	 $post['bankname']=$_POST['Bankname'];	
	 $Bankname= $_POST['Bankname'];	
	 $post['branchname']=$_POST['Branchname'];
	 $Branchname=$_POST['Branchname'];
	 $post['ifsccode']=$_POST['IFSCcode'];
	 $IFSCcode=$_POST['IFSCcode'];
	 $post['accountholdersname']=$_POST['Accountholder'];
	 $Accountholder=$_POST['Accountholder'];
	 $post['accno'] = $_POST['AccNo'];
	 $AccNo = $_POST['AccNo'];	
	 $RetailerCode =($_POST['RetailerCode']);
	 $RetailerName =($_POST['RetailerName']);
	 $Address =($_POST['Address']);
	 $City=($_POST['City']);
	 $Districtname =($_POST['Districtname']);
	 $fmexecutive =($_POST['fmexecutive']);
	 $Category =($_POST['Category']);
	 $ContactName =($_POST['ContactName']);
	 $ContactNo =($_POST['ContactNo']);
	 $CreditDays =($_POST['CreditDays']);
	 $CreditLimit =($_POST['CreditLimit']);
	 $TinNo =($_POST['TinNo']);
	 $TinDate =$_POST['TinDate'];
 
        // This will make sure its displayed
		if(!empty($_POST['RetailerCode'])&&!empty($_POST['RetailerName'])&&!empty($_POST['Address'])&&!empty($_POST['City'])&&!empty($_POST['Districtname'])&&!empty($_POST['fmexecutive'])&&!empty($_POST['Category'])&&!empty($_POST['ContactNo'])&&!empty($_POST['ContactName']))
		{ 
		$result=mysql_query("SELECT * FROM retailermaster where RetailerCode ='".$_POST['RetailerCode']."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("You are not allowed to save a new record using Update!",'retailermaster.php');
			</script>
   			<?
		}
		else
		{
		$post['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$post['m_date']= date("y/m/d : H:i:s", time());
					$wherecon= "RetailerCode ='".$post['RetailerCode']."'";
			$news->editNews($post,$tname,$wherecon);
			
			$mkrow = mysql_query("SELECT Status,Franchiseecode FROM retailermasterupload where Code='".$post['RetailerCode']."'");
			while($val=mysql_fetch_array($mkrow))
			{
					if($val['Status']>0)
					{
						$spost1['Status']=1;
					}
					else
					{
						$spost1['Status']=0;
					}
			$wherecon= "Code='".$post['RetailerCode']."' AND Franchiseecode='".$val['Franchiseecode']."'";
			$news->editNews($spost1,$stname,$wherecon);
			}	
						
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'retailermaster.php');
			//setInterval(function(){document.location='retailermaster.php';},2000);
			</script>
            <?
					
		}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='retailermaster.php';
			</script>
            <?
		}
	}


if(isset($_POST['Search']))
{
if(isset($_POST['codes'])||isset($_POST['names']))
{
	$search1=$_POST['codes'];
	$search2=$_POST['names'];
	
	if(empty($_POST['codes'])&&empty($_POST['names']))
	{
		?>
		    <script type="text/javascript">
			alert("Please enter some search criteria!");
			setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
			</script>
			<?
	}
	else
	{
	if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM retailermaster WHERE RetailerCode like'".$_POST['codes']."%' OR RetailerName like'".
		$_POST['names']."%'";$fffquery=$condition;
		
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM retailermaster WHERE RetailerCode like'".$_POST['codes']."%'";
		$fffquery=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM retailermaster WHERE RetailerName like'".$_POST['names']."%'";
		$fffquery=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM retailermaster WHERE 1";$fffquery=$condition;
	}
	
	$refer=mysql_query($condition);
	$myrow1 = mysql_num_rows($refer);
	//mysql_fetch_array($query);
	
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	$limit = $myrow1+1000;
    	$startpoint = ($page * $limit) - $limit;
        //to make pagination
        $statement = "retailermaster";
		$startvalue=$myrow1;
		 //show records
       $query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
		if($myrow1==0)	
		{
			?>
		    <script type="text/javascript">
			alert("Data not found!");//document.location='retailermaster.php';
			setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
			</script>
			<?
		
		}
	}
}

}

$_SESSION['type']=NULL;
	$fffquery='select * from retailermaster';

if(isset($_POST['PDF']))
{

$select=$_POST['Type'];
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM retailermaster WHERE RetailerCode like'".$_POST['codes']."%' OR RetailerName like'".
		$_POST['names']."%'order by id DESC";$fffquery=$condition;
		
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM retailermaster WHERE RetailerCode like'".$_POST['codes']."%'order by id DESC";
		$fffquery=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM retailermaster WHERE RetailerName like'".$_POST['names']."%'order by id DESC";
		$fffquery=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM retailermaster order by id DESC";$fffquery=$condition;
	}

if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$fffquery;
	echo  $productwarranty;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	
	$myquery = mysql_query($fffquery);
	while( $myrecord = mysql_fetch_array($myquery))
   {
	   
	    $groupgg="SELECT * FROM retailercategory where CategoryCode='".$myrecord['Category']."'";
	   $groupgg1 = mysql_query($groupgg);
	 $cntnog=mysql_num_rows($groupgg1);
	 if($cntnog==1)
	  {
		   	$groupgg2 = mysql_fetch_array($groupgg1);
			$testtemp=$groupgg2['RetailerCategory'];
	   }
	   else
	   {
		   $testtemp ="";
	   }
$stringData =$myrecord['RetailerCode']."\t ;".$myrecord['RetailerName']."\t ;".$myrecord['fmexecutive']."\t;".$testtemp."\t ;".$myrecord['ContactName']."\t ;".$myrecord['ContactNo']."\t ;".$myrecord['CreditDays']."\t ;".$myrecord['CreditLimit']."\t ;".$myrecord['TinNo']."\t ;".date("d/m/Y",strtotime($myrecord['TinDate']))." ;\t\n";//
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportRetailer.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$fffquery;

	header('Location:ExportRetailer.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$fffquery;
	header('Location:ExportRetailer.php');
}
	
}	


if(isset($_POST['Cancel']))
{
	header('Location:retailermaster.php');
}


?> 

<script type="text/javascript">
$(function() {
  $("#TinDate").datepicker({ changeYear:true,maxDate: '0', yearRange: '2006:3050',dateFormat:'dd/mm/yy'});
  

});

function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}
function test()//Used for hide the delete button while click the check  box
				{
					var a=0;
					var addbutton = document.getElementById('addbutton');
					var checkall = document.getElementById('checkall')
					var table = document.getElementById('datatable1');
					var rowCount = table.rows.length;
					 if(rowCount >1)
					 {
						
						 for(var i=1;i < rowCount;i++)
						 {
						 if(document.getElementById('datatable1').rows[i].getElementsByTagName("INPUT")[0].checked==true)
							{
								 // delbutton.style.visibility = 'visible'; 
								 a++;
								 
							}
						 }
							
					 }
					 if(a>0)
					 {
						
						//delbutton.style.visibility = 'visible';
						//addbutton.style.visibility='hidden';
						addbutton.value="Delete";
						addbutton.name="Delete";
						
						 
					 }
					 else
					 {
						// addbutton.style.visibility='visible';
						 // delbutton.style.visibility='hidden';
						 	addbutton.value="Save";
							addbutton.name="Save";
						  checkall.checked= false; 
					 }
		}
checked=false;
function checkedAll (frm1) {
	var aa= document.getElementById('frm1');
	var addbutton = document.getElementById('addbutton');
	var table = document.getElementById('datatable1');
	var rowCount = table.rows.length;
	 if (rowCount >1 && checked == false)
          {
           checked = true
		   // delbutton.style.visibility = 'visible';
			//addbutton.style.visibility='hidden';
			addbutton.value="Delete";
			addbutton.name="Delete";
			// addbutton.style.width='1';
			//addbutton.style.height='1';
		   
          }
        else
          {
          checked = false
		// delbutton.style.visibility = 'hidden';
		  // addbutton.style.visibility='visible'; 
			//addbutton.add();
			addbutton.value="Save";
			addbutton.name="Save";
			//document.location='productgroupmaster.php';
		 
          }
	for (var i =0; i < aa.elements.length; i++) 
	{
	 aa.elements[i].checked = checked;
	 //delbutton.style.visibility = 'visible'; 
	}
      }
function validateRetailerCode(key)
{
	var object = document.getElementById('RetailerCode');
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
function validateRetailerName(key)
{
	var object = document.getElementById('RetailerName');
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
	var object = document.getElementById('Address');
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
function validateTinNo(key)
{
	var object = document.getElementById('TinNo');
	if (object.value.length <20 || key.keycode==8 || key.keycode==46)
{
	return true;
}
else
{
	alert("Enter only 20 characters");
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
function validateDistrictname(key)
{
	var object = document.getElementById('Districtname');
	if (object.value.length <50 || key.keycode==8 || key.keycode==46)
{
	return true;
}
else
{
	alert("Enter only 50 characters");toutfun(object);
return false;
}
}
function validateeno(){
    x=document.myForm
    object=x.ContactName.value
	
    if (object.length>49 || key.keycode==8 || key.keycode==46){
        alert("The field cannot contain more than 50 characters!")
		toutfun(object);
        return false
    }else {
        return true
    }
}
function ValidatePhoneno()
{
        var x = document.myForm.ContactNo.value;
       
          

       	 if(isNaN(x)|| x.indexOf(" ")!=-1)
	{
              			alert("Enter numeric value");
			return false;
                }
       			 if (x.length > 15)
			{
                			alert("Enter only 15 characters"); 
				return false;
          			 }
       
}

function validatecontac1(key)
{
	var object = document.getElementById('ph');
	if (object.value.length <20)
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
function validatebankname(key)
{
	var object = document.getElementById('Bankname');
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
function validateBranchname(key)
{
	var object = document.getElementById('Branchname');
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
function validateIFSCcode(key)
{
	var object = document.getElementById('IFSCcode');
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
function validateAccountholder(key)
{
	var object = document.getElementById('Accountholder');
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

function myFunc() { 
theValue = document.myForm.CreditLimit.value; 
rx = /[^0-9.]/; 
if(rx.test(theValue)) { 
alert("The field can only contain numbers"); 
document.myForm.CreditLimit.value = '';
return; 
} 
if(theValue.indexOf(".") != -1) { 
theValue = theValue.substring(0,(theValue.indexOf(".") + 3)); 
} 
lnt = theValue.length; 
if(lnt > 11) { 
if(theValue.indexOf(".") == -1) { 
theValue = theValue.substring(0,11); 
} 
else { 
theValue = theValue.substring(0,12); 
} 
lnt = theValue.length; 
} 
if(lnt > 9 && theValue.indexOf(".") == -1) { 
first = theValue.substring(0,9); 
second = theValue.substring(9); 
theValue = first + "." + second; 
} 
document.myForm.CreditLimit.value = theValue; 
} 

var filter = /^[0-9-+]+$/
function validatePhoneno(ph) {
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

var url = "inc/retaileronload.php?param=";
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
        function getagentids() 
        { 
            http=GetHttpObject();
              
if (http !=null)
{       //var idValue = document.getElementById("ProductCode").options.;
          
        var idValue = document.getElementById("fmexecutive").value; 
           var myRandom = parseInt(Math.random()*99999999); 
        
        // cache buster

        http.open("GET", url + escape(idValue)+  "&rand=" + myRandom, true); 
        http.onreadystatechange = handleHttpResponse; 
        http.send(null);
        
}
        }
 function handleHttpResponse()
  { 
  if (http.readyState == 4)
   { 
   results = http.responseText;
    var testing=results;
      var output=testing.replace("Resource id #5","");
     //document.all("Productdescription").options.selectedIndex = results; 
     document.getElementById("RetailerCode").value=output;
 
    } 
    } 
    </script>
<title>Amara Raja || Retailer Master</title>
</head>

 <?php if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.myForm.RetailerName.focus()">

<? }else{?>


<body class="default" onLoad="document.myForm.fmexecutive.focus()">

 <? } ?>
 <center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
			<form method="POST" action="<?php $_PHP_SELF ?>" name="myForm" onSubmit="return validate()" id="frm1">
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Retailer Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:290px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                             <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Franchisee</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                           <?php if(!empty($_GET['edi']))
					{?>
                               <input type="text" name="fmexecutive" value="<?php echo $fmexecutive;?>" readonly="readonly" style="border-style:hidden; background:#f5f3f1;" id="fmexecutive" />
                                  
                                  
                                <? }
					else
					{?>
     <!--   <input type="text" name="fmexecutive" value="<? echo $fmexecutive ?>" onKeyUp="validatefmexecutive(this)"/>-->
                               <select name="fmexecutive" id="fmexecutive" onBlur="getagentids()">
 <option value="<?php echo $fmexecutive;?>"><? if(!empty($fmexecutive)){ echo $fmexecutive;}else{?> ----Select---- <? } ?></option>
                                     <?
                                        $que = mysql_query("SELECT * FROM franchisemaster order by id desc");
                                     while( $record = mysql_fetch_array($que))
                                     {     
                                      echo "<option value=\"".$record['Franchisecode']."\">".$record['Franchisecode']."\n ";                      				}
                                    ?>
                             </select>
                              <? } ?>  
                               </div>
                               <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Retailer Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
        <input type="text" name="RetailerCode" id="RetailerCode" style="border-style:hidden; background:#f5f3f1;" readonly="readonly"  maxlength="15" onKeyPress="return validateRetailerCode(event)" value="<? echo $RetailerCode ?>" onFocus="RetailerName.focus();"/>     
                               </div>
 							<!--Row1 end-->
							 <!--Row1 -->  
                               <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Retailer Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="RetailerName" id="RetailerName" value="<? echo $RetailerName ?>" maxlength="50" onKeyPress="return validateRetailerName(event)" onChange="return trim(this)"/>
                               </div>
 							<!--Row1 end-->
                                  <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Retailer Category</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <select name="Category" id="Category">
 <option value="<?php echo $Category;?>"><? if(!empty($Category)){ echo $Category;}else{?> ----Select---- <? } ?></option>
                                     <?
                                        $que = mysql_query("SELECT * FROM retailercategory order by id desc");
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($Category!=$record['RetailerCategory'])
									  {     
                                      	echo "<option value=\"".$record['RetailerCategory']."\">".$record['RetailerCategory']."\n "; 
									  }
									 }
                                    ?>
                             </select>     
                              
                               </div>
 							<!--Row6 end-->       
                            
                             <!--Row7 -->  
                               <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Contact Person</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="ContactName" id="ContactName" value="<? echo $ContactName ?>" onKeyUp="validateeno(this)" onChange="return trim(this)" maxlength="50"/>
                               </div>
 							<!--Row7 end-->       
                            
                             <!--Row8 -->  
                               <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Contact No</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="ContactNo" id="ph" value="<? echo $ContactNo ?>" onKeyUp="return validatecontac1(event)" onChange="return validatePhoneno(this)" maxlength="20" onKeyPress="return trim(this)"/>
                               </div>
                               
                             <!--Row2 -->  
                               
							   </div>
							    <div style="width:290px; height:auto; padding-bottom:5px; float:left; " class="cont">
 							<!--Row4end-->
                           
 							<!--Row2 end-->   
                             <!--Row3 -->  
                              <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Address</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:65px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <textarea rows="4" cols="14" name="Address" id="Address" maxlength="250" onKeyPress="return validateAddress(event)" onChange="return trim(this)"><?php echo $Address; ?></textarea>
                               </div>
                               <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>City</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="City" id="City" value="<? echo $City ?>" onKeyPress="return validateCity(event)" onChange="return trim(this)" maxlength="50"/>
                               </div>
 							<!--Row3 end-->   
                             <!--Row4 -->  
							 
							 
                               <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>District Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="Districtname" id="Districtname" value="<? echo $Districtname ?>" onKeyPress="return validateDistrictname(event)" onChange="return trim(this)" maxlength="50"/>
                               </div>   
                             <!--Row5 -->  
                                <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Acc Holders Name</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="Accountholder" id="Accountholder" value="<? echo $Accountholder ?>" maxlength="50" onChange="return trim(this)" onKeyPress="return validateAccountholder(event)"/>
                               </div>
                                <div style="width:110px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Account No</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                <input type="text" name="AccNo" id="AccNo" value="<? echo $AccNo ?>" maxlength="50" onChange="return trim(this)" onKeyPress="return validatebankname(event)"/>
                               </div>
                                
                               
 							<!--Row5 end-->    
                            
                             <!--Row6 -->  
                         
 							<!--Row8 end-->                               
                                                          
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:290px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                                      <!--Row1 -->  
                              <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Bank Name</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                <input type="text" name="Bankname" id="Bankname" value="<? echo $Bankname ?>" maxlength="50" onChange="return trim(this)" onKeyPress="return validatebankname(event)"/>
                               </div>
                             
                              <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Branch Name</label>
                               </div>
                               
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="Branchname" id="Branchname" value="<? echo $Branchname ?>" maxlength="50"  onChange="return trim(this)" onKeyPress="return validateBranchname(event)"/>
                               </div>
                              <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>IFSC Code</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                <input type="text" name="IFSCcode" id="IFSCcode" value="<? echo $IFSCcode ?>" maxlength="50" onChange="return trim(this)" onKeyPress="return validateIFSCcode(event)"/>
                               </div>
                               <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Credit Days</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="CreditDays" value="<?echo $CreditDays ?>" onKeyUp="numericFilter(this)" onChange="return trim(this)"/>
                               </div>
 							<!--Row1 end-->
                             <!--Row2 -->  
                               <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Credit Limit</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="CreditLimit" value="<?echo $CreditLimit ?>" onKeyup="myFunc(this)" onChange="return trim(this)"/>
                               </div>
 							<!--Row2 end-->   
                             <!--Row3 -->  
                               <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Tin No</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="TinNo" id="TinNo" value="<?echo $TinNo ?>" maxlength="20" onKeyPress="return validateTinNo(event)" onChange="return trim(this)"/>
                               </div>
 							<!--Row3 end-->   
                             <!--Row4 -->  
                               <div style="width:120px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Tin Date</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="TinDate" id="TinDate" value="<?echo $TinDate ?>" readonly="readonly"/>
                               </div>
 							<!--Row4end-->   
                             <!--Row5 -->  
                               
 							<!--Row8 end-->   
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                                
                     <!-- col3 --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                     <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:-5px;">
                             
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
                              <!--Row1 -->  
							  <br>
                               <div style="width:100px; height:32px; float:left;margin-left:20px;" class="cont">
						     <label>Retailer Code :</label>
				           </div>	
                           
                           <div style="width:115px; height:32px; margin-left:20px; float:left;" class="cont">
						    <input type="text" name="codes" id="codes" value="<? echo $search1 ?>"/>
				           </div> 
                           
                            <div style="width:100px; height:32px; margin-left:10px; float:left;" class="cont">
						     <label>Retailer Name:</label>
				           </div>
						   
						   <div style="width:115px; height:32px; margin-left:10px; float:left;" class="cont">
						    <input type="text" name="names" value="<? echo $search2 ?>"/>
				           </div> 
						   
                           <div style="width:80px; height:32px; margin-left:20px; float:left;">
						  <input name="Search" type="submit" class="button1" value="">
				           </div>
                          </div> 
                </div>
                <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                      
                <table id="datatable1" align="center" class="sortable" bgcolor="#FF0000" border="1" width="900px" cellpadding="20%" style=" width:860px;	 overflow:auto;">
     		<tr>
                <?  if(($row['deleterights'])=='Yes')
	 {
	?> 
               <td class="sorttable_nosort" style="font-weight:bold; text-align:center">
               <input type='checkbox' name='checkall' onclick='checkedAll(frm1);'></td>
                  <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
              <td class="sorttable_nosort" style="font-weight:bold; text-align:center">Action</td>
               <? 
	  } 
	  ?>
              <td style="font-weight:bold;">RetailerCode</td>
              <td style="font-weight:bold;">RetailerName</td>
              <td style="font-weight:bold;">Address</td>
              <td style="font-weight:bold;">City</td>
              <td style="font-weight:bold;">DistrictName</td>
              <td style="font-weight:bold;">Franchisee</td>
              <td style="font-weight:bold;">RetailerCategory</td>
              <td style="font-weight:bold;">ContactPerson</td>
              <td style="font-weight:bold;">ContactNo</td>
              <td style="font-weight:bold;">CreditDays</td>
              <td style="font-weight:bold;">CreditLimits</td>
              <td style="font-weight:bold;">TinNo</td>
               <td style="font-weight:bold;">AccountHolders Name</td>
                <td style="font-weight:bold;">Account No</td>
              <td style="font-weight:bold;">BankName</td>
              <td style="font-weight:bold;">BranchName</td>
              <td style="font-weight:bold;">IFSCcode</td>
             <td style="font-weight:bold;">TinDate</td></tr>
               <?php
              // This while will loop through all of the records as long as there is another record left. 
               while( $record = mysql_fetch_array($query))
			  { // Basically as long as $record isn't false, we'll keep looping.
				// You'll see below here the short hand for echoing php strings.
				// <?=$record[key] - will display the value for that array.
				$testdate1=date("d/m/Y",strtotime($record['TinDate'])) ;
				if($testdate1 == '01/01/1970')
				{
				$testdate1="";
				}
			   ?>
    
			  <tr>
               <?  if(($row['deleterights'])=='Yes')
	 {
	?>  
			   <td style="font-weight:bold; text-align:center" bgcolor="#FFFFFF"><input name="checkbox[]" onChange="test();" type="checkbox" id="checkbox[]" value="<? echo $record['RetailerCode']."~".$record['RetailerName']; ?>"></td>
               <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
			  <td bgcolor="#FFFFFF" style="font-weight:bold; text-align:center"> <a style=" color:#FF2222; font-weight:bold " name="edit" href="retailermaster.php?<?php if(($row['editrights'])=='Yes') { echo 'edi='; echo $record['RetailerCode'];} else echo 'permiss'; ?>">Edit</a></td>
              <? 
	  } 
	  ?>
		      <td  bgcolor="#FFFFFF">
              <?=$record['RetailerCode']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['RetailerName']?>
              </td>
	          <td  bgcolor="#FFFFFF">
              <?=$record['Address']?>
              </td>
	          <td  bgcolor="#FFFFFF">
			  <?=$record['City']?>
			  </td>
			  <td  bgcolor="#FFFFFF">
              <?=$record['Districtname']?>
              </td>
			  <td  bgcolor="#FFFFFF">
              <?=$record['fmexecutive']?>
              </td>
              <td  bgcolor="#FFFFFF">
               <? $p=mysql_query("SELECT RetailerCategory FROM retailercategory where CategoryCode='".$record['Category']."'");
			$r=mysql_fetch_array($p);
			
			echo $r['RetailerCategory']; ?>
             
              </td>
	          <td  bgcolor="#FFFFFF">
              <?=$record['ContactName']?>
              </td>
	          <td  bgcolor="#FFFFFF">
			  <?=$record['ContactNo']?>
			  </td>
			  <td  bgcolor="#FFFFFF">
              <?=$record['CreditDays']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['CreditLimit']?>
              </td>
	          <td  bgcolor="#FFFFFF">
              <?=$record['TinNo']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['accountholdersname']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['accno']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['bankname']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['branchname']?>
              </td>
              <td  bgcolor="#FFFFFF">
              <?=$record['ifsccode']?>
              </td>
              
	          <td  bgcolor="#FFFFFF">
			  <?=$testdate1 ?>
			  </td>
    </tr>
             

  <?php
      }
  ?>
</table>

      
                   
               </div> 
<br /> <div style="width:600px; height:50px; float:left;  margin-left:15px; margin-top:0px;"  >
  <div style="margin-left:10px; margin-top:16px;"><?php
			echo pagination($startvalue,$statement,$limit,$page);
		?></div></div>
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