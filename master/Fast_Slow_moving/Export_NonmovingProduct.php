<?php
ini_set('memory_limit', '-1');  
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';

// Download the file
$filename = $_SESSION['filename'].date('Y-m-d').".csv";
// $filename .= date('Y-m-d').".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

//echo $rbf_output.$output;
echo $_SESSION['form_controls1'];
unset($_SESSION['form_controls1']);
unset($_SESSION['form_controls']);
// exit;
?>