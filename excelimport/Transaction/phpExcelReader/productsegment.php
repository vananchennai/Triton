<?php
	session_start();
	if (!isset($_SESSION['username']))
	{
	header('Location:../../index.php');
	}
    require_once 'inc/Productsegmentclass.php'; // Include The News Class
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews();
	
	global $productsegmentcode,$productsegment,$productgroup;
    if(isset($_POST['Save'])) // If the submit button was clicked
    {
	
        $post['ProductSegmentCode'] = $_POST['ProductSegmentCode'];
		$post['ProductSegment'] = $_POST['ProductSegment'];
        $post['ProductGroup'] = $_POST['productgroup'];
 
        // This will make sure its displayed
		if(!empty($_POST['ProductSegment'])&&!empty($_POST['ProductSegmentCode'])&&!empty($_POST['productgroup']))
		{   
		$result="SELECT * FROM productsegmentmaster where ProductSegmentCode ='".$post['ProductSegmentCode']."'";
		$sql1 = mysql_query($result) or die (mysql_error());
 		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
			if($myrow1>0)
			{
			?>
            <script type="text/javascript">
			alert("Duplicate entry..!!");document.location='productsegment.php';	
			</script>
        	 <?
			}
			else
			{
			$news->addNews($post);
			?>
            <script type="text/javascript">
			alert("Created Sucessfully..!!");document.location='productsegment.php';
			</script>
            <?
			}
        }
	
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields");document.location='productsegment.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
		
		$post['ProductSegmentCode'] = $_POST['ProductSegmentCode'];
		$post['ProductSegment'] = $_POST['ProductSegment'];
        $post['ProductGroup'] = $_POST['productgroup'];
 
        // This will make sure its displayed
		if(!empty($_POST['ProductSegment'])&&!empty($_POST['ProductSegmentCode'])&&!empty($_POST['productgroup']))
		{ 
						$news->editNews($post);
						
						?>
            <script type="text/javascript">
			alert("Updated Sucessfully..!!");document.location='productsegment.php';
			</script>
            <?
					
					
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields");document.location='productsegment.php';
			</script>
            <?
		}
	}
	
/// EDIT LINK FUNCTION 
if(!empty($_GET['edi']))
{
$prmaster =$_GET['edi'];

//$cont->connect();
$result=mysql_query("SELECT * FROM productsegmentmaster where ProductSegmentCode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!!");document.location='productsegment.php';
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		
		   $productsegmentcode = $myrow['ProductSegmentCode'];
		   $productsegment = $myrow['ProductSegment'];
		   $productgroup = $myrow['ProductGroup'];
		 
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
			alert("Select data to delete!!");document.location='productsegment.php';
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
		///$prodid= $_POST['checkbox'];
		$news->deleteNews($prodidd);
		}
			?>
            <script type="text/javascript">
			alert("Deleted  Successfully!!");document.location='productsegment.php';
			</script>
   			<?
}
}
 include_once ('inc/paginationfunction.php');
	   $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
        $statement = "`productsegmentmaster`"; 
		$starvalue = "";
        $query = mysql_query("SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}");


if(isset($_POST['Search']))
{
if(isset($_POST['codes'])||isset($_POST['names']))
{
	if(empty($_POST['codes'])&&empty($_POST['names']))
	{
		?>
		    <script type="text/javascript">
			alert("Enter text Field!!");document.location='productsegment.php';
			</script>
			<?
	}
	else
	{
	if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM productsegmentmaster WHERE ProductSegmentCode like'".$_POST['codes']."%' OR ProductSegment like'".
		$_POST['names']."%'";
		
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM productsegmentmaster WHERE ProductSegmentCode like'".$_POST['codes']."%'";
		
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM productsegmentmaster WHERE ProductSegment like'".$_POST['names']."%'";
		
	}
	else
	{
		
		$condition="SELECT * FROM productsegmentmaster WHERE 1";
	}
	
	$refer=mysql_query($condition);
	$myrow1 = mysql_num_rows($refer);
	//mysql_fetch_array($query);
	
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    	$limit =10;
    	$startpoint = ($page * $limit) - $limit;
        //to make pagination
        $statement = "productsegmentmaster";
		 //show records
		 $starvalue = $myrow1;
       $query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
		if($myrow1==0)	
		{
			?>
		    <script type="text/javascript">
			alert("Entered keyword not found!!");document.location='productsegment.php';
			</script>
			<?
		
		}
	}
}

}


