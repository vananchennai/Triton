<?
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
$news = new News(); 
if(!empty($_GET['pricelistcode']))
{
	$prmaster  = $_GET['pricelistcode'];
	$pricelistmaster="SELECT * FROM pricelistmaster where pricelistcode ='".$prmaster."'  order by id ";
	$result=mysql_query("SELECT * FROM pricelistmaster where pricelistcode ='".$prmaster."'  order by id ");
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
	$_SESSION['query']=$pricelistmaster;
	
	$myFile = "testFile.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData =NULL;
	$myquery = mysql_query($pricelistmaster);
	while( $myrecord = mysql_fetch_array($myquery))
   {
	   $eft=date("d/m/Y",strtotime($myrecord[2])) ;
      if($eft == '01/01/1970')
      {
      $eft="00/00/0000";
      }
      $aplt=date("d/m/Y",strtotime($myrecord[3])) ;
      if($aplt == '01/01/1970')
      {
      $aplt="00/00/0000";
      }
	  $Productgroupselct="SELECT ProductDescription FROM productmaster where ProductCode='".$myrecord['productcode']."'";
	   $Productgroupselct1 = mysql_query($Productgroupselct);
	   $Productcntno1=mysql_num_rows($Productgroupselct1);
	   if($Productcntno1==1)
   		{
		   	$Productgroupselct12 = mysql_fetch_array($Productgroupselct1);
			$Producttesttempp=$Productgroupselct12['ProductDescription'];
	   }
	    else
	   {
		   $Producttesttempp ="";
	   }
    $stringData =$myrecord[0]."\t ;".$myrecord[1]."\t ;".$eft."\t;".$aplt."\t ;".$myrecord[4]."\t ;".$Producttesttempp."\t;".$myrecord[5]."\t ;".$myrecord[6]."\t ;".$myrecord[7]."\t ;".$myrecord[8]."\t  ;\n";
		fwrite($fh, $stringData);
 		
			
	}
//	
	fclose($fh);
	$pricelistmaster;
	header('Location:ExportPriceList.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$pricelistmaster;
	header('Location:ExportPriceList.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$pricelistmaster;
	header('Location:ExportPriceList.php');
}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/style.css" />
<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<title><?php echo $_SESSION['title']; ?>|&nbsp;PricelistGrid</title>
<link href="../../jquery/css/smoothness/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<link href="../../css/A_red.css" rel="stylesheet" type="text/css"/>
<link href="../../css/pagination.css" rel="stylesheet" type="text/css"/>
<script src="../../js/sorttable.js"></script>
<script src="../../js/jquerymin.js"></script>
<script src="../../js/jqueryuimin.js"></script>
</head>

<body>
<form method="POST" action="<?php $_PHP_SELF ?>" name="myForm" id="form1"  >
<div style=" height:auto; overflow:auto ; " class="grid" >
 <table align="left" class="sortable" border="1" style="overflow:auto"  >
    <tr > 
  <!--<td  align="center" style=" font-weight:bold;">Price list Code</td>
  <td style=" font-weight:bold;">Price list Name</td>
   <td style=" font-weight:bold;">Pricelist Description</td>
  <td style=" font-weight:bold;">Effective Date</td>
  <td style=" font-weight:bold;">Applicable Till</td>-->
    <td style=" font-weight:bold;">Product Name</td>
   <td style=" font-weight:bold;">Product Description</td>
<td style=" font-weight:bold;">Consumer Price</td>
  <td style=" font-weight:bold;">Distributor Price</td>
  <td style=" font-weight:bold;">Retailer Price</td>
  <td style=" font-weight:bold;">Industrial Price</td>
    
  
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
        <?=$record['productcode']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
       <?php $pg= mysql_query("select ProductDescription from productmaster where ProductCode='".$record['productcode']."' ")  ;
		$record1 = mysql_fetch_array($pg);
       echo $record1['ProductDescription']; ?>
       
    </td>
     <td  bgcolor="#FFFFFF" align="left">
        <?=$record['mrp']?>
    </td>
     <td  bgcolor="#FFFFFF">
        <?=$record['fprice']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
        <?=$record['rprice']?>
    </td>
     <td  bgcolor="#FFFFFF" align="left">
        <?=$record['iprice']?>
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