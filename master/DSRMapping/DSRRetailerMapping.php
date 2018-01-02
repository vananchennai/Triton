<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) 
{ 
  header('Location:../../index.php');// Redirect to login page!
} 
else
{
	global $dsrcode,$dsrlocation,$Franchisecode,$tname,$scode,$sname;
    $scode = 'dsrcode';
	$sname = 'dsrlocation';
	$tname = "retailerdsrmapping";
	require_once '../../searchfun.php';
	require_once '../../paginationfunction.php';
	$news  = new News(); // Create a new News Object

		
	$_SESSION['type']=NULL;
	$dsrlocationmaster='select * from retailerdsrmapping';
	
	$pagename  = "dsrretailermapping";//"productgroupmaster";
	$validuser = $_SESSION['username'];
	$selectvar = mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");//$news->getNewsById($validuser,$pagename);
	$row       = mysql_fetch_array($selectvar);
  	
 	if (($row['viewrights'])== 'No')
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
	if(isset($_POST['permiss'])) // If the submit button was clicked
    {
		?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'DSRRetaillerMapping.php');
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
		?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'DSRRetaillerMapping.php');
			</script>
         <?
	}//Page Verification Code and User Verification

	//Save
    if(isset($_POST['Save'])) // If the submit button was clicked
    {
		$_SESSION['codesval']=NULL;
		$_SESSION['namesval']=NULL;
		$post['Franchisecode'] = strtoupper(str_replace('&', 'and',$_POST['Franchisecode']));
		$post['retailername'] = strtoupper(str_replace('&', 'and',$_POST['retailername']));
		$post['retailercode'] = strtoupper(str_replace('&', 'and',$_POST['retailercode']));
        $post['dsrcode'] = strtoupper(str_replace('&', 'and',$_POST['dsrcode']));
		$post['dsrname'] = $_POST['dsrname'];
		$post['dsrlocation'] = strtoupper(str_replace('&', 'and',$_POST['dsrlocation']));
		

        // This will make sure its displayed
		
		$Franchisecode=$post['Franchisecode'];
		$retailername =$post['retailername'];
		$retailercode=$post['retailercode'];
		$dsrname =$post['dsrname'];
		$dsrcode=$post['dsrcode'];
		$dsrlocation =$post['dsrlocation'];
        
		if(!empty($_POST['Franchisecode'])&&!empty($_POST['retailername'])&&!empty($_POST['dsrcode']))
		{   
		$p1=strtoupper( preg_replace('/\s+/', '',$post['dsrcode']));
		$p2=strtoupper( preg_replace('/\s+/', '',$post['dsrlocation']));
		$p3=strtoupper( preg_replace('/\s+/', '',$post['retailername']));
		$p4=strtoupper( preg_replace('/\s+/', '',$post['retailercode']));
		$p5=strtoupper( preg_replace('/\s+/', '',$post['dsrname']));
		
		
	$cnduplicate=0;
	/*
	$repqry="SELECT REPLACE( `dsrcode` ,  ' ',  '' ) AS dsrcode, REPLACE(  `dsrlocation` ,  ' ',  '' ) AS dsrlocation FROM dsrcodemapping where dsrlocation = '".$p2."' or dsrlocation = '".$post['dsrlocation']."' or dsrcode = '".$p2."' or dsrcode = '".$post['dsrlocation']."' or dsrcode = '".$p1."' or dsrcode = '".$post['dsrcode']."' or dsrlocation = '".$p1."' or dsrlocation = '".$post['dsrcode']."'";
	 */
	 $repqry="SELECT REPLACE( `dsrcode` ,  ' ',  '' ) AS dsrcode, REPLACE(  `dsrlocation` ,  ' ',  '') AS dsrlocation FROM retailerdsrmapping where (dsrcode = '".$post['dsrcode']."' and retailername = '".$post['retailername']."' and Franchisecode= '".$_POST['Franchisecode']."')  or dsrcode = '".$p2."' or dsrcode = '".$post['dsrlocation']."' or dsrlocation = '".$p1."' or dsrlocation = '".$post['dsrcode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
			
		if($cnduplicate>0 || ($post['dsrcode'] == $post['dsrlocation']) )
		{
		?>
            <script type="text/javascript">
			alert("Duplicate entry!");
			</script>
         <?
		}
			else
			{
		$post['user_id'] = $_SESSION['username'];
		/* date_default_timezone_set ("Asia/Calcutta");
		$post['m_date']= date("y/m/d : H:i:s", time()); */
		
		//$postupdate['franchisecode'] = strtoupper(str_replace('&', 'and',$_POST['Franchisecode']));
		//$postupdate['retailername'] = strtoupper(str_replace('&', 'and',$_POST['retailername']));
		$postupdate['tertiary_code'] = strtoupper(str_replace('&', 'and',$_POST['dsrcode']));
		$postupdate['location'] = strtoupper(str_replace('&', 'and',$_POST['dsrlocation']));
		$postupdate['dsrname'] = strtoupper(str_replace('&', 'and',$_POST['dsrname']));
		$wherecon= "franchisecode ='".$_POST['Franchisecode']."' and retailername ='".$post['retailername']."'";
		$news->editNews($postupdate,"r_salesreport",$wherecon);
		$news->editNews($postupdate,"r_salesreturn",$wherecon);
		
		$news->addNews($post,$tname);
			?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'DSRRetailerMapping.php');
			</script>
            <?
			}
        }
	
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");
			</script>
            <?
		}
    }
  if(isset($_POST['Update'])) // If the submit button was clicked
    {
		$_SESSION['codesval']=NULL;
		$_SESSION['namesval']=NULL;
		$post['Franchisecode'] = strtoupper(str_replace('&', 'and',$_POST['Franchisecode']));
		$post['retailername'] = strtoupper(str_replace('&', 'and',$_POST['retailername']));
		$post['retailercode'] = strtoupper(str_replace('&', 'and',$_POST['retailercode']));
        $post['dsrcode'] = strtoupper(str_replace('&', 'and',$_POST['dsrcode']));
		$post['dsrname'] = $_POST['dsrname'];
		$post['dsrlocation'] = $_POST['dsrlocation'];
        // This will make sure its displayed
		
		
  if(!empty($_POST['Franchisecode'] &&!empty($_POST['retailername']) &&!empty($_POST['dsrcode'])))
	{ 	
		$codenamedcheck=0;
	if($_SESSION['pgroupval'] !=$dsrlocation)
		{
		$p1=strtoupper( preg_replace('/\s+/', '',$post['dsrcode']));
		$p2=strtoupper( preg_replace('/\s+/', '',$post['dsrlocation']));
		$p3=strtoupper( preg_replace('/\s+/', '',$post['retailername']));
		$p4=strtoupper( preg_replace('/\s+/', '',$post['retailercode']));
		$p5=strtoupper( preg_replace('/\s+/', '',$post['dsrname']));
		
		$repqry="SELECT REPLACE(  `retailername` ,  ' ',  '' ) AS retailername  FROM  retailerdsrmapping where (dsrcode = '".$post['dsrname']."') or (dsrcode = '".$post['dsrlocation']."') or dsrcode = '".$p5."' or retailername = '".$p4."'";
		$repres= mysql_query($repqry) or die (mysql_error());
		$codenamedcheck=mysql_num_rows($repres);
		}	
	if($codenamedcheck>0 ||($post['dsrlocation']==$post['dsrcode']))
		{
		?> 
		<script type="text/javascript">	alert("Duplicate entry!"); </script> 
		<?
		}
	else
		{
			try
			{
			$post['user_id'] = $_SESSION['username'];
			date_default_timezone_set ("Asia/Calcutta");
			$post['m_date']= date("y/m/d : H:i:s", time());
			$wherecon= "Franchisecode ='".$post['Franchisecode']."' and retailercode ='".$post['retailercode']."'";
			

			//$posteditupdate['retailername'] = strtoupper(str_replace('&', 'and',$_POST['retailername']));
			$posteditupdate['tertiary_code'] = strtoupper(str_replace('&', 'and',$_POST['dsrcode']));
			$posteditupdate['location'] = strtoupper(str_replace('&', 'and',$_POST['dsrlocation']));
			$posteditupdate['dsrname'] = strtoupper(str_replace('&', 'and',$_POST['dsrname']));
			echo $wherecons= "franchisecode ='".$_POST['Franchisecode']."' and retailername ='".$post['retailername']."'";
			
			$news->editNews($posteditupdate,"r_salesreport",$wherecons);
			$news->editNews($posteditupdate,"r_salesreturn",$wherecons);
			
		
			$news->editNews($post,$tname,$wherecon);
			unset($_SESSION['pgroupval'] );	
			?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'DSRRetailerMapping.php');
			</script>
            <?
			}
			catch (Exception $e) 
			{
			echo 'Caught exception: ',  $e->getMessage(), "\n";
			}		
		}
	
	}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");
			</script>
            <?
		}
	}
	
