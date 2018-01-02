<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $RetailerCode,$RetailerName,$Address,$City,$Districtname,$fmexecutive,$Category,$ContactName,$ContactNo,$CreditDays,$CreditLimit,$TinNo,$TinDate,$checkbox,$flag,$tname,$scode,$sname,$Bankname,$Branchname,$IFSCcode,$Accountholder,$AccNo; 
	$scode = 'RetailerCode';
	$sname = 'RetailerName';
	$tname	= "retailermaster";
	require_once '../../searchfun.php';
	$stname="retailermasterupload";
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
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
			
				$mkrow = mysql_query("SELECT Status FROM retailermasterupload where Code='".$var1."' and Status!='0'");
				$val=mysql_num_rows($mkrow);
				if($val==0)
				{
				$wherec= "Code='".$var1."' ";
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


$_SESSION['type']=NULL;
	$fffquery='select * from retailermaster';

if(isset($_POST['PDF']))
{

$select=$_POST['Type'];
if(!empty($_SESSION['codesval'])&&!empty($_SESSION['namesval']))
	{
		$condition="SELECT * FROM retailermaster WHERE RetailerCode like'".$_SESSION['codesval']."%' OR RetailerName like'".
		$_SESSION['namesval']."%'order by id DESC";$fffquery=$condition;
		
	}
	else if(!empty($_SESSION['codesval'])&&empty($_SESSION['namesval']))
	{
		$condition="SELECT * FROM retailermaster WHERE RetailerCode like'".$_SESSION['codesval']."%'order by id DESC";
		$fffquery=$condition;
	}
	else if(!empty($_SESSION['namesval'])&&empty($_SESSION['codesval']))
	{
		$condition="SELECT * FROM retailermaster WHERE RetailerName like'".$_SESSION['namesval']."%'order by id DESC";
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
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
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
    x=document.form1
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
        var x = document.form1.ContactNo.value;
       
          

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
theValue = document.form1.CreditLimit.value; 
rx = /[^0-9.]/; 
if(rx.test(theValue)) { 
alert("The field can only contain numbers"); 
document.form1.CreditLimit.value = '';
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
document.form1.CreditLimit.value = theValue; 
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

 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.codes.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.codes.focus()">

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
			<form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1">
            <div style="width:930px; height:auto;   padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Retailer Master</p>
						</div>
              <!-- main row 1 start-->     
               
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                     <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:-5px;">
                             
						
                         
                          <div style="width:840px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
							  <br>
                               <div style="width:100px; height:32px; float:left;margin-left:20px;" class="cont">
						     <label>Retailer Code :</label>
				           </div>	
                           
                           <div style="width:145px; height:32px; margin-left:20px; float:left;" class="cont">
						    <input type="text" name="codes" onKeyPress="searchKeyPress(event);" id="codes" value="<? echo $_SESSION['codesval'] ?>"/>
				           </div> 
                           
                            <div style="width:100px; height:32px; margin-left:10px; float:left;" class="cont">
						     <label>Retailer Name:</label>
				           </div>
						   
						   <div style="width:145px; height:32px; margin-left:10px; float:left;" class="cont">
						    <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
				           </div> 
						   
                           <div style="width:80px; height:32px; margin-left:20px; float:left;">
						  <input name="Search" id="Search" type="submit" class="button1" value="">
				           </div>
                           <div style="width:80px; height:32px; margin-left:20px; margin-top:2px; float:left;">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div>   
                          </div> 
                </div>
                <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                      
                <table id="datatable1" align="center" class="sortable" bgcolor="#FF0000" border="1" width="900px" cellpadding="20%" style=" width:860px;	 overflow:auto;">
     		<tr style="white-space:nowrap;">
<?php /*?>                <?  if(($row['deleterights'])=='Yes')
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
	  ?><?php */?>
                    <td style="font-weight:bold; width:auto; text-align:center;">Retailer Code</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Retailer Name</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Address</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">City</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">District Name</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Franchisee</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Retailer Category</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Contact Person</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Contact No.</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Credit Days</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Credit Limits</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Tin No.</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Account Holders Name</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Account No</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Bank Name</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Branch Name</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">IFSC code</td>
                    <td style="font-weight:bold; width:auto; text-align:center;">Tin Date</td>
             </tr>
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
    
			  <tr style="white-space:nowrap;">
<?php /*?>               <?  if(($row['deleterights'])=='Yes')
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
	  ?><?php */?>
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
                   <?php
  if(isset($_POST['Search']))
{
if($myrow1==0)	
{?>
		<? echo '<tr ><td colspan="21" align="center" bgcolor="#FFFFFF" style="color:#F00"  >No Records Found</td></tr>'; ?>	
<? } }?>
</table>

      
                   
               </div> 
<?php include("../../paginationdesign.php")?>
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
