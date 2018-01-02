<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");
require_once '../../paginationfunction.php';

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $userid,$password,$empcode,$repeatnewpassword,$empname,$scode,$sname,$salt;
	$scode = 'userid';
	$sname = 'empcode';
	$tname	= "usercreation";
	require_once '../../searchfun.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	$pagename = "User Creation";
	$validuser = $_SESSION['username'];
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");
	$row = mysql_fetch_array($selectvar);

 	if (($row['viewrights'])== 'No')
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
	if(isset($_POST['permiss'])) // If the submit button was clicked
    {
		?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'usercreation.php');
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'usercreation.php');
			</script>
         <?
		
	}//Page Verification Code and User Verification

	
if(isset($_POST['p'], $_POST['userid'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		// The hashed password from the form
$password = $_POST['p']; 
// Create a random salt
$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
// Create salted password (Careful not to over season)
$password = hash('sha512', $password.$random_salt);
	
       $post['userid'] = $_POST['userid'];
		$post['password'] = $password;		
        $post['empcode'] = $_POST['empcode'];
		$post['salt'] = $random_salt;
		$post['estatus'] = 'NEW';
		$userid=$_POST['userid'];
		$password=$_POST['password'];
		$repeatnewpassword=$_POST['repeatnewpassword'];
		$empcode=$_POST['empcode'];
		$empname = $_POST['empname'];
		$salt = $random_salt;
	$estatus=$post['estatus'];
	$post['count']=0;


        // This will make sure its have a Value in textbox's
		if(!empty($_POST['userid']))
		{ 
			if(!empty($_POST['password']))
			{
				if(!empty($_POST['repeatnewpassword']))
				{
					if(!empty($_POST['empcode']))
					{
						if ($_POST['password']==$_POST['repeatnewpassword'])
						{    
			 				$c1=substr($post['userid'],0,1);
							$Producttype=strtolower( preg_replace('/\s+/', '',$_POST['userid']));
							$empcode=$_POST['empcode'];
							$myrow1=0;
							$repqry="SELECT REPLACE(`userid` ,  ' ',  '' ) AS userid,empcode  FROM  usercreation  where  userid like  '$c1[0]%' or empcode ='$empcode' ";
							$repres= mysql_query($repqry) or die (mysql_error());
							 while($myrow = mysql_fetch_array($repres))
							  {
								  
								  $productcode = strtolower($myrow['userid']);
								  $empcode = $myrow['empcode'];
									if($productcode==$Producttype||$empcode==$_POST['empcode'])
									{
										$myrow1++;
										break;
									}
									
							}
							if($myrow1>0)
							{
							?>
								<script type="text/javascript">
								alert("Duplicate entry!");
								</script>
							 <?
							}
							else
							{
								$news->addNews($post,$tname);
								$qry = "select pagename from pagename where pagetype='Master' order by sorting";
								$masterpage = mysql_query($qry);

								$reps['userid'] = isset($_POST['userid']) ? $_POST['userid'] : NULL;
							    while($master_result = mysql_fetch_array($masterpage)){ 
									$reps['screen'] = $master_result['pagename'];
									$reps['addrights'] =  'No';
									$reps['editrights'] = 'No';
									$reps['viewrights'] = 'No';
									$reps['deleterights'] =  'No';
									// echo "INSERT INTO `userrights` VALUES ('".$reps['userid']."','".$reps['screen']."','".$reps['addrights']."','".$reps['editrights']."','".$reps['viewrights']."','".$reps['deleterights']."')";

									mysql_query("INSERT INTO `userrights` VALUES ('".$reps['userid']."','".$reps['screen']."','".$reps['addrights']."','".$reps['editrights']."','".$reps['viewrights']."','".$reps['deleterights']."')") or die('Insert Error');	
								}
// exit;
// Report rights


	$reps['userid'] = isset($_POST['userid']) ? $_POST['userid'] : NULL;
	$qry = "select pagename from pagename where pagetype='Report' order by sorting";
	$reportpage = mysql_query($qry);
		while($report_result = mysql_fetch_array($reportpage)){ 
			$reps['r_screen'] = $report_result['pagename'];
			$reps['access_right'] = 'No';
			$reps['branch_right'] = 'Others';
			// echo "INSERT INTO `reportrights` VALUES ('".$reps['userid']."','".$reps['r_screen']."','".$reps['access_right']."','".$reps['branch_right']."''')";
			mysql_query("INSERT INTO `reportrights` VALUES ('".$reps['userid']."','".$reps['r_screen']."','".$reps['access_right']."','".$reps['branch_right']."')");	
		}
		// exit;
					?>
					<script type="text/javascript">
					alert("Created Sucessfully!",'usercreation.php');
					</script>
					<?
					}
						}
						else
						{
							?>
							<script type="text/javascript">
							alert("password and Repeat passwords are Mismatch! Enter again");
							</script>
							<?
						}
					}
					else
						{
							?>
							<script type="text/javascript">
							alert("Please enter Employee Code");
							</script>
							<?
						}
						}
						else
						{
							?>
							<script type="text/javascript">
							alert("Please enter Repeat password");
							</script>
							<?
						}
			}
			else
						{
							?>
							<script type="text/javascript">
							alert("Please enter password");
							</script>
							<?
						}
		}
		else
		{
		?>
		<script type="text/javascript">
		alert("Please enter User Id");
		</script>
		<?
		}

	}

//Update
if(isset($_POST['updatep'])) // If the submit button was clicked
{
		unset($_SESSION['codesval']);
		unset($_SESSION['namesval']);
			if(!empty($_POST['userid']))
		{ 
			if(!empty($_POST['password']))
			{
				if(!empty($_POST['repeatnewpassword']))
				{
					if(!empty($_POST['empcode']))
					{
		if($_POST['password']==$_POST['repeatnewpassword'])
		{
		$queryget = mysql_query("SELECT * FROM usercreation WHERE userid='".$_POST['userid']."'" );
		$userrow = mysql_fetch_assoc($queryget);
		$salt= $userrow['salt'];
		$encpassword= hash('sha512', $_POST['updatep'].$salt);
		mysql_query("update usercreation SET password='".$encpassword."',estatus='NEW',count=0 where userid='".$_POST['userid']."'");
		?>
		<script type="text/javascript">
		alert("Your Password has been Updated successfully..!",'usercreation.php');
		</script>
		<?
		}
		else
		{
			?>
		<script type="text/javascript">
		alert("password and Repeat passwords are Mismatch! Enter again");
		password.focus();
		</script>
		<?
		}
					}
					else
						{
							?>
							<script type="text/javascript">
							alert("Please enter Employee Code");
							</script>
							<?
						}
						}
						else
						{
							?>
							<script type="text/javascript">
							alert("Please enter Repeat password");
							</script>
							<?
						}
			}
			else
						{
							?>
							<script type="text/javascript">
							alert("Please enter password");
							</script>
							<?
						}
		}
		else
		{
		?>
		<script type="text/javascript">
		alert("Please enter User Id");
		</script>
		<?
		}
}
	
/// EDIT LINK FUNCTION 
if(!empty($_GET['userid']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	
$uyid =$_GET['userid'];

//$cont->connect();
$result=mysql_query("SELECT * FROM usercreation where userid ='".$uyid."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'usercreation.php');
			</script>
   			<?
		}
		else
		{
		$myrow = mysql_fetch_array($result);
		$userid = $myrow['userid'];
		//$password = $myrow['password'];
		// $repeatnewpassword = $myrow['repeatnewpassword'];
		$empcode = $myrow['empcode'];
		$empqry= mysql_query("select employeename from employeemaster where employeecode='".$myrow['empcode']."' ")  ;
		$emprec = mysql_fetch_array($empqry);
		$empname = $emprec['employeename'];
		$password="";
		$repeatnewpassword="";
		}
		$uyid = NULL;
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
			alert("Select data to delete!",'usercreation.php');
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
			
			if($_SESSION['username']!=$var1 && 'admin'!=$var1)
			{
				///$prodid= $_POST['checkbox'];
				$wherecon= "userid ='".$var1."'";
				$tname = "usercreation";
				$news->deleteNews($tname,$wherecon);
				$tname = "userrights";
				$wherecon= "userid ='".$var1."'";
				$news->deleteNews($tname,$wherecon);
				$news->deleteNews("reportrights",$wherecon);
				?><script>alert("Deleted Successfully!",'usercreation.php');</script><?
			}
			else
			{
				?><script>alert("You try to delete the current user",'usercreation.php');</script><?
			}
		}
		
}
}

if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:usercreation.php');
}

