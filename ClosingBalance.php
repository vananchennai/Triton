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
$news = new News(); // Create a new News Object
//  Sample data 
 /* $getData="<ENVELOPE>
 <FRANCHISECODE>000008</FRANCHISECODE>
 <DATE>23-Dec-2015</DATE>
 <TODATE>23-Dec-2015</TODATE>
 <REQUEST>
  <VOUCHER>
   <VOUCHERTYPE>STOCK ITEM CLOSING BALANCE</VOUCHERTYPE>
   <ALIASNAME>A3003-FE240</ALIASNAME>
   <STOCKITEMNAME>FE240</STOCKITEMNAME>
   <GODOWNNAME>Main Location</GODOWNNAME>
   <BATCHNAME>Primary Batch</BATCHNAME>
   <CLOSINGBALANCE>96</CLOSINGBALANCE>
   <CLOSINGVALUE>21,120.00</CLOSINGVALUE>
   <MFGDATE></MFGDATE>
   <CLOSINGRATE>220</CLOSINGRATE>
  </VOUCHER>
  <VOUCHER>
   <VOUCHERTYPE>STOCK ITEM CLOSING BALANCE</VOUCHERTYPE>
   <ALIASNAME>A3003-FE252</ALIASNAME>
   <STOCKITEMNAME>FE252</STOCKITEMNAME>
   <GODOWNNAME>Main Location</GODOWNNAME>
   <BATCHNAME>Primary Batch</BATCHNAME>
   <CLOSINGBALANCE>113</CLOSINGBALANCE>
   <CLOSINGVALUE>12,241.67</CLOSINGVALUE>
   <MFGDATE></MFGDATE>
   <CLOSINGRATE>108.33</CLOSINGRATE>
  </VOUCHER>
 </REQUEST>
</ENVELOPE>
"; 
If(1){ 
    $xml_object = simplexml_load_string($getData);*/
if (isset($HTTP_RAW_POST_DATA)) {
    $xml_object = simplexml_load_string($HTTP_RAW_POST_DATA);
	
    if ($xml_object != "") {
        $i               = 1;
        $masterid        = '';
        $oldserialnumber = '';
        $groCount        = 0;
        $count           = $count1 = $count2 = $count3 = $count4 = $count5 = $count6 = $count7 = $count8 = $count9 = $count10 = $count11 = $count12 = $count13 = $count14 = $count15 = $count16 = $count17 = $count18 = $count19 = NULL;
        date_default_timezone_set('UTC');
        $FRANCHISECODE = $xml_object->FRANCHISECODE;
        $opdate        = $xml_object->DATE;
        $category      = $xml_object->REQUEST->VOUCHER;
        foreach ($xml_object->REQUEST->children() as $VOUCHER) {
            $opdate      = strtotime($opdate);
            $opdate      = date('Y-m-d', $opdate);
            $vouchertype = $VOUCHER->VOUCHERTYPE;
            $fraqry      = mysql_query("select PrimaryFranchise  from  franchisemaster where  PrimaryFranchise='" . $FRANCHISECODE . "'");
            $fraCount    = mysql_num_rows($fraqry);
            if ($fraCount == 0) {
                echo "Franchisee Not Available in tally central server";
            } else {
                if ($vouchertype == 'STOCK ITEM CLOSING BALANCE') { 
				
                    $salesdate                    = $opdate;
                    $postop['opdate']             = $salesdate;
                    $pccod                       = str_replace("\n",'',$VOUCHER->ALIASNAME);
					$pccode                       = str_replace("\r",'',$pccod);
                    $postop['productcode']        = $pccode;
                    $postop['productdescription'] = $VOUCHER->STOCKITEMNAME;
                    $postop['openstock']          = preg_replace('/[()]/', '', $VOUCHER->CLOSINGBALANCE);
                    $postop['rate']               = $VOUCHER->CLOSINGRATE;
                    $pro_qry                      = mysql_query("SELECT pgm.distributorcode FROM productmaster p LEFT JOIN pgroupmapping pgm ON p.ProductGroupCode = pgm.productgroupcode WHERE p.ProductCode= '$pccode' AND pgm.PrimaryFranchise='$FRANCHISECODE' AND mapping='Yes'");
						
                    while ($pro_record = mysql_fetch_array($pro_qry)) {
                       $postop['franchiseecode'] = $pro_record['distributorcode'];
                    }
                    $pString    = str_replace(",", "", $VOUCHER->CLOSINGVALUE);
                    $floatvalue = floatval($pString);
                    if ($floatvalue < 0) {
                        $floatvalue = (-1) * $floatvalue;
                    }
                    $postop['stockvalue'] = $floatvalue;
                    $mdate                = date('Y-m-d');
                    $postop['mdate']      = $mdate;
                    if ($pccode != '' && $opdate != '') {
                        $optname = "stockledgerreport";
                        $result  = mysql_query("SELECT * FROM stockledgerreport where opdate='" . $opdate . "' and productcode ='" . $pccode . "' and franchiseecode ='" . $FRANCHISECODE . "'");
                        $myrow1  = mysql_num_rows($result);
                        if ($myrow1 == 0) {
						
						
                            $news->addNews($postop, $optname);
                            $count19 = $count19 . $pccode . "~";
                        } else {
                            $whereconop = "opdate='" . $opdate . "' and productcode ='" . $postop['productcode'] . "' and franchiseecode ='" . $postop['franchiseecode'] . "'";
                            $news->editNews($postop, $optname, $whereconop);
                            $count19 = $count19 . $pccode . "~";
                        }
                    }
                }
            }
        }
    }
}
$count = $count19 . $count;
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
?>