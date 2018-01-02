<?php
session_start();
global $host, $uid, $pass, $databname;
$str        = "";
$data       = array();
$uploadfile = "rights.txt";
$file = fopen($uploadfile, "r") or exit("Unable to open file!");
while (!feof($file)) {
    $str = $str . fgetc($file);
}
list($host, $uid, $pass, $databname) = explode('~', trim($str));
fclose($file);
$_SESSION["dbhostname"] = $host;
$_SESSION["dbusername"] = $uid;
$_SESSION["dbpassword"] = $pass;
$_SESSION["databname"]  = $databname;
require_once 'masterclass.php';
$news    = new News(); // Create a new News Object
//if (isset($HTTP_RAW_POST_DATA))
    {
	$getData = "<ENVELOPE>
 <FRANCHISECODE>000003</FRANCHISECODE>
 <REQUEST>
  <VOUCHER>
   <VOUCHERUPLOADSTATE>NEW</VOUCHERUPLOADSTATE>
   <VOUCHERACTIVE>Active</VOUCHERACTIVE>
   <VOUCHERSTATUS>0</VOUCHERSTATUS>
   <MASTERID>40</MASTERID>
   <GUID>df5b933c-4d20-43af-a515-63cc9d52be3a-00000028</GUID>
   <VOUCHERTYPENAME>Sales</VOUCHERTYPENAME>
   <VOUCHERPARENT>Sales</VOUCHERPARENT>
   <PRICELEVEL>Retailer</PRICELEVEL>
   <DATE>19-Feb-2015</DATE>
   <VOUCHERNUMBER>1</VOUCHERNUMBER>
   <REFERENCE></REFERENCE>
   <PARTYLEDGERNAME>Customer</PARTYLEDGERNAME>
   <LOCATION>chennai</LOCATION>
   <TERTIARYCODE>TERTIARYCODE</TERTIARYCODE>
   <VCHAMOUNT>5,649.60</VCHAMOUNT>
   <NARRATION>eeeeeeeeree</NARRATION>
   <ALLLEDGERENTRIES>
    <LEDGERNAME>Customer</LEDGERNAME>
    <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
    <AMOUNT>-5649.60</AMOUNT>
    <LEDGERTAXVALUE>0</LEDGERTAXVALUE>
    <BILLDETAILS>
     <BILLNAME>1</BILLNAME>
     <AMOUNTNAME>5,649.60</AMOUNTNAME>
    </BILLDETAILS>
   </ALLLEDGERENTRIES>
   <ALLLEDGERENTRIES>
    <LEDGERNAME>Sales@5%</LEDGERNAME>
    <ISPARTYLEDGER>No</ISPARTYLEDGER>
    <AMOUNT>105.60</AMOUNT>
    <LEDGERTAXVALUE>2</LEDGERTAXVALUE>
   </ALLLEDGERENTRIES>
   <ALLLEDGERENTRIES>
    <LEDGERNAME>Output VAt@5%</LEDGERNAME>
    <ISPARTYLEDGER>No</ISPARTYLEDGER>
    <AMOUNT>264.00</AMOUNT>
    <LEDGERTAXVALUE>5</LEDGERTAXVALUE>
   </ALLLEDGERENTRIES>
   <INVENTORY>
    <PRODUCTCODE>A3003-FE292</PRODUCTCODE>
    <STOCKITEMNAME>FE292</STOCKITEMNAME>
    <RATE>160.00</RATE>
    <AMOUNT>1760.00</AMOUNT>
    <ACTUALQTY>11</ACTUALQTY>
    <BILLEDQTY>11</BILLEDQTY>
    <TAXVALUE>88.00</TAXVALUE>
    <DISCOUNT></DISCOUNT>
   </INVENTORY>
   <INVENTORY>
    <PRODUCTCODE>12312</PRODUCTCODE>
    <STOCKITEMNAME>ONIDA</STOCKITEMNAME>
    <RATE>160.00</RATE>
    <AMOUNT>3520.00</AMOUNT>
    <ACTUALQTY>22</ACTUALQTY>
    <BILLEDQTY>22</BILLEDQTY>
    <TAXVALUE>176.00</TAXVALUE>
    <DISCOUNT></DISCOUNT>
   </INVENTORY>
  </VOUCHER>
 </REQUEST>
</ENVELOPE>";
    $xml_object = simplexml_load_string($getData); 
  //  $xml_object = simplexml_load_string($HTTP_RAW_POST_DATA);
    if ($xml_object != "") {
        $i               = 1;
        $groCount        = 0;
        $masterid        = $oldserialnumber = $regionname=$branchname=$franchisename=$fraCount =NULL;
        $count           = $count1 = $count2 = $count3 = $count4 = $count5 = $count6 = $count7 = $count8 = $count9 = $count10 = $count11 = $count12 = $count13 = $count14 = $count15 = $count16 = $count17 = $count18 = $count19 = $dbuvalue = NULL;
        date_default_timezone_set('UTC');
        $FRANCHISECODE = $xml_object->FRANCHISECODE;
        $category      = $xml_object->REQUEST->VOUCHER;
        foreach ($xml_object->REQUEST->children() as $VOUCHER) {
            $dbuvalue        = $VOUCHER->GUID; //DATABASEUNIQUEVALUE;
            $voucherstate    = $VOUCHER->VOUCHERSTATE;
            $SalesNo         = $VOUCHER->VOUCHERNUMBER;
            $vouchertype     = $VOUCHER->VOUCHERPARENT;
            $vouchertypename = $VOUCHER->VOUCHERTYPENAME;
            $supplerinvno    = $VOUCHER->SUPPLIERIVOICENUMBER;
            $sdt             = $VOUCHER->SUPPLIERIVOICEDATE;
            $middle1         = strtotime($sdt);
            $suppinvdate     = date('Y-m-d', $middle1);
            $plant           = $VOUCHER->PLANT;
            $schemetype      = $VOUCHER->SCHEMETYPE;
            $status          = 'ACTIVE';
            $masterid        = $VOUCHER->MASTERID;
            $responseid      = $masterid;
            $olddate         = $VOUCHER->DATE;
            $middle          = strtotime($olddate);
            $salesdate       = date('Y-m-d', $middle);
            $fraqry          = mysql_query("select Region, Branch,Franchisename from  franchisemaster where  PrimaryFranchise='" . $FRANCHISECODE . "'");
			while( $record = mysql_fetch_array($fraqry)){
				$regionname=$record['Region'];
				$branchname=$record['Branch'];
				$franchisename=$record['Franchisename'];
				$fraCount = 1;
				}	
            if ($fraCount == 0) {
                echo "Franchisee Not Available in tally central server";
            } else {
                if ($vouchertype == "") {
                    echo "Voucher type  Not Available in tally tag";
                } elseif ($vouchertype == 'RETAILER') {
                    $postret['fmexecutive']            = $FRANCHISECODE;
                    $postret['RetailerName']           = $VOUCHER->RETAILERNAME;
                    $retcode                           = $VOUCHER->RETAILERCODE;
                    $postret['RetailerCode']           = $VOUCHER->RETAILERCODE;
                    $postret['Category']               = $VOUCHER->RETAILERCATEGORY;
                    $postret['ContactName']            = $VOUCHER->RETAILERCONTACTPERSON;
                    $postret['ContactNo']              = $VOUCHER->RETAILERCONTACTNO;
                    $postret['Address']                = $VOUCHER->RETAILERADDRESS;
                    $postret['City']                   = $VOUCHER->RETAILERCITY;
                    $postret['Districtname']           = $VOUCHER->RETAILERDISTRICY;
                    $postret['accountholdersname']     = $VOUCHER->RETAILERACCNAME;
                    $postret['bankname']               = $VOUCHER->RETAILERBANKNAME;
                    $postret['branchname']             = $VOUCHER->RETAILERBRANCHNAME;
                    $postret['ifsccode']               = $VOUCHER->RETAILERIFSCCODE;
                    $postret['CreditDays']             = $VOUCHER->RETAILERCRDAYS;
                    $postret['CreditLimit']            = $VOUCHER->RETAILERCRLIMIT;
                    $postret['TinNo']                  = $VOUCHER->RETAILERTINNO;
                    $postret['TinDate']                = $VOUCHER->RETAILERTINDATE;
                    $postret['AccNo']                  = $VOUCHER->RETAILERACCNO;
                    $postret['franchiseeme']           = $VOUCHER->ARBLMENAMEVALUE;
                    $postret['retailerclassification'] = $VOUCHER->ARBLCLASSVALUE;
                    $postret['geographical']           = $VOUCHER->ARBLTYPEVALUE;
                    $postret['retailercategory1']      = $VOUCHER->RCATEGORYAVALUE;
                    $postret['retailercategory2']      = $VOUCHER->RCATEGORYBVALUE;
                    $postret['retailercategory3']      = $VOUCHER->RCATEGORYCVALUE;
                    if ($retcode != '') {
                        $tname  = "retailermaster";
                        $result = mysql_query("SELECT * FROM retailermaster where RetailerCode ='" . $retcode . "'");
                        $myrow1 = mysql_num_rows($result); //mysql_fetch_array($retval);
                        if ($myrow1 == 0) {
                            $news->addNews($postret, $tname);
                            $count18 = $count18 . $retcode . "~";
                        } else {
                            $wherecon = "RetailerCode ='" . $postret['RetailerCode'] . "'";
                            $news->editNews($postret, $tname, $wherecon);
                            $count18 = $count18 . $retcode . "~";
                        }
                    }
                } else if ($dbuvalue == "") {
                    echo "DATABASEUNIQUEVALUE Not Available in tally tag";
                } else {
                    if ($vouchertype == 'Purchase Order') {
                        $SalesNo                    = $SalesNo . "-PO-" . $FRANCHISECODE;
                        $masterid                   = $dbuvalue;
                        $postaPO['FranchiseCode']   = $FRANCHISECODE;
                        $postaPO['Purchasedate']    = $salesdate;
                        $postaPO['VoucherType']     = $vouchertype;
                        $postaPO['PurchaseNumber']  = $SalesNo;
                        $postaPO['masterid']        = $masterid;
                        $po                         = $VOUCHER->ORDERNO;
                        $po                         = $po . "-" . $FRANCHISECODE;
                        $postaPO['PurchaseorderNo'] = $po;
                        $postaPO['voucherstatus']   = $status;
                        $pString                    = str_replace(",", "", $VOUCHER->VCHAMOUNT);
                        $floatvalue                 = floatval($pString);
                        if ($floatvalue < 0) {
                            $floatvalue = (-1) * $floatvalue;
                        }
                        $postaPO['TotalPurchaseAmt']  = $floatvalue;
                        $postaPO['Narration']         = $VOUCHER->NARRATION;
                        $postaPO['ARBLWarehouseName'] = $VOUCHER->PARTYLEDGERNAME;
                        // Table name:purchaseorder
                        $vouchertable1                = 'purchaseorder';
                        $qry                          = mysql_query("select PurchaseorderNo  from  purchaseorder where masterid='" . $masterid . "'");
                        $groCount                     = mysql_num_rows($qry);
                        if ($groCount == 0) {
                            $news->addNews($postaPO, $vouchertable1);
                        } else if ($groCount > 0) {
                            $wherecon = "masterid ='" . $masterid . "'";
                            $news->deleteNews($vouchertable1, $wherecon);
                            $news->addNews($postaPO, $vouchertable1);
                            $inventorytablePO = 'purchaseorder_details';
                            $wherecon2        = "masterid ='" . $masterid . "'";
                            $news->deleteNews($inventorytablePO, $wherecon2);
                            $taxtablePO = 'purchaseorderledger';
                            $wherecon1  = "masterid ='" . $masterid . "'";
                            $news->deleteNews($taxtablePO, $wherecon1);
                        }
                        //Tax  Ledger 
                        $j = 1;
                        $K = 1;
                        foreach ($VOUCHER->children() as $ALLLEDGERENTRIES) {
                            if ($vouchertype == 'Purchase Order' && $stkitemname = $ALLLEDGERENTRIES->LEDGERNAME != "" && $stkrate = $ALLLEDGERENTRIES->AMOUNT != "") {
                                $ledger = $ALLLEDGERENTRIES->LEDGERNAME;
                                ;
                                $post1aPO['Taxledger'] = $ALLLEDGERENTRIES->LEDGERNAME;
                                $pString               = str_replace(",", "", $ALLLEDGERENTRIES->AMOUNT);
                                $floatvalue            = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post1aPO['Taxamount']       = $floatvalue;
                                $post1aPO['Purchaseno']      = $SalesNo;
                                $post1aPO['PurchaseOrderno'] = $po;
                                $post1aPO['Purchasedate']    = $salesdate;
                                $post1aPO['franchisecode']   = $FRANCHISECODE;
                                $post1aPO['voucherstatus']   = $status;
                                $post1aPO['masterid']        = $masterid;
                                // Table Name:purchaseorderledger;
                                $taxtablePO                  = 'purchaseorderledger';
                                if ($K > 1) {
                                    if ($groCount == 0) {
                                        $news->addNews($post1aPO, $taxtablePO);
                                    } else if ($groCount > 0) {
                                        $news->addNews($post1aPO, $taxtablePO);
                                    }
                                }
                                $K++;
                            }
                        }
                        foreach ($VOUCHER->children() as $INVENTORYALLOCATIONS) {
                            if ($vouchertype == 'Purchase Order' && $stkitemname = $INVENTORYALLOCATIONS->STOCKITEMNAME != "" && $stkrate = $INVENTORYALLOCATIONS->RATE != "" && $PRODUCTCODE = $INVENTORYALLOCATIONS->PRODUCTCODE != "" && $AMOUNT = $INVENTORYALLOCATIONS->AMOUNT != "" && $billqty = $INVENTORYALLOCATIONS->BILLEDQTY != "") {
                                $inventorytablePO               = 'purchaseorder_details';
                                $post2aPO['ProductCode']        = $INVENTORYALLOCATIONS->PRODUCTCODE;
                                $pcode                          = $INVENTORYALLOCATIONS->PRODUCTCODE;
                                $post2aPO['ProductDescription'] = $INVENTORYALLOCATIONS->STOCKITEMNAME;
                                $post2aPO['Rate']               = $INVENTORYALLOCATIONS->RATE;
                                $pString                        = str_replace(",", "", $INVENTORYALLOCATIONS->AMOUNT);
                                $floatvalue                     = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post2aPO['Amount']         = $floatvalue;
                                $post2aPO['Quantity']       = $INVENTORYALLOCATIONS->BILLEDQTY;
                                $post2aPO['PurchaseNumber'] = $SalesNo;
                                $post2aPO['PurchaseoderNo'] = $po;
                                $post2aPO['franchisecode']  = $FRANCHISECODE;
                                $post2aPO['PurchaseDate']   = $salesdate;
                                $post2aPO['voucherstatus']  = $status;
                                $post2aPO['masterid']       = $masterid;
                                $inventorytablePO           = 'purchaseorder_details';
                                if ($groCount == 0) {
                                    $news->addNews($post2aPO, $inventorytablePO);
                                } else if ($groCount > 0) {
                                    $news->addNews($post2aPO, $inventorytablePO);
                                }
                                $j++;
                            }
                        }
                        $i++;
                        $count1 = $count1 . $responseid . "~";
                    } elseif ($vouchertype == 'Purchase') {
                        /* To compute the Bill reference numbers*/
                        $refno   = "";
                        $billref = "";
                        foreach ($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS) {
                            $billref = $BILLDETAILS->BILLNAME;
                            If ($billref != "") {
                                $refno .= $billref . "~";
                            }
                        }
                        $refno                      = substr($refno, 0, -1);
                        $SalesNo                    = $SalesNo . "-RP-" . $FRANCHISECODE;
                        $masterid                   = $dbuvalue;
                        $post_rp['franchisecode']   = $FRANCHISECODE;
                        $post_rp['Purchasedate']    = $salesdate;
                        $post_rp['referenceno']     = $refno;
                        $post_rp['VoucherType']     = $vouchertypename;
                        $post_rp['PurchaseNumber']  = $SalesNo;
                        $post_rp['PlantCode']       = $plant;
                        $post_rp['SupplierInvNo']   = $supplerinvno;
                        $post_rp['SupplierInvDate'] = $suppinvdate;
                        $post_rp['masterid']        = $masterid;
                        $po                         = $VOUCHER->REFERENCE;
                        if ($po == '') {
                            $po = '';
                        } else {
                            $po = $po . "-" . $FRANCHISECODE;
                        }
                        $post_rp['PO'] = $po;
                        $pString       = str_replace(",", "", $VOUCHER->VCHAMOUNT);
                        $floatvalue    = floatval($pString);
                        if ($floatvalue < 0) {
                            $floatvalue = (-1) * $floatvalue;
                        }
                        $post_rp['TotalPurchaseAmt']  = $floatvalue;
                        $post_rp['Narration']         = $VOUCHER->NARRATION;
                        $post_rp['ARBLWarehouseName'] = $VOUCHER->PARTYLEDGERNAME;
                        $post_rp['schemename']        = $schemetype;
                        $post_rp['voucherstatus']     = $status;
                        $vouchertable                 = 'purchase';
                        $qry                          = mysql_query("select PurchaseNumber  from  purchase where masterid='" . $masterid . "' ");
                        $groCount                     = mysql_num_rows($qry);
                        if ($groCount == 0) {
                            $news->addNews($post_rp, $vouchertable);
                        } else if ($groCount > 0) {
                            $wherecon1 = "masterid ='" . $masterid . "'";
                            $news->editNews($post_rp, $vouchertable, $wherecon1);
                            //To Delete the entries in r_purchasereport table
                            $wherecondel         = "unique_id ='" . $masterid . "'";
                            $updatepurchasetable = 'r_purchasereport';
                            $news->deleteNews($updatepurchasetable, $wherecondel);
                            $taxtable = 'purchaseledger';
                            $wherecon = "masterid ='" . $masterid . "'";
                            $news->deleteNews($taxtable, $wherecon);
                            $inventorytable = 'purchase_details';
                            $wherecon1      = "masterid ='" . $masterid . "'";
                            $news->deleteNews($inventorytable, $wherecon1);
                        }
                        $j = 1;
                        $K = 1;
                        foreach ($VOUCHER->children() as $ALLLEDGERENTRIES) {
                            if ($vouchertype == 'Purchase' && $stkitemname = $ALLLEDGERENTRIES->LEDGERNAME != "") {
                                $taxtable                 = 'purchaseledger';
                                $ledger                   = $ALLLEDGERENTRIES->LEDGERNAME;
                                $post1a['Taxledger']      = $ALLLEDGERENTRIES->LEDGERNAME;
                                $post1a['ledgertaxvalue'] = $ALLLEDGERENTRIES->LEDGERTAXVALUE;
                                $pString                  = str_replace(",", "", $ALLLEDGERENTRIES->AMOUNT);
                                $floatvalue               = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post1a['Taxamount']     = $floatvalue;
                                $post1a['Purchaseno']    = $SalesNo;
                                $post1a['masterid']      = $masterid;
                                $post1a['Purchasedate']  = $salesdate;
                                $post1a['franchisecode'] = $FRANCHISECODE;
                                $post1a['voucherstatus'] = $status;
                                if ($K > 1) {
                                    if ($groCount == 0) {
                                        $news->addNews($post1a, $taxtable);
                                    } else if ($groCount > 0) {
                                        $news->addNews($post1a, $taxtable);
                                    }
                                }
                                $K++;
                            }
                        }
                        foreach ($VOUCHER->children() as $INVENTORYALLOCATIONS) {
                            if ($vouchertype == 'Purchase' && $stkitemname = $INVENTORYALLOCATIONS->STOCKITEMNAME != "" && $PRODUCTCODE = $INVENTORYALLOCATIONS->PRODUCTCODE != "" && $billqty = $INVENTORYALLOCATIONS->BILLEDQTY != "") {
                                $inventorytable               = 'purchase_details';
                                $productcode                  = $INVENTORYALLOCATIONS->PRODUCTCODE;
                                $post2a['ProductCode']        = $INVENTORYALLOCATIONS->PRODUCTCODE;
                                $post2a['ProductDescription'] = $INVENTORYALLOCATIONS->STOCKITEMNAME;
                                $post2a['finvsno']            = $INVENTORYALLOCATIONS->FINVSNO;
                                $finvsno1                     = $INVENTORYALLOCATIONS->FINVSNO;
                                $post2a['Rate']               = $INVENTORYALLOCATIONS->RATE;
                                $pString                      = str_replace(",", "", $INVENTORYALLOCATIONS->AMOUNT);
                                $floatvalue                   = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $invamt             = $floatvalue;
                                $post2a['Amount']   = $floatvalue;
                                $post2a['Quantity'] = $INVENTORYALLOCATIONS->BILLEDQTY;
                                $pString            = str_replace(",", "", $INVENTORYALLOCATIONS->TAXVALUE);
                                $floatvalue         = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post2a['taxvalue']       = $floatvalue;
                                $post2a['PurchaseNumber'] = $SalesNo;
                                $post2a['masterid']       = $masterid;
                                $post2a['franchisecode']  = $FRANCHISECODE;
                                $post2a['voucherstatus']  = $status;
                                $post2a['PurchaseDate']   = $salesdate;
                                $invqty                   = $INVENTORYALLOCATIONS->BILLEDQTY;
                                $arrayassign              = array();
                                $basicuser                = $INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
                                $a;
                                if ($basicuser == 'ANY') {
                                    $a = '0';
                                } else {
                                    $arrayassign = explode(",", $basicuser);
                                    $a           = count($arrayassign) + 1;
                                    ;
                                }
								$pro_qry = mysql_query("SELECT pgm.distributorcode,p.ProductDescription, p.ProductGroupCode FROM productmaster p LEFT JOIN pgroupmapping pgm ON p.ProductGroupCode = pgm.productgroupcode WHERE p.ProductCode= '$productcode' AND pgm.PrimaryFranchise='$FRANCHISECODE' AND mapping='Yes'");
								while( $pro_record = mysql_fetch_array($pro_qry)){
									$sd_code=$pro_record['distributorcode'];
									$productdes=$pro_record['ProductDescription'];
									$pgroupname=$pro_record['ProductGroupCode'];
									}
								$post2a['sd_code']       = $sd_code;	
                                $news->addNews($post2a, $inventorytable);
                                $j++;
								$gross_amt = $invamt + $floatvalue;
								$inarguments="'$regionname','$branchname','$FRANCHISECODE','$sd_code','$franchisename','$SalesNo','$finvsno1','$refno','$salesdate','$supplerinvno','$suppinvdate','$plant','$po','$productcode','$productdes','$pgroupname','$vouchertypename','$invqty','$invamt','$floatvalue','$gross_amt','$masterid'";
								$insqry   = 'CALL r_insertreport("'.$inarguments.'","purchase")';
								$qry_exec = mysql_query($insqry) or DIE(mysql_error());
                            }
                        }
                        $i++;
                        $count5 = $count5 . $responseid . "~";
                    } elseif ($vouchertype == 'Debit Note') //Purchase Return
                        {
                        /* To compute the Bill reference numbers*/
                        $refno   = "";
                        $billref = "";
                        foreach ($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS) {
                            $billref = $BILLDETAILS->BILLNAME;
                            If ($billref != "") {
                                $refno .= $billref . "~";
                            }
                        }
                        $refno                         = substr($refno, 0, -1);
                        $SalesNo                       = $SalesNo . "-PR-" . $FRANCHISECODE;
                        $masterid                      = $dbuvalue;
                        $postb['franchisecode']        = $FRANCHISECODE;
                        $postb['Purchasereturndate']   = $salesdate;
                        $postb['VoucherType']          = $vouchertype;
                        $postb['PurchaseReturnNumber'] = $SalesNo;
                        $postb['referenceno']          = $refno;
                        $postb['masterid']             = $masterid;
                        $pString                       = str_replace(",", "", $VOUCHER->VCHAMOUNT);
                        $floatvalue                    = floatval($pString);
                        if ($floatvalue < 0) {
                            $floatvalue = (-1) * $floatvalue;
                        }
                        $postb['TotalPurchaseRetAmt'] = $floatvalue;
                        $postb['Narration']           = $VOUCHER->NARRATION;
                        $postb['ARBLWarehouseName']   = $VOUCHER->PARTYLEDGERNAME;
                        $postb['schemename']          = $schemetype;
                        $postb['voucherstatus']       = $status;
                        $postb['pd_code']       	= $VOUCHER->PRIMARYDCODE;
                        $pd_code                	= $VOUCHER->PRIMARYDCODE;
                        $vouchertable                 = 'purchasereturn';
                        $qry                          = mysql_query("select PurchaseReturnNumber  from  purchasereturn where masterid='" . $masterid . "' ");
                        $groCount                     = mysql_num_rows($qry);
                        if ($groCount == 0) {
                            $news->addNews($postb, $vouchertable);
                        } else if ($groCount > 0) {
                            $wherecon = "masterid ='" . $masterid . "'";
                            $news->editNews($postb, $vouchertable, $wherecon);
                            $taxtable  = 'purchasreturnledger';
                            $wherecon1 = "masterid ='" . $masterid . "'";
                            $news->deleteNews($taxtable, $wherecon1);
                            $inventorytable = 'purchasereturn_details';
                            $wherecon2      = "masterid ='" . $masterid . "' ";
                            $news->deleteNews($inventorytable, $wherecon2);
                            //To Delete the entries in r_salesreport table
                            $wherecondel   = "unique_id ='" . $masterid . "'";
                            $updateprtable = 'r_purchasereturn';
                            $news->deleteNews($updateprtable, $wherecondel);
                        }
                        $j  = 1;
                        $Ki = 1;
                        foreach ($VOUCHER->children() as $ALLLEDGERENTRIES) {
                            if ($vouchertype == 'Debit Note' && $stkitemname = $ALLLEDGERENTRIES->LEDGERNAME != "") {
                                $taxtable                 = 'purchasreturnledger';
                                $post1b['Taxledger']      = $ALLLEDGERENTRIES->LEDGERNAME;
                                $ledger                   = $ALLLEDGERENTRIES->LEDGERNAME;
                                $post1b['ledgertaxvalue'] = $ALLLEDGERENTRIES->LEDGERTAXVALUE;
                                $pString                  = str_replace(",", "", $ALLLEDGERENTRIES->AMOUNT);
                                $floatvalue               = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post1b['Taxamount']       = $floatvalue;
                                $post1b['PurchaseRetno']   = $SalesNo;
                                $post1b['masterid']        = $masterid;
                                $post1b['PurchaseRetdate'] = $salesdate;
                                $post1b['franchisecode']   = $FRANCHISECODE;
                                $post1b['voucherstatus']   = $status;
                                if ($Ki > 1) {
                                    $news->addNews($post1b, $taxtable);
                                }
                                $Ki++;
                            }
                        }
                        foreach ($VOUCHER->children() as $INVENTORYALLOCATIONS) {
                            if ($vouchertype == 'Debit Note' && $stkitemname = $INVENTORYALLOCATIONS->STOCKITEMNAME != "" && $PRODUCTCODE = $INVENTORYALLOCATIONS->PRODUCTCODE != "" && $billqty = $INVENTORYALLOCATIONS->BILLEDQTY != "") {
                                $inventorytable        = 'purchasereturn_details';
                                $post2b['ProductCode'] = $INVENTORYALLOCATIONS->PRODUCTCODE;
                                $pcode                 = $INVENTORYALLOCATIONS->PRODUCTCODE;
                                ;
                                $post2b['ProductDescription'] = $INVENTORYALLOCATIONS->STOCKITEMNAME;
                                $post2b['finvsno']            = $INVENTORYALLOCATIONS->FINVSNO;
                                $finvsno1                     = $INVENTORYALLOCATIONS->FINVSNO;
                                $post2b['Rate']               = $INVENTORYALLOCATIONS->RATE;
                                $pString                      = str_replace(",", "", $INVENTORYALLOCATIONS->AMOUNT);
                                $floatvalue                   = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post2b['Amount']   = $floatvalue;
                                $invamt             = $floatvalue;
                                $post2b['Quantity'] = $INVENTORYALLOCATIONS->BILLEDQTY;
                                $invqty             = $INVENTORYALLOCATIONS->BILLEDQTY;
                                $pString            = str_replace(",", "", $INVENTORYALLOCATIONS->TAXVALUE);
                                $floatvalue         = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post2b['taxvalue']          = $floatvalue;
                                $post2b['PurchaseRetNumber'] = $SalesNo;
                                $post2b['masterid']          = $masterid;
                                $post2b['franchisecode']     = $FRANCHISECODE;
                                $post2b['RetDate']           = $salesdate;
                                $post2b['voucherstatus']     = $status;
                                $basicuser                   = $INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
                                $a;
                                $basicuser;
                                if ($basicuser == '&#4; Any') {
                                    $a = '0';
                                } else if ($basicuser == 'ANY') {
                                    $a = '0';
                                } else {
                                    $arrayassign = explode(",", $basicuser);
                                    $a           = count($arrayassign);
                                }
								$pro_qry = mysql_query("SELECT pgm.distributorcode,p.ProductDescription, p.ProductGroupCode FROM productmaster p LEFT JOIN pgroupmapping pgm ON p.ProductGroupCode = pgm.productgroupcode WHERE p.ProductCode= '$productcode' AND pgm.PrimaryFranchise='$FRANCHISECODE' AND mapping='Yes'");
								while( $pro_record = mysql_fetch_array($pro_qry)){
									$sd_code=$pro_record['distributorcode'];
									$productdes=$pro_record['ProductDescription'];
									$pgroupname=$pro_record['ProductGroupCode'];
									}
								$post2b['sd_code']       = $sd_code;	
                                $news->addNews($post2b, $inventorytable);
                                $j++;
								$gross_amt = $invamt + $floatvalue;
								$inarguments="'$regionname','$branchname','$FRANCHISECODE','$sd_code','$franchisename','$SalesNo','$finvsno1','$refno','$salesdate','$productcode','$pgroupname','$vouchertype','$invqty','$invamt','$floatvalue','$gross_amt','$masterid'";
								$insqry   = 'CALL r_insertreport("'.$inarguments.'","purchasereturn")';
								$qry_exec = mysql_query($insqry) or DIE(mysql_error());
                            }
                        }
                        $i++;
                        $count8 = $count8 . $responseid . "~";
                    } elseif ($vouchertype == 'Credit Note') //Sales Return
                        {
                        $SalesNo  = $SalesNo . "-SR-" . $FRANCHISECODE;
                        $masterid = $dbuvalue;
                        $refno    = "";
                        $billref  = "";
                        $pd_code       = "";
                        $location      = "";
                        $tertiary_code = "";
                        foreach ($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS) {
                            $billref = $BILLDETAILS->BILLNAME;
                            If ($billref != "") {
                                $refno .= $billref . "~";
                            }
                        }
                        $refno                  = substr($refno, 0, -1);
                        $postc['franchisecode'] = $FRANCHISECODE;
                        $postc['salesRetdate']  = $salesdate;
                        $postc['VoucherType']   = $vouchertype;
                        $postc['salesRetno']    = $SalesNo;
                        $postc['referenceno']   = $refno;
                        $postc['masterid']      = $masterid;
                        $postc['retailername']  = $VOUCHER->PARTYLEDGERNAME;
                        $postc['pd_code']       = $VOUCHER->PRIMARYDCODE;
                        $postc['location']      = $VOUCHER->LOCATION;
                        $postc['tertiary_code'] = $VOUCHER->TERTIARYCODE;
                        $retailername           = $VOUCHER->PARTYLEDGERNAME;
                        $location               = $VOUCHER->LOCATION;
                        $tertiary_code          = $VOUCHER->TERTIARYCODE;
                        $pString                = str_replace(",", "", $VOUCHER->VCHAMOUNT);
                        $floatvalue             = floatval($pString);
                        if ($floatvalue < 0) {
                            $floatvalue = (-1) * $floatvalue;
                        }
                        $postc['Rettotalamount'] = $floatvalue;
                        $postc['narration']      = $VOUCHER->NARRATION;
                        $postc['franchisecode']  = $FRANCHISECODE;
                        $postc['schemename']     = $schemetype;
                        $postc['voucherstatus']  = $status;
                        $vouchertable            = 'salesreturn';
                        $qry                     = mysql_query("select salesRetno  from  salesreturn where masterid='" . $masterid . "' ");
                        $groCount                = mysql_num_rows($qry);
                        if ($groCount == 0) {
                            $news->addNews($postc, $vouchertable);
                        } else if ($groCount > 0) {
                            $wherecon = "masterid ='" . $masterid . "'";
                            $news->editNews($postc, $vouchertable, $wherecon);
                            $taxtable  = 'salesledgerreturn';
                            $wherecon1 = "masterid ='" . $masterid . "' ";
                            $news->deleteNews($taxtable, $wherecon1);
                            $inventorytable = 'salesreturnitem';
                            $wherecon2      = "masterid ='" . $masterid . "' ";
                            $news->deleteNews($inventorytable, $wherecon2);
                            //To Delete the entries in r_salesreturnreport table
                            $wherecondel      = "unique_id ='" . $masterid . "'";
                            $updatesalestable = 'r_salesreturn';
                            $news->deleteNews($updatesalestable, $wherecondel);
                        }
                        $j = 1;
                        $K = 1;
                        foreach ($VOUCHER->children() as $ALLLEDGERENTRIES) {
                            if ($vouchertype == 'Credit Note' && $stkitemname = $ALLLEDGERENTRIES->LEDGERNAME != "") {
                                $taxtable                 = 'salesledgerreturn';
                                $post1c['taxledger']      = $ALLLEDGERENTRIES->LEDGERNAME;
                                $ledger                   = $ALLLEDGERENTRIES->LEDGERNAME;
                                $post1c['ledgertaxvalue'] = $ALLLEDGERENTRIES->LEDGERTAXVALUE;
                                $pString                  = str_replace(",", "", $ALLLEDGERENTRIES->AMOUNT);
                                $floatvalue               = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post1c['taxamount']     = $floatvalue;
                                $post1c['SalesRetNo']    = $SalesNo;
                                $post1c['masterid']      = $masterid;
                                $post1c['salesRetdate']  = $salesdate;
                                $post1c['franchisecode'] = $FRANCHISECODE;
                                $post1c['voucherstatus'] = $status;
                                if ($K > 1) {
                                    if ($groCount == 0) {
                                        $news->addNews($post1c, $taxtable);
                                    } else if ($groCount > 0) {
                                        $news->addNews($post1c, $taxtable);
                                        //echo "Duplicate entry in Sales Ledger return  -  ".$SalesNo;
                                    }
                                }
                                $K++;
                            }
                        }
                        foreach ($VOUCHER->children() as $INVENTORYALLOCATIONS) {
                            if ($vouchertype == 'Credit Note' && $stkitemname = $INVENTORYALLOCATIONS->STOCKITEMNAME != "" && $PRODUCTCODE = $INVENTORYALLOCATIONS->PRODUCTCODE != "" && $billqty = $INVENTORYALLOCATIONS->BILLEDQTY != "") {
                                $inventorytable        = 'salesreturnitem';
                                $productcode           = $INVENTORYALLOCATIONS->PRODUCTCODE;
                                $post2c['productcode'] = $INVENTORYALLOCATIONS->PRODUCTCODE;
                                $post2c['productdes']  = $INVENTORYALLOCATIONS->STOCKITEMNAME;
                                $post2c['rate']        = $INVENTORYALLOCATIONS->RATE;
                                $pString               = str_replace(",", "", $INVENTORYALLOCATIONS->AMOUNT);
                                $floatvalue            = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $invamt             = $floatvalue;
                                $post2c['amount']   = $floatvalue;
                                $post2c['quantity'] = $INVENTORYALLOCATIONS->BILLEDQTY;
                                $invqty             = $INVENTORYALLOCATIONS->BILLEDQTY;
                                $pString            = str_replace(",", "", $INVENTORYALLOCATIONS->TAXVALUE);
                                $floatvalue         = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post2c['taxvalue']      = $floatvalue;
                                $post2c['saleRetsno']    = $SalesNo;
                                $post2c['masterid']      = $masterid;
                                $post2c['salesRetdate']  = $salesdate;
                                $post2c['voucherstatus'] = $status;
                                $post2c['franchisecode'] = $FRANCHISECODE;
                                $arrayassign             = array();
                                $basicuser               = $INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
                                $arrayassign             = explode(",", $basicuser);
                                $a                       = count($arrayassign);
								$pro_qry = mysql_query("SELECT pgm.distributorcode,p.ProductDescription, p.ProductGroupCode FROM productmaster p LEFT JOIN pgroupmapping pgm ON p.ProductGroupCode = pgm.productgroupcode WHERE p.ProductCode= '$productcode' AND pgm.PrimaryFranchise='$FRANCHISECODE' AND mapping='Yes'");
								while( $pro_record = mysql_fetch_array($pro_qry)){
									$sd_code=$pro_record['distributorcode'];
									$productdes=$pro_record['ProductDescription'];
									$pgroupname=$pro_record['ProductGroupCode'];
									}
								$post2c['sd_code']       = $sd_code;	
                                $news->addNews($post2c, $inventorytable);
                                $j++;
								$gross_amt = $invamt + $floatvalue;
								$inarguments="'$regionname','$branchname','$FRANCHISECODE','$sd_code','$franchisename','$SalesNo','$refno','$salesdate','$retailername','$location','$tertiary_code','$productcode','$productdes','$pgroupname','$vouchertype','$invqty','$invamt','$floatvalue','$gross_amt','$masterid'";
								$insqry   = 'CALL r_insertreport("'.$inarguments.'","salesreturn")';
								$insqry   = 'CALL r_insertsalesreturnreport("'.$inarguments.'")';
								$qry_exec = mysql_query($insqry) or DIE(mysql_error());
                            }
                        }
                        $i++;
                        $count9 = $count9 . $responseid . "~";
                    }
// Sales Voucher type
					elseif ($vouchertype == 'Sales') {
                        $refno         = "";
                        $billref       = "";
                        $sd_code       = "";
                        $location      = "";
                        $tertiary_code = "";
                        foreach ($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS) {
                            $billref = $BILLDETAILS->BILLNAME;
                            If ($billref != "") {
                                $refno .= $billref . "~";
                            }
                        }
                        $refno                  = substr($refno, 0, -1);
                        $SalesNo                = $SalesNo . "-RS-" . $FRANCHISECODE;
                        $masterid               = $dbuvalue;
                        $postd['salesdate']     = $salesdate;
                        $postd['VoucherType']   = $vouchertypename;
                        $postd['salesno']       = $SalesNo;
                        $postd['referenceno']   = $refno;
                        $postd['masterid']      = $masterid;
                        $postd['retailername']  = $VOUCHER->PARTYLEDGERNAME;
                        $postd['location']      = $VOUCHER->LOCATION;
                        $postd['tertiary_code'] = $VOUCHER->TERTIARYCODE;
                        $retailername  = $VOUCHER->PARTYLEDGERNAME;
                        $location      = $VOUCHER->LOCATION;
                        $tertiary_code = $VOUCHER->TERTIARYCODE;
                        $pString                = str_replace(",", "", $VOUCHER->VCHAMOUNT);
                        $floatvalue             = floatval($pString);
                        if ($floatvalue < 0) {
                            $floatvalue = (-1) * $floatvalue;
                        }
                        $postd['totalamount']   = $floatvalue;
                        $postd['narration']     = $VOUCHER->NARRATION;
                        $postd['franchisecode'] = $FRANCHISECODE;
                        $postd['schemename']    = $schemetype;
                        $postd['voucherstatus'] = $status;
                        $postd['pricelevel']    = $VOUCHER->PRICELEVEL;
                        $pricelevel    = $VOUCHER->PRICELEVEL;
                        $vouchertable           = 'retailersales';
                        $qry                    = mysql_query("select salesno  from  retailersales where masterid='" . $masterid . "'");
                        $groCount               = mysql_num_rows($qry);
                        if ($groCount == 0) {
                            $news->addNews($postd, $vouchertable);
                        } else if ($groCount > 0) {
                            $wherecon = "masterid ='" . $masterid . "'";
                            $news->editNews($postd, $vouchertable, $wherecon);
                            //To Delete the entries in r_salesreport table
                            $wherecondel      = "unique_id ='" . $masterid . "'";
                            $updatesalestable = 'r_salesreport';
                            $news->deleteNews($updatesalestable, $wherecondel);
                            $inventorytable = 'retailersalesitem';
                            $wherecon2      = "masterid ='" . $masterid . "'";
                            $news->deleteNews($inventorytable, $wherecon2);
                            $taxtable  = 'retailersalesledger';
                            $wherecon1 = "masterid ='" . $masterid . "' ";
                            $news->deleteNews($taxtable, $wherecon1);
                        }
                        $j = 1;
                        $K = 1;
                        foreach ($VOUCHER->children() as $ALLLEDGERENTRIES) {
                            if ($vouchertype == 'Sales' && $stkitemname = $ALLLEDGERENTRIES->LEDGERNAME != "") {
                                $taxtable            = 'retailersalesledger';
                                $post1d['taxledger'] = $ALLLEDGERENTRIES->LEDGERNAME;
                                $ledger              = $ALLLEDGERENTRIES->LEDGERNAME;
                                ;
                                $post1d['ledgertaxvalue'] = $ALLLEDGERENTRIES->LEDGERTAXVALUE;
                                $pString                  = str_replace(",", "", $ALLLEDGERENTRIES->AMOUNT);
                                $floatvalue               = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post1d['taxamount']     = $floatvalue;
                                $post1d['SalesNo']       = $SalesNo;
                                $post1d['masterid']      = $masterid;
                                $post1d['salesdates']    = $salesdate;
                                $post1d['franchisecode'] = $FRANCHISECODE;
                                $post1d['voucherstatus'] = $status;
                                if ($K > 1) {
                                    $news->addNews($post1d, $taxtable);
                                }
                                $K++;
                            }
                        }
                        foreach ($VOUCHER->children() as $INVENTORYALLOCATIONS) {
                            if ($vouchertype == 'Sales' && $stkitemname = $INVENTORYALLOCATIONS->STOCKITEMNAME != "" && $PRODUCTCODE = $INVENTORYALLOCATIONS->PRODUCTCODE != "" && $billqty = $INVENTORYALLOCATIONS->BILLEDQTY != "") {
                                $inventorytable        = 'retailersalesitem';
                                $productcode           = $INVENTORYALLOCATIONS->PRODUCTCODE;
                                $post2d['productcode'] = $INVENTORYALLOCATIONS->PRODUCTCODE;
                                $post2d['productdes']  = $INVENTORYALLOCATIONS->STOCKITEMNAME;
                                $post2d['rate']        = $INVENTORYALLOCATIONS->RATE;
                                $pString               = str_replace(",", "", $INVENTORYALLOCATIONS->AMOUNT);
                                $floatvalue            = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post2d['amount']   = $floatvalue;
                                $post2d['quantity'] = $INVENTORYALLOCATIONS->BILLEDQTY;
                                $invamt             = $floatvalue;
                                $invqty             = $INVENTORYALLOCATIONS->BILLEDQTY;
                                $pString            = str_replace(",", "", $INVENTORYALLOCATIONS->TAXVALUE);
                                $floatvalue         = floatval($pString);
                                if ($floatvalue < 0) {
                                    $floatvalue = (-1) * $floatvalue;
                                }
                                $post2d['taxvalue']      = $floatvalue;
                                $post2d['salesno']       = $SalesNo;
                                $post2d['masterid']      = $masterid;
                                $post2d['salesdates']    = $salesdate;
                                $post2d['voucherstatus'] = $status;
                                $post2d['franchisecode'] = $FRANCHISECODE;
								$pro_qry = mysql_query("SELECT pgm.distributorcode,p.ProductDescription, p.ProductGroupCode FROM productmaster p LEFT JOIN pgroupmapping pgm ON p.ProductGroupCode = pgm.productgroupcode WHERE p.ProductCode= '$productcode' AND pgm.PrimaryFranchise='$FRANCHISECODE' AND mapping='Yes'");
								while( $pro_record = mysql_fetch_array($pro_qry)){
									$sd_code=$pro_record['distributorcode'];
									$productdes=$pro_record['ProductDescription'];
									$pgroupname=$pro_record['ProductGroupCode'];
									}
								$post2d['sd_code']       = $sd_code;								
                                $news->addNews($post2d, $inventorytable);
                                $j++;
								$gross_amt = $invamt + $floatvalue;
								$inarguments="'$regionname','$branchname','$FRANCHISECODE','$sd_code','$franchisename','$SalesNo','$refno','$salesdate','$retailername','$location','$tertiary_code','$productcode','$productdes','$pgroupname','$vouchertypename','$invqty','$invamt','$floatvalue','$gross_amt','$status','$pricelevel','$masterid'";
								$insqry   = 'CALL r_insertreport("'.$inarguments.'","sales")';
								$qry_exec = mysql_query($insqry) or DIE(mysql_error());
                            }
                        }
                        $i++;
                        $count10 = $count10 . $responseid . "~";
                    } 
                }
            }
        }
    }
}
$count                  = $count1 . $count2 . $count3 . $count4 . $count5 . $count6 . $count7 . $count8 . $count9 . $count10 . $count11 . $count12 . $count13 . $count14 . $count15 . $count16 . $count17 . $count18 . $count19 . $count;
$tax                    = 'uploadstatus';
$post1['franchisecode'] = $FRANCHISECODE;
$post1['date']          = date("Y-m-d");
$post1['status']        = 'Delivered';
$news->addNews($post1, $tax);
{
    print("<ENVELOPE>");
    print("<HEADER>");
    print("<VERSION>1</VERSION>");
    print("<STATUS>1</STATUS>");
    print("</HEADER>");
    print("<BODY>");
    print("<DATA>");
    print("<VOUCHERRESPONSES>");
    print("<MASTERIDS>" . $count . "</MASTERIDS> ");
    print("</VOUCHERRESPONSES>");
    print("</DATA>");
    print("</BODY>");
    print("</ENVELOPE>");
}
?>