?>


<script type="text/javascript"> 
 		
		
		function nospaces(t)
		{
			if(t.value.match(/\s/g))
			{
				alert('Sorry, you are not allowed to enter any spaces');
				t.value=t.value.replace(/\s/g,'');
			}
		}
		
function validate(){
    x=document.myForm
    input=x.userid.value
    if (input.length>50){
        alert("The field cannot contain more than 50 characters!")
        return false
    }else {
        return true
    }
}
function validatee1(key)
{
var object = document.getElementById('repeatnewpassword');
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
function validatee(key)
{
var object = document.getElementById('password');
if (object.value.length <15 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("The field should contain minimum  15 characters!");
	toutfun(object);
return false;
}
}

function updatefunc(form, password) {
   // Create a new element input, this will be out hashed password field.


   var updatep = document.createElement("input");
   // Add the new element to our form.
   
   form.appendChild(updatep);
    
   updatep.name = "updatep";
   
   updatep.type = "hidden"
 
   updatep.value = hex_sha512(password.value);
   
   // Make sure the plaintext password doesn't get sent.
  
   // Finally submit the form.
   form.submit();
}

  function getagentids() 
        { 
	
		var e = document.getElementById("empcode"); 
		var er=e.options[e.selectedIndex].value;
		var ddlArray= new Array();
		var ddl = document.getElementById('empcodelist');
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
			
			document.getElementById("empname").value=tt;
		}
		}


