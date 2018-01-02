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
	global $tname,$employeecode,$employeename,$doj,$designation,$function,$gender,$dob,$bloodgroup,$parentname,$spousename,$address,$contact,$email,$country,$branch,$state,$region,$micrcode,$franchisecode,$pfno,$esino,$qualification,$scode,$sname;
	$scode = 'employeecode';
	$sname = 'employeename';
	$tname	= "employeemaster";
	require_once '../../searchfun.php';
	require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
   
   $pagename = "Employee Master";
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
			alert("you are not allowed to do this action!",'employeemaster.php');
			//setInterval(function(){document.location='employeemaster.php';},2000);
			//document.location='employeemaster.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'employeemaster.php');
			//setInterval(function(){document.location='employeemaster.php';},2000);
			//document.location='employeemaster.php';	
			</script>
         <?
		
	}
   
    if(isset($_POST['Save'])) // If the submit button was clicked
    {
		//global $tname,$employeecode,$employeename,$doj,$designation,$function,$gender,$dob,$bloodgroup,$parentname,$spousename,$address,$contact,$email,$country,$branch,$state,$region,$micrcode,$franchisecode,$pfno,$esino,$qualification,$search1,$search2;
       /* $employeecode = $_POST['employeecode'];
		$employeename = $_POST['employeename'];
		$test = $news->dateformat($_POST['doj']);
		$doj =$test;
	    $designation = $_POST['designation'];
		$function = $_POST['function'];	
		$gender = $_POST['gender'];
		$test1 = $news->dateformat($_POST['dob']);;	
		$dob =$test1;	   
		$bloodgroup = $_POST['bloodgroup'];
		$parentname = $_POST['parentname'];	
		$spousename = $_POST['spousename'];
		$address = $_POST['address'];
		$contact = $_POST['contact'];
		$email = $_POST['email'];
		$country = $_POST['country'];
		$branch = $_POST['branch'];
		$state = $_POST['state'];
		$region = $_POST['region'];	
		$micrcode = $_POST['micrcode'];
		$franchisecode = $_POST['franchisecode'];	
		$pfno = $_POST['pfstate'];
		$esino = $_POST['esino'];	
		$qualification = $_POST['qualification'];*/
			unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		
		$post['employeecode'] = str_replace('&', 'and',$_POST['employeecode']);
		$post['employeename'] = str_replace('&', 'and',$_POST['employeename']);
		$post['address'] = $_POST['address'];
		$post['contact'] = $_POST['contact'];
		$post['email'] = $_POST['email'];
		$countrysmallCode=mysql_query("SELECT * FROM countrymaster where countryname='".$_POST['country']."'");
			$countrysmallfetch=mysql_fetch_array($countrysmallCode);
			
			$post['country'] = $countrysmallfetch['countrycode'];
		//$post['country'] = $_POST['country'];
		$smallCode=mysql_query("SELECT * FROM region where RegionName='".$_POST['region']."'");
			$smallfetch=mysql_fetch_array($smallCode);
			
			$post['region'] = $smallfetch['RegionCode'];
		//$post['region'] = $_POST['region'];	
		$statesmallCode=mysql_query("SELECT * FROM state where statename='".$_POST['state']."'");
			$statesmallfetch=mysql_fetch_array($statesmallCode);
			
			$post['state'] = $statesmallfetch['statecode'];
		//$post['state'] = $_POST['state'];
		$branchsmallCode=mysql_query("SELECT * FROM branch where branchname='".$_POST['branch']."'");
			$branchsmallfetch=mysql_fetch_array($branchsmallCode);
			
			$post['branch'] = $branchsmallfetch['branchcode'];
		//$post['branch'] = $_POST['branch'];
		$post['designation'] = str_replace('&', ' and ',$_POST['designation']);
		
		$employeecode=$_POST['employeecode'];
		$employeename=$_POST['employeename'];
		$address=$_POST['address'];
		$contact=$_POST['contact'];
		$email=$_POST['email'];
		$country=$_POST['country'];
		$region=$_POST['region'];
		$state=$_POST['state'];	
		$branch=$_POST['branch'];
		$designation=$_POST['designation'];
		        
 
        // This will make sure its displayed
		if(!empty($_POST['employeecode']) && !empty($_POST['employeename'])&& !empty($_POST['address'])&& !empty($_POST['contact'])&& !empty($_POST['email'])&& !empty($_POST['designation'])&& !empty($_POST['branch'])&& !empty($_POST['country'])&& !empty($_POST['region'])&& !empty($_POST['state']))
		{   
			$result="SELECT * FROM employeemaster where employeecode ='".$post['employeecode']."'";
			 $sql1 = mysql_query($result) or die (mysql_error());
 			$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
		if($myrow1>0)
		{
		?>
            <script type="text/javascript">
			alert("Duplicate entry!");//document.location='employeemaster.php';
			</script>
         <?
		}
		else
		{	
		
				$post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());		
			$news->addNews($post,$tname);
		
		
		?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'employeemaster.php');
			//setInterval(function(){document.location='employeemaster.php';},2000);
			//document.location='employeemaster.php';
			</script>
            <?
        }
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='employeemaster.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);	
		$post['employeecode'] = $_POST['employeecode'];
		$post['employeename'] =$_POST['employeename'];
		$post['address'] = $_POST['address'];
		$post['contact'] =$_POST['contact'];
		$post['email'] = $_POST['email'];
		$countrysmallCode=mysql_query("SELECT * FROM countrymaster where countryname='".$_POST['country']."'");
			$countrysmallfetch=mysql_fetch_array($countrysmallCode);
			
			$post['country'] = $countrysmallfetch['countrycode'];
		//$post['country'] = $_POST['country'];
		$smallCode=mysql_query("SELECT * FROM region where RegionName='".$_POST['region']."'");
			$smallfetch=mysql_fetch_array($smallCode);
			
			$post['region'] = $smallfetch['RegionCode'];
	//	$post['region'] = $_POST['region'];	
		$statesmallCode=mysql_query("SELECT * FROM state where statename='".$_POST['state']."'");
			$statesmallfetch=mysql_fetch_array($statesmallCode);
			
			$post['state'] = $statesmallfetch['statecode'];
		//$post['state'] = $_POST['state'];
		$branchsmallCode=mysql_query("SELECT * FROM branch where branchname='".$_POST['branch']."'");
			$branchsmallfetch=mysql_fetch_array($branchsmallCode);
			
			$post['branch'] = $branchsmallfetch['branchcode'];
		//$post['branch'] = $_POST['branch'];
		$post['designation'] = str_replace('&', ' and ',$_POST['designation']);
		
		$employeecode=$_POST['employeecode'];
		$employeename=$_POST['employeename'];
		$address=$_POST['address'];
		$contact=$_POST['contact'];
		$email=$_POST['email'];
		$country=$_POST['country'];
		$region=$_POST['region'];
		$state=$_POST['state'];	
		$branch=$_POST['branch'];
		$designation=$_POST['designation'];
        // This will make sure its displayed
		if(!empty($_POST['employeecode'])&& !empty($_POST['employeename'])&& !empty($_POST['address'])&& !empty($_POST['contact'])&& !empty($_POST['email'])&& !empty($_POST['designation'])&& !empty($_POST['branch'])&& !empty($_POST['country'])&& !empty($_POST['region'])&& !empty($_POST['state']) )
		{
			$result=mysql_query("SELECT * FROM employeemaster where employeecode ='".$_POST['employeecode']."' ");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("You are not allowed to save a new record using Update!");
			</script>
   			<?
		}
		else
		{  
					$post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
				$wherecon= "employeecode ='".$post['employeecode']."'";
			$news->editNews($post,$tname,$wherecon);
						
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'employeemaster.php');
			//setInterval(function(){document.location='employeemaster.php';},2000);
			//document.location='employeemaster.php';
			</script>
            <?
					
		}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='employeemaster.php';
			</script>
            <?
		}
	}
	
