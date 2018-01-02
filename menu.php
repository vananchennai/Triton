 <!--First Block - Logo-->
<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:130px; float:none;">
<!--        <div style="width:200px; height:180px; float:left; background-image:url(../../img/logo_amaron.png);"-->
         <div style="padding-top: 50px;float:left;  color:#29648d";>
             <h1>INVOICE DESK</h1>
           </div>
  <!--userid and emp name Block - start-->
		<div style="width:350px; height:130px; float:right;">
        
                             <!--Row1 -->  
           <div style="width:340px; height:30px; float:left; margin-top:40px; margin-left:3px;" class="contval">
          <label><b>User :</b><? echo $_SESSION['username'].' ['.$_SESSION['employeeusername'].']';?></label>
            </div>
			
            <div style="width:340px; height:30px;  float:left;  margin-top:2px; margin-left:8px;" class="contval">
             <div id='csslogout'> <ul> <li>  <a href="/<? echo $_SESSION['mainfolder']; ?>/logout.php"><b>Logout</b></a> </li> </ul>  </div>
               </div>
         </div>
         <!--userid and emp name Block - End-->
     </div>       
</div>
<!--First Block - End-->

<!--Second Block - Menu-->
<!--<div style="width:100%; height:50px; float:none; background:url(../../img/menubg.jpg) repeat-x;">-->
    <div style="width:100%; height:50px; float:none; background-color:#29648d;">
    <div style="width:980px; height:50px; float:none;"> 
      
    


 <div id='cssmenu'>


<ul>
<? 
	$menu_query = "SELECT * FROM menu where maccess = '1' order by sorting";
	$menu_query = mysql_query($menu_query) or die (mysql_error());
	$menuCount=mysql_num_rows($menu_query);
	$headcount = 0;
	$sub1count = 0;
	$sub2count = 0;
	$sub3count = 0;
	$prevhead = "";
	$prevsubmenu1 = "";
	$prevsubmenu2 = "";
	$prevsubmenu3 = "";
	$cursubmenu1 = "";
	$cursubmenu2 = "";
	$cursubmenu3 = "";
	$curhead = "";
	while($rowmenu = mysql_fetch_array($menu_query))
	{
		$path = $_SESSION['mainfolder']."".$rowmenu['path'];
		
		if($headcount == 0 && $rowmenu['hmenu'] != $prevhead)
		{
			echo "<li class='has-sub' ><a href=/".$path."><span>".$rowmenu['hmenu']."</span></a>";
			$headcount++;
		}
		else
		{
			if($rowmenu['hmenu'] != $prevhead)
			{
				if($sub2count!=0)
				{
					echo "</li></ul></li>";
				}
				if($sub1count!=0)
				{
					echo "</ul>";
				}
				
				echo "</li><li class='has-sub' ><a href=/".$path."><span>".$rowmenu['hmenu']."</span></a>";
				$sub1count = 0;
				$sub2count = 0;
				$sub3count = 0;
			}
			else
			{
				if($sub1count == 0 && $rowmenu['submenu1'] != $prevsubmenu1)
				{
					echo "<ul><li  class='has-sub' ><a style='text-align:left' href=/".$path.">".$rowmenu['submenu1']."</a>";
					$sub1count++;
				}
				else
				{
					if($rowmenu['submenu1'] != $prevsubmenu1)
					{
						if($sub2count!=0)
						{
							echo "</ul>";
						}
						echo "</li><li class='has-sub' ><a style='text-align:left' href=/".$path."><span>".$rowmenu['submenu1']."</span></a>";
						$sub2count=0;
					}
					else
					{
						if($sub2count == 0 && $rowmenu['submenu2'] != $prevsubmenu2)
						{
							echo "<ul><li class='has-sub' ><a style='text-align:left' href=/".$path.">".$rowmenu['submenu2']."</a>";
							$sub2count++;
						}
						else
						{
							if($rowmenu['submenu2'] != $prevsubmenu2)
							{
								if($sub3count!=0)
								{
									echo "</ul>";
								}
								echo "</li><li class='has-sub' ><a style='text-align:left' href=/".$path.">".$rowmenu['submenu2']."</a>";
								$sub3count = 0;
							}
							else
							{
								 if($sub3count == 0 && $rowmenu['submenu3'] != $prevsubmenu3)
								{
									echo "<ul><li><a style='text-align:left' href=/".$_SESSION['mainfolder']."".$rowmenu['path'].">".$rowmenu['submenu3']."</a>";
									$sub3count++;
								}
								else
								{
									if($rowmenu['submenu3'] != $prevsubmenu3)
									{
										echo "</ul></li><li><a style='text-align:left' href=/".$_SESSION['mainfolder']."".$rowmenu['path'].">".$rowmenu['submenu3']."</a>";
									}
								} 
							}
						}
					}
				}
			}
				
		}
		$prevhead = $rowmenu['hmenu'];
		$prevsubmenu1 = $rowmenu['submenu1'];
		$prevsubmenu2 = $rowmenu['submenu2'];
		$prevsubmenu3 = $rowmenu['submenu3'];
		
	};
?>
</ul></li>
</ul>
</div>
 </div>       
</div>
<!--Second Block - Menu -End -->

