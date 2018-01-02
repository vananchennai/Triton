<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $schemecode,$schemename,$schemestatus,$effectivedate,$schemetype,$tname,$stname,$scode,$sname;
	$scode = 'schemecode';
	$sname = 'schemename';
	$tname	= "schememaster";
	require_once '../../searchfun.php';	
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$stname="schememasterupload";
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	$_SESSION['type']=NULL;
	$schemenamemaster='select * from schememaster';
	$pagename = "scheme";
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
			alert("you are not allowed to do this action!",'schememaster.php');
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'schememaster.php');
			</script>
         <?
		
	}//Page Verification Code and User Verification

    if(isset($_POST['Save'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
		unset($_SESSION['namesval']);
        $post['schemecode'] = strtoupper(str_replace('&', 'and',$_POST['schemecode']));
		$post['schemename'] = strtoupper(str_replace('&', 'and',$_POST['schemename']));
		$post['schemestatus'] = $_POST['schemestatus'];
		$post['effectivedate'] = $news->dateformat($_POST['effectivedate']);
		$post['schemetype'] = $_POST['schemetype'];
        // This will make sure its displayed
		$schemecode=strtoupper($_POST['schemecode']);
		$schemename =strtoupper($_POST['schemename']);
		$schemestatus = $_POST['schemestatus'];
		$effectivedate = $_POST['effectivedate'];
		$schemetype = $_POST['schemetype'];
		if(!empty($_POST['schemecode'])&&!empty($_POST['schemename'])&&!empty($_POST['schemestatus'])&&!empty($_POST['effectivedate'])&&!empty($_POST['schemetype']))
		{   
			$p1=strtoupper( preg_replace('/\s+/', '',$post['schemecode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['schemename']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `schemecode` ,  ' ',  '' ) AS schemecode, REPLACE(  `schemename` ,  ' ',  '' ) AS schemename FROM schememaster where schemename = '".$p2."' or schemename = '".$post['schemename']."' or schemecode = '".$p2."' or schemecode = '".$post['schemename']."' or schemecode = '".$p1."' or schemecode = '".$post['schemecode']."' or schemename = '".$p1."' or schemename = '".$post['schemecode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		if($cnduplicate>0 or ($post['schemecode'] == $post['schemename']))
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
			
			$spost['Code']  =$post['schemecode'];
		$spost['Masters']="schememaster";
		$spost['Status']="0";
		$spost['InsertDate']=date("Y/m/d");
		$spost['Deliverydae']=date("Y/m/d");
				
		$franqry= mysql_query("SELECT Franchisecode  FROM  `franchisemaster`") or die (mysql_error());
		while($frqry = mysql_fetch_array($franqry))
		  {
			  $spost['Franchiseecode']=$frqry['Franchisecode'];
			  $news->addNews($spost,$stname);
		  }
			
			?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'schememaster.php');
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
		unset($_SESSION['codesval']);
		unset($_SESSION['namesval']);	
		$post['schemecode'] = strtoupper(str_replace('&', 'and',$_POST['schemecode']));
		$post['schemename'] = strtoupper(str_replace('&', 'and',$_POST['schemename']));
		$post['schemestatus'] = $_POST['schemestatus'];
		$post['effectivedate'] = $news->dateformat($_POST['effectivedate']);
		$post['schemetype'] = $_POST['schemetype'];
 		$schemecode=$post['schemecode'];
		$schemename =$post['schemename'];
		$schemestatus=$post['schemestatus'];
		$effectivedate=$_POST['effectivedate'];
		$schemetype=$_POST['schemetype'];
        // This will make sure its displayed
		if(!empty($_POST['schemecode'])&&!empty($_POST['schemename'])&&!empty($_POST['schemestatus'])&&!empty($_POST['effectivedate'])&&!empty($_POST['schemetype']))
		{ 			
		$codenamedcheck=0;
	if($_SESSION['pschemeval']!=$schemename)
	{
	$p2=strtoupper( preg_replace('/\s+/', '',$post['schemename']));
	$repqry="SELECT REPLACE(  `schemename` ,  ' ',  '' ) AS schemename  FROM  `schememaster` where schemename = '".$p2."' or schemename = '".$post['schemename']."' or schemecode = '".$p2."' or schemecode = '".$post['schemename']."'";
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
			$result="SELECT * FROM schememaster where schemecode ='".$post['schemecode']."' or schemename ='".$post['schemename']."'";
			$sql1 = mysql_query($result) or die (mysql_error());
 			$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
			if($myrow1==0)
			{
			?>
            <script type="text/javascript">
			alert("You are not allowed to save a new record using Update!");
			</script>
            
        	 <?

			}
			else
			{
				
				$post['user_id'] = $_SESSION['username'];
		date_default_timezone_set ("Asia/Calcutta");
		$post['m_date']= date("y/m/d : H:i:s", time());	
			
			
			$wherecon= "schemecode ='".$post['schemecode']."'";
						$news->editNews($post,$tname,$wherecon);
			
			$mkrow = mysql_query("SELECT Status,Franchiseecode FROM schememasterupload where Code='".$post['schemecode']."'");
			while($val=mysql_fetch_array($mkrow))
			{
					if($val['Status']>0)
					{
						$spost1['Status']=1;
					}
					else
					{
						$spost1['Status']=0;
					}
			$wherecon= "Code='".$post['schemecode']."' AND Franchiseecode='".$val['Franchiseecode']."'";
			$news->editNews($spost1,$stname,$wherecon);
			}	
			
			unset($_SESSION['pschemeval']);
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'schememaster.php');
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
$result=mysql_query("SELECT * FROM schememaster WHERE schemecode ='".$prmaster."'");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'schememaster.php');
			</script>
   			<?
		}
		else
		{
			$myrow = mysql_fetch_array($result);
			$schemecode = $myrow['schemecode'];
			$schemename = $myrow['schemename'];
			$_SESSION['pschemeval'] = $myrow['schemename'];
			$schemestatus = $myrow['schemestatus'];
			$effectivedate = date("d/m/Y",strtotime($myrow['effectivedate']));
			$schemetype = $myrow['schemetype'];
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
		
			$mkrow = mysql_query("SELECT Status FROM schememasterupload where Code='".$checkbox[$i]."' and Status!='0'");
			$val=mysql_num_rows($mkrow);
				if($val == 0)
				{
				$wherec= "Code='".$checkbox[$i]."'";
					$news->deleteNews($stname,$wherec);	
					
					
					$wherecon= "schemecode ='".$checkbox[$i]."'";
					$news->deleteNews($tname,$wherecon);
					?>
					<script type="text/javascript">
					alert("Deleted  Successfully!!",'schememaster.php');
					//setInterval(function(){document.location='schememaster.php';},2000);
					</script>
					<?	
					}
					else
					{
					?>
					<script type="text/javascript">
					alert("You Can't delete already send to Franchisee ",'schememaster.php');
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
	header('Location:schememaster.php');
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
		
		$condition="SELECT * FROM schememaster WHERE schemecode like'".$_POST['codes']."%' AND schemename like'".
		$_POST['names']."%'order by m_date  DESC";
		
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$search1=$_POST['codes'];
		//$search2=$_POST['names'];
		$condition="SELECT * FROM schememaster WHERE schemecode like'".$_POST['codes']."%'order by m_date DESC";
		
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		//$search1=$_POST['codes'];
		$search2=$_POST['names'];
		$condition="SELECT * FROM schememaster WHERE schemename like'".$_POST['names']."%'order by m_date DESC";
		
	}
	else
	{
		
		$condition="SELECT * FROM schememaster order by m_date DESC";
	}
	$schemenamemaster=$condition;
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$schemenamemaster;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($schemenamemaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
$stringData =$myrecord['schemecode']."\t ;".$myrecord['schemename']."\t;".$myrecord['schemestatus']."\t;".date("d/m/Y",strtotime($myrecord['effectivedate']))."\t;".$myrecord['schemetype']."\t;\n";
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportScheme.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$schemenamemaster;

	header('Location:ExportScheme.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$schemenamemaster;
	header('Location:ExportScheme.php');
}
	
}	
?>

<script type="text/javascript">

$(function() {
$("#effectivedate").datepicker({ changeYear:true, minDate:'0' ,dateFormat:'dd/mm/yy',defaultDate: null});
});
		
function validateschemecode(key)
{
var object = document.getElementById('schemecode');
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
var object = document.getElementById('schemename');
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
<title><?php echo $_SESSION['title']; ?>|| Scheme Master</title>
</head>
 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.schemename.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.schemecode.focus()">

 <? }
}else{?>
<body class="default" onLoad="document.form1.codes.focus()">

 <? } ?>
 <center>

<form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1"> 
<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center"> 
           
            <!-- form id start-->
            <div style="width:930px; height:auto;   min-height: 150px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Scheme Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Scheme Code </label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?php if(!empty($_GET['edi']))
							{?>
                           <input type="text" name="schemecode" id="schemecode" style="border-style:hidden; background:#f5f3f1; text-transform:uppercase" readonly="readonly"  value="<?php echo $schemecode ;?>" onKeyPress="return validateschemecode(event)" onChange="return codetrim(this)" />
                            <? } 
							else { ?>
                                  <input type="text" name="schemecode" id="schemecode" value="<?php echo $schemecode ;?>" maxlength="15" onKeyPress="return validateschemecode(event)" onChange="return codetrim(this)" style="text-transform:uppercase;" />
                                   <?
							}
							?>
                              
                               </div>
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Scheme Name</label><label style="color:#F00;">*</label>
                               </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <input type="text" name="schemename" id="schemename" maxlength="50" onKeyPress="return validate(event)" value="<?php echo $schemename ;?>" onChange="return trim(this)" style="text-transform:uppercase;"/>
                               </div>
                               
                             <!--Row2 end-->  
                            <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Scheme Status</label><label style="color:#F00;">*</label>
                               </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                           
                             <select name="schemestatus" id="schemestatus">
									 <option value="<?php echo $schemestatus;?>"><? if(!empty($schemestatus)){ echo $schemestatus;}else{?> ----Select---- <? } ?></option>
								  <option value="ACTIVE">ACTIVE</option>
                                       	<option value="INACTIVE">INACTIVE</option></select>
										</select>
                       
                                 </div>
                                        
                                <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                	<label>Effective date</label><label style="color:#F00;">*</label>
                                </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                	<input type="text" name="effectivedate" id="effectivedate" readonly="readonly"  value="<?php echo $effectivedate ;?>" />
                                </div>
                                
                                 <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                	<label>Scheme type</label><label style="color:#F00;">*</label>
                                </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <select name="schemetype" id="schemetype">
                                    <option value="<?php echo $schemetype;?>"><? if(!empty($schemetype)){ echo $schemetype;}else{?> ----Select---- <? } ?></option>
                                     <option value="PURCHASE">PURCHASE</option>
                                    <option value="SALES">SALES</option>
                                   </select>
                                    </select>
                                </div>
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:200px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                      
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:200px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
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
                                  <label>Scheme Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:90px; height:30px; float:left; margin-left:15px; margin-top:14px;">
                                  <label>Scheme Name</label>
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

<script>// all scripts used to eliminate duplication in dropdown.

// Set the present object
var present = {};
$('#schemestatus option').each(function(){
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


// Set the present object
var present = {};
$('#schemetype option').each(function(){
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
</script>                
                
  <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px;" class="grid">
                 
                  <table align="center" class="sortable" bgcolor="#FF0000" border="1" width="900px" id="datatable1">
                  <tr>
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
     <td style=" font-weight:bold;">Scheme Code</td>
     <td style=" font-weight:bold;">Scheme Name</td>
     <td style=" font-weight:bold;">Scheme Status</td>
     <td style=" font-weight:bold;">Effective Date</td>
     <td style=" font-weight:bold;">Scheme Type</td>
     </tr>
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
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" onChange="test();" id="checkboxs" value="<? echo $record['schemecode']; ?>" ></td> 
	 <?
	  } 
	 if(($row['editrights'])=='Yes')
	  { 
	 ?>
     
 	 <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center""> <a style="color:#0360B2" name="edit" href="schememaster.php?edi=<?=  $record['schemecode']?> ">Edit</a></td>
      <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['schemecode']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['schemename']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['schemestatus']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
       <?=date("d/m/Y",strtotime($record['effectivedate']))?>
    </td>
   <td  bgcolor="#FFFFFF" align="left">
       <?=$record['schemetype']?>
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

<?php include("../../paginationdesign.php")?>
             <div style="width:260px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
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
                               <div style="width:63px; height:32px; float:right; margin-top:18px;">
             					  <input type="submit" name="PDF" value="Export" class="button"/>
                               </div ></div>
        </div>
             <!-- form id start end-->      
          </div> 
          
     </div>       
</div>
<!--Third Block - Menu -Container -->
</form>


<!--Footer Block -->
<div id="footer-wrap1">
        <?php include("../../footer.php")?>
  </div>
<!--Footer Block - End-->
</center></body>
</html><?
}
?>