/// EDIT LINK FUNCTION 
	if(!empty($_GET['edi']) && !empty($_GET['retname']) && $_GET['edi'] !='NULL')
	{
	$prmaster =$_GET['edi'];
	$rname=$_GET['retname'];
	$_SESSION['codesval']=NULL;
	$_SESSION['namesval']=NULL;
	$result=mysql_query("SELECT * FROM retailerdsrmapping WHERE retailername ='".$rname."' and Franchisecode ='".$prmaster."'");
	$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

	if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'DSRRetailerMapping.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		   $dsrcode = $myrow['dsrcode'];
		   $dsrlocation = $myrow['dsrlocation'];	
		   $Franchisecode = $myrow['Franchisecode'];
		   $dsrname = $myrow['dsrname'];
		   $retailercode = $myrow['retailercode'];
		   $retailername = $myrow['retailername'];
		}
		 $_SESSION['pgroupval'] = $myrow['dsrlocation'];
		}
		$prmaster = NULL;
   }

	
	// Check if delete button active, start this 

if(isset($_POST['Delete']))
  {
	if(!isset($_POST['checkbox']))
	{
			?>
		    <script type="text/javascript">
			alert("Select data to delete!");
			</script>
			<?
	}

   else
    {
	
		$checkbox = $_POST['checkbox']; //from name="checkbox[]"
		$countCheck = count($_POST['checkbox']);
		for($i=0;$i<$countCheck;$i++)
		{
			$dataval=$checkbox[$i];
			$pieces = explode("~",$checkbox[$i]);
			$wherecon= "Franchisecode ='".$pieces[0]."' and retailername ='".$pieces[1]."'";
			$news->deleteNews($tname,$wherecon);
			?>
			<script type="text/javascript">
			alert("Deleted  Successfully!",'DSRRetailerMapping.php');
			</script>		
			<?
		}
		}
	}

