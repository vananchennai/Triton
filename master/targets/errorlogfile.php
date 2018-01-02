<?php
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
$news = new News();  
$values=$_SESSION['retailer_code_error'];
// echo $values;
if(!empty($values))
{
		header('Content-type: application/vnd.txt');
        header("Content-Disposition: attachment; filename=Logfile.txt");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $values;
        unset($_SESSION['retailer_code_error']);
        // exit();
}
		?>