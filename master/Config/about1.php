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

<title><?php echo $_SESSION['title']; ?> || Setting</title>
</head>
 <body class="default"><center>

<?php include("../../menu.php")?>

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

                              <div style="width:250px; height:30px;  float:left;  margin-top:10px; margin-left:-18px;">
                               <span style="font-weight:300">Tally Central Server [TCS]</span> 
                               </div>
 							<!--Row1 end-->
                            <div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Developed by</label>
                               </div>
                               <div style="height:30px; float:left; margin-top:5px; margin-left:3px;">
                               <label>:</label>
                               </div>
                              <div style="width:250px; height:30px; align:left;  float:left;  margin-top:10px; margin-left:-18px;">
                               <a href="http://tiaraconsulting.com/" style="text-decoration: none;color: blue" target="_blank"><u>TIARA Consulting Services</u></a>
                                
                               </div>
							       
                            <div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Release Version</label>
                               </div>
                               <div style="height:30px; float:left; margin-top:5px; margin-left:3px;">
                               <label>:</label>
                               </div>

                              <div style="width:250px; height:30px;  float:left;  margin-top:5px; margin-left:20px;">
                                  <label>Rel 3.7</label>
                               </div>
                                <div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Build</label>
                               </div>
                               <div style="height:30px; float:left; margin-top:5px; margin-left:3px;">
                               <label>:</label>
                               </div>

                              <div style="width:250px; height:30px;  float:left;  margin-top:5px; margin-left:20px;">
                                  <label>15</label>
                               </div>

							<div style="width:165px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Last Updated On</label>
                               </div>
                              <div style="height:30px; float:left; margin-top:5px; margin-left:3px;">
                               <label>:</label>
                               </div>

                              <div style="width:250px; height:30px;  float:left;  margin-top:5px; margin-left:20px;">
                                 <label>30-Sep-2013 at 5.30 PM</label>
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
        <?php include("../../footer.php")?>
  </div>
<!--Footer Block - End-->
</center></body>
</html>
<?
}
?>
