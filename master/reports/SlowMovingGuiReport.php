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
  $pagename = "Slow Moving GUI Report";
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
        document.location='SlowMovingGuiReport.php';
    </script>
    <?
}
include("inc/common_functions.php");
?> 

<script src="inc/multiselect.js" type="text/javascript"></script>
<link rel="stylesheet" href="inc/chosen.css">
<!-- <link href="reportstyle.css" rel="stylesheet" type="text/css" /> -->

<title>SSV&nbsp;|&nbsp;Slow Moving GUI Report</title>
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
                            <p>Slow Moving GUI Report</p>
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
                  <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                      </div>                                    
                                       <? if (($authen_row['usertype'])== 'Others') { 
                                        require_once 'second_moving_div_element.php';

                                     }else{
                                        require_once 'first_moving_div_element.php';
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
                                        <input type="hidden" name="SRP_ToDate" id="rp_todate" style="width:90%;height: inherit;" value="<?php echo $_POST['SRP_ToDate']; ?>" />
                                    </div>
                                     <? if (($authen_row['usertype'])== 'Others') { 
                                        require_once 'first_moving_div_element.php';

                                     }else{
                                        require_once 'second_moving_div_element.php';
                                     }

                                      ?> 
                                      
                             <!--        <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
                                        <label>Productgroup</label>
                                    </div>
                                    <div style="width:190px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='Productgroup[]' id = 'Productgroup'  onChange="drpfunc2();">
                                            <option value="0">.All Productgroup.</option>
                                            <?
                                            $Productgroup_select = ($_POST['Productgroup']) ? $_POST['Productgroup'] : '';
                    
                                            
                       $qry="SELECT ProductGroup FROM productgroupmaster ORDER BY ProductGroup asc";
                       
                       $list1 = mysql_query($qry);
                                            while ($row_list1 = mysql_fetch_assoc($list1)) {
                                                $selected = '';

                                                if ($row_list1['ProductGroup'] == $Productgroup_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                          <option value="<? echo $row_list1['ProductGroup']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list1['ProductGroup']; ?>                                          
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
 

                                    </div> -->
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
                            <div style="width:340px; height:50px; float:left;  margin-left:56px; margin-top:0px;" id="center1">
                                <div style="color:#900">
                                  <b>Chart - Dash Board</b>
                                </div>
                                <div style="width:60px; height:32px; float:left;  margin-left:35px; margin-top:5px;">
                                    <img src="../../img/column.png" id="column_img" style="cursor: pointer;" alt="Column">
                                    <p style="font-size:9px"><b>Column Chart</b></p>
                                </div>
                                <div style="width:40px; height:32px; float:left;  margin-left:35px; margin-top:5px;">
                                    <img src="../../img/pie.png" id="pie_img" style="cursor: pointer;" alt="Pie">
                                    <p style="font-size:9px"><b>Pie Chart</b></p>
                                </div>
                                <div style="width:40px; height:32px; float:left;  margin-left:35px; margin-top:5px;">
                                    <img src="../../img/bar.png" id="bar_img" style="cursor: pointer;" alt="Bar">
                                    <p style="font-size:9px"><b>Bar Chart</b></p>
                                </div>
                                <div style="width:60px; height:32px; float:left;  margin-left:35px; margin-top:5px;">
                                    <img src="../../img/line.png" id="line_img" style="cursor: pointer;" alt="Line">
                                    <p style="font-size:9px"><b>Line Chart</b></p>
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
				// Log information
									require_once '../../weblog.php';
									weblogfun("Report Access", "Sales Dashboard -> ".$pagename);
                  foreach ($_POST['region'] as $selectedOption){
                    if($selectedOption!="0")
                    {
                      $reg =  $reg ."rs.regionname = '" .$selectedOption."'  OR ";
                    }
                  }
                  
                  if(isset($_POST['branch'])){
                      $brn1 = $brn;
                      $brn = NULL;
                      foreach ($_POST['branch'] as $selectedOption1){
                          if($selectedOption1!="0")
                          {
                          $brn = $brn . "rs.branchname = '" .$selectedOption1."'  OR ". "\n";
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
                        $fname = $fname . "rs.franchisecode = '" .$selectedOption2."'  OR ". "\n";
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
                    $pgrp =  $pgrp. "rs.pgroupname = '" .$selectedOption3."'  OR ". "\n";       
                    $dis_productcode = $selectedOption3;           
                    }else{
                       break;
                    }
                  }
                  //   foreach ($_POST['productcode'] as $selectedOption6){
                  //   if($selectedOption6!="0")
                  //   {
                  //   $pcode =  $pcode. "productcode = '" .$selectedOption6."'  OR ". "\n";
                  //   }
                  // }
                   $reg = substr($reg, 0, -3);
                     $brn = substr($brn, 0, -4);                    
                     $fname = substr($fname, 0, -4);
                     $pgrp =  substr($pgrp, 0, -4);
                     // $pseg =  substr($pseg, 0, -4);
                     // $ptype = substr($ptype, 0, -4);
                     // $pcode = substr($pcode, 0, -4);
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
                   
                      // if($pcode!=NULL)
                      // {
                      //   $grystr=$grystr. "(".$pcode.") AND ";
                      // }
                        $grystrres = substr($grystr, 0, -4);
                        $gui_query = "SELECT  pg.ProductGroup As ProductGroup,sum(quantity) as Quantity FROM r_salesreport rs LEFT JOIN productgroupmaster pg ON pg.ProductCode=rs.pgroupname where $grystrres ";
                        $group_by = "rs.pgroupname"; ?>
                        <script language="javascript">
                    var chart_type = <?php echo json_encode($_REQUEST['chart_types']); ?>;
                    </script> 
                    <?
                   // $gui_query = "SELECT franchisename,sum(quantity) as quantity FROM `r_salesreport` where ";
                    //$group_by = "franchisename";
              
                  
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
                  if($_POST['SRP_FromDate']!="" && $_POST['SRP_ToDate']!="")
                  {
                    $dbtodateto=date("Y-m-d",strtotime($_POST['SRP_ToDate'])) ;
                    $dbfromdatefrom=date('Y-m-d',strtotime($_POST['SRP_FromDate']));
                      if($gui_query!=NULL && $group_by!=NULL)
                      {
                         $dbfromdatefrom="'". $dbfromdatefrom."'"; 
                         $dbtodateto="'".$dbtodateto."'";
                         $gui_query = $gui_query." AND  rs.salesdates between $dbfromdatefrom AND $dbtodateto group by ProductGroup order by Quantity";
                      //  echo $gui_query; 
                         $gui_result = mysql_query($gui_query);
                         $numrows = mysql_num_rows($gui_result);
                         $dis_productcode = "GUI Slow Moving Product Group Report for ".$dis_franchisename;
                         echo $dis_productcode;
                         if($numrows== 0){ ?>
                              <script type="text/javascript">
                                        alert("No data Available");
                                        </script>
                          <? exit;
                         }
                          $rows = array();
                          $chart = array();
                          $chart['cols'] = array(
                            // Labels for your chart, these represent the column titles.
                            /* 
                                note that one column is in "string" format and another one is in "number" format 
                                as pie chart only required "numbers" for calculating percentage 
                                and string will be used for Slice title
                            */
                            array('label' =>   'ProductGroup', 'type' => 'string'),
                            array('label' => 'Quantity', 'type' => 'number'),
                            array('role' =>'annotation','type' => 'number')
                          );
                           while ($row_list = mysql_fetch_assoc($gui_result)) {
                              $temp = array();
                              // The following line will be used to slice the Pie chart
                             $temp[] = array('v' => (string) $row_list['ProductGroup']); 
                             $temp[] = array('v' => (int) $row_list['Quantity']);
                              // Values of the each slice
                              // $temp[] = array('v' => (string) $row_list['ProductGroup']);
                              $temp[] = array('v' => (int) $row_list['Quantity']);
                              $rows[] = array('c' => $temp);
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
                              var options = {
                                   title: 'GUI Slow Moving Report',
                                  is3D: 'true',
                                  backgroundColor: '#F5F3F1',
                                  width: 800,
                                  height: 300,
                                  vAxis: {minValue: 1, maxValue: 100},
                                  hAxis: {minValue: 1,maxValue:100}
                                };
                              // Instantiate and draw our chart, passing in some options.
                              // Do not forget to check your div ID
                              var chart ="";
                             if(chart_type == "Column"){
                                chart = new google.visualization.ColumnChart(document.getElementById('sales_report_gui_div'));
                              }else if(chart_type == "Pie"){
                                 chart = new google.visualization.PieChart(document.getElementById('sales_report_gui_div'));
                              }else if(chart_type == "Bar"){
                                chart = new google.visualization.BarChart(document.getElementById('sales_report_gui_div'));
                              }else{
                                chart = new google.visualization.LineChart(document.getElementById('sales_report_gui_div'));
                              }


                              chart.draw(data, options);
                            }
                            </script>
                         <?               
                        }
                      else
                      { ?>
                         <script type="text/javascript">
                                        alert("Please Select Product Code ");
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
    $("#column_img").click(function(){
      $("#chart_types").val("Column");
      $("#get_report_btn").trigger("click");
    });
    $("#pie_img").click(function(){
      $("#chart_types").val("Pie");
      $("#get_report_btn").trigger("click");
    });
    $("#bar_img").click(function(){
      $("#chart_types").val("Bar");
      $("#get_report_btn").trigger("click");
    });
    $("#line_img").click(function(){
      $("#chart_types").val("Line");
      $("#get_report_btn").trigger("click");
    });
      $("#get_report_btn").click(function(){
      var franch = $("#franchise").val();
      if(franch == "0" || franch == 0){
        alert("Please Select Franchise Name ");
        return false;
      }
	  	var region_select = $('#region').val();
  	if(region_select=="0"){
  		alert("Enter Mandatory Fields !");
      	return false;
		}
    });
</script>
<script src="inc/prism.js" type="text/javascript" charset="utf-8"></script>
<script src="inc/chosen.jquery.js" type="text/javascript"></script>
<!-- <script src="inc/docsupport/prism.js" type="text/javascript" charset="utf-8"></script> -->
  <script type="text/javascript">
  var config = {
    '.chosen-select': {},
    '.chosen-select-deselect': { allow_single_deselect: true },
    '.chosen-select-no-single': { disable_search_threshold: 10 },
    '.chosen-select-no-results': { no_results_text: 'Oops, nothing found!' },
    '.chosen-select-width': { width: "95%" }
}
for (var selector in config) {
    $(selector).chosen(config[selector]);
}
  </script>