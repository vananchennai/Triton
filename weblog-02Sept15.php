<?php
function weblogfun($accesstype, $accessform)// application access log function
{
	If($accesstype=="Login" || $accesstype=="Logout")
	{
		require_once 'masterclass.php';
	}
	else If($accesstype=="Report Access")
	{
		require_once '../../masterclass.php';
	}
		$news = new News();
		date_default_timezone_set ("Asia/Calcutta");
		$wvalues['accesstype']= $accesstype;
		$wvalues['accessform']= $accessform;
		$wvalues['userid']= $_SESSION['username'];
		$wvalues['accesstime']= date("y/m/d : H:i:s", time());
		$news->addNews($wvalues,'weblog');
}

function uploadfun($statustable,$logtable,$fcode,$master) // upload log function
{						
	$editval['Deliverydae']=date("Y-m-d");
	$editval['Status']='2';
	$wherecon= "Franchiseecode ='".$fcode."'";
	$news->editNews($editval,$statustable,$wherecon);
	
	$insertval['franchisecode']=$fcode;
	$insertval['master']=$master;
	$insertval['date']=date("Y-m-d H:i:s");
	$insertval['status']='Delivered';
	$news->addNews($insertval,$logtable);
}

function statusfun($logtable,$fcode,$master) // upload log function
{
	$insertval['franchisecode']=$fcode;
	$insertval['master']=$master;
	$insertval['date']=date("Y-m-d H:i:s");
	$insertval['status']='Delivered';
	$news->addNews($insertval,$logtable);
}

?>




