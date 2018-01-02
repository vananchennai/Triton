<?
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
include("../../header.php");
$news = new News(); 

if(!empty($_GET['ProductCode']))
//if(!empty($_GET['ProductCode']))
{
	$prmaster  = $_GET['ProductCode'];
	//$prmaster  = $_GET['ProductCode'];
	/* $productmaster="SELECT * FROM masterproduct where ProductCode ='".$prmaster."'  order by id";
	$result=mysql_query("SELECT * FROM masterproduct where ProductCode ='".$prmaster."'  order by id"); */
	$productmaster="SELECT * FROM productmaster where ProductCode ='".$prmaster."'  order by m_date";
	$result=mysql_query("SELECT * FROM productmaster where ProductCode ='".$prmaster."'  order by m_date");
	$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!!");window.close();
			</script>
   			<?
		}
		else
		{
	     
		}
		
}
 	if(isset($_POST['PDF']))
{

$select=$_POST['Type'];

if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$productmaster;
	
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($productmaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
	
/* 	 $groupgg="SELECT * FROM producttypemaster where ProductTypeCode='".$myrecord['ProductType']."'"; */
       $groupgg="SELECT * FROM producttypemaster where ProductTypeCode='".$myrecord['ProductType']."'";
	   $groupgg1 = mysql_query($groupgg);
	 $cntnog=mysql_num_rows($groupgg1);
	 if($cntnog==1)
	  {
		   	$groupgg2 = mysql_fetch_array($groupgg1);
			$testtemp=$groupgg2['ProductTypeName'];
	   }
	   else
	   {
		   $testtemp ="";
	   }
	   /*$group="SELECT * FROM productuom where productuomcode='".$myrecord['UOM']."'";
	   $gro = mysql_query($group);
	 $cnt=mysql_num_rows($gro);
	 if($cnt==1)
	  {
		   	$g2 = mysql_fetch_array($gro);
			$test=$g2['productuom'];
	   }
	   else
	   {
		   $test ="";
	   }*/
   $stringData =$myrecord[0]."\t ;".$myrecord[1]."\t ;".$myrecord[2]."\t;".$myrecord[3]."\t;\n";
		fwrite($fh, $stringData);
			
	}
//	
	fclose($fh);
	$productmaster;
	header('Location:ExportProduct.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$productmaster;
	header('Location:ExportProduct.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$productmaster;
	header('Location:ExportProduct.php');
}
	
}
?>
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/style.css" />
<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<title>Amaron&nbsp;|&nbsp;Home</title>
<link href="../../jquery/css/smoothness/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<link href="../../css/A_red.css" rel="stylesheet" type="text/css"/>
<link href="../../css/pagination.css" rel="stylesheet" type="text/css"/>
<script src="../../js/sorttable.js"></script>
<script src="../../js/jquerymin.js"></script>
<script src="../../js/jqueryuimin.js"></script>-->
</head>

<body>
<form method="POST" action="<?php $_PHP_SELF ?>" name="myForm" id="form1"  >
<div style=" overflow:auto ; " class="grid" >
 <table align="left" class="sortable" border="1"  style="overflow:auto"  >
    <tr > 
  <!--<td  align="center" style=" font-weight:bold;">Price list Code</td>
  <td style=" font-weight:bold;">Price list Name</td>
   <td style=" font-weight:bold;">Pricelist Description</td>
  <td style=" font-weight:bold;">Effective Date</td>
  <td style=" font-weight:bold;">Applicable Till</td>-->
  <td  align="center" style=" font-weight:bold;">Product Code</td>
  <td style=" font-weight:bold;">Product Description</td>
 <!--  <td style=" font-weight:bold;">Pricelist Description</td>-->
  <td style=" font-weight:bold;">Product Group</td>

  <td style=" font-weight:bold;">UOM</td>

</tr>
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($result))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
    ?>
    
     <tr>
   <!-- <td  bgcolor="#FFFFFF">
        <?$record['pricelistcode']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
        <?$record['pricelistname']?>
    </td>
     <td  bgcolor="#FFFFFF" align="left">
        <?$record['pricelistdescription']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?date("d/m/Y",strtotime($record['effectivedate']))?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
        <?date("d/m/Y",strtotime($record['applicabledate']))?>
    </td>-->
   <td  bgcolor="#FFFFFF">
        <?=$record['ProductCode']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
        <?=$record['ProductDescription']?>
    </td>
  <td  bgcolor="#FFFFFF">
    <?php /* $pg= mysql_query("select * from producttypemaster where ProductTypeCode='".$record['ProductType']."' ")  ;
		$record1 = mysql_fetch_array($pg);
       echo $record1['ProductGroupCode']; */ ?> 
         <?=$record['ProductGroupCode']?>
    </td> 

    <td  bgcolor="#FFFFFF"  align="left">
    <?php /*$pg1= mysql_query("select * from productuom where productuomcode='".$record['UOM']."' ")  ;
		$record11 = mysql_fetch_array($pg1);
       echo $record11['productuom']; */?>
         <?=$record['UOMCode']?>
        
    </td>
<!--     <td  bgcolor="#FFFFFF"  align="left">
        <?/* =$record['SalesType'] */?>
    </td> -->

    </td>
    </tr>  
  <?php
      }
  ?>
  
</table>

</div>
<div style=" float:right; border:1px">
<div style="width:83px; height:25px; float:left; margin-right:15px; margin-top:12px;">
                                <select name="Type">
                                   <option value="PDF">PDF</option>
                                    <option value="Excel">Excel</option>
                                     <option value="Document">Document</option>
                                                                   </select>
             				
                               </div>
                               <div style="width:63px; height:25px; float:right; margin-right:15px; margin-top:10px;">
             					  <input type="submit" name="PDF" value="Export" class="button"/>
                                  
                  </div >
                  </div></form>
</body>
</html>