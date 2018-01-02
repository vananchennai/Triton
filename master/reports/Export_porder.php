
<?php
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
$news = new News();// Database Connection

// Fetch Record from Database
$output="";
$controls = $_SESSION['form_controls'];
$sql = mysql_query($controls);
if (!$sql) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}
 $columns_total = mysql_num_fields($sql);
$output .= '"Region","Branch","Distributor Code","Distributor Name","Po No","Po Date","Product Group","Product Code","Product Description","Ordered Qty","GRN No","GRN Date","Received Qty","Pending Qty",';
$output .="\n";

// Get Records from the table

while ($row = mysql_fetch_array($sql)) {
for ($i = 0; $i < $columns_total; $i++) {
$output .='"'.$row["$i"].'",';
}
$output .="\n";
}

// Download the file

$filename = "PurchaseOrder-".date('Y-m-d').".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;

?>

