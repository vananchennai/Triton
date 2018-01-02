<? if (($authen_row['usertype'])== 'Corporate') { ?> 
 <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
     <label>Distributor Name</label>
 </div>
   <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
    <select name='franchise[]' id='franchise' multiple="multiple" onChange="allfranchisee();">
        <option value="0">.All Distributor.</option>
        <?
        $add_qry = '';
        $franchise_select = ($_POST['franchise']) ? $_POST['franchise'] : '';
        $list = mysql_query("SELECT Franchisecode, Franchisename FROM franchisemaster order by Franchisename asc");
        while ($row_list = mysql_fetch_assoc($list)) {
            $selected = '';
            if ($row_list['Franchisename'] == $franchise_select) {
                $selected = ' selected ';
            }
            ?>
            <option value="<? echo $row_list['Franchisename']; ?>" <? echo $selected; ?>><? echo trim($row_list['Franchisename']); ?></option>
            <?
        }
        ?>
    </select>
</div>		
<? } ?>
<? if (($authen_row['usertype'])== 'Others') { ?> 
     <div style="width:145px; height:30px; float:left; margin-top:5px; margin-left:45px;">
     <label>Distributor Name</label>
     </div>
     <div style="width:190px; height:auto;  float:left;  margin-top:5px; margin-left:3px;">
        <select name='franchise[]' id='franchise' multiple="multiple" onChange="allfranchisee();">
            <option value="0">.All Distributor.</option>
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
                    if ($row_list['Franchisename'] == $franchise_select) {
                        $selected = ' selected ';
                    }
                    ?>
                    <option value="<? echo $row_list['Franchisename']; ?>" <? echo $selected; ?>><? echo trim($row_list['Franchisename']); ?></option>
                    <?
                    $fname = $fname . "franchisename = '" . $row_list['Franchisename']."'  OR ". "\n";
                }
                ?>
      </select>
</div>  


<? } ?>
<? if($pagename == "Sales Summary Report" || $pagename== "Sub-Product Wise Sales Report" || $pagename=="Division Wise Closing Stock Report" || $pagename=="Consolidated Stock Summary" || $pagename == "Stockist Monthly Comparison Report"){ ?>
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
    </div>
<? } ?>