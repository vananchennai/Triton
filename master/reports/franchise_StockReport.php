<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<?php 
ini_set('memory_limit', '-1');
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
include("../../header.php");
  require_once '../../paginationfunction.php';
if(login_check($mysqli) == false) 
{
  header('Location:../../index.php');// Redirect to login page!
}

else
{

//This block to authenticate the user whether he has the rights to access this page 
  $pagename = "GUI Stock Opening and Closing Report";
  require_once 'Authentication_Rights.php';

  // Authentication block ends 
    
  // Export function 
if (isset($_POST['Excel'])) 
{
  header('Location:Export_SalesReport.php');
  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=Export_SalesReport.php">';
  exit;
}

if (isset($_POST['Cancel']))
 {
unset($_SESSION['form_controls']);
    ?>
    <script type="text/javascript">
        document.location='franchise_StockReport.php';
    </script>
    <?
}
include("inc/common_functions.php");
?> 

<script src="inc/multiselect.js" type="text/javascript"></script>

<link href="reportstyle.css" rel="stylesheet" type="text/css" />

<title>SSV&nbsp;|&nbsp;GUI Stock Opening and Closing report</title>
</head>

<body><center>

 <?php include("../../menu.php") ?>
 
    <!--Third Block - Container-->
    <div style="width:100%; height:auto; float:none;">
        <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">

            <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
                <!-- form id start-->
          <form method="POST" action="<?php // $_PHP_SELF ?>">
            <? require_once'all_list.php' ?>
            <table  id="default" style="display:none;"  >
            <tr><td>
<input type="text" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
</td></tr></table>
                    <div style="width:930px; height:auto;   min-height: 180px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">


                        <div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
                            <p>GUI Stock Opening and Closing Report</p>
                        </div>
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                            <!-- col1 -->   
                            <div style="width:800px; height:auto; padding-bottom:5px; float:left; " class="cont">
                                <!--Row1 -->
                                <div style="float:left;width:400px">

                                    <!--Row1 end-->
                       <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Period</label>
                                  </div>
               <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
               <select name='Period' id="Period" style="height: inherit;" onChange="datefun();">
                <option value="<? if(!empty($_POST['Period'])){ echo $_POST['Period'];}else{?> 0<? } ?></"><? if(!empty($_POST['Period'])){ echo $_POST['Period'];}else{?> .Select. <? } ?></option>
                <option value="Current Calender Year">Current Calender Year</option>
                <option value="Last Calender Year">Last Calender Year</option>
                <option value="Current Month">Current Month</option>
                <option value="Last Month">Last Month</option>
                <option value="Current Quarter">Current Quarter</option>
                <option value="Last Quarter">Last Quarter</option>
                <option value="Current Financial Year">Current Financial Year</option>
                <option value="Last Financial Year">Last Financial Year</option>
                <option value="Custom">Custom</option>
              </select>
                </div>
                  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                      </div>

                               <? if (($authen_row['usertype'])== 'Others') { 
                                        require_once 'second_div_element.php';

                                     }else{
                                        require_once 'first_div_element.php';
                                     }

                                      ?>                  
                                </div>
                                <!-- col1 end -->
                                <div style="float:left;width:400px">
                                  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>From Date</label><label style="color:#F00;">*</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <input type="text" name="SRP_FromDate" style="width:90%;height: inherit;" id="frdate" readonly="readonly" value="<?php echo $_POST['SRP_FromDate'];   ?>"/>
                                        <input type="hidden" name="SRP_FromDate" id="rp_frdate" style="width:90%;height: inherit;" value="<?php echo $_POST['SRP_FromDate']; ?>"/>
                                    </div>
                  
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>To Date</label><label style="color:#F00;">*</label>
                                  </div>
                                  <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                    <input type="text" name="SRP_ToDate" id="todate" style="width:90%;height: inherit;" readonly="readonly" value="<?php echo $_POST['SRP_ToDate']; ?>" />
                                        <input type="hidden" name="SRP_ToDate" id="rp_todate"  style="width:90%;height: inherit;" value="<?php echo $_POST['SRP_ToDate']; ?>" />
                                    </div>
                                     <? if (($authen_row['usertype'])== 'Others') { 
                                        require_once 'first_div_element.php';

                                     }else{
                                        require_once 'second_div_element.php';
                                     }

                                      ?> 
                             
                                </div>
                                <div style="clear:both"></div>
                            </div>
            </div>
                        <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:8px;">

                            <div style="width:340px; height:50px; float:left;  margin-left:4px; margin-top:0px;" id="center1">
                                <div style="width:95px; height:32px; float:left;  margin-left:35px; margin-top:16px;">
                                    <input id="get_report_btn" name="Get" type="submit" class="button" value="Get Report">
                                </div>
                                <div style="width:85px; height:32px; float:left;  margin-left:75px;margin-top:16px;">
                                    <input id="cancel_btn" name="Cancel" type="submit" class="button" value="Cancel">
                                </div>                     

                            </div>  

                        </div>
                        <?php $table_data_height = '';
if (isset($_POST['Get'])) 
{
    $table_data_height = " height:auto; ";
} 
?>
                        <div style="width:917px; <?php echo $table_data_height ?> padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                            <div id="sales_report_gui_div" style="width:900px;padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;"> </div>
                                <?php
                                if (isset($_POST['Get'])) 
                {
                  foreach ($_POST['franchise'] as $selectedOption2){
                    if($selectedOption2!="0")
                    {
                    // $fname =  "franchisename = '" .$selectedOption2."'";
                    $dis_franchisename = $selectedOption2;
                    $fname = $fname . "franchisename = '" .$selectedOption2."'  OR ". "\n";
                    //$gui_query = "SELECT pgroupname,productdes,sum(quantity) as quantity FROM `r_salesreport` where  $fname ";
                    $group_by = "productcode";
                    }
                    else
                    {
                       break;
                    }
                  }
                  foreach ($_POST['region'] as $selectedOption){
                    if($selectedOption!="0")
                    {
                      $reg =  $reg ."regionname = '" .$selectedOption."'  OR ";
                    }
                  }
                  
                  if(isset($_POST['branch'])){
                    $brn1 = $brn;
                    $brn = NULL;
                    foreach ($_POST['branch'] as $selectedOption1){
                        if($selectedOption1!="0")
                        {
                        $brn = $brn . "branchname = '" .$selectedOption1."'  OR ". "\n";
                        }else{
                            $brn = $brn1;
                            break;
                        }
                    }
                }
                  
                  if(isset($_POST['franchise'])) 
                  {
                      $fname1 = $fname;
                      $fname=NULL;
                      foreach ($_POST['franchise'] as $selectedOption2){
                        if($selectedOption2!="0")
                        {
                          $fname = $fname . "franchisename = '" .$selectedOption2."'  OR ". "\n";
                          $dis_franchisename = $selectedOption2;
                        }else{
                          $fname = $fname1;
                          break;
                        }
                      }
                  }
                  foreach ($_POST['Productgroup'] as $selectedOption3){
                    if($selectedOption3!="0")
                    {
                    $pgrp =  $pgrp. "pgroupname = '" .$selectedOption3."'  OR ". "\n";                  
                    }
                  }
                    foreach ($_POST['productcode'] as $selectedOption6){
                    if($selectedOption6!="0")
                    {
                    $pcode =  $pcode. "productcode = '" .$selectedOption6."'  OR ". "\n";
                    }
                  }
                   $reg = substr($reg, 0, -3);
                     $brn = substr($brn, 0, -4);                    
                     $fname = substr($fname, 0, -4);
                     $pgrp =  substr($pgrp, 0, -4);
                     $pseg =  substr($pseg, 0, -4);
                     $ptype = substr($ptype, 0, -4);
                     $pcode = substr($pcode, 0, -4);
                     if($reg!=NULL)
                      {
                        $grystr = "(".$reg.") AND ";
                      }
                      
                      if($brn!=NULL)
                      {
                        $grystr=$grystr. "(".$brn.") AND ";
                      }
                      if($fname!=NULL)
                      {
                        $grystr=$grystr. "(".$fname.") AND ";
                      }
                      if($pgrp!=NULL)
                      {
                        $grystr=$grystr. "(".$pgrp.") AND ";
                      }
                   
                      if($pcode!=NULL)
                      {
                        $grystr=$grystr. "(".$pcode.") AND ";
                      }
                        $grystrres = substr($grystr, 0, -4);
                      $group_by = "productcode";
                  // foreach ($_POST['Productgroup'] as $selectedOption3){
                  //   if($selectedOption3!="0")
                  //   {
                  //   $pgrp =  "pgroupname = '" .$selectedOption3."'";
                  //   $gui_query = "SELECT franchisename,sum(quantity) as quantity FROM `r_salesreport` where $pgrp ";               
                  //   $group_by ="franchisename";
                  //   }
                  //   else
                  //   {
                  //      break;
                  //   }
                  // }
                 // $fname =  substr($fname, 0, -4);
                  echo "GUI Stock Ledger Report for ".$dis_franchisename;
                  // if($fname!=NULL)
                  // {
                  //   $grystr=$grystr. "(".$fname.")";
                  // }
                  if($_POST['SRP_FromDate']!="" && $_POST['SRP_ToDate']!="")
                  {
                    $dbtodateto=date("Y-m-d",strtotime($_POST['SRP_ToDate'])) ;
                    $dbfromdatefrom=date('Y-m-d',strtotime($_POST['SRP_FromDate']));
                      if($group_by!=NULL)
                      {
                         $dbfromdatefrom="'". $dbfromdatefrom."'"; 
                         $dbtodateto="'".$dbtodateto."'";
                         // $gui_query = $gui_query." AND  salesdates between $dbfromdatefrom AND $dbtodateto group by $group_by";
                         // $gui_result = mysql_query($gui_query);
                         // $numrows = mysql_num_rows($gui_result);
                         // $dis_franchisename = "GUI Sales Report for ".$dis_franchisename;
                         // echo $dis_franchisename;
                         // if($numrows== 0){ ?>
                          <script type="text/javascript">
                         //                alert("No data Available");
                         //                </script>
                          <? 
                         // }
                         if($grystrres!=NULL)
                        {
                          $grystrres= str_replace("'","''",$grystrres);
                          $grystrres= "'".$grystrres."'";
                        }
                        else
                        {
                          $grystrres= "'1'";
                        }
                        //  if($grystr!=NULL)
                        // {
                        //   $grystr= str_replace("'","''",$grystr);
                        //   $grystr= "'".$grystr."'";
                        // }
                        // else
                        // {
                        //   $grystr= "'1'";
                        // }
                        $SL_FromDate="'". $SL_FromDate."'";
                        $SL_ToDate="'".$SL_ToDate."'";
                        $qry ="CALL stockreport($dbfromdatefrom,$dbtodateto,$grystrres);";
                        // echo $qry;
                        $_SESSION['form_controls'] = $qry ; 
                        if(!empty($_SESSION['form_controls']))
                        { 
                          $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                          //echo $limit;
                          $startpoint = ($page * $limit) - $limit;
                          $statement = $_SESSION['form_controls'];
                         // echo $statement;
                          $qry_exec=mysql_query($statement);
                          $myrow1 = mysql_num_rows($qry_exec);
                          if($myrow1 == 0){ ?>
                            <script type="text/javascript">
                                        alert("No data Available");
                                        </script>
                          <? exit;
                          }
                          $starvalue = $myrow1;
                          
                          // while( $record[] = mysql_fetch_array($qry_exec)){
                          //  echo  $record[$i]['productcode']."Open Stack :".$record[$i]['opstock']."Closing Stock:".$record[$i]['StockonHand']."<br>";
                          //   $i++;
                          // }
                        }
                          $rows = array();
                          $chart = array();
                          $record = array();
                          $i=0;
                          $chart['cols'] = array(
                            // Labels for your chart, these represent the column titles.
                            /* 
                                note that one column is in "string" format and another one is in "number" format 
                                as pie chart only required "numbers" for calculating percentage 
                                and string will be used for Slice title
                            */
                            array('label' =>  $dis_franchisename , 'type' => 'string'),
                            array('label' => 'Openning stock', 'type' => 'number'),
                            array('role' =>'annotation','type' => 'number'),
                            array('label' => 'Closing stock', 'type' => 'number'),
                            array('role' =>'annotation','type' => 'number'),
                          );
                           while( $record[] = mysql_fetch_array($qry_exec)){
                              $temp = array();
                              // The following line will be used to slice the Pie chart
                              $temp[] = array('v' => (string) $record[$i][$group_by]); 
                              // Values of the each slice
                              $temp[] = array('v' => (int) $record[$i]['opstock']);
                              $temp[] = array('v' => (int) $record[$i]['opstock']);
                              $temp[] = array('v' => (int) $record[$i]['StockonHand']);
                              $temp[] = array('v' => (int) $record[$i]['StockonHand']);
                              $rows[] = array('c' => $temp);
                              $i++;
                            }
                        $chart['rows'] = $rows;
                        // convert data into JSON format
                        $jsonTable = json_encode($chart);
                         ?>
                          <script type="text/javascript">
                            // Load the Visualization API and the piechart package.
                            google.load('visualization', '1', {'packages':['corechart']});
                            // Set a callback to run when the Google Visualization API is loaded.
                            google.setOnLoadCallback(drawChart);

                            function drawChart() {
                              // Create our data table out of JSON data loaded from server.
                              var data = new google.visualization.DataTable(<?=$jsonTable?>);
                              // data.setColumns([0, 1,2]);
                              var options = {
                                   title: 'GUI Sales Report',
                                  is3D: 'true',
                                  width: 800,
                                  height: 300,
                                  x_axis_title:'Franchise Name',
                                  y_axis_title:'Quantity',
                                  legend: { position: "none" },
                                  backgroundColor: '#F5F3F1',
                                  vAxis: {minValue: 1, maxValue: 100},
                                  hAxis: {minValue: 1,maxValue:100}
                                };
                              // Instantiate and draw our chart, passing in some options.
                              // Do not forget to check your div ID
                              var chart = new google.visualization.ColumnChart(document.getElementById('sales_report_gui_div'));
                              chart.draw(data, options);
                            }
                            </script>
                         <?               
                        }
                      else
                      { ?>
                         <script type="text/javascript">
                                        alert("Please Select Distributor ");
                                        </script>
                     <?  }
                  }
                else
                {
                  ?>
                    <script type="text/javascript">
                                        alert("Enter Mandatory Fields!");
                                        </script>
                                     <?
                }
                  $_GET["page"] ="1";

                }
                                                ?>
                        </div>
                        <br />
                     <?
                  ?>

              <!--Main row 2 end-->
</div>
              <!-- form id start end-->  
            
            </form>      
            </div> 
<script>// all scripts used to eliminate duplication in dropdown.
   // Set the present object
	var present = {};
	$('#Period option').each(function(){
	// Get the text of the current option
	var text = $(this).text();
	// Test if the text is already present in the object
	if(present[text]){
	// If it is then remove it
	$(this).remove();
	}else{
	// Otherwise, place it in the object
	present[text] = true;
	}
	});
</script>        
</div>       
    </div>
    <!--Third Block - Menu -Container -->


    <!--Footer Block -->
    <div id="footer-wrap1">
        <?php include("../../footer.php"); ?>
    </div>
  <!--Footer Block - End-->
</center></body>
</html>
<? } ?>
<script type="text/javascript">
  $("#get_report_btn").click(function(){
      var franch = $("#franchise").val();
      if(franch == "0" || franch == 0){
        alert("Please Select Distributor ");
        return false;
      }
    });
</script>
