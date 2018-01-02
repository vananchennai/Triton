<?php

include '../../../functions.php';
sec_session_start();
require_once '../../../Mysql.php';
$dblink = new Mysql();
$dblink->__construct();
$param=$_GET['param'];
global  $textout;
if(strlen($param)>0)
{ 

$result = mysql_query("select employeename from employeemaster where employeecode ='$param'"); 
$textout=NULL;

if(mysql_num_rows($result)==1) 
{
 while($myrow = mysql_fetch_array($result))
 {
  $agentname = $myrow["employeename"]; 

  $textout = $agentname; 
  } 
  } 
  } 
  echo $textout;
 