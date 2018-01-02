<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $categorycode,$retailercategory,$tname,$scode,$sname;	
	$scode = 'CategoryCode';
	$sname = 'RetailerCategory';
	$tname	= "retailercategory";
	require_once '../../searchfun.php';
	require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$stname="retailercategoryupload";
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	$_SESSION['type']=NULL;
	$pagename = "Retailercategory";
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
			alert("you are not allowed to do this action!",'retailercategory.php');
			//setInterval(function(){document.location='productgroupmaster.php';},2000);
		//	document.location='productgroupmaster.php';	
			</script>
         <?
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'retailercategory.php');
			//setInterval(function(){document.location='productgroupmaster.php';},2000);
		//	document.location='productgroupmaster.php';	
			</script>
         <?
		
	}//Page Verification Code and User Verification

    if(isset($_POST['Save'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$post['CategoryCode'] = strtoupper(str_replace('&', 'and',$_POST['CategoryCode']));
		$post['RetailerCategory'] = strtoupper(str_replace('&', 'and',$_POST['RetailerCategory']));
		$categorycode = $post['CategoryCode'];
        $retailercategory = $post['RetailerCategory'];
       
		
		if(!empty($_POST['CategoryCode'])&&!empty($_POST['RetailerCategory']))
		{   
		$p1=strtoupper( preg_replace('/\s+/', '',$post['CategoryCode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['RetailerCategory']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `CategoryCode` ,  ' ',  '' ) AS CategoryCode, REPLACE(  `RetailerCategory` ,  ' ',  '' ) AS RetailerCategory FROM retailercategory where RetailerCategory = '".$p2."' or RetailerCategory = '".$post['RetailerCategory']."' or CategoryCode = '".$p2."' or CategoryCode = '".$post['RetailerCategory']."' or CategoryCode = '".$p1."' or CategoryCode = '".$post['CategoryCode']."' or RetailerCategory = '".$p1."' or RetailerCategory = '".$post['CategoryCode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);

		if($cnduplicate>0 || ($categorycode == $retailercategory))
		{
		?>
            <script type="text/javascript">alert("Duplicate entry!");</script>
         <?
		}
		else
		{
			
			$post['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$post['m_date']= date("y/m/d : H:i:s", time());
			$news->addNews($post,$tname);
			
		$spost['Code']  =$post['RetailerCategory'];
		$spost['Masters']="retailercategory";
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
            <script type="text/javascript">alert("Created Sucessfully!",'retailercategory.php');</script>
            <?
			}
        }
	
		else
		{
			?>
            <script type="text/javascript">alert("Enter Mandatory Fields!");</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$post['CategoryCode'] = strtoupper(str_replace('&', 'and',$_POST['CategoryCode']));
		$post['RetailerCategory'] = strtoupper(str_replace('&', 'and',$_POST['RetailerCategory']));
       
		$categorycode = $post['CategoryCode'];
        $retailercategory = $post['RetailerCategory'];
        // This will make sure its displayed
		if(!empty($_POST['CategoryCode'])&&!empty($_POST['RetailerCategory']))
		{ 
		 $codenamedcheck=0;
	if($_SESSION['pcatval']!=$retailercategory)
	{
	$p2=strtoupper( preg_replace('/\s+/', '',$post['RetailerCategory']));
echo $repqry="SELECT REPLACE(  `RetailerCategory` ,  ' ',  '' ) AS RetailerCategory  FROM  `retailercategory` where RetailerCategory = '".$p2."' or RetailerCategory = '".$post['RetailerCategory']."' or CategoryCode = '".$p2."' or CategoryCode = '".$post['RetailerCategory']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$codenamedcheck=mysql_num_rows($repres);
	}
			if($codenamedcheck>0 || ($categorycode == $retailercategory))
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
				$post['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$post['m_date']= date("y/m/d : H:i:s", time());
			$wherecon= "CategoryCode ='".$post['CategoryCode']."'";
						$news->editNews($post,$tname,$wherecon);
						
						
			$mkrow = mysql_query("SELECT Status,Franchiseecode FROM retailercategoryupload where Code='".$post['CategoryCode']."'");
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
			$wherecon= "Code='".$post['CategoryCode']."' AND Franchiseecode='".$val['Franchiseecode']."'";
			$news->editNews($spost1,$stname,$wherecon);
			}	
			unset($_SESSION['pcatval']);
			 
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'retailercategory.php');
			//setInterval(function(){document.location='retailercategory.php';},2000);
			//document.location='retailercategory.php';
			</script>
            <?
					
			}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='retailercategory.php';
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
$result=mysql_query("SELECT * FROM retailercategory where CategoryCode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'retailercategory.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
	
		   $categorycode = $myrow['CategoryCode'];
		   $retailercategory = $myrow['RetailerCategory'];
		   $_SESSION['pcatval']= $myrow['RetailerCategory'];
		 
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
			alert("Select data to delete!",'retailercategory.php');
			</script>
			<?
	}

else
{
		 $checkbox = $_POST['checkbox']; //from name="checkbox[]"
		 $countCheck = count($_POST['checkbox']);
		for($i=0;$i<$countCheck;$i++)
		{
		$repqry1="SELECT CategoryCode from retailercategory where CategoryCode in(select category from retailermaster where category='".$checkbox[$i]."') ";
		$repres= mysql_query($repqry1) or die (mysql_error());
		$myrow1 = mysql_num_rows($repres);//mysql_fetch_array($retval);
			
		if($myrow1==0)	
		{
			$mkrow = mysql_query("SELECT Status FROM retailercategoryupload where Code='".$checkbox[$i]."'and Status!='0'");
			$val=mysql_num_rows($mkrow);
				if($val==0)
				{
				$wherec= "Code='".$checkbox[$i]."'";
				$news->deleteNews($stname,$wherec);			 	
				
				$wherecon= "CategoryCode ='".$checkbox[$i]."'";
				$news->deleteNews($tname,$wherecon);	
				?>
				<script type="text/javascript">
				alert("Deleted  Successfully!",'retailercategory.php');
				//setInterval(function(){document.location='retailercategory.php';},2000);
				</script>
				<?		
				}
				else
				{
				?>
				<script type="text/javascript">
				alert("You Can't delete already send to Franchisee ",'retailercategory.php');
				</script>
				<?	
				}
		}
		else
		{

		?>
            <script type="text/javascript">
			alert("you can't delete already used in other masters!",'retailercategory.php');
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
	header('Location:retailercategory.php');
}


$_SESSION['type']=NULL;
	$Retailercategory='select * from retailercategory';
	
if(isset($_POST['PDF']))
{

$select=$_POST['Type'];
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		
		$condition="SELECT * FROM retailercategory WHERE CategoryCode like'".$_POST['codes']."%' AND RetailerCategory like'".
		$_POST['names']."%'";
		$Retailercategory=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$search1=$_POST['codes'];
		//$search2=$_POST['names'];
		$condition="SELECT * FROM retailercategory WHERE CategoryCode like'".$_POST['codes']."%'";
		$Retailercategory=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		//$search1=$_POST['codes'];
		$search2=$_POST['names'];
		$condition="SELECT * FROM retailercategory WHERE RetailerCategory like'".$_POST['names']."%'";
		$Retailercategory=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM retailercategory order by id DESC";$Retailercategory=$condition;
	}
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$Retailercategory;
	
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($Retailercategory);
	while( $myrecord = mysql_fetch_array($myquery))
   {
//$stringData =$myrecord[0]."\t ;\n";
$stringData =$myrecord[0]."\t ;".$myrecord[1]."\t\n";
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportCategory.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$Retailercategory;

	header('Location:ExportCategory.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$Retailercategory;
	header('Location:ExportCategory.php');
}
	
}	


?>

<script type="text/javascript">

function validateProductCode(key)
{
var object = document.getElementById('CategoryCode');
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
var object = document.getElementById('RetailerCategory');
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
<title><?php echo $_SESSION['title']; ?> || Retailer Category Master</title>
</head>

 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.RetailerCategory.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.CategoryCode.focus()">

 <? }  
}else{?>
<body class="default" onLoad="document.form1.codes.focus()">

 <? } ?>
 <center
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
						<p>Retailer Category Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:300px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Category Code </label>
                                  <label style="color:#F00;">*</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?php if(!empty($_GET['edi']))
							{?>
                           <input type="text" name="CategoryCode" id="CategoryCode" readonly="readonly" style="border-style:hidden; background:#f5f3f1;text-transform:uppercase;"  value="<?php echo $categorycode ;?>" onKeyPress="return validateProductCode(event)" onChange="return codetrim(this)" />
                            <? } 
							else { ?>
                                  <input type="text" name="CategoryCode" id="CategoryCode" value="<?php echo $categorycode ;?>" maxlength="15" onKeyPress="return validateProductCode(event)" onChange="return codetrim(this)" style="text-transform:uppercase;" />
                                   <?
							}
							?>
                              
                               </div>
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Retailer Category </label>
                                  <label style="color:#F00;">*</label>
                               </div>
                                <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="RetailerCategory" id="RetailerCategory" value="<?php echo $retailercategory;?>" maxlength="50" onKeyPress="return validate(event)" onChange="return trim(this)"  style="text-transform:uppercase;"/>
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
                                  <label>Category Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval'] ?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:90px; height:30px; float:left; margin-left:15px; margin-top:14px;">
                                  <label>Retailer Category</label>
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
     <td style=" font-weight:bold;">Category Code</td>
     <td style=" font-weight:bold;">Retailer Category</td>
     
 
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
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" onChange="test();" id="checkboxs" value="<? echo $record['CategoryCode']; ?>" ></td> 
	 <?
	  } 
	 if(($row['editrights'])=='Yes')
	  { 
	 ?>
     
 	 <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center""> <a style="color:#0360B2" name="edit" href="retailercategory.php?edi=<?=  $record['CategoryCode']?> ">Edit</a></td>
      <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['CategoryCode']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['RetailerCategory']?>
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
</html>
<?
}
?>