/// EDIT LINK FUNCTION 

if(!empty($_GET['edi']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
  $prmaster  = $_GET['edi'];
  
$result=mysql_query("SELECT * FROM employeemaster where employeecode ='".$prmaster."' ");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!");//document.location='employeemaster.php';
			</script>
   			<?
		}
		else
		{
		 $myrow = mysql_fetch_array($result);
		 $employeecode = $myrow['employeecode'];
		 $employeename = $myrow['employeename'];
		 $address=$myrow['address'];
		 $contact=$myrow['contact'];
		 $email=$myrow['email'];
		  $countrypgg= mysql_query("select countryname from countrymaster where countrycode='".$myrow['country']."' ")  ;
		$countryrecord11 = mysql_fetch_array($countrypgg);
        $country = $countryrecord11['countryname'];
		// $country=$myrow['country'];
		 $pgg= mysql_query("select RegionName from region where RegionCode='".$myrow['region']."' ")  ;
		$record11 = mysql_fetch_array($pgg);
        $region = $record11['RegionName'];
		 //$region=$myrow['region'];
		$Statepgg= mysql_query("select statename from state where statecode='".$myrow['state']."' ")  ;
		$Staterecord11 = mysql_fetch_array($Statepgg);
        $state = $Staterecord11['statename'];
		// $state=$myrow['state'];
		$branchpgg= mysql_query("select branchname from branch where branchcode='".$myrow['branch']."' ")  ;
		$branchrecord11 = mysql_fetch_array($branchpgg);
        $branch = $branchrecord11['branchname'];
		// $branch=$myrow['branch'];
		$designation = $myrow['designation'];	    
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
			alert("Select data to delete!",'employeemaster.php');
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
		$q1="select employeecode
FROM employeemaster
WHERE employeecode='".$prodidd."' and EXISTS(
 SELECT empcode
FROM  `usercreation` 
WHERE empcode='".$prodidd."'
)";
$repres= mysql_query($q1) or die (mysql_error());
 $myrow1 = mysql_num_rows($repres);
 if($myrow1==0)
 {
		$wherecon= "employeecode ='".$checkbox[$i]."'";
		$news->deleteNews($tname,$wherecon);
		
				?>
							<script type="text/javascript">
							alert("Deleted  Successfully!",'employeemaster.php');
						//setInterval(function(){document.location='branchmaster.php';},2000);
							//document.location='branchmaster.php';
							</script>
							<?
 }
 else
 {
	 						?>
           					<script type="text/javascript">
							alert("you can't delete already used in other forms!",'employeemaster.php');
							</script>
 							<?
 }
		}
}
}
$_SESSION['type']=NULL;
	$employeemaster='select * from employeemaster';
