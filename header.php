<?php
error_reporting(1);
 require_once '../../masterclass.php';
 $news = new News();
 date_default_timezone_set ("Asia/Calcutta");
 

$pos['timeout']= date("y/m/d : H:i:s", time());
$whereon= "userid ='".$_SESSION['username']."'";
$news->editNews($pos,'usercreation',$whereon);

$timeqry =mysql_query("SELECT timeoutvar FROM pagination ");
$timevalue = mysql_fetch_array($timeqry);
$timeoutvar = $timevalue['timeoutvar'];
$Timevar=$timeoutvar*60000;
//	Cleare the session for searching
 if (isset($_SESSION['previous'])) {
   if (basename($_SERVER['PHP_SELF']) != $_SESSION['previous']) {
       unset($_SESSION['codesval']);
	   unset($_SESSION['namesval']);
	   unset($_SESSION['form_controls']);
        ### or alternatively, you can use this for specific variables:
        ### unset($_SESSION['varname']);
   }
}
  $_SESSION['previous'] = basename($_SERVER['PHP_SELF']);
  
  // Set the page count for searching
	if (!isset($_SESSION['pagecount']))
	{
	 $limit = 10;
	}
	else
	  {
	   $limit =$_SESSION['pagecount'] ;
	  }

//search pagination and defaut values in table
//"-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
?>
<!DOCTYPE html PUBLIC>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<link href="../../css/grid.css" rel="stylesheet" type="text/css">
<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link href="../../css/reveal.css" rel="stylesheet" type="text/css">
<link href="../../css/A_red.css" type="text/css" rel="stylesheet" />
<link href="../../css/pagination.css" type="text/css" rel="stylesheet" />
<link href="../../css/menu.css" rel="stylesheet" type="text/css">
<link href="../../css/confirm.css" rel="stylesheet" type="text/css">
<link href="../../jquery/css/smoothness/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="../../img/amararajaicon.png" >

<script src="../../js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery.reveal.js"></script>
<script src="../../js/sorttable.js"></script>
<script src="../../js/jquery.simplemodal.js"></script>	
<script src="../../js/jquery.hotkeys.js"></script>	
<script src="../../js/common.js"></script>	
<script src="../../js/jqueryuimin.js"></script>
<script src="../../js/checkboxdel.js"></script>
 
 <script type="text/javascript">  

   var val=<? echo $Timevar;?>;
   
   onInactive( val, function(){
    alert('Session Expired',document.location='../../logout.php');//document.location='../../logout.php';   
	setInterval(function(){document.location='../../logout.php';},2000);
});
    

function onInactive(ms, cb){   
   
    var wait = setTimeout(cb, ms); 
    document.onmousemove = document.mousedown = document.mouseup = document.onkeydown = document.onkeyup = document.focus = function(){
        clearTimeout(wait);
        wait = setTimeout(cb, ms);
    };

}
 
function toutfun(object) 
        { 
		 setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); object.focus(); }, 2000);
		}
		
		function codetrim(el) {
    el.value = el.value.
       replace (/(^\s*)|(\s*$)/gi, ""). // removes leading and trailing spaces
       replace (/[ ]{2,}/gi," ").       // replaces multiple spaces with one space 
       replace (/\n +/,"\n");           // Removes spaces after newlines
	    if(el.value.match(/\s/g)){
		
		el.value=el.value.replace(/\s/g,'');}
    return;
}
function trim (el) {
    el.value = el.value.
       replace (/(^\s*)|(\s*$)/gi, ""). // removes leading and trailing spaces
       replace (/[ ]{2,}/gi," ").       // replaces multiple spaces with one space 
       replace (/\n +/,"\n");           // Removes spaces after newlines
    return;
}
function minmax(value, min, max) 
{
  
  if(parseInt(value) < 0 || isNaN(value))
  {
	  return min; 
  }
  else if(parseInt(value) > max || parseInt(value) < min ) 
  {
	return min;
  }
  else return value;
}
function minmaxcon(value, min, max) 
{
  
  if(parseInt(value) < 0 || isNaN(value))
  {
	  return 10; 
  }
  else if(parseInt(value) > max || parseInt(value) < min ) 
  {
	return 10;
  }
  else return value;
}
    function searchKeyPress(e)
    {
        // look for window.event in case event isn't passed in
        if (typeof e == 'undefined' && window.event) { e = window.event; }
        if (e.keyCode == 13)
        {
            document.getElementById('Search').click();
        }
    }
</script>


<div id='confirm'>
		<div class='header'><span><?php echo $_SESSION['title']; ?></span></div>
		<div class='message'></div>
		<div class='buttons'>
				<div class='no simplemodal-close'>No</div><div class='yes'>Ok</div>
		</div>
</div>
