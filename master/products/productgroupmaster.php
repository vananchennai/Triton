<?php 
	include '../../functions.php';
	sec_session_start();
	require_once '../../masterclass.php';
	include("../../header.php");

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $ProductCode,$ProductGroup,$Parent,$tname,$scode,$sname;
    $scode = 'ProductCode';
	$sname = 'ProductGroup';
	
	$tname	= "productgroupmaster";
	require_once '../../searchfun.php';
	require_once '../../paginationfunction.php';
	$stname="productgroupupload";
	$news = new News(); // Create a new News Object

		
	$_SESSION['type']=NULL;
	$productgroupmaster='select * from productgroupmaster';
	
	$pagename = "productgroupmaster";
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
			alert("you are not allowed to do this action!",'productgroupmaster.php');
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'productgroupmaster.php');
			</script>
         <?
		
	}//Page Verification Code and User Verification

	  //Save
    if(isset($_POST['Save'])) // If the submit button was clicked
    {
		$_SESSION['codesval']=NULL;
		$_SESSION['namesval']=NULL;
        $_SESSION['Parent']=NULL;
        $post['ProductCode'] = strtoupper(str_replace('&', 'and',$_POST['ProductCode']));
		$post['ProductGroup'] = strtoupper(str_replace('&', 'and',$_POST['ProductGroup']));
		$post['Parent'] = $_POST['Parent'];

        // This will make sure its displayed
		$ProductCode=$post['ProductCode'];
		$ProductGroup =$post['ProductGroup'];
        
		if(!empty($_POST['ProductCode'])&&!empty($_POST['ProductGroup']))
		{   
		
		$p1=strtoupper( preg_replace('/\s+/', '',$post['ProductCode']));
		$p2=strtoupper( preg_replace('/\s+/', '',$post['ProductGroup']));
		
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `ProductCode` ,  ' ',  '' ) AS ProductCode, REPLACE(  `ProductGroup` ,  ' ',  '' ) AS ProductGroup FROM productgroupmaster where ProductGroup = '".$p2."' or ProductGroup = '".$post['ProductGroup']."' or ProductCode = '".$p2."' or ProductCode = '".$post['ProductGroup']."' or ProductCode = '".$p1."' or ProductCode = '".$post['ProductCode']."' or ProductGroup = '".$p1."' or ProductGroup = '".$post['ProductCode']."'";
	 
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
			
			$conditionseg="SELECT ProductSegmentCode,ProductSegment FROM  `productsegmentmaster` where ProductSegmentCode='".$ProductCode."'or ProductSegment='".$p2."' or ProductSegmentCode='".$p2."'or ProductSegment='".$ProductCode."'" ;
								 $referseg=mysql_query($conditionseg);
								 $arrlistseg=mysql_num_rows($referseg);
								
			$conditiontype="SELECT ProductTypeCode,ProductTypeName FROM  `producttypemaster` where ProductTypeCode='".$ProductCode."' or ProductTypeName='".$p2."' or ProductTypeCode='".$p2."' or ProductTypeName='".$ProductCode."'";
								 $refertype=mysql_query($conditiontype);
								 $arrlisttype=mysql_num_rows($refertype);
			
		if($cnduplicate>0 || $arrlistseg>0 || $arrlisttype>0 || ($post['ProductCode'] == $post['ProductGroup']) )
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
		
		$news->addNews($post,$tname);
		$spost['Code']  =$post['ProductCode'];
		$spost['Masters']="productgroupmaster";
		$spost['Status']="0";
		$spost['InsertDate']=date("Y/m/d");
		$spost['Deliverydae']=date("Y/m/d");
				
		$franqry= mysql_query("SELECT Franchisecode,PrimaryFranchise  FROM  `franchisemaster`") or die (mysql_error());
		while($frqry = mysql_fetch_array($franqry))
		  {
			  $spost['Franchiseecode']=$frqry['Franchisecode'];
			  $spost['PrimaryFranchise']=$frqry['PrimaryFranchise'];
			  $news->addNews($spost,$stname);
		  }
			?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'productgroupmaster.php');
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
		$post['ProductCode'] = strtoupper(str_replace('&', 'and',$_POST['ProductCode']));
		$post['ProductGroup'] = strtoupper(str_replace('&', 'and',$_POST['ProductGroup']));
		$post['Parent'] = $_POST['Parent'];
		 $ProductCode=$post['ProductCode'];
		$ProductGroup =$post['ProductGroup'];
        // This will make sure its displayed
		if(!empty($_POST['ProductCode'])&&!empty($_POST['ProductGroup']))
		{ 	
		$codenamedcheck=0;
	if($_SESSION['pgroupval'] !=$ProductGroup)
	{
	$p2=strtoupper( preg_replace('/\s+/', '',$post['ProductGroup']));
	
	 $repqry="SELECT REPLACE(  `ProductGroup` ,  ' ',  '' ) AS ProductGroup  FROM  productgroupmaster where ProductGroup = '".$p2."' or ProductGroup = '".$post['ProductGroup']."' or ProductCode = '".$p2."' or ProductCode = '".$post['ProductGroup']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	echo $codenamedcheck=mysql_num_rows($repres);
	}

			
								$conditionseg="SELECT ProductSegmentCode,ProductSegment FROM  `productsegmentmaster` where ProductSegmentCode='".$post['ProductCode']."'or ProductSegment='".$post['ProductGroup']."' or ProductSegmentCode='".$post['ProductGroup']."'or ProductSegment='".$post['ProductCode']."'" ;
								 $referseg=mysql_query($conditionseg);
								 $arrlistseg=mysql_num_rows($referseg);
								
								$conditiontype="SELECT ProductTypeCode,ProductTypeName FROM  `producttypemaster` where ProductTypeCode='".$post['ProductCode']."' or ProductTypeName='".$post['ProductGroup']."' or ProductTypeCode='".$post['ProductGroup']."' or ProductTypeName='".$post['ProductCode']."'";
								 $refertype=mysql_query($conditiontype);
								 $arrlisttype=mysql_num_rows($refertype);
			
			if($codenamedcheck>0 || $arrlistseg>0 || $arrlisttype>0 || ($post['ProductGroup']==$post['ProductCode']))
			{
				?> <script type="text/javascript">	alert("Duplicate entry!"); </script> <?
			}
			else
			{
			try
			{
				$post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
			$wherecon= "ProductCode ='".$post['ProductCode']."'";
			$news->editNews($post,$tname,$wherecon);
		
			$mkrow = mysql_query("SELECT Status,Franchiseecode FROM productgroupupload where Code='".$post['ProductCode']."'");
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
			$wherecon= "Code='".$post['ProductCode']."' AND Franchiseecode='".$val['Franchiseecode']."'";
			$news->editNews($spost1,$stname,$wherecon);
			}
			unset($_SESSION['pgroupval'] );	
			?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'productgroupmaster.php');
			</script>
            <?
			}
			catch (Exception $e) {
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
if(!empty($_GET['edi']) && $_GET['edi'] !='PRIMARY')
{
$prmaster =$_GET['edi'];
$_SESSION['codesval']=NULL;
	$_SESSION['namesval']=NULL;

$result=mysql_query("SELECT * FROM productgroupmaster WHERE ProductCode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'productgroupmaster.php');
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
			$ProductCode = $myrow['ProductCode'];
		   $ProductGroup = $myrow['ProductGroup'];
		   // $pgg= mysql_query("select ProductGroup from productgroupmaster where ProductCode='".$myrow['Parent']."' ")  ;
		
		// $record11 = mysql_fetch_array($pgg);
		   // $Parent =$record11['ProductGroup'];
		   	if($myrow['Parent'] != "PRIMARY" ){
			 $par= mysql_query("select ProductGroup from productgroupmaster where ProductCode='".$myrow['Parent']."' ");
			$record11 = mysql_fetch_array($par); 
		    $Parent1 = $record11['ProductGroup'];
		    $Parent = $myrow['Parent'];
		   }else{
		   	$Parent = $myrow['Parent'];
		   	$Parent1 = $myrow['Parent'];
		   }
		 $_SESSION['pgroupval'] = $myrow['ProductGroup'];
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
			$repqry1="SELECT ProductCode from productgroupmaster where ProductCode in(select ProductGroupCode from productmaster where ProductGroupCode='".$checkbox[$i]."') ";
			$repres= mysql_query($repqry1) or die (mysql_error());
			$myrow1 = mysql_num_rows($repres);//mysql_fetch_array($retval);
			$repqry2="SELECT ProductCode from productgroupmaster where Parent ='".$checkbox[$i]."'";
			$repres1= mysql_query($repqry2) or die (mysql_error());
			$myrow2 = mysql_num_rows($repres1);
			
		if($myrow1==0 && $myrow2==0)	
		{
			$mkrow = mysql_query("SELECT Status FROM productgroupupload where Code='".$checkbox[$i]."' and Status !='0'");
			$val=mysql_num_rows($mkrow);
				if($val==0)
			{
			$wherec= "Code='".$checkbox[$i]."'";
			$news->deleteNews($stname,$wherec); 
			
			
			$wherecon= "ProductCode ='".$checkbox[$i]."'";
			$news->deleteNews($tname,$wherecon);
			?>
            <script type="text/javascript">
			alert("Deleted  Successfully!",'productgroupmaster.php');
			</script>
   			<?		
			}
			else
			{
			?>
            <script type="text/javascript">
			alert("You Can't delete already send to Franchisee ",'productgroupmaster.php');
			</script>
   			<?	
			}
			
		
		}
		else
		{

		?>
            <script type="text/javascript">
			alert("you can't delete already used in other masters!",'productgroupmaster.php');
			</script>
   			<?
		}
		}
}
}

