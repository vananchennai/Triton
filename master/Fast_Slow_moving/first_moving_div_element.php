	<?  if($pagename == "Fast Moving GUI Report" || $pagename == "Slow Moving GUI Report"){
			// $multiple = '';
			// $height = 'height:30px;';
			// $style_height = 'style="height:inherit;"';
   //          $style_height = '';
            // $important = '<label style="color:#F00;">*</label>';
			// // $onchange= 'onChange="fsetvalue();"';
   //          $onchange= '';
   //          $frn_option ='.Select.';
   //          $regstyle = 'style="height:30px"';
            $multiple = 'multiple="multiple"';
            $height = 'height:auto;';
            $style_height ='style="height:30px;"';
            $important = '';
            // $onchange= 'onChange="allfranchisee();"';
            $onchange= '';
            $frn_option ='.Select.';
            $regstyle = 'style="height:30px;"';
		}
        // else if($pagename == "Stock Ledger"){
        else if($pagename == "Retailer Model Wise Sales" || $pagename=="Retailer Month Wise Sales" || $pagename=="Retailer wise Day Sales"){
            $multiple = '';
            $height = 'height:75px;';
            $style_height = 'style="height:35px;"';
            $important = '<label style="color:#F00;">*</label>';
            $onchange= 'onChange="allfranchisee();"';
            $frn_option ='.Select.';
            // $onchange= 'onChange="fsetvalue();"';
        }
		else if($pagename == "Retailer Category Detailed" || $pagename == "Retailer Category Summary"){
            $multiple = 'multiple="multiple"';
            $height = 'height:auto;';
            $style_height = '';
            $important = '';
            $onchange= 'onChange="drpfuncretailer();"';
            $frn_option ='.All Distributor.';
           // onChange="drpfuncretailer();"
        }
        else{
			$multiple = 'multiple="multiple"';
			$height = 'height:auto;';
			$style_height ='style="height:30px;"';
            $important = '';
			// $onchange= 'onChange="allfranchisee();"';
            $onchange= '';
            $frn_option ='.Select.';
            $regstyle = 'style="height:30px;"';
		} ?>

