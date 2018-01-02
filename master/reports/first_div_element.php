	<?  if($pagename == "Service Compensation Claim"){
			$multiple = '';
			$height = 'height:75px;';
			$style_height = 'style="height:35px;"';
            $important = '<label style="color:#F00;">*</label>';
			$onchange= 'onChange="fsetvalue();"';
            $frn_option ='.Select.';
            $regstyle = "";
            $pgmultiple = 'multiple="multiple"';
            $pg_option ='.All Productgroup.';
            $pgimportant = "";
            $pgstyle = "";
            $div_style = "";
		}
        // else if($pagename == "Stock Ledger"){
        else if($pagename == "Retailer Model Wise Sales" || $pagename=="Retailer Month Wise Sales" || $pagename=="Retailer wise Day Sales"){
            $multiple = '';
            $height = 'height:75px;';
            $style_height = 'style="height:35px;"';
            $important = '<label style="color:#F00;">*</label>';
            $onchange= 'onChange="allfranchisee();"';
            $frn_option ='.Select.';
            $regstyle = "";
            $pgmultiple = 'multiple="multiple"';
            $pg_option ='.All Productgroup.';
            $pgimportant = "";
            $div_style = "";
            $pgstyle = ""; // $onchange= 'onChange="fsetvalue();"';
        }
		else if($pagename == "Retailer Category Detailed" || $pagename == "Retailer Category Summary"){
            $multiple = 'multiple="multiple"';
            $height = 'height:auto;';
            $style_height = '';
            $important = '';
            $onchange= 'onChange="drpfuncretailer();"';
            $frn_option ='.All Distributor.';
            $regstyle ="";
            $pgmultiple = 'multiple="multiple"';
            $pg_option ='.All Productgroup.';
            $pgimportant = "";
            $pgstyle = "";        
            $div_style = "";   // onChange="drpfuncretailer();"
        }
        else if($pagename== "GUI Sales Report Based on Distributor Name" || $pagename == "GUI Purchase Report Based on Distributor Name" || $pagename == "GUI Stock Opening and Closing Report"){
             $multiple = '';
            $height = 'height:75px;';
            $style_height = 'style="height:35px;"';
            $important = '<label style="color:#F00;">*</label>';
            $onchange= 'onChange="allfranchisee();"';
            $frn_option ='.Select.';
            $regstyle = 'style="height:inherit"';
            $pgmultiple = 'multiple="multiple"';
            $pg_option ='.All Productgroup.';
            $pgimportant = "";
            $pgstyle = 'style="height:inherit"';
            $div_style = "";
        }else if($pagename== "GUI Sales Report Based on Products" || $pagename == "GUI Purchase Report Based on Products"){
            $multiple = 'multiple="multiple"';
            $height = 'height:auto;';
            $style_height = 'style="height:inherit"';
            $important = '';
            $onchange= 'onChange="allfranchisee();"';
            $frn_option ='.All Primary Distributor.';
            $regstyle = 'style="height:inherit"';
            $pgmultiple = "";
            $pgstyle = 'style="height:30px"';
            $pgimportant = '<label style="color:#F00;">*</label>';
            $pg_option ='.Select.';
            $div_style = "";
        }
		else if($pagename == "WeeklySalesReportDwRw" || $pagename == "Range Report" || $pagename == "Reach Report"){
			$multiple = 'multiple="multiple"';
			$height = 'height:auto;';
			$style_height = '';
            $important = '';
			$onchange= 'onChange="drpfranchise();drpfuncdsr_primary();"';
            $frn_option ='.All Primary Distributor.';
            $regstyle = "";
            $pgmultiple = 'multiple="multiple"';
            $pg_option ='.All Productgroup.';
            $pgimportant = "";
            $pgstyle = "";
		}
        else{
			$multiple = 'multiple="multiple"';
			$height = 'height:auto;';
			$style_height = '';
            $important = '';
			$onchange= 'onChange="drpfranchise();"';
            $frn_option ='.All Primary Distributor.';
            $regstyle = "";
            $pgmultiple = 'multiple="multiple"';
            $pg_option ='.All Productgroup.';
            $pgimportant = "";
            $pgstyle = "";
            if($pagename == "Data Exchange"){
                $div_style = 'style="width:inherit"';
            }
            
		} ?>