</script>
<script>
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
						addbutton.onclick="formhash(this.form, this.form.password);";
						 
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
</script>

<script type="text/javascript">
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
			addbutton.onclick="formhash(this.form, this.form.password);";
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
	  </script>
<script type="text/javascript" src="../../sha512.js"></script>
<script type="text/javascript" src="forms.js"></script>
<!--<script type="text/javascript" src="formup.js"></script>-->
<title>SSV || User Creation Master</title>
</head>
<?php /*?><?php
  $uri=$_SERVER['REQUEST_URI'];
  $page=substr($uri,strrpos($uri,"/")+1);
  //echo $page;
?><?php */?>
<?php 
 if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['userid'])){?>
 
 <body class="default" onLoad="document.myForm.password.focus()">

<? }else{?>


<body class="default" onLoad="document.myForm.userid.focus()">

 <? } 
}else{?>
<body class="default" onLoad="document.myForm.codes.focus()">

 <? } ?>


<center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
            <form name="myForm" id="frm1" method="POST" action="<?php $_PHP_SELF ?>" onSubmit="return validate()">
            
             <table  id="default" style="display:none;"  >
            <tr><td>
       <select name="empcodelist" id="empcodelist">
   
			 <?
                                                        
                $que = mysql_query("SELECT employeecode, employeename FROM employeemaster ORDER BY employeecode ASC ");
               
             while( $record = mysql_fetch_array($que))
             {     
              echo "<option value=\"".$record['employeecode']."~".$record['employeename']."\">".$record['employeecode']."~".$record['employeename']."\n ";                      
                                             
             }
            ?>
                                          </select>

        </td></tr>