//search pagination and defaut values in table


if(isset($_POST['Cancel']))
{
	$_SESSION['codesval']=NULL;
	$_SESSION['namesval']=NULL;
	header('Location:DSRRetailerMapping.php');
}
//$productmaster = "SELECT * FROM dsrretailermapping";
if(isset($_POST['Excel']))
{
$productmaster = "SELECT * FROM retailerdsrmapping where dsrcode='' or dsrname='' or dsrlocation='' or retailercode=''";
$_SESSION['type']='TallyExcel';
$_SESSION['query']=$productmaster;
header('Location:ExportDSRRetailerMapping.php');

}

if(isset($_POST['PDF']))
{
//	header('Content-type: application/vnd.ms-excel');
//    header("Content-Dispos..ition: attachment; filename=test.xls");
//    header("Pragma: no-cache");
//    header("Expires: 0");

$select=$_POST['Type'];
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		
		$condition="SELECT * FROM retailerdsrmapping WHERE dsrcode like'".$_POST['codes']."%' AND dsrlocation like'".
		$_POST['names']."%'order by m_date DESC";
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$search1=$_POST['codes'];
		//$search2=$_POST['names'];
		$condition="SELECT * FROM retailerdsrmapping WHERE dsrcode like'".$_POST['codes']."%'order by m_date DESC"; 
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		//$search1=$_POST['codes'];
		$search2=$_POST['names'];
		$condition="SELECT * FROM retailerdsrmapping WHERE dsrlocation like'".$_POST['names']."%'order by m_date DESC";
	}
	else
	{
		
		$condition="SELECT * FROM retailerdsrmapping order by m_date DESC";
	}
	$dsrlocationmaster=$condition;
	
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$dsrlocationmaster;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($dsrlocationmaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
$stringData =$myrecord['Franchisecode']."\t ;".$myrecord['retailername']."\t;".$myrecord['retailercode']."\t;".$myrecord['dsrcode']."\t;".$myrecord['dsrname']."\t;".$myrecord['dsrlocation']."\t\n";
		fwrite($fh, $stringData);
	}
