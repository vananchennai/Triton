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
	global $regioncode,$regionname,$CountryName,$tname,$scode,$sname;
    $scode = 'RegionCode';
	$sname = 'RegionName';
	$tname	= "region";
	require_once '../../searchfun.php';
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	
	
	$pagename = "Region Master";
	$validuser = $_SESSION['username'];
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");//$news->getNewsById($validuser,$pagename);
	$row = mysql_fetch_array($selectvar);
  
 	if (($row['viewrights'])== 'No')
	{ header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
		?>
            <script type="text/javascript">
			alert("you are not allowed to view this page!");
			//setInterval(function(){document.location='/amararaja/home/home/master.php';},2000);
			//document.location='/amararaja/home/home/master.php';	
			</script>
         <?
	
	}
	if(isset($_POST['permiss'])) // If the submit button was clicked
    {
		?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'regionmaster.php');
				//setInterval(function(){document.location='regionmaster.php';},2000);
			//document.location='regionmaster.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!");//document.location='regionmaster.php';	
			</script>
         <?
		
	}
	
    if(isset($_POST['Save'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	$regioncode=str_replace('&', 'and',$_POST['RegionCode']);
		$post['RegionName']=str_replace('&', 'and',$_POST['RegionName']);
		$post['RegionCode']=str_replace('&', 'and',$_POST['RegionCode']);
		   $countrysmallCode=mysql_query("SELECT * FROM countrymaster where countryname='".$_POST['CountryName']."'");
			$countrysmallfetch=mysql_fetch_array($countrysmallCode);
			
			$post['CountryName'] = $countrysmallfetch['countrycode'];
		$regionname=str_replace('&', 'and',$_POST['RegionName']);
		$CountryName= $_POST['CountryName'];
   if(!empty($_POST['RegionCode'])&&!empty($_POST['RegionName'])&&!empty($_POST['CountryName']))
{	
	$p1=strtoupper( preg_replace('/\s+/', '',$post['RegionCode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['RegionName']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `RegionCode` ,  ' ',  '' ) AS RegionCode, REPLACE(  `RegionName` ,  ' ',  '' ) AS RegionName FROM region where RegionName = '".$p2."' or RegionName = '".$post['RegionName']."' or RegionCode = '".$p2."' or RegionCode = '".$post['RegionName']."' or RegionCode = '".$p1."' or RegionCode = '".$post['RegionCode']."' or RegionName = '".$p1."' or RegionName = '".$post['RegionCode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		if($cnduplicate>0 || ($post['RegionCode']==$post['RegionName']))
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
			alert("Created Sucessfully!",'regionmaster.php');
			//setInterval(function(){document.location='regionmaster.php';},2000);
			</script>
            <?
        }
}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");
			//document.location='regionmaster.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		     $post['RegionCode'] = $_POST['RegionCode'];
		$post['RegionName'] = $_POST['RegionName'];
        $countrysmallCode=mysql_query("SELECT * FROM countrymaster where countryname='".$_POST['CountryName']."'");
			$countrysmallfetch=mysql_fetch_array($countrysmallCode);
			
			$post['CountryName'] = $countrysmallfetch['countrycode'];
	$regioncode=$_POST['RegionCode'];
		$regionname=$_POST['RegionName'];
		$CountryName= $_POST['CountryName'];
        // This will make sure its displayed
		if(!empty($_POST['RegionCode'])&&!empty($_POST['RegionName'])&&!empty($_POST['CountryName']))
		{
				
		$codenamedcheck=0;
		if($_SESSION['regionsession']!=$regionname)
		{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['RegionName']));
		$repqry="SELECT REPLACE(  `RegionName` ,  ' ',  '' ) AS RegionName  FROM  `region` where RegionName = '".$p2."' or RegionName = '".$post['RegionName']."' or RegionCode = '".$p2."' or RegionCode = '".$post['RegionName']."'";
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
	$result="SELECT * FROM region where RegionCode ='".$post['RegionCode']."'";
	    $sql1 = mysql_query($result) or die (mysql_error());
 		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
		if($myrow1>0)
		{
						$post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
						$wherecon= "RegionCode ='".$post['RegionCode']."'";
						$news->editNews($post,$tname,$wherecon);
						unset($_SESSION['regionsession']);
						
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'regionmaster.php');
			//setInterval(function(){document.location='regionmaster.php';},2000);
			</script>
            <?
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("You are not allowed to save a new record using update!");
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
$result=mysql_query("SELECT * FROM region where RegionCode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'regionmaster.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		
		   $regioncode = $myrow['RegionCode'];
		   $regionname = $myrow['RegionName'];
		   $countrypgg= mysql_query("select countryname from countrymaster where countrycode='".$myrow['CountryName']."' ")  ;
		$countryrecord11 = mysql_fetch_array($countrypgg);
        $CountryName = $countryrecord11['countryname'];
		   //$CountryName = $myrow['CountryName'];
		  
		 $_SESSION['regionsession']= $myrow['RegionName'];
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
			alert("Select data to delete!",'regionmaster.php');
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
			$q1="SELECT RegionCode
FROM region
WHERE RegionCode='".$var1."'
AND EXISTS (
SELECT region
FROM  `branch` 
WHERE region='".$var1."'
) ";
 $repres= mysql_query($q1) or die (mysql_error());
 $myrow1 = mysql_num_rows($repres);
 if($myrow1==0)
 {
		$wherecon= "RegionCode ='".$var1."'";
		$news->deleteNews($tname,$wherecon);
	
			?>
            <script type="text/javascript">
			alert("Deleted  Successfully!",'regionmaster.php');
			//setInterval(function(){document.location='regionmaster.php';},2000);
			//document.location='regionmaster.php';
			</script>
   			<?
}
else
{
	
	 						?>
           					<script type="text/javascript">
							alert("you can't delete already used in other forms!",'regionmaster.php');
							//setInterval(function(){document.location='regionmaster.php';},2000);
							</script>
 							<?
}
		}
}
}
 


if(isset($_POST['Search']))
{
	
if(isset($_POST['codes'])||isset($_POST['names']))
{
	$search1=$_POST['codes'];
	$search2=$_POST['names'];
	$_SESSION['codesval']=$_POST['codes'];
	$_SESSION['namesval']=$_POST['names'];
	if(empty($_POST['codes'])&&empty($_POST['names']))
	{
		?>
		    <script type="text/javascript">
			alert("Please enter some search criteria!");
			setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
			</script>
			<?
	}
	else
	{
	if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM region WHERE RegionCode like'".$_POST['codes']."%' OR RegionName like'".$_POST['names']."%'";
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM region WHERE RegionCode like'".$_POST['codes']."%'";
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM region WHERE RegionName like'".$_POST['names']."%'";
	}
	else
	{
		
		$condition="SELECT * FROM region WHERE 1";
	}
	
	$refer=mysql_query($condition);
	$myrow1 = mysql_num_rows($refer);
	//mysql_fetch_array($query);
	
	$page = (int) (!isset($_GET["page"]) ? 1 : 1);
    	//$limit = $myrow1+1000;
    	$startpoint = ($page * $limit) - $limit;
        //to make pagination
        $statement = "region";
		$starvalue = $myrow1;
		 //show records
       $query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
		if($myrow1==0)	
		{
			?>
		    <script type="text/javascript">
			alert("Data not found!");
			setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
			//document.location='regionmaster.php';
			</script>
			<?
		
		}
	}
}

}
else
{
if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{		
	   $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	//$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
        $statement = "region"; 
		$starvalue = "";
        $query = mysql_query("SELECT * FROM {$statement} order by id desc LIMIT {$startpoint} , {$limit}");

}
else
	{
		if(!empty($_SESSION['codesval'])&&!empty($_SESSION['namesval']))
	{
		$condition="SELECT * FROM region WHERE RegionCode like'".$_SESSION['codesval']."%' OR RegionName like'".$_SESSION['namesval']."%'";
	}
	else if(!empty($_SESSION['codesval'])&&empty($_SESSION['namesval']))
	{
		$condition="SELECT * FROM region WHERE RegionCode like'".$_SESSION['codesval']."%'";
	}
	else if(!empty($_SESSION['namesval'])&&empty($_SESSION['codesval']))
	{
		$condition="SELECT * FROM region WHERE RegionName like'".$_SESSION['namesval']."%'";
	}
	else
	{
		
		$condition="SELECT * FROM region WHERE 1";
	}
	
	$refer=mysql_query($condition);
	$myrow1 = mysql_num_rows($refer);
	//mysql_fetch_array($query);
	
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	//$limit = $myrow1+1000;
    	$startpoint = ($page * $limit) - $limit;
        //to make pagination
        $statement = "region";
		$starvalue = $myrow1;
		 //show records
       $query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
}
}


if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:regionmaster.php');
}

?>
<script type="text/javascript">


function validateRegionCode(key)
{
var object = document.getElementById('RegionCode');
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
function validateRegionName(key)
{
var object = document.getElementById('RegionName');
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
<title><?php echo $_SESSION['title']; ?> || Region Master</title>
</head>
<?php 
 if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.RegionName.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.RegionCode.focus()">

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
						<p>Region Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Region Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?
                              if(!empty($_GET['edi']))
                              {
                              ?>
                                   <input type="text" style="border-style:hidden; background:#f5f3f1;"  name="RegionCode" <? if(!empty($_GET['edi'])) { ?> readonly="readonly" <? }?> value="<?php echo $regioncode;?>" onChange="return codetrim(this)"/>
                                  <? }
							  else
							  { ?>
                                  <input type="text"  name="RegionCode" id="RegionCode" maxlength="15" value="<?php echo $regioncode;?>" onChange="return codetrim(this)" onKeyUp="return validateRegionCode(event)"/>
                                  <? }?>   
                                   
                               </div>
 							<!--Row1 end-->
                            
                             <!--Row1 -->  
                               <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Region Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text"  name="RegionName" id="RegionName" maxlength="50" value="<?php echo $regionname;?>" onChange="return trim(this)" onKeyUp="return validateRegionName(event)"/>
                               </div>
 							<!--Row1 end-->
                            
                             <!--Row2 -->  
                               <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Country Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                       <select name="CountryName" id="CountryName" >
                                       <option value="<?php echo $CountryName;?>"><? if(!empty($CountryName)){ echo $CountryName;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT countryname FROM countrymaster order by countryname asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($CountryName!=$record['countryname'])
									  {      
                                       echo "<option value=\"".$record['countryname']."\">".$record['countryname']."\n ";  
									  }
                                     }
                                    ?>
                                          </select>
                             
                               </div>
 							<!--Row2 end--> 
                                                  
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:100px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                                     
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:100px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                                 
                            
                            </div>      
                     <!-- col3 --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                <!--Main row 2 start-->
                <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:-99px;">
                             
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
                               <div style="width:80px; height:30px; float:left; margin-left:20px; margin-top:16px;" >
                                  <label>Region Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:88px; height:30px; float:left; margin-left:16px; margin-top:16px;">
                                  <label>Region Name</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                  <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:83px; height:32px; float:left; margin-top:16px; margin-left:16px;">
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>
                               </div>  
                          </div> 
                </div>
                
                <!--Main row 2 end-->
                
            
             <!-- form id start end-->      
             <div style="width:930px; height:auto; padding-bottom:8px; margin-top:-20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                   
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
     <td style="font-weight:bold;">Region Code</td>
     <td style="font-weight:bold;">Region Name</td>
     <td style="font-weight:bold;">Country</td></tr>
     
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
     <td style="font-weight:bold; text-align:center" bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" onChange="test();" value="<? echo $record['RegionCode']."~".$record['RegionName'];
 ?>"></td>
    <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
  <td style="font-weight:bold; text-align:center" bgcolor="#FFFFFF" > <a style="color:#0360B2" name="edit" href="regionmaster.php?edi=<?= $record['RegionCode']; ?>">Edit</a></td>
   <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF">
        <?=$record['RegionCode']?>
    </td>
     <td  bgcolor="#FFFFFF">
        <?=$record['RegionName']?>
    </td>
    <td  bgcolor="#FFFFFF">
    <? $check= mysql_query("select countryname from countrymaster where countrycode='".$record['CountryName']."' ")  ;
		$checkrecord = mysql_fetch_array($check);
       echo $checkrecord['countryname']; ?>
       
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