</table>
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>User Creation Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:150px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>User ID</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                       
                        <?php if(!empty($_GET['userid'])) { ?>
                        
                        <input type="text" name="userid"  value="<?php echo $userid;?>" onKeyUp="nospaces(this)" readonly="readonly" style="border-style:hidden; background:#f5f3f1;"/>
						  
						   <? } else { ?>
                           
                                 <input type="text" name="userid"  value="<?php echo $userid;?>" onKeyUp="nospaces(this)" />
                                                           
                           <?php  }	  ?>
                                  
                               </div>
 							<!--Row1 end-->
                           
                             <!--Row1 -->  
                               <div style="width:150px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Password</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="password"  name="password" value="<?php echo $password;?>"  maxlength="15" onKeyPress="return validatee(event)"/>
                                 
                               </div>
 							<!--Row1 end-->
                            
                            <!--Row1 -->  
                             <div style="width:150px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Repeat password</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                 <input type="password"  name="repeatnewpassword" value="<?php echo $repeatnewpassword;?>" maxlength="15" onKeyPress="return validatee1(event)"/>
                               </div>
                               
 							<!--Row1 end-->
                            
                            
                                 <!--Row2 -->  
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:400px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">  
                           <div style="width:150px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Employee Code</label><label style="color:#F00;">*</label>
                               </div>
                             <!-- <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;"> -->
                                  <div class="ui-widget" style="width:200px; height:30px;  float:left; margin-top:5px; margin-left:3px;"> 
                                  
                                  
                                  
                                   <?php if(!empty($_GET['userid']))
						  {?>
						  <input type="text" name="empcode" id="empcode" readonly="readonly" value="<?=$empcode?>" style="border-style:hidden; background:#f5f3f1;"/>
						   <? } 
						  else { ?>
                                <select name="empcode" id="empcode" onChange="getagentids();">
    <option value="<?php echo $empcode;?>"><? if(!empty($empcode)){ echo $empcode;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT employeecode FROM employeemaster ORDER BY employeecode ASC");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {     
                                      echo "<option value=\"".$record['employeecode']."\">".$record['employeecode']."\n ";                      
                                                                     
                                     }
                                    ?>
                                          </select>
                                          <?php
						  }
						  ?>
                             </div>
                     
                               
                                <div style="width:150px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Employee Name</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="empname" id="empname" value="<?php echo $empname;?>" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" <?php /*?>onFocus="addbutton.focus()"<?php */?>/>
                               </div>                                     
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:150px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                                 
                            
                            </div>      
                     <!-- col3 --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                     <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:-100px;">
                             
					<div style="width:235px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
                          <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                       
                    <?php      if(!empty($_GET['userid']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton" onClick="updatefunc(this.form, this.form.password);">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" onClick="formhash(this.form, this.form.password);";>
				          <? } ?>
				           </div>
                           
                           <div style="width:100px; height:32px; float:left;margin-top:16px; margin-left:10px;">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div>                            
                                                   
				     </div>	
                         
                          <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:80px; height:30px; float:left; margin-left:20px; margin-top:16px;" >
                                  <label>User ID</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;">
                                  <input type="text" name="codes" onKeyPress="searchKeyPress(event);" id="codes" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:80px; height:30px; float:left; margin-left:20px; margin-top:16px;">
                                  <label>Employee Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;" >
                                  <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval'] ?>"/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:80px; height:32px; float:left; margin-top:16px; margin-left:10px;">
                            	<input type="submit" name="Search" id="Search" value="Search" class="button"/>	
                                <!-- <input type="submit" name="Search" id="Search" value="" class="button1"/> -->
                             </div>  
                          </div> 
                </div>
                
                <!--Main row 2 end-->
                
            
             <!-- form id start end-->    
              <div style="width:900px; height:auto; padding-bottom:8px; margin-top:-20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                   
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
     <td style="font-weight:bold;">User ID</td>
    <!-- <td style="font-weight:bold;">password </td>-->
     <td style="font-weight:bold;">Employee Code</td>
     <td style="font-weight:bold;">Employee Name</td></tr>
 
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
     <td style="font-weight:bold; text-align:center;"  bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" onChange="test();" value="<? echo $record['userid'];
 ?>"></td>
     <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
  <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center" > <a style="color:#0360B2" name="edit"  href="usercreation.php?<?php 
  echo 'userid=';echo $record['userid'];echo '&empcode='; echo $record['empcode'];echo '&empname=';echo $record['empname'];echo '&password=';echo $record['password']; ?>">Edit</a></td>
  <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF"><?=$record['userid']?></td>
    <td  bgcolor="#FFFFFF" ><?=$record['empcode']?></td>
    <?php $empqry= mysql_query("select employeename from employeemaster where employeecode='".$record['empcode']."' ")  ;
    $emprec = mysql_fetch_array($empqry); ?>
    <td  bgcolor="#FFFFFF" ><?=$emprec['employeename']?></td>
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
