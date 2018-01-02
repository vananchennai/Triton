<?php 
include '../../functions.php';
sec_session_start();
 require_once '../../masterclass.php';
include("../../header.php");
// Include database connection and functions here.

if(login_check($mysqli) == false) {
 
  header('Location:../../index.php');// Redirect to login page!
 
} else
{
	global $branchcode,$branchname,$country,$region,$state,$tname,$scode,$sname;
	$scode = 'branchcode';
	$sname = 'branchname';
	$tname	= "branch";
	require_once '../../searchfun.php';
    require_once '../../masterclass.php'; // Include The News Class
	require_once '../../paginationfunction.php';
	
	$news = new News(); // Create a new News Object
	$newsRecordSet = $news->getNews($tname);
	$pagename = "Branch Master";
	$validuser = $_SESSION['username'];
	$selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");//$news->getNewsById($validuser,$pagename);
	$row = mysql_fetch_array($selectvar);
  
 	if (($row['viewrights'])== 'No')
	{
		header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
	}
	if(isset($_POST['permiss'])) // If the submit button was clicked
    {
		?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'branchmaster.php');
			//setInterval(function(){document.location='branchmaster.php';},2000);
			//document.location='branchmaster.php';	
			</script>
         <?
		
	}
	if(isset($_GET['permiss']))
	{
	?>
            <script type="text/javascript">
			alert("you are not allowed to do this action!",'branchmaster.php');
			//setInterval(function(){document.location='branchmaster.php';},2000);
			</script>
         <?
		
	}
	
	
    if(isset($_POST['Save'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
        $post['branchcode'] = str_replace('&', 'and',$_POST['branchcode']);
		$post['branchname'] = str_replace('&', 'and',$_POST['branchname']);
		$countrysmallCode=mysql_query("SELECT * FROM countrymaster where countryname='".$_POST['country']."'");
			$countrysmallfetch=mysql_fetch_array($countrysmallCode);
			
			$post['country'] = $countrysmallfetch['countrycode'];
			//echo $smallfetch['countrycode'];
        //$post['country'] = $_POST['country'];
		//$post['region'] = $_POST['region'];
 		//$post['state'] = $_POST['state'];
		$smallCode=mysql_query("SELECT * FROM region where RegionName='".$_POST['region']."'");
			$smallfetch=mysql_fetch_array($smallCode);
			
			$post['region'] = $smallfetch['RegionCode'];
			//echo $smallfetch['RegionCode'];
		$branchcode=str_replace('&', 'and',$_POST['branchcode']);
		$branchname=str_replace('&', 'and',$_POST['branchname']);
		$country=$_POST['country'];
		$region=$_POST['region'];
		//$state=$_POST['state'];
		
        // This will make sure its displayed
		if(!empty($_POST['branchcode'])&&!empty($_POST['branchname'])&&!empty($_POST['country'])&&!empty($_POST['region']))
		{  
	$p1=strtoupper( preg_replace('/\s+/', '',$post['branchcode']));
	$p2=strtoupper( preg_replace('/\s+/', '',$post['branchname']));
	$cnduplicate=0;
	$repqry="SELECT REPLACE( `branchcode` ,  ' ',  '' ) AS branchcode, REPLACE(  `branchname` ,  ' ',  '' ) AS branchname FROM branch where branchname = '".$p2."' or branchname = '".$post['branchname']."' or branchcode = '".$p2."' or branchcode = '".$post['branchname']."' or branchcode = '".$p1."' or branchcode = '".$post['branchcode']."' or branchname = '".$p1."' or branchname = '".$post['branchcode']."'";
	$repres= mysql_query($repqry) or die (mysql_error());
	$cnduplicate=mysql_num_rows($repres);
			
		 
		// $selectval = mysql_fetch_array($repres);
		
		
		if($cnduplicate>0 || ($post['branchcode']==$post['branchname']))
		{
		?>
            <script type="text/javascript">
			alert("Duplicate entry!");
			//document.location='branchmaster.php';
			</script>
         <?
		}
		else
		{
			 $post['user_id'] = $_SESSION['username'];
			 date_default_timezone_set ("Asia/Calcutta");
			 $post['m_date']= date("y/m/d : H:i:s", time());				
			 $news->addNews($post,$tname);
		?>
            <script type="text/javascript">
			alert("Created Sucessfully!",'branchmaster.php');
			//setInterval(function(){document.location='branchmaster.php';},2000);
			//document.location='branchmaster.php';
			</script>
            <?
        }
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");//document.location='branchmaster.php';
			</script>
            <?
		}
    }
	 if(isset($_POST['Update'])) // If the submit button was clicked
    {
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
		 $post['branchcode'] = $_POST['branchcode'];
		$post['branchname'] = str_replace('&', 'and',$_POST['branchname']);
		$countrysmallCode=mysql_query("SELECT * FROM countrymaster where countryname='".$_POST['country']."'");
		$countrysmallfetch=mysql_fetch_array($countrysmallCode);
			
		$post['country'] = $countrysmallfetch['countrycode'];
      //  $post['country'] = $_POST['country'];
		//$post['region'] = $_POST['region'];
 		//$post['state'] = $_POST['state'];
		$smallCode=mysql_query("SELECT * FROM region where RegionName='".$_POST['region']."'");
		$smallfetch=mysql_fetch_array($smallCode);
			
		$post['region'] = $smallfetch['RegionCode'];
		$branchcode=$_POST['branchcode'];
		$branchname=str_replace('&', 'and',$_POST['branchname']);
		$country=$_POST['country'];
		$region=$_POST['region'];
		//$state=$_POST['state'];
		
        // This will make sure its displayed
		if(!empty($_POST['branchcode'])&&!empty($_POST['branchname'])&&!empty($_POST['country']))
		{  
		$codenamedcheck=0;
		if($_SESSION['oemsessionval1']!=$branchname)
		{
		$p2=strtoupper( preg_replace('/\s+/', '',$post['branchname']));
		$repqry="SELECT REPLACE(  `branchname` ,  ' ',  '' ) AS branchname  FROM  `branch` where branchname = '".$p2."' or branchname = '".$post['branchname']."' or branchcode = '".$p2."' or branchcode = '".$post['branchname']."'";
		$repres= mysql_query($repqry) or die (mysql_error());
		$codenamedcheck=mysql_num_rows($repres);
		}
			if($codenamedcheck>0)
			{
			?>
            <script type="text/javascript">
			alert("Duplicate entry!");
			</script>
       	  	<?
			}
	
			
			else
			{
				$myrow1=0;	
			$result="SELECT * FROM branch where branchcode ='".$_POST['branchcode']."'";
	    $sql1 = mysql_query($result) or die (mysql_error());
 		$myrow1 = mysql_num_rows($sql1);//mysql_fetch_array($retval);
		if($myrow1>0)
		{
						$post['user_id'] = $_SESSION['username'];
						 date_default_timezone_set ("Asia/Calcutta");
						 $post['m_date']= date("y/m/d : H:i:s", time());	
						$wherecon= "branchcode ='".$_POST['branchcode']."'";
						$news->editNews($post,$tname,$wherecon);
		unset($_SESSION['oemsessionval1']);
		?>
        
            <script type="text/javascript">
			alert("Updated Sucessfully!",'branchmaster.php');
			//setInterval(function(){document.location='branchmaster.php';},2000);
			//document.location='branchmaster.php';
			</script>
            <?
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("You are not allowed to save a new record using update!");
			</script>
         <?
		}
		}
		}
		else
		{
			?>
            <script type="text/javascript">
			alert("Enter Mandatory Fields!");
			</script>
            <?
		}
	}
	
/// EDIT LINK FUNCTION 
if(!empty($_GET['edi']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
$prmaster =$_GET['edi'];

//$cont->connect();
$result=mysql_query("SELECT * FROM branch where branchcode ='".$prmaster."'");

$myrow1 = mysql_num_rows($result);//mysql_fetch_array($retval);

		if($myrow1==0)	
		{
			?>
            <script type="text/javascript">
			alert("No Data Found!");document.location='branchmaster.php';
			</script>
   			<?
		}
		else
		{
		   $myrow = mysql_fetch_array($result);
		
		   $branchcode = $myrow['branchcode'];
		   $branchname = $myrow['branchname'];
		    $countrypgg= mysql_query("select countryname from countrymaster where countrycode='".$myrow['country']."' ")  ;
		$countryrecord11 = mysql_fetch_array($countrypgg);
        $country = $countryrecord11['countryname'];
		   //$country = $myrow['country'];
		  // $region = $myrow['region'];
		   $pgg= mysql_query("select RegionName from region where RegionCode='".$myrow['region']."' ")  ;
		$record11 = mysql_fetch_array($pgg);
        $region = $record11['RegionName'];
		  // $state = $myrow['state'];
		  
		 $_SESSION['oemsessionval1']= $myrow['branchname'];
		}
		$prmaster = NULL;
}

	
	// Check if delete button active, start this 

	if(isset($_POST['Delete']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	if(!isset($_POST['checkbox']))
	{
			?>
		    <script type="text/javascript">
			alert("Select data to delete!");document.location='branchmaster.php';
			</script>
			<?
	}

else
{
		 $checkbox = $_POST['checkbox']; //from name="checkbox[]"
		 $countCheck = count($_POST['checkbox']);
		for($i=0;$i<$countCheck;$i++)
		{
		$prodidd = $checkbox[$i];
		///$prodid= $_POST['checkbox'];
					$prodidd = $checkbox[$i];
			$newvar=explode("~",$prodidd);
			$var1=$newvar[0];
			$var2=$newvar[1];
			//$q1="SELECT * FROM branch WHERE branchname='".$var2."' and (SELECT '".$var2."') IN (SELECT Branch FROM pricelistlinking WHERE EXISTS (SELECT Branch FROM franchisemaster WHERE EXISTS (SELECT Branch FROM suppliermaster WHERE EXISTS (SELECT branch FROM employeemaster))))";
			/* $q1="select branchcode
FROM branch
WHERE branchcode='".$var1."' and EXISTS(
 SELECT Branch
FROM  `franchisemaster` 
WHERE Branch='".$var1."'
)
OR exists(

SELECT Branch
FROM suppliermaster
WHERE Branch =  '".$var1."'
)
OR exists(

SELECT branch
FROM employeemaster
WHERE branch =  '".$var1."'
)
OR exists(
SELECT Branch
FROM pricelistlinking
WHERE Branch=  '".$var1."') "; */
$q1="select branchcode
FROM branch
WHERE branchcode='".$var1."' and EXISTS(
 SELECT Branch
FROM  `franchisemaster` 
WHERE Branch='".$var1."'
) OR exists(
SELECT branch
FROM employeemaster
WHERE branch =  '".$var1."')";

 $repres= mysql_query($q1) or die (mysql_error());
 $myrow1 = mysql_num_rows($repres);
 if($myrow1==0)
 {
	 						$wherecon= "branchcode ='".$var1."'";
							$news->deleteNews($tname,$wherecon);
			
							?>
							<script type="text/javascript">
							alert("Deleted  Successfully!",'branchmaster.php');
						//setInterval(function(){document.location='branchmaster.php';},2000);
							//document.location='branchmaster.php';
							</script>
							<?
 }
 else
 {
	 						?>
           					<script type="text/javascript">
							alert("you can't delete already used in other forms!",'branchmaster.php');
							</script>
 							<?
 }
		}
}
}




if(isset($_POST['Cancel']))
{
	unset($_SESSION['codesval']);
	unset($_SESSION['namesval']);
	header('Location:branchmaster.php');
}

?>
<!--<script type="text/javascript"> 
 
		jQuery(document).ready(function(){
			$('#zipsearch').autocomplete({source:'inc/branchsuggest.php', minLength:1});
		});
 
	</script>-->
<script type="text/javascript">

$(function() {
function split( val ) {
return val.split( /,\s*/ );
}
        function extractLast( term ) {
            return split( term ).pop();
        }
 
        $( "#tags" )
            // don't navigate away from the field on tab when selecting an item
            .bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB &&
                        $( this ).data( "autocomplete" ).menu.active ) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function( request, response ) {
                    $.getJSON( "inc/statenamefetch.php", {
                        term: extractLast( request.term )
                    }, response );
                },
                search: function() {
                    // custom minLength
                    var term = extractLast( this.value );
                    if ( term.length < 1 ) {
                        return false;
                    }
                },
                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                select: function( event, ui ) {
                    var terms = split( this.value );
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    this.value = terms.join( ", " );
                    return false;
                }
            });
    });
 
 
       var url = "inc/autofetch.php?param=";
        var http;
function GetHttpObject()
{
if (window.ActiveXObject)
return new ActiveXObject("Microsoft.XMLHTTP");
else if (window.XMLHttpRequest)
return new XMLHttpRequest();
else
{
alert("Your browser does not support this functionality.");

return null;
}
}

function newbrfunc()
{
		var e = document.getElementById("region"); 
		var er=e.options[e.selectedIndex].value;
		var ddlArray= new Array();
		var ddl = document.getElementById('countrylist');
		var tt;
		for (i = 0; i < ddl.options.length; i++) 
		{
			ddlArray[i] = ddl .options[i].value;
			var ty = ddlArray[i].split("~");
			var p =ty[0];
			var p2 =ty[1];
			
			if(p==er)
			{
				tt=p2;
			}
			else if(er=="")
			{
				tt="";
			}
			
			document.getElementById("country").value=tt;
		}
}

        function getagentids1() 
        { 
            http=GetHttpObject();
              
if (http !=null)
{       //var idValue = document.getElementById("ProductCode").options.;
          
        var idValue = document.getElementById("region").value; 
//           
           var myRandom = parseInt(Math.random()*99999999); 
        
       //  cache buster

        http.open("GET", url + escape(idValue)+  "&rand=" + myRandom, true); 
        http.onreadystatechange = handleHttpResponse1; 
        http.send(null);
        
}
        }
 function handleHttpResponse1()
  { 
  if (http.readyState == 4)
   { 
   results = http.responseText;
    var testing=results;
     
      var output=testing.replace("Resource id #5","");
	 // var b=new Array();
	 //  var c=output.split("+");
	
    // document.all("Productdescription").options.selectedIndex = results; 
     //document.getElementById("region").value=c[0];
  document.getElementById("country").value=output;
 http.responseText='';
    } 
    } 


function validatebranchcode(key)
{
var object = document.getElementById('branchcode');
if (object.value.length <15 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 15 characters");
	toutfun(object);
return false;
}
}


function validatebranchname(key)
{
var object = document.getElementById('branchname');
if (object.value.length <50 || key.keycode==8 || key.keycode==46)
{
	
return true;
}
else
{
	alert("Enter only 50 characters");
	toutfun(object);
return false;
}
}

</script>
<title><?php echo $_SESSION['title']; ?> || Branch Master</title>
</head>
 <?php 
  if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{
 if(!empty($_GET['edi'])){?>
 
 <body class="default" onLoad="document.form1.branchname.focus()">

<? }else{?>


<body class="default" onLoad="document.form1.branchcode.focus()">

 <? }  
}else{?>
<body class="default" onLoad="document.form1.codes.focus()">

 <? } ?>
 <center>

<?php include("../../menu.php")?>

<!--Third Block - Container-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" action="<?php $_PHP_SELF ?>" name="form1" id="frm1">
                    <table style="display:none;" >

                                         <tr >
                                         <td>
                                        
    <select  name="countrylist" id="countrylist"  >
    <?
    
    $que = mysql_query("SELECT * FROM view_region1");
    
		while( $record = mysql_fetch_array($que))
		{
			echo "<option value=\"".$record['RegionName']."~".$record['countryname']."\">".$record['RegionName']."~".$record['countryname']."\n "; 
		}
    
    ?>
    </select>
                                      </td>

                                      </tr>
</table>
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
						<div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
						<p>Branch Master</p>
						</div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:400px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Branch Code</label><label style="color:#F00">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <?
                              if(!empty($_GET['edi']))
                              {
                              ?>
                              <input type="text" name="branchcode" style="border-style:hidden; background:#f5f3f1;"  readonly="readonly" value="<?php echo $branchcode;?>" onChange="return codetrim(this)" />
                             
                              <?
							  
                              }
							  else
							  { ?>
                                <input type="text" name="branchcode" id="branchcode" maxlength="15" value="<?php echo $branchcode;?>"  onChange="return codetrim(this)" onKeyUp="return validatebranchcode(event)" />
                                  <? }?>
                                  
                                  
                                   
                                   
                               </div>
 							<!--Row1 end-->
                             <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Branch Name</label><label style="color:#F00">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                      <input type="text" name="branchname" id="branchname" maxlength="50" value="<?php echo $branchname;?>" onChange="return trim(this)" onKeyUp="return validatebranchname(event)" />
                               </div>
                             <!--Row2 -->  
                      
 							<!--Row2 end--> 
                               <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Region</label><label style="color:#F00">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <select name="region" id="region" onChange="newbrfunc()" >
           <option value="<?php echo $region;?>"><? if(!empty($region)){ echo $region;}else{?> ----Select---- <? } ?></option>
                                     <?
                                                                                
                                        $que = mysql_query("SELECT RegionName FROM region order by RegionName asc");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
									  if($region!=$record['RegionName'])
									  {	      
                                       echo "<option value=\"".$record['RegionName']."\">".$record['RegionName']."\n "; 
									  }
                                     }
                                    ?>
                                          </select>   
                                       </div>
                               <!--Row3 -->
                             
                            <div style="width:100px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>country</label><label style="color:#F00">*</label>
                               </div>
                              <div style="width:245px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                               <input type="text" name="country" id="country" value="<?php echo $country;?>" readonly="readonly" style="border-style:hidden; background:#f5f3f1;" onFocus="addbutton.focus();" />
      						 </div>
                           
                                                  
                           </div>                             
                     <!-- col1 end --> 
                     
                     <!-- col2 -->   
                           <div style="width:200px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">                                      <!--Row1 -->  
                              <!-- <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>Branch Name</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                      <input type="text" name="name" value=""/>
                               </div>-->
 							<!--Row1 end-->
                            
                           </div>                             
                     <!-- col2 end--> 
                     
                     <!-- col3 -->   
                           <div style="width:200px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                                 
                            
                            </div>      
                     <!-- col3 --> 
                                                              
                    </div>
                  
                
				</div>
                <!-- main row 1 end-->
                
                
                    <!--Main row 2 start-->
                <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:-50px;">
                             
						<div style="width:235px; height:50px; float:left;  margin-left:14px; margin-top:0px;" id="center1">
						   
                          <div style="width:100px; height:32px; float:left; margin-top:16px; margin-left:10px;" >
                    <?php      if(!empty($_GET['edi']))
					{?>
						<input name="<?php if(($row['editrights'])=='Yes') echo 'Update'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" id="addbutton">
					<? }
					else
					{?>
						<input name="<?php if(($row['addrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" id="addbutton" value="Save" >
				          <? } ?>
				           </div>
                           
                           <div style="width:100px; height:32px; float:left;margin-top:16px; margin-left:10px;">
						  <input name="Cancel" type="submit" class="button" value="Reset">
				           </div>                            
                                                   
				     </div>	
                         
                          <div style="width:640px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:80px; height:30px; float:left; margin-left:3px; margin-top:16px;" >
                                  <label>Branch Code</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;">
                                  <input type="text" name="codes" id="codes" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['codesval']?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                               <div style="width:80px; height:30px; float:left; margin-left:16px; margin-top:16px;">
                                  <label>Branch Name</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:3px; margin-top:16px;" >
                                  <input type="text" name="names" onKeyPress="searchKeyPress(event);" value="<? echo $_SESSION['namesval']?>"/>
                               </div>
                             <!--Row2 end-->
                             
                             <div style="width:83px; height:32px; float:left; margin-top:16px; margin-left:16px;">
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>
                               </div>  
                          </div> 
                </div>
                
                <!--Main row 2 end-->
            
             <!-- form id start end-->      
              <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px; overflow:auto;" class="grid">
                   
                  <table id="datatable1" align="center" class="sortable" bgcolor="#FF0000" border="1" width="900px"><tr>
        <?  if(($row['deleterights'])=='Yes')
	 {
	?>    
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="8px">
     <input type='checkbox' id="checkall" name='checkall' onclick='checkedAll(frm1);'></td>
       <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center" width="12px">Action</td>
      <? 
	  } 
	  ?>
     <td style="font-weight:bold;">Branch Code</td>
     <td style="font-weight:bold;">Branch Name</td>
     <td style="font-weight:bold;">Region</td>
     <td style="font-weight:bold;">Country</td>
     
     <!--<td style="font-weight:bold;">State</td>--></tr>
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($query))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
    ?>
    
     <tr>
           <?  if(($row['deleterights'])=='Yes')
	 {
	?>  
     <td style="font-weight:bold; text-align:center;"  bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" onChange="test();" value="<? echo $record['branchcode']."~".$record['branchname'];
 ?>"></td>
    <? 
   }
    if(($row['editrights'])=='Yes') 
	  { 
	 ?>
  <td style="font-weight:bold; text-align:center" bgcolor="#FFFFFF" align="left" valign="top"> <a style="color:#0360B2" name="edit" href="branchmaster.php?edi=<?= $record['branchcode'];?>">Edit</a></td>
 <? 
	  } 
	  ?>
    <td  bgcolor="#FFFFFF">
        <?=$record['branchcode']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left" valign="top">
        <?=$record['branchname']?>
    </td>
     <td  bgcolor="#FFFFFF"  align="left" valign="top">
     <? $check1= mysql_query("select RegionName from region where RegionCode='".$record['region']."' ")  ;
		$check1record = mysql_fetch_array($check1);
       echo $check1record['RegionName']; 
	 ?>
        
    </td>
    <td  bgcolor="#FFFFFF"  align="left" valign="top">
   <? $check= mysql_query("select countryname from countrymaster where countrycode='".$record['country']."' ")  ;
		$checkrecord = mysql_fetch_array($check);
       echo $checkrecord['countryname']; ?>
       
    </td>
   
   <?php /*?> <td  bgcolor="#FFFFFF"  align="left" valign="top">
        <?=$record['state']?>
    </td><?php */?>
  
   </tr>
  <?php
      }
  ?>
  
<?php
  if(isset($_POST['Search']))
{
if($myrow1==0)	
{?>
		<? echo '<tr ><td colspan="11" align="center" bgcolor="#FFFFFF" style="color:#F00"  >No Records Found</td></tr>'; ?>	
<? } }?>
</table>
</div>

<?php include("../../paginationdesign.php")?>
        </form>
        </div> 
          
     </div>       
</div>
<!--Third Block - Menu -Container -->


<!--Footer Block -->
<div id="footer-wrap1">
        <?php include("../../footer.php")?>
  </div>
<!--Footer Block - End-->
</center></body>
</html>
<?
}
?>
