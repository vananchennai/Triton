<?php

{
include 'functions.php';
sec_session_start();
session_start();

 // Our custom secure way of starting a php session. ghasgha
 // The hashed password from the form

//$password = $_POST['p']; 
//// Create a random salt
//$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
//// Create salted password (Careful not to over season)
//$password = hash('sha512', $password.$random_salt);
// 
//// Add your insert to database script here. 
//// Make sure you use prepared statements!
//if ($insert_stmt = $mysqli->prepare("INSERT INTO members (username, userid, password, salt) VALUES (?, ?, ?, ?)")) { 
//$insert_stmt->bind_param('ssss', $username, $userid, $password, $random_salt); 
//   // Execute the prepared query.
//   $insert_stmt->execute();
//}
if(isset($_POST['userid'], $_POST['p'])) 
{ 

$_SESSION['mainfolder']='Triton';



global $host, $uid, $pass,$checkstatus,$countstatus;
 $str ="";
 $data=array();
 //$uploadfile='log.txt';
$uploadfile= "rights.txt";
$file=fopen($uploadfile,"r") or exit("Unable to open file!");
//$file==fopen($uploadfile, "r");
while (!feof($file))
  {
 $str= $str.fgetc($file);
  }

list($host, $uid, $pass, $databname) = explode('~',trim($str));
fclose($file);
	if ($_SESSION['username'] ==NULL or $_SESSION['username']=='')
{
	$_SESSION["dbhostname"]=$host;
	$_SESSION["dbusername"]=$uid;
	$_SESSION["dbpassword"]=$pass;
	$_SESSION["databname"]=$databname;
	
	//$_SESSION['username']
    $username = $_POST['userid'];
    $password = $_POST['p']; // The hashed password.
   
  include 'Mysql.php';
  $news = new Mysql(); // Create a new News Object
	$newsRecordSet = $news->__construct();
	//***************************************************Validation Checking****************************************************************
	 $ValiddateQueary="Select Validdate from usercreation where id= '1'";
	
				$ValiddatResult = mysql_query($ValiddateQueary);
				 $ValiddatDetails = mysql_fetch_array($ValiddatResult);
				// var_dump($ValiddatDetails);
				
				 $ValidDateFormate=$ValiddatDetails['Validdate'];
				//exit;
				date_default_timezone_get('Asia/Kolkata');
				$CurrentDateFormat= date('Y-m-d');
			if ($ValidDateFormate >= $CurrentDateFormat || 1<2 ){
	//*************************************************************************************************************************************
	
	echo $ques = mysql_query("SELECT estatus,count FROM usercreation where userid ='".$username."'");
	
	$numrows1 = mysql_num_rows($ques );

				if($numrows1>0)
				{		
		$rows45 = mysql_fetch_array($ques)or die (mysql_error());
		$status=$rows45['estatus'];	
		$countstatus=$rows45['count'];
		}
		
		$ques2 = mysql_query("SELECT nopass FROM pagination ");
		$numrows3 = mysql_num_rows($ques2 );
				if($numrows3>0)
				{		
		$rows46 = mysql_fetch_array($ques2)or die (mysql_error());
		$checkstatus=$rows46['nopass'];
		}
	echo $mysqli->connect_errno;
//print "Check Process".'ps -ef | grep mysqld | grep -v grep';
		
   if(login($username, $password, $mysqli) == true) 
	{

      // Login success
	   // SELECT  `estatus` FROM  `usercreation` WHERE  `userid` =  'raja'
		if($countstatus>=$checkstatus)
		{
		?>
		    <script type="text/javascript">			
			alert("Your account is blocked , Please contact Admin");document.location='logout.php';			
            </script>
		<? 
		}
		else
		{
			
				
				
		
		
			mysql_query("update usercreation SET count=0 where userid='".$username."'");
			//echo $status;		
			if($status=="NEW")
			{
			header("Location:/".$_SESSION['mainfolder']."/master/usersettings/firstloginpasschg.php");
			}
			else
			{
			//header('Location:/amararaja/master/usersettings/forgetpassword.php');
			header("Location:/".$_SESSION['mainfolder']."/home/home/master1.php");
			}
		}
   } 
   else {
      // Login failedifelse
				//if($username='')
				//{	
				if($countstatus>=$checkstatus)
		{
		?>
		    <script type="text/javascript">			
			alert("Your account is blocked , Please contact Admin");document.location='logout.php';			
            </script>
			<? 
		}
		else
		{		
				$quescount = mysql_query("SELECT count FROM usercreation where userid ='".$username."'");
				$numrows = mysql_num_rows($quescount);
				if($numrows>0)
				{			
				$rowscount = mysql_fetch_array($quescount)or die (mysql_error());;
				$count=$rowscount['count'];

               					$count=$count+1;
				mysql_query("update usercreation SET count='".$count."' where userid='".$username."'");
				}
				//}
				?>
				<script type="text/javascript">			
				alert("Enter correct User ID and password!");document.location='logout.php';			
				</script>
				<? 

							
		}
   // header('Location: ./index.php');
   }
   }else{
   ?>
		    <script type="text/javascript">			
			alert("Your Validation is over");document.location='logout.php';			
            </script>
			<? 
			}
   
  }
else
	{
	$_SESSION["dbhostname"]=$host;
	$_SESSION["dbusername"]=$uid;
	$_SESSION["dbpassword"]=$pass;
	$_SESSION["databname"]=$databname;
	//$_SESSION['username']
  // $username = $_POST['userid'];
 //  $password = $_POST['p']; // The hashed password.

  include 'Mysql.php';
  $news = new Mysql(); // Create a new News Object
	$newsRecordSet = $news->__construct();
	
				//document.location='logout.php';
			header("Location:/".$_SESSION['mainfolder']."/home/home/master1.php");
	}

	
}
 else
	{ 
   // The correct POST variables were not sent to this page.
   echo 'Invalid Request';
   }
   
   }
?>