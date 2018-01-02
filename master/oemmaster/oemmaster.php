<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");
// Include database connection and functions here.

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} 
else
{	
	require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	global $oemcode,$oemname,$tname,$scode,$sname;
	$scode = 'oemcode';
	$sname = 'oemname';
	$tname	= "oemmaster";
	require_once '../../searchfun.php';
	$stname="oemmasterupload";
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	$_SESSION['type']=NULL;
	$oemmaster='select * from oemmaster';
	$pagename = "oemmaster";
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
			alert("you are not allowed to do this action!",'oemmaster.php');
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'oemmaster.php');
			</script>
         <?
		
	}//Page Verification Code and User Verification

    if(isset($_POST['Save'])) // If the submit button was clicked
    {
		unset($_SESSION['codesval']);
		unset($_SESSION['namesval']);
        $post['oemcode'] = strtoupper(str_replace('&', 'and',$_POST['oemcode']));
		$post['oemname'] = strtoupper(str_replace('&', 'and',$_POST['oemname']));
        // This will make sure its displayed
		$oemcode=$post['oemcode'];
		$oemname =$post['oemname'];
		
		if(!empty($_POST['oemcode'])&&!empty($_POST['oemname']))
		{   
		
		$p1=strtoupper( preg_replace('/\s+/', '',$post['oemcode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['oemname']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `oemcode` ,  ' ',  '' ) AS oemcode, REPLACE(  `oemname` ,  ' ',  '' ) AS oemname FROM oemmaster where oemname = '".$p2."' or oemname = '".$post['oemname']."' or oemcode = '".$p2."' or oemcode = '".$post['oemname']."' or oemcode = '".$p1."' or oemcode = '".$post['oemcode']."' or oemname = '".$p1."' or oemname = '".$post['oemcode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		if($cnduplicate>0 || ($post['oemcode']==$post['oemname']))
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
			$spost['Code']  =$post['oemcode'];
		$spost['Masters']="oemmaster";
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
			alert("Created Sucessfully!",'oemmaster.php');
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
		$post['oemcode'] = strtoupper(str_replace('&', 'and',$_POST['oemcode']));
		$post['oemname'] = strtoupper(str_replace('&', 'and',$_POST['oemname']));
		 $oemcode=$post['oemcode'];
		$oemname =$post['oemname'];
        // This will make sure its displayed
	if(!empty($_POST['oemcode'])&&!empty($_POST['oemname']))
	{ 
		$codenamedcheck=0;
		if($_SESSION['oemsessionval']!=$oemname)
		{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['oemname']));
		$repqry="SELECT REPLACE(  `oemname` ,  ' ',  '' ) AS oemname  FROM  `oemmaster` where oemname = '".$p2."' or oemname = '".$post['oemname']."' or oemcode = '".$p2."' or oemcode = '".$post['oemname']."'";
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
				$post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
						 
			$wherecon= "oemcode ='".$post['oemcode']."'";
						$news->editNews($post,$tname,$wherecon);
						
						
			$mkrow = mysql_query("SELECT Status,Franchiseecode FROM oemmasterupload where Code='".$post['oemcode']."'");
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
			$wherecon= "Code='".$post['oemcode']."' AND Franchiseecode='".$val['Franchiseecode']."'";
			$news->editNews($spost1,$stname,$wherecon);
			}	
			unset($_SESSION['oemsessionval']);
			?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'oemmaster.php');
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
	
/// EDIT LINK FUNCTION 
if(!empty($_GET['edi']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$prmaster =$_GET['edi'];


$result=mysql_query("SELECT * FROM oemmaster WHERE oemcode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'oemmaster.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		
		   $oemcode = $myrow['oemcode'];
		   $oemname = $myrow['oemname'];
		 	$_SESSION['oemsessionval']= $myrow['oemname'];
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
			
		$repqry1="SELECT oemcode from oemmaster where oemcode in(select oemname from productwarranty where oemname='".$checkbox[$i]."') ";
		$repres= mysql_query($repqry1) or die (mysql_error());
		$myrow1 = mysql_num_rows($repres);//mysql_fetch_array($retval);
		
		if($myrow1==0)	
		{
		
			$mkrow = mysql_query("SELECT Status FROM oemmasterupload where Code='".$checkbox[$i]."' and Status!='0'");
			$val=mysql_num_rows($mkrow);
				if($val==0)
				{
				$wherec= "Code='".$checkbox[$i]."'";
				$news->deleteNews($stname,$wherec); 
				
				$wherecon= "oemcode ='".$checkbox[$i]."'";
				$news->deleteNews($tname,$wherecon);
				?>
				<script type="text/javascript">
				alert("Deleted  Successfully!",'oemmaster.php');
				</script>
				<?	
				}
				else
				{
				?>
				<script type="text/javascript">
				alert("You Can't delete already send to Franchisee ",'oemmaster.php');
				</script>
				<?	
				}
		}
		else
		{

		?>
            <script type="text/javascript">
			alert("you can't delete already used in other masters!",'oemmaster.php');
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
	header('Location:oemmaster.php');
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
		
		$condition="SELECT * FROM oemmaster WHERE oemcode like'".$_POST['codes']."%' AND oemname like'".
		$_POST['names']."%'order by id desc";
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$search1=$_POST['codes'];
		//$search2=$_POST['names'];
		$condition="SELECT * FROM oemmaster WHERE oemcode like'".$_POST['codes']."%'order by id desc";
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		//$search1=$_POST['codes'];
		$search2=$_POST['names'];
		$condition="SELECT * FROM oemmaster WHERE oemname like'".$_POST['names']."%'order by id desc";
	}
	else
	{
		
		$condition="SELECT * FROM oemmaster order by id desc";
	}
	$oemmaster=$condition;
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$oemmaster;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($oemmaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
$stringData =$myrecord['oemcode']."\t ;".$myrecord['oemname']."\t;\n";
		fwrite($fh, $stringData);
	}
//	
	fclose($fh);
	header('Location:ExportOEMmaster.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$oemmaster;

	header('Location:ExportOEMmaster.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$oemmaster;
	header('Location:ExportOEMmaster.php');
}
	
}	
?>

<script type="text/javascript">


function validateoemcode(key)
{
var object = document.getElementById('oemcode');
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
var object = document.getElementById('oemname');
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
<title><?php echo $_SESSION['title']; ?> || OEM Master</title>
</head>
 <?php 
 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.oemname.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.oemcode.focus()">

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
           <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1"> 
            <!-- form id start-->
            <div style="width:930px; height:auto;   min-height: 150px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>OEM Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>OEM Code </label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?php if(!empty($_GET['edi']))
							{?>
                           <input type="text" name="oemcode" id="oemcode" style="border-style:hidden; background:#f5f3f1;text-transform:uppercase;" readonly="readonly"  value="<?php echo $oemcode ;?>" onKeyPress="return validateoemcode(event)" onChange="return codetrim(this)" />
                            <? } 
							else { ?>
                                  <input type="text" name="oemcode" id="oemcode" value="<?php echo $oemcode ;?>" maxlength="15" onKeyPress="return validateoemcode(event)" style="text-transform:uppercase;" onChange="return codetrim(this)" />
                                   <?
							}
							?>
                              
                               </div>
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>OEM Name</label><label style="color:#F00;">*</label>
                               </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <input type="text" name="oemname" id="oemname" maxlength="50" onKeyPress="return validate(event)" value="<?php echo $oemname ;?>" onChange="return trim(this)" style="text-transform:uppercase;"/>
                               </div>
                             <!--Row2 end-->  
                            
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
                                  <label>OEM Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:90px; height:30px; float:left; margin-left:15px; margin-top:14px;">
                                  <label>OEM Name</label>
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
     <td style=" font-weight:bold;">OEM Code</td>
     <td style=" font-weight:bold;">OEM Name</td></tr>

 
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
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" onChange="test();" id="checkboxs" value="<? echo $record['oemcode']; ?>" ></td> 
	 <?
	  } 
	 if(($row['editrights'])=='Yes')
	  { 
	 ?>
     
 	 <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center""> <a style="color:#0360B2" name="edit" href="oemmaster.php?edi=<?=  $record['oemcode']?> ">Edit</a></td>
      <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['oemcode']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['oemname']?>
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
}
?>