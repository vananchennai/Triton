<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{

 $tname = "userrights";
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	?>
 	<?php /*?>$pagename = "Password change Master";
	$validuser = $_SESSION['username'];
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");//$news->getNewsById($validuser,$pagename);
	$row = mysql_fetch_array($selectvar);
  
 	if (($row['viewrights'])== 'No')
	{
		?>
            <script type="text/javascript">
			alert("you are not allowed to view this page..!!");	document.location='/ARBLTCS/home/home/master1.php';
			</script>
         <?
	}
	if(isset($_POST['permiss'])) // If the submit button was clicked
    {
		?>
            <script type="text/javascript">
			alert("you are not allowed to do this action..!!",'changepassword.php');	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action..!!",'changepassword.php');	
			</script>
         <?
		
	}
 <?php */?>
 
 <?php
 
 $user = $_SESSION['username'];
//user is logged in
 
		if(isset($_POST['p'],$_POST['op']))
		{
		//check fields
 
		$oldpassword = $_POST['op'];
		$newpassword = $_POST['newpassword'];
		$repeatnewpassword = $_POST['repeatnewpassword'];
 		
		//check pass against db
		$queryget = mysql_query("SELECT * FROM usercreation WHERE userid='".$user."'" );
		$row = mysql_fetch_assoc($queryget);
		$salt= $row['salt'];
 		$oldpassword = hash('sha512', $oldpassword.$salt);
		$oldpassworddb = $row['password'];
 		
		//check pass
		if ($oldpassword==$oldpassworddb)
		{
		
		//check twonew pass
		if ($newpassword==$repeatnewpassword)
		{
		//success
		//change pass in db
		//check old and new pass
		if($_POST['oldpassword']!=$_POST['newpassword'] )
		{
		 
		 // check pass lenth
		 if (!(strlen($newpassword)>15||strlen($newpassword)<5))  // <---------------Here is the code
		{
		//check digit
		// if (preg_match('/[0-9a-zA-Z\'-@#$%^&+=]/', $newpassword))
		 if (preg_match('/[0-9]/', $newpassword) && preg_match('/[a-z]/', $newpassword) && preg_match('/[A-Z]/', $newpassword))
		{
		
			if (!(preg_match('/[-@#$%^&+=]/', $newpassword)))// special characters exist in the newpassword.
			{
			?>
		    <script type="text/javascript">
			alert("Password must have one special characters(-@#$%^&+=)...! Enter again",'changepassword.php');
			</script>
			<?
			}
		
			else
			{
				$encpassword= hash('sha512', $_POST['p'].$salt);
				mysql_query("update usercreation SET password='".$encpassword."',estatus='OLD' where userid='".$user."'");
			?>
		    <script type="text/javascript">
			alert("Your Password has been changed successfully..!",'changepassword.php');
			</script>
			<?
				//session_destroy();
				//die("Your pass has benn changed.<a href='index.php'>Return</a> to the main page");
			}
		}
		else
		{
			?>
		    <script type="text/javascript">
			alert("Please follow the password rule",'changepassword.php');
			</script>
			<?
		}
		}
		
		else
		{
			?>
		    <script type="text/javascript">
			alert("Please follow the password rule",'changepassword.php');
			</script>
			<?
		}
		}
		else
			{
			?>
		    <script type="text/javascript">
			alert("Old and New passwords are same..! Enter again",'changepassword.php');
			</script>
			<?
			}	
		}
		else
			{
			?>
		    <script type="text/javascript">
			alert("New and Repeat passwords are not the same..! Enter again",'changepassword.php');
			</script>
			<?
			}
 
 
 
		}
		else
			{
			?>
		    <script type="text/javascript">
			alert("Old Password doesn't match..! Enter again",'changepassword.php');
			</script>
			<?
			}
		}
 
		
		/*?>echo "
 
		<form action='changepassword.php' method='POST'>
			Old password:    <input type='text' name='oldpassword'><p>
			New password:	<input type='password' name='newpassword'><p><br>
			Repeat new password:	<input type='password' name='repeatnewpassword'><p>
			<input type='submit' name='submit' value='Change Password'>
 
		</form>
 
		";<?php */?>
		<script type="text/javascript" src="../../sha512.js"></script>
<script type="text/javascript" src="chform.js"></script>
	<title><?php echo $_SESSION['title']; ?> || Password Change Master</title>	
</head>
<?php /*?><?php
  $uri=$_SERVER['REQUEST_URI'];
  $page=substr($uri,strrpos($uri,"/")+1);
  //echo $page;
?><?php */?>
<body><center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form  method="POST" action="<?php $_PHP_SELF ?>">
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Password Change Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:300px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Old Password</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input placeholder="Old Password" onBlur="this.value=!this.value?'':this.value;" onFocus="this.select()" onClick="this.value='';" type="password" name="oldpassword" value="" <?php /*?><!--value="<? echo $oldpassword ?>"--><?php */?>/>
                               </div>
 							<!--Row1 end-->
                            
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>New Password</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input placeholder="New Password" onBlur="this.value=!this.value?'':this.value;" onFocus="this.select()" onClick="this.value='';" type="password" name="newpassword" value=""/>
                               </div>
 							<!--Row1 end-->
                            <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Repeat New Password</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input placeholder="Re- Password" onBlur="this.value=!this.value?'':this.value;" onFocus="this.select()" onClick="this.value='';" type="password" name="repeatnewpassword" value=""/>
                               </div>
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:600px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                         
    <div style="width:auto; height:10px; float:left; margin-top:30px; margin-left:3px;">
    <label style="color:#F00;">Password Rule:</label>
                               </div>
                              <div style="width:auto; height:60px;  float:left;  margin-top:5px; margin-left:3px;">
                                <label > <p>[ Password should be minimum of 5 to maximum of 15 characters.</p> 
										<p>Must contain at least one lower case letter,one upper case letter, one digit and one special character.</p>
										<p>Valid special characters are  - @#$%^&+=  ]</p></label>     
   </div>
 
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                          
                     <!-- col3 --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                   <!--Main row 2 start-->
                <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:-100px;">
                             
					<div style="width:230px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
                            <div style="width:95px; height:32px; float:left; margin-top:16px; margin-left:20px;">
						   <input name="Change" type="submit" class="button" value="Change" id="addbutton"  onClick="formhashchange(this.form, this.form.newpassword, this.form.oldpassword);"> 
				           </div>	
                                                                            
                           <div style="width:95px; height:32px; float:left;margin-top:16px;">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div> 
                </div>
                </div>
                
                <!--Main row 2 end-->
                
            </form>
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
 <?php
		
 

}
 
		
?>

