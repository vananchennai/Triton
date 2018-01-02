<?php

session_start();
global $host, $uid, $pass,$databname;
$str ="";
$data=array();
//$uploadfile='log.txt';
$uploadfile= "rights.txt";
$file=fopen($uploadfile,"r") or exit("Unable to open file!");
//$file==fopen($uploadfile, "r");
	while (!feof($file))
	{
		$str= $str.fgetc($file);
	}

list($host, $uid, $pass,$databname) = explode('~',trim($str));
fclose($file);
$_SESSION["dbhostname"]=$host;
$_SESSION["dbusername"]=$uid;
$_SESSION["dbpassword"]=$pass;
$_SESSION["databname"]=$databname;

require_once 'masterclass.php';
$news = new News();
	if ( isset( $HTTP_RAW_POST_DATA ) )
	{ 
/* $getData="
<ENVELOPE>
 <REQUEST>
  <VOUCHER>
   <ACTION>CANCEL</ACTION>
   <FRANCHISECODE>1234567</FRANCHISECODE>
   <VOUCHERDATE>19-Feb-2014</VOUCHERDATE>
   <VOUCHERNUMBER>1</VOUCHERNUMBER>
   <VOUCHERTYPE>Sales</VOUCHERTYPE>	
   <MASTERID>138</MASTERID>
   <GUID>
  </VOUCHER>
 </REQUEST>
</ENVELOPE>
";
	
	$xml_object = simplexml_load_string($getData);  */
	$xml_object = simplexml_load_string( $HTTP_RAW_POST_DATA ); 
		if($xml_object!="") 
//if(1)
		{
			$count=$count1=$count2=$count3=$count4=$count5=$count6=$count7=$count8=$count9=$count10=$count11=$count12=NULL;
			date_default_timezone_set('UTC');
		
			foreach($xml_object->REQUEST->children() as $VOUCHER)
			{  
				$FRANCHISECODE =$VOUCHER->FRANCHISECODE;
				$status=$VOUCHER->ACTION;
				$SalesNo=$VOUCHER->VOUCHERNUMBER;
				$vouchertype=$VOUCHER->VOUCHERTYPE;
				/*$masterid=$VOUCHER->MASTERID;
				$responseid = $masterid;*/
				$responseid=$VOUCHER->MASTERID;
				// $responseid = $VOUCHER->GUID;
				// $masterid=$VOUCHER->DATABASEUNIQUEVALUE;
				$masterid=$VOUCHER->GUID;
				$vocherdate=$VOUCHER->VOUCHERDATE;
				$middle = strtotime($vocherdate);
				$salesdate=date('Y-m-d', $middle);
				//echo $vouchertype;
				// if($vouchertype=='Regular Sales'||$vouchertype=='Scrap Sales'||$vouchertype=='Scheme Sales')
				if($vouchertype=='Sales')
				{
					/*if($vouchertype=='Regular Sales')
					{
						$masterid=$masterid."-RS-".$FRANCHISECODE;
					}
					else if($vouchertype=='Scrap Sales')
					{
						$masterid=$masterid."-SS-".$FRANCHISECODE;
					}
					else if($vouchertype=='Scheme Sales')
					{
						$masterid=$masterid."-SCS-".$FRANCHISECODE;
					}*/
					$postd['voucherstatus']=$status;
					//echo $SalesNo;
					//table1
					$vouchertable='retailersales';
					$where="masterid='".$masterid."'";
					//echo $where;
					$news->editNews($postd,$vouchertable,$where);
					//table 2
					$where1="masterid='".$masterid."'";
					$vouchertable1='retailersalesitem';
					$news->editNews($postd,$vouchertable1,$where1);
					//table 3
					$where2="masterid='".$masterid."'";
					$vouchertable2='retailersalesledger';
					$news->editNews($postd,$vouchertable2,$where2);
					//table 4
					/*$where3="SalesNo='".$SalesNo."'";
					$vouchertable3='retailerbatterymaster';
					$news->editNews($postd,$vouchertable3,$where3);*/
					$count1=$count1.$responseid."~";
					
					$wherecondel= "unique_id ='".$masterid."'";
					$updatesalestable='r_salesreport';
					$news->deleteNews($updatesalestable,$wherecondel);
				}	
				  
				// if($vouchertype=='Regular Purchase'||$vouchertype=='Scrap Purchase'||$vouchertype=='Scheme Purchase')
				if($vouchertype=='Purchase')
				{
					/*if($vouchertype=='Regular Purchase')
					{
						$masterid=$masterid."-RP-".$FRANCHISECODE;
					}
					else if($vouchertype=='Scrap Purchase')
					{
						$masterid=$masterid."-SRP-".$FRANCHISECODE;
					}
					else if($vouchertype=='Scheme Purchase')
					{
						$masterid=$masterid."-SCP-".$FRANCHISECODE;
					}*/
					$postd['voucherstatus']=$status;
					//table1
					$vouchertable='purchase';
					$where="masterid='".$masterid."'";
					//echo $where;
					$news->editNews($postd,$vouchertable,$where);
					//table 2
					$where1="masterid='".$masterid."'";
					$vouchertable1='purchase_details';
					$news->editNews($postd,$vouchertable1,$where1);
					//table 3
					$where2="masterid='".$masterid."'";
					$vouchertable2='purchaseledger';
					$news->editNews($postd,$vouchertable2,$where2);
					//table 4
					/*$where3="purchasesNo='".$SalesNo."' and vochertype='".$vouchertype."'";
					$vouchertable3='purchasebatterymaster';
					$news->editNews($postd,$vouchertable3,$where3);*/
					$count8=$count8.$responseid."~";
					
                    //To Delete the entries in r_salesreport table
					$wherecondel= "unique_id ='".$masterid."'";
					$updatepurchasetable='r_purchasereport';
					$news->deleteNews($updatepurchasetable,$wherecondel);								
				}	
				// if($vouchertype=='Purchase Return')
				if($vouchertype=='Debit Note')
				{
					//$masterid=$masterid."-PR-".$FRANCHISECODE;
					$postPR['voucherstatus']=$status;
					//table1
					$vouchertable='purchasereturn';
					$where="masterid='".$masterid."'";
					//echo $where;
					$news->editNews($postPR,$vouchertable,$where);
					//table 2
					$where1="masterid='".$masterid."'";
					$vouchertable1='purchasereturn_details';
					$news->editNews($postPR,$vouchertable1,$where1);
					//table 3
					$where2="masterid='".$masterid."'";
					$vouchertable2='purchasreturnledger';
					$news->editNews($postPR,$vouchertable2,$where2);
					//table 4
					/*$where3="purchasesretNo='".$SalesNo."' and vochertype='".$vouchertype."'";
					$vouchertable3='Purchasereturnbatterymaster';
					$news->editNews($postPR,$vouchertable3,$where3);*/
					$count9=$count9.$responseid."~";
					
                    $wherecondel= "unique_id ='".$masterid."'";
					$updateprtable='r_purchasereturn';
					$news->deleteNews($updateprtable,$wherecondel);
					
				}	
				// if($vouchertype=='Purchase Order')
				// {
				// 	//$masterid=$masterid."-PO-".$FRANCHISECODE;
				// 	$postPR['voucherstatus']=$status;
				// 	$vouchertable='purchaseorder';
				// 	$where="masterid='".$masterid."'";
				// 	$news->editNews($postPR,$vouchertable,$where);
				// 	$inventorytablePO='purchaseorder_details';
				// 	$wherecon2= "masterid ='".$masterid."'";	
				// 	$news->editNews($postPR,$inventorytablePO,$wherecon2); 
				
				// 	$taxtablePO='purchaseorderledger';
				// 	$wherecon1= "masterid ='".$masterid."'";	
				// 	$news->editNews($postPR,$taxtablePO,$wherecon1);
				// 	$count10=$count10.$responseid."~";
				// }	
				// if($vouchertype=='Sales Return')
				if($vouchertype=='Credit Note')
				{
					//$masterid=$masterid."-SR-".$FRANCHISECODE;
					$postPR['voucherstatus']=$status;
					//table1
					$vouchertable='salesreturn';
					$where="masterid='".$masterid."'";
					//echo $where;
					$news->editNews($postPR,$vouchertable,$where);
					//table 2
					$where1="masterid='".$masterid."'";
					$vouchertable1='salesreturnitem';
					$news->editNews($postPR,$vouchertable1,$where1);
					//table 3
					$where2="masterid='".$masterid."'";
					$vouchertable2='salesledgerreturn';
					$news->editNews($postPR,$vouchertable2,$where2);
					//table 4
					$count11=$count11.$responseid."~";	
					
 					$wherecondel= "unique_id ='".$masterid."'";
				    $updatesalestable='r_salesreturn';
				    $news->deleteNews($updatesalestable,$wherecondel);
					
				}	
			}
		}
	}
$count=$count1.$count2.$count3.$count4.$count5.$count6.$count7.$count8.$count9.$count10.$count11.$count12.$count;
$tax='uploadstatus';
$wherecon1= "franchisecode ='".$FRANCHISECODE."' ";
$news->deleteNews($tax,$wherecon1);
$post1['franchisecode']=$FRANCHISECODE;
$post1['date']=date("Y-m-d ");
$post1['status']='Delivered';
$news->addNews($post1,$tax);
{
print ("<ENVELOPE>");
print ("<HEADER>" );
print ("<VERSION>1</VERSION>"); 
print ("<STATUS>1</STATUS>");
print ("</HEADER>");
print ("<BODY>"); 
print ("<DATA>"); 
print ("<VOUCHERRESPONSES>");
print ("<MASTERIDS>".  $count  ."</MASTERIDS> ");
print ("</VOUCHERRESPONSES>");
print ("</DATA>" ); 
print ("</BODY>");
print ("</ENVELOPE>");
}
?>
