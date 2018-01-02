<?php
session_start();

global $host, $uid, $pass,$databname;
$str ="";
$data=array();
$uploadfile= "rights.txt";
$file=fopen($uploadfile,"r") or exit("Unable to open file!");
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
$news = new News(); // Create a new News Object
$getData="";
 
if ( isset( $HTTP_RAW_POST_DATA ) )
//if(1)
{                     
     //$xml_object = simplexml_load_string($getData); 
	$xml_object = simplexml_load_string( $HTTP_RAW_POST_DATA ); 
if($xml_object!="") 
   { 
	$i=1;$masterid='';$oldserialnumber='';$groCount=0;$count=$count1=$count2=$count3=$count4=$count5=$count6=$count7=$count8=$count9=$count10=$count11=$count12=$count13=$count14=$count15=$count16=$count17=$count18=$count19=$dbuvalue=NULL;
	date_default_timezone_set('UTC');
	$FRANCHISECODE =$xml_object->FRANCHISECODE;
	$category=$xml_object->REQUEST->VOUCHER;
  foreach($xml_object->REQUEST->children() as $VOUCHER)
   {        
	$dbuvalue=$VOUCHER->GUID;//DATABASEUNIQUEVALUE;
	$voucherstate=$VOUCHER->VOUCHERSTATE;
	$SalesNo=$VOUCHER->VOUCHERNUMBER;
	$vouchertype=$VOUCHER->VOUCHERPARENT;
	$vouchertypename=$VOUCHER->VOUCHERTYPENAME;
	$supplerinvno=$VOUCHER->SUPPLIERIVOICENUMBER;
	$sdt=$VOUCHER->SUPPLIERIVOICEDATE;
	$middle1 = strtotime($sdt);
	$suppinvdate=date('Y-m-d', $middle1);
	$plant=$VOUCHER->PLANT;
	$schemetype=$VOUCHER->SCHEMETYPE;
	$status='ACTIVE';
	$masterid=$VOUCHER->MASTERID;
	$responseid=$masterid;
	$olddate=$VOUCHER->DATE;
	$middle = strtotime($olddate);
	$salesdate=date('Y-m-d', $middle);
	$fraqry =mysql_query("select Franchisecode  from  franchisemaster where  Franchisecode='".$FRANCHISECODE."'"	);
	$fraCount=mysql_num_rows($fraqry);
	
   if($fraCount==0)
	{  
		echo "Franchisee Not Available in tally central server";
	}
  else
	{
  if($vouchertype=="")
	{
		echo "Voucher type  Not Available in tally tag";
	}
elseif($vouchertype=='RETAILER')
	{
		$postret['fmexecutive']=$FRANCHISECODE;				
		$postret['RetailerName']=$VOUCHER->RETAILERNAME;
		$retcode=$VOUCHER->RETAILERCODE;
		$postret['RetailerCode']=$VOUCHER->RETAILERCODE;
		$postret['Category']=$VOUCHER->RETAILERCATEGORY;
		$postret['ContactName']=$VOUCHER->RETAILERCONTACTPERSON;
		$postret['ContactNo'] =$VOUCHER->RETAILERCONTACTNO;
		$postret['Address']=$VOUCHER->RETAILERADDRESS;
		$postret['City']=$VOUCHER->RETAILERCITY;
		$postret['Districtname']=$VOUCHER->RETAILERDISTRICY;
		$postret['accountholdersname']=$VOUCHER->RETAILERACCNAME;
		$postret['bankname']=$VOUCHER->RETAILERBANKNAME;
		$postret['branchname']=$VOUCHER->RETAILERBRANCHNAME;
		$postret['ifsccode']=$VOUCHER->RETAILERIFSCCODE;
		$postret['CreditDays']=$VOUCHER->RETAILERCRDAYS;
		$postret['CreditLimit']=$VOUCHER->RETAILERCRLIMIT;
		$postret['TinNo']=$VOUCHER->RETAILERTINNO;
		$postret['TinDate']=$VOUCHER->RETAILERTINDATE;
		$postret['AccNo']=$VOUCHER->RETAILERACCNO;
		$postret['franchiseeme']=$VOUCHER->ARBLMENAMEVALUE;			
		$postret['retailerclassification']=$VOUCHER->ARBLCLASSVALUE;			
		$postret['geographical']=$VOUCHER->ARBLTYPEVALUE;			
		$postret['retailercategory1']=$VOUCHER->RCATEGORYAVALUE;			
		$postret['retailercategory2']=$VOUCHER->RCATEGORYBVALUE;		
		$postret['retailercategory3']=$VOUCHER->RCATEGORYCVALUE;
		if($retcode!='')
		{
			$tname	= "retailermaster";
			$result=mysql_query("SELECT * FROM retailermaster where RetailerCode ='".$retcode."'");
			$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);
			if($myrow1==0)	
			{
				$news->addNews($postret,$tname);
				$count18=$count18.$retcode."~";
			}	
			else
			{
				$wherecon= "RetailerCode ='".$postret['RetailerCode']."'";
				$news->editNews($postret,$tname,$wherecon);
				$count18=$count18.$retcode."~";
			}	
		}
	}
	else if($dbuvalue=="")
	{
		echo "DATABASEUNIQUEVALUE Not Available in tally tag";
	}
	else
	{
if($vouchertype=='Purchase Order')
{
		$SalesNo=$SalesNo."-PO-".$FRANCHISECODE;
		$masterid=$dbuvalue ;
		$postaPO['FranchiseCode']=$FRANCHISECODE;
		$postaPO['Purchasedate']=$salesdate;
		$postaPO['VoucherType']=$vouchertype;        
		$postaPO['PurchaseNumber']=$SalesNo;
		$postaPO['masterid']=$masterid;
		$po=$VOUCHER->ORDERNO;
		$po=$po."-".$FRANCHISECODE;
		$postaPO['PurchaseorderNo']=$po;
		$postaPO['voucherstatus']=$status;
		$pString = str_replace(",", "", $VOUCHER->VCHAMOUNT);
		$floatvalue=floatval($pString);
		if($floatvalue<0)
		{
			$floatvalue=(-1)*$floatvalue;
		}
		$postaPO['TotalPurchaseAmt']=$floatvalue;
		$postaPO['Narration']=$VOUCHER->NARRATION;			
		$postaPO['ARBLWarehouseName']=$VOUCHER->PARTYLEDGERNAME;
		// Table name:purchaseorder
		$vouchertable1='purchaseorder';
		$qry =mysql_query("select PurchaseorderNo  from  purchaseorder where masterid='".$masterid."'");
		$groCount=mysql_num_rows($qry);
		if($groCount==0)
		{
			$news->addNews($postaPO,$vouchertable1);
		}
		else if($groCount>0)
		{
			$wherecon= "masterid ='".$masterid."'";	
			$news->deleteNews($vouchertable1,$wherecon); 
			$news->addNews($postaPO,$vouchertable1);
			$inventorytablePO='purchaseorder_details';
			$wherecon2= "masterid ='".$masterid."'";	
			$news->deleteNews($inventorytablePO,$wherecon2); 
			$taxtablePO='purchaseorderledger';
			$wherecon1= "masterid ='".$masterid."'";	
			$news->deleteNews($taxtablePO,$wherecon1);
		}
		//Tax  Ledger 
		$j=1;
		$K=1;
		foreach($VOUCHER->children() as $ALLLEDGERENTRIES)
		{
			if($vouchertype=='Purchase Order' && $stkitemname=$ALLLEDGERENTRIES->LEDGERNAME!="" && $stkrate=$ALLLEDGERENTRIES->AMOUNT!= "")
			{  
				$ledger=$ALLLEDGERENTRIES->LEDGERNAME;;
				$post1aPO['Taxledger']=$ALLLEDGERENTRIES->LEDGERNAME;
				$pString = str_replace(",", "", $ALLLEDGERENTRIES->AMOUNT);
				$floatvalue=floatval($pString); 
				if($floatvalue<0)
				{
					$floatvalue=(-1)*$floatvalue;
				}
				$post1aPO['Taxamount']=$floatvalue;
				$post1aPO['Purchaseno']=$SalesNo;
				$post1aPO['PurchaseOrderno']=$po;
				$post1aPO['Purchasedate']=$salesdate;
				$post1aPO['franchisecode']=$FRANCHISECODE;
				$post1aPO['voucherstatus']=$status;
				$post1aPO['masterid']=$masterid;
				// Table Name:purchaseorderledger;
				$taxtablePO='purchaseorderledger';
				if($K > 1)
				{
					if($groCount==0)
					{
						$news->addNews($post1aPO,$taxtablePO);
					}
					else if($groCount>0)
					{
						$news->addNews($post1aPO,$taxtablePO);
					}
				} 
				$K++;
			}
		}
		foreach($VOUCHER->children() as $INVENTORYALLOCATIONS)
		{
			if($vouchertype=='Purchase Order' && $stkitemname=$INVENTORYALLOCATIONS->STOCKITEMNAME!="" && $stkrate=$INVENTORYALLOCATIONS->RATE!=""&&$PRODUCTCODE=$INVENTORYALLOCATIONS->PRODUCTCODE!=""&&$AMOUNT=$INVENTORYALLOCATIONS->AMOUNT!=""&&$billqty=$INVENTORYALLOCATIONS->BILLEDQTY!="")
			{
				$inventorytablePO='purchaseorder_details';
				$post2aPO['ProductCode']=$INVENTORYALLOCATIONS->PRODUCTCODE;
				$pcode=$INVENTORYALLOCATIONS->PRODUCTCODE;
				$post2aPO['ProductDescription']=$INVENTORYALLOCATIONS->STOCKITEMNAME;
				$post2aPO['Rate']=$INVENTORYALLOCATIONS->RATE;
				$pString = str_replace(",", "", $INVENTORYALLOCATIONS->AMOUNT);
				$floatvalue=floatval($pString); 
				if($floatvalue<0)
				{
					$floatvalue=(-1)*$floatvalue;
				}
				$post2aPO['Amount']=$floatvalue;
				$post2aPO['Quantity']=$INVENTORYALLOCATIONS->BILLEDQTY;
				$post2aPO['PurchaseNumber']=$SalesNo;
				$post2aPO['PurchaseoderNo']=$po;
				$post2aPO['franchisecode']=$FRANCHISECODE;
				$post2aPO['PurchaseDate']=$salesdate; 
				$post2aPO['voucherstatus']=$status;
				$post2aPO['masterid']=$masterid;
				$inventorytablePO='purchaseorder_details';
				if($groCount==0)
				{
					$news->addNews($post2aPO,$inventorytablePO);
				}
				else if($groCount>0)
				{
					$news->addNews($post2aPO,$inventorytablePO);
				}
				$j++;
			}
		}
		$i++;
		$count1=$count1.$responseid."~";  
}
			
elseif($vouchertype=='Purchase')
{
	/* To compute the Bill reference numbers*/
	$refno="";
	$billref="";
	foreach($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS) 
	{
		$billref=$BILLDETAILS->BILLNAME;
		If($billref !="")
		  {
		   $refno.= $billref."~";
		  }	  
	}
	$refno = substr($refno,0,-1);
	$SalesNo=$SalesNo."-RP-".$FRANCHISECODE;
	$masterid=$dbuvalue ;
	$post_rp['franchisecode']=$FRANCHISECODE;
	$post_rp['Purchasedate']=$salesdate;
	$post_rp['referenceno']=$refno;
	$post_rp['VoucherType']=$vouchertypename;        
	$post_rp['PurchaseNumber']=$SalesNo;
	$post_rp['PlantCode']=$plant;
    $post_rp['SupplierInvNo']=$supplerinvno;
    $post_rp['SupplierInvDate']=$suppinvdate;
	$post_rp['masterid']=$masterid;
	$po=$VOUCHER->REFERENCE;
	if($po=='')
	{
		$po='';
	}
	else
	{
		$po=$po."-".$FRANCHISECODE;
	}
	$post_rp['PO']=$po;
	$pString = str_replace(",", "",$VOUCHER->VCHAMOUNT);
	$floatvalue=floatval($pString); 
	if($floatvalue<0)
	{
		$floatvalue=(-1)*$floatvalue;
	}
	$post_rp['TotalPurchaseAmt']=$floatvalue;
	$post_rp['Narration']=$VOUCHER->NARRATION;
	$post_rp['ARBLWarehouseName']=$VOUCHER->PARTYLEDGERNAME;
	$post_rp['schemename']=$schemetype;
	$post_rp['voucherstatus']=$status;
	$vouchertable='purchase';
	$qry =mysql_query("select PurchaseNumber  from  purchase where masterid='".$masterid."' "	);
	$groCount=mysql_num_rows($qry);
	if($groCount==0)
	{
		$news->addNews($post_rp,$vouchertable);
	}
	else if($groCount>0)
	{
		$wherecon1= "masterid ='".$masterid."'";	
		$news->editNews( $post_rp,$vouchertable,$wherecon1);
        
		//To Delete the entries in r_purchasereport table
		$wherecondel= "unique_id ='".$masterid."'";
		$updatepurchasetable='r_purchasereport';
		$news->deleteNews($updatepurchasetable,$wherecondel);
		
		$taxtable='purchaseledger';
		$wherecon= "masterid ='".$masterid."'";	
		$news->deleteNews($taxtable,$wherecon); 

		$inventorytable='purchase_details';
		$wherecon1= "masterid ='".$masterid."'";	
		$news->deleteNews($inventorytable,$wherecon1); 
	}	
	$j=1;
	$K=1;
	foreach($VOUCHER->children() as $ALLLEDGERENTRIES)
	{
		if($vouchertype=='Purchase'&& $stkitemname=$ALLLEDGERENTRIES->LEDGERNAME!="")
		{
			$taxtable='purchaseledger';
			$ledger=$ALLLEDGERENTRIES->LEDGERNAME;
			$post1a['Taxledger']=$ALLLEDGERENTRIES->LEDGERNAME;
			$post1a['ledgertaxvalue']=$ALLLEDGERENTRIES->LEDGERTAXVALUE;
			$pString = str_replace(",", "",$ALLLEDGERENTRIES->AMOUNT);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{
				$floatvalue=(-1)*$floatvalue;
			}
			$post1a['Taxamount']=$floatvalue;
			$post1a['Purchaseno']=$SalesNo;
			$post1a['masterid']=$masterid;
			$post1a['Purchasedate']=$salesdate;
			$post1a['franchisecode']=$FRANCHISECODE;
			$post1a['voucherstatus']=$status;
			if($K > 1)
			{
				if($groCount==0)
				{
					$news->addNews($post1a,$taxtable);
				}
				else if($groCount>0)
				{
					$news->addNews($post1a,$taxtable);
				}	
			}  $K++;
		}
	}
	foreach($VOUCHER->children() as $INVENTORYALLOCATIONS)
	{
		if($vouchertype=='Purchase' && $stkitemname=$INVENTORYALLOCATIONS->STOCKITEMNAME!=""&&$PRODUCTCODE=$INVENTORYALLOCATIONS->PRODUCTCODE!=""&&$billqty=$INVENTORYALLOCATIONS->BILLEDQTY!="")
		{
			$inventorytable='purchase_details';
			$productcode=$INVENTORYALLOCATIONS->PRODUCTCODE;
			$post2a['ProductCode']=$INVENTORYALLOCATIONS->PRODUCTCODE;
			$post2a['ProductDescription']=$INVENTORYALLOCATIONS->STOCKITEMNAME;
                        $post2a['finvsno']=$INVENTORYALLOCATIONS->FINVSNO;
                        $finvsno1=$INVENTORYALLOCATIONS->FINVSNO;
			$post2a['Rate']=$INVENTORYALLOCATIONS->RATE;
			$pString = str_replace(",", "",$INVENTORYALLOCATIONS->AMOUNT);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{
				$floatvalue=(-1)*$floatvalue;
			}
			$invamt=$floatvalue;
			$post2a['Amount']=$floatvalue;
			$post2a['Quantity']=$INVENTORYALLOCATIONS->BILLEDQTY;
			$pString = str_replace(",", "",$INVENTORYALLOCATIONS->TAXVALUE);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{
				$floatvalue=(-1)*$floatvalue;
			}
			$post2a['taxvalue']=$floatvalue;
			$post2a['PurchaseNumber']=$SalesNo;
			$post2a['masterid']=$masterid;
			$post2a['franchisecode']=$FRANCHISECODE;
			$post2a['voucherstatus']=$status;
			$post2a['PurchaseDate']=$salesdate; 
			$invqty=$INVENTORYALLOCATIONS->BILLEDQTY;
			$arrayassign=array();
			$basicuser=$INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
			$a;											
			if($basicuser=='ANY')
			{
				$a='0';
			}
			else
			{
				$arrayassign=explode(",", $basicuser);
				$a=count($arrayassign)+1;;
			}
			if($groCount==0)
			{
				$news->addNews($post2a,$inventorytable);
			}
			//serial number maitain 
			else if($groCount>0)
			{
			$news->addNews($post2a,$inventorytable);
			}		
			$j++;
		/*To Insert the Purchase data into r_purchasereport table for reporting purpose*/	
		$insqry ="CALL r_insertpurchasereport('$SalesNo','$finvsno1','$refno','$masterid','$salesdate','$FRANCHISECODE','$invqty','$invamt','$floatvalue','$productcode','$plant','$supplerinvno','$suppinvdate');";
		$qry_exec=mysql_query($insqry);
		}
	}
	$i++;
	$count5=$count5.$responseid."~";
}

elseif($vouchertype=='Scheme Purchase')
{
	$SalesNo=$SalesNo."-SCP-".$FRANCHISECODE;
	$masterid=$dbuvalue ;
	$postaSCP['franchisecode']=$FRANCHISECODE   ;
	$postaSCP['Purchasedate']=$salesdate;
	$postaSCP['VoucherType']=$vouchertype;        
	$postaSCP['PurchaseNumber']=$SalesNo;
	$postaSCP['masterid']=$masterid;
	$po=$VOUCHER->REFERENCE;
	if($po=='')
	{
		$po='';
	}
	else
	{
		$po=$po."-".$FRANCHISECODE;
	}
	$postaSCP['PO']=$po;
	$pString = str_replace(",", "",$VOUCHER->VCHAMOUNT);
	$floatvalue=floatval($pString); 
	if($floatvalue<0)
	{
		$floatvalue=(-1)*$floatvalue;
	}
	$postaSCP['TotalPurchaseAmt']=$floatvalue;
	$postaSCP['Narration']=$VOUCHER->NARRATION;
	$postaSCP['ARBLWarehouseName']=$VOUCHER->PARTYLEDGERNAME;
	$postaSCP['schemename']=$schemetype;
	$postaSCP['voucherstatus']=$status;
	$arrayassign=array();
	$basicuser=$INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
	$a;											
	if($basicuser=='ANY')
	{
		$a='0';
	}
	else
	{
		$arrayassign=explode(",", $basicuser);
		$a=count($arrayassign);
	}
	$vouchertableSCP='purchase';
	$qry =mysql_query("select PurchaseNumber  from  purchase where masterid='".$masterid."'"	);
	$groCount=mysql_num_rows($qry);
	if($groCount==0)
	{
		$news->addNews($postaSCP,$vouchertableSCP);
	}
	else if($groCount>0)
	{
		$wherecon= "masterid ='".$masterid."'";	
		$news->editNews( $postaSCP,$vouchertableSCP,$wherecon);
		$taxtableSCP='purchaseledger';
		$whereconw= "masterid ='".$masterid."' ";	
		$news->deleteNews($taxtableSCP,$whereconw); 
		$inventorytableSCP='purchase_details';
		$wherecon2= "masterid ='".$masterid."'";	
		$news->deleteNews($inventorytableSCP,$wherecon2); 
		$batterytableSS='purchasebatterymaster';
		$wherecon3= "masterid ='".$masterid."'";	
		$news->deleteNews($batterytableSS,$wherecon3);  
	}	
	$j=1;
	$K=1;
	foreach($VOUCHER->children() as $ALLLEDGERENTRIES)
	{
		if($vouchertype=='Scheme Purchase'&&$stkitemname=$ALLLEDGERENTRIES->LEDGERNAME!="")
		{
			$taxtableSCP='purchaseledger';
			$post1aSCP['Taxledger']=$ALLLEDGERENTRIES->LEDGERNAME;
			$ledger=$ALLLEDGERENTRIES->LEDGERNAME;;
			$post1aSCP['ledgertaxvalue']=$ALLLEDGERENTRIES->LEDGERTAXVALUE;
			$pString = str_replace(",", "",$ALLLEDGERENTRIES->AMOUNT);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{
				$floatvalue=(-1)*$floatvalue;
			}
			$post1aSCP['Taxamount']=$floatvalue;
			$post1aSCP['Purchaseno']=$SalesNo;
			$post1aSCP['masterid']=$masterid;
			$post1aSCP['Purchasedate']=$salesdate;
			$post1aSCP['franchisecode']=$FRANCHISECODE;
			$post1aSCP['voucherstatus']=$status;
			if($K > 1)
			{
				if($groCount==0)
				{
					$news->addNews($post1aSCP,$taxtableSCP);
				}
				else if($groCount>0)
				{
					$news->addNews($post1aSCP,$taxtableSCP);
				}
			}  $K++;
		}
	}
	foreach($VOUCHER->children() as $INVENTORYALLOCATIONS)
	{
		if($vouchertype=='Scheme Purchase' && $stkitemname=$INVENTORYALLOCATIONS->STOCKITEMNAME!=""&&$PRODUCTCODE=$INVENTORYALLOCATIONS->PRODUCTCODE!=""&&$billqty=$INVENTORYALLOCATIONS->BILLEDQTY!="")
		{
			$inventorytableSCP='purchase_details';
			$productcode=$INVENTORYALLOCATIONS->PRODUCTCODE;
			$post2aSCP['ProductCode']=$INVENTORYALLOCATIONS->PRODUCTCODE;
			$post2aSCP['ProductDescription']=$INVENTORYALLOCATIONS->STOCKITEMNAME;
			$post2aSCP['Rate']=$INVENTORYALLOCATIONS->RATE;
			$pString = str_replace(",", "",$INVENTORYALLOCATIONS->AMOUNT);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{
				$floatvalue=(-1)*$floatvalue;
			}
			$post2aSCP['Amount']=$floatvalue;
			$post2aSCP['Quantity']=$INVENTORYALLOCATIONS->BILLEDQTY;
			$pString = str_replace(",", "",$INVENTORYALLOCATIONS->TAXVALUE);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{
				$floatvalue=(-1)*$floatvalue;
			}
			$post2aSCP['taxvalue']=$floatvalue;
			$post2aSCP['PurchaseNumber']=$SalesNo;
			$post2aSCP['masterid']=$masterid;
			$post2aSCP['franchisecode']=$FRANCHISECODE;
			$post2aSCP['PurchaseDate']=$salesdate; 
			$post2aSCP['voucherstatus']=$status;															
			$arrayassign=array();
			$basicuser=$INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
			$a;											
			if($basicuser=='ANY')
			{
				$a='0';
			}
			else
			{
				$arrayassign=explode(",", $basicuser);
				$a=count($arrayassign);
			}	
			if($groCount==0)
			{
				$news->addNews($post2aSCP,$inventorytableSCP);
			}
			else if($groCount>0)
			{ 
				$news->addNews($post2aSCP,$inventorytableSCP);
			}
			if($a!=0)
			{
				for($r=1;$r<$a;$r++)
				{  
					$batterytableSS='purchasebatterymaster';
					$post_scpbm['Batteryno']=trim($arrayassign[$r-1]);
					$post_scpbm['Productcode']=$productcode;
					$post_scpbm['purchasesNo']=$SalesNo;
					$post_scpbm['masterid']=$masterid;
					$post_scpbm['vochertype']=$vouchertype;
					if($groCount==0)
					{
						$news->addNews($post_scpbm,$batterytableSS);
					}
					else if($groCount>0)
					{
						$news->addNews($post_scpbm,$batterytableSS);
					}
				}
			} 
			$j++;
		}
	}
	$i++;
	$count7=$count7.$responseid."~";
}

elseif($vouchertype=='Debit Note') //Purchase Return
{
	/* To compute the Bill reference numbers*/
	$refno="";
	$billref="";
	foreach($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS) 
	{
		$billref=$BILLDETAILS->BILLNAME;
		If($billref !="")
		  {
		   $refno.= $billref."~";
		  }	  
	}
	$refno = substr($refno,0,-1);
	$SalesNo=$SalesNo."-PR-".$FRANCHISECODE;
	$masterid=$dbuvalue ;
	$postb['franchisecode']=$FRANCHISECODE;
	$postb['Purchasereturndate']=$salesdate;
	$postb['VoucherType']=$vouchertype;        
	$postb['PurchaseReturnNumber']=$SalesNo;
	$postb['referenceno']=$refno;
	$postb['masterid']=$masterid;
	$pString = str_replace(",", "",$VOUCHER->VCHAMOUNT);
	$floatvalue=floatval($pString); 
	if($floatvalue<0)
	{
		$floatvalue=(-1)*$floatvalue;
	}
	$postb['TotalPurchaseRetAmt']=$floatvalue;
	$postb['Narration']=$VOUCHER->NARRATION;
	$postb['ARBLWarehouseName']=$VOUCHER->PARTYLEDGERNAME;
	$postb['schemename']=$schemetype;
	$postb['voucherstatus']=$status;
	$vouchertable='purchasereturn';
	$qry=mysql_query("select PurchaseReturnNumber  from  purchasereturn where masterid='".$masterid."' "	);
	$groCount=mysql_num_rows($qry);
	if($groCount==0)
	{
		$news->addNews($postb,$vouchertable);
	}
	else if($groCount>0)
	{
		$wherecon= "masterid ='".$masterid."'";	
		$news->editNews( $postb,$vouchertable,$wherecon);
		$taxtable='purchasreturnledger';
		$wherecon1= "masterid ='".$masterid."'";	
		$news->deleteNews($taxtable,$wherecon1); 
		$inventorytable='purchasereturn_details';
		$wherecon2= "masterid ='".$masterid."' ";	
		$news->deleteNews($inventorytable,$wherecon2);
		
		//To Delete the entries in r_salesreport table
		$wherecondel= "unique_id ='".$masterid."'";
		$updateprtable='r_purchasereturn';
		$news->deleteNews($updateprtable,$wherecondel);
	}
	$j=1;
	$Ki=1;
	foreach($VOUCHER->children() as $ALLLEDGERENTRIES)
	{
		//if($vouchertype=='Purchase Return'&&$stkitemname=$ALLLEDGERENTRIES->LEDGERNAME!="")
		if($vouchertype=='Debit Note'&&$stkitemname=$ALLLEDGERENTRIES->LEDGERNAME!="")
		{
			
			$taxtable='purchasreturnledger';
			$post1b['Taxledger']=$ALLLEDGERENTRIES->LEDGERNAME;
			$ledger=$ALLLEDGERENTRIES->LEDGERNAME;
			$post1b['ledgertaxvalue']=$ALLLEDGERENTRIES->LEDGERTAXVALUE;
			$pString = str_replace(",", "",$ALLLEDGERENTRIES->AMOUNT);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{
				$floatvalue=(-1)*$floatvalue;
			}
			$post1b['Taxamount']=$floatvalue;
			$post1b['PurchaseRetno']=$SalesNo;
			$post1b['masterid']=$masterid;
			$post1b['PurchaseRetdate']=$salesdate;
			$post1b['franchisecode']=$FRANCHISECODE;
			$post1b['voucherstatus']=$status;    
			if($Ki > 1)
			{
				$news->addNews($post1b,$taxtable);
			}
			$Ki++;
		}
	}
	foreach($VOUCHER->children() as $INVENTORYALLOCATIONS)
	{

	if($vouchertype=='Debit Note'&&$stkitemname=$INVENTORYALLOCATIONS->STOCKITEMNAME!=""&&$PRODUCTCODE=$INVENTORYALLOCATIONS->PRODUCTCODE!=""&&$billqty=$INVENTORYALLOCATIONS->BILLEDQTY!="")
		{
			$inventorytable='purchasereturn_details';
			$post2b['ProductCode']=$INVENTORYALLOCATIONS->PRODUCTCODE;
			$pcode=$INVENTORYALLOCATIONS->PRODUCTCODE;;
			$post2b['ProductDescription']=$INVENTORYALLOCATIONS->STOCKITEMNAME;
                        $post2b['finvsno']=$INVENTORYALLOCATIONS->FINVSNO;
                        $finvsno1=$INVENTORYALLOCATIONS->FINVSNO;
			$post2b['Rate']=$INVENTORYALLOCATIONS->RATE;
			$pString = str_replace(",", "",$INVENTORYALLOCATIONS->AMOUNT);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{
				$floatvalue=(-1)*$floatvalue;
			}
			$post2b['Amount']=$floatvalue;
			$invamt=$floatvalue;
			$post2b['Quantity']=$INVENTORYALLOCATIONS->BILLEDQTY;
			$invqty=$INVENTORYALLOCATIONS->BILLEDQTY;
			$pString = str_replace(",", "",$INVENTORYALLOCATIONS->TAXVALUE);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{
				$floatvalue=(-1)*$floatvalue;
			}
			$post2b['taxvalue']=$floatvalue;
			$post2b['PurchaseRetNumber']=$SalesNo;
			$post2b['masterid']=$masterid;
			$post2b['franchisecode']=$FRANCHISECODE;
			$post2b['RetDate']=$salesdate;  
			$post2b['voucherstatus']=$status; 
			$basicuser=$INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
			$a;
			$basicuser;											
			if($basicuser=='&#4; Any')
			{
				$a='0';
			}
			else if($basicuser=='ANY')
			{
				$a='0';
			}
			else
			{
				$arrayassign=explode(",", $basicuser);
				$a=count($arrayassign);
			}
			if($groCount==0)
			{
				$news->addNews($post2b,$inventorytable);
			}
			else if($groCount>0)
			{
				$news->addNews($post2b,$inventorytable);
			}
			$j++;
			$insqry ="CALL r_insertpurchasereturnreport('$SalesNo','$finvsno1','$refno','$masterid','$salesdate','$FRANCHISECODE','$invqty','$invamt','$floatvalue','$pcode');";
			$qry_exec=mysql_query($insqry);
		}
	}
	$i++;
	$count8=$count8.$responseid."~";
}

elseif($vouchertype=='Credit Note')//Sales Return
{
		$SalesNo=$SalesNo."-SR-".$FRANCHISECODE;
		$masterid=$dbuvalue ;
		$refno="";
		$billref="";
		foreach($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS)
		{
		  //echo $BILLDETAILS->BILLNAME;
		  $billref=$BILLDETAILS->BILLNAME;
		  //echo $billref;
		  If($billref !="")
		  {
		   $refno.= $billref."~";
		  }
		}
		$refno = substr($refno,0,-1);
		$postc['franchisecode']=$FRANCHISECODE;
		$postc['salesRetdate']=$salesdate;
		$postc['VoucherType']=$vouchertype;        
		$postc['salesRetno']=$SalesNo;
		$postd['referenceno']=$refno;
		$postc['masterid']=$masterid;
		$postc['retailername']=$VOUCHER->PARTYLEDGERNAME;
		$pString = str_replace(",", "",$VOUCHER->VCHAMOUNT);
		$floatvalue=floatval($pString); 
		if($floatvalue<0)
		{
		$floatvalue=(-1)*$floatvalue;
		}
		$postc['Rettotalamount']=$floatvalue;
		$postc['narration']=$VOUCHER->NARRATION;
		$postc['franchisecode']=$FRANCHISECODE;
		$postc['schemename']=$schemetype;
		$postc['voucherstatus']=$status;
		$vouchertable='salesreturn';
		// echo "salesreturn"   ;
		//$qry =mysql_query("select salesRetno  from  salesreturn where masterid='".$masterid."'and voucherstatus='ACTIVE' "	);
		$qry =mysql_query("select salesRetno  from  salesreturn where masterid='".$masterid."' "	);

		$groCount=mysql_num_rows($qry);
		if($groCount==0)
		{
		$news->addNews($postc,$vouchertable);
		}
		else if($groCount>0)
		{


		$wherecon= "masterid ='".$masterid."'";	
		$news->editNews( $postc,$vouchertable,$wherecon);

		$taxtable='salesledgerreturn';
		$wherecon1= "masterid ='".$masterid."' ";	
		$news->deleteNews($taxtable,$wherecon1);

		$inventorytable='salesreturnitem';
		$wherecon2= "masterid ='".$masterid."' ";
		$news->deleteNews($inventorytable,$wherecon2);	

				//To Delete the entries in r_salesreturnreport table
				
				$wherecondel= "unique_id ='".$masterid."'";
				//$delqry =mysql_query("DELETE FROM r_salesreturn WHERE unique_id ='".$masterid."'");
				$updatesalestable='r_salesreturn';
				$news->deleteNews($updatesalestable,$wherecondel);
		/* $batterytable='salesbatteryreturn';
		$wherecon3= "masterid ='".$masterid."' ";	
		$news->deleteNews($batterytable,$wherecon3); */

		}		


		$j=1;
		$K=1;
		foreach($VOUCHER->children() as $ALLLEDGERENTRIES)
		{

		if($vouchertype=='Credit Note'&&$stkitemname=$ALLLEDGERENTRIES->LEDGERNAME!="")
		 {
			$taxtable='salesledgerreturn';
			$post1c['taxledger']=$ALLLEDGERENTRIES->LEDGERNAME;
			$ledger=$ALLLEDGERENTRIES->LEDGERNAME;
			$post1c['ledgertaxvalue']=$ALLLEDGERENTRIES->LEDGERTAXVALUE;
			$pString = str_replace(",", "",$ALLLEDGERENTRIES->AMOUNT);
			$floatvalue=floatval($pString); 
			//  $floatvalue=(-1)*$floatvalue;
			if($floatvalue<0)
			{
			$floatvalue=(-1)*$floatvalue;
			}
			$post1c['taxamount']=$floatvalue;
			$post1c['SalesRetNo']=$SalesNo;
			$post1c['masterid']=$masterid;
			$post1c['salesRetdate']=$salesdate;
			$post1c['franchisecode']=$FRANCHISECODE;
			$post1c['voucherstatus']=$status;    
			// echo "salesledgerreturn"   ;

			if($K > 1)
			{

			if($groCount==0)
			{
			$news->addNews($post1c,$taxtable);
			}
			else if($groCount>0)
			{ 
			$news->addNews($post1c,$taxtable);	
			//echo "Duplicate entry in Sales Ledger return  -  ".$SalesNo;
			}
			//$news->addNews($post1c,$taxtable);
			}   
			$K++;
		 }
	}

	foreach($VOUCHER->children() as $INVENTORYALLOCATIONS)
	{
	if($vouchertype=='Credit Note'&&$stkitemname=$INVENTORYALLOCATIONS->STOCKITEMNAME!=""&&$PRODUCTCODE=$INVENTORYALLOCATIONS->PRODUCTCODE!=""&&$billqty=$INVENTORYALLOCATIONS->BILLEDQTY!="")
	{
	$inventorytable='salesreturnitem';
	$productcode=$INVENTORYALLOCATIONS->PRODUCTCODE;
	$post2c['productcode']=$INVENTORYALLOCATIONS->PRODUCTCODE;
	$pcode=$INVENTORYALLOCATIONS->PRODUCTCODE;
	$post2c['productdes']=$INVENTORYALLOCATIONS->STOCKITEMNAME;
	$post2c['rate']=$INVENTORYALLOCATIONS->RATE;
	$pString = str_replace(",", "",$INVENTORYALLOCATIONS->AMOUNT);
	$floatvalue=floatval($pString); 
	if($floatvalue<0)
	{
	$floatvalue=(-1)*$floatvalue;
	}
	$invamt=$floatvalue;
	$post2c['amount']=$floatvalue;
	$post2c['quantity']=$INVENTORYALLOCATIONS->BILLEDQTY;
	$invqty=$INVENTORYALLOCATIONS->BILLEDQTY;

	$pString = str_replace(",", "",$INVENTORYALLOCATIONS->TAXVALUE);
	$floatvalue=floatval($pString); 
	if($floatvalue<0)
	{
	$floatvalue=(-1)*$floatvalue;
	}



	$post2c['taxvalue']=$floatvalue;
	$post2c['saleRetsno']=$SalesNo;
	$post2c['masterid']=$masterid;
	$post2c['salesRetdate']=$salesdate;
	$post2c['voucherstatus']=$status;
	$post2c['franchisecode']=$FRANCHISECODE;
	$arrayassign=array();
	$basicuser=$INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
	$arrayassign=explode(",", $basicuser);
	$a=count($arrayassign);
	//  echo "salesreturnitem"   ;

	if($groCount==0)
	{
	$news->addNews($post2c,$inventorytable);
	}
	else if($groCount>0)
	{
	$news->addNews($post2c,$inventorytable);
	//echo "Duplicate entry in Sales return item  -  ".$SalesNo;
	}
		$j++;
		$insqry ="CALL r_insertsalesreturnreport('$SalesNo','$refno','$masterid','$salesdate','$FRANCHISECODE','$invqty','$invamt','$floatvalue','$productcode');";
		echo $insqry;
		$qry_exec=mysql_query($insqry);
	}

	}
	$i++;
	//$responseid=$responseid."!".$masterid;
	$count9=$count9.$responseid."~";
}

elseif($vouchertype=='Sales')
{
			//$post['franchisecode']=$FRANCHISECODE;
			$refno="";
			$billref="";
			foreach($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS)
			{
			  //echo $BILLDETAILS->BILLNAME;
			  $billref=$BILLDETAILS->BILLNAME;
			  //echo $billref;
			  If($billref !="")
			  {
			   $refno.= $billref."~";
			  }
			  
			  
			}
			  $refno = substr($refno,0,-1);
			  //echo $refno;
			 
			//}
			//echo $refno;
			$SalesNo=$SalesNo."-RS-".$FRANCHISECODE;
			$masterid=$dbuvalue ;
	   
			$postd['salesdate']=$salesdate;
			$postd['VoucherType']=$vouchertypename;        
			$postd['salesno']=$SalesNo;
			$postd['referenceno']=$refno;
			$postd['masterid']=$masterid;
			$postd['retailername']=$VOUCHER->PARTYLEDGERNAME;
			$pString = str_replace(",", "",$VOUCHER->VCHAMOUNT);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{
			$floatvalue=(-1)*$floatvalue;
			}
			
			$postd['totalamount']=$floatvalue;
			$postd['narration']=$VOUCHER->NARRATION;
			$postd['franchisecode']=$FRANCHISECODE;
			$postd['schemename']=$schemetype;
			$postd['voucherstatus']=$status;
			$postd['pricelevel']=$VOUCHER->PRICELEVEL;
			$vouchertable='retailersales';
			//  echo "retailersales"   ;
			$qry =mysql_query("select salesno  from  retailersales where masterid='".$masterid."'"	);

			$groCount=mysql_num_rows($qry);
			if($groCount==0)
			{
			$news->addNews($postd,$vouchertable);
			}
			else if($groCount>0)
			{
			$wherecon= "masterid ='".$masterid."'";	
			$news->editNews( $postd,$vouchertable,$wherecon);
			
			//To Delete the entries in r_salesreport table
			
			$wherecondel= "unique_id ='".$masterid."'";
			//$delqry =mysql_query("DELETE FROM r_salesreport WHERE unique_id ='".$masterid."'");
			$updatesalestable='r_salesreport';
			$news->deleteNews($updatesalestable,$wherecondel);
					
			$inventorytable='retailersalesitem';
			$wherecon2= "masterid ='".$masterid."'";	
			$news->deleteNews($inventorytable,$wherecon2);
			$taxtable='retailersalesledger';
			$wherecon1= "masterid ='".$masterid."' ";	
			$news->deleteNews($taxtable,$wherecon1);
			}


			$j=1;
			$K=1;

			foreach($VOUCHER->children() as $ALLLEDGERENTRIES)
			{

			//if($vouchertype=='Regular Sales'&&$stkitemname=$ALLLEDGERENTRIES->LEDGERNAME!="")
			if($vouchertype=='Sales'&&$stkitemname=$ALLLEDGERENTRIES->LEDGERNAME!="")
			{
			$taxtable='retailersalesledger';
			$post1d['taxledger']=$ALLLEDGERENTRIES->LEDGERNAME;
			$ledger=$ALLLEDGERENTRIES->LEDGERNAME;;
			$post1d['ledgertaxvalue']=$ALLLEDGERENTRIES->LEDGERTAXVALUE;
			$pString = str_replace(",", "",$ALLLEDGERENTRIES->AMOUNT);
			$floatvalue=floatval($pString); 
			if($floatvalue<0)
			{

			$floatvalue=(-1)*$floatvalue;
			}
			// $floatvalue=(-1)*$floatvalue;
			$post1d['taxamount']=$floatvalue;
			$post1d['SalesNo']=$SalesNo;
			$post1d['masterid']=$masterid;
			$post1d['salesdates']=$salesdate;
			$post1d['franchisecode']=$FRANCHISECODE;
			$post1d['voucherstatus']=$status;
			// echo "RetailerSalesLedger"   ;

			if($K > 1)
			{
			$news->addNews($post1d,$taxtable);
			}  $K++;
			}
			}

			foreach($VOUCHER->children() as $INVENTORYALLOCATIONS)
			{
			/* if($vouchertype=='Regular Sales'&&$stkitemname=$INVENTORYALLOCATIONS->STOCKITEMNAME!=""&&$PRODUCTCODE=$INVENTORYALLOCATIONS->PRODUCTCODE!=""&&$billqty=$INVENTORYALLOCATIONS->BILLEDQTY!="") */
			
			if($vouchertype=='Sales'&&$stkitemname=$INVENTORYALLOCATIONS->STOCKITEMNAME!=""&&$PRODUCTCODE=$INVENTORYALLOCATIONS->PRODUCTCODE!=""&&$billqty=$INVENTORYALLOCATIONS->BILLEDQTY!="")
			{
			
			$inventorytable='retailersalesitem';
			$productcode=$INVENTORYALLOCATIONS->PRODUCTCODE;
			$post2d['productcode']=$INVENTORYALLOCATIONS->PRODUCTCODE;
			$post2d['productdes']=$INVENTORYALLOCATIONS->STOCKITEMNAME;
			$post2d['rate']=$INVENTORYALLOCATIONS->RATE;
			$pString = str_replace(",", "",$INVENTORYALLOCATIONS->AMOUNT);
			$floatvalue=floatval($pString);
			
			if($floatvalue<0)
			{
			$floatvalue=(-1)*$floatvalue;
			}
			$invamt=$floatvalue;
			$post2d['amount']=$floatvalue;
			$post2d['quantity']=$INVENTORYALLOCATIONS->BILLEDQTY;
			$invqty=$INVENTORYALLOCATIONS->BILLEDQTY;

			$pString = str_replace(",", "",$INVENTORYALLOCATIONS->TAXVALUE);
			$floatvalue=floatval($pString); 
			
			if($floatvalue<0)
			{
			$floatvalue=(-1)*$floatvalue;
			}
			
			$post2d['taxvalue']=$floatvalue;
			$post2d['salesno']=$SalesNo;
			$post2d['masterid']=$masterid;
			$post2d['salesdates']=$salesdate;
			$post2d['voucherstatus']=$status;
			$post2d['franchisecode']=$FRANCHISECODE;

			if($groCount==0)
			{
			$news->addNews($post2d,$inventorytable);
			}
			else if($groCount>0)
			{
			//var_dump($post2d);
			$news->addNews($post2d,$inventorytable);
			//echo "Duplicate entry in Retailer Sales Item -  ".$SalesNo;
			}				 

			$j++;
			$insqry ="CALL r_insertsalesreport('$SalesNo','$refno','$masterid','$salesdate','$FRANCHISECODE','$invqty','$invamt','$floatvalue','$productcode');";
			//echo $insqry;
			$qry_exec=mysql_query($insqry);		
			}

			}
			$i++;
			//  $responseid=$responseid."!".$masterid;
			$count10=$count10.$responseid."~";
	}

elseif($vouchertype=='Scheme Sales')
{
		$SalesNo=$SalesNo."-SCS-".$FRANCHISECODE;
		$masterid=$dbuvalue ;

		$postdSCS['salesdate']=$salesdate;
		$postdSCS['VoucherType']=$vouchertype;        
		$postdSCS['salesno']=$SalesNo;
		$postdSCS['masterid']=$masterid;
		$postdSCS['retailername']=$VOUCHER->PARTYLEDGERNAME;
		$pString = str_replace(",", "",$VOUCHER->VCHAMOUNT);
		$floatvalue=floatval($pString); 
		if($floatvalue<0)
		{
		$floatvalue=(-1)*$floatvalue;
		}
		$postdSCS['totalamount']=$floatvalue;
		$postdSCS['narration']=$VOUCHER->NARRATION;
		$postdSCS['franchisecode']=$FRANCHISECODE;
		$postdSCS['schemename']=$schemetype;
		$postdSCS['voucherstatus']=$status;
		$postdSCS['pricelevel']=$VOUCHER->PRICELEVEL;
		$vouchertableSCS='retailersales';
		//  echo "retailersales"   ;
		//$qry =mysql_query("select salesno  from  retailersales where masterid='".$masterid."'and voucherstatus='ACTIVE' "	);
		$qry =mysql_query("select salesno  from  retailersales where masterid='".$masterid."' "	);

		$groCount=mysql_num_rows($qry);
		if($groCount==0)
		{
		$news->addNews($postdSCS,$vouchertableSCS);
		}
		else if($groCount>0)
		{
		$wherecon1= "masterid ='".$masterid."'";	
		$news->editNews( $postdSCS,$vouchertableSCS,$wherecon1);

		$taxtableSCS='retailersalesledger';
		$wherecon2= "masterid ='".$masterid."'";	
		$news->deleteNews($taxtableSCS,$wherecon2); 

		$inventorytableSCS='retailersalesitem';
		$whereconss= "masterid ='".$masterid."'";	
		$news->deleteNews( $inventorytableSCS,$whereconss);

		$batterytableSCS='retailerbatterymaster';
		$wherecons= "masterid ='".$masterid."'";	
		$news->deleteNews($batterytableSCS,$wherecons); 

		}


		$j=1;
		$K=1;
		foreach($VOUCHER->children() as $ALLLEDGERENTRIES)
		{

		if($vouchertype=='Scheme Sales'&&$stkitemname=$ALLLEDGERENTRIES->LEDGERNAME!="")
		{
		$taxtableSCS='retailersalesledger';
		$post1dSCS['taxledger']=$ALLLEDGERENTRIES->LEDGERNAME;
		$ledger=$ALLLEDGERENTRIES->LEDGERNAME;
		$post1dSCS['ledgertaxvalue']=$ALLLEDGERENTRIES->LEDGERTAXVALUE;
		$pString = str_replace(",", "",$ALLLEDGERENTRIES->AMOUNT);
		$floatvalue=floatval($pString); 
		if($floatvalue<0)
		{
		$floatvalue=(-1)*$floatvalue;
		}
		//$floatvalue=(-1)*$floatvalue;
		$post1dSCS['taxamount']=$floatvalue;
		$post1dSCS['SalesNo']=$SalesNo;
		$post1dSCS['masterid']=$masterid;
		$post1dSCS['salesdates']=$salesdate;
		$post1dSCS['franchisecode']=$FRANCHISECODE;
		$post1dSCS['voucherstatus']=$status;

		if($K > 1)
		{
		$news->addNews($post1dSCS,$taxtableSCS);
		}  $K++;
		}
		}

		foreach($VOUCHER->children() as $INVENTORYALLOCATIONS)
		{
		if($vouchertype=='Scheme Sales'&&$stkitemname=$INVENTORYALLOCATIONS->STOCKITEMNAME!=""&&$PRODUCTCODE=$INVENTORYALLOCATIONS->PRODUCTCODE!=""&&$billqty=$INVENTORYALLOCATIONS->BILLEDQTY!="")
		{
		$inventorytableSCS='retailersalesitem';
		$productcode=$INVENTORYALLOCATIONS->PRODUCTCODE;
		$post2dSCS['productcode']=$INVENTORYALLOCATIONS->PRODUCTCODE;
		$post2dSCS['productdes']=$INVENTORYALLOCATIONS->STOCKITEMNAME;
		$post2dSCS['rate']=$INVENTORYALLOCATIONS->RATE;
		$pString = str_replace(",", "",$INVENTORYALLOCATIONS->AMOUNT);
		$floatvalue=floatval($pString); 
		if($floatvalue<0)
		{
		$floatvalue=(-1)*$floatvalue;
		}
		$post2dSCS['amount']=$floatvalue;
		$post2dSCS['quantity']=$INVENTORYALLOCATIONS->BILLEDQTY;

		$pString = str_replace(",", "",$INVENTORYALLOCATIONS->TAXVALUE);
		$floatvalue=floatval($pString); 
		if($floatvalue<0)
		{
		$floatvalue=(-1)*$floatvalue;
		} 


		$post2dSCS['taxvalue']=$floatvalue;
		$post2dSCS['salesno']=$SalesNo;
		$post2dSCS['masterid']=$masterid;
		$post2dSCS['salesdates']=$salesdate;
		$post2dSCS['franchisecode']=$FRANCHISECODE;
		$post2dSCS['voucherstatus']=$status;
		$arrayassign=array();
		$basicuser=$INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
		$arrayassign=explode(",", $basicuser);
		$a=count($arrayassign);
		$news->addNews($post2dSCS,$inventorytableSCS);


		for($q=0;$q<$a;$q++)
		{     $batterytableSCS='retailerbatterymaster';
		$post3dSCS['Batteryno']=trim($arrayassign[$q]);
		$battery=$arrayassign[$q-1];;
		$post3dSCS['Productcode']=$productcode;
		$post3dSCS['SalesNo']=$SalesNo;
		$post3dSCS['masterid']=$masterid;
		//  $post3dSCS['vochertype']=$vouchertype;
		$news->addNews($post3dSCS,$batterytableSCS);


		}
		$j++;
		}

		}
		$i++;
		//$responseid=$responseid."!".$masterid;
		$count12=$count12.$responseid."~";
		}

}
}
}
}			
}
$count=$count1.$count2.$count3.$count4.$count5.$count6.$count7.$count8.$count9.$count10.$count11.$count12.$count13.$count14.$count15. $count16. $count17.$count18.$count19.$count;

$tax='uploadstatus';
/* $wherecon1= "franchisecode ='".$FRANCHISECODE."' ";
$news->deleteNews($tax,$wherecon1); */
$post1['franchisecode']=$FRANCHISECODE;
$post1['date']=date("Y-m-d");
$post1['status']='Delivered';
$news->addNews($post1,$tax);	

//$name="$count".$a+1;			
//if($count1!="")
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