//	
	fclose($fh);
	header('Location:ExportDSRRetailerMapping.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$dsrlocationmaster;
	header('Location:ExportDSRRetailerMapping.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$dsrlocationmaster;
	header('Location:ExportDSRRetailerMapping.php');
}
	
}	
?>

<script type="text/javascript">

function validatedsrcode(key)
{
var object = document.getElementById('dsrcode');
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
function validate(key)
{
var object = document.getElementById('dsrlocation');
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


</script>

<!--<script type="text/javascript">
$(document).ready(function(){
	$('.mine').click(function(){
		$('#dsrcode').focus();
	});
});
</script>-->

<script type="text/javascript">
 $(document).ready(function(){
    $('a.doSomething').click(function(){
       
        });
 });
</script>


<title><?php echo $_SESSION['title']; ?> || DSR Retailer Mapping Master</title>
</head>

 <?php
  
 if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
  if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.retailercode.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.Franchisecode.focus()">

 <? }
}
 else
 { ?>
 <body class="default" onLoad="document.form1.codes.focus()">
 <? }
 ?>
 <center>



<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center"> 
           <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1"> 
		   <table id="default" style=" height:10px; Display:None;" >
            <tr>
                <td>
                                    <select  name="dsrlist" id="dsrlist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT Franchisecode,dsrcode,dsrname,dsrlocation FROM dsrcodemapping");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
                          
                                      echo "<option value=\"".$record['Franchisecode']."~".$record['dsrcode']."~".$record['dsrname']."~".$record['dsrlocation']."\">".$record['Franchisecode']."~".$record['dsrcode']."~".$record['dsrname']."~".$record['dsrlocation']."\n "; 
                    }
                                   
                                    ?>
                                          </select>
                                      </td>
            </tr>
		</table>
            <!-- form id start-->
            <div style="width:930px; height:auto;   min-height: 150px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;" class="mine">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>DSR Retailer Mapping Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
						   <!--Row1-->  
                               <div style="width:145px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label>Distributor code</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <select name="Franchisecode" id="Franchisecode" onChange="dsrdrpfunc();">
								       
                                       
									   <option value="<?PHP if(!empty($Franchisecode)){ echo $Franchisecode; }else{?>0<? } ?>"><? if(!empty($Franchisecode)){ echo $Franchisecode; }else{?> ----Select---- <? } ?></option>
                                     <?
                                         if(!empty($_GET['edi']))
										{ 
											$que = mysql_query("SELECT Franchisecode FROM franchisemaster WHERE Franchisecode!='".$Franchisecode."' order by Franchisecode asc");
										}
										else
										{
											$que = mysql_query("SELECT Franchisecode FROM franchisemaster order by m_date asc");
										}

                                     while( $record = mysql_fetch_array($que))
                                     { 
									  if($Franchisecode !=$record['Franchisecode'] )
									  {
                                       echo "<option value=\"".$record['Franchisecode']."\">".$record['Franchisecode']."\n ";
                                      }
									 }
                                    ?>
                                    </select>
                               </div>
							   <!--Row1 End-->
							   
<!--<SCRIPT>
//var listdsr;
//$('#Franchisecode').change(function()
//{
	//var listdsr = $( "#Franchisecode" ).val();//$(this).text();
	 //alert(listdsr);
