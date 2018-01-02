<?Php
require_once 'masterclass.php';
include 'functions.php';
sec_session_start();
// Unset all session values

 $news = new News();
$pos['status']= "OUT";
$whereon= "userid ='".$_SESSION['username']."'";
$news->editNews($pos,'usercreation',$whereon);
$_SESSION = array();
// get session parameters 
$params = session_get_cookie_params();
// Delete the actual cookie.
setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

// Destroy session
session_destroy();
header('Location: index.php');
?>