<br />  <?php
  if(isset($_POST['Search']))
{
if($myrow1==0)	
{
		//	echo " No Record Found";
}
else 
{
	echo pagination($starvalue,$statement,$limit,$page);
}
}
else
{
	echo pagination($starvalue,$statement,$limit,$page);
}		?>