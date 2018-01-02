<?php
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
$news = new News();
//include("inc/common_functions.php");


$controls = $_SESSION['form_controls'];
// Database Connection


// Fetch Record from Database

$output = "";
$table = ""; // Enter Your Table Name 
$sql = mysql_query($controls);
if (!$sql) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}
 $columns_total = mysql_num_fields($sql);
/*
// Get The Field Name

for ($i = 0; $i < $columns_total; $i++) {
echo $heading = mysql_field_name($sql, $i);
$output .= '"'.$heading.'",';
} */

$output .= '"Region","Branch", "Franchisee Code", "Franchisee Name", "Retailer Name", "SCF No", "SCF Date", "Complaint Date", "Category", "Date of Sale", "Battery SI No.", "Product Code", "Product Type", "Product Segment", "Product Group", "Bty. Mfg. Date", "Customer Name", "City", "Phone No.", "Vehicle Model", "Vehicle Make", "Vehicle Segment", "Vehicle Reg. No.", "Engine Type", "Life Served (In Days)", "KM Run", "Failure Mode", "Decision", "Decision Date", "Closure Date", "TAT - 2", "Settled in Days", "New Battery SI No.", "New Product Code", "New Product Type", "New Product Segment", "New Product Group", "Lead Time in days", "Service Compensation",';
$output .="\n";

// Get Records from the table

while ($row = mysql_fetch_array($sql)) {
for ($i = 0; $i < $columns_total; $i++) {
$output .='"'.$row["$i"].'",';
}
$output .="\n";
}

// Download the file

$filename = "ServiceCallReport-".date('Y-m-d').".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;

?>
