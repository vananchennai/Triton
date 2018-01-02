<? if($pagename == "Sales Summary Report" || $pagename == "Sub-Product Wise Sales Report"|| $pagename=="Division Wise Closing Stock Report" || $pagename=="Consolidated Stock Summary" || $pagename == "Stockist Monthly Comparison Report" ){ ?>
     <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
            <label>Productgroup</label>
        </div>
        <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
             <select name='Productgroup[]' id = 'Productgroup' multiple="multiple" onChange="drpfunc2();">
                <option value="0">.All Productgroup.</option>
                <?
                $Productgroup_select = ($_POST['Productgroup']) ? $_POST['Productgroup'] : '';
            
                
               $qry="SELECT distinct (ProductGroup)as ProductGroup FROM productgroupmaster ORDER BY ProductGroup asc";
               
               $list1 = mysql_query($qry);
                while ($row_list1 = mysql_fetch_assoc($list1)) {
                    $selected = '';

                    if ($row_list1['ProductGroup'] == $Productgroup_select) {
                        $selected = ' selected ';
                    }
                    ?>
                      <option value="<? echo $row_list1['ProductGroup']; ?>"<? echo $selected; ?>>
                        <? echo $row_list1['ProductGroup']; ?>                                          
                    </option>

                    <?
                }
                ?>
            </select>


        </div>
                                  
<? } else{ ?>
<div style="width:145px; height:auto; float:left; margin-top:5px; margin-left:45px;">
    <label>Product Code</label>
    </div>
     <div style="width:185px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
       <select name='productcode[]' id="productcode" multiple="multiple" onChange="allproductcode();">
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
    </div   

<? } ?>