/*//	Cleare the session for searching
 if (isset($_SESSION['previous'])) {
   if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
       unset($_SESSION['codesval']);
	$_SESSION['namesval']=NULL;
        ### or alternatively, you can use this for specific variables:
        ### unset($_SESSION['varname']);
   }
}
  $_SESSION['previous'] = basename($_SERVER['PHP_SELF']);
  
  // Set the page count for searching
	if (!isset($_SESSION['pagecount']))
	{
	 $limit = 10;
	}
	else
	  {
	   $limit =$_SESSION['pagecount'] ;
	  }
*/
//search pagination and defaut values in table


if(isset($_POST['Cancel']))
{
	$_SESSION['codesval']=NULL;
	$_SESSION['namesval']=NULL;
	header('Location:productgroupmaster.php');
}
if(isset($_POST['Excel']))
{
$productgroupmaster = "SELECT * FROM productgroupmaster order by m_date";
$_SESSION['type']='TallyExcel';
$_SESSION['query']=$productgroupmaster;
header('Location:ExportGroup.php');
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
		
		$condition="SELECT * FROM productgroupmaster WHERE ProductCode like'".$_POST['codes']."%' AND ProductGroup like'".
		$_POST['names']."%'order by m_date DESC";
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$search1=$_POST['codes'];
		//$search2=$_POST['names'];
		$condition="SELECT * FROM productgroupmaster WHERE ProductCode like'".$_POST['codes']."%'order by m_date DESC"; 
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		//$search1=$_POST['codes'];
		$search2=$_POST['names'];
		$condition="SELECT * FROM productgroupmaster WHERE ProductGroup like'".$_POST['names']."%'order by m_date DESC";
	}
	else
	{
		
		$condition="SELECT * FROM productgroupmaster order by m_date DESC";
	}
	$productgroupmaster=$condition;
	
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$productgroupmaster;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($productgroupmaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
$stringData =$myrecord['ProductCode']."\t ;".$myrecord['ProductGroup']."\t;".$myrecord['Parent']."\t\n";
		fwrite($fh, $stringData);
	}
