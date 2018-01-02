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
	global $tname,$ttname,$pricelistcode,$pricelistname,$effectivedate,$applicabledate,$productcode,$productdescription,$fprice,$rprice,$mrp,$iprice,$result12,$scode,$sname,$pricelistdescription,$effectivedate1,$applicabledate1,$i,$mrpmine,$fpricemine,$rpricemine,$ipricemine;
	$scode = 'pricelistcode';
	$sname = 'pricelistname';
	$tname = 'masterpricelist';
	$ttname	= "pricelistmaster";
	require_once '../../searchfun.php';
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($ttname);
	$pagename = "Pricelist";
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
			alert("you are not allowed to do this action!",'pricelistmaster.php');//document.location='pricelistmaster.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'pricelistmaster.php');//document.location='pricelistmaster.php';	
			</script>
         <?
		
	}
if(isset($_POST['Save'])) // If the submit button was clicked
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		$test="";
		$test1="";
        $post['pricelistcode'] = strtoupper(str_replace('&', 'and',$_POST['pricelistcode']));
		$post['pricelistname'] = strtoupper(str_replace('&', 'and',$_POST['pricelistname']));
		
		if($_POST['effectivedate']!="" && $_POST['effectivedate']!="00/00/0000" && $_POST['effectivedate']!="0000-00-00" )
		{
			$test = $news->dateformat($_POST['effectivedate']);
			$effectivedate=$_POST['effectivedate'];//$test;
		}
		
		else
		{
			$test="00/00/0000";
			$effectivedate='00/00/0000';
		}
		$post['effectivedate'] =$test; 
		
		if($_POST['applicabledate']!="" && $_POST['applicabledate']!="00/00/0000" && $_POST['applicabledate']!="0000-00-00")
		{
		$test1 = $news->dateformat($_POST['applicabledate']);
		$applicabledate=$_POST['applicabledate'];//$test1;
		}
		else
		{
			$test1="00/00/0000";
			$applicabledate='00/00/0000';
		}
		
		$post['effectivedate'] = $test;
		$post['applicabledate'] = $test1;
		//$post['pricelistdescription']=trim($_POST['pricelistdescription']);	
        // $pricelistdescription=$post['pricelistdescription'];
		$pricelistcode=strtoupper(str_replace('&', 'and',$_POST['pricelistcode']));
		$pricelistname=strtoupper(str_replace('&', 'and',$_POST['pricelistname']));
		
		
		$effectivedate1=$_POST['effectivedate'];
		$applicabledate1=$_POST['applicabledate'];
		$productcode=$_POST['productcode'];
		$productdescription=$_POST['productdescription'];
		$mrp=$_POST['mrp'];
		$fprice=$_POST['fprice'];
		$rprice=$_POST['rprice'];
		$iprice=$_POST['iprice'];
		
		if(!empty($_POST['pricelistcode'])&&!empty($_POST['pricelistname'])&&!empty($_POST['effectivedate']))
		{   
		
	$p1=strtoupper( preg_replace('/\s+/', '',$post['pricelistcode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['pricelistname']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `pricelistcode` ,  ' ',  '' ) AS pricelistcode, REPLACE(  `pricelistname` ,  ' ',  '' ) AS pricelistname FROM pricelistmaster where pricelistname = '".$p2."' or pricelistname = '".$post['pricelistname']."' or pricelistcode = '".$p2."' or pricelistcode = '".$post['pricelistname']."' or pricelistcode = '".$p1."' or pricelistcode = '".$post['pricelistcode']."' or pricelistname = '".$p1."' or pricelistname = '".$post['pricelistcode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
		if($cnduplicate>0 || ($post['pricelistcode']==$post['pricelistname']))
		{
		?>
            <script type="text/javascript">
			alert("Duplicate entry!");
			</script>
         <?
		}
		else
		{	
			$mtabl='masterpricelist';
			
			$productcode=$_POST['productcode'];
			$productdescription=$_POST['productdescription'];
			$mrp=$_POST['mrp'];
			$fprice=$_POST['fprice'];
			$rprice=$_POST['rprice'];
			$iprice=$_POST['iprice'];
			$numcnt=count($_POST['productcode']);
			
			$j=0;
			$spost['pricelistcode'] = strtoupper(str_replace('&', 'and',$_POST['pricelistcode']));
			$spost['pricelistname'] = strtoupper(str_replace('&', 'and',$_POST['pricelistname']));
			
			if($_POST['effectivedate']!="")
			{
				 $test = $news->dateformat($_POST['effectivedate']);
			}
			else
			{
				$test="";
			}
			
			$spost['effectivedate'] =$test; 
			
			if($_POST['applicabledate']!="")
			{
				$test1 = $news->dateformat($_POST['applicabledate']);
			}
			else
			{
				$test1="";
			}
			
			$spost['applicabledate'] = $test1;
			for($i=0;$i<$numcnt;$i++)
			{
					$spost['productcode'] = strtoupper(trim($productcode[$i]));	
					//$spost['productdescription'] = trim($productdescription[$i]);
				 $spost['mrp'] = trim($mrp[$i]);	
					$spost['fprice'] = trim($fprice[$i]);
					 $spost['rprice'] = trim($rprice[$i]);	
					$spost['iprice'] = trim($iprice[$i]);
					if(!empty($spost['productcode'])) //&&!empty($spost['productdescription'])
					{ 
						if(($spost['mrp']=='' || $spost['mrp']== '0.00') && ($spost['fprice']=='' || $spost['fprice']== '0.00') && ($spost['rprice']=='' || $spost['rprice']== '0.00') && ($spost['iprice']=='' || $spost['iprice']== '0.00'))
						{
							?>
							<script type="text/javascript">
                            alert("Enter Valid Values in Grid!");//document.location='pricelistmaster.php';
                            </script>
                            <?	
						}
						else
						{
						$wherecon= " pricelistcode ='".$post['pricelistcode']."'";
					    if($i==0)
						{
						 $post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
						$news->addNews($post,$mtabl);
						 //$news->editNews($post,$mtabl,$wherecon);
						}
						$result="SELECT * FROM pricelistmaster where pricelistcode ='".$post['pricelistcode']."' and productcode='".$spost['productcode']."'";
						$sql1 = mysql_query($result) or die (mysql_error());
						$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
						if($myrow1==0)
						{
								$news->addNews($spost,$ttname);
						}
						$j++;
						}
					
					}
					
			}
			
		
						if($j==0)
						{
							?>
							<script type="text/javascript">
                            alert("Enter Mandatory Fields in the Grid!");//document.location='pricelistmaster.php';
                            </script>
                            <?
							
							/*if($effectivedate!='00/00/0000')
							{
								$effectivedate = date("d/m/Y",strtotime($effectivedate));
							}
							
							if($applicabledate!='00/00/0000')
							{
								$applicabledate = date("d/m/Y",strtotime($applicabledate));
							}       */
							
						}
		else
		{
			?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'pricelistmaster.php');
			//setInterval(function(){document.location='pricelistmaster.php';},2000);
			//document.location='pricelistmaster.php';
			</script>
            <?
		}
		}
		}
		else
		{
			if($effectivedate!='00/00/0000')
			{
			$effectivedate = date("d/m/Y",strtotime($effectivedate));
			}
			if($applicabledate!='00/00/0000')
			{
			$applicabledate = date("d/m/Y",strtotime($applicabledate));
			}
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='pricelistmaster.php';
			</script>
            <?
		}
}
	
	
	
if(isset($_POST['Update'])) // If the submit button was clicked
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	$test="";
	$test1="";
	$post['pricelistcode'] = strtoupper(str_replace('&', 'and',$_POST['pricelistcode']));
	$post['pricelistname'] = strtoupper(str_replace('&', 'and',$_POST['pricelistname']));
	
						 $post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());
		if($_POST['effectivedate']!="" || $_POST['effectivedate']!="00/00/0000" || $_POST['effectivedate']!="0000-00-00" )//Date Formation 
		{
			$test = $news->dateformat($_POST['effectivedate']);
		}
		else
		{
			$test="00/00/0000";
		}
		if($_POST['applicabledate']!="" || $_POST['applicabledate']!="00/00/0000" || $_POST['applicabledate']!="0000-00-00")//Date Formation 
		{
			$test1 = $news->dateformat($_POST['applicabledate']);
		}
		else
		{
			$test1="00/00/0000";
		}
	$post['effectivedate'] =$test; 
	$post['applicabledate'] = $test1;
	$pricelistcode=strtoupper(trim($_POST['pricelistcode']));
	$pricelistname=strtoupper(trim($_POST['pricelistname']));
	$effectivedate=$test;
	$applicabledate=$test1;
	$effectivedate1=$_POST['effectivedate'];
	$applicabledate1=$_POST['applicabledate'];
	
		if(!empty($_POST['pricelistcode'])&&!empty($_POST['pricelistname'])&&!empty($_POST['effectivedate']))
		{   
			if($test1>$test)
			{
				$codenamedcheck=0;
				if($_SESSION['plistsession']!=$pricelistname)
				{ 
				$p2=strtoupper( preg_replace('/\s+/', '',$post['pricelistname']));
				$repqry="SELECT REPLACE(  `pricelistname` ,  ' ',  '' ) AS pricelistname  FROM  `masterpricelist` where pricelistname = '".$p2."' or pricelistname = '".$post['pricelistname']."' or pricelistcode = '".$p2."' or pricelistcode = '".$post['pricelistname']."'";
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
					$productcode =$_POST['productcode'];
					$productdesc =$_POST['productdescription'];
					$mrp=$_POST['mrp'];
					$fprice=$_POST['fprice'];
					$rprice=$_POST['rprice'];
					$iprice=$_POST['iprice'];
					$numcnt=count($_POST['productcode']);
					$j=0;
					$spost['pricelistcode'] = strtoupper(str_replace('&', 'and',$_POST['pricelistcode']));
					$spost['pricelistname'] = strtoupper(str_replace('&', 'and',$_POST['pricelistname']));
					$spost['effectivedate'] =$test;
					$spost['applicabledate'] = $test1;
					
					for($i=0;$i<$numcnt;$i++)
					{
						$spost['productcode'] = strtoupper(trim($productcode[$i]));	
						
						$mrpmine =trim($mrp[$i]);
						$spost['mrp'] =str_replace(',','', $mrpmine);
						
						$fpricemine=trim($fprice[$i]);
						$spost['fprice']=str_replace(',','', $fpricemine);
						
						$rpricemine=trim($rprice[$i]);	
						$spost['rprice'] =str_replace(',','',$rpricemine);
						
						$ipricemine=trim($iprice[$i]);
						$spost['iprice'] = str_replace(',','',$ipricemine);
						
						if(!empty($spost['productcode'])) //&&!empty($spost['productdescription'])
						{ 
							if(($spost['mrp']=='' || $spost['mrp']== '0.00') && ($spost['fprice']=='' || $spost['fprice']== '0.00') && ($spost['rprice']=='' || $spost['rprice']== '0.00') && ($spost['iprice']=='' || $spost['iprice']== '0.00'))
							{
								?><script type="text/javascript">alert("Enter Valid Values in Grid!");</script><?	
							}
							else
							{
								$wherecon= " pricelistcode ='".$post['pricelistcode']."'";
								if($i==0)
								{
									$mtabl ='masterpricelist';
									$wherecon = "pricelistcode ='".$post['pricelistcode']."'";
									$news->deleteNews($ttname,$wherecon);
									$pgtable="pricelistlinkinggrid";
									$wheregrid= "PriceListCode ='".$post['pricelistcode']."'";
									$news->deleteNews($pgtable,$wheregrid);
								}
							$result="SELECT * FROM pricelistmaster where pricelistcode ='".$post['pricelistcode']."' and productcode='".$spost['productcode']."'";
							$sql1 = mysql_query($result) or die (mysql_error());
							$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
								if($myrow1==0)
								{
								$news->addNews($spost,$ttname);
								$news->editNews($post,$mtabl,$wherecon);
								/* $pgpost['pricelistcode']=$spost['pricelistcode'];
								$pgpost['pricelistname']=$spost['pricelistname'];
								$pgpost['effectivedate']=$spost['effectivedate'];
								$pgpost['applicabledate']=$spost['applicabledate'];
								$pgpost['productcode']=$spost['productcode'];
								$pgpost['mrp']=$spost['mrp'];
								$pgpost['fprice']=$spost['fprice'];
								$pgpost['rprice']=$spost['rprice'];
								$pgpost['iprice']=$spost['iprice'];
								
								$mkrow = mysql_query("SELECT * FROM pricelistlinkinggrid where PriceListCode='".$pgpost['pricelistcode']."'");
								while($val=mysql_fetch_array($mkrow))
								{
									if($val['Status']>0)
									{
										$pgpost['Status']=1;
									}
									else
									{
										$pgpost['Status']=0;
									}
									$pgpost['Franchisee']=$val['Franchisee'];
									$pgpost['Country']=$val['Country'];
									$pgpost['State']=$val['State'];
									$pgpost['Branch']=$val['Branch'];
									$pgpost['InsertDate']=date("Y/m/d");
									$pgpost['Deliverydae']=date("Y/m/d");
									$pgtable="pricelistlinkinggrid";
								
								$news->addNews($pgpost,$pgtable);
								
								} */
								$j++;
								}
							}
						}
					}
					if($j==0)
					{
						if($effectivedate!='00/00/0000')
						{
							$effectivedate = date("d/m/Y",strtotime($effectivedate));
						}
						if($applicabledate!='00/00/0000')
						{
							$applicabledate = date("d/m/Y",strtotime($applicabledate));
						}
						?><script type="text/javascript">alert("Enter Mandatory Fields in the Grid!");</script><?
					}
					
					else
					{
						unset($_SESSION['plistsession']);
						?><script type="text/javascript">alert("Updated Sucessfully!",'pricelistmaster.php');</script><?
					}
				}
			}
			else
			{
				?><script type="text/javascript">alert("Applicable Date should be greater than effective date");</script><?
			}
		}
	else
	{
		if($effectivedate!='00/00/0000')
		{
			$effectivedate = date("d/m/Y",strtotime($effectivedate));
		}
		if($applicabledate!='00/00/0000')
		{
			$applicabledate = date("d/m/Y",strtotime($applicabledate));
		}
	?><script type="text/javascript">alert("Enter Mandatory Fields!");</script><?
	}
}

	
/// EDIT LINK FUNCTION 

