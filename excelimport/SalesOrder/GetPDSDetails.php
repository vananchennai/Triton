<?php
$file=fopen('../../rights.txt',"r") or exit("Unable to open file!");
while (!feof($file))
 {
 $str= $str.fgetc($file);
  }

list($host, $uid, $pass,$databname) = explode('~',trim($str));
fclose($file);
//header('Content-Type: application/json');
        mysql_connect($host,$uid,$pass) or die( mysql_error() ); 

            //     This says connect to the database or exit and
            //    give me the reason I couldn't connect.
        mysql_select_db($databname) or die( mysql_error() );
$PDSQuery = "SELECT po.ekb_order_no,po.line_no,po.part_no, po.Part_Name,po.unit_qty,pm.HIMLorToyotoPartNumber,pm.Assessable_Value,pm.Basic_Value FROM purchase_order po LEFT JOIN partmaster pm ON po.part_no=pm.PartNo ";
$Pdsnumber = $_POST['pdsnumber'];
//$Pdsnumber ='11 T147AT3A0275';
$PDSQuery = $PDSQuery." WHERE po.PDS_number='".$Pdsnumber."'";
$PDSQueryResult = mysql_query($PDSQuery);
$Pds = array();
while($row = mysql_fetch_array($PDSQueryResult,MYSQL_ASSOC)){
	$Pds[] = $row;
}
$ret['pdsdetails'] = $Pds;
echo json_encode($ret);

?>