<?
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
include("../../header.php");
$news = new News(); 

if(!empty($_GET['logiccode']))
//if(!empty($_GET['logiccode']))
{
	$logiccode  = $_GET['logiccode'];
	$effectivedate  = $_GET['effectivedate'];
	$proratalogic="SELECT * FROM proratalogic_view where logiccode ='".$logiccode."' AND effectivedate ='".$effectivedate."'";
	$result=mysql_query($proratalogic);
	$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!!");window.close();
			</script>
   			<?
		}
		
}
 	if(isset($_POST['PDF']))
{

$select=$_POST['Type'];

if($select=='PDF')
{
$_SESSION['type']='PDF';
$_SESSION['query']=$proratalogic;
$myFile = "testFile.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
$stringData =NULL;
$myquery = mysql_query($proratalogic);
	while( $myrecord = mysql_fetch_array($myquery))
	{
		$mfgdate= date('d/m/Y', strtotime($myrecord[2]));
	
	if($mfgdate=='01/01/1970')
	{
		$mfgdate='00/00/0000';
	}
	$stringData =$myrecord[0]."\t ;".$myrecord[1]."\t ;".$mfgdate."\t;".$myrecord[3]."\t ;".$myrecord[4]."\t;".$myrecord[5]."\t;".$myrecord[6]."\t;\n";
	fwrite($fh, $stringData);
	}
	//	
	fclose($fh);
	$proratalogic;
	header('Location:Exportlogic.php');
	}
	elseif($select=='Excel')
	{
	$_SESSION['type']='Excel';
	$_SESSION['query']=$proratalogic;
	header('Location:Exportlogic.php');
	}
	elseif($select=='Document')
	{
	$_SESSION['type']='Document';
	$_SESSION['query']=$proratalogic;
	header('Location:Exportlogic.php');
	}
	
	}
?>

</head>

<body>
<form method="POST" action="<?php $_PHP_SELF ?>" name="myForm" id="form1"  >
<div style=" overflow:auto ; " class="grid" >
 <table align="left" class="sortable" border="1"  style="overflow:auto"  >
    <tr > 
  <td  align="center" style=" font-weight:bold;">Category</td>
  <td style=" font-weight:bold;">Logic Code</td>
  <td style=" font-weight:bold;">Effective Date</td>
  <td style=" font-weight:bold;">Warranty Months Minimum</td>
  <td style=" font-weight:bold;">Warranty Months Maximum</td>
  <td style=" font-weight:bold;">Discount</td>
</tr>
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($result))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
	  $mfgdate= date('d/m/Y', strtotime($record['effectivedate']));
	
	if($mfgdate=='01/01/1970')
	{
		$mfgdate='00/00/0000';
	}
    ?>
    
     <tr>
      <td  bgcolor="#FFFFFF"  align="left">
        <?=$record['category']?>
    </td>
   <td  bgcolor="#FFFFFF">
        <?=$record['logiccode']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
        <?=$mfgdate?>
    </td>
  
     <td  bgcolor="#FFFFFF"  align="left">
        <?=$record['min']?>
    </td>
    <td  bgcolor="#FFFFFF"  align="left">
        <?=$record['max']?>
    </td>
  
     <td  bgcolor="#FFFFFF"  align="left">
        <?=$record['discount']?>
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