if(!empty($_GET['pricelistcode']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
  $prmaster  = $_GET['pricelistcode'];
  
$result=mysql_query("SELECT * FROM masterpricelist where pricelistcode ='".$prmaster."' ");
$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!!",'pricelistmaster.php');//document.location='pricelistmaster.php';
			</script>
   			<?
		}
		else
		{
	    
		$myrow = mysql_fetch_array($result);
	 	$pricelistcode = $myrow['pricelistcode'];
	   	$pricelistname = $myrow['pricelistname'];
	 	$effectivedate =  date("d/m/Y",strtotime($myrow['effectivedate']));
		
		  if($effectivedate == '01/01/1970')
		  {
		  	$effectivedate="00/00/0000";
		  }
		  		  
		  	$applicabledate = date("d/m/Y",strtotime($myrow['applicabledate']));
		  
		  if($applicabledate == '01/01/1970')
		  {
		  	$applicabledate="00/00/0000";
		  }
		  	      
	//	 $pricelistdescription = $myrow['pricelistdescription'];
		 $result1="SELECT * FROM pricelistmaster where pricelistcode ='".$prmaster."' ";
			$result12=mysql_query($result1);
			
			$_SESSION['plistsession']= $myrow['pricelistname'];
			    
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
			alert("Select data to delete!",'pricelistmaster.php');//document.location='pricelistmaster.php';
			</script>
			<?
	}

else
{
	
		 $checkbox = $_POST['checkbox']; //from name="checkbox[]"
		 $countCheck = count($_POST['checkbox']);
         $message=NULL;
		for($i=0;$i<$countCheck;$i++)
		{
		$prodidd = $checkbox[$i];
		///$prodid= $_POST['checkbox'];
		$wherecon= " pricelistcode = '".$checkbox[$i]."'";
		$wherecon;
		$mtabl='masterpricelist';
        $result="SELECT * FROM pricelistlinking where PriceListCode ='".$checkbox[$i]."'";
        $sql1 = mysql_query($result) or die (mysql_error());        
        $myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
        if($myrow1==0)
        {
		$news->deleteNews($mtabl,$wherecon);
		$news->deleteNews($ttname,$wherecon);
        
        }
        else
        {
              ?>
            <script type="text/javascript">
            alert("you can't delete already used in Pricelist linking masters!",'pricelistmaster.php');//document.location='pricelistmaster.php';
            </script>
               <?
                   $message =  $message+'".$checkbox[$i]."'+",";
        }
		}
        if($message!=NULL)
        {
        } 
        else
        {
			?>
            <script type="text/javascript">
			alert("Deleted  Successfully!",'pricelistmaster.php');
			</script>
   			<?
        }
}
}
 
	 

 $_SESSION['type']=NULL;
 $pricelistmaster='select * from pricelistmaster';

   	if(isset($_POST['PDF']))
{

$select=$_POST['Type'];
if(!empty($_POST['codes'])&&!empty($_POST['names']))
	{
		$condition="SELECT * FROM pricelistmaster WHERE pricelistcode like'".$_POST['codes']."%' OR pricelistname like'".
		$_POST['names']."%' order by id desc";
		$pricelistmaster=$condition;
	}
	else if(!empty($_POST['codes'])&&empty($_POST['names']))
	{
		$condition="SELECT * FROM pricelistmaster WHERE pricelistcode like'".$_POST['codes']."%'  order by id desc";
		$pricelistmaster=$condition;
	}
	else if(!empty($_POST['names'])&&empty($_POST['codes']))
	{
		$condition="SELECT * FROM pricelistmaster WHERE pricelistname like'".$_POST['names']."%'  order by id desc";
		$pricelistmaster=$condition;
	}
	else
	{
		
		$condition="SELECT * FROM pricelistmaster order by id desc";
		$pricelistmaster=$condition;
	}
if($select=='PDF')
{
	$_SESSION['type']='PDF';
	$_SESSION['query']=$pricelistmaster;
	//$pricelistmaster;
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



if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:pricelistmaster.php');
}

?>

<script type="text/javascript">
$(function() {
  $("#start,#end,#searchdate").datepicker({ changeYear:true, yearRange: '2006:3050',dateFormat:'dd/mm/yy'});
  
  $("#start").change(function(){
    test = $(this).datepicker('getDate');
    testm = new Date(test.getTime());
    testm.setDate(testm.getDate() + 1);

    $("#end").datepicker("option", "minDate", testm);
  });
});


/*function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'width=600,height=255,scrollbars=yes, resizable=0,fullscreen=no,location=no,menubar=no');
return false;
}*/

function popup(mylink)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href,'_blank');
return false;
}


 function addRow(tableID) {
 
            var table = document.getElementById(tableID);
 
            var rowCount = table.rows.length;
			//alert(tableID);
            var row = table.insertRow(rowCount-1);
		
			var delRow = parseInt(rowCount) - 2;
            var colCount = table.rows[1].cells.length;
            for(var i=0; i<colCount; i++) {
 
                var newcell = row.insertCell(i);
				var deleteBtn = '';
				if(i == (colCount-1)) {
					htmlVal = "<img src='del_img.jpg' style='cursor: pointer;' onclick='removeRow(this, \"add\");'/>";   
				} else {
					htmlVal = table.rows[1].cells[i].innerHTML;
				}
                newcell.innerHTML = htmlVal;
				
                //alert(newcell.childNodes);
				
				var controlType = newcell.childNodes[0].type;
                switch(controlType) {
                    case "text":
                            newcell.childNodes[0].value = "";
                            break;
                    
                    case "checkbox":
                            newcell.childNodes[0].checked = false;
                            break;
                    case "select-one":
                            newcell.childNodes[0].selectedIndex = 0;
                            break;
                }
            }	
			//	alert(newcell.childNodes[0].value);
			document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].options.remove(0);
			document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].options.add(new Option("----Select----",""));
			document.getElementById('dataTable').rows[rowCount-1].getElementsByTagName("select")[0].value="";
			
			$('#'+tableID+' select#produtcode:last').focus();
        }

		function removeRow(src, type){
			var del = true;
			if(type == 'edit') {
				var del = confirm('Are you want to remove selected row?');
			}
			if(del) {
				var sourceTableID = 'dataTable';       
				var oRow = src.parentElement.parentElement;  
				document.getElementById(sourceTableID).deleteRow(oRow.rowIndex);  
			}
		}
 
        function deleteRow(tableID, row) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;
			if(rowCount <= 3) {
				alert("Cannot delete all the rows.");
			}
			else
					{
						
						 if(document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[0].value!=""||document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[1].value!=""||document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[2].value!="")
						 {
							  var deleting= confirm("Do you really want to delete the row containing information??");
						    if (deleting== true)
							{
							   table.deleteRow(rowCount-2);
							}
							else
							{
								
								if(document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[0].value!="")
								{
									if(document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[1].value!="")
								{
									document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[2].focus();
								}
								else
								{
								document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("INPUT")[1].focus();
								}
								}
								else
								{
								document.getElementById('dataTable').rows[rowCount-2].getElementsByTagName("Select")[0].focus();
								}
								
							}
						 }
						 else
						 {
						  		table.deleteRow(rowCount-2);
						 }
					}
			
			
            }
			catch(e) {
                alert(e);
            }
        }

