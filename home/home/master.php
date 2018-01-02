`<?php 
include '../../functions.php';
sec_session_start();
include '../../Mysql.php';// Include database connection and functions here.
include("../../header.php");
?>
            <script type="text/javascript">
			alert("you are not allowed to view this page!");
			//setInterval(function(){document.location='/amararaja/home/home/master.php';},2000);
			//document.location='/amararaja/home/home/master.php';	
			</script>
         <?
if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
?>
<title><?php echo $_SESSION['title']; ?></title>
</head>
<body><center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
          <div style="width:500px; height:55px; float:none;">    
          </div> 
          <div style="width:505px; height:360px; float:Center; background-color:#FFF;  background:url(../../img/construction.png)">
          </div> 
          <div style="width:500px; height:25px; float:none;">    
          </div> 
     </div>       
</div>
<!--Third Block - Menu -Container -->


<!--Footer Block -->
<div id="footer-wrap">
    <?php include("../../footer.php")?>
  </div>
<!--Footer Block - End-->
</center></body>
</html>
<?Php
}
?>