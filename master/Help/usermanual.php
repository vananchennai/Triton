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

<title>SSV || Help</title>
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

                    
						<div style="width:930px; height:25px; float:left;  margin-left:5px;" class="head">
						<p>Help</p>
						</div>
            
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px;">
                     <!-- col1 -->   
                           
						    <a href="User Manual for Fenner - Centroid.pdf">Download User Manual</a>
                                                        
                     <!-- col1 end --> 
                     
                     
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
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