if(isset($_POST['PDF']))
{

$select=$_POST['Type'];
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM employeemaster WHERE employeecode like'".$_POST['codes']."%' OR employeename like'".
		$_POST['names']."%'order by id desc";
		$employeemaster=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM employeemaster WHERE employeecode like'".$_POST['codes']."%'order by id desc";
		$employeemaster=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM employeemaster WHERE employeename like'".$_POST['names']."%'order by id desc";
		$employeemaster=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM employeemaster order by id desc";
		$employeemaster=$condition;
	}
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$employeemaster;
	//echo  $productwarranty;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	
	$myquery = mysql_query($employeemaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
	   $groupselct="SELECT branchname FROM branch where branchcode='".$myrecord['branch']."'";
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
	   $stateselct="SELECT statename FROM state where statecode='".$myrecord['state']."'";
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
	    $regionselct="SELECT RegionName FROM region where RegionCode='".$myrecord['region']."'";
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
	    $countryselct="SELECT countryname FROM countrymaster where countrycode='".$myrecord['country']."'";
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
$stringData =$myrecord[0]."\t ;".$myrecord[1]."\t ;".$myrecord[2].";\t".$myrecord[3]."\t ;".$myrecord[4]."\t ;".$myrecord[5].";\t".$countrytempp."\t ;".$regiontempp."\t ;".$statetempp."\t ;". $testtempp." ;\t\n";//
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportEmployee.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$employeemaster;

	header('Location:ExportEmployee.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$employeemaster;
	header('Location:ExportEmployee.php');
}
}