function validatePricelistCode(key)
{
	var object = document.getElementById('pricelistcode');
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

function validatePricelistName(key)
{
	var object = document.getElementById('pricelistname');
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

  var element;
 function isDecimal(str){
        if(isNaN(str)){
          if(element=="mrp")
                             {  
                                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value='';   
                                    form.num.focus();
                              }
          else if(element=="fprice")
                               {
                                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value='';    
                                  }
                                   else if(element=="rprice")
                                   {
                                    document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value='';       
                                     }  
                                      else if(element=="iprice")
                                       {
                                        document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value='';   
                                          }  
                                           form.num.focus();
        }
        else{
        str=parseFloat(str);
                
              if(element=="mrp")
                  {
                  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value=str.toFixed(2);
                     form.num.focus();
                  }
                  else if(element=="fprice")
                  {
                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value=str.toFixed(2);   
                  }
                   else if(element=="rprice")
                  {
                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value=str.toFixed(2);  
                  }  
                 else if(element=="iprice")
                  {
                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value=str.toFixed(2);  
                  }
       // document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value=str.toFixed(2);
       
       
       
           
            }
            }
function validate()
{
   
    var srcElem = window.event.srcElement;
              element=  srcElem.id;
              
             rowNum = srcElem.parentNode.parentNode.rowIndex ;
         
                  if(element=="mrp")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value;
                     
                  }
                  else if(element=="fprice")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value;    
                  }
                   else if(element=="rprice")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value;    
                  }  
                 else if(element=="iprice")
                  {
                   var dec=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value;    
                  }
                 
                // var dec=document.getElementById('dataTable').rows[rowNum1].getElementsByTagName("select")[1].value;
                 if (dec == "")
                 {
                     
                         if(element=="mrp")
                             {
                                  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[1].value='';   
                                form.num.focus();
                             }
                              else if(element=="fprice")
                               {
                                 document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[2].value='';    
                                  }
                                   else if(element=="rprice")
                                   {
                                    document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[3].value='';       
                                     }  
                                      else if(element=="iprice")
                                       {
                                        document.getElementById('dataTable').rows[rowNum].getElementsByTagName("INPUT")[4].value='';   
                                          }  
                           
          form.num.focus();
             
                    return false;
                }
                if (isDecimal(dec)==false)
                {
                   num="";
                   form.num.focus();
                    return false;
                 }
                      return true;
   }

        function getagentids() 
        { 
	
		var srcElem = window.event.srcElement;
		rowNum = srcElem.parentNode.parentNode.rowIndex ;
		var e=  document.getElementById('dataTable').rows[rowNum].getElementsByTagName("select")[0];
		var er=e.options[e.selectedIndex].value;
		//strUser = e.selectedIndex;
		//var resultq=document.getElementById('productdescriptionlist');
		var ddlArray= new Array();
		var ddl = document.getElementById('productdescriptionlist');
		//cnt=0;
		var tt;
		for (i = 0; i < ddl.options.length; i++) 
		{
			ddlArray[i] = ddl .options[i].value;
			var ty = ddlArray[i].split("~");
			var p =ty[0];
			var p2 =ty[1];
			
			if(p==er)
			{
				tt=p2;
			}
			else if(er=="")
			{
				tt="";
			}
			
			document.getElementById('dataTable').rows[rowNum].getElementsByTagName("input")[0].value=tt;
		}
		}

