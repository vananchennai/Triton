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
/*  $getData="<ENVELOPE>
<FRANCHISECODE>1000681</FRANCHISECODE>
<DATE>1-Apr-2013</DATE>
<TODATE>31-Mar-2014</TODATE>
<REQUEST>
<VOUCHER>
<VOUCHERTYPE>STOCK ITEM OPENING BALANCE</VOUCHERTYPE>
<ALIASNAME>AAM-CR-CRST150AH</ALIASNAME>
<STOCKITEMNAME>150AH SHORT TUBULAR CR BATTERY</STOCKITEMNAME>
<GODOWNNAME>ARBL Ware House</GODOWNNAME>
<BATCHNAME>B2</BATCHNAME>
<OPENINGBALANCE>2</OPENINGBALANCE>
<OPENINGVALUE>600.00</OPENINGVALUE>
<MFGDATE>1-Apr-2012</MFGDATE>
<OPENINGRATE>300</OPENINGRATE>
</VOUCHER>
<VOUCHER>
<VOUCHERTYPE>STOCK ITEM OPENING BALANCE</VOUCHERTYPE>
<ALIASNAME>AAM-CR-CRTU150AH</ALIASNAME>
<STOCKITEMNAME>150AH TALL TUBULAR CR BATTERY</STOCKITEMNAME>
<GODOWNNAME>WIP - Service</GODOWNNAME>
<BATCHNAME>B3</BATCHNAME>
<OPENINGBALANCE>3</OPENINGBALANCE>
<OPENINGVALUE>750.00</OPENINGVALUE>
<MFGDATE>2-Apr-2012</MFGDATE>
<OPENINGRATE>250</OPENINGRATE>
</VOUCHER>
<VOUCHER>
<VOUCHERTYPE>STOCK ITEM OPENING BALANCE</VOUCHERTYPE>
<ALIASNAME>ABR-PR-12APATX25</ALIASNAME>
<STOCKITEMNAME>2.5Ah AMARON PRO Bike Rider -Alpha</STOCKITEMNAME>
<GODOWNNAME>ARBL Ware House</GODOWNNAME>
<BATCHNAME>33</BATCHNAME>
<OPENINGBALANCE>4</OPENINGBALANCE>
<OPENINGVALUE>1,600.00</OPENINGVALUE>
<MFGDATE></MFGDATE>
<OPENINGRATE>400</OPENINGRATE>
</VOUCHER>
<VOUCHER>
<VOUCHERTYPE>STOCK ITEM OPENING BALANCE</VOUCHERTYPE>
<ALIASNAME>ABR-PR-12APBTX25</ALIASNAME>
<STOCKITEMNAME>2.5Ah AMARON PRO Bike Rider -Beta</STOCKITEMNAME>
<GODOWNNAME>ARBL Ware House</GODOWNNAME>
<BATCHNAME>0103CA1234567</BATCHNAME>
<OPENINGBALANCE>1</OPENINGBALANCE>
<OPENINGVALUE>200.00</OPENINGVALUE>
<MFGDATE></MFGDATE>
<OPENINGRATE>200</OPENINGRATE>
</VOUCHER>
<VOUCHER>
<VOUCHERTYPE>STOCK ITEM OPENING BALANCE</VOUCHERTYPE>
<ALIASNAME>ABR-PR-12APBTX25</ALIASNAME>
<STOCKITEMNAME>2.5Ah AMARON PRO Bike Rider -Beta</STOCKITEMNAME>
<GODOWNNAME>ARBL Ware House</GODOWNNAME>
<BATCHNAME>0103CA1234568</BATCHNAME>
<OPENINGBALANCE>1</OPENINGBALANCE>
<OPENINGVALUE>400.00</OPENINGVALUE>
<MFGDATE></MFGDATE>
<OPENINGRATE>400</OPENINGRATE>
</VOUCHER>
<VOUCHER>
<VOUCHERTYPE>STOCK ITEM OPENING BALANCE</VOUCHERTYPE>
<ALIASNAME>ABR-PR-12APBTX25</ALIASNAME>
<STOCKITEMNAME>2.5Ah AMARON PRO Bike Rider -Beta</STOCKITEMNAME>
<GODOWNNAME>ARBL Ware House</GODOWNNAME>
<BATCHNAME>0103CA1234569</BATCHNAME>
<OPENINGBALANCE>1</OPENINGBALANCE>
<OPENINGVALUE>450.00</OPENINGVALUE>
<MFGDATE></MFGDATE>
<OPENINGRATE>450</OPENINGRATE>
</VOUCHER>
<VOUCHER>
<VOUCHERTYPE>STOCK ITEM OPENING BALANCE</VOUCHERTYPE>
<ALIASNAME>AAM-HU-HUPS400VA</ALIASNAME>
<STOCKITEMNAME>400V A AMARON HOME UPS</STOCKITEMNAME>
<GODOWNNAME>Scrap Go Down</GODOWNNAME>
<BATCHNAME>B New</BATCHNAME>
<OPENINGBALANCE>10</OPENINGBALANCE>
<OPENINGVALUE>6,500.00</OPENINGVALUE>
<MFGDATE>2-Apr-2012</MFGDATE>
<OPENINGRATE>650</OPENINGRATE>
</VOUCHER>
<VOUCHER>
<VOUCHERTYPE>STOCK ITEM OPENING BALANCE</VOUCHERTYPE>
<ALIASNAME>OMM-WR-00048D26R</ALIASNAME>
<STOCKITEMNAME>48D26R Warranty Battery-OEM-M and M</STOCKITEMNAME>
<GODOWNNAME>Main Location</GODOWNNAME>
<BATCHNAME>2</BATCHNAME>
<OPENINGBALANCE>6</OPENINGBALANCE>
<OPENINGVALUE>2,700.00</OPENINGVALUE>
<MFGDATE>1-Apr-2012</MFGDATE>
<OPENINGRATE>450</OPENINGRATE>
</VOUCHER>
</REQUEST>
</ENVELOPE>"; 
If(1) {
$xml_object = simplexml_load_string($getData); */
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
                if ($vouchertype == 'STOCK ITEM OPENING BALANCE') {
                    $salesdate                    = $opdate;
                    $postop['opdate']             = $salesdate;
                    $pccod                        = str_replace("\n",'',$VOUCHER->ALIASNAME);
					$pccode                       = str_replace("\n",'',$pccod);
                    $postop['productcode']        = $pccode;
                    $postop['productdescription'] = $VOUCHER->STOCKITEMNAME;
                    $postop['openstock']          = preg_replace('/[()]/', '', $VOUCHER->OPENINGBALANCE);
                    $postop['rate']               = $VOUCHER->OPENINGRATE;
                    $pro_qry                      = mysql_query("SELECT pgm.distributorcode FROM productmaster p LEFT JOIN pgroupmapping pgm ON p.ProductGroupCode = pgm.productgroupcode WHERE p.ProductCode= '$pccode' AND pgm.PrimaryFranchise='$FRANCHISECODE' AND mapping='Yes'");
                    while ($pro_record = mysql_fetch_array($pro_qry)) {
                        $postop['franchiseecode'] = $pro_record['distributorcode'];
                    }
                    $pString    = str_replace(",", "", $VOUCHER->OPENINGVALUE);
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
