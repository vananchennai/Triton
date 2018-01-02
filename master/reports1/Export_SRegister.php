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
$output .= '"Region","Branch","Primary Distributor","Distributor Code","Distributor Name","Product Code","Product Group","Voucher Type","Quantity","Net Amount","Tax Amount","Gross Amount",';
$output .="\n";

// Get Records from the table

while ($row = mysql_fetch_array($sql)) {
for ($i = 0; $i < $columns_total; $i++) {
$output .='"'.$row["$i"].'",';
}
$output .="\n";
}

// Download the file

$filename = "Salesregister-".date('Y-m-d').".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;

?>

