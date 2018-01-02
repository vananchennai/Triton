<?php
include '../../../functions.php';
sec_session_start();
require_once '../../../Mysql.php';
$dblink = new Mysql();
$dblink->__construct();
$param=$_GET['param'];
global  $textout,$rowcount,$rcode,$rcodelen;
if(strlen($param)>0)
{ 


$result = mysql_query("select max(RetailerCode) as RetailerCodes from retailermaster where fmexecutive = '$param'"); 
$textout=$rowcount=$rcode=$rcodelen=NULL;
$rowcount=mysql_fetch_array($result);
$valuess= $rowcount['RetailerCodes'];

$idcode=substr($valuess , 8, 4);
$rcode= 1 + $idcode; 
$rcodelen =strlen($rcode);
if ( $rcodelen = 1)
{
$textout=$param.'-000'.$rcode;
}
else if ( $rcodelen = 2)
{
$textout= $param.'-00'.$rcode;
}
else if ( $rcodelen = 3)
{
$textout= $param.'-0'.$rcode;
}
else
{
$textout=$param.'-'.$rcode;
}
  } 
 echo $textout;
 