if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:employeemaster.php');
}

?>


 <script type="text/javascript"> 
 function drpfunc()
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
		
		document.getElementById("region").value=tt;
		document.getElementById("country").value=tt2;
	}
}

       var url = "inc/autofetch.php?param=";
        var http;
function GetHttpObject()
{
if (window.ActiveXObject)
return new ActiveXObject("Microsoft.XMLHTTP");
else if (window.XMLHttpRequest)
return new XMLHttpRequest();
else
{
alert("Your browser does not support this functionality.");
return null;
}
}
        function getagentids1() 
        { 
            http=GetHttpObject();
              
if (http !=null)
{       //var idValue = document.getElementById("ProductCode").options.;
          
        var idValue = document.getElementById("branch").value; 
           
           var myRandom = parseInt(Math.random()*99999999); 
        
        // cache buster

        http.open("GET", url + escape(idValue)+  "&rand=" + myRandom, true); 
        http.onreadystatechange = handleHttpResponse1; 
        http.send(null);
        
}
        }
 function handleHttpResponse1()
  { 
  if (http.readyState == 4)
   { 
   results = http.responseText;
    var testing=results;
     
      var output=testing.replace("Resource id #5","");
	  var b=new Array();
	   var c=output.split("+");
	
     //document.all("Productdescription").options.selectedIndex = results; 
     //document.getElementById("state").value=c[0];
  document.getElementById("region").value=c[0];
  document.getElementById("country").value=c[1];
  state.focus()
 //http.responseText='';
    } 
    } 


			

var filter = /^[0-9-+() ]+$/
function validatePhoneno(th) {
    
    var returnvalph=filter.test(th.value);
    if (returnvalph==false) {
		alert("Please enter a valid Contact number")
		th.value=''; 
		toutfun(th);
//ph.select()
// $(':text').val(''); 
        
    }
    return returnvalph;
    }

function validateemployeecode(key)
{
//getting key code of pressed key
var phn = document.getElementById('employeecode');
if (phn.value.length <15 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 15 characters");
	toutfun(phn);
return false;
}
}

function validateemployeename(key)
{
//getting key code of pressed key
var phn = document.getElementById('employeename');
//Condition to check textbox contains ten numbers or not
if (phn.value.length <50 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 50 characters");
	toutfun(phn);
return false;
}
}

function validatedesg(key)
{
//getting key code of pressed key
var phn = document.getElementById('designation');
//Condition to check textbox contains ten numbers or not
if (phn.value.length <50 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 50 characters");
	toutfun(phn);
return false;
}
}

function validateadd(key)
{
//getting key code of pressed key
var phn = document.getElementById('address');
//Condition to check textbox contains ten numbers or not
if (phn.value.length <250 || key.keycode==8 || key.keycode==46)
{
return true;
}
else
{
	alert("Enter only 250 characters");
	toutfun(phn);
return false;
}
}

