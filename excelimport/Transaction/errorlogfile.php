<?php
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
$news = new News();  
$values=$_SESSION['errorlogs'];
if(!empty($values))
{
		header('Content-type: application/vnd.txt');
        header("Content-Disposition: attachment; filename=Errorlog file.txt");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $values;
        exit();
}
		?>