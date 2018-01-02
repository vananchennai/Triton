<?php

session_start(); 
 /*$to = "sairammani@yahoo.co.in";
 $subject = "Hi!";
 $body = "Hi,\n\nHow are you?";
 if (mail($to, $subject, $body)) {
   echo("<p>Message successfully sent!</p>");
  } else {
   echo("<p>Message delivery failed...</p>");

  }*/
@require_once "Mail.php";
include("../../fheader.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/style.css" />
<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<link href="../../css/grid.css" rel="stylesheet" type="text/css">
<link href="../../css/reveal.css" rel="stylesheet" type="text/css">
<link href="../../css/A_red.css" type="text/css" rel="stylesheet" />
<link href="../../css/pagination.css" type="text/css" rel="stylesheet" />
<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery.reveal.js"></script>
<script type="text/javascript" src="../../sha512.js"></script>
<script type="text/javascript" src="form.js"></script>

<?php
global $host, $uid, $pass;
 $str ="";
 $data=array();
 //$uploadfile='log.txt';
$uploadfile= "../../rights.txt";
$file=fopen($uploadfile,"r") or exit("Unable to open file!");
//$file==fopen($uploadfile, "r");
while (!feof($file))
  {
 $str= $str.fgetc($file);
  }

list($host, $uid, $pass, $databname) = explode('~',trim($str));
mysql_connect($host,$uid,$pass) or die( mysql_error() ); 
            //     This says connect to the database or exit and
            //    give me the reason I couldn't connect.
mysql_select_db($databname) or die( mysql_error() );
//$news = new News(); // Create a new News Object
//$newsRecordSet = $news->getNews();
if(!isset($_POST['p']))
{
		$length = 8;
    	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   		 $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
		} $_SESSION['ramdompass'] = $result;
		
}
if(isset($_POST['p']))
{
	$newpassword=$_POST['p'];
	$frommail= mysql_query("SELECT * FROM pagination");
	$rows = mysql_fetch_array($frommail);
	$fromeid=$rows['configure'];
	if (!empty($_POST['forgetpassword'])) //Empty value
	{
	$toeid = $_POST['forgetpassword'];  
	$empqry=mysql_query("SELECT * FROM `usercreation` WHERE `userid` = '" .$toeid. "'");
	
	if (mysql_num_rows($empqry) == 1) //Employee id is present or not
	{
		$row = mysql_fetch_array($empqry);
		$empcode=$row['empcode'];
		$salt=$row['salt'];
		$userqry = mysql_query("SELECT email,employeename FROM employeemaster WHERE employeecode = '" .$empcode. "'");
		$row1 = mysql_fetch_array($userqry);
	    $toAddress=$row1['email'];	
$employeename1=$row1['employeename'];		
		if (mysql_num_rows($userqry) == 1) //Employee id is present or not
		{
			
			$encpassword= hash('sha512', $newpassword.$salt);
			mysql_query("update usercreation SET password='".$encpassword."',estatus='NEW',count=0 where empcode = '" .$empcode. "'");
			
			$name="ARBL";//From Email ID
	
			$subject  = "Password Details";			 
			$msg = "Dear ".$employeename1.", \r \n \n \t";  
			$msg.= "Your temporary password is :  ". $_SESSION['ramdompass']."\r \n \t";
			$msg.= "Change your password once you logged into the Tally Central Server. \r \n \t";
			$msg.= "Thank You \r \n \t";
			$msg.= "ARBL Support";
		
		
					$host = $host;
					
					$username = $uid;
					$password = $pass;
					$headers = array ('From' => $fromeid,
					'To' => $toAddress,
					'Subject' => $subject
					);
					
					$smtp = Mail::factory('smtp',
					array ('host' => $host,
					'auth' => false,
					'username' => $username,
					'password' => $password));
					
					
					$mail = $smtp->send($toAddress, $headers, $msg);
					
					if (PEAR::isError($mail)) 
					{				
					echo("<p>" . $mail->getMessage() . "</p>");
					} 	
					else 
					{
					 ?>
					<script type="text/javascript">
					alert("Your Password details is sent to your mail. Please check it out",'forgetpassword.php');
					</script> 
			<?
					}
		
			/*if  (mail($toeid, $subject, $msg, "From: $fromeid\r\nReply-To: $fromeid\r\nReturn-Path: $fromeid"))
			{
			 ?>
					<script type="text/javascript">
					alert("Your Password details is sent to your mail. Please check it out",'forgetpassword.php');
					</script> 
			<?
			}
			else  
			{
			?>
					<script type="text/javascript">
					alert("Sorry!! Email is not sent, please try again after sometime. Thank you",'forgetpassword.php');
					</script> 
			<?
			}*/
		}
		else  
		{
		?>
					<script type="text/javascript">
				alert("Your User ID doesn't match ARBL Employee list, Please contact Admin.",'forgetpassword.php');
					</script> 
		<?
		}
	}
	else
	{
		//echo "We couldnt match the Email ID you entered with information in our database. Try entering your Email ID again.";
		 ?>
				<script type="text/javascript">
				alert("Enter a valid User ID",'forgetpassword.php');
				</script> 
		<?
	}
	}
	else
	{
		//echo "We couldnt match the Email ID you entered with information in our database. Try entering your Email ID again.";
		 ?>
				<script type="text/javascript">
				alert("Enter your User ID",'forgetpassword.php');
				</script> 
		<?
	}
}

 ?>
