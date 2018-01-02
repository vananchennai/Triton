 <!--First Block - Logo-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:130px; float:none;">
           <div style="width:250px; height:130px; float:left; background-image:url(../../img/logo_amaron.png);">
          
           </div>
  <!--userid and emp name Block - start-->
		<div style="width:350px; height:130px; float:right;">
        
                             <!--Row1 -->  
           <div style="width:340px; height:30px; float:left; margin-top:40px; margin-left:3px;" class="contval">
          <label><b>User :</b><? echo $_SESSION['username'].' ['.$_SESSION['employeeusername'].']';?></label>
            </div>
			
            <div style="width:340px; height:30px;  float:left;  margin-top:2px; margin-left:8px;" class="contval">
             <div id='csslogout'> <ul> <li>  <a href="/<? echo $_SESSION['mainfolder']; ?>/logout.php"><b>Logout</b></a> </li> </ul>  </div>
               </div>
         </div>
         <!--userid and emp name Block - End-->
     </div>       
</div>
<!--First Block - End-->

<!--Second Block - Menu-->
<div style="width:100%; height:50px; float:none; background:url(../../img/menubg.jpg) repeat-x;">
     <div style="width:980px; height:50px; float:none;"> 
      
    


 <div id='cssmenu'>
<ul>
   <li ><a href="/<? echo $_SESSION['mainfolder']; ?>/home/home/master1.php"><span>Home</span></a></li>
  
   <li class='has-sub '><a  href="#"><span>Master</span></a>
      <ul>
        <li class='has-sub '><a style="text-align:left" href='#'><span>Products</span></a>
         <ul>
		  <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/products/productgroupmaster.php">Product Group</a></li>
          <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/products/productuom.php">Product UOM</a></li>
          <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/products/productdetails.php">Product</a></li>
          <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/products/schememaster.php">Product Schemes</a></li>
         </ul>
		</li>
            
         <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/franchise/franchisemaster.php">Distributor</a></li>
		 
         <li  class="has-sub"><a style="text-align:left" href="#"><span>Price List</span></a>
            <ul>
			  <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/pricelist/pricelistmaster.php">Price List </a></li> 
              <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/pricelist/pricelistlinking.php">Pricelist Linking</a></li> 
            </ul>
		</li>
		<li  class="has-sub"><a style="text-align:left" href="#"><span>Retailer</span></a>
		 <ul>
		   <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/retailer/retailercategory.php">Retailer Category</a></li>
		   <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/retailer/retailermaster.php">Retailer</a></li>
		 </ul>
		</li>
            
            
             
             <!--<li><a href="/amararaja/master/customer/customermaster.php">Customer</a></li>-->  
            
             
              <li  class="has-sub"><a style="text-align:left" href="#"><span>Geographical Location </span></a>
         <ul> <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/address/countrymaster.php">Country</a></li>
              <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/address/regionmaster.php">Region</a></li>
              <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/address/statemaster.php">State</a></li>
              <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/address/branchmaster.php">Branch</a></li>
             </ul></li>   
      
     </ul></li>
   
              <!-- <li class="has-sub"><a href='#'><span> Transaction</span></a>
               <ul><li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/serialno/serialnumber.php">Tertiary Sales</a> </li>
              
              </ul></li> -->
  
     		  <li class="has-sub"><a href="#"><span>Reports</span></a>
      	<ul>
        


		 
