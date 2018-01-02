<?php
		include '../../functions.php';// Include database connection and functions here.
		sec_session_start();
		require_once '../../masterclass.php';
		include("../../header.php");
		if(login_check($mysqli) == false) {
		header('Location:../../index.php');// Redirect to login page!
		} else
		{
		global $tname,$page,$configure,$timeoutvar,$nopass;
		$tname='pagination';
		$news = new News(); // Create a new News Object
		$newsRecordSet = $news->getNews($tname);
		$pagename = "configuration";
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
			alert("you are not allowed to do this action!",'pagination.php');
			</script>
			<?
			}
	
$selectqry =mysql_query( "select * from pagination");//Fetching previous Values from DB;
$prerowcount = mysql_num_rows($selectqry);
$preval = mysql_fetch_array($selectqry);
$page=$preval["page"];
$configure=$preval["configure"];
$timeoutvar=$preval["timeoutvar"];
$nopass=$preval["nopass"];
	
	
    if(isset($_POST['Update'])){ // If the submit button was clicked
	
    	if(!empty($_POST['page'])&&!empty($_POST['timeoutvar']) && !empty($_POST['timeoutvar']) )
		{ 
		   if(is_numeric($_POST['page']) && is_numeric($_POST['timeoutvar'])){
				if($prerowcount!=0)	{
					 date_default_timezone_set ("Asia/Calcutta");
						 $m_date= date("y/m/d : H:i:s", time());
					$test =mysql_query("UPDATE pagination SET page='".$_POST['page']."',configure='".$_POST['configure']."',timeoutvar='".$_POST['timeoutvar']."',nopass='".$_POST['nopass']."',user_id='".$_SESSION['username']."',m_date='".$m_date."'")or die("Update Error");
					$_SESSION['pagecount']=$_POST['page'];
					$_SESSION['confEmail']=$_POST['configure'];
					//$_SESSION['timeoutvar']=$_POST['timeoutvar'];
					?><script type="text/javascript">
					alert("Updated Sucessfully!",'pagination.php');
					</script> <? }
				else{
					$test =mysql_query("INSERT INTO pagination (page,configure,timeoutvar,nopass) VALUES ('".$_POST['page']."','".$_POST['configure']."','".$_POST['timeoutvar']."','".$_POST['nopass']."')")or die("Insert Error");
					$_SESSION['pagecount']=$_POST['page'];
					$_SESSION['confEmail']=$_POST['configure'];
					//$_SESSION['timeoutvar']=$_POST['timeoutvar'];
					?>
					<script type="text/javascript">
					alert("Saved Sucessfully!",'pagination.php');
					</script>
					<? 	}
			}else{
				?>
				<script type="text/javascript">
				alert("Enter only numerics for Page and Time out",'pagination.php');
				</script>
				<?  }
		}else{
			?>
			<script type="text/javascript">
			alert("Enter Mandatory Fields",'pagination.php');
			</script>
			<? }
	}
	//echo $timeoutvar; 
if(isset($_POST['Cancel'])){
	header('Location:pagination.php');
}

?> 
	 

<script type="text/javascript">
function validateForm()
{
var x=document.forms["form1"]["configure"].value;
var atpos=x.indexOf("@");
var dotpos=x.lastIndexOf(".");
if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
  {
	
  alert("Not a valid e-mail address");
   setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); configure.focus(); }, 2000);  
  return false;
  }
}
</script>

<title><?php echo $_SESSION['title']; ?> || Setting</title>
</head>
<?php /*?><?php
  $uri=$_SERVER['REQUEST_URI'];
  $pagee=substr($uri,strrpos($uri,"/")+1);
  //echo $page;
?><?php */?>
</head>

 <body class="default" onLoad="document.form1.page.focus()"><center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
			<form method="POST" name="form1" action="<?php $_PHP_SELF ?>">
            <div style="width:930px; height:auto;   min-height: 180px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">

                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Configuration Settings</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:350px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>No.of records in Page</label>
                               </div>
                              <div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="page" maxlength="2" value="<?php echo $page;?>" onChange="return codetrim(this),this.value = minmaxcon(this.value, 1, 100000000000)"/>
                               </div>
 							<!--Row1 end-->
<!--
                            <div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Email Configuration</label>
                               </div>
                              <div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="configure" id="configure"  value="<?php echo $configure;?>"  onChange="return codetrim(this)" onBlur="validateForm()"/>
                               </div>
-->
							       
                            <div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Time Out in mins</label>
                               </div>
                              <div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="timeoutvar" maxlength="2" value="<?php echo $timeoutvar;?>" onChange="return codetrim(this),this.value = minmaxcon(this.value, 1, 1000000000)"/>
                               </div>
							<div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>No.of wrong login Attempts Allowed</label>
                               </div>
                              <div style="width:150px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <input type="text" name="nopass" maxlength="2" value="<?php echo $nopass;?>" onChange="return codetrim(this),this.value = minmaxcon(this.value, 1, 1000000000)"/>
                               </div>
                                  </div>                             
                     <!-- col1 end --> 
                     
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                     <!--Main row 2 start-->
                <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:8px;">
                             
					<div style="width:340px; height:50px; float:left;  margin-left:4px; margin-top:0px;" id="center1">
                            
                           <div style="width:95px; height:32px; float:left;  margin-left:35px; margin-top:16px;">
						  <input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
				           </div>
                    <div style="width:85px; height:32px; float:left;  margin-left:75px;margin-top:16px;">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div>                     
                                                   
				     </div>	
                         
                          
                </div>
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
