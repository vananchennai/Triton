<?
define(TIMEOUT_SECONDS, 600);

session_cache_expire( TIMEOUT_SECONDS );
if (isset($_SESSION['user_id']) && ($_SESSION['user_id']!= '')){
if(isset($_SESSION['start']) ) {
$session_life = time() - $_SESSION['start'];
if($session_life > TIMEOUT_SECONDS){
//unset main session variables used in all pages.(dont use session_destroy())
//because we are setting the return page url in session if we use session_destroy() it will reset the return page session also.
unset($_SESSION['user_id']);

$script_file_name = curPageURL();
if ($script_file_name == ''){
$script_file_name = 'index.php';
}
$script_file_arr = explode('/',$script_file_name);
$script_file_name = end($script_file_arr);

$_SESSION['return'] = curPageURL();
$_SESSION['session_out_msg'] = 1;
}
}
}
$_SESSION['start'] = time();

### IF THE USER NOT LOGGED IN HE WILL BE REDIRECT TO LOGIN PAGE or SESSION-TIMEOUT PAGE ######

$_SESSION['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
if (($_SESSION['user_id'] == '') && ($_SESSION['user_type'] != SITE_USER)) {
$_SESSION['return_page'] = curPageURL();
if ($_SESSION['session_out_msg'] == ''){
header('Location:'.$base_url.'login.html');
exit;
}
else {
header('Location:'.$base_url.'logout.php');
exit;
}
}

###### FUNCTION TO GET CURRENT PAGE URL ##############
function curPageURL()
{
$pageURL = 'http';
if(isset($_SERVER["HTTPS"]))
if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
$pageURL .= "://";
if ($_SERVER["SERVER_PORT"] != "80") {
$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} else {
$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}
return $pageURL;
}
?>