<!--<li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/serialnumberreport.php">Serial Number History</a></li>-->
<li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/downloaduploadstatus.php">Data Exchange Status Report</a></li>

 <li  class="has-sub"><a style="text-align:left" href="#"><span>Purchase</span></a>
         <ul>
		     <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/PurchaseOrder.php">Purchase Order Report</a></li>
	         <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/PurchaseReport.php">Purchase Report</a></li>   
             <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/PurchaseSummary.php">Purchase Summary</a></li>  
             <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/PurchaseReturn.php">Purchase Returns</a></li>    
         </ul>    
          	 <li  class="has-sub"><a style="text-align:left" href="#"><span>Sales</span></a>
         <ul>
		     <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/SalesRegister.php">Sales Register</a></li>   
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/SalesReport.php">Sales Report</a></li>  
             <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/WeeklyReport.php">Weekly Report</a></li> 
             <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/rcdreport.php">Retailer Detailed Report</a></li>  
             <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/rcsreport.php">Retailer Summary Report</a></li>  
		     <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/SalesReturn.php">Sales Returns</a></li>
             <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/iwssreport.php">Item Wise Sales Summary Report</a></li>
	         <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/sswpwssreport.php">Sales Summary report</a></li>
	         <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/spwswsreport.php">Sub-Product Wise Stockist Wise Sales Report</a></li>
		     <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/zsssreport.php">Zone Wise State Wise Sales Summary</a></li>  
		     <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/dwreport.php">Division Wise Report</a></li>
		     <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/ssrreport.php">Sales with Sales Return Report</a></li>				 
         </ul>
		     <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/Stockledger.php">Stock Ledger</a></li>
             <li class="has-sub"><a style="text-align:left" href="#">Stock Report</a>
		  <ul>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/pprreport.php">Purchase with Purchase Return Report</a></li>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/lwssreport.php">Location Wise Stock Summary Report</a></li>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/dwcreport.php">Division Wise Closing Stock Report</a></li>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/ConsolidatedStockSummary.php">Consolidated Stock Summary</a></li>
		  </ul>
		      <li class="has-sub"><a style="text-align:left" href="#">Transaction Reports</a>
		  <ul>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/pwcwreport.php">Product Wise Category Wise Transaction Report</a></li>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/smcreport.php">Stockist Monthly Comparison Report</a></li>
		  </ul>
		     <li class="has-sub"><a style="text-align:left" href="#">Admin Reports</a>
		  <ul>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/monthlysyncdload.php">Month Wise Synch Status - Transaction Download</a></li>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/monthlysyncupload.php">Month Wise Synch Status - Master Upload</a></li>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/daywisedownload.php">Day Wise Synch Status - Transaction Download</a></li>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/daywiseupload.php">Day Wise Synch Status - Master Upload</a></li>
			 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/wlreport.php">Usage Web Log Report</a></li>
		  </ul>
			
         	 
             <!--<li  class="has-sub"><a style="text-align:left" href="#"><span>Service</span></a>
         <ul><li><a style="text-align:left" href="/<? /* echo $_SESSION['mainfolder']; */ ?>/master/reports/ServicecallRegister.php">Service Call Register</a></li> -->
<!--</ul> 
          	  <li  class="has-sub"><a style="text-align:left" href="#"><span>Warranty</span></a>
         <ul> -->
<!--<li><a style="text-align:left" href="/<? /* echo $_SESSION['mainfolder']; */ ?>/master/reports/WarrantyAdminScrap.php">Warranty Administration report</a></li>
         </ul> </li>-->
<li  class="has-sub"><a style="text-align:left" href="#"><span>GUI Reports</span></a>
         <ul><li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/franchise_PurchaseReport.php">Purchase - Franchisee Wise</a></li>   
	         <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/product_PurchaseReport.php">Purchase - Product Wise</a></li>  
             <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/franchise_SalesReport.php">Sales - Franchisee Wise</a></li> 
               <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/product_SalesReport.php">Sales - Product Wise</a></li>  
                 <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/reports/franchise_StockReport.php">Stock Opening & Closing </a></li>    
         </ul>
        </li>
         
		
	
         </ul></li>
   
   
             <li class="has-sub"><a href='#'><span>Data Exchange</span></a>
             <ul><li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/excelimport/products/Bulkupload.php">Import Masters</a></li>
            </ul></li>
    
   			<li class="has-sub"><a href='#'><span>Settings</span></a>
     		<ul><li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/Config/pagination.php">Configuration Settings</a></li>
   			<li  class="has-sub"><a style="text-align:left" href="#"><span>User Settings</span></a>
            <ul>
            <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/employee/employeemaster.php">Employee</a></li>
            <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/employee/truncate.php">Truncate</a></li>
            <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/usersettings/usercreation.php">User Creation</a></li>
            <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/usersettings/userrights.php">User Rights</a></li>
			<li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/usersettings/reportrights.php">Report Rights</a></li> 
            <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/usersettings/changepassword.php">Password Change</a></li>           
             </ul></li>
             <li><a style="text-align:left" href="/<? echo $_SESSION['mainfolder']; ?>/master/Config/about.php">About</a></li>
    		
    </ul></li>
    
   
</ul>
</div>
 </div>       
</div>
<!--Second Block - Menu -End -->