</script>
<title><?php echo $_SESSION['title']; ?> || Price List Master</title>
</head>
 <?php  
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['pricelistcode'])){?>
 
 <body class="default" onLoad="document.form1.pricelistname.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.pricelistcode.focus()">

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
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="form1"  >
             <table style="display:none;" >

                                         <tr >
                                         <td>
                                        
    <select  name="productdescriptionlist" id="productdescriptionlist"  >
    <?
    
    $que = mysql_query("SELECT ProductCode,ProductDescription FROM productmaster order by m_date desc");
    
		while( $record = mysql_fetch_array($que))
		{
			echo "<option value=\"".$record['ProductCode']."~".$record['ProductDescription']."\">".$record['ProductCode']."~".$record['ProductDescription']."\n "; 
		}
    
    ?>
    </select>
                                      </td>

                                      </tr>
</table>
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Pricelist Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Pricelist Code</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                               <?php      if(!empty($_GET['pricelistcode']))
					{?>
              <input type="text" name="pricelistcode" id="pricelistcode" style="border-style:hidden; background:#f5f3f1; text-transform:uppercase;" readonly="readonly"  value="<?php echo $pricelistcode;?>" onKeyPress="return validatePricelistCode(event)" maxlength="15" onChange="return codetrim(this)" />
                            <? }
					else
					{?>
						  <input type="text" name="pricelistcode" id="pricelistcode" value="<?php echo $pricelistcode;?>" onKeyPress="return validatePricelistCode(event)" maxlength="15" onChange="return codetrim(this)" style="text-transform:uppercase;" />
				          <? } ?>
                             </div>
                             
 							<!--Row1 end-->
                             <!--Row2 -->  
							   <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                    <label>Pricelist Name</label><label style="color:#F00;">*</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                  
						  <input type="text" name="pricelistname" id="pricelistname" value="<?php echo $pricelistname;?>" onKeyPress="return validatePricelistName(event)" onChange="return trim(this)" maxlength="50" style="text-transform:uppercase;" />
				         
                             </div>
                                                              
                           </div>                             
                     <!-- col1 end -->  
                     
                     <!-- col2 -->   
  		<div style="width:400px; overflow:auto; height:auto; float:left; padding-left:150px,padding-bottom:5px; margin-left:100px;" class="cont">
   <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Effective Date</label><label style="color:#F00;">*</label>
              </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
				<?php /*?>	<?php  if(!empty($_GET['pricelistcode']))
                    {?>
                    <input type="text" name="effectivedate" value="<?php echo $effectivedate;?>" onFocus="end.focus()" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" />
                    <? }
                    else
                    {?>
                    <input type="text" name="effectivedate" value="<?php echo $effectivedate;?>" readonly="readonly" id="start"/>
                    <? }?><?php */?>
                     <input type="text" name="effectivedate" value="<?php echo $effectivedate;?>" readonly="readonly" id="start"/>
			  </div>
                              
 					<!--Row4end-->   
                    <!--Row5 -->  
                               <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Applicable Till</label>
                               </div>
                              <div style="width:200px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
         <input type="text" name="applicabledate" value="<?php echo $applicabledate;?>" readonly="readonly"  id="end"/>
              </div>
 							<!--Row5 end--> 
                            
                      </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   


                            
                     <!-- col3 --> 
                                                              
                    </div>
                   
                   <div style="width:925px; height:200px; overflow:auto; float:left;  margin-top:8px; margin-left:5px;">
 
   <!-- <TABLE  width="700px" border="1"> 
    <tr><td style="width:25px;"></td>
    <td style="width:75px;">Product Code</td>
    <td style="width:75px;">Product Description</td>
    <td style="width:75px;">MRP</td>
    <td style="width:75px;">Franchise Price</td>
    <td style="width:75px;">Retailer Price</td>
    <td style="width:75px;">Institutional Price</td>
    </tr></TABLE>-->
    <TABLE  id="dataTable"  width="890px;" border='1'>
  <tr><!--<td class="sorttable_nosort" style=" font-weight:bold; text-align:center width:20px;">&nbsp;</td>-->
    <td  style=" font-weight:bold; width:150px; text-align:center;" >Product Code<label style="color:#F00;">*</label></td>
    <td  style=" font-weight:bold; width:150px; text-align:center;">Product Description<label style="color:#F00;">*</label></td>
    <td  style=" font-weight:bold; width:80px; text-align:center;">Consumer Price</td>
    <td  style=" font-weight:bold; width:80px; text-align:center;">Distributor Price</td>
    <td  style=" font-weight:bold; width:80px; text-align:center;">Retailer Price</td>
    <td  style=" font-weight:bold; width:80px; text-align:center;">Industrial Price</td>
	<td  style=" font-weight:bold; width:10px;">&nbsp;</td>
    </tr>
    <?
	if($result12!="")
	{
	while($myrow1price = mysql_fetch_array($result12))
			{
				$productcode='';
				$productpgg= mysql_query("select ProductDescription from productmaster where ProductCode='".$myrow1price['productcode']."' ")  ;
		
		$productrecord11 = mysql_fetch_array($productpgg);
      $productdescription = $productrecord11['ProductDescription'];
	    // $productdescription= $myrow1price['productdescription'];
	     $productcode = $myrow1price['productcode'];
		 $mrp= $myrow1price['mrp'];
	      $fprice= $myrow1price['fprice'];
		 $rprice= $myrow1price['rprice'];
		 $iprice= $myrow1price['iprice'];
		 $i++;
	?>
        <TR>
        <!--   <TD style="width:20px;"><INPUT style="width:30px;" type="checkbox" name="chk"/></TD>-->
          <TD> <select  name="productcode[]" id="produtcode" onChange="getagentids();" style="width:150px;">
    <option value="<?php echo $productcode;?>"><? if(!empty($productcode)){ echo $productcode;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT ProductCode FROM productmaster order by ProductCode asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($productcode!=$record['ProductCode'])
									  {      
                                      echo "<option value=\"".$record['ProductCode']."\">".$record['ProductCode']."\n "; 
									  }
                              		 }
									 
                                    ?>
                                          </select> </TD>
            <TD><input type="text" id="productdescription" name="productdescription[]"  value="<?php echo $productdescription;?>" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" />
        </TD>
             <TD><INPUT type="text" name="mrp[]" id="mrp" style="width:80px;"  onChange="validate(this)" value="<?php echo number_format((float)$mrp, 2,'.','');?>" /></TD>
            <TD><INPUT type="text" name="fprice[]" id="fprice" style="width:80px;" onChange="validate(this)" value="<?php echo  number_format((float)$fprice, 2,'.',''); ?>" /></TD>
            <TD><INPUT type="text" name="rprice[]" id="rprice" style="width:80px;" onChange="validate(this)" value="<?php echo number_format((float)$rprice, 2,'.',''); ?>" /></TD>
            <TD><INPUT type="text" name="iprice[]"  id="iprice" style="width:80px;" onChange="validate(this)" value="<?php echo number_format((float)$iprice, 2,'.',''); ?>" class="table_last_field" /></TD>
			
			<TD  class="remove_btn">
			<?php if($i>1) { ?> <img src="del_img.jpg" style='cursor: pointer; width:20px;' onclick='removeRow(this, "edit");'/>
			<?php } ?>
			</TD>
			
        </TR>
        <?
				}
				$productcode='';
		}
		else
		{
		?>
        <TR>
         <!--   <TD style="width:20px;"><INPUT style="width:30px;" type="checkbox" name="chk"/></TD>-->
            <TD> <select  name="productcode[]" id="produtcode" onChange="getagentids(this);" style="width:150px;">
           
            <option value=""> ----Select---- </option>
                                     	 <?php
								  $result=mysql_query("SELECT ProductCode FROM productmaster order by ProductCode asc");
									while($myrow1price = mysql_fetch_array($result))
									{
										echo "<option value='".$myrow1price['ProductCode']."'>".$myrow1price['ProductCode']."</option>";
									}
								  ?>
                                    
                                   </select></TD>
                                   
            <TD>
             <input type="text" id="productdescription"  name="productdescription[]" style="border-style:hidden; background:#f5f3f1; width:150px;" readonly="readonly" /></TD>
             <TD>
             <input type="text" name="mrp[]" id="mrp" onChange="validate(this)" style="width:80px;"/></TD>
             
            <TD><INPUT type="text" name="fprice[]" id="fprice" onChange="validate(this)" style="width:80px;"/></TD>
            <TD ><INPUT type="text" name="rprice[]"  id="rprice" onChange="validate(this)" style="width:80px;"/></TD>
            <TD ><INPUT type="text" name="iprice[]" id="iprice"  onchange="validate(this)" style="width:80px;" class="table_last_field" /></TD>
			<TD  class="remove_btn">&nbsp;</TD>
         
        </TR>
        <?
		}
		?>
		<tr><td colspan="7" style="height: 0px;"></td></tr>
        <!-- <tr height="40px"> <td  align="center"><INPUT style=" margin-left:20px;"align="middle" type="button" value="Add Row" class="button" onClick="addRow('dataTable')" /></td>
 
    <td  align="center"><INPUT type="button" align="middle" style=" margin-left:20px;" value="Delete Row" class="button" onClick="deleteRow('dataTable')" /></td></tr> -->
    </TABLE></div>
    
			   </div>
               
                <!-- main row 1 end-->
                
                  <!--Main row 2 start-->
                <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:10px;">     
                   
					<div style="width:225px; height:50px; float:left;  margin-left:14px; margin-top:-3px;" id="center1">
                    
                        <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                        
                    <?php      if(!empty($_GET['pricelistcode']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" >
				          <? } ?>
		              </div>
                           
                          <div style="width:80px; height:32px; float:left;margin-top:16px; margin-left:10px; ">
						  <input name="Cancel" type="submit" class="button" value="Reset">
		              </div>    
	              </div>                          
                                                   
		       
                         
               <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:95px; height:30px; float:left; margin-left:3px; margin-top:16px;" >
                                <label>Price List Code</label>
                               </div>
                               <div style="width:130px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                 <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:95px; height:30px; float:left; margin-left:3px; margin-top:16px;">
                                  <label>Price List Name</label>
                               </div>
                               <div style="width:130px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                 <input type="text"   name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
                               </div>
                               <div style="width:83px; height:32px; float:left; margin-top:16px;">
                                <input id="Search" type="submit" name="Search" value="Search" class="button"/>
                               </div>  
                               </div>
                               </div>	
                          <!--Row2 end-->
          <!--  grid start here-->
             
              <div style="width:900px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:19px; overflow:auto;" class="grid">
                    
                  <table id="datatable1" align="center" class="sortable" border="1" width="870px">
    <tr > 
 	 <?  
	 if(($row['deleterights'])=='Yes')
		{
	?>    
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="8px">
     <input type='checkbox' id="checkall" name='checkall' onclick='checkedAll(form1);'></td>
   	<? 
   		}
    if(($row['editrights'])=='Yes') 
	  	{ 
	 ?>
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="12px">Action</td>
      <? 
		} 
	  ?>
  <td  align="center" style=" font-weight:bold;">Price list Code</td>
  <td style=" font-weight:bold;">Price list Name</td>
 <!--  <td style=" font-weight:bold;">Pricelist Description</td>-->
  <td style=" font-weight:bold;">Effective Date</td>
  <td style=" font-weight:bold;">Applicable Till</td>
	<td style=" font-weight:bold;">View</td>
  
 <!-- <td style=" font-weight:bold;">fprice</td>
  <td style=" font-weight:bold;">rprice</td>
  

  <td style=" font-weight:bold;">Branch</td>
  <td style=" font-weight:bold;">Franchise</td>
  
    <td style=" font-weight:bold;">iprice</td>-->
  
  </tr>
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($query))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
      $testdate1=date("d/m/Y",strtotime($record['applicabledate'])) ;
      if($testdate1 == '01/01/1970')
      {
      $testdate1="00/00/0000";
      }
      $testdate2=date("d/m/Y",strtotime($record['effectivedate'])) ;
      if($testdate2 == '01/01/1970')
      {
      $testdate2="00/00/0000";
      }
    ?>
    
     <tr>
      <?  
	 if(($row['deleterights'])=='Yes')
		{
	?> 
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $record['pricelistcode']; ?>"  onchange="test();"></td>
       	<? 
   		}
    if(($row['editrights'])=='Yes') 
	  	{ 
	 ?>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"> <a style="color:#0360B2" name="edit" href="pricelistmaster.php?pricelistcode=<?=$record['pricelistcode'];echo '&effectivedate=';echo $record['effectivedate']?>">Edit</a></td>
     <? 
		} 
	  ?>
    <td  bgcolor="#FFFFFF">
        <?=$record['pricelistcode']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
        <?=$record['pricelistname']?>
    </td>
    <!-- <td  bgcolor="#FFFFFF" align="left">
       <?php /*?> <?=$record['pricelistdescription']?><?php */?>
    </td>-->
    <td  bgcolor="#FFFFFF" align="left">
       <? echo  $testdate2;?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left">
        <? echo  $testdate1;?>
    </td>
     <td bgcolor="#FFFFFF" style=" font-weight:bold; text-align:center"><a style="color:#0360B2" HREF="view" onClick="return popup('pricelistgrid.php?<? echo 'pricelistcode='; echo $record['pricelistcode']; ?>')">View</a></td>
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
 </div> 
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
               <!--  grid end here-->
        
             <!-- form id start end-->      
       <br /><br />
       <input type="hidden" value="0" id="last_inc_count" />  
       <!--Third Block - Menu -Container -->
    </form>
</div>
</div>
</div>
<!--Footer Block --><!--Footer Block - End-->

<div id="footer-wrap1">
  <?php include("../../footer.php")?>
</div>
</center></body>
</html>
<?
$productcode='';
}
?>