//});
</SCRIPT>-->
							   
							   <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Retailer Name </label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?php if(!empty($_GET['edi']))
							{?>
                            <input type="text" name="retailername" id="retailername" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?php echo $retailername ;?>"/>
                            <? } 
							else { ?>
                                  <input type="text" name="retailername" id="retailername" value="<?php echo $retailername ;?>" maxlength="50"  style="text-transform:uppercase" />
                                   <?
							}
							?>
                              
                               </div>
 							<!--Row2 end--> 
							 <!--Row3 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Retailer Code </label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              
                                  <input type="text" name="retailercode" id="retailercode" value="<?php echo $retailercode ;?>" maxlength="15" onKeyPress="return validatedsrcode(event)" onChange="return codetrim(this)" style="text-transform:uppercase" />
                              
                               </div>
 							<!--Row3 end--> 
							
							   
                             <!--Row4 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>DSR Code </label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
							  
                              <select name="dsrcode" id="dsrcode" onChange="dsrdrpfunction();">
								       
                                       
									   <option value="<?php  if(!empty($dsrcode)){ echo $dsrcode; }else{?>0<?}?>"><? if(!empty($dsrcode)){ echo $dsrcode; }else{?> ----Select---- <? } ?></option>
                                     <?
                                         if(!empty($_GET['edi']))
										{ 
											$que = mysql_query("SELECT dsrcode FROM dsrcodemapping WHERE dsrcode!='".$dsrcode."' order by dsrcode asc");
										}
										else
										{
											$que = mysql_query("SELECT dsrcode FROM dsrcodemapping order by m_date asc");
										}

                                     while( $record = mysql_fetch_array($que))
                                     { 
									  if($dsrcode !=$record['dsrcode'] )
									  {
                                       echo "<option value=\"".$record['dsrcode']."\">".$record['dsrcode']."\n ";
                                      }
									 }
                                    ?>
                                    </select>
									
                               </div>
 							<!--Row4 end--> 
                            
							<!--Row5 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>DSR Name</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  
								  <input type="text" name="dsrname" id="dsrname" value="<?php echo $dsrname ;?>" maxlength="30" onKeyPress="return validatedsrcode(event)" onChange="return codetrim(this)" style="text-transform:uppercase" />
                                   
                              
                               </div>
 							<!--Row5 end--> 
							
                            <!--Row6 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>DSR Location</label>
                               </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                             
                                  <input type="text" name="dsrlocation" id="dsrlocation" value="<?php echo $dsrlocation ;?>" maxlength="30" onKeyPress="return validatedsrcode(event)" onChange="return codetrim(this)" style="text-transform:uppercase" />
                                   
                               </div>
                             <!--Row6 end-->  
							 
                            
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                      
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                            </div>      
                     <!-- col3 --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
               <!--Main row 2 start-->
                <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:8px;">
                             
					<div style="width:235px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
                          <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                    <?php      if(!empty($_GET['edi']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" >
				         
						<?php /*?> <!--  <input name="<?php if(($row['deleterights'])=='Yes') echo 'Delete'; else echo 'permiss'; ?>" type="submit" id="delbutton"  class="button" value="Delete"  style="visibility:hidden;" >--><?php */?>
                           <? } ?>
				           </div>
                           
                           <div style="width:100px; height:32px; float:left;margin-top:16px; margin-left:10px;">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div>                            
                                                   
				     </div>	
                         
                          <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:120px; height:30px; float:left; margin-left:3px; margin-top:14px;" >
                                  <label>DSR Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);"  value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:90px; height:30px; float:left; margin-left:15px; margin-top:14px;">
                                  <label>DSR Location</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                  <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
                               </div>
                             <!--Row2 end-->
                            
                             
                             <div style="width:83px; height:32px; margin-left:15px; float:left; margin-top:16px;">
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>	
                               </div>  
                          </div> 
                </div>
                
                <!--Main row 2 end-->
             <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px;" class="grid">
                   
                  <table align="center" class="sortable" bgcolor="#FF0000" border="1" width="900px" id="datatable1">
   <?  if(($row['deleterights'])=='Yes')
	 {
 ?>  
     <td class="sorttable_nosort" style=" font-weight:bold; text-align:center" width="8px"> 
     <input type='checkbox' name='checkall' id="checkall" onclick='checkedAll(frm1);'></td> 
	 <? 
	 }
	  if(($row['editrights'])=='Yes') 
	  { 
	 ?>
     <td class="sorttable_nosort"  style=" font-weight:bold; text-align:center" >Action</td>
     <? 
	  } 
	  ?>
     <td style=" font-weight:bold;">Distributor Code</td>
	 <td style=" font-weight:bold;">Retailer Name</td>
	 <td style=" font-weight:bold;">Retailer Code</td>
	 <td style=" font-weight:bold;">DSR Code</td>
	 <td style=" font-weight:bold;">DSR Name</td>
     <td style=" font-weight:bold;">DSR Location</td>

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
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" onChange="test();" id="checkboxs" value="<? echo $record['Franchisecode']."~".$record['retailername']; ?>" ></td> 
	 <?
	  } 
	 if(($row['editrights'])=='Yes')
	  { 
	 ?>
     
 	 <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center""> <a style="color:#0360B2" name="edit" href="DSRRetailerMapping.php?edi=<?=$record['Franchisecode']?>&retname=<?= $record['retailername']?>" class="doSomething">Edit</a></td>
      <? 
	  } 
	  ?>
	 <td  bgcolor="#FFFFFF" align="left">
		   <? 
		   $par= mysql_query("select Franchisecode from retailerdsrmapping where dsrcode='".$record['dsrcode']."' ")  ;
			$record11 = mysql_fetch_array($par); 
		    echo $record11['Franchisecode'];
		   ?>
    </td>
	    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['retailername']?>
    </td>
	    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['retailercode']?>
    </td>
	    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['dsrcode']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['dsrname']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['dsrlocation']?>
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
		<? echo '<tr ><td colspan="11" align="center" bgcolor="#FFFFFF" style="color:#F00"  >No Records Found</td></tr>'; ?>	
