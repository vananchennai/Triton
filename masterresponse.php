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
global $response;
require_once 'Mysql.php';
$news          = new Mysql();
$newsRecordSet = $news->__construct();
require_once 'masterclass.php';
$news = new News();
require_once 'weblog.php';
if (isset($HTTP_RAW_POST_DATA)) {
$xml_object          = simplexml_load_string($HTTP_RAW_POST_DATA);
/*
 $getdata="<ENVELOPE>
 <REQUEST>
  <NAME>PGroup</NAME>
  <FRANCHISEECODE>000007</FRANCHISEECODE>
 </REQUEST>
</ENVELOPE>"; 
 if (isset($getdata)){ 
    $xml_object          = simplexml_load_string($getdata);
	*/
    $request_person_name = $xml_object->REQUEST->NAME;
    $TestType            = $xml_object->REQUEST->TYPE;
    $FRANCHISECODE       = $xml_object->REQUEST->FRANCHISEECODE;
    $vouchersdowloaded   = $xml_object->REQUEST->DOWNLOADEDPURCHASEVOUCHERS;
    $fraqry              = mysql_query("select Franchisecode  from  franchisemaster where  PrimaryFranchise='" . $FRANCHISECODE . "'");
    $fraCount            = mysql_num_rows($fraqry);
    if ($fraCount > 0) {
        global $masters, $mas;
		/* Responces block Starts */
        if ($request_person_name == "Product Group is Created Successfully") {
            uploadfun("productgroupupload", "downloadstatus", $FRANCHISECODE, "product Group");
        }
        if ($request_person_name == "Product UOM is Created Successfully") {
            uploadfun("productuomupload", "downloadstatus", $FRANCHISECODE, "Product UOM");
        }
        if ($request_person_name == "Product Is Created Successfully") {
			uploadfun("productmasterupload", "downloadstatus", $FRANCHISECODE, "Product Master");
			$resultproduct = "SELECT PrimaryFranchise FROM productmasterupload WHERE Status!=2 and PrimaryFranchise='" . $FRANCHISECODE . "' LIMIT 500";
            $sqlproduct = mysql_query($resultproduct) or die(mysql_error());
			$prtCount = 0;
            $prtCount           = mysql_num_rows($sqlproduct);
			if($prtCount!=0)
			{
				$request_person_name = "ProductResponse";
			}
			
        }
		if ($request_person_name == "ProductResponse")
		{
		    print("<ENVELOPE>");
            print("<HEADER>");
            print("<VERSION>1</VERSION>");
            print("<STATUS>1</STATUS>");
            print("</HEADER>");
            print("<BODY>");
            print("<DATA>");
            print("<PURCHASEDETAILSHEAD>");
            print("<STATUSVALUE>Updated</STATUSVALUE> ");
            print("</PURCHASEDETAILSHEAD>");
            print("</DATA>");
            print("</BODY>");
            print("</ENVELOPE>");
		
		}
		/* Responce block Ends */
		
		/* Request block Starts */
        if ($request_person_name == "PGroup") {
            downloadfun("downloadstatus", $FRANCHISECODE, "Product Group");
            $fraqry1   = mysql_query("select Franchiseecode  from  productgroupupload where  PrimaryFranchise='" . $FRANCHISECODE . "'");
            $fraCount1 = mysql_num_rows($fraqry1);
            if ($fraCount1 > 0) {
                $result = "SELECT pg.ProductCode,pg.ProductGroup,pg.Parent,pgu.Franchiseecode FROM productgroupmaster pg LEFT JOIN  productgroupupload pgu ON pg.ProductCode= pgu.Code WHERE NOT EXISTS(SELECT  null FROM productgroupupload d WHERE d.Status=2 and d.Code= pg.ProductCode and d.PrimaryFranchise='" . $FRANCHISECODE . "') AND pgu.PrimaryFranchise='" . $FRANCHISECODE . "'";
                $sql1 = mysql_query($result) or die(mysql_error());
                $productgrplist = null;
                $groCount       = null;
                $groCount       = mysql_num_rows($sql1);
                while ($row = mysql_fetch_array($sql1)) {
                    $ProductCode    = $row['ProductCode'];
                    $ProductGroup   = $row['ProductGroup'];
                    $Parent         = $row['Parent'];
                    $secdis_code    = $row['Franchiseecode'];
                    echo $productgrplist = $productgrplist . $secdis_code . "!" . $ProductCode . "!" . $ProductGroup . "!" . $Parent . "^";
                }
                print("<ENVELOPE>");
                print("<HEADER>");
                print("<VERSION>1</VERSION>");
                print("<STATUS>1</STATUS>");
                print("</HEADER>");
                print("<BODY>");
                print("<DATA>");
                print("<PRODUCTGROUPHEAD>");
                print("<COUNT>" . $groCount . "</COUNT> ");
                print("<PRODUCTGROUP>" . $productgrplist . "</PRODUCTGROUP> ");
                print("</PRODUCTGROUPHEAD>");
                print("</DATA>");
                print("</BODY>");
                print("</ENVELOPE>");
            }
        }
        if ($request_person_name == "PUOM") {
            downloadfun("downloadstatus", $FRANCHISECODE, "Product UOM");
            $resultuom = "SELECT * FROM productuom  a WHERE   NOT EXISTS(SELECT  null FROM productuomupload d WHERE d.Status=2 and d.Code=a.productuomcode and d.PrimaryFranchise='" . $FRANCHISECODE . "')";
            $sqluom = mysql_query($resultuom) or die(mysql_error());
            $productuomlist = null;
            $uomCount       = null;
            $uomCount       = mysql_num_rows($sqluom);
            while ($rowuom = mysql_fetch_array($sqluom)) {
                $productuom     = $rowuom['productuomcode'];
                $productuom1    = $rowuom['productuom'];
                $productuomlist = $productuomlist . $productuom . "!" . $productuom1 . "^";
            }
            print("<ENVELOPE>");
            print("<HEADER>");
            print("<VERSION>1</VERSION>");
            print("<STATUS>1</STATUS>");
            print("</HEADER>");
            print("<BODY>");
            print("<DATA>");
            print("<PRODUCTUOMHEAD>");
            print("<COUNT>" . $uomCount . "</COUNT> ");
            print("<PRODUCTUOM>" . $productuomlist . "</PRODUCTUOM> ");
            print("</PRODUCTUOMHEAD>");
            print("</DATA>");
            print("</BODY>");
            print("</ENVELOPE>");
        }
        if ($request_person_name == "Product") {
            downloadfun("downloadstatus", $FRANCHISECODE, "Product Master");
            $resultproduct = "SELECT a.* FROM productmaster_view  a left join productmasterupload d on d.Code=a.ProductCode WHERE d.Status!=2 and d.PrimaryFranchise='" . $FRANCHISECODE . "' LIMIT 500";
            $sqlproduct = mysql_query($resultproduct) or die(mysql_error());
            $productproductlist = null;
            $prtCount           = null;
            $prtCount           = mysql_num_rows($sqlproduct);
            while ($rowproduct = mysql_fetch_array($sqlproduct)) {
                $ProductCode        = $rowproduct['ProductCode'];
                $ProductDescription = $rowproduct['ProductDescription'];
                $ProductGroupCode   = $rowproduct['ProductGroupCode'];
                $UOM                = $rowproduct['productuom'];
                $productproductlist = $productproductlist . $ProductCode . "!" . $ProductDescription . "!" . $ProductGroupCode . "!" . $UOM . "^";
            }
            print("<ENVELOPE>");
            print("<HEADER>");
            print("<VERSION>1</VERSION>");
            print("<STATUS>1</STATUS>");
            print("</HEADER>");
            print("<BODY>");
            print("<DATA>");
            print("<PRODUCTDETAILSHEAD>");
            print("<COUNT>" . $prtCount . "</COUNT> ");
            print("<PRODUCTDETAILS>" . $productproductlist . "</PRODUCTDETAILS> ");
            print("</PRODUCTDETAILSHEAD>");
            print("</DATA>");
            print("</BODY>");
            print("</ENVELOPE>");
        }
       if ($request_person_name == "Purchase") {
            $secondaryDBcodes = "Select PrimaryFranchise,Franchisecode, Franchisename from franchisemaster where PrimaryFranchise='" . $FRANCHISECODE . "'";
            $secondaryDBcodesqry = mysql_query($secondaryDBcodes) or die(mysql_error());
            while ($rowfc = mysql_fetch_array($secondaryDBcodesqry)) {
                $secdbcode = $secdbcode . "'" . $rowfc['Franchisecode'] . "',";
            }
            $SDCODE         = substr($secdbcode, 0, -1);
            $resultpurchase = "SELECT Distinct(FINVNO),FINVNO,FINVDT,PONO,PODT,PLANT,Status FROM ztally_invoice  where DCODE IN (" . $SDCODE . ") and Status=0";
            echo $resultpurchase;
            $sqlpurchase = mysql_query($resultpurchase) or die(mysql_error());
            $purchasedetailslist = null;
            $prtCount            = null;
            $prtCount            = mysql_num_rows($sqlpurchase);
            while ($rowpurchase = mysql_fetch_array($sqlpurchase)) {
                $PurchaseNumber = $rowpurchase['FINVNO'];
                $PurchaseDates  = $rowpurchase['FINVDT'];
                $PurchaseDatess = strtotime($PurchaseDates);
                $PurchaseDate   = date("d/m/Y", $PurchaseDatess);
                $pono           = $rowpurchase['PONO'];
                $podates        = $rowpurchase['PODT'];
                $podatess       = strtotime($podates);
                $podate         = date("d/m/Y", $podatess);
                $partyname      = $rowpurchase['PLANT'];
                $resultproduct  = "SELECT * FROM ztally_invoice where FINVNO='" . $PurchaseNumber . "' and DCODE IN (" . $SDCODE . ") and Status=0";
                echo $resultproduct;
                $sqlproduct = mysql_query($resultproduct) or die(mysql_error());
                $productdetailslist = null;
                $prodCount          = null;
                $prodCount          = mysql_num_rows($sqlproduct);
                while ($rowproduct = mysql_fetch_array($sqlproduct)) {
                    $ProductCode        = $rowproduct['MATNR'];
                    $finvsno            = $rowproduct['FINVSNO'];
                    $Quantity           = $rowproduct['QTY'];
                    $productdetailslist = $productdetailslist . $ProductCode . "*" . $finvsno . "*" . $Quantity . "~";
                }
                $purchasedetailslist = $purchasedetailslist . $PurchaseNumber . "!" . $PurchaseDate . "!" . $pono . "!" . $podate . "!" . $partyname . "!" . $productdetailslist . "^";
            }
            print("<ENVELOPE>");
            print("<HEADER>");
            print("<VERSION>1</VERSION>");
            print("<STATUS>1</STATUS>");
            print("</HEADER>");
            print("<BODY>");
            print("<DATA>");
            print("<PURCHASEDETAILSHEAD>");
            print("<COUNT>" . $prtCount . "</COUNT> ");
            print("<PURCHASEDETAILS>" . $purchasedetailslist . "</PURCHASEDETAILS> ");
            print("</PURCHASEDETAILSHEAD>");
            print("</DATA>");
            print("</BODY>");
            print("</ENVELOPE>");
        }
        if ($request_person_name == "Purchase Invoice is Created Successfully") {
            $secondaryDBcodes1 = "Select PrimaryFranchise,Franchisecode, Franchisename from franchisemaster where PrimaryFranchise='" . $FRANCHISECODE . "'";
            $secondaryDBcodesqry1 = mysql_query($secondaryDBcodes1) or die(mysql_error());
            while ($rowfc1 = mysql_fetch_array($secondaryDBcodesqry1)) {
                $secdbcode1 = $secdbcode1 . "'" . $rowfc1['Franchisecode'] . "',";
            }
            $SDCODE1   = substr($secdbcode1, 0, -1);
            $masters   = "Purchase(GRN)";
            $taxtable4 = 'ztally_invoice';
            date_default_timezone_set("Asia/Calcutta");
            $post1c4['DeliveryDate'] = date("Y-m-d H:i:s");
            $post1c4['Status']       = '2';
            echo $wherecon = "DCODE IN (" . $SDCODE1 . ") ";
            $news->editNews($post1c4, $taxtable4, $wherecon);
            $logtable                   = 'downloadstatus';
            $insertval['franchisecode'] = $FRANCHISECODE;
            $insertval['master']        = $masters;
            $insertval['date']          = date("Y-m-d H:i:s", time());
            $insertval['status']        = 'Delivered';
            $news->addNews($insertval, $logtable);
        }
        if ($request_person_name == "PurchaseStatus") {
            $secondaryDBcodesStatus = "Select PrimaryFranchise,Franchisecode, Franchisename from franchisemaster where PrimaryFranchise='" . $FRANCHISECODE . "'";
            $secondaryDBcodesqryStatus = mysql_query($secondaryDBcodesStatus) or die(mysql_error());
            while ($rowfcstatus = mysql_fetch_array($secondaryDBcodesqryStatus)) {
                $secdbcodeStatus = $secdbcodeStatus . "'" . $rowfcstatus['Franchisecode'] . "',";
            }
            $SDCODES   = substr($secdbcodeStatus, 0, -1);
            $masters   = "Purchase(GRN)";
            $taxtable4 = 'ztally_invoice';
            date_default_timezone_set("Asia/Calcutta");
            $post1c4['DeliveryDate'] = date("Y-m-d H:i:s");
            $post1c4['Status']       = '2';
            $vnoarray                = array();
            $vnoarray                = Explode("~", $vouchersdowloaded);
            foreach ($vnoarray as $arvalue) {
                $wherecon2 .= "'" . $arvalue . "'" . ",";
            }
            $wherecon3 = substr_replace($wherecon2, "", -4);
            $wherecon  = "DCODE IN (" . $SDCODES . ") and FINVNO IN (" . $wherecon3 . ")";
            $news->editNews($post1c4, $taxtable4, $wherecon);
            $logtable                   = 'downloadstatus';
            $insertval['franchisecode'] = $FRANCHISECODE;
            $insertval['master']        = $masters;
            $insertval['date']          = date("Y-m-d H:i:s", time());
            $insertval['status']        = 'Delivered';
            $news->addNews($insertval, $logtable);
        }
		/* Request block Starts */
    }
}
?>		
	