function validatecontact(key)
{
//getting key code of pressed key
var phn = document.getElementById('contact');
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
    
</script>
<title><?php echo $_SESSION['title']; ?> || Employee Master</title>
</head>
 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 <body class="default" onLoad="document.form1.employeename.focus()">
<? }else{?>
<body class="default" onLoad="document.form1.employeecode.focus()">
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
						<p> Employee Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:300px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Employee Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <input type="text" id="employeecode" name="employeecode"  onKeyUp="return validateemployeecode(event)" onKeyPress="return validateemployeecode(event)" <? if(!empty($_GET['edi'])) { ?> readonly="readonly" style="border-style:hidden; background:#f5f3f1;" <? }?> value="<?php echo $employeecode;?>"  onChange="return codetrim(this)" />
                               </div> 
 							<!--Row1 end-->
                             <!--Row2 -->
                              <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Employee Name</label><label style="color:#F00;">*</label>
                             </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <input type="text" id="employeename" name="employeename" onKeyPress="return validateemployeename(event)" value="<?php echo $employeename;?>" onChange="return trim(this)" />
                             </div>
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Address</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:65px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <textarea rows="4" cols="14" name="address" id="address" onKeyPress="return validateadd(event)" onChange="return trim(this)"><?php echo $address;?></textarea>
                             </div>
                             
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Contact No</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="contact" id="contact" value="<?php echo $contact;?>" onKeyUp="return validatecontact(event)" maxlength="20" onChange="return validatePhoneno(this)" onBlur="return trim(this)" />
                               </div>
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->  
                      
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">  
                           
                            <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Email Id</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <input type="text" id="email" name="email"  value="<?php echo $email;?>" onChange="return checkmail(this)" onBlur="return codetrim(this)" />
                                  <!--  <input type="text" name="email" id="email" onChange="validateForm()" value=" onKeyPress="return trim(this)"/>-->
                               </div>
                               
                            <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Designation</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <input type="text" name="designation" id="designation" onKeyPress="return validatedesg(event)" value="<?php echo $designation;?>" onChange="return trim(this)" />
                               </div> 
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Branch</label><label style="color:#F00;">*</label>
                               </div>
                               <!--//onBlur="getagentids1()"-->
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                             
                                       <select name="branch" id="branch" onChange="drpfunc();" >
                                       <option value="<?php echo $branch;?>"><? if(!empty($branch)){ echo $branch;}else{?> ----Select---- <? } ?></option>
                                     <?
                                         $que = mysql_query("SELECT branchname FROM branch order by branchname asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($branch!=$record['branchname'])
									  {	      
                                       echo "<option value=\"".$record['branchname']."\">".$record['branchname']."\n ";                      
									  }
                                     }
                                    ?>
                                    
                                          </select>
                               </div>
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>State</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                          <?php /*?><input type="text" name="state" id="state" value="<?php echo $state;?>" readonly="readonly"/><?php */?>                
                                      <select name="state" id="state"  >
                                       <option value="<?php echo $state;?>"><? if(!empty($state)){ echo $state;}else{?> ----Select---- <? } ?></option>
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
                                  <label>region</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
              <input type="text" name="region" id="region" value="<?php echo $region;?>" readonly="readonly" style="border-style:hidden; background:#f5f3f1;" onFocus="addbutton.focus()"/>   
                               </div>                       
                           <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Country</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <input type="text" name="country" id="country" readonly="readonly" style="border-style:hidden; background:#f5f3f1;" value="<?php echo $country;?>" />

 <?php /*?>    <select name="country" id="country" >
         <option value="<?php echo $country;?>"><? if(!empty($country)){ echo $country;}else{?> ----Select---- <? } ?></option>
             <?
                                                                                
                                        $que = mysql_query("SELECT countryname FROM countrymaster");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {     
                                      echo "<option value=\"".$record['countryname']."\">".$record['countryname']."\n ";                      
                                     }
                                    ?>
                                          </select><?php */?>
                               </div>
                               
 							
                             
                               
                                                        
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                                   <!--Row1 -->  
                             
                 
                            </div>      
                     <!-- col3 --> 
                                                              
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
                              <!--Row1 -->  
                               <div style="width:100px; height:30px; float:left; margin-left:3px; margin-top:16px;" >
                                <label>Employee Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                 <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:100px; height:30px; float:left; margin-left:15px; margin-top:9px;">
                                  <label>Employee Name</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                 <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval'] ?>"/>
                               </div>
                               <div style="width:83px; height:32px; float:left; margin-top:16px;  margin-left:15px;">
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>
                               </div>  
                               </div>
                               </div>
                
                <!--Main row 2 end-->
            
             <!-- form id start end-->  
                   <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                   
                  <table id="datatable1" align="center" class="sortable" bgcolor="#FF0000" border="1" width="900px"><tr>
        <?  if(($row['deleterights'])=='Yes')
	 {
	?>    
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="8px">
     <input type='checkbox' id="checkall" name='checkall' onclick='checkedAll(frm1);'></td>
       <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="12px">Action</td>
      <? 
	  } 
	  ?>
     <td style="font-weight:bold;text-align:center;">EmployeeCode</td>
     <td style="font-weight:bold;text-align:center;">EmployeeName</td>
     <td style="font-weight:bold;text-align:center;">Address </td>
     <td style="font-weight:bold;text-align:center;">Contact</td>
     <td style="font-weight:bold;text-align:center;">Email</td>
        <td style="font-weight:bold;text-align:center;">Designation</td>
     <td style="font-weight:bold;text-align:center;">Country</td>
     <td style="font-weight:bold;text-align:center;">Region</td>
     <td style="font-weight:bold;text-align:center;">State</td>
     <td style="font-weight:bold;text-align:center;">Branch</td>
   
     </tr>
     
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
     <td align="center" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" onChange="test();" value="<? echo $record['employeecode'];
 ?>"></td>
    <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
  <td style="font-weight:bold; text-align:center" bgcolor="#FFFFFF" align="left" valign="top"> <a style="color:#0360B2" name="edit" href="employeemaster.php?edi=<?= $record['employeecode'];?>">Edit</a></td>
 <? 
	  } 
	  ?>
    <td bgcolor="#FFFFFF">
        <?=$record['employeecode']?>
    </td>
     <td   bgcolor="#FFFFFF"  >
        <?=$record['employeename']?>
    </td>
    <td   bgcolor="#FFFFFF" >
        <?=$record['address']?>
    </td>
    <td   bgcolor="#FFFFFF"  >
        <?=$record['contact']?>
    </td>
    <td  bgcolor="#FFFFFF" >
        <?=$record['email']?>
    </td>
       <td    bgcolor="#FFFFFF"  >
        <?=$record['designation']?>
    </td>
    <td   bgcolor="#FFFFFF"  >
    <? $check= mysql_query("select countryname from countrymaster where countrycode='".$record['country']."' ")  ;
		$checkrecord = mysql_fetch_array($check);
       echo $checkrecord['countryname']; ?>
        
    </td>
    <td  bgcolor="#FFFFFF">
    <? $check1= mysql_query("select RegionName from region where RegionCode='".$record['region']."' ")  ;
		$check1record = mysql_fetch_array($check1);
       echo $check1record['RegionName']; ?>
       
    </td>
     <td  bgcolor="#FFFFFF" >
     <? $check2= mysql_query("select statename from state where statecode='".$record['state']."' ")  ;
		$check2record = mysql_fetch_array($check2);
       echo $check2record['statename'];  ?>
      
    </td>
     <td   bgcolor="#FFFFFF">
     <?  $check3= mysql_query("select branchname from branch where branchcode='".$record['branch']."' ")  ;
		$check3record = mysql_fetch_array($check3);
       echo $check3record['branchname'];  ?>
       
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
                                <select name="Type">
                                <option value="PDF">PDF</option>
                                   <option value="Excel">Excel</option>
                                     <option value="Document">Document</option>
                                                                   </select>
             				
                               </div>  
                               <div style="width:63px; height:32px; float:right; margin-top:18px;">
             					  <input type="submit" name="PDF" value="Export" class="button"/>
                               </div ></div>

          
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