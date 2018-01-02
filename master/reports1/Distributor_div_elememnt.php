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