<script type="text/javascript">
function trim (el) {
    el.value = el.value.
       replace (/(^\s*)|(\s*$)/gi, ""). // removes leading and trailing spaces
       replace (/[ ]{2,}/gi," ").       // replaces multiple spaces with one space 
       replace (/\n +/,"\n");           // Removes spaces after newlines
    return;
}

var emailfilter=/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i

function checkmail(e){
var returnval=emailfilter.test(e.value)
if (returnval==false){
alert("Please enter a valid email address.")
e.select()
}
}

</script>


<title><?php echo $_SESSION['title']; ?> || Forgot Password</title>
</head>

<body><center>

<!--First Block - Logo-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:130px; float:none;">
           <div style="width:250px; height:130px; float:left; background-image:url(../../img/logo_amaron.png);">
          
           </div>
     </div>       
</div>
<!--First Block - End-->


<!--Second Block - Menu-->
<div style="width:100%; height:50px; float:none; background:url(../../img/menubg.jpg) repeat-x;">
     <div style="width:980px; height:50px; float:none;"> 
       
     </div>       
</div>
<!--Second Block - Menu -End -->

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>">
            <div style="width:930px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:40px; float:left;  margin-left:0px;" class="head">
	<div  align="left"><p align="left">Forgot Password</p></div>
	<div align="right"> <a href="../../logout.php"> Go to Login again</a></div>
              </div>  
                        
                        
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:900px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <div style="width:200px; height:32px; float:left; margin-top:16px; margin-left:15px;">
						 
                           	
                              
                           <div style="width:550px; height:50px; float:left;  margin-left:155px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:120px; height:40px; float:left; margin-left:3px; margin-top:10px;" >
                                 <h1 style="size:60px;"> <label>Enter User ID </label><label style="color:#F00;">*</label></h1>
                               </div>
                               <div style="width:240px; height:30px;  float:left; margin-left:1px; margin-top:16px;">
                                <!-- <input name="forgetpassword" type="text" id="forgetpassword" onKeyPress="return trim(this)" onChange="return checkmail(this)" />-->
                                   <input name="forgetpassword" type="text" id="forgetpassword" />
                             </div>
                                  <div style="width:70px; height:30px; float:right; margin-right:80px; margin-top:16px;">
                                 <input type="submit" name="submit" value="Submit" class="button" onClick="formhash(this.form,'<?=$_SESSION['ramdompass']?>' );"/> 
                                 </div>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                              
                              
                            
                          </div> 
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                                                      
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                             
                     <!-- col3end --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                <!--Main row 2 start-->

		<!--Main row 2 end-->
                
              <!--  grid start here-->

       <!--  grid end here-->
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


