<?php
		include '../../functions.php';// Include database connection and functions here.
		sec_session_start();
		require_once '../../masterclass.php';
		include("../../header.php");
		if(login_check($mysqli) == false) {
		header('Location:../../index.php');// Redirect to login page!
		} else
		{
?>

<title>SSV || Setting</title>
</head>
 <body class="default"><center>

<?php include("../../menu.php") ?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
			<form method="POST" name="form1" action="<?php $_PHP_SELF ?>">
            <div style="width:930px; height:auto;   min-height: 180px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">

                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>About</p>
						</div>
            <? 
              $abt_qry = mysql_query("select * from about order by last_update desc limit 1");
              $myrow1 = mysql_num_rows($abt_qry);
              if($myrow1 == 0){
                echo '<table><tr class="no_records"><td colspan="111" align="center">No Records Found</td></tr></table>';
              }else{
               while( $record = mysql_fetch_array($abt_qry)){
                $Product = $record['product_name'];
                $developed = $record['developed_by'];
                $release = $record['release_version'];
                $build = $record['build'];
                $last_update = $record['last_update'];
               }
              }
            ?>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:500px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Product</label>
                               </div>
                               <div style="height:30px; float:left; margin-top:5px; margin-left:3px;">
                               <label>:</label>
                               </div>

                              <div style="width:250px; height:30px;  float:left;  margin-top:5px; margin-left:20px;">
							  <label><b><? echo $Product; ?></b></label>
                               <!--<span style="font-weight:300"><? /* echo $Product; */?></span> -->
                               </div>
 							<!--Row1 end-->
                            <div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Developed by</label>
                               </div>
                               <div style="height:30px; float:left; margin-top:5px; margin-left:3px;">
                               <label>:</label>
                               </div>
                              <div style="width:250px; height:30px;  float:left;  margin-top:10px; margin-left:-18px;">
                               <a href="http://mazeworkssolutions.com/" target="_blank"><? echo $developed; ?></a>
                                
                               </div>
							       
                            <div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Release Version</label>
                               </div>
                               <div style="height:30px; float:left; margin-top:5px; margin-left:3px;">
                               <label>:</label>
                               </div>

                              <div style="width:250px; height:30px;  float:left;  margin-top:5px; margin-left:20px;">
                                  <label><? echo $release; ?></label>
                               </div>
                                <div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Build</label>
                               </div>
                               <div style="height:30px; float:left; margin-top:5px; margin-left:3px;">
                               <label>:</label>
                               </div>
                               
                              <div style="width:250px; height:30px;  float:left;  margin-top:5px; margin-left:20px;">
                                  <label><? echo $build;  ?></label>
                               </div>

							<div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Last Updated On</label>
                               </div>
                              <div style="height:30px; float:left; margin-top:5px; margin-left:3px;">
                               <label>:</label>
                               </div>

                              <div style="width:250px; height:30px;  float:left;  margin-top:5px; margin-left:20px;">
                                 <label><? echo $last_update;?></label>
                               </div>
                                  </div>                             
                     <!-- col1 end --> 
                     
                     
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                     <!--Main row 2 start-->
              
                 <!--Main row 2 end-->
            
             <!-- form id start end-->  
</form>			 
          </div> 
          
     </div>       
</div>
<!--Third Block - Menu -Container -->


<!--Footer Block -->
<div id="footer-wrap1">
        <?php include("../../footer.php") ?>
  </div>
<!--Footer Block - End-->
</center></body>
</html>
<?
}
?>
