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
	global $Productuomcode,$Productuom,$tname,$scode,$sname;
	$scode = 'productuomcode';
	$sname = 'productuom';
	$tname	= "productuom";
	require_once '../../searchfun.php';
	require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$stname="productuomupload";
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	$_SESSION['type']=NULL;
	$productuommaster='select * from productuom';
	$pagename = "productuom";
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
			alert("you are not allowed to do this action!",'productuom.php');
			//setInterval(function(){document.location='productgroupmaster.php';},2000);
			//document.location='productgroupmaster.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'productuom.php');
			//setInterval(function(){document.location='productgroupmaster.php';},2000);
		//	document.location='productgroupmaster.php';	
			</script>
         <?
		
	}//Page Verification Code and User Verification

    if(isset($_POST['Save'])) // If the submit button was clicked
    {
		unset($_SESSION['codesval']);
		unset($_SESSION['namesval']);
        $post['productuomcode'] = strtoupper(str_replace('&', 'and',$_POST['productuomcode']));
		$post['productuom'] = strtoupper(str_replace('&', 'and',$_POST['productuom']))	;
        // This will make sure its displayed
		$Productuomcode=$post['productuomcode'];
		$Productuom =$post['productuom'];
		
		if(!empty($_POST['productuomcode'])&&!empty($_POST['productuom']))
		{   

	
			$p1=strtoupper( preg_replace('/\s+/', '',$post['productuomcode']));
			$p2=strtoupper( preg_replace('/\s+/', '',$post['productuom']));
		$myrows=0;
		$repqry="SELECT REPLACE(  `productuom` ,  ' ',  '' ) AS productuom  FROM  `productuom` where productuom = '".$p2."' or productuom = '".$post['productuom']."' or productuomcode = '".$p2."' or productuomcode = '".$post['productuom']."' or productuomcode = '".$p1."' or productuomcode = '".$post['productuomcode']."' or productuom = '".$p1."' or productuom = '".$post['productuomcode']."'";
		 $repres= mysql_query($repqry) or die (mysql_error());
		$myrows = mysql_num_rows($repres);
			
			
			 $condition1="SELECT productuomcode,productuom FROM `productuom` WHERE `productuomcode`='". $post['productuomcode']."' or productuom='".$post['productuom'] ."' or productuomcode ='".$post['productuom'] ."' or productuom='". $post['productuomcode']."'";
			 $refer1=mysql_query($condition1);
			 $arrlist1=mysql_num_rows($refer1);
			if($myrows>0 ||$arrlist1>0 ||($post['productuomcode'] == $post['productuom']))
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
				$post['m_date']= date("y/m/d : H:i:s", time());
				 */
			$news->addNews($post,$tname);
			
		$spost['Code']  =$post['productuomcode'];
		$spost['Masters']="productuom";
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
			alert("Created Sucessfully!",'productuom.php');
			//setInterval(function(){document.location='productgroupmaster.php';},2000);
			
			</script>
            <?
			}
        }
	
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");
			//document.location='productgroupmaster.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
		unset($_SESSION['codesval']);
		unset($_SESSION['namesval']);
		$post['productuomcode'] = strtoupper(str_replace('&', 'and',$_POST['productuomcode']));
		$post['productuom'] = strtoupper(str_replace('&', 'and',$_POST['productuom']));
 		$Productuomcode=$post['productuomcode'];
		$Productuom =$post['productuom'];
        // This will make sure its displayed
		if(!empty($_POST['productuomcode'])&&!empty($_POST['productuom']))
		{ 			
		$myrows=0;
		if($_SESSION['Productuomold']!=$Productuom)
				{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['productuom']));
		
		$repqry="SELECT REPLACE(  `productuom` ,  ' ',  '' ) AS productuom  FROM  `productuom` where productuom = '".$p2."' or productuom = '".$post['productuom']."' or productuomcode = '".$p2."' or productuomcode = '".$post['productuom']."'";
		 $repres= mysql_query($repqry) or die (mysql_error());
		 $myrows = mysql_num_rows($repres);
		
				}
			
			$arrlist1=0;
		
				if($_SESSION['Productuomold']!=$Productuom)
				{
				 $condition1="SELECT productuomcode,productuom FROM `productuom` WHERE  productuom='".$Productuom ."' ";
				 $refer1=mysql_query($condition1);
				 $arrlist1=mysql_num_rows($refer1);
				}
				else
				{
				 $arrlist1=0;
				}
			if($myrows>0 or $arrlist1>0 or ($post['productuomcode']==$post['productuom']))
			{
			?>
            <script type="text/javascript">
			alert("Duplicate Entry !");
			</script>
            
        	 <?

			}
			else
			{
				$post['user_id'] = $_SESSION['username'];
				date_default_timezone_set ("Asia/Calcutta");
				$post['m_date']= date("y/m/d : H:i:s", time());
				
			$wherecon= "productuomcode ='".$post['productuomcode']."'";
						$news->editNews($post,$tname,$wherecon);
			
			
		$mkrow = mysql_query("SELECT Status,Franchiseecode FROM productuomupload where Code='".$post['productuomcode']."'");
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
			$wherecon= "Code='".$post['productuomcode']."' AND Franchiseecode='".$val['Franchiseecode']."'";
			$news->editNews($spost1,$stname,$wherecon);
			}			
			unset($_SESSION['Productuomold']);
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully!",'productuom.php');
			//setInterval(function(){document.location='productgroupmaster.php';},2000);
			</script>
            <?
					
			}
		
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='productgroupmaster.php';
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
$result=mysql_query("SELECT * FROM productuom WHERE productuomcode ='".$prmaster."'");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!",'productuom.php');
			//setInterval(function(){document.location='productgroupmaster.php';},2000);
			//document.location='productgroupmaster.php';
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		
		   $Productuomcode = $myrow['productuomcode'];
		   $Productuom = $myrow['productuom'];
		   $_SESSION['Productuomold'] = $myrow['productuom'];
		   
		 
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
			alert("Select data to delete!");//document.location='productgroupmaster.php';
			</script>
			<?
	}

