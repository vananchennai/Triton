 <?  //if($pagename == "GUI Sales Report Based on Distributor Name" || $pagename == "GUI Purchase Report Based on Distributor Name" || $pagename == "GUI Stock Opening and Closing Report"){
//         $brnstyle = 'style="height:inherit"';
//         $multiple = 'multiple="multiple"';
//         $important = "";
//         $regstyle = "";
//         $pg_option ='.All Productgroup.';
//     }else if($pagename == "GUI Sales Report Based on Products" || $pagename == "GUI Purchase Report Based on Products"){
//          $multiple = "";
//         $regstyle = 'style="height:30px"';
//         $brnstyle = 'style="height:inherit"';
//         $important = '<label style="color:#F00;">*</label>';
//         $pg_option ='.Select.';
//     }
//     else{
//         $brnstyle = "";
//         $multiple = 'multiple="multiple"';
//         $important = "";
//         $regstyle = "";
//          $pg_option ='.All Productgroup.';
//     }
    if($pagename== "GUI Sales Report Based on Distributor Name" || $pagename == "GUI Purchase Report Based on Distributor Name" || $pagename == "GUI Stock Opening and Closing Report"){
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
            $pgstyle = "";
            $div_style = "";
        }else if($pagename== "GUI Sales Report Based on Products" || $pagename == "GUI Purchase Report Based on Products"){
            $multiple = 'multiple="multiple"';
            $height = 'height:auto;';
            $style_height = 'style="height:inherit"';
            $important = '';
            $onchange= 'onChange="allfranchisee();"';
            $frn_option ='.All Distributor.';
            $regstyle = 'style="height:inherit"';
            $pgmultiple = "";
            $pgstyle = 'style="height:30px"';
            $pgimportant = '<label style="color:#F00;">*</label>';
            $pg_option ='.Select.';
            $div_style = "";
        }
        else{
            $multiple = 'multiple="multiple"';
            $height = 'height:auto;';
            $style_height = '';
            $important = '';
            $onchange= 'onChange="allfranchisee();"';
            $frn_option ='.All Distributor.';
            $regstyle = "";
            $pgmultiple = 'multiple="multiple"';
            $pg_option ='.All Productgroup.';
            $pgimportant = "";
            $pgstyle = "";
            $div_style = "";
              if($pagename == "Data Exchange"){
                $div_style = 'style="width:inherit"';
            }
        } 
 if (($authen_row['usertype'])== 'Corporate') { ?> 
                                 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Branch</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='branch[]' id='branch' <? echo $regstyle; ?> <? echo $div_style; ?> multiple="multiple" onChange="drpfunc1();drpprimary();">
                                            <option value="0">.All Branches.</option>
                                            <?php
                                            $add_qry = '';
                                            if ($region_select) {
                                                $add_qry = " AND region = '" . $region_select."'";
                                            }
                                            $branch_select = ($_POST['branch']) ? $_POST['branch'] : '';

                                            $list = mysql_query("SELECT branchcode, branchname FROM branch order by branchname asc");
                                            while ($row_list = mysql_fetch_assoc($list)) {
                                                $selected = '';

                                                if ($row_list['branchname'] == $branch_select) {
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
                                    <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Distributor Name</label><? echo $important; ?>
                                    </div>
                                    <div style="width:190px; <? echo $height; ?>  float:left;  margin-top:5px; margin-left:3px;">

                                        <select <? echo $style_height ; ?> <? echo $div_style; ?>name='franchise[]' id='franchise' <? echo $multiple; ?> <? echo $onchange;?> >
                                            <option value="0"><? echo $frn_option; ?></option>
                                            <?
                                            $add_qry = '';
                                            $franchise_select = ($_POST['franchise']) ? $_POST['franchise'] : '';
                                               if($multiple == ""){
                                                    foreach ($_POST['franchise'] as $selectedOption2){
                                                        if($selectedOption2!="0")
                                                        {
                                                            $franchise_select = $selectedOption2;
                                                        }
                                                    }
                                                }
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
                                    <? } ?>
                                    <? if (($authen_row['usertype'])== 'Others') { ?> 
                                       <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Branch</label>
                                  </div>
                                    <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                        <select name='branch[]' id='branch' <? echo $regstyle; ?> <? echo $div_style; ?> multiple="multiple" onChange="drpfunc1();drpprimary();">
                                            <option value="0">.All Branches.</option>
                                            <?php
                                            $add_qry = '';
                                            if ($region_select) {
                                                $add_qry = " AND region = '" . $region_select."'";
                                            }
                                            $branch_select = ($_POST['branch']) ? $_POST['branch'] : '';

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
                                     <? if (($authen_row['usertype'])== 'Others') { ?> 
                                         <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                        <label>Distributor Name</label><? echo $important; ?>
                                    </div>
                                    <div style="width:190px; <? echo $height ; ?>  float:left;  margin-top:5px; margin-left:3px;">
                                        <select <? echo $style_height ; ?> <? echo $div_style; ?> name='franchise[]' id='franchise' <? echo $multiple; ?> <? echo $onchange;?> >
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
                                                    $fname = $fname . "r.franchisename = '" . $row_list['Franchisecode']."'  OR ". "\n";
                                                }else{
                                                    $fname = $fname . "franchisename = '" . $row_list['Franchisecode']."'  OR ". "\n";
                                                }
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
                                       <select name='RetailerName[]' id = 'RetailerName' multiple="multiple" onchange="allretailername();">
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
                                    <? if($pagename != "Data Exchange") { ?>
                        
                  
                                       <div style="width:145px; height:auto; float:left; margin-top:5px; margin-left:3px;">
                                    <label>Product Code</label>
                                    </div>
                                     <div style="width:185px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
                                       <select name='productcode[]' id="productcode" multiple="multiple" <? echo $regstyle; ?> onChange="allproductcode();">
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
                                    </div>
                                    <? 
                                     
                                 }?> 

         
