<?php


if(isset($_POST['Search']))
{
	if(isset($_POST['codes'])||isset($_POST['names']))
	{
		$_SESSION['codesval']=$_POST['codes'];
		$_SESSION['namesval']=$_POST['names'];
		if(empty($_POST['codes'])&&empty($_POST['names']))
		{
			$_SESSION['codesval']='';
			$_SESSION['namesval']='';
			$myrow1=1;
			?><script type="text/javascript">alert("Please enter some search criteria!");
				setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
				</script><?
		}
		else
		{
			if(!empty($_POST['codes'])&&!empty($_POST['names']))
			{
				$condition="SELECT * FROM ".$tname." WHERE ".$scode." like'".$_POST['codes']."%' OR ".$sname." like'".$_POST['names']."%'";
			}
			else if(!empty($_POST['codes'])&&empty($_POST['names']))
			{
				$condition="SELECT * FROM ".$tname." WHERE ".$scode." like'".$_POST['codes']."%'";
			}
			else if(!empty($_POST['names'])&&empty($_POST['codes']))
			{
				$condition="SELECT * FROM ".$tname." WHERE ".$sname." like'".$_POST['names']."%'";
			}
			else
			{
				$condition="SELECT * FROM ".$tname." WHERE 1";
			}
			$refer=mysql_query($condition);
			$myrow1 = mysql_num_rows($refer);		
			$page = (int) (!isset($_GET["page"]) ? 1 : 1);
			$startpoint = ($page * $limit) - $limit;
			//to make pagination
			$statement = $tname;
			 //show records
			$starvalue = $myrow1;
		    $query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
			if($myrow1==0)	
			{
				?><script type="text/javascript">alert("Data not found!");
				setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); codes.focus(); }, 2000);
				</script><?
			}
		}
	}
}
else
{
	if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
	{		
		$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
		$startpoint = ($page * $limit) - $limit;
		$statement = $tname; 
		$startvalue= "";
		if($tname == "usercreation"){
			$query = mysql_query("SELECT * FROM {$statement} order by id LIMIT {$startpoint} , {$limit}");
		}
		else if($statement=="state")
		{
			//echo "SELECT * FROM $statement order by statename LIMIT $startpoint , $limit";
		$query = mysql_query("SELECT * FROM {$statement}  LIMIT {$startpoint} , {$limit}");
		}
		else
		{
		$query = mysql_query("SELECT * FROM {$statement}  LIMIT {$startpoint} , {$limit}");
		}
		//echo "SELECT * FROM $statement order by id LIMIT $startpoint , $limit";
		// echo $startpoint;
		// echo $limit;

		
	}
	else
	{
		if(!empty($_SESSION['codesval'])&&!empty($_SESSION['namesval']))
		{
			$condition="SELECT * FROM ".$tname." WHERE ".$scode." like'".$_SESSION['codesval']."%' OR ".$sname." like'".$_SESSION['namesval']."%'";
		}
		else if(!empty($_SESSION['codesval'])&&empty($_SESSION['namesval']))
		{
			$condition="SELECT * FROM ".$tname." WHERE ".$scode." like'".$_SESSION['codesval']."%'";
		}
		else if(!empty($_SESSION['namesval'])&&empty($_SESSION['codesval']))
		{
			$condition="SELECT * FROM ".$tname." WHERE ".$sname." like'".$_SESSION['namesval']."%'";
		}
		else
		{
			$condition="SELECT * FROM ".$tname." WHERE 1";
		}
		$refer=mysql_query($condition);
		$myrow1 = mysql_num_rows($refer);
		$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
		$startpoint = ($page * $limit) - $limit;
		//to make pagination
		$statement = $tname;
		//show records
		$starvalue = $myrow1;
		$query = mysql_query("{$condition} LIMIT {$startpoint} , {$limit}");
	}
}

// if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
	// {		
		// $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
		// $startpoint = ($page * $limit) - $limit;
		// $statement = $tname; 
		// $startvalue= ""; 
	//echo "SELECT * FROM {$statement} order by m_date desc LIMIT {$startpoint} , {$limit}";
		// if($tname == "usercreation"){
			// $query = mysql_query("SELECT * FROM {$statement} order by id LIMIT {$startpoint} , {$limit}");
		// }else if($tname=="state")
		// {
			//echo "SELECT * FROM $statement order by statename LIMIT $startpoint , $limit";
		// $query = mysql_query("SELECT * FROM {$statement} order by statename LIMIT {$startpoint} , {$limit}");
		// }else{
			// $query = mysql_query("SELECT * FROM {$statement} order by m_date desc LIMIT {$startpoint} , {$limit}");
		// }
	// }

?>