<? if (($authen_row['usertype'])== 'Corporate') { ?> 
                                    <div id='region_div' style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Region</label>
                                    </div>
                                    <div id='regin_select_div' style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                    
                  <select name='region[]' id='region' <? echo $regstyle; ?> <? echo $div_style; ?> onChange="drpfunc();" multiple="multiple" >
                     <option value="0">.All Regions.</option>
                    <?
                                            $region_select = ($_POST['region']) ? $_POST['region'] : '';

                                            $list = mysql_query("SELECT regioncode, regionname FROM region order by regionname asc");

                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['regionname'] == $region_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                    <option value="<? echo $row_list['regioncode']; ?>"<? echo $selected; ?>> <? echo $row_list['regionname']; ?> </option>
                    <?
                                            }
                                            ?>
                    </select>
                  
                                    </div>
                                    <? if($pagename!="Zone State Sales Summary"){ ?>
                                   <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Primary Distributor</label><? echo $important; ?>
                                    </div>
                                    <div style="width:190px; <? echo $height; ?>  float:left;  margin-top:5px; margin-left:3px;">
                                        <select <? echo $style_height ; ?> <? echo $div_style; ?> name='primaryfranchise[]' id='primaryfranchise' <? echo $multiple; ?>  <? echo $onchange;?> >
                                            <option value="0"><? echo $frn_option; ?></option>
                                            <?
                                            $add_qry = '';
                                            $franchise_select = ($_POST['primaryfranchise']) ? $_POST['primaryfranchise'] : '';
                                               if($multiple == ""){
                                                    foreach ($_POST['primaryfranchise'] as $selectedOption2){
                                                        if($selectedOption2!="0")
                                                        {
                                                            $franchise_select = $selectedOption2;
                                                        }
                                                    }
                                                }
                                        $list = mysql_query("SELECT distinct(PrimaryFranchise) FROM franchisemaster where PrimaryFranchise!= '' order by PrimaryFranchise desc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';
                                                if ($row_list['PrimaryFranchise'] == $franchise_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['PrimaryFranchise']; ?>" <? echo $selected; ?>><? echo trim($row_list['PrimaryFranchise']); ?></option>
                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>   
                            
                                    <? } } ?>
                                    <? if (($authen_row['usertype'])== 'Others') {
                                        if($pagename!="Zone State Sales Summary"){ ?> 
                                         <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Primary Distributor</label><? echo $important; ?>
                                    </div>
                                    <div style="width:190px; <? echo $height ; ?>  float:left;  margin-top:5px; margin-left:3px;">
                                        <select <? echo $style_height ; ?> <? echo $div_style; ?>name='primaryfranchise[]' id='primaryfranchise' <? echo $multiple; ?>  <? echo $onchange;?> >
                                            <option value="0"><? echo $frn_option; ?></option>
                                            <?
                                            $add_qry = '';
                                            if ($region_select) {
                                                $add_qry = " AND Region = '" . $region_select."'";
                                            }
                                            if ($branch_select) {
                                                $add_qry .= " AND Branch = '" . $branch_select."'";
                                            }

                                            $franchise_select = ($_POST['primaryfranchise']) ? $_POST['primaryfranchise'] : '';
                                            $list = mysql_query("SELECT PrimaryFranchise, Franchisename FROM franchisemaster WHERE Branch IN $authen_branch order by PrimaryFranchise asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';
                                                if ($row_list['PrimaryFranchise'] == $franchise_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['PrimaryFranchise']; ?>" <? echo $selected; ?>><? echo trim($row_list['PrimaryFranchise']); ?></option>
                                                <?
                                                if($pagename == 'Purchase Order'){
                                                    $pfname = $pfname . "r.franchisename = '" . $row_list['PrimaryFranchise']."'  OR ". "\n";
                                                }else{
                                                    $pfname = $pfname . "franchisename = '" . $row_list['PrimaryFranchise']."'  OR ". "\n";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
								
                                  <? } } ?>
                    

                                     <? if($pagename != "Data Exchange" && $pagename != "Zone State Sales Summary" && $pagename != "Location Wise Stock Summary" && $pagename != "Day Wise Synch Status - Master Upload" && $pagename != "Day Wise Synch Status - Transaction Download" && $pagename != "Month Wise Synch Status - Master Upload" && $pagename != "Month Wise Synch Status - Transaction Download" && $pagename !="Range Report" && $pagename !="Reach Report" && $pagename !="WeeklySalesReportDwRw"){ ?> 
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Productgroup</label><? echo $pgimportant; ?>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                         
                                  
                                         <select name='Productgroup[]' id = 'Productgroup'  <? echo $pgstyle; ?> <? echo $pgmultiple; ?>  onChange="drpfunc2();">
                                            <option value="0"><? echo $pg_option; ?></option>
                                            <?
                                            $Productgroup_select = ($_POST['Productgroup']) ? $_POST['Productgroup'] : '';
                                             if($pgmultiple == ""){
                                                    foreach ($_POST['Productgroup'] as $selectedOption2){
                                                        if($selectedOption2!="0")
                                                        {
                                                            $Productgroup_select = $selectedOption2;
                                                        }
                                                    }
                                                }
                                            
                                           $qry="SELECT distinct (ProductCode) as ProductGroupCode ,(ProductGroup)as ProductGroup FROM productgroupmaster ORDER BY ProductGroup asc";
                                           
                                           $list1 = mysql_query($qry);
                                            while ($row_list1 = mysql_fetch_assoc($list1)) {
                                                $selected = '';

                                                if ($row_list1['ProductGroupCode'] == $Productgroup_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                  <option value="<? echo $row_list1['ProductGroupCode']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list1['ProductGroup']; ?>                                          
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
 

                                    </div>
                             
                                  <? 
                                     
                                  } ?>

                                <? if($pagename=="Zone State Sales Summary"){ ?>
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>State</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='state[]' id="state" multiple="multiple" onChange="allstate();">
                                            <option value="0">.All State.</option>
                                            <?
                                            $product_select = ($_POST['state']) ? $_POST['state'] : '';

                                            $list = mysql_query("SELECT distinct (statename) FROM state WHERE statename !='' order by statename asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['statename'] == $product_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['statename']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list['statename']; ?>
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>


                                    </div>


                                <? } ?>
						<? if($pagename=="WeeklySalesReportDwRw" || $pagename=="Range Report" || $pagename=="Reach Report")//  
						{ ?>
							<div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>DSR Code</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='dsr[]' id="dsr" multiple="multiple" onChange="alldsr();">
                                            <option value="0">.All DSR.</option>
                                            <?
                                            $dsr_select = ($_POST['dsr']) ? $_POST['dsr'] : '';

                                            $list = mysql_query("SELECT dsrcode FROM dsrcodemapping ORDER BY dsrcode");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['dsrcode'] == $product_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['dsrcode']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list['dsrcode']; ?>
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>


                                    </div>
						<? } ?>