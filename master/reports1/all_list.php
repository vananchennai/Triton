<table id="default" style=" height:10px; display:none;" >
            <tr>
                <td>
                                    <select  name="emplist" id="emplist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT Distinct(RegionCode),branchcode,branchname FROM `view_rptfrnfin`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
                          
                                      echo "<option value=\"".$record['RegionCode']."~".$record['branchcode']."~".$record['branchname']."\">".$record['RegionCode']."~".$record['branchcode']."~".$record['branchname']."\n "; 
                    }
                                   
                                    ?>
                                          </select>
                                      </td>
                                        
                                        <td>
                                    <select  name="forlist" id="forlist">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT branchcode,Franchisecode FROM `view_rptfrnfin`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
                          
                                      echo "<option value=\"".$record['branchcode']."~".$record['Franchisecode']."\">".$record['branchcode']."~".$record['Franchisecode']."\n "; 
                    }
                                   
                                    ?>
                                          </select>
                                            <select  name="psdiscode" id="psdiscode">
                                     <?
                                                                                
                                        $que = mysql_query("SELECT branchcode,Franchisecode,PrimaryFranchise FROM `view_rptfrnfin`");
                                       
                                     while( $record = mysql_fetch_array($que))
                                     {
                          
                                      echo "<option value=\"".$record['branchcode']."~".$record['PrimaryFranchise']."~".$record['Franchisecode']."\">".$record['branchcode']."~".$record['PrimaryFranchise']."~".$record['Franchisecode']."\n "; 
                    }
                                   
                                    ?>
                                          </select>
                                      </td>
                                      <td>
                                        <select  name="productlist" id="productlist">
                                            <?
                                            $que = mysql_query("SELECT pcode AS ProductCode,pgroupcode AS ProductGroup FROM view_productdtetails");
                                            while( $record = mysql_fetch_array($que))
                                            {   
                                              echo "<option value=\"".$record['ProductGroup']."~".$record['ProductCode']."\">".$record['ProductGroup']."~".$record['ProductCode']."\n "; 
                                            }
                                            ?>
                                        </select>
                                      </td>
                                      
                                     
                                   
                                    </tr>

                                     
                                      
            </table>