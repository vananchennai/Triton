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
	$news = new News(); // Create a new News Object
//  Sample data 
 $getData="<ENVELOPE>
 <FRANCHISECODE>430418</FRANCHISECODE>
 <DATE>1-Apr-2013</DATE>
 <TODATE>31-Mar-2014</TODATE>
 <REQUEST>
  <VOUCHER>
   <VOUCHERTYPE>STOCK ITEM CLOSING BALANCE</VOUCHERTYPE>
   <ALIASNAME>A3NKOS-8500</ALIASNAME>
   <STOCKITEMNAME>8500</STOCKITEMNAME>
   <GODOWNNAME></GODOWNNAME>
   <BATCHNAME></BATCHNAME>
   <CLOSINGBALANCE>2</CLOSINGBALANCE>
   <CLOSINGVALUE>600.00</CLOSINGVALUE>
   <MFGDATE>1-Apr-2012</MFGDATE>
   <CLOSINGRATE>300</CLOSINGRATE>
  </VOUCHER>
 </REQUEST>
</ENVELOPE>"; 
if ( isset( $HTTP_RAW_POST_DATA ) )
//If(1)
{                     
   
//$xml_object = simplexml_load_string($getData);
			//echo $TestType =$xml_object->REQUEST->VOUCHER->INVENTORYALLOCATIONSLIST->;
			//echo $xml_object;
			//print_r($xml_object);
$xml_object = simplexml_load_string( $HTTP_RAW_POST_DATA ); 
        if($xml_object!="") 
		{
			$i=1;$masterid='';$oldserialnumber='';$groCount=0;$count=$count1=$count2=$count3=$count4=$count5=$count6=$count7=$count8=$count9=$count10=$count11=$count12=$count13=$count14=$count15=$count16=$count17=$count18=$count19=NULL;
            date_default_timezone_set('UTC');
			$FRANCHISECODE =$xml_object->FRANCHISECODE;
			 $opdate =$xml_object->DATE;
		  $category=$xml_object->REQUEST->VOUCHER;
		  
		   foreach($xml_object->REQUEST->children() as $VOUCHER)
                {   
				
                 $opdate = strtotime($opdate);
                  $opdate=date('Y-m-d', $opdate);
				 $vouchertype=$VOUCHER->VOUCHERTYPE;				
					
					$qry="select Franchisecode  from  franchisemaster where  Franchisecode='".$FRANCHISECODE."'";
					//echo $qry;
					$fraqry =mysql_query("select Franchisecode  from  franchisemaster where  Franchisecode='".$FRANCHISECODE."'");
					//echo $fraqry;
					$fraCount=mysql_num_rows($fraqry);
					if($fraCount==0)
					{  
					echo "Franchisee Not Available in tally central server";
					}
					else
					{
						
								if($vouchertype=='STOCK ITEM CLOSING BALANCE')
                                {
/*												
                                $salesdate=$opdate;
								$postop['franchiseecode']=$FRANCHISECODE;				
								$postop['closingdate']=$salesdate;
								$pccode=$VOUCHER->ALIASNAME;
								$postop['productcode']=$VOUCHER->ALIASNAME;
								$postop['productdescription']=$VOUCHER->STOCKITEMNAME;
								$postop['closingstock']=preg_replace('/[()]/', '', $VOUCHER->CLOSINGBALANCE);
								$postop['rate']=$VOUCHER->CLOSINGRATE;
							
								$pString = str_replace(",", "",$VOUCHER->CLOSINGVALUE);
																	$floatvalue=floatval($pString); 
																	if($floatvalue<0)
																	{
																	$floatvalue=(-1)*$floatvalue;
																	}	
								
								$postop['stockvalue'] =$floatvalue;
								
                                $mdate=date('Y-m-d');
								$postop['mdate'] =$mdate;
									
				if($pccode!='' && $opdate!='')
				{
				
				$optname	= "stockclosingreport";
				$result=mysql_query("SELECT * FROM stockclosingreport where closingdate='".$opdate."' and productcode ='".$pccode."' and franchiseecode ='".$FRANCHISECODE."'");
				$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);
				if($myrow1==0)	
				{
				$news->addNews($postop,$optname);
				$count19=$count19.$pccode."~";
				}	
				else
				{
				$whereconop= "closingdate='".$opdate."' and productcode ='".$postop['productcode']."' and franchiseecode ='".$postop['franchiseecode']."'";
				$news->editNews($postop,$optname,$whereconop);
				$count19=$count19.$pccode."~";			*/

                 $salesdate=$opdate;
								$postop['franchiseecode']=$FRANCHISECODE;				
								$postop['opdate']=$salesdate;
								$pccode=$VOUCHER->ALIASNAME;
								$postop['productcode']=$VOUCHER->ALIASNAME;
								$postop['productdescription']=$VOUCHER->STOCKITEMNAME;
								$postop['openstock']=preg_replace('/[()]/', '', $VOUCHER->CLOSINGBALANCE);
								$postop['rate']=$VOUCHER->CLOSINGRATE;
							
								$pString = str_replace(",", "",$VOUCHER->CLOSINGVALUE);
																	$floatvalue=floatval($pString); 
																	if($floatvalue<0)
																	{
																	$floatvalue=(-1)*$floatvalue;
																	}	
								
								$postop['stockvalue'] =$floatvalue;
								/* $mdate=NOW();
                                $mdate = strtotime($mdate); */
                                $mdate=date('Y-m-d');
								$postop['mdate'] =$mdate;
									
				if($pccode!='' && $opdate!='')
				{
				
				$optname	= "stockledgerreport";
				$result=mysql_query("SELECT * FROM stockledgerreport where opdate='".$opdate."' and productcode ='".$pccode."' and franchiseecode ='".$FRANCHISECODE."'");
				$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);
				if($myrow1==0)	
				{
				$news->addNews($postop,$optname);
				  $count19=$count19.$pccode."~";
				}	
				else
				{
				$whereconop= "opdate='".$opdate."' and productcode ='".$postop['productcode']."' and franchiseecode ='".$postop['franchiseecode']."'";
				$news->editNews($postop,$optname,$whereconop);
				 $count19=$count19.$pccode."~";
				 
				}	
				
				}
				  
			}							
		}		
		
			
		
	
	}
	
					}			
                    }
						$count=$count19.$count;
									
			
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
	//print ("<MASTERIDS>101</MASTERIDS> ");
		print ("</VOUCHERRESPONSES>");
		print ("</DATA>" ); 
		print ("</BODY>");
		print ("</ENVELOPE>");
		}
          
           
		  

?>

