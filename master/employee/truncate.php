<?php 
include '../../functions.php';
sec_session_start();
 require_once '../../masterclass.php';
 include("../../header.php");
// Include database connection and functions here.

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	$news = new News(); // Create a new News Object
   
   $pagename = "Employee Master";
	$validuser = $_SESSION['username'];
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");//$news->getNewsById($validuser,$pagename);
	$row = mysql_fetch_array($selectvar);
  
 	if (($row['viewrights'])== 'No')
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
   
if(isset($_POST['Save'])) // If the submit button was clicked
{
			
	// CREATE A NEW ARRAY TO STORE THE ALL TABLE NAMES
	$all_tables = array();

	// USE MYSQL'S SHOW TABLES TO GET ALL THE TABLE NAMES
	$sql = mysql_query("SHOW FULL TABLES IN ssvsales WHERE TABLE_TYPE <>  'VIEW'") or die(mysql_error());

	while($row = mysql_fetch_array($sql))
	{
	$all_tables[] = $row[0];
	}

	// CREATE A NEW ARRAY THAT CONTAINS NAMES OF TABLES THAT NEED NOT BE EMPTIED
	$not_to_empty = array('branch', 'countrymaster', 'employeemaster', 'pagination', 'region', 'state', 'usercreation', 'userrights', 'reportrights', 'reportrights_sub', 'tempdate', 'productgroupmaster', 'productuom','menu','pagename','about',);

	// FIND THE DIFFERENCE IN ARRAYS
	$truncate_tables = array_diff($all_tables, $not_to_empty);
	sort($truncate_tables);

	// RUN A LOOP TO TRUNCATE THE TABLES
	for($i=0; $i<count($truncate_tables); $i++)
	{
	$truncate = mysql_query("TRUNCATE TABLE $truncate_tables[$i]") or die(mysql_error());
	 // END OF IF
	} // END OF FOR
	?>
            <script type="text/javascript">
			alert("Truncated Sucessfully!",'truncate.php');
			</script>
   			<?
}
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
		mysql_query("TRUNCATE TABLE stock_open") or die(mysql_error());
		mysql_query("CALL stockopen()") or die(mysql_error());
		mysql_query("CALL stockday()") or die(mysql_error());
		mysql_query("CALL stockmonth()") or die(mysql_error());
	 ?>
            <script type="text/javascript">
			alert("Stock updated Sucessfully!",'truncate.php');
			</script>
   			<?
	}


?>

<title><?php echo $_SESSION['title']; ?> || DEMO settings</title>
</head>

<body class="default">

 <center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
            <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1">
           
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p> DEMO settings</p>
						</div>
             
                
                
                   <!--Main row 2 start-->
               <div style="width:1000px; height:60px; float:left; margin-left:8px; margin-top:8px;">
                             
					<div style="width:305px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
                          <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
						  <input name="Save" type="submit" class="button" id="addbutton" value="Truncate" >
				           </div>
					 <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
						 <input name="Update" type="submit" class="button" value="Stock Updation" id="addbutton">
				           </div>                          
                                                   
				     </div>	
                         
                               </div>
                
                <!--Main row 2 end-->
            
             
            

          
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
