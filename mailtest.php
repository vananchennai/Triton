<?php
session_start(); 
@require_once "Mail.php";
include("fheader.php");

global $host, $uid, $pass;
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
		mysql_connect($host,$uid,$pass) or die( mysql_error() ); 
					//     This says connect to the database or exit and
					//    give me the reason I couldn't connect.
		mysql_select_db($databname) or die( mysql_error() );



    $frommail= mysql_query("SELECT * FROM pagination");
	$rows = mysql_fetch_array($frommail);
	$fromeid=$rows['configure'];
	
	$empqry=mysql_query("SELECT DISTINCT(DCODE) FROM ztally_invoice  where Status=0");
	if (mysql_num_rows($empqry) > 0) //Employee id is present or not
	{
	  while($rowpurchase = mysql_fetch_array($empqry))
	   {
		$empcode=$rowpurchase['DCODE'];
		$userqry = mysql_query("SELECT Email,Franchisecode,Franchisename FROM franchisemaster WHERE Franchisecode = '" .$empcode. "'");
		$row1 = mysql_fetch_array($userqry);
	    $toAddress=$row1['Email'];	
        $employeename1=$row1['Franchisename'];			
		if (mysql_num_rows($userqry) == 1) //Employee id is present or not
		{
            $name="Fenner";//From Email ID
			$subject  = "Remainder Mail";			 
			$msg = "<html><body>&nbsp;<p>Dear ".$employeename1.",</p>";
			$msg.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Download your Purchase Invoice from the Tally Central Server.<br>";
			$msg.= "&nbsp;Thank You<br>";
			$msg.= "&nbsp;Fenner Support</body></html>";
		    $to="sankargowri55@gmail.com";
		    $headers = 'From:Fenner'."\r\n".'MIME-Version:1.0'."\r\n".'Content-type:text/html; charset=iso-8859-1'."\r\n".'X-Mailer: PHP/'.phpversion();
		    //echo $mail = mail("sankargowri55@gmail.com","Test",$msg,$headers);
		if(mail($toAddress,$subject,$msg,$headers))
		{
		echo "success";
		}
		else
		{
		 echo "Failed";
		}
		}
		}
	}
	




/* $msg="Test mail";
$to="sankargowri55@gmail.com";
$headers = 'From:sankargowri55@gmail.com'."\r\n".'MIME-Version:1.0'."\r\n".'Content-type:text/html; charset=iso-8859-1'."\r\n".'X-Mailer: PHP/'.phpversion();
echo $mail = mail("sankargowri55@gmail.com","Test",$msg,$headers);
if(mail("sankargowri55@gmail.com","Test",$msg,$headers)){
echo "success";
}else{
 echo "Failed";
} */

?>