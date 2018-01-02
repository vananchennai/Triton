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
global $response;

require_once 'Mysql.php'; // Include The News Class
	$news = new Mysql(); // Create a new News Object
	$newsRecordSet = $news->__construct();
	require_once 'masterclass.php';
	require_once 'weblog.php';
	$news = new News();
// case : if got a post string from binaryssss

if ( isset( $HTTP_RAW_POST_DATA ) )  
{ 
 /* if ( 1) 
{   */
/*$getdata="<ENVELOPE>
 <REQUEST>
  <NAME>VModel</NAME>
  <FRANCHISEECODE>1000818</FRANCHISEECODE>
  </REQUEST>
</ENVELOPE>";*/
//$xml_object = simplexml_load_string( $getdata );  
  /* $getdata="<ENVELOPE>
 <REQUEST>
  <NAME>Purchase</NAME>
  <FRANCHISEECODE>444444</FRANCHISEECODE>
 </REQUEST>
</ENVELOPE>"; */    
/* $getdata="<ENVELOPE>
 <REQUEST>
   <NAME>PurchaseStatus</NAME>
  <DOWNLOADEDPURCHASEVOUCHERS>PU-127~PU-121~</DOWNLOADEDPURCHASEVOUCHERS>
  <FRANCHISEECODE>444444</FRANCHISEECODE>
 </REQUEST>
</ENVELOPE>"; */
//$xml_object = simplexml_load_string( $getdata ); 
    
       
	$xml_object = simplexml_load_string( $HTTP_RAW_POST_DATA );      
					$request_person_name =$xml_object->REQUEST->NAME;
				$TestType =$xml_object->REQUEST->TYPE;
				$FRANCHISECODE =$xml_object->REQUEST->FRANCHISEECODE;
				$vouchersdowloaded=$xml_object->REQUEST->DOWNLOADEDPURCHASEVOUCHERS;
								$fraqry =mysql_query("select Franchisecode  from  franchisemaster where  Franchisecode='".$FRANCHISECODE."'"	);
								$fraCount=mysql_num_rows($fraqry);
									if($fraCount>0)
									{  
						
								global $masters,$mas;
																
	
			if($request_person_name=="Product Group is Created Successfully")
			{
			            $masters="Product Group";
						$taxtable4='productgroupupload'; 
						
						  $post1c4['Deliverydae']=date("Y-m-d");
						  //$post1c4['Franchiseecode']=$FRANCHISECODE;
						  $post1c4['Status']='2';
						$wherecon= "Franchiseecode ='".$FRANCHISECODE."' ";
						$news->editNews($post1c4,$taxtable4,$wherecon);
						
						uploadfun("productgroupupload","downloadstatus",$FRANCHISECODE,"product Group");
			}

			if($request_person_name=="Product UOM is Created Successfully")
			{
						$masters="Product UOM";
						$taxtable4='productuomupload'; 
						
						  $post1c4['Deliverydae']=date("Y-m-d");
						  //$post1c4['Franchiseecode']=$FRANCHISECODE;
						  $post1c4['Status']='2';
						$wherecon= "Franchiseecode ='".$FRANCHISECODE."' ";
						$news->editNews($post1c4,$taxtable4,$wherecon);
						uploadfun("productuomupload","downloadstatus",$FRANCHISECODE,$masters);
			}

			if($request_person_name=="Product Is Created Successfully")
			{
						$masters="Product Master";
						$taxtable5='productmasterupload';
                                                      
								$post1c5['Deliverydae']=date("Y-m-d");
								//$post1c5['Franchiseecode']=$FRANCHISECODE;
								$post1c5['Status']='2';
								$wherecon= "Franchiseecode ='".$FRANCHISECODE."'";
								$news->editNews($post1c5,$taxtable5,$wherecon);
								uploadfun("productmasterupload","downloadstatus",$FRANCHISECODE,$masters);	
			}
			if($request_person_name=="PriceList Is Inserted Successfully")
					{
						$masters="PriceList";
						$taxtable161='pricelistlinkinggrid';
					  //  $post1c16['Franchiseecode']=$FRANCHISECODE;
					   // $post1c16['Masters']=$masters;
						$post1c161['Status']='2';
					  // $post1c16['InsertDate']='2012/02/01';
						$post1c161['Deliverydae']=date("Y-m-d");
					  // echo "salesledgerreturn"   ;
					    $wherecon= "Franchisee ='".$FRANCHISECODE."' ";
						$news->editNews($post1c161,$taxtable161,$wherecon);
						uploadfun("pricelistlinkinggrid","downloadstatus",$FRANCHISECODE,$masters);
			}
			/* if($request_person_name=="Retailer Category Is Created Successfully")
			{
						$masters="Retailer Category";
						$taxtable9='retailercategoryupload';
						
						$post1c9['Deliverydae']=date("Y-m-d");
						//$post1c9['Franchiseecode']=$FRANCHISECODE;
						$post1c9['Status']='2';
						$wherecon= "Franchiseecode ='".$FRANCHISECODE."' ";
						$news->editNews($post1c9,$taxtable9,$wherecon);
						uploadfun("downloadstatus",$FRANCHISECODE,$masters);
			
			}
			if($request_person_name=="Retailer Is Created Successfully")
			{
						$masters="Retailer Master";
						$taxtable10='retailermasterupload';
						
						$post1c10['Deliverydae']=date("Y-m-d");
						//$post1c10['Franchiseecode']=$FRANCHISECODE;
						$post1c10['Status']='2';
						$wherecon= "Franchiseecode ='".$FRANCHISECODE."'";
						$news->editNews($post1c10,$taxtable10,$wherecon);
						uploadfun("downloadstatus",$FRANCHISECODE,$masters);
			}
 */
	/////RequestRetailer Is Created Successfully 
	
	if( $request_person_name=="PGroup")
	{

			// Download status
			$tax='downloadstatus';
			$masters="Product Group";
			$wherecon1= "franchisecode ='".$FRANCHISECODE."' and master='".$masters."'";
			$news->deleteNews($tax,$wherecon1);
			$post1['franchisecode']=$FRANCHISECODE;
			$post1['master']=$masters;
			$post1['date']=date("Y-m-d H:i:s");
			$post1['status']='Delivered';
			$news->addNews($post1,$tax);				
			$fraqry1 =mysql_query("select Franchiseecode  from  productgroupupload where  Franchiseecode='".$FRANCHISECODE."'"	);
			$fraCount1=mysql_num_rows($fraqry1);
			if($fraCount1>0)
			{ 	
										
							
					
			$result="SELECT * FROM productgroupmaster a WHERE   NOT EXISTS(SELECT  null FROM productgroupupload d WHERE d.Status=2 and d.Code= a.ProductCode and d.Franchiseecode='".$FRANCHISECODE."') ";
			
			$sql1 = mysql_query($result) or die (mysql_error());
			$productgrplist=null;
			$groCount=null;
			$groCount=mysql_num_rows($sql1);
			while($row = mysql_fetch_array($sql1))
			{
			$ProductCode=$row['ProductCode'];
			$ProductGroup=$row['ProductGroup'];
			$Parent=$row['Parent'];
			$productgrplist =$productgrplist.$ProductCode."!".$ProductGroup."!".$Parent."^";
			}
			//End of Product Group
				
			print ("<ENVELOPE>" );
			print ("<HEADER>" );
			print ("<VERSION>1</VERSION>"); 
			print ("<STATUS>1</STATUS>");
			print ("</HEADER>");
			print ("<BODY>"); 
			print ("<DATA>"); 
			print ("<PRODUCTGROUPHEAD>");
			print ("<COUNT>".  $groCount  ."</COUNT> ");
			print ("<PRODUCTGROUP>".  $productgrplist  ."</PRODUCTGROUP> ");
			print ("</PRODUCTGROUPHEAD>");
			print ("</DATA>" ); 
			print ("</BODY>");
			print ("</ENVELOPE>");
		
		
		/*$masters="product Group";
						
					 if($myrow1>0)
						{
						$post1c1['Deliverydae']=date("Y-m-d");
						$post1c1['Franchiseecode']=$FRANCHISECODE;
						$post1c1['Status']='1';
						$wherecon= "Franchiseecode ='".$FRANCHISECODE."' and Status =0";
						$taxtable1='productgroupupload';
						$news->editNews($post1c1,$taxtable1,$wherecon);
						}
				
						//Echo $masters ."  download Successfully";*/
		
		
		
		
	}		
	}

	// 4th Value 
	if($request_person_name=="PUOM")
	{
		
		//Download status
							$tax='downloadstatus';
							$wherecon1= "franchisecode ='".$FRANCHISECODE."' and master='".$masters."'";
							$news->deleteNews($tax,$wherecon1);
							$post1['franchisecode']=$FRANCHISECODE;
							$post1['master']=$request_person_name;
							$post1['date']=date("Y-m-d H:i:s");
							$post1['status']='Delivered';
							$news->addNews($post1,$tax);
		
		//Code for Product UOM
		$resultuom="SELECT * FROM productuom  a WHERE   NOT EXISTS(SELECT  null FROM productuomupload d WHERE d.Status=2 and d.Code=a.productuomcode and d.Franchiseecode='".$FRANCHISECODE."')";
		$sqluom= mysql_query($resultuom) or die (mysql_error());
		$productuomlist=null;
		$uomCount=null;
		$uomCount=mysql_num_rows($sqluom);

		while($rowuom = mysql_fetch_array($sqluom))
		{
		$productuom=$rowuom['productuomcode'];
		$productuom1=$rowuom['productuom'];
		$productuomlist =$productuomlist.$productuom."!".$productuom1."^";
		}
		//End of Product UOM
		print ("<ENVELOPE>" );
		print ("<HEADER>" );
		print ("<VERSION>1</VERSION>"); 
		print ("<STATUS>1</STATUS>");
		print ("</HEADER>");
		print ("<BODY>"); 
		print ("<DATA>"); 
		print ("<PRODUCTUOMHEAD>");
		print ("<COUNT>".  $uomCount  ."</COUNT> ");
		print ("<PRODUCTUOM>".  $productuomlist  ."</PRODUCTUOM> ");
		print ("</PRODUCTUOMHEAD>");
		print ("</DATA>" ); 
		print ("</BODY>");
		print ("</ENVELOPE>");
								
	}
	
	// 5th Value 
	if($request_person_name=="Product")
	{
		
		//Download status
							$tax='downloadstatus';
							$wherecon1= "franchisecode ='".$FRANCHISECODE."' and master='".$masters."'";
							$news->deleteNews($tax,$wherecon1);
							$post1['franchisecode']=$FRANCHISECODE;
							$post1['master']=$request_person_name;
							$post1['date']=date("Y-m-d H:i:s");
							$post1['status']='Delivered';
							$news->addNews($post1,$tax);
		
		//Code for Product Details
		//$resultproduct="SELECT * FROM productmaster_view  a WHERE   NOT EXISTS(SELECT  null FROM productmasterupload d WHERE d.Status=2 and d.Code=a.ProductCode and d.Franchiseecode='".$FRANCHISECODE."')";
                $resultproduct="SELECT a.* FROM productmaster_view  a left join productmasterupload d on d.Code=a.ProductCode WHERE d.Status!=2 and d.Franchiseecode='".$FRANCHISECODE."'";
		$sqlproduct= mysql_query($resultproduct) or die (mysql_error());
		$productproductlist=null;
		$prtCount=null;
		$prtCount=mysql_num_rows($sqlproduct);
		
		while($rowproduct = mysql_fetch_array($sqlproduct))
		{
		$ProductCode=$rowproduct['ProductCode'];
		$ProductDescription=$rowproduct['ProductDescription'];
		$ProductGroupCode=$rowproduct['ProductGroupCode'];
		//$warrantyapplicable=$rowproduct['warrantyapplicable'];
		//$EnableSerialno=$rowproduct['EnableSerialno'];
		//$ServiceCompensation=$rowproduct['ServiceCompensation'];
		//$IdentificationCode=$rowproduct['IdentificationCode'];
		//$serialnodigits=$rowproduct['serialnodigits'];
		//$salestype=$rowproduct['salestype'];
		//$Status=$rowproduct['Status'];
		$UOM=$rowproduct['productuom'];
		
		 /* $resultproduct1="SELECT IdentificationCode,EnableSerialno,logic FROM masterproduct   WHERE  ProductCode='".$ProductCode."'";
		$sqlproduct1= mysql_query($resultproduct1) or die (mysql_error());
		$productproductlist1=null;
		$prtCount1=null;
		$prtCount1=mysql_num_rows($sqlproduct1);
		$productproductlist2=null;
		while($rowproduct1 = mysql_fetch_array($sqlproduct1))
		{
		$IdentificationCode=$rowproduct1['IdentificationCode'];
		$serialnodigits=$rowproduct1['EnableSerialno'];
		$serialnologic=$rowproduct1['logic'];
		//$productproductlist1 =$productproductlist1.$IdentificationCode."|";
		//$productproductlist2=$productproductlist2.$serialnodigits."|";;
		$productproductlist1 =$productproductlist1.$IdentificationCode."|".$serialnodigits."|".$serialnologic."%";
		} */
		
		
		//$productproductlist =$productproductlist.$ProductCode."!".$ProductDescription."!".$ProductType."!".$warrantyapplicable."!".$Status."!".$UOM."!".$productproductlist1."!".$productproductlist2."^";
		$productproductlist =$productproductlist.$ProductCode."!".$ProductDescription."!".$ProductGroupCode."!".$UOM."^";
		
		}
		//End of Product Details
	  
		/*
		$response=NULL;
		$response.="<ENVELOPE>" ;
		$response.="<HEADER>" ;
		$response.="<VERSION>1</VERSION>"; 
		$response.="<STATUS>1</STATUS>";
		$response.="</HEADER>";
		$response.="<BODY>"; 
		$response.="<DATA>"; 
		$response.="<PRODUCTDETAILSHEAD>";
		$response.="<COUNT>".  $prtCount  ."</COUNT> ";
		$response.="<PRODUCTDETAILS>".  $productproductlist.  "</PRODUCTDETAILS> ";
		$response.="</PRODUCTDETAILSHEAD>";
		$response.="</DATA>"; 
		$response.="</BODY>";
		$response.="</ENVELOPE>";
		header("Content-Length:".strlen($response));
		print ($response);
		//var_dump($response);
		*/
                        print ("<ENVELOPE>" );
			print ("<HEADER>" );
			print ("<VERSION>1</VERSION>"); 
			print ("<STATUS>1</STATUS>");
			print ("</HEADER>");
			print ("<BODY>"); 
			print ("<DATA>"); 
			print ("<PRODUCTDETAILSHEAD>");
			print ("<COUNT>".  $prtCount  ."</COUNT> ");
			print ("<PRODUCTDETAILS>".  $productproductlist  ."</PRODUCTDETAILS> ");
			print ("</PRODUCTDETAILSHEAD>");
			print ("</DATA>" ); 
			print ("</BODY>");
			print ("</ENVELOPE>");

	}
	
	// 7th Value 
	if($request_person_name=="PMapping")
	{
		//Download status
							$tax='downloadstatus';
							$wherecon1= "franchisecode ='".$FRANCHISECODE."' and master='".$masters."'";
							$news->deleteNews($tax,$wherecon1);
							$post1['franchisecode']=$FRANCHISECODE;
							$post1['master']=$request_person_name;
							$post1['date']=date("Y-m-d H:i:s");
							$post1['status']='Delivered';
							$news->addNews($post1,$tax);
		
		
		//Code for Product Mapping
		 //$resultMapping="SELECT * FROM productmapping ";
		//$resultMapping="SELECT productmaster.ProductDescription,productmapping.MapProductCode,productmapping.effectivedate FROM productmapping left join productmaster on productmapping.ProductCode=productmaster.ProductCode";
		$resultMapping="SELECT * FROM productmapping_view where  Franchiseecode='".$FRANCHISECODE."' and Status!='2' ";
		
		$sqlMapping= mysql_query($resultMapping) or die (mysql_error());
		$productMappinglist=null;
		$mapCount=null;
		$mapCount=mysql_num_rows($sqlMapping);
		
		while($rowMapping = mysql_fetch_array($sqlMapping))
		{
		$ProductCode=$rowMapping['ProductDescription'];
		$MapProductCode=$rowMapping['MapProductCode'];
		$effectivedate=$rowMapping['effectivedate'];
		$Status=$rowMapping['pstatus'];
		$dates1 = explode('-', $effectivedate);
		$AppliDate1=$dates1[2]."-".$dates1[1]."-".$dates1[0];
			
		$productMappinglist =$productMappinglist.$ProductCode."!".$MapProductCode."!".$AppliDate1."!".$Status."^";
		}
		//End of Product Mapping
		$response=NULL;
		$response.="<ENVELOPE>" ;
		$response.="<HEADER>" ;
		$response.="<VERSION>1</VERSION>"; 
		$response.="<STATUS>1</STATUS>";
		$response.="</HEADER>";
		$response.="<BODY>"; 
		$response.="<DATA>"; 
		$response.="<PRODUCTMAPPINGHEAD>";
		$response.="<COUNT>".  $mapCount  ."</COUNT> ";
		$response.="<PRODUCTMAPPING>".  $productMappinglist  ."</PRODUCTMAPPING> ";
		$response.="</PRODUCTMAPPINGHEAD>";
		$response.="</DATA>" ; 
		$response.="</BODY>";
		$response.="</ENVELOPE>";
		header("Content-Length:".strlen($response));
		print ($response);
	}
	
	// 9th Value 
	if($request_person_name=="PriceList")
	{
							$tax='downloadstatus';
							$wherecon1= "franchisecode ='".$FRANCHISECODE."' and master='".$masters."'";
							$news->deleteNews($tax,$wherecon1);
							$post1['franchisecode']=$FRANCHISECODE;
							$post1['master']=$request_person_name;
							$post1['date']=date("Y-m-d H:i:s");
							$post1['status']='Delivered';
							$news->addNews($post1,$tax);
		
		//Code for PriceList
		
		$xml_object = simplexml_load_string( $HTTP_RAW_POST_DATA );      
		$frachisename =$xml_object->REQUEST->FRANCHISEECODE;
		//$frachisename='1000786';
		//echo "selva";
		$pricelist=NULL;
		$fpricelist=NULL;
		$rpricelist=NULL;
		$ipricelist=NULL;
		$mrplist=NULL;
		$pricelistquery="SELECT effectivedate FROM pricelistlinkinggrid_view WHERE  `effectivedate` <=CURDATE() and `Franchisee`='".$frachisename."' and Status!='2' ORDER BY effectivedate DESC  LIMIT 1";
		
		$pricelistsql = mysql_query($pricelistquery) or die (mysql_error());
		$pricelistrowcount = mysql_num_rows($pricelistsql);
		if($pricelistrowcount > 0)
		{
			$row = mysql_fetch_array($pricelistsql);
			$effectivedate=$row['effectivedate'];
			//echo $effectivedate;
			$pricelistquery="SELECT `PriceListCode`,`productdescription`,`effectivedate`,`applicabledate`,`fprice`,`rprice`,`mrp`,`iprice` FROM  `pricelistlinkinggrid_view` WHERE  `Branch` !=  '' and Franchisee='".$frachisename."' and effectivedate='".$effectivedate."'  and Status!='2' order by effectivedate DESC";
			$pricelistsql = mysql_query($pricelistquery) or die (mysql_error());
			$pricelistrowcount = mysql_num_rows($pricelistsql);
			if($pricelistrowcount > 0)
			{
				while($row = mysql_fetch_array($pricelistsql))
				{
						$PriceListCode=$row['PriceListCode'];
						$productcode=$row['productdescription'];
						$effectivedateintial=$row['effectivedate'];
						$effectivedatemiddle = strtotime($effectivedateintial);
						$effectivedate = date("d/m/Y", $effectivedatemiddle);
						$applicabledateintial=$row['applicabledate'];
						$effectivedatemiddle = strtotime($applicabledateintial);
						$applicabledate = date("d/m/Y", $effectivedatemiddle);
						$fprice=$row['fprice'];
						$rprice=$row['rprice'];
						$mrp=$row['mrp'];
						$iprice=$row['iprice'];
						/*$fpricelist =$fpricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$fprice."^";
						$rpricelist =$rpricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$rprice."^";
						$ipricelist =$ipricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$iprice."^";
						$mrplist =$mrplist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$mrp."^";*/
						
						$fpricelist =$fpricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$fprice."^";
						$rpricelist =$rpricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$rprice."^";
						$ipricelist =$ipricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$iprice."^";
						$mrplist =$mrplist.$productcode."!".$effectivedate."!".$applicabledate."!".$mrp."^";
						
				}
			}
			else
			{
				$pricelistquery="SELECT `PriceListCode`,`productdescription`,`effectivedate`,`applicabledate`,`fprice`,`rprice`,`mrp`,`iprice` FROM  `pricelistlinkinggrid_view` WHERE  `State` !=  ''  and Franchisee='".$frachisename."'  and effectivedate='".$effectivedate."'  and Status!='2' order by effectivedate DESC";
				$pricelistsql = mysql_query($pricelistquery) or die (mysql_error());
				$pricelistrowcount = mysql_num_rows($pricelistsql);
				if($pricelistrowcount > 0)
				{
					while($row = mysql_fetch_array($pricelistsql))
					{
							$PriceListCode=$row['PriceListCode'];
							$productcode=$row['productdescription'];
							$effectivedateintial=$row['effectivedate'];
							$effectivedatemiddle = strtotime($effectivedateintial);
							$effectivedate = date("d/m/Y", $effectivedatemiddle);
							$applicabledateintial=$row['applicabledate'];
							$effectivedatemiddle = strtotime($applicabledateintial);
							$applicabledate = date("d/m/Y", $effectivedatemiddle);
							$fprice=$row['fprice'];
							$rprice=$row['rprice'];
							$mrp=$row['mrp'];
							$iprice=$row['iprice'];
							/*$fpricelist =$fpricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$fprice."^";
							$rpricelist =$rpricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$rprice."^";
							$ipricelist =$ipricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$iprice."^";
							$mrplist =$mrplist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$mrp."^";*/
							
							$fpricelist =$fpricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$fprice."^";
							$rpricelist =$rpricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$rprice."^";
							$ipricelist =$ipricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$iprice."^";
							$mrplist =$mrplist.$productcode."!".$effectivedate."!".$applicabledate."!".$mrp."^";
					}
				}
				else
				{
					$pricelistquery="SELECT `PriceListCode`,`productdescription`,`effectivedate`,`applicabledate`,`fprice`,`rprice`,`mrp`,`iprice` FROM  `pricelistlinkinggrid_view` WHERE   Franchisee='".$frachisename."'  and effectivedate='".$effectivedate."' and Status!='2' order by effectivedate DESC";
					$pricelistsql = mysql_query($pricelistquery) or die (mysql_error());
					$pricelistrowcount = mysql_num_rows($pricelistsql);
					if($pricelistrowcount > 0)
					{
						while($row = mysql_fetch_array($pricelistsql))
						{
								$PriceListCode=$row['PriceListCode'];
								$productcode=$row['productdescription'];
								$effectivedateintial=$row['effectivedate'];
								$effectivedatemiddle = strtotime($effectivedateintial);
								$effectivedate = date("d/m/Y", $effectivedatemiddle);
								$applicabledateintial=$row['applicabledate'];
								$effectivedatemiddle = strtotime($applicabledateintial);
								$applicabledate = date("d/m/Y", $effectivedatemiddle);
								$fprice=$row['fprice'];
								$rprice=$row['rprice'];
								$mrp=$row['mrp'];
								$iprice=$row['iprice'];
								/*$fpricelist =$fpricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$fprice."^";
								$rpricelist =$rpricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$rprice."^";
								$ipricelist =$ipricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$iprice."^";
								$mrplist =$mrplist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$mrp."^";*/
								
								$fpricelist =$fpricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$fprice."^";
								$rpricelist =$rpricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$rprice."^";
								$ipricelist =$ipricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$iprice."^";
								$mrplist =$mrplist.$productcode."!".$effectivedate."!".$applicabledate."!".$mrp."^";
						}
					}
					else
					{
						$pricelistquery="SELECT `PriceListCode`,`productdescription`,`effectivedate`,`applicabledate`,`fprice`,`rprice`,`mrp`,`iprice` FROM  `pricelistlinkinggrid_view` WHERE Franchisee='".$frachisename."'  and effectivedate='".$effectivedate."' and Status!='2' order by effectivedate DESC";
						$pricelistsql = mysql_query($pricelistquery) or die (mysql_error());
						$pricelistrowcount = mysql_num_rows($pricelistsql);
						if($pricelistrowcount > 0)
						{
							while($row = mysql_fetch_array($pricelistsql))
							{
									$PriceListCode=$row['PriceListCode'];
									$productcode=$row['productdescription'];
									$effectivedateintial=$row['effectivedate'];
									$effectivedatemiddle = strtotime($effectivedateintial);
									$effectivedate = date("d/m/Y", $effectivedatemiddle);
									$applicabledateintial=$row['applicabledate'];
									$effectivedatemiddle = strtotime($applicabledateintial);
									$applicabledate = date("d/m/Y", $effectivedatemiddle);
									$fprice=$row['fprice'];
									$rprice=$row['rprice'];
									$mrp=$row['mrp'];
									$iprice=$row['iprice'];
									/*$fpricelist =$fpricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$fprice."^";
									$rpricelist =$rpricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$rprice."^";
									$ipricelist =$ipricelist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$iprice."^";
									$mrplist =$mrplist.$PriceListCode."!".$productcode."!".$effectivedate."!".$applicabledate."!".$mrp."^";*/
									
									$fpricelist =$fpricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$fprice."^";
									$rpricelist =$rpricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$rprice."^";
									$ipricelist =$ipricelist.$productcode."!".$effectivedate."!".$applicabledate."!".$iprice."^";
									$mrplist =$mrplist.$productcode."!".$effectivedate."!".$applicabledate."!".$mrp."^";
							}
						}
					}
				}
			}
		}
		
		$response=NULL;
		$response.="<ENVELOPE>";
		$response.="<HEADER>";
		$response.="<VERSION>1</VERSION>"; 
		$response.="<STATUS>1</STATUS>";
		$response.="</HEADER>";
		$response.="<BODY>"; 
		$response.="<DATA>"; 
		$response.="<COUNT>". $pricelistrowcount  . "</COUNT>";
		$response.="<franchiseehead>";
		$response.="<FranchiseePrice>".$fpricelist."</FranchiseePrice>";
		$response.="</franchiseehead>";
		$response.="<RetailerPricehead>";
		$response.="<RetailerPrice>".$rpricelist."</RetailerPrice>";
		$response.="</RetailerPricehead>";
		$response.="<Mrphead>";
		$response.="<Mrprice>".$mrplist."</Mrprice>";
		$response.="</Mrphead>";
		$response.="<Institutionalhead>";
		$response.="<InstitutionalPrice>".$ipricelist."</InstitutionalPrice>";
		$response.="</Institutionalhead>";	
		
		$response.="</DATA>"; 
		$response.="</BODY>";
		$response.="</ENVELOPE>";
		header("Content-Length:".strlen($response));
		print ($response);
	
		//End of PriceList
		
	}
	
	
	/* // 10th Value 
	if($request_person_name=="RCategory")
	{
	
							$tax='downloadstatus';
							$wherecon1= "franchisecode ='".$FRANCHISECODE."' and master='".$masters."'";
							$news->deleteNews($tax,$wherecon1);
							$post1['franchisecode']=$FRANCHISECODE;
							$post1['master']=$request_person_name;
							$post1['date']=date("Y-m-d H:i:s");
							$post1['status']='Delivered';
							$news->addNews($post1,$tax);
		//Code for Retailer Category
		$resultRCat="SELECT * FROM retailercategory  a WHERE  NOT EXISTS(SELECT  null FROM retailercategoryupload d WHERE Status=2 and d.Code=a.CategoryCode and d.Franchiseecode='".$FRANCHISECODE."')";
		$sqlRCat= mysql_query($resultRCat) or die (mysql_error());
		$productRCatlist=null;
		$RCatCount=null;
		$RCatCount=mysql_num_rows($sqlRCat);
		
		while($rowRCat = mysql_fetch_array($sqlRCat))
		{
		$RetailerCategory=$rowRCat['RetailerCategory'];
		$RetailerCategorycode=$rowRCat['CategoryCode'];
		$productRCatlist =$productRCatlist.$RetailerCategorycode."!".$RetailerCategory."^";
		}
		//End of Retailer Category
		$response=NULL;
		$response.="<ENVELOPE>" ;
		$response.="<HEADER>" ;
		$response.="<VERSION>1</VERSION>"; 
		$response.="<STATUS>1</STATUS>";
		$response.="</HEADER>";
		$response.="<BODY>"; 
		$response.="<DATA>"; 
		$response.="<PRODUCTVMAKEHEAD>";
		$response.="<COUNT>".  $RCatCount  ."</COUNT> ";
		$response.="<PRODUCTVMAKE>".  $productRCatlist  ."</PRODUCTVMAKE> ";
		$response.="</PRODUCTVMAKEHEAD>";
		$response.="</DATA>" ; 
		$response.="</BODY>";
		$response.="</ENVELOPE>";
	 header("Content-Length:".strlen($response));
		print ($response);
		
		
	}
	 */
	
	// 11th Value 
	/* if($request_person_name=="Retailer")
	{
									
		//Code for Retailer
		$resultRetailer="SELECT * FROM retailermaster  a WHERE  a.fmexecutive='".$FRANCHISECODE."'and   NOT EXISTS(SELECT  null FROM retailermasterupload d WHERE Status=2 and d.Code=a.RetailerCode and d.Franchiseecode='".$FRANCHISECODE."')";
		//echo $resultRetailer;
		$sqlRetailer = mysql_query($resultRetailer) or die (mysql_error());
		$Retailerlist=null;
		$retCount=null;
		$retCount=mysql_num_rows($sqlRetailer);
		
		while($rowRetailer = mysql_fetch_array($sqlRetailer))
		{
		$RetailerCode=$rowRetailer['RetailerCode'];
		$RetailerName=$rowRetailer['RetailerName'];
		$Address=$rowRetailer['Address'];
		$City=$rowRetailer['City'];
		$Districtname=$rowRetailer['Districtname'];
		$FranchiseeMarketing=$rowRetailer['fmexecutive'];
		$Category=$rowRetailer['Category'];
		$ContactName=$rowRetailer['ContactName'];
		$ContactNo=$rowRetailer['ContactNo'];
		$CreditDays=$rowRetailer['CreditDays'];
		$CreditLimit=$rowRetailer['CreditLimit'];
		$TinNo=$rowRetailer['TinNo'];
		$TinDate=$rowRetailer['TinDate'];
		$Retailerlist =$Retailerlist.$RetailerName."!".$RetailerCode."!".$Category."!".$CreditDays."!".$CreditLimit."!".$Address."!".$ContactName."!".$ContactNo."!".$City."!".$Districtname."!".$TinNo."!".$TinDate."!".$FranchiseeMarketing."^";
	   // $Retailerlist =$Retailerlist.$RetailerCode."!".$RetailerName."!".$Address."!".$City."!".$Districtname."!".$FranchiseeMarketing."!".$Category."!".$ContactName."!".$ContactNo."!".$CreditDays."!".$CreditLimit."!".$TinNo."!".$TinDate."^";
		}
		//End of Retailer
	$response=NULL;
		$response.="<ENVELOPE>" ;
		$response.="<HEADER>" ;
		$response.="<VERSION>1</VERSION>"; 
		$response.="<STATUS>1</STATUS>";
		$response.="</HEADER>";
		$response.="<BODY>"; 
		$response.="<DATA>"; 
		$response.="<RETAILERHEAD>";
		$response.="<COUNT>".  $retCount  ."</COUNT> ";
		$response.="<RETAILER>".  $Retailerlist  ."</RETAILER> ";
		$response.="</RETAILERHEAD>";
		$response.="</DATA>" ; 
		$response.="</BODY>";
		$response.="</ENVELOPE>";
		header("Content-Length:".strlen($response));
		print ($response);	        
	} */
	
	// 14th Value 
	
	/* if($request_person_name=="Scheme")
	{
						$tax='downloadstatus';
							$wherecon1= "franchisecode ='".$FRANCHISECODE."' and master='".$masters."'";
							$news->deleteNews($tax,$wherecon1);
							$post1['franchisecode']=$FRANCHISECODE;
							$post1['master']=$request_person_name;
							$post1['date']=date("Y-m-d H:i:s");
							$post1['status']='Delivered';
							$news->addNews($post1,$tax);
		
		//Code for Vehicle Model
		$productschemelist='';
		
		$resultscheme="SELECT * FROM schememaster a WHERE   NOT EXISTS(SELECT  null FROM schememasterupload d WHERE Status=2 and d.Code=a.schemecode and d.Franchiseecode='".$FRANCHISECODE."')";
		$sqlscheme= mysql_query($resultscheme) or die (mysql_error());
		$schemelist=null;
		$schemeCount=null;
		$schemeCount=mysql_num_rows($sqlscheme);
		
		while($rowVModel = mysql_fetch_array($sqlscheme))
		{
		$sqlschemecode=$rowVModel['schemecode'];
		$sqlschemename=$rowVModel['schemename'];
		$schemestatus=$rowVModel['schemestatus'];
		$schemeeffectivedate=$rowVModel['effectivedate'];
		$schemeeffectivedate= strtotime($schemeeffectivedate);
		$schemeeffectivedate = date("d/m/Y",$schemeeffectivedate);
		$schemetypes=$rowVModel['schemetype'];
		$productschemelist =$productschemelist.$sqlschemecode."!".$sqlschemename."!".$schemestatus."!".$schemeeffectivedate."!".$schemetypes."^";
		}
		//End of Vehicle Model
		$response=NULL;
		$response.="<ENVELOPE>" ;
		$response.="<HEADER>" ;
		$response.="<VERSION>1</VERSION>"; 
		$response.="<STATUS>1</STATUS>";
		$response.="</HEADER>";
		$response.="<BODY>"; 
		$response.="<DATA>"; 
		$response.="<PRODUCTVMODELHEAD>";
		$response.="<COUNT>".  $schemeCount  ."</COUNT> ";
		$response.="<PRODUCTVMODEL>".  $productschemelist  ."</PRODUCTVMODEL> ";
		$response.="</PRODUCTVMODELHEAD>";
		$response.="</DATA>" ; 
		$response.="</BODY>";
		$response.="</ENVELOPE>";
		header("Content-Length:".strlen($response));
		print ($response);				    
	} */



if($request_person_name=="Purchase")
{
                            /* $tax='downloadstatus';
							$wherecon1= "franchisecode ='".$FRANCHISECODE."' and master='".$masters."'";
							$news->deleteNews($tax,$wherecon1);
							$post1['franchisecode']=$FRANCHISECODE;
							$post1['master']=$request_person_name;
							$post1['date']=date("Y-m-d H:i:s");
							$post1['status']='Delivered';
							$news->addNews($post1,$tax); */
		
							//Code for Purchase Voucher 
                            $resultpurchase="SELECT Distinct(FINVNO),FINVNO,FINVDT,PONO,PODT,PLANT,Status FROM ztally_invoice  where DCODE='".$FRANCHISECODE."' and Status=0";
							$sqlpurchase= mysql_query($resultpurchase) or die (mysql_error());
							$purchasedetailslist=null;
							$prtCount=null;
							$prtCount=mysql_num_rows($sqlpurchase);
							
							while($rowpurchase = mysql_fetch_array($sqlpurchase))
							{
							$PurchaseNumber=$rowpurchase['FINVNO'];
							$PurchaseDates=$rowpurchase['FINVDT'];
							$PurchaseDatess=strtotime($PurchaseDates);
							$PurchaseDate=date("d/m/Y",$PurchaseDatess);
							$pono=$rowpurchase['PONO'];
							$podates=$rowpurchase['PODT'];
							$podatess = strtotime($podates);
							$podate = date("d/m/Y", $podatess);
							//$product=$rowpurchase['MATNR'];
							$partyname=$rowpurchase['PLANT'];
							//$Quantity=$rowpurchase['QTY'];
/* 							$PurchaseNumber=$rowpurchase['PurchaseNumber'];
							$referenceno=$rowpurchase['referenceno'];
							$Purchasedate=$rowpurchase['Purchasedate'];
							$TotalPurchaseAmt=$rowpurchase['TotalPurchaseAmt']; */
							
 							$resultproduct="SELECT * FROM ztally_invoice where FINVNO='".$PurchaseNumber."' and DCODE='".$FRANCHISECODE."' and Status=0";
							$sqlproduct= mysql_query($resultproduct) or die (mysql_error());
							$productdetailslist=null;
							$prodCount=null;
							$prodCount=mysql_num_rows($sqlproduct); 
						   while($rowproduct = mysql_fetch_array($sqlproduct))
							{
							$ProductCode=$rowproduct['MATNR'];
                                                        $finvsno=$rowproduct['FINVSNO'];
							$Quantity=$rowproduct['QTY'];
							/* $Rate=$rowproduct['Rate'];
							$Amount=$rowproduct['Amount']; */
							$productdetailslist=$productdetailslist.$ProductCode."*".$finvsno."*".$Quantity."~";
							} 
							
							
							$purchasedetailslist =$purchasedetailslist.$PurchaseNumber."!".$PurchaseDate."!".$pono."!".$podate."!".$partyname."!".$productdetailslist."^";							
							}
								print ("<ENVELOPE>" );
								print ("<HEADER>" );
								print ("<VERSION>1</VERSION>"); 
								print ("<STATUS>1</STATUS>");
								print ("</HEADER>");
								print ("<BODY>"); 
								print ("<DATA>"); 
								print ("<PURCHASEDETAILSHEAD>");
								print ("<COUNT>".  $prtCount  ."</COUNT> ");
								print ("<PURCHASEDETAILS>".  $purchasedetailslist."</PURCHASEDETAILS> ");
								print ("</PURCHASEDETAILSHEAD>");
								print ("</DATA>" ); 
								print ("</BODY>");
								print ("</ENVELOPE>");
}

			if($request_person_name=="Purchase Invoice is Created Successfully")
			{
			            $masters="Purchase(GRN)";
						$taxtable4='ztally_invoice'; 
						date_default_timezone_set ("Asia/Calcutta");
						$post1c4['DeliveryDate']=date("Y-m-d H:i:s");
						//$post1c4['Franchiseecode']=$FRANCHISECODE;
						$post1c4['Status']='2';
						$wherecon= "DCODE ='".$FRANCHISECODE."' ";
						$news->editNews($post1c4,$taxtable4,$wherecon);
						$logtable='downloadstatus';
						$insertval['franchisecode']=$FRANCHISECODE;
						$insertval['master']=$masters;
						$insertval['date']=date("Y-m-d H:i:s",time());
						$insertval['status']='Delivered';
						$news->addNews($insertval,$logtable);
			}
			
			if($request_person_name=="PurchaseStatus")
			{
			            $masters="Purchase(GRN)";
						$taxtable4='ztally_invoice'; 
						date_default_timezone_set ("Asia/Calcutta");
						$post1c4['DeliveryDate']=date("Y-m-d H:i:s");
						//$post1c4['Franchiseecode']=$FRANCHISECODE;
						$post1c4['Status']='0';
						$vnoarray=array();
						$vnoarray=Explode("~",$vouchersdowloaded);
						//print_r(array_values($vnoarray));
						foreach($vnoarray as $arvalue)
						{
						$wherecon2 .= "'".$arvalue."'".",";
						}
						//echo $wherecon2;
						$wherecon3=substr_replace($wherecon2,"", -4);
					
						//echo $vouchersdowloaded;
						$wherecon= "DCODE ='".$FRANCHISECODE."' and FINVNO IN (".$wherecon3.")";
						$news->editNews($post1c4,$taxtable4,$wherecon);
						$logtable='downloadstatus';
						$insertval['franchisecode']=$FRANCHISECODE;
						$insertval['master']=$masters;
						$insertval['date']=date("Y-m-d H:i:s",time());
						$insertval['status']='Delivered';
						$news->addNews($insertval,$logtable);
			}
			
}
}
?>		
	