else
{
	
		 $checkbox = $_POST['checkbox']; //from name="checkbox[]"
		 $countCheck = count($_POST['checkbox']);
		
		for($i=0;$i<$countCheck;$i++)
		{
			
			
		$repqry1="SELECT productuomcode from productuom where productuomcode in(select UOMCode from productmaster where UOMCode='".$checkbox[$i]."') ";
		$repres= mysql_query($repqry1) or die (mysql_error());
		$myrow1 = mysql_num_rows($repres);//mysql_fetch_array($retval);
		
		if($myrow1==0)	
		{
		
			$mkrow = mysql_query("SELECT Status FROM productuomupload where Code='".$checkbox[$i]."' and Status !='0'");
			$val=mysql_num_rows($mkrow);
				if($val==0)
				{
				$wherec= "Code='".$checkbox[$i]."' ";
				$news->deleteNews($stname,$wherec); 
				
				$wherecon= "productuomcode ='".$checkbox[$i]."'";
				$news->deleteNews($tname,$wherecon);
				?>
				<script type="text/javascript">
				alert("Deleted  Successfully!!",'productuom.php');
				//setInterval(function(){document.location='productgroupmaster.php';},2000);
				</script>
				<?		
				}
				else
				{
				?>
				<script type="text/javascript">
				alert("You Can't delete already send to Franchisee ",'productuom.php');
				</script>
				<?	
				}
		}
		else
		{

		?>
            <script type="text/javascript">
			alert("you can't delete already used in other masters!",'productuom.php');
			//setInterval(function(){document.location='productuom.php';},2000);
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
		
		$condition="SELECT * FROM productuom WHERE productuomcode like'".$_POST['codes']."%' or productuom like'".
		$_POST['names']."%'";
		$productuommaster=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$search1=$_POST['codes'];
		//$search2=$_POST['names'];
		$condition="SELECT * FROM productuom WHERE productuomcode like'".$_POST['codes']."%'";
		$productuommaster=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		//$search1=$_POST['codes'];
		$search2=$_POST['names'];
		$condition="SELECT * FROM productuom WHERE productuom like'".$_POST['names']."%'";
		$productuommaster=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM productuom WHERE 1";$productuommaster=$condition;
	}
	
	$refer=mysql_query($condition);
	$myrow1 = mysql_num_rows($refer);
	//mysql_fetch_array($query);
	
	$page = (int) (!isset($_GET["page"]) ? 1 : 1);
    	//$limit =$myrow1+1000;
    	$startpoint = ($page * $limit) - $limit;
        //to make pagination
        $statement = "productuom";
		 //show records
		 $starvalue = $myrow1;
       $query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
		if($myrow1==0)	
		{
			?>
		    <script type="text/javascript">
			alert("Data not found!");
			setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
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
        $statement = "`productuom`"; 
		$starvalue = "";
        $query = mysql_query("SELECT * FROM {$statement} order by m_date desc LIMIT {$startpoint} , {$limit}");
}
else
	{
	if(!empty($_SESSION['codesval'])&&!empty($_SESSION['namesval']))
	{
		
		$condition="SELECT * FROM productuom WHERE productuomcode like'".$_SESSION['codesval']."%' or productuom like'".
		$_SESSION['namesval']."%'";
		$productuommaster=$condition;
	}
	else if(!empty($_SESSION['codesval'])&&empty($_SESSION['namesval']))
	{
		$search1=$_SESSION['codesval'];
		//$search2=$_SESSION['namesval'];
		$condition="SELECT * FROM productuom WHERE productuomcode like'".$_SESSION['codesval']."%'";
		$productuommaster=$condition;
	}
	else if(!empty($_SESSION['namesval'])&&empty($_SESSION['codesval']))
	{
		//$search1=$_SESSION['codesval'];
		$search2=$_SESSION['namesval'];
		$condition="SELECT * FROM productuom WHERE productuom like'".$_SESSION['namesval']."%'";
		$productuommaster=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM productuom WHERE 1";$productuommaster=$condition;
	}
	
	$refer=mysql_query($condition);
	$myrow1 = mysql_num_rows($refer);
	//mysql_fetch_array($query);
	
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	//$limit =$myrow1+1000;
    	$startpoint = ($page * $limit) - $limit;
        //to make pagination
        $statement = "productuom";
		 //show records
		 $starvalue = $myrow1;
       $query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
}
}


if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
		unset($_SESSION['namesval']);
	header('Location:productuom.php');
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
		
		$condition="SELECT * FROM productuom WHERE productuomcode like'".$_POST['codes']."%' AND productuom like'".
		$_POST['names']."%'order by m_date DESC";
		$productuommaster=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$search1=$_POST['codes'];
		//$search2=$_POST['names'];
		$condition="SELECT * FROM productuom WHERE productuomcode like'".$_POST['codes']."%'order by m_date DESC";
		$productuommaster=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		//$search1=$_POST['codes'];
		$search2=$_POST['names'];
		$condition="SELECT * FROM productuom WHERE productuom like'".$_POST['names']."%'order by m_date DESC";
		$productuommaster=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM productuom order by m_date DESC";$productuommaster=$condition;
	}
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$productuommaster;
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($productuommaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
$stringData =$myrecord['productuomcode']."\t ;".$myrecord['productuom']."\t\n";
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	header('Location:ExportUOM.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$productuommaster;

	header('Location:ExportUOM.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$productuommaster;
	header('Location:ExportUOM.php');
}
	
}	
?>

<script type="text/javascript">

				
function validateProductCode(key)
{
var object = document.getElementById('productuomcode');
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
var object = document.getElementById('productuom');
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

 /* function isCharacterKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 47 || charCode > 48) && charCode >49)
            return true;

         return false;
      }*/


function AllowAlphabet(){
               if (!form1.productuomcode.value.match(/^[a-zA-Z]+$/) && form1.productuomcode.value !="")
               {
                    form1.productuomcode.value="";
                    alert("Please Enter only alphabets in text");
					 setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); productuomcode.focus(); }, 2000);
               }
}      


	 /* 
	  function numericFilter(txb) {
   txb.value = txb.value.match(/^[a-zA-Z]+$/, "");
}*/
</script>
<title><?php echo $_SESSION['title']; ?>|| Product UOM Master</title>
</head>

 <?php
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
  if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.productuom.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.productuomcode.focus()">

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
						<p>Product UOM Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Product UOM Code </label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                              <?php if(!empty($_GET['edi']))
							{?>
                           <input type="text" name="productuomcode" id="productuomcode" style="border-style:hidden; background:#f5f3f1;text-transform:uppercase;" readonly="readonly"  value="<?php echo $Productuomcode ;?>" onKeyPress="return validateProductCode(event)" onChange="return codetrim(this)" />
                            <? } 
							else { ?>
                                  <input type="text" name="productuomcode" id="productuomcode" value="<?php echo $Productuomcode ;?>" maxlength="15" onKeyPress="return validateProductCode(event)" onKeyUp="AllowAlphabet()" onChange="return codetrim(this)" style="text-transform:uppercase;"/>
                                   <?
							}
							?>
                              
                               </div>
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Product UOM </label><label style="color:#F00;">*</label>
                               </div>
                                <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                  <input type="text" name="productuom" id="productuom" maxlength="50" onKeyPress="return validate(event)" onKeyUp="AllowAlphabet()" value="<?php echo $Productuom ;?>" onChange="return trim(this)" style="text-transform:uppercase;"/>
                               </div>
                             <!--Row2 end-->  
                            
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
                                  <label>Product UOM Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:90px; height:30px; float:left; margin-left:15px; margin-top:14px;">
                                  <label>Product UOM</label>
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
     <td style=" font-weight:bold;">Product UOM Code</td>
     <td style=" font-weight:bold;">Product UOM </td>
     
 
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
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" onChange="test();" id="checkboxs" value="<? echo $record['productuomcode']; ?>" ></td> 
	 <?
	  } 
	 if(($row['editrights'])=='Yes')
	  { 
	 ?>
     
 	 <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center""> <a style="color:#0360B2" name="edit" href="productuom.php?edi=<?=  $record['productuomcode']?> ">Edit</a></td>
      <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['productuomcode']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['productuom']?>
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