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

	$scode = 'countrycode';
	$sname = 'countryname';
	$tname	= "countrymaster";
	require_once '../../searchfun.php';
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	global $countrycode,$countryname,$tname,$search1,$search2;
	$pagename = "Country Master";
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
			alert("you are not allowed to do this action!",'countrymaster.php');
		//	setInterval(function(){document.location='countrymaster.php';},2000);
			//document.location='countrymaster.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'countrymaster.php');
			//setInterval(function(){document.location='countrymaster.php';},2000);
			//document.location='countrymaster.php';	
			</script>
         <?
		
	}
	
	
    if(isset($_POST['Save'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
        $post['countrycode'] = str_replace('&', 'and',$_POST['countrycode']);
		$post['countryname'] = str_replace('&', 'and',$_POST['countryname']);
		
		$countrycode=str_replace('&', 'and',$_POST['countrycode']);
		$countryname=str_replace('&', 'and',$_POST['countryname']);
   
	
        // This will make sure its displayed
		if(!empty($_POST['countrycode'])&&!empty($_POST['countryname']))
{	

	$p1=strtoupper( preg_replace('/\s+/', '',$post['countrycode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['countryname']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `countrycode` ,  ' ',  '' ) AS countrycode, REPLACE(  `countryname` ,  ' ',  '' ) AS countryname FROM countrymaster where countryname = '".$p2."' or countryname = '".$post['countryname']."' or countrycode = '".$p2."' or countrycode = '".$post['countryname']."' or countrycode = '".$p1."' or countrycode = '".$post['countrycode']."' or countryname = '".$p1."' or countryname = '".$post['countrycode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		if($cnduplicate>0 || ($post['countrycode']==$post['countryname']))
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
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
		$news->addNews($post,$tname);
		?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'countrymaster.php');
			//setInterval(function(){document.location='countrymaster.php';},2000);
			
			</script>
            <?
        }
}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");
			//document.location='countrymaster.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$post['countrycode'] = $_POST['countrycode'];
		$post['countryname'] = str_replace('&', 'and',$_POST['countryname']);
   		$countrycode=$_POST['countrycode'];
		$countryname=$_POST['countryname'];
	
        // This will make sure its displayed
		if(!empty($_POST['countrycode'])&&!empty($_POST['countryname']))
{	
		$codenamedcheck=0;
		if($_SESSION['ccsessionval']!=$countryname)
		{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['countryname']));
		$repqry="SELECT REPLACE(  `countryname` ,  ' ',  '' ) AS countryname  FROM  `countrymaster` where countryname = '".$p2."' or countryname = '".$post['countryname']."' or countrycode = '".$p2."' or countrycode = '".$post['countryname']."'";
		$repres= mysql_query($repqry) or die (mysql_error());
		$codenamedcheck=mysql_num_rows($repres);
		}
			if($codenamedcheck>0)
			{
			?>
            <script type="text/javascript">
			alert("Duplicate entry!");
			</script>
       	  	<?
			}
			
			else
			{
				$myrow1=0;	
		$result="SELECT * FROM countrymaster where countrycode ='".$post['countrycode']."'";
	    $sql1 = mysql_query($result) or die (mysql_error());
 		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
		if($myrow1>0)
		{
						 $post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
					$wherecon= "countrycode ='".$post['countrycode']."'";
					$news->editNews($post,$tname,$wherecon);
						unset($_SESSION['ccsessionval']);
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'countrymaster.php');
			//setInterval(function(){document.location='countrymaster.php';},2000);
			
			</script>
            <?
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("You are not allowed to save a new record using update!",'countrymaster.php');
			</script>
         <?
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
if(!empty($_GET['edi']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$prmaster =$_GET['edi'];

//$cont->connect();
$result=mysql_query("SELECT * FROM countrymaster where countrycode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'countrymaster.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		
		   $countrycode = $myrow['countrycode'];
		   
		   $countryname = $myrow['countryname'];
		 $_SESSION['ccsessionval']= $myrow['countryname']; 
		 
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
			alert("Select data to delete!",'countrymaster.php');
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
//			$q2="SELECT countryname
//FROM countrymaster
//WHERE countryname='".$var2."'
//AND EXISTS (
//SELECT Country
//FROM  `pricelistlinking` 
//WHERE Country='".$var2."'
//)
//OR EXISTS (
//SELECT Country
//FROM franchisemaster
//WHERE Branch='".$var2."'
//)
//OR EXISTS (
//SELECT country
//FROM employeemaster
//WHERE country='".$var2."'
//)
//OR EXISTS (
//SELECT country
//FROM branch
//WHERE country='".$var2."'
//)";
$q1="SELECT countrycode
FROM countrymaster
WHERE countrycode='".$var1."'
AND EXISTS (
SELECT CountryName
FROM  `region` 
WHERE CountryName='".$var1."')
OR exists(
SELECT Country
FROM pricelistlinking
WHERE Country =  '".$var1."'
)";
 $repres= mysql_query($q1) or die (mysql_error());
 $myrow1 = mysql_num_rows($repres);
 if($myrow1==0)
 {
		$wherecon= "countrycode ='".$var1."'";
		$news->deleteNews($tname,$wherecon);
		
			?>
            <script type="text/javascript">
			alert("Deleted  Successfully!",'countrymaster.php');
			//setInterval(function(){document.location='countrymaster.php';},2000);
			//document.location='countrymaster.php';
			</script>
   			<?
}
 else
 {
	 						?>
           					<script type="text/javascript">
							
							alert("you can't delete already used in other forms!",'countrymaster.php');
							//setInterval(function(){document.location='countrymaster.php';},2000);
							
							</script>
 							<?
 }
		}
}
}

if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:countrymaster.php');
}


?>

<script type="text/javascript">


function validatecountrycode(key)
{
var object = document.getElementById('countrycode');
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

function validatecountryname(key)
{
var object = document.getElementById('countryname');
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
<title><?php echo $_SESSION['title']; ?> || Country Master</title>
</head>

<?php 
 if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.countryname.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.countrycode.focus()">

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
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Country Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Country Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?
                              if(!empty($_GET['edi']))
                              {
                              ?>
                                  <input type="text" name="countrycode" id="countrycode" style="border-style:hidden; background:#f5f3f1;" value="<?php echo $countrycode;?>" <? if(isset($countrycode)) { ?> readonly="readonly" <? }?> onChange="return codetrim(this)" />
                              <? }
							  else
							  { ?>
                                  <input type="text" name="countrycode" id="countrycode" maxlength="15" value="<?php echo $countrycode;?>" onChange="return codetrim(this)" onKeyUp="return validatecountrycode(event)" /> 
                                    <? }?> 
                                                                 
                               </div>
 							<!--Row1 end-->
                            <!--Row1 -->  
                               <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Country Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="countryname" id="countryname" maxlength="50" value="<?php echo $countryname;?>" onChange="return trim(this)" onKeyUp="return validatecountryname(event)" />
                               </div>
 							<!--Row1 end-->
                            
                            
                                                  
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
                <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:-130px;">
                             
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
                                  <label>CountryCode</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval'] ?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:100px; height:30px; float:left; margin-left:16px; margin-top:16px;">
                                  <label>CountryName</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                  <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval'] ?>"/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:83px; height:32px; float:left; margin-top:16px; margin-left:16px;">
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>
                               </div>  
                          </div> 
                </div>
                
                <!--Main row 2 end-->
     							           
            
             <!-- form id start end-->  
             <div style="width:930px; height:auto; padding-bottom:8px; margin-top:-50px; float:left; margin-left:10px; overflow:auto;" class="grid">
                   
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
     <td style="font-weight:bold;">Country Code</td>
     <td style="font-weight:bold;">Country Name</td>
   
     
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
     <td align="center" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" onChange="test();" value="<? echo $record['countrycode']."~".$record['countryname'];
 ?>"></td>
    <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
  <td style="font-weight:bold; text-align:center" bgcolor="#FFFFFF" align="left" valign="top"> <a style="color:#0360B2" name="edit" href="countrymaster.php?edi=<?= $record['countrycode']; ?>">Edit</a></td>
 <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF">
        <?=$record['countrycode']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left" valign="top">
        <?=$record['countryname']?>
    </td>
    
  
   
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