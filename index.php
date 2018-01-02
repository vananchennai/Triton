<?
include 'functions.php';
sec_session_start();
session_start();
if ($_SESSION['username'] ==NULL or $_SESSION['username']=='')
{
	$_SESSION['mainfolder']='Triton';
	$_SESSION['title']='Triton';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script type="text/javascript" src="sha512.js"></script>
<script type="text/javascript" src="forms.js"></script>
<title><?php echo $_SESSION['title']; ?></title>
</head>
<body class="default" onLoad="document.login_form.userid.focus()"><center>

<div style="width:980px;" class="mod-container">
        <div style="width:980px; height:50px;">
        
        </div>
        
        <div style="width:980px; height:130px; float:none;">
           <div style="width:250px; height:130px; float:left; ">
          
           </div>
        </div>
        
        
        <div style="width:980px; height:350px; float:none;">
        		<div style="width:270px; height:350px; margin-left:10px; float:left;">
                
                </div>
                
                <div style="width:400px; height:350px; background-image:url(img/loginbg.png); margin-left:10px;float:left;">
                <!--   Form start here-->
                <form action="process_login.php" method="post" name="login_form">
                     <div style="width:369px; height:275px; margin-left:17px; margin-top:59px; float:left;">
                        <div style="width:321px; height:48px; background-image:url(img/uid.png); margin-left:27px; margin-top:30px; float:left;" class="log">
                        <input type="text" placeholder="User ID" onBlur="this.value=!this.value?'User ID':this.value;" onFocus="this.select()" onClick="this.value='';" name="userid" value="User ID">
                        </div>
						<div style="width:321px; height:48px; background-image:url(img/pw.png); margin-left:27px; margin-top:29px; float:left;" class="log">
                       <input type="password" placeholder="Password" onBlur="this.value=!this.value?'Password':this.value;" onFocus="this.select()" onClick="this.value='';" name="password" value="Password">
                        </div>
                           <div style="width:321px; height:48px;  margin-left:27px; margin-top:19px; float:left;">
                           <input class="logbutton" type="submit" value="Login" name="Login" onclick="formhash(this.form, this.form.password);">
                       <!--   <a href="#" style="">Forgot Password ?</a>-->
                           </div>
                         
<!--
                            <div style="width:321px; height:29px;  margin-left:27px; margin-top:15px; float:left;" class="logforgot"><a href="master/usersettings/forgetpassword.php"> I can't access my account </a>
                           </div>
-->
                     </div>  
                     
                <!--   Form end here-->  
                </form>
                </div>
                
                <div style="width:270px; height:350px; margin-left:10px;float:left;">
                
                </div>
        </div>
      
</div>

<!--
<div id="footer-wrap">
        <?php include("footer.php")?>
  </div>
-->

</center></body>

</html>
<?
}
else
{
header('Location:/Triton/home/home/master1.php');
} ?>