<? if (($authen_row['usertype'])== 'Corporate') { ?> 
                                    <div id='region_div' style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Region</label>
                                    </div>
                                    <div id='regin_select_div' style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                    
                  <select name='region[]' id='region' onChange="drpfuncreg();"  <? echo $regstyle;?> >
                     <option value="0">.Select.</option>
                    <?
                                            $region_select = ($_POST['region']) ? $_POST['region'] : '';
                                             foreach ($_POST['region'] as $selectedOption2){
                                                    if($selectedOption2!="0")
                                                    {
                                                        $region_select = $selectedOption2;
                                                    }
                                                }
                                            $list = mysql_query("SELECT regioncode, regionname FROM region order by regionname asc");

                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['regioncode'] == $region_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                    <option value="<? echo $row_list['regioncode']; ?>"<? echo $selected; ?>> <? echo $row_list['regionname']; ?> </option>
                    <?
                                            }
                                            ?>
                    </select>
                  
                                    </div>
                                   <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Distributor Name</label><? echo $important; ?>
                                    </div>
                                    <div style="width:190px; <? echo $height; ?>  float:left;  margin-top:5px; margin-left:3px;">
                                        <select <? echo $style_height ; ?> name='franchise[]' id='franchise'  <? echo $onchange;?> >
                                            <option value="0"><? echo $frn_option; ?></option>
                                            <?
                                            $add_qry = '';
                                            $franchise_select = ($_POST['franchise']) ? $_POST['franchise'] : '';
                                           // if($multiple == ""){
                                               foreach ($_POST['franchise'] as $selectedOption2){
                                                    if($selectedOption2!="0")
                                                    {
                                                        $franchise_select = $selectedOption2;
                                                    }
                                                }
                                           // }
                                             $list = mysql_query("SELECT Franchisecode, Franchisename FROM franchisemaster order by Franchisename asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';
                                                if ($row_list['Franchisecode'] == $franchise_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['Franchisecode']; ?>" <? echo $selected; ?>><? echo trim($row_list['Franchisecode']); ?></option>
                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>   
                            
                                    <?  } ?>
                                    <? if (($authen_row['usertype'])== 'Others') { ?> 
                                         <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Distributor Name</label><? echo $important; ?>
                                    </div>
                                    <div style="width:190px; <? echo $height ; ?>  float:left;  margin-top:5px; margin-left:3px;">
                                        <select <? echo $style_height ; ?> name='franchise[]' id='franchise'  <? echo $onchange;?> >
                                            <option value="0"><? echo $frn_option; ?></option>
                                            <?
                                            $add_qry = '';
                                            if ($region_select) {
                                                $add_qry = " AND Region = '" . $region_select."'";
                                            }
                                            if ($branch_select) {
                                                $add_qry .= " AND Branch = '" . $branch_select."'";
                                            }

                                            $franchise_select = ($_POST['franchise']) ? $_POST['franchise'] : '';
                                            //  if($multiple == ""){
                                               foreach ($_POST['franchise'] as $selectedOption2){
                                                    if($selectedOption2!="0")
                                                    {
                                                        $franchise_select = $selectedOption2;
                                                    }
                                                }
                                            //}
                                            $list = mysql_query("SELECT Franchisecode, Franchisename FROM franchisemaster WHERE Branch IN $authen_branch order by Franchisename asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';
                                                if ($row_list['Franchisecode'] == $franchise_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['Franchisecode']; ?>" <? echo $selected; ?>><? echo trim($row_list['Franchisecode']); ?></option>
                                                <?
                                                if($pagename == 'Purchase Order'){
                                                    $fname = $fname . "r.Franchisecode = '" . $row_list['Franchisecode']."'  OR ". "\n";
                                                }else{
                                                    $fname = $fname . "Franchisecode = '" . $row_list['Franchisecode']."'  OR ". "\n";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
								
                                  <? } ?>
								    <?  if($pagename == "Retailer Category Detailed" || $pagename == "Retailer Category Summary") { ?>
                                        <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Retailer Category</label>
                                    </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='RetailerCategory[]' id = 'RetailerCategory'  onchange="allretailercategory();" >
                                            <option value="0">.All Retailer Category.</option>
                                            <?
                                            $Productgroup_select = ($_POST['RetailerCategory']) ? $_POST['RetailerCategory'] : '';
                                        
                                            
                                           $qry="SELECT distinct (RetailerCategory)as RetailerCategory FROM retailercategory ORDER BY RetailerCategory asc";
                                           
                                           $list1 = mysql_query($qry);
                                            while ($row_list1 = mysql_fetch_assoc($list1)) {
                                                $selected = '';

                                                if ($row_list1['RetailerCategory'] == $Productgroup_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                  <option value="<? echo $row_list1['RetailerCategory']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list1['RetailerCategory']; ?>                                          
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
                                     </div>
                                  <? } ?>
                              
                    <? //if($pagename != "Data Exchange" && $pagename != "GUI Sales Report Based on Products" ) { ?>
                        <!--
                  
                                       <div style="width:145px; height:auto; float:left; margin-top:5px; margin-left:3px;">
                                    <label>Product Code</label>
                                    </div>
                                     <div style="width:185px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                       <select name='productcode[]' id="productcode" multiple="multiple" <? echo $regstyle;?> onChange="allproductcode();">
                                            <option value="0">.All Products.</option>
                                            <?
                                            $product_select = ($_POST['productcode']) ? $_POST['productcode'] : '';

                                            $list = mysql_query("SELECT distinct (ProductCode) FROM productmaster WHERE ProductCode !='' order by ProductCode asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['ProductCode'] == $product_select) {
                                                    $selected = ' selected ';
                                                }
                                                ?>
                                                <option value="<? echo $row_list['ProductCode']; ?>"<? echo $selected; ?>>
                                                    <? echo $row_list['ProductCode']; ?>
                                                </option>

                                                <?
                                            }
                                            ?>
                                        </select>
                                    </div>   -->
                                    <? 
                                     
                                // }?> 
                                    <?  if($pagename == "Sales Report" || $pagename == "Sales Register"){ ?>
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Maximum Limit</label>
                                  </div>
                               <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                      <select name='Limit' id="Limit" style='height:30px' >
                                            <option value="0" <? if($_POST['Limit']== "0"){ echo 'selected';}?> >All</option>
                                            <option value="10" <? if($_POST['Limit']== "10"){ echo 'selected';}?> >10</option>
                                            <option value="50" <? if($_POST['Limit']== "50"){ echo 'selected';}?> >50</option>
                                          
                                        </select>
                                  </div>
                                    <? } ?>

                                