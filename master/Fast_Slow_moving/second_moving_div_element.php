<? if($pagename == "GUI Sales Report Based on Products"){
    $regstyle = 'style="height:inherit"';
}else{
    $regstyle = 'style="height:30px;"';
}
 if (($authen_row['usertype'])== 'Corporate') { ?> 
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Branch</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='branch[]' id='branch' <? echo $regstyle; ?>  onChange="drpfuncbrn();">
                                            <option value="0">.Select.</option>
                                            <?php
                                            $add_qry = '';
                                            if ($region_select) {
                                                $add_qry = " AND region = '" . $region_select."'";
                                            }
                                            $branch_select = ($_POST['branch']) ? $_POST['branch'] : '';
                                            foreach ($_POST['branch'] as $selectedOption2){
                                                    if($selectedOption2!="0")
                                                    {
                                                        $branch_select = $selectedOption2;
                                                    }
                                                }
                                            $list = mysql_query("SELECT branchcode, branchname FROM branch order by branchname asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['branchcode'] == $branch_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['branchcode']; ?>"<? echo $selected; ?>>
    <? echo $row_list['branchname']; ?>
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <? } ?>
                                    <? if (($authen_row['usertype'])== 'Others') { ?> 
                                       <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Branch</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='branch[]' id='branch' <? echo $regstyle; ?>  onChange="drpfuncbrn();">
                                            <option value="0">.Select.</option>
                                            <?php
                                            $add_qry = '';
                                            if ($region_select) {
                                                $add_qry = " AND region = '" . $region_select."'";
                                            }
                                            $branch_select = ($_POST['branch']) ? $_POST['branch'] : '';
                                            foreach ($_POST['branch'] as $selectedOption2){
                                                    if($selectedOption2!="0")
                                                    {
                                                        $branch_select = $selectedOption2;
                                                    }
                                                }
                                            $list = mysql_query("SELECT branchcode, branchname FROM branch WHERE branchcode IN $authen_branch order by branchname asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['branchcode'] == $branch_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['branchcode']; ?>"<? echo $selected; ?>>
    <? echo $row_list['branchname']; ?>
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <? } ?>
									
									<?  if($pagename == "Retailer Category Detailed" || $pagename == "Retailer Category Summary") { ?>
                                         <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Retailer Name</label>
                                    </div>
                                    
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                       <select name='RetailerName[]' id = 'RetailerName'  onchange="allretailername();">
                                            <option value="0">.All Retailer Name.</option>
                                            <?
                                            $Productsegment_select = ($_POST['RetailerName']) ? $_POST['RetailerName'] : '';
                                           $list2 = mysql_query("SELECT distinct (RetailerName) as RetailerName FROM retailermaster ORDER BY RetailerName asc");
                                            while ($row_list2 = mysql_fetch_assoc($list2)) {
                                                $selected = '';

                                                if ($row_list2['RetailerName'] == $Productsegment_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                
                                                <option value="<? echo $row_list2['RetailerName']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list2['RetailerName']; ?> 
                                                
                                                        
                                                </option>

                                                <?
                                            }
                                            ?>
                                      </select>
                                    </div>

                                    <? } ?>


                                    <? if($pagename != "Fast Moving GUI Report" && $pagename != "Slow Moving GUI Report") { 
                                      ?> 
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Productgroup</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                         <select name='Productgroup[]' id = 'Productgroup' <? echo $regstyle; ?> >
                                            <option value="0">.Select.</option>
                                            <?
                                            $Productgroup_select = ($_POST['Productgroup']) ? $_POST['Productgroup'] : '';
                                            foreach ($_POST['Productgroup'] as $selectedOption2){
                                                    if($selectedOption2!="0")
                                                    {
                                                        $Productgroup_select = $selectedOption2;
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
                                  
								

                                  
                                    <!--
                                       <? /*if($pagename == "Purchase Report" || $pagename == "Purchase Summary") { ?> 
                                      <div style="width:145px; height:auto; float:left; margin-top:5px; margin-left:3px;">
                                     <label>Purchase Type</label>
                                    </div>
                                    <div style="width:185px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                       <select name='Voucher[]' id="Voucher" multiple="multiple" onChange="allVoucher();">
                                            <option value="0">-- Select --</option>
                                            <option value="Prorata Material Receipt">Prorata Material Receipt</option>
                                            <option value="Regular Purchase">Regular Purchase</option>
                                            <option value="Scheme Purchase">Scheme Purchase</option>
                                            <option value="Scrap Purchase">Scrap Purchase</option>
                                            <option value="Warranty Material Receipt">Warranty Material Receipt</option>
                                        </select>
                                  </div>
                                  <? }*/ ?>-->
                                  <? 
                                     
                                  } ?>

                                     <? if($pagename == "Fast Moving GUI Report" || $pagename == "Slow Moving GUI Report"){ ?>
                                          <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Chart Types</label><label style="color:#F00;">*</label>
                                    </div>
                                    <div style="width:190px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                        <? $chart_select = $_POST['chart_types'] ? $_POST['chart_types'] : '';?>
                                        <select name='chart_types' id = 'chart_types'>
                                           <option value="Column" <? if($chart_select == "Column") echo "selected"; else ''; ?> >Column Chart</option>
                                            <option value="Pie" <? if($chart_select=="Pie") echo "selected"; else ''; ?> >Pie Chart</option>
                                            <option value="Bar" <? if($chart_select=="Bar") echo "selected"; else ''; ?> >Bar Chart</option>
                                            <option value="Line" <? if($chart_select=="Line") echo "selected"; else ''; ?> >Line Chart</option>
                                        </select>
                                    </div>

                                    <? } ?>
         