<? } }?>
</table>

<?php include("../../paginationdesign.php");
  echo $listdsr;?>
             <div style="width:400px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
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
                               <div style="width:63px; height:32px; float:left; margin-top:18px;">
             					  <input type="submit" name="PDF" value="Export" class="button"/>
                               </div >
							   <div style="width:135px; height:32px; float:right; margin-top:18px;">
				   <input type="submit" name="Excel" value="ExportUnMapped" class="button"/>
                   </div>
							   </div>
        </div>

<script>
// all scripts used to eliminate duplication in dropdown.
			 
                                    // Set the present object
                                    var present = {};
                                    $('#Franchisecode option').each(function(){
                                    // Get the text of the current option
                                    var text = $(this).text();
									
                                    // Test if the text is already present in the object
                                    if(present[text]){
                                    // If it is then remove it
									
                                    $(this).remove();
                                    }else{
                                    // Otherwise, place it in the object
                                    present[text] = true;
                                    }
                                    });
									
//To Select All the DSRcode  based on  their selected values based on franchisee

function SortOptions(id) 
{
    var prePrepend = "#";
    if (id.match("^#") == "#") prePrepend = "";
    $(prePrepend + id).html($(prePrepend + id + " option").sort(
        function (a, b) { return a.text == b.text ? 0 : a.text < b.text ? -1 : 1 })
    );
}




//To Select All the DSRcode  based on  their selected values based on franchisee

function dsrdrpfunc()
{

	var e = document.getElementById("Franchisecode");
	var RetailerCodelist = document.getElementById('dsrlist');	
	var RetailerName = document.getElementById('dsrcode');
	var distributor = $("#Franchisecode").val();
	RetailerName.options.length = 0;
	
	if(distributor != "0" )
	{
		for (i = 0; i < RetailerCodelist.options.length; i++) 
			{
				var optvalue = RetailerCodelist.options[i].value;
				var lstval=optvalue.split("~");
				if(lstval[0]==distributor)
				{
					RetailerName.options.add(new Option(lstval[1],lstval[1]));
				}
			}
	}
	else
	{
		for (i = 0; i < RetailerCodelist.options.length; i++) 
			{
				var optvalue = RetailerCodelist.options[i].value;
				var lstval=optvalue.split("~");
				RetailerName.options.add(new Option(lstval[1],lstval[1]));
			}
		
	}
		RetailerName.options.add(new Option(' ----Select---- ','0'));
		var usedNames = {};
			$("select[id='dsrcode'] > option").each(function () {
			if(usedNames[this.text] || this.text=='') {
			$(this).remove();
			} else {
			usedNames[this.text] = this.value;
			}
			});
		SortOptions("dsrcode");		
}

	//To Select the DSRLocation and DSRName based on  their selected values based on DSRcode

		function dsrdrpfunction()
		{
			var e = document.getElementById("dsrcode");
			var RetailerCodelist = document.getElementById('dsrlist');	
			var DSRName = document.getElementById('dsrname');
			var DSRLocation = document.getElementById('dsrlocation');
			var distributor = $("#dsrcode").val();

			if(distributor != "0")
			{
				for (i = 0; i < RetailerCodelist.options.length; i++) 
					{
						var optvalue = RetailerCodelist.options[i].value;
						var lstval=optvalue.split("~");
						if(lstval[1]==distributor)
						{
						document.getElementById('dsrname').value = lstval[2];
						document.getElementById('dsrname').text = lstval[2];
						document.getElementById('dsrlocation').value = lstval[3];
						document.getElementById('dsrlocation').text = lstval[3];
						}	
					}
			}
			else
			{
						document.getElementById('dsrlocation').value = " ";
						document.getElementById('dsrlocation').text = " ";
						document.getElementById('dsrname').value = " ";
						document.getElementById('dsrname').text = " ";
			}				
		}

</script> 

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
<?
?>