if(isset($_POST['Cancel']))
{
	header('Location:productsegment.php');
}

if(isset($_POST['Submit']))
{
//echo $ufile;
GLOBAL $HTTP_POST_FILES;

//$path= $HTTP_POST_FILES['ufile']['name'];
$path= $_FILES['ufile']['name'];
echo"Selected file :"+ $path;
if(copy($_FILES['ufile']['tmp_name'], $path))
{
$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password=""; // Mysql password 
$db_name="amararaja"; // Database name 
$tbl_name="productsegmentmaster"; // Table name 
require_once '.\phpExcelReader\Excel\reader.php';
$excel = new Spreadsheet_Excel_Reader();
$excel->setOutputEncoding('CP1251');
$excel->read($path);
$x=2;
$sep = "*";
ob_start();
while($x<=$excel->sheets[0]['numRows']) {
$y=1;
$row="";
while($y<=$excel->sheets[0]['numCols']) {
$cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
$row.=($row=="")?"".$cell."":"".$sep."".$cell."";
$y++;
} 
echo $row."\n"; 
$x++;
}
	//echo "Sheet count:$x";
$fp = fopen("data.csv",'w');
fwrite($fp,ob_get_contents());
fclose($fp);
echo"----";	
ob_end_clean();
//connect to the database 
$connect = mysql_connect("localhost:81","root",""); 
mysql_select_db("AmaraRaja",$connect); //select the table 
$file = $_FILES['./data.csv'][tmp_name]; 
echo"$file";
$handle = fopen('data.csv',"r"); 
//loop through the csv file and insert into database 
do { 
if ($data[0]) { 
mysql_query("INSERT INTO productsegmentmaster VALUES ( '".addslashes($data[0])."', '".addslashes($data[1])."','".addslashes($data[2])."')"); 

} 
} while ($data = fgetcsv($handle,1000,"*","'")); 
}
fclose($handle);
echo"<script>alert('File $path Imported Successfully')</script>";
unlink('data.csv');
unlink($path);
}

?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/style.css" />
<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<link href="../../css/grid.css" rel="stylesheet" type="text/css">
<link href="../../css/reveal.css" rel="stylesheet" type="text/css">
<link href="../../css/A_red.css" type="text/css" rel="stylesheet" />
<link href="../../css/pagination.css" type="text/css" rel="stylesheet" />
<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery.reveal.js"></script>
<script src="../../js/sorttable.js"></script>
<script type="text/javascript">
function nospaces(t){
if(t.value.match(/\s/g)){
alert('Sorry, you are not allowed to enter any spaces');
t.value=t.value.replace(/\s/g,'');
}
}
</script>
<title>Amaron&nbsp;|&nbsp;Home</title>
</head>
<?php /*?><?php
  $uri=$_SERVER['REQUEST_URI'];
  $pagee=substr($uri,strrpos($uri,"/")+1);
  //echo $page;
?><?php */?>
<body><center>

<!--First Block - Logo-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:130px; float:none;">
           <div style="width:250px; height:130px; float:left; background-image:url(../../img/logo_amaron.png);">
          
           </div>
     </div>       
</div>
<!--First Block - End-->


<!--Second Block - Menu-->
<div style="width:100%; height:50px; float:none; background:url(../../img/menubg.jpg) repeat-x;">
     <div style="width:980px; height:50px; float:none;"> 
       <?php include("../../menu.php")?>
     </div>       
</div>
<!--Second Block - Menu -End -->

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
            <div style="width:930px; height:auto; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Product Segment Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:300px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Product Segment Code</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                               <input type="text" name="ProductSegmentCode" onKeyUp="nospaces(this)" value="<?php echo $productsegmentcode;?>"/>
                               </div>
 							<!--Row1 end--> 
                            
                            <!--Row2 -->  
                               <div style="width:145px; height:30px; float:left;  margin-top:5px; margin-left:3px;">
                                  <label>Product Segment Name</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <input type="text" name="ProductSegment" onKeyUp="nospaces(this)" value="<?php echo $productsegment;?>"/>
                               </div>
                             <!--Row2 end-->  
                             
                            <!--Row3 -->  
                               <div style="width:145px; height:30px;  float:left; margin-top:5px; margin-left:3px;">
                                 <label>Product Group</label><label style="color:#F00;">*</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                    <select name="productgroup">
                                    <option value="<?php echo $productgroup;?>"><?php echo $productgroup;?></option>
                                    <option value="BATTERIES">BATTERIES</option>                                
                                    <option value="HOMEUPS">HOMEUPS</option>                                   
                                    <option value="TUBULAR">TUBULAR</option>                                   
                                    <option value="POP">POP</option>                                     
                                    <option value="Others">Others</option>                                    
                                   </select>
                               </div>
                             <!--Row3 end-->   
                             
                                                              
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                      
                           <table width="500" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td><strong>Select File to Import </strong></td>
</tr>
<tr>
<td>Select file 
<input name="ufile" type="file" id="ufile" size="50" /></td>
</tr>
<tr>
<td align="center"><input type="submit" name="Submit" value="Import to Database" /></td>
</tr>
</table>
</td>

</tr>
</table>
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
                             
					<div style="width:340px; height:50px; float:left;  margin-left:4px; margin-top:0px;" id="center1">
						   
                          <div style="width:70px; height:32px; float:left; margin-top:16px; margin-left:15px;">
						   <input name="Save" type="submit" class="button" value="Save">
				           </div>	
                                                    
                            <div style="width:80px; height:32px; float:left;margin-top:16px; ">
						 	<input name="Update" type="submit" class="button" value="Update">
				           </div>
                           
                           <div style="width:80px; height:32px; float:left;margin-top:16px; ">
						   <input name="Delete" type="submit" class="button" value="Delete">
				           </div>
                           
                           <div style="width:80px; height:32px; float:left;margin-top:16px;">
						  <input name="Cancel" type="submit" class="button" value="Cancel">
				           </div>                            
                                                   
				     </div>	
                         
                          <div style="width:560px; height:50px; float:left;  margin-left:15px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:80px; height:30px; float:left; margin-left:3px; margin-top:16px;" >
                                  <label>Product Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" value=""/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:9z0px; height:30px; float:left; margin-left:3px; margin-top:16px;">
                                  <label>Product Name</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                  <input type="text" name="names" value=""/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:83px; height:32px; float:left; margin-top:16px;">
                                <input type="submit" name="Search" value="" class="button1"/>
                               </div>  
                          </div> 
                </div>
                
                <!--Main row 2 end-->
                
              <!--  grid start here-->
                
               <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px;" class="grid">
                   
                  <table align="center" class="sortable" bgcolor="#FF0000" border="1" width="900px">
     
     <td class="sorttable_nosort" style=" font-weight:bold; text-align:center" width="8px">Select</td>
     <td class="sorttable_nosort"  style=" font-weight:bold; text-align:center" >Action</td>
     <td style=" font-weight:bold;">Product Segment Code</td>
     <td style=" font-weight:bold;">Product Segment Name</td>
     <td style=" font-weight:bold;">Product Group </td>
     
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($query))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
    ?>
    
     <tr>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $record['ProductSegmentCode']; ?>"></td>
 	 <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center""> <a style="color:#FF2222" name="edit" href="productsegment.php?edi=<? echo $record['ProductSegmentCode'];?>">Edit</a></td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['ProductSegmentCode']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
        <?=$record['ProductSegment']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['ProductGroup']?>
    </td>
   
</tr>
  <?php
      }
  ?>
</table>
</div>
<br />
  <?php
			echo pagination($starvalue,$statement,$limit,$page);
		?>
       <!--  grid end here-->
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