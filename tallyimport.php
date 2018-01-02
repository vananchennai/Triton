<?php
session_start();
/* Accessing DB input - Start */
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
$news = new News();
/* Accessing DB input - End */

 if (isset($HTTP_RAW_POST_DATA)) 
 {
/* If(1)  
 {
$getData="<ENVELOPE>
 <FRANCHISECODE>000006</FRANCHISECODE>
 <REQUEST>
  <VOUCHER>
   <VOUCHERUPLOADSTATE>NEW</VOUCHERUPLOADSTATE>
   <VOUCHERACTIVE>Active</VOUCHERACTIVE>
   <VOUCHERSTATUS>1</VOUCHERSTATUS>
   <MASTERID>4</MASTERID>
   <GUID>a1a82702-6ac3-469e-959d-c262f9ad0550-00000004</GUID>
   <VOUCHERTYPENAME>Credit Note</VOUCHERTYPENAME>
   <XMLPOSTREQREPSALESUPLOADSUPPLIERINVNO></XMLPOSTREQREPSALESUPLOADSUPPLIERINVNO>
   <XMLPOSTREQREPSALESUPLOADSUPPLERINVDATE></XMLPOSTREQREPSALESUPLOADSUPPLERINVDATE>
   <XMLPOSTREQREPSALESUPLOADPLANT></XMLPOSTREQREPSALESUPLOADPLANT>
   <VOUCHERPARENT>Credit Note</VOUCHERPARENT>
   <XMLPOSTREQREPPRICENAME></XMLPOSTREQREPPRICENAME>
   <DATE>1-Apr-2015</DATE>
   <VOUCHERNUMBER>1</VOUCHERNUMBER>
   <REFERENCE></REFERENCE>
   <PARTYLEDGERNAME>ADAMPUR SHIV MOTOR STORE</PARTYLEDGERNAME>
   <LOCATION>Hisar District</LOCATION>
   <TERTIARYCODE>120266_DSR01</TERTIARYCODE>
   <VCHAMOUNT>3,000.00</VCHAMOUNT>
   <NARRATION></NARRATION>
   <ALLLEDGERENTRIES>
    <LEDGERNAME>ADAMPUR SHIV MOTOR STORE</LEDGERNAME>
    <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
    <AMOUNT>3000.00</AMOUNT>
    <LEDGERTAXVALUE>0</LEDGERTAXVALUE>
    <BILLDETAILS>
     <BILLNAME>4</BILLNAME>
     <AMOUNTNAME>3,000.00</AMOUNTNAME>
    </BILLDETAILS>
   </ALLLEDGERENTRIES>
   <INVENTORY>
    <SECONDARYDISTRIBUTOR></SECONDARYDISTRIBUTOR>
    <PRODUCTCODE>A3003-FE240</PRODUCTCODE>
    <STOCKITEMNAME>FE240</STOCKITEMNAME>
    <RATE>1000.00</RATE>
    <AMOUNT>-3000.00</AMOUNT>
    <ACTUALQTY>3</ACTUALQTY>
    <BILLEDQTY>3</BILLEDQTY>
    <FINVSNO></FINVSNO>
    <TAXVALUE></TAXVALUE>
    <DISCOUNT></DISCOUNT>
   </INVENTORY>
  </VOUCHER>
 </REQUEST>
</ENVELOPE>";
    $xml_object = simplexml_load_string($getData);*/
    $xml_object = simplexml_load_string($HTTP_RAW_POST_DATA);
    if ($xml_object != "") {
        $groCount = 0;
        $materid  = $oldserialnumber = $regionname = $branchname = $franchisename = $fraCount = $dsrCount=NULL;
        $count    = $count1 = $count2 = $count3 = $count4 = $dbuvalue = NULL;
        $pd_code  = $xml_object->FRANCHISECODE;
        foreach ($xml_object->REQUEST->children() as $VOUCHER) {
            $dbuvalue      = $VOUCHER->GUID;
            $voucherno     = $VOUCHER->VOUCHERNUMBER;
            $voucherdate   = date('Y-m-d', strtotime($VOUCHER->DATE));
            $voucherparent = $VOUCHER->VOUCHERPARENT;
            $vouchertype   = $VOUCHER->VOUCHERTYPENAME;
            $supplerinvno  = $VOUCHER->SUPPLIERIVOICENUMBER;
            $suppinvdate   = date('Y-m-d', strtotime($VOUCHER->SUPPLIERIVOICEDATE));
            $plant         = $VOUCHER->PLANT;
            $status        = 'ACTIVE';
            $responseid    = $VOUCHER->MASTERID;
            $schemetype    = $VOUCHER->SCHEMETYPE;
            $fraqry        = mysql_query("select Region, Branch,Franchisename,Franchisecode from  franchisemaster where  PrimaryFranchise='" . $pd_code . "'");
            while ($record = mysql_fetch_array($fraqry)) {
                $regionname    = $record['Region'];
                $branchname    = $record['Branch'];
                $franchisename = $record['Franchisename'];
                $fraCount      = 1;
				$franhisedsr   = $record['Franchisecode'];
            }
            if ($fraCount == 0) {
                echo "Franchisee Not Available in tally central server";
            } 
			else {
				/*DSR code Fetching*/
				echo $RName=$VOUCHER->PARTYLEDGERNAME;
				echo $franhisedsr;
					$fraqry        = mysql_query("select dsrcode, dsrlocation,dsrname from  retailerdsrmapping where  Franchisecode='" . $franhisedsr . "' and  retailername='" . $RName . "'");
					
					while ($record = mysql_fetch_array($fraqry)) 
					{
					$dsrcode    = $record['dsrcode'];
					$dsrlocation= $record['dsrlocation'];
					$dsrname    = $record['dsrname'];
					$dsrCount      = 1;
					}
					if ($dsrCount == 0) 
					{
					  $dsrretailertable="retailerdsrmapping";
					  $post_dsr['retailername']=$RName;
					  $post_dsr['Franchisecode']=$franhisedsr;
					  $news->addNews($post_dsr, $dsrretailertable);
					}
		
									
                if ($voucherparent == "") {
                    echo "Voucher type  Not Available in tally tag";
                } 
				
				
				
								
				/* Purchase Voucher - Start */

				elseif ($voucherparent == 'Purchase') {
                    $refno = $billref = NULL;
                    foreach ($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS) {
                        $billref = $BILLDETAILS->BILLNAME;
                        If ($billref != "") {
                            $refno .= $billref . "~";
                        }
                    }
                    $refno                      = substr($refno, 0, -1);
                    $voucherno                  = $voucherno . "-RP-" . $pd_code;
                    $post_rp['pd_code']         = $pd_code;
                    $post_rp['Purchasedate']    = $voucherdate;
                    $post_rp['referenceno']     = $refno;
                    $post_rp['VoucherType']     = $vouchertype;
                    $post_rp['PurchaseNumber']  = $voucherno;
                    $post_rp['PlantCode']       = $plant;
                    $post_rp['SupplierInvNo']   = $supplerinvno;
                    $post_rp['SupplierInvDate'] = $suppinvdate;
                    $post_rp['masterid']        = $dbuvalue;
                    $po                         = $VOUCHER->REFERENCE;
                    if ($po == '') {
                        $po = '';
                    } else {
                        $po = $po . "-" . $pd_code;
                    }
                    $post_rp['PO'] = $po;
                    $vch_amt       = floatval(str_replace(",", "", $VOUCHER->VCHAMOUNT));
                    if ($vch_amt < 0) {
                        $vch_amt = (-1) * $vch_amt;
                    }
                    $post_rp['TotalPurchaseAmt']  = $vch_amt;
                    $post_rp['Narration']         = $VOUCHER->NARRATION;
                    $post_rp['ARBLWarehouseName'] = $VOUCHER->PARTYLEDGERNAME;
                    $post_rp['schemename']        = $schemetype;
                    $post_rp['voucherstatus']     = $status;
                    $vouchertable                 = 'purchase';
                    $qry                          = mysql_query("select PurchaseNumber  from  purchase where masterid='" . $dbuvalue . "' ");
                    $groCount                     = mysql_num_rows($qry);
                    if ($groCount == 0) {
                        $news->addNews($post_rp, $vouchertable);
                    } else if ($groCount > 0) {
                        $wherecon1 = "masterid ='" . $dbuvalue . "'";
                        $news->editNews($post_rp, $vouchertable, $wherecon1);
                        $wherecondel         = "unique_id ='" . $dbuvalue . "'";
                        $updatepurchasetable = 'r_purchasereport';
                        $news->deleteNews($updatepurchasetable, $wherecondel);
                        $taxtable = 'purchaseledger';
                        $wherecon = "masterid ='" . $dbuvalue . "'";
                        $news->deleteNews($taxtable, $wherecon);
                        $inventorytable = 'purchase_details';
                        $wherecon1      = "masterid ='" . $dbuvalue . "'";
                        $news->deleteNews($inventorytable, $wherecon1);
                    }
                    $K = 1;
                    foreach ($VOUCHER->children() as $ALLLEDGERENTRIES) {
                        if ($voucherparent == 'Purchase' && $stkitemname = $ALLLEDGERENTRIES->LEDGERNAME != "") {
                            $taxtable                 = 'purchaseledger';
                            $post1a['Taxledger']      = $ALLLEDGERENTRIES->LEDGERNAME;
                            $post1a['ledgertaxvalue'] = $ALLLEDGERENTRIES->LEDGERTAXVALUE;
                            $tax_amt                  = floatval(str_replace(",", "", $ALLLEDGERENTRIES->AMOUNT));
                            if ($tax_amt < 0) {
                                $tax_amt = (-1) * $tax_amt;
                            }
                            $post1a['Taxamount']     = $tax_amt;
                            $post1a['Purchaseno']    = $voucherno;
                            $post1a['masterid']      = $dbuvalue;
                            $post1a['Purchasedate']  = $voucherdate;
                            $post1a['pd_code']       = $pd_code;
                            $post1a['voucherstatus'] = $status;
                            if ($K > 1) {
                                $news->addNews($post1a, $taxtable);
                            }
                            $K++;
                        }
                    }
                    foreach ($VOUCHER->children() as $INVENTORYALLOCATIONS) {
                        if ($voucherparent == 'Purchase' && $stkitemname = $INVENTORYALLOCATIONS->STOCKITEMNAME != "" && $PRODUCTCODE = $INVENTORYALLOCATIONS->PRODUCTCODE != "" && $billqty = $INVENTORYALLOCATIONS->BILLEDQTY != "") {
                            $inventorytable               = 'purchase_details';
                            $productcode                  = $INVENTORYALLOCATIONS->PRODUCTCODE;
                            $finvsno                      = $INVENTORYALLOCATIONS->FINVSNO;
                            $post2a['ProductCode']        = $productcode;
                            $post2a['ProductDescription'] = $INVENTORYALLOCATIONS->STOCKITEMNAME;
                            $post2a['finvsno']            = $finvsno;
                            $post2a['Rate']               = $INVENTORYALLOCATIONS->RATE;
                            $vch_amt                      = floatval(str_replace(",", "", $INVENTORYALLOCATIONS->AMOUNT));
                            if ($vch_amt < 0) {
                                $vch_amt = (-1) * $vch_amt;
                            }
                            $post2a['Amount']   = $vch_amt;
                            $inv_qty            = $INVENTORYALLOCATIONS->BILLEDQTY;
                            $post2a['Quantity'] = $inv_qty;
                            $tax_amt            = floatval(str_replace(",", "", $INVENTORYALLOCATIONS->TAXVALUE));
                            if ($tax_amt < 0) {
                                $tax_amt = (-1) * $tax_amt;
                            }
                            $post2a['taxvalue']       = $tax_amt;
                            $post2a['PurchaseNumber'] = $voucherno;
                            $post2a['masterid']       = $dbuvalue;
                            $post2a['pd_code']        = $pd_code;
                            $post2a['voucherstatus']  = $status;
                            $post2a['PurchaseDate']   = $voucherdate;
                            $arrayassign              = array();
                            $basicuser                = $INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
                            $a;
                            if ($basicuser == 'ANY') {
                                $a = '0';
                            } else {
                                $arrayassign = explode(",", $basicuser);
                                $a           = count($arrayassign) + 1;
                            }
                            $pro_qry = mysql_query("SELECT pgm.distributorcode,p.ProductDescription, p.ProductGroupCode FROM productmaster p LEFT JOIN pgroupmapping pgm ON p.ProductGroupCode = pgm.productgroupcode WHERE p.ProductCode= '$productcode' AND pgm.PrimaryFranchise='$pd_code' AND mapping='Yes'");
                            while ($pro_record = mysql_fetch_array($pro_qry)) {
                                $sd_code    = $pro_record['distributorcode'];
                                $productdes = $pro_record['ProductDescription'];
                                $pgroupname = $pro_record['ProductGroupCode'];
                            }
                            $post2a['franchisecode'] = $sd_code;
                            $news->addNews($post2a, $inventorytable);
                            $j++;
                            $gross_amt   = $vch_amt + $tax_amt;
                            $inarguments = "'$regionname','$branchname','$pd_code','$sd_code','$franchisename','$voucherno','$finvsno','$refno','$voucherdate','$supplerinvno','$suppinvdate','$plant','$po','$productcode','$productdes','$pgroupname','$vouchertype','$inv_qty','$vch_amt','$tax_amt','$gross_amt','$dbuvalue'";
                            $insqry      = 'CALL r_insertreport("' . $inarguments . '","purchase")';
                            $qry_exec = mysql_query($insqry) or DIE(mysql_error());
                        }
                    }
                    $count1 = $count1 . $responseid . "~";
                } 
				/* Purchase Voucher - End */
				/* Purchase Return Voucher - Start */
				elseif ($voucherparent == 'Debit Note') {
                    $refno = $billref = NULL;
                    foreach ($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS) {
                        $billref = $BILLDETAILS->BILLNAME;
                        If ($billref != "") {
                            $refno .= $billref . "~";
                        }
                    }
                    $refno                         = substr($refno, 0, -1);
                    $voucherno                     = $voucherno . "-PR-" . $pd_code;
                    $postb['pd_code']              = $pd_code;
                    $postb['Purchasereturndate']   = $voucherdate;
                    $postb['VoucherType']          = $vouchertype;
                    $postb['PurchaseReturnNumber'] = $voucherno;
                    $postb['referenceno']          = $refno;
                    $postb['masterid']             = $dbuvalue;
                    $vch_amt                       = floatval(str_replace(",", "", $VOUCHER->VCHAMOUNT));
                    if ($vch_amt < 0) {
                        $vch_amt = (-1) * $vch_amt;
                    }
                    $postb['TotalPurchaseRetAmt'] = $vch_amt;
                    $postb['Narration']           = $VOUCHER->NARRATION;
                    $postb['ARBLWarehouseName']   = $VOUCHER->PARTYLEDGERNAME;
                    $postb['schemename']          = $schemetype;
                    $postb['voucherstatus']       = $status;
                    //$pd_code                      = $VOUCHER->PRIMARYDCODE;
                    $postb['pd_code']             = $pd_code;
                    $vouchertable                 = 'purchasereturn';
                    $qry                          = mysql_query("select PurchaseReturnNumber  from  purchasereturn where masterid='" . $dbuvalue . "' ");
                    $groCount                     = mysql_num_rows($qry);
                    if ($groCount == 0) {
                        $news->addNews($postb, $vouchertable);
                    } else if ($groCount > 0) {
                        $wherecon = "masterid ='" . $dbuvalue . "'";
                        $news->editNews($postb, $vouchertable, $wherecon);
                        $taxtable  = 'purchasreturnledger';
                        $wherecon1 = "masterid ='" . $dbuvalue . "'";
                        $news->deleteNews($taxtable, $wherecon1);
                        $inventorytable = 'purchasereturn_details';
                        $wherecon2      = "masterid ='" . $dbuvalue . "' ";
                        $news->deleteNews($inventorytable, $wherecon2);
                        $wherecondel   = "unique_id ='" . $dbuvalue . "'";
                        $updateprtable = 'r_purchasereturn';
                        $news->deleteNews($updateprtable, $wherecondel);
                    }
                    $Ki = 1;
                    foreach ($VOUCHER->children() as $ALLLEDGERENTRIES) {
                        if ($voucherparent == 'Debit Note' && $stkitemname = $ALLLEDGERENTRIES->LEDGERNAME != "") {
                            $taxtable                 = 'purchasreturnledger';
                            $ledger                   = $ALLLEDGERENTRIES->LEDGERNAME;
                            $post1b['Taxledger']      = $ledger;
                            $post1b['ledgertaxvalue'] = $ALLLEDGERENTRIES->LEDGERTAXVALUE;
                            $tax_amt                  = floatval(str_replace(",", "", $ALLLEDGERENTRIES->AMOUNT));
                            if ($tax_amt < 0) {
                                $tax_amt = (-1) * $tax_amt;
                            }
                            $post1b['Taxamount']       = $tax_amt;
                            $post1b['PurchaseRetno']   = $voucherno;
                            $post1b['masterid']        = $dbuvalue;
                            $post1b['PurchaseRetdate'] = $voucherdate;
                            $post1b['pd_code']         = $pd_code;
                            $post1b['voucherstatus']   = $status;
                            if ($Ki > 1) {
                                $news->addNews($post1b, $taxtable);
                            }
                            $Ki++;
                        }
                    }
                    foreach ($VOUCHER->children() as $INVENTORYALLOCATIONS) {
                        if ($voucherparent == 'Debit Note' && $stkitemname = $INVENTORYALLOCATIONS->STOCKITEMNAME != "" && $PRODUCTCODE = $INVENTORYALLOCATIONS->PRODUCTCODE != "" && $billqty = $INVENTORYALLOCATIONS->BILLEDQTY != "") {
                            $inventorytable               = 'purchasereturn_details';
                            $productcode                  = $INVENTORYALLOCATIONS->PRODUCTCODE;
                            $post2b['ProductCode']        = $productcode;
                            $post2b['ProductDescription'] = $INVENTORYALLOCATIONS->STOCKITEMNAME;
                            $finvsno                      = $INVENTORYALLOCATIONS->FINVSNO;
                            $post2b['finvsno']            = $finvsno;
                            $post2b['Rate']               = $INVENTORYALLOCATIONS->RATE;
                            $vch_amt                      = floatval(str_replace(",", "", $INVENTORYALLOCATIONS->AMOUNT));
                            if ($vch_amt < 0) {
                                $vch_amt = (-1) * $vch_amt;
                            }
                            $post2b['Amount']   = $vch_amt;
                            $inv_qty            = $INVENTORYALLOCATIONS->BILLEDQTY;
                            $post2b['Quantity'] = $inv_qty;
                            $tax_amt            = floatval(str_replace(",", "", $INVENTORYALLOCATIONS->TAXVALUE));
                            if ($tax_amt < 0) {
                                $tax_amt = (-1) * $tax_amt;
                            }
                            $post2b['taxvalue']          = $tax_amt;
                            $post2b['PurchaseRetNumber'] = $voucherno;
                            $post2b['masterid']          = $dbuvalue;
                            $post2b['pd_code']           = $pd_code;
                            $post2b['RetDate']           = $voucherdate;
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
                            $pro_qry = mysql_query("SELECT pgm.distributorcode,p.ProductDescription, p.ProductGroupCode FROM productmaster p LEFT JOIN pgroupmapping pgm ON p.ProductGroupCode = pgm.productgroupcode WHERE p.ProductCode= '$productcode' AND pgm.PrimaryFranchise='$pd_code' AND mapping='Yes'");
                            while ($pro_record = mysql_fetch_array($pro_qry)) {
                                $sd_code    = $pro_record['distributorcode'];
                                $productdes = $pro_record['ProductDescription'];
                                $pgroupname = $pro_record['ProductGroupCode'];
                            }
                            $post2b['franchisecode'] = $sd_code;
                            $news->addNews($post2b, $inventorytable);
                            $j++;
                            $gross_amt   = $vch_amt + $tax_amt;
                            echo $inarguments = "'$regionname','$branchname','$pd_code','$sd_code','$franchisename','$voucherno','$finvsno','$refno','$voucherdate','$productcode','$pgroupname','$vouchertype','$inv_qty','$vch_amt','$tax_amt','$gross_amt','$dbuvalue'";
                            $insqry      = 'CALL r_insertreport("' . $inarguments . '","purchasereturn")';
                            $qry_exec = mysql_query($insqry) or DIE(mysql_error());
                        }
                    }
                    $count2 = $count2 . $responseid . "~";
                } 
/* Purchase Return Voucher - End */
				
/* Sales Return Voucher - Start */
elseif ($voucherparent == 'Credit Note') {
                    $voucherno = $voucherno . "-SR-" . $pd_code;
                    $refno     = $billref = $pd_code = $location = $tertiary_code = NULL;
                    foreach ($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS) {
                        $billref = $BILLDETAILS->BILLNAME;
                        If ($billref != "") {
                            $refno .= $billref . "~";
                        }
                    }
                    $refno                  = substr($refno, 0, -1);
                    $postc['pd_code']       = $pd_code;
                    $postc['salesRetdate']  = $voucherdate;
                    $postc['VoucherType']   = $vouchertype;
                    $postc['salesRetno']    = $voucherno;
                    $postc['referenceno']   = $refno;
                    $postc['masterid']      = $dbuvalue;
                    $retailername           = $VOUCHER->PARTYLEDGERNAME;
                    $location               = $dsrlocation;//$VOUCHER->LOCATION;
                    $tertiary_code          = $dsrcode;//$VOUCHER->TERTIARYCODE;
                    $postc['retailername']  = $retailername;
                    $postc['location']      = $location;
                    $postc['tertiary_code'] = $tertiary_code;
					$postc['dsrname']      = $dsrname;
                    $vch_amt                = floatval(str_replace(",", "", $VOUCHER->VCHAMOUNT));
                    if ($vch_amt < 0) {
                        $vch_amt = (-1) * $vch_amt;
                    }
                    $postc['Rettotalamount'] = $vch_amt;
                    $postc['narration']      = $VOUCHER->NARRATION;
                    $postc['schemename']     = $schemetype;
                    $postc['voucherstatus']  = $status;
                    $vouchertable            = 'salesreturn';
                    $qry                     = mysql_query("select salesRetno  from  salesreturn where masterid='" . $dbuvalue . "' ");
                    $groCount                = mysql_num_rows($qry);
                    if ($groCount == 0) {
                        $news->addNews($postc, $vouchertable);
                    } else if ($groCount > 0) {
                        $wherecon = "masterid ='" . $dbuvalue . "'";
                        $news->editNews($postc, $vouchertable, $wherecon);
                        $taxtable  = 'salesledgerreturn';
                        $wherecon1 = "masterid ='" . $dbuvalue . "' ";
                        $news->deleteNews($taxtable, $wherecon1);
                        $inventorytable = 'salesreturnitem';
                        $wherecon2      = "masterid ='" . $dbuvalue . "' ";
                        $news->deleteNews($inventorytable, $wherecon2);
                        $wherecondel      = "unique_id ='" . $dbuvalue . "'";
                        $updatesalestable = 'r_salesreturn';
                        $news->deleteNews($updatesalestable, $wherecondel);
                    }
                    $j = 1;
                    foreach ($VOUCHER->children() as $ALLLEDGERENTRIES) {
                        if ($voucherparent == 'Credit Note' && $stkitemname = $ALLLEDGERENTRIES->LEDGERNAME != "") {
                            $taxtable                 = 'salesledgerreturn';
                            $ledger                   = $ALLLEDGERENTRIES->LEDGERNAME;
                            $post1c['taxledger']      = $ledger;
                            $post1c['ledgertaxvalue'] = $ALLLEDGERENTRIES->LEDGERTAXVALUE;
                            $tax_amt                  = floatval(str_replace(",", "", $ALLLEDGERENTRIES->AMOUNT));
                            if ($tax_amt < 0) {
                                $tax_amt = (-1) * $tax_amt;
                            }
                            $post1c['taxamount']     = $tax_amt;
                            $post1c['SalesRetNo']    = $voucherno;
                            $post1c['masterid']      = $dbuvalue;
                            $post1c['salesRetdate']  = $voucherdate;
                            $post1c['pd_code']       = $pd_code;
                            $post1c['voucherstatus'] = $status;
                            if ($j > 1) {
                                $news->addNews($post1c, $taxtable);
                            }
                            $j++;
                        }
                    }
                    foreach ($VOUCHER->children() as $INVENTORYALLOCATIONS) {
                        if ($voucherparent == 'Credit Note' && $stkitemname = $INVENTORYALLOCATIONS->STOCKITEMNAME != "" && $PRODUCTCODE = $INVENTORYALLOCATIONS->PRODUCTCODE != "" && $billqty = $INVENTORYALLOCATIONS->BILLEDQTY != "") {
                            $inventorytable        = 'salesreturnitem';
                            $productcode           = $INVENTORYALLOCATIONS->PRODUCTCODE;
                            $post2c['productcode'] = $productcode;
                            $post2c['productdes']  = $INVENTORYALLOCATIONS->STOCKITEMNAME;
                            $post2c['rate']        = $INVENTORYALLOCATIONS->RATE;
                            $vch_amt               = floatval(str_replace(",", "", $INVENTORYALLOCATIONS->AMOUNT));
                            if ($vch_amt < 0) {
                                $vch_amt = (-1) * $vch_amt;
                            }
                            $post2c['amount']   = $vch_amt;
                            $inv_qty            = $INVENTORYALLOCATIONS->BILLEDQTY;
                            $post2c['quantity'] = $inv_qty;
                            $tax_amt            = floatval(str_replace(",", "", $INVENTORYALLOCATIONS->TAXVALUE));
                            if ($tax_amt < 0) {
                                $tax_amt = (-1) * $tax_amt;
                            }
                            $post2c['taxvalue']      = $tax_amt;
                            $post2c['saleRetsno']    = $voucherno;
                            $post2c['masterid']      = $dbuvalue;
                            $post2c['salesRetdate']  = $voucherdate;
                            $post2c['voucherstatus'] = $status;
                            $post2c['pd_code']       = $pd_code;
                            $arrayassign             = array();
                            $basicuser               = $INVENTORYALLOCATIONS->BASICUSERDESCRIPTION;
                            $arrayassign             = explode(",", $basicuser);
                            $a                       = count($arrayassign);
                            $pro_qry                 = mysql_query("SELECT pgm.distributorcode,p.ProductDescription, p.ProductGroupCode FROM productmaster p LEFT JOIN pgroupmapping pgm ON p.ProductGroupCode = pgm.productgroupcode WHERE p.ProductCode= '$productcode' AND pgm.PrimaryFranchise='$pd_code' AND mapping='Yes'");
                            while ($pro_record = mysql_fetch_array($pro_qry)) {
                                $sd_code    = $pro_record['distributorcode'];
                                $productdes = $pro_record['ProductDescription'];
                                $pgroupname = $pro_record['ProductGroupCode'];
                            }
                            $post2c['franchisecode'] = $sd_code;
                            $news->addNews($post2c, $inventorytable);
                            $j++;
                            $gross_amt   = $vch_amt + $tax_amt;
                            $inarguments = "'$regionname','$branchname','$pd_code','$sd_code','$franchisename','$voucherno','$refno','$voucherdate','$retailername','$location','$tertiary_code','$dsrname','$productcode','$productdes','$pgroupname','$vouchertype','$inv_qty','$vch_amt','$tax_amt','$gross_amt','$dbuvalue'";
                            $insqry      = 'CALL r_insertreport("' . $inarguments . '","salesreturn")';
                            $qry_exec = mysql_query($insqry) or DIE(mysql_error());
                        }
                    }
                    $count3 = $count3 . $responseid . "~";
                }
				/* Sales Return Voucher - End */ 
				/* Sales Voucher - Start */				
				elseif ($voucherparent == 'Sales') {
                    //$refno = $billref = $pd_code = $location = $tertiary_code = NULL;
					$refno = $billref = $location = $tertiary_code = NULL;
					

				
                    foreach ($VOUCHER->ALLLEDGERENTRIES->children() as $BILLDETAILS) {
                        $billref = $BILLDETAILS->BILLNAME;
                        If ($billref != "") {
                            $refno .= $billref . "~";
                        }
                    }
                    $refno                  = substr($refno, 0, -1);
                    $voucherno              = $voucherno . "-RS-" . $pd_code;
                    $dbuvalue               = $dbuvalue;
                    $postd['salesdate']     = $voucherdate;
                    $postd['VoucherType']   = $vouchertype;
                    $postd['salesno']       = $voucherno;
                    $postd['referenceno']   = $refno;
                    $postd['masterid']      = $dbuvalue;
                    $retailername           = $VOUCHER->PARTYLEDGERNAME;
                    $location               = $dsrlocation;//$VOUCHER->LOCATION;
                    $tertiary_code          = $dsrcode;//$VOUCHER->TERTIARYCODE;
                    $postd['retailername']  = $retailername;
                    $postd['location']      = $location;
                    $postd['tertiary_code'] = $tertiary_code;
					$postd['dsrname'] = $dsrname;
                    $vch_amt                = floatval(str_replace(",", "", $VOUCHER->VCHAMOUNT));
                    if ($vch_amt < 0) {
                        $vch_amt = (-1) * $vch_amt;
                    }
                    $postd['totalamount']   = $vch_amt;
                    $postd['narration']     = $VOUCHER->NARRATION;
                    $postd['pd_code']       = $pd_code;
                    $postd['schemename']    = $schemetype;
                    $postd['voucherstatus'] = $status;
                    $postd['pricelevel']    = $VOUCHER->PRICELEVEL;
                    $pricelevel             = $VOUCHER->PRICELEVEL;
                    $vouchertable           = 'retailersales';
                    $qry                    = mysql_query("select salesno  from  retailersales where masterid='" . $dbuvalue . "'");
                    $groCount               = mysql_num_rows($qry);
                    if ($groCount == 0) {
                        $news->addNews($postd, $vouchertable);
                    } else if ($groCount > 0) {
                        $wherecon = "masterid ='" . $dbuvalue . "'";
                        $news->editNews($postd, $vouchertable, $wherecon);
                        $wherecondel      = "unique_id ='" . $dbuvalue . "'";
                        $updatesalestable = 'r_salesreport';
                        $news->deleteNews($updatesalestable, $wherecondel);
                        $inventorytable = 'retailersalesitem';
                        $wherecon2      = "masterid ='" . $dbuvalue . "'";
                        $news->deleteNews($inventorytable, $wherecon2);
                        $taxtable  = 'retailersalesledger';
                        $wherecon1 = "masterid ='" . $dbuvalue . "' ";
                        $news->deleteNews($taxtable, $wherecon1);
                    }
                    $i = 1;
                    foreach ($VOUCHER->children() as $ALLLEDGERENTRIES) {
                        if ($voucherparent == 'Sales' && $stkitemname = $ALLLEDGERENTRIES->LEDGERNAME != "") {
                            $taxtable                 = 'retailersalesledger';
                            $ledger                   = $ALLLEDGERENTRIES->LEDGERNAME;
                            $post1d['taxledger']      = $ledger;
                            $post1d['ledgertaxvalue'] = $ALLLEDGERENTRIES->LEDGERTAXVALUE;
                            $tax_amt                  = floatval(str_replace(",", "", $ALLLEDGERENTRIES->AMOUNT));
                            if ($tax_amt < 0) {
                                $tax_amt = (-1) * $tax_amt;
                            }
                            $post1d['taxamount']     = $tax_amt;
                            $post1d['SalesNo']       = $voucherno;
                            $post1d['masterid']      = $dbuvalue;
                            $post1d['salesdates']    = $voucherdate;
                            $post1d['pd_code']       = $pd_code;
                            $post1d['voucherstatus'] = $status;
                            if ($i > 1) {
                                $news->addNews($post1d, $taxtable);
                            }
                            $i++;
                        }
                    }
                    foreach ($VOUCHER->children() as $INVENTORYALLOCATIONS) {
                        if ($voucherparent == 'Sales' && $stkitemname = $INVENTORYALLOCATIONS->STOCKITEMNAME != "" && $PRODUCTCODE = $INVENTORYALLOCATIONS->PRODUCTCODE != "" && $billqty = $INVENTORYALLOCATIONS->BILLEDQTY != "") {
                            $inventorytable        = 'retailersalesitem';
                            $productcode           = $INVENTORYALLOCATIONS->PRODUCTCODE;
                            $post2d['productcode'] = $productcode;
                            $post2d['productdes']  = $INVENTORYALLOCATIONS->STOCKITEMNAME;
                            $post2d['rate']        = $INVENTORYALLOCATIONS->RATE;
                            $vch_amt               = floatval(str_replace(",", "", $INVENTORYALLOCATIONS->AMOUNT));
                            if ($vch_amt < 0) {
                                $vch_amt = (-1) * $vch_amt;
                            }
                            $post2d['amount']   = $vch_amt;
                            $inv_qty            = $INVENTORYALLOCATIONS->BILLEDQTY;
                            $post2d['quantity'] = $inv_qty;
                            $tax_amt            = floatval(str_replace(",", "", $INVENTORYALLOCATIONS->TAXVALUE));
                            if ($tax_amt < 0) {
                                $tax_amt = (-1) * $tax_amt;
                            }
                            $post2d['taxvalue']      = $tax_amt;
                            $post2d['salesno']       = $voucherno;
                            $post2d['masterid']      = $dbuvalue;
                            $post2d['salesdates']    = $voucherdate;
                            $post2d['voucherstatus'] = $status;
                            $post2d['pd_code']       = $pd_code;
                            $pro_qry                 = mysql_query("SELECT pgm.distributorcode,p.ProductDescription, p.ProductGroupCode FROM productmaster p LEFT JOIN pgroupmapping pgm ON p.ProductGroupCode = pgm.productgroupcode WHERE p.ProductCode= '$productcode' AND pgm.PrimaryFranchise='$pd_code' AND mapping='Yes'");
                            while ($pro_record = mysql_fetch_array($pro_qry)) {
                                $sd_code    = $pro_record['distributorcode'];
                                $productdes = $pro_record['ProductDescription'];
                                $pgroupname = $pro_record['ProductGroupCode'];
                            }
                            $post2d['franchisecode'] = $sd_code;
								
								

							
							
							
                            $news->addNews($post2d, $inventorytable);
                            $j++;
                            $gross_amt   = $vch_amt + $tax_amt;
                            $inarguments = "'$regionname','$branchname','$pd_code','$sd_code','$franchisename','$voucherno','$refno','$voucherdate','$retailername','$location','$tertiary_code','$dsrname','$productcode','$productdes','$pgroupname','$vouchertype','$inv_qty','$vch_amt','$tax_amt','$gross_amt','$status','$pricelevel','$dbuvalue'";
                            $insqry      = 'CALL r_insertreport("' . $inarguments . '","sales")';
                            $qry_exec = mysql_query($insqry) or DIE(mysql_error());
                        }
                    }
                    $count4 = $count4 . $responseid . "~";
                }/* Sales Voucher - End */				
            }
        }
    }
}
$count                  = $count1 . $count2 . $count3 . $count4 . $count;
$tax                    = 'uploadstatus';
$post1['franchisecode'] = $pd_code;
$post1['date']          = date("Y-m-d");
$post1['status']        = 'Delivered';
$news->addNews($post1, $tax); {
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