//	
	fclose($fh);
	header('Location:ExportGroup.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$productgroupmaster;

	header('Location:ExportGroup.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$productgroupmaster;
	header('Location:ExportGroup.php');
}
	
}	
?>

<script type="text/javascript">

function validateProductCode(key)
{
var object = document.getElementById('ProductCode');
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
var object = document.getElementById('ProductGroup');
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
		$('#ProductCode').focus();
	});
});
</script>-->

<script type="text/javascript">
 $(document).ready(function(){
    $('a.doSomething').click(function(){
       
        });
 });
</script>


<title><?php echo $_SESSION['title']; ?> || Product Group Master</title>
</head>

 <?php
  
 if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
  if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.ProductGroup.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.ProductCode.focus()">

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
            <!-- form id start-->
            <div style="width:930px; height:auto;   min-height: 150px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;" class="mine">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Product Group Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Product Group Code </label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?php if(!empty($_GET['edi']))
							{?>
                            <input type="text" name="ProductCode" id="ProductCode" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?php echo $ProductCode ;?>" onKeyPress="return validateProductCode(event)" onChange="return codetrim(this)"/>
                            <? } 
							else { ?>
                                  <input type="text" name="ProductCode" id="ProductCode" value="<?php echo $ProductCode ;?>" maxlength="15" onKeyPress="return validateProductCode(event)" onChange="return codetrim(this)" style="text-transform:uppercase" />
                                   <?
							}
							?>
                              
                               </div>
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Product Group </label><label style="color:#F00;">*</label>
                               </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <input type="text" name="ProductGroup" id="ProductGroup" maxlength="50" onKeyPress="return validate(event)" value="<?php echo $ProductGroup ;?>" onChange="return trim(this)" style="text-transform:uppercase"/>
                               </div>
                             <!--Row2 end-->  
							 <!--Row3 -->  
                               <div style="width:145px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label>Parent</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <select name="Parent" id="Parent">
								       
                                       <option value="<?php if(!empty($Parent)){ echo $Parent;}else{?>PRIMARY<? }?>"><? if(!empty($Parent1)){ echo $Parent1;}else{?>.PRIMARY<? } ?></option>
									   <option value="PRIMARY">.PRIMARY</option>
                                     <?
                                         if(!empty($_GET['edi']))
										{ 
											$que = mysql_query("SELECT ProductGroup,ProductCode FROM productgroupmaster WHERE ProductGroup!='".$ProductGroup."' order by ProductGroup asc");
										}
										else
										{
											$que = mysql_query("SELECT ProductGroup,ProductCode FROM productgroupmaster order by ProductGroup asc");
										}
                                       	
                                     while( $record = mysql_fetch_array($que))
                                     { 
									  if($Parent!=$record['ProductGroup'] )
									  {     
                                       echo "<option value=\"".$record['ProductCode']."\">".$record['ProductGroup']."\n ";
                                       /* echo "<option value=\"".$record['ProductCode']."\">".$record['ProductCode']."\n "; */									   
                                      }
									 }
                                    ?>
                                          </select>
										  
										  
									
                               </div>
							   <!--Row3 -->
                            
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
                                  <label>Product Group Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);"  value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:90px; height:30px; float:left; margin-left:15px; margin-top:14px;">
                                  <label>Product Group</label>
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
     <td style=" font-weight:bold;">Product Group Code</td>
     <td style=" font-weight:bold;">Product Group </td>
     <td style=" font-weight:bold;">Parent</td>

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
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" onChange="test();" id="checkboxs" value="<? echo $record['ProductCode']; ?>" ></td> 
	 <?
	  } 
	 if(($row['editrights'])=='Yes')
	  { 
	 ?>
     
 	 <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center""> <a style="color:#0360B2" name="edit" href="productgroupmaster.php?edi=<?=  $record['ProductCode']?> " class="doSomething">Edit</a></td>
      <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['ProductCode']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['ProductGroup']?>
    </td>
	<td  bgcolor="#FFFFFF" align="left">
		<? if($record['Parent'] != "PRIMARY" ){
			 $par= mysql_query("select ProductGroup from productgroupmaster where ProductCode='".$record['Parent']."' ")  ;
			$record11 = mysql_fetch_array($par); 
		    echo $record11['ProductGroup'];
		   }else{
		   	echo $record['Parent'];
		   } ?>
    </td>
	<!-- <td  bgcolor="#FFFFFF" align="left">
        <?//=$record['Parent']?>
    </td> -->
   
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
             <div style="width:366px; height:50px; float:right;  margin-right:15px; margin-top:0px;" class="cont" >
         					
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
                               <div style="width:85px; height:32px; float:left; margin-top:18px;">
             					  <input type="submit" name="PDF" value="Export" class="button"/>
                               </div >
							   <div style="width:95px; height:32px; float:right; margin-top:18px;">
				   <input type="submit" name="Excel" value="TallyExport" class="button"/>
                   </div>
							   </div>
        </div>
		<script>// all scripts used to eliminate duplication in dropdown.
			 
                                    // Set the present object
                                    var present = {};
                                    $('#Parent option').each(function(){
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