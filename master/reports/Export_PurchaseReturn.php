<?php
include '../../functions.php';
sec_session_start();
require_once '../../masterclass.php';
$news = new News();
//include("inc/common_functions.php");


$controls = $_SESSION['form_controls'];
// Database Connection


// Fetch Record from Database

$output = "";
$table = ""; // Enter Your Table Name 
$sql = mysql_query($controls);
if (!$sql) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}
 $columns_total = mysql_num_fields($sql);
/*
// Get The Field Name

for ($i = 0; $i < $columns_total; $i++) {
echo $heading = mysql_field_name($sql, $i);
$output .= '"'.$heading.'",';
} */
$output .= '"Region","Branch","Distributor  Code","Distributor  Name","Purchase Return Number","Purchase Return Date","Product Code","Product Group","Voucher Type","Quantity","Net Amount","Tax Amount","Gross Amount",';
$output .="\n";

// Get Records from the table

while ($row = mysql_fetch_array($sql)) {
for ($i = 0; $i < $columns_total; $i++) {
$output .='"'.$row["$i"].'",';
}
$output .="\n";
}

// Download the file

$filename = "PurchaseReturn-".date('Y-m-d').".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;

?>


		
     </tr>";
		
								if(!empty($_SESSION['form_controls']))
								{
									 $qry_exec = mysql_query($controls);
									 while( $record = mysql_fetch_array($qry_exec))
									 { 
									   $table .=" </tr> <tr style='font-size:10px;white-space:nowrap;'>
                                     <td style='bordercolor;#FF0000; height:30px;'>" .  $record['regionname'] . "
                                     <td style='bordercolor;#FF0000; height:30px;'>" . $record['branchname'] . "
                                     <td style='bordercolor;#FF0000; height:30px;'>" .$record['franchisecode'] . "
                                     <td style='bordercolor;#FF0000; height:30px;'>" .$record['franchisename'] . "
                                    <td style='bordercolor;#FF0000; height:30px;'>" .$record['purchaseRetnumber'] . "
									<td style='bordercolor;#FF0000; height:30px;'>" .$record['purchaseRetdate'] . "
									<td style='bordercolor;#FF0000; height:30px;'>" .$record['pgroupname'] . "
									 <td style='bordercolor;#FF0000; height:30px;'>" . $record['psegmentname'] . "
									 <td style='bordercolor;#FF0000; height:30px;'>" . $record['ptypename'] . " 
                                     <td style='bordercolor;#FF0000; height:30px;'>" . $record['productcode'] . "
									 <td style='bordercolor;#FF0000; height:30px;'>" . $record['vouchertype'] . "
                                     <td style='bordercolor;#FF0000; height:30px;' align='right'>" . $record['quantity'] . "
                                     <td style='bordercolor;#FF0000; height:30px;' align='right'>" . $record['NetAmount'] . " 
                                     <td style='bordercolor;#FF0000; height:30px;' align='right'>" . $record['taxamount'] . "
                                     <td style='bordercolor;#FF0000; height:30px;' align='right'>" . $record['grossamt'] . "</tr>";
									}
									// This while will loop through all of the records as long as there is another record left. 
								}
								else
								{
									 $table .="<tr ><td border='1111' align='center'  style='font-weight:bold; color:#F00;' colspan='16'>No Records Found..!";
								}
                              
	

    if ($type == 'Excel') {
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=PurchaseReturn.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $table;
        exit();
    } else if ($type == 'Document') {
        header('Content-type: application/vnd.ms-doc');
        header("Content-Disposition: attachment; filename=PurchaseReturn.doc");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $table;
        exit();
    }
	// Code is wrong for PDF Export
} else if ($type == 'PDF') {

    //$myquery = mysql_query($query);

    $table = "<table>
	<tr  bgcolor='#E41E1E'>
	<td  size='16px' border='1111'  color='#ffffff' colspan='11' align='center'><b>Amara Raja Batteries</b></tr>
	<tr   bgcolor='#E41E1E' >
	<td size='14px' border='1111'  color='#ffffff' colspan='11' align='center' ><b>Purchase Returns Report </b>
	</tr>
	       
<tr bgcolor='#E41E1E' >
<td border='1111' color='#ffffff' >Label
        <td  size='12px' border='1111' color='#ffffff'><b>PurchaseRetNo</b>
        <td  size='12px' border='1111' color='#ffffff'><b>Franchisee Code</b>
        <td  size='12px' border='1111' color='#ffffff'><b>Franchisee Name</b>
        <td  size='12px' border='1111' color='#ffffff'><b>Product Code</b>
        <td  size='12px' border='1111' color='#ffffff'><b>Region</b>
        <td  size='12px' border='1111' color='#ffffff'><b>Branch</b>
        <td  size='12px' border='1111' color='#ffffff'><b>Quantity</b>        
        <td  size='12px' border='1111' color='#ffffff'><b>Tax Amount</b>
        <td  size='12px' border='1111' color='#ffffff'><b>Gross Amount</b>
        <td  size='12px' border='1111' color='#ffffff'><b>Net Amount</b></tr>";


    $region_select = ($session_region) ? $session_region : '';
    if ($region_select) {
        $region_add_qry = " AND RegionCode = " . $region_select;
    }
    $region_qry = mysql_query("SELECT RegionCode, RegionName FROM region WHERE 1 $region_add_qry ");
    while ($region_row = mysql_fetch_array($region_qry)) {
        $region_total_qty = $region_total_tax = $region_total_amount = $region_total_netamount = '';
        // $table .="<tr><td>Region<td colspan='11' >" . $region_row['regionname'] . "";
        $b_region_add_qry = " AND region = " . $region_row['RegionCode'];

        $branch_select = ($session_branch) ? $session_branch : '';
        if ($branch_select) {
            $branch_add_qry = " AND branchcode = " . $branch_select;
        }
        $branch_qry = mysql_query("SELECT branchcode, branchname FROM branch WHERE 1 $branch_add_qry $b_region_add_qry ");
        while ($branch_row = mysql_fetch_array($branch_qry)) {
            $branch_total_qty = $branch_total_tax = $branch_total_amount = $branch_total_netamount = '';
            //   $table .="</tr><tr><td>Branch<td colspan='11'>" . $branch_row['branchname'] . "";
            $f_region_add_qry = " AND Region = " . $region_row['RegionCode'];
            $f_branch_add_qry = " AND Branch = " . $branch_row['branchcode'];
            $franchise_select = ($session_franchisecode) ? $session_franchisecode : '';
            if ($franchise_select) {
                $franchise_add_qry = " AND Franchisecode = " . $franchise_select;
            }
            $franchise_qry = mysql_query("SELECT Franchisecode, Franchisename FROM franchisemaster WHERE 1 $franchise_add_qry $f_branch_add_qry $f_region_add_qry ");
            while ($franchise_row = mysql_fetch_array($franchise_qry)) {
                $franchise_add_qry = '';
                //  $table .="</tr><tr ><td><b>Franchise</b><td colspan='11'><b>" . $franchise_row['Franchisename'] . "</b>";

                $final_total_qty = $final_total_tax = $final_total_amount = $final_total_netamount = '';



                if ($session_PS_FromDate && $session_PS_Todate) {
                    if ($session_productcode) {
                        $product_qry = " AND productcode = '" . $session_productcode . "'";
                    }
                    $dates_array = dateRange($session_PS_FromDate, $session_PS_Todate);
                    if (count($dates_array) > 0) {
                        foreach ($dates_array as $date) {

                            $date_final_total_qty = $date_final_total_tax = $date_final_total_amount = $date_final_total_netamount = '';
                            //echo $date;
                            $qry = "SELECT * FROM view_purchasesummary WHERE 1 AND regioncode = '" . $region_row['RegionCode'] . "' AND branchcode = '" . $branch_row['branchcode'] . "' AND franchisecode = '" . $franchise_row['Franchisecode'] . "' $product_qry AND PurchaseDate = '" . $date . "'";
                            $qry_exec = mysql_query($qry);
                            if (mysql_num_rows($qry_exec)) {

                                $table .="<tr ><td border='1111' bgcolor='#FCD1E5'>Region<td border='1111' bgcolor='#FCD1E5' colspan='10'>" . $region_row['RegionName'] . "</tr>
<tr><td border='1111' bgcolor='#FBDAC0' >Branch<td border='1111' bgcolor='#FBDAC0' colspan='10'>" . $branch_row['branchname'] . "</tr>
<tr><td border='1111' bgcolor='#EAF2C8'><b>Franchise</b><td border='1111' bgcolor='#EAF2C8' colspan='10'><b>" . $franchise_row['Franchisename'] . "</b></tr>";

                                $temp_date = '';
                                while ($qry_obj = mysql_fetch_array($qry_exec)) {
                                    $total_record++;
                                    $ddate = '';
                                    $db_date = date('d/m/Y', strtotime($qry_obj['PurchaseDate']));
                                    if ($temp_date == $db_date) {
                                        $ddate = '';
                                    } else {
                                        $ddate = $db_date;
                                        $temp_date = $db_date;
                                    }

                                    $table .=" </tr> <tr> 
                                    <td border='1111'>" . $qry_obj['purchasedate'] . "
                                   
                                     <td border='1111' >" . $qry_obj['purchasenumber'] . " 
                                     <td border='1111'>" . $qry_obj['franchisecode'] . "
                                     <td border='1111'>" . $qry_obj['franchisename'] . "
                                     <td border='1111'>" . $qry_obj['productcode'] . "
                                     <td border='1111'>" . $qry_obj['regionname'] . "
                                     <td border='1111'>" . $qry_obj['branchname'] . "
                                     <td align='right' border='1111'>" . $qry_obj['quantity'] . "
                                    
                                     <td align='right' border='1111'  >" . number_format($qry_obj['taxamount'], 2) . "
                                     <td align='right' border='1111' >" . number_format($qry_obj['TotalAmount'], 2) . " 
                                     <td align='right' border='1111' >" . number_format($qry_obj['NetAmount'], 2) . " </tr>";

                                    $date_final_total_qty += $qry_obj['quantity'];
                                    $date_final_total_tax += $qry_obj['taxamount'];
                                    $date_final_total_amount += $qry_obj['TotalAmount'];
                                    $date_final_total_netamount += $qry_obj['NetAmount'];
                                    $final_total_qty += $qry_obj['quantity'];
                                    $final_total_tax += $qry_obj['taxamount'];
                                    $final_total_amount += $qry_obj['TotalAmount'];
                                    $final_total_netamount += $qry_obj['NetAmount'];
                                }
                            }
                            if ($date_final_total_qty) {
                                $table .=" <tr><td border='1111' colspan='7' bgcolor='#8E8D8D' align='right' style='bold'  color='#0092CA'>" . $temp_date . "<td align='right' border='1111' bgcolor='#8E8D8D' style='bold' color='#0092CA'>" . $date_final_total_qty . "<td bgcolor='#8E8D8D' style='bold' color='#0092CA' border='1111' align='right'>" . number_format($date_final_total_tax, 2) . "<td border='1111' bgcolor='#8E8D8D' style='bold' color='#0092CA'  align='right'>" . number_format($date_final_total_amount, 2) . "<td border='1111' bgcolor='#8E8D8D' style='bold' color='#0092CA' align='right'>" . number_format($date_final_total_netamount, 2) . "</tr>";
                            }
                        }
                        if ($final_total_qty) {
                            $table .="<tr><td border='1111' colspan='7' bgcolor='#8E8D8D' align='right' style='bold'  color='#B3D32D'>" . $franchise_row['Franchisename'] . "</b><td border='1111' bgcolor='#8E8D8D' style='bold' color='#B3D32D' align='right'>" . $final_total_qty . "<td border='1111' bgcolor='#8E8D8D' style='bold' color='#B3D32D'  align='right'>" . number_format($final_total_tax, 2) . "<td border='1111' bgcolor='#8E8D8D' style='bold' color='#B3D32D' align='right'>" . number_format($final_total_amount, 2) . "<td border='1111' bgcolor='#8E8D8D' style='bold' color='#B3D32D' align='right'>" . number_format($final_total_netamount, 2) . "</tr>";
                        }
                    }
                } else {
                    if ($session_productcode) {
                        $product_qry = " AND productcode = '" . $session_productcode . "'";
                    }
                    $qry = "SELECT * FROM view_purchasesummary WHERE 1 AND regioncode = '" . $region_row['RegionCode'] . "' AND branchcode = '" . $branch_row['branchcode'] . "' AND franchisecode = '" . $franchise_row['Franchisecode'] . "' $product_qry ";
                    $qry_exec = mysql_query($qry);
                    if (mysql_num_rows($qry_exec)) {
                        $table .="<tr ><td border='1111' bgcolor='#FCD1E5'>Region<td border='1111' bgcolor='#FCD1E5' colspan='10'>" . $region_row['RegionName'] . "</tr>
<tr><td border='1111' bgcolor='#FBDAC0' >Branch<td border='1111' bgcolor='#FBDAC0' colspan='10'>" . $branch_row['branchname'] . "</tr>
<tr><td border='1111' bgcolor='#EAF2C8'><b>Franchise</b><td border='1111' bgcolor='#EAF2C8' colspan='10'><b>" . $franchise_row['Franchisename'] . "</b></tr>";
                        while ($qry_obj = mysql_fetch_array($qry_exec)) {
                            $total_record++;
                            $date = ($total_record == 1) ? $qry_obj['purchasedate'] : '';

                            $table .="<tr >
                                    <td border='1111' >" . $qry_obj['purchasedate'] . "
                                    <td border='1111' >" . $qry_obj['purchasenumber'] . " 
                                    <td border='1111'>" . $qry_obj['franchisecode'] . "
                                    <td border='1111'>" . $qry_obj['franchisename'] . "
                                    <td border='1111'>" . $qry_obj['productcode'] . "
                                    <td border='1111'>" . $qry_obj['regionname'] . "
                                    <td border='1111'>" . $qry_obj['branchname'] . "
                                    <td align='right' border='1111'>" . $qry_obj['quantity'] . "
                                    <td align='right' border='1111'>" . number_format($qry_obj['taxamount'], 2) . "
                                    <td align='right' border='1111' >" . number_format($qry_obj['TotalAmount'], 2) . " 
                                    <td align='right' border='1111'>" . number_format($qry_obj['NetAmount'], 2) . " </tr>";


                            $final_total_qty += $qry_obj['quantity'];
                            $final_total_tax += $qry_obj['taxamount'];
                            $final_total_amount += $qry_obj['TotalAmount'];
                            $final_total_netamount += $qry_obj['NetAmount'];
                        }
                    }

                    if ($final_total_qty) {
                        $table .="<tr><td border='1111' colspan='7' bgcolor='#8E8D8D' align='right' style='bold'  color='#EAF2C8'><b>" . $franchise_row['Franchisename'] . "</b><td bgcolor='#8E8D8D' border='1111' align='right' style='bold' color='#EAF2C8'>" . $final_total_qty . "<td border='1111' bgcolor='#8E8D8D' align='right' style='bold' color='#EAF2C8'>" . number_format($final_total_tax, 2) . "<td border='1111' bgcolor='#8E8D8D' align='right' style='bold' color='#EAF2C8'>" . number_format($final_total_amount, 2) . "<td border='1111' bgcolor='#8E8D8D' align='right' style='bold' color='#EAF2C8'>" . number_format($final_total_netamount, 2) . "</tr>";
                    }
                }

                $branch_total_qty += $final_total_qty;
                $branch_total_tax += $final_total_tax;
                $branch_total_amount += $final_total_amount;
                $branch_total_netamount += $final_total_netamount;
            }
            if ($branch_total_qty) {
                $table .="<tr ><td border='1111' colspan='7' bgcolor='#8E8D8D' align='right' style='bold' color='#FBDAC0'>" . $branch_row['branchname'] . "<td border='1111' bgcolor='#8E8D8D' align='right' style='bold' color='#FBDAC0'>" . $branch_total_qty . "<td border='1111' bgcolor='#8E8D8D' align='right' style='bold' color='#FBDAC0'>" . number_format($branch_total_tax, 2) . "<td border='1111' bgcolor='#8E8D8D' align='right' style='bold' color='#FBDAC0'>" . number_format($branch_total_amount, 2) . "<td border='1111' bgcolor='#8E8D8D' align='right' style='bold' color='#FBDAC0'>" . number_format($branch_total_netamount, 2) . " </tr>";
            }
            $region_total_qty += $branch_total_qty;
            $region_total_tax += $branch_total_tax;
            $region_total_amount += $branch_total_amount;
            $region_total_netamount += $branch_total_netamount;
        }
        if ($region_total_qty) {
            $table .="<tr ><td border='1111' colspan='7' bgcolor='#8E8D8D' align='right' style='bold' color='#FCD1E5'>" . $region_row['RegionName'] . "<td align='right' bgcolor='#8E8D8D' border='1111' style='bold' color='#FCD1E5' >" . $region_total_qty . "<td align='right' bgcolor='#8E8D8D' border='1111' style='bold' color='#FCD1E5' >" . number_format($region_total_tax, 2) . "<td align='right' bgcolor='#8E8D8D' border='1111' style='bold' color='#FCD1E5' >" . number_format($region_total_amount, 2) . "<td align='right' bgcolor='#8E8D8D' border='1111' style='bold' color='#FCD1E5'>" . number_format($region_total_netamount, 2) . "</tr>";
        }
    }
    if (!$total_record) {
        $table .="<tr ><td border='1111' bgcolor='#8E8D8D' ><td border='1111' bgcolor='#8E8D8D' align='center' style='bold' colspan='11'>No Records Found..!";
    }
    $table .=" </table>";

/// Table format Refered by this site: http://www.vanxuan.net/tool/pdftable/ 
    define('FPDF_FONTPATH', 'font/');
    require("inc/pdftable.inc.php");
    $p = new PDFTable();
    //$p->AddPage(L);
    $p->setfont('times', '', 10);
    $p->htmltable($table);
    $p->output('PurchaseReturn.pdf', 'D');
}
?>
