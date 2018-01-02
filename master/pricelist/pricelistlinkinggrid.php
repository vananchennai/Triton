
<?
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
$news = new News(); 
if(!empty($_GET['pricelistcode']) && !empty($_GET['fnchisee']))
{
	$prmaster  = $_GET['pricelistcode'];
	$Franchisee = $_GET['fnchisee'];
	$pricelistmaster="SELECT * FROM pricelistlinkinggrid where PriceListCode ='".$prmaster."' AND Franchisee ='".$Franchisee."'   order by id  desc";
	$result=mysql_query("SELECT * FROM pricelistlinkinggrid where PriceListCode ='".$prmaster."' AND Franchisee ='".$Franchisee."'  order by id  desc");
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
	    $countryselct="SELECT countryname FROM countrymaster where countrycode='".$myrecord['Country']."'";
	   $countryselct1 = mysql_query($countryselct);
	   $cntno4=mysql_num_rows($countryselct1);
	   if($cntno4==1)
   		{
		   	$countryselct12 = mysql_fetch_array($countryselct1);
			$countrytempp=$countryselct12['countryname'];
	   }
	    else
	   {
		   $countrytempp ="";
	   }
	   $stateselct="SELECT statename FROM state where statecode='".$myrecord['State']."'";
	   $stateselct1 = mysql_query($stateselct);
	   $cntno2=mysql_num_rows($stateselct1);
	   if($cntno2==1)
   		{
		   	$stateselct12 = mysql_fetch_array($stateselct1);
			$statetempp=$stateselct12['statename'];
	   }
	    else
	   {
		   $statetempp ="";
	   }
	   $groupselct="SELECT branchname FROM branch where branchcode='".$myrecord['Branch']."'";
	   $groupselct1 = mysql_query($groupselct);
	   $cntno1=mysql_num_rows($groupselct1);
	   if($cntno1==1)
   		{
		   	$groupselct12 = mysql_fetch_array($groupselct1);
			$testtempp=$groupselct12['branchname'];
	   }
	    else
	   {
		   $testtempp ="";
	   }
	    $productgroupselct="SELECT ProductDescription FROM productmaster where ProductCode='".$myrecord['productcode']."'";
	   $productgroupselct1 = mysql_query($productgroupselct);
	   $productcntno1=mysql_num_rows($productgroupselct1);
	   if($productcntno1==1)
   		{
		   	$productgroupselct12 = mysql_fetch_array($productgroupselct1);
			$producttesttempp=$productgroupselct12['ProductDescription'];
	   }
	    else
	   {
		   $producttesttempp ="";
	   }
	    $stringData =$myrecord[0]."\t ;".$myrecord[1]."\t ;".$myrecord[5]."\t ;".$countrytempp."\t ;".$statetempp."\t ;".$testtempp."\t ;".date("d/m/Y",strtotime($myrecord[6]))."\t ;".date("d/m/Y",strtotime($myrecord[7]))."\t ;".$myrecord[8]."\t ;".$producttesttempp."\t ;".$myrecord[9]."\t ;".$myrecord[10]."\t ;".$myrecord[11]."\t ;".$myrecord[12]."\t ;\n";
		fwrite($fh, $stringData);
	}
	
	fclose($fh);
	$pricelistmaster;
	header('Location:ExportPriceLink.php');
}
elseif($select=='Excel')
{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$pricelistmaster;
	header('Location:ExportPriceLink.php');
}
elseif($select=='Document')
{
	$_SESSION['type']='Document';
	$_SESSION['query']=$pricelistmaster;
	header('Location:ExportPriceLink.php');
}
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/style.css" />
<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<title><?php echo $_SESSION['title']; ?> || Pricelist Linking</title>
<link href="../../jquery/css/smoothness/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<link href="../../css/A_red.css" rel="stylesheet" type="text/css"/>
<link href="../../css/pagination.css" rel="stylesheet" type="text/css"/>
<script src="../../js/sorttable.js"></script>
<script src="../../js/jquerymin.js"></script>
<script src="../../js/jqueryuimin.js"></script>
</head>

<body>
<form method="POST" action="<?php $_PHP_SELF ?>" name="myForm" id="form1"  >
<div style=" height:auto; overflow:auto ; "class="grid" >
        
                 <table align="left" bgcolor="#00FF99" class="sortable" border="1" style="overflow:auto; " >   
                   <tr >                    
   
  
   <td style=" font-weight:bold;">PriceListCode</td>
   <td style=" font-weight:bold;">PriceListName</td>
   
   <td style=" font-weight:bold;">Country</td>
   <td style=" font-weight:bold;" >State</td>
   <td style=" font-weight:bold;">Branch</td>
   <td style=" font-weight:bold;">Distributor</td>
   <td style=" font-weight:bold;">EffectiveDate</td>
  <td style=" font-weight:bold;">ApplicableTill</td>
  <td style=" font-weight:bold;">ProductCode</td>
  <td style=" font-weight:bold;">ProductDescription</td>
	<td style=" font-weight:bold;">ConsumerPrice</td>
    <td style=" font-weight:bold;">DistributorPrice</td>
  <td style=" font-weight:bold;">RetailerPrice</td>
	<td style=" font-weight:bold;">IndustrialPrice</td>
   
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($result))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
    ?>
    
     <tr>
   
    <td  bgcolor="#FFFFFF"> <?=$record['PriceListCode']?> </td>
    <td  bgcolor="#FFFFFF"><? $checkname= mysql_query("select pricelistname from masterpricelist where pricelistcode='".$record['PriceListCode']."'");
		$checkrecordn = mysql_fetch_array($checkname);
       echo $checkrecordn['pricelistname'];  ?>  </td>
    
    <td  bgcolor="#FFFFFF"> <? $check= mysql_query("select countryname from countrymaster where countrycode='".$record['Country']."' ")  ;
		$checkrecord = mysql_fetch_array($check);
       echo $checkrecord['countryname'];  ?> </td>
    <td  bgcolor="#FFFFFF"> <?  $check2= mysql_query("select statename from state where statecode='".$record['State']."' ")  ;
		$check2record = mysql_fetch_array($check2);
       echo $check2record['statename']; ?>   </td>
   <td  bgcolor="#FFFFFF"> <? $check3= mysql_query("select branchname from branch where branchcode='".$record['Branch']."' ")  ;
		$check3record = mysql_fetch_array($check3);
       echo $check3record['branchname']; ?>  </td>
   <td  bgcolor="#FFFFFF"> <?=$record['Franchisee']?> </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=date("d/m/Y",strtotime($record['effectivedate']))?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
        <?=date("d/m/Y",strtotime($record['applicabledate']))?>
    </td>
     <td  bgcolor="#FFFFFF" align="left">
        <?=$record['productcode']?>
    </td>
    
    <td  bgcolor="#FFFFFF" align="left">
    <? $check1= mysql_query("select ProductDescription from productmaster where ProductCode='".$record['productcode']."' ")  ;
		$check1record = mysql_fetch_array($check1);
       echo $check1record['ProductDescription']; ?> 
       
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['mrp']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
        <?=$record['fprice']?>
    </td>
    <td  bgcolor="#FFFFFF" align="left">
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