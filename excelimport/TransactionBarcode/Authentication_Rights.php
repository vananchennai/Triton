<?
 $validuser = $_SESSION['username'];
  $authen_qry =mysql_query( "select access_right,usertype from reportrights where userid = '$validuser' and r_screen = '$pagename'");
  $authen_row = mysql_fetch_array($authen_qry);
  $auth_check = mysql_num_rows($authen_qry);
  
  // echo "select access_right,usertype from reportrights where userid = '$validuser' and r_screen = '$pagename'";
  if (($authen_row['access_right'])== 'No' || $auth_check == 0)
  {
    header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
  }

   if (($authen_row['usertype'])== 'Others')
  {
     $branch_qry =mysql_query( "select branch from reportrights_sub where userid = '".$_SESSION['username']."'") or mysql_error();
    while ($branch_row = mysql_fetch_array($branch_qry)) {
      $authen_branch = $authen_branch ."'". $branch_row['branch']."', ";
      }
    $authen_branch =  substr($authen_branch, 0, -2);
    $authen_branch = "(".$authen_branch.")";
    
    $general_qry =mysql_query( "SELECT branchname FROM branch WHERE branchcode IN $authen_branch") or mysql_error();
    while ($general_row = mysql_fetch_array($general_qry)) {
      if($pagename == 'BF Wise sales'){
        // if($pagename == 'Branch Wise sales And Franchisee Wise sales'){
        $brn = $brn . "rs.branchname = '" .$general_row['branchname']."'  OR ". "\n";
        $brnwise = $brnwise . "branchname = '" .$general_row['branchname']."'  OR ". "\n";
      }
      //else if($pagename == 'StockBranchFranchisee'){
       // else if($pagename == 'Branch wise stocks And Franchisee wise stocks'){
      else if($pagename == 'Location Wise Stock Summary'){
        $brn = $brn . "vr.branchname = '" .$general_row['branchname']."'  OR ". "\n";
        $brnwise = $brnwise . "branchname = '" .$general_row['branchname']."'  OR ". "\n";
      }else if($pagename == 'Retailer Model Wise Sales' || $pagename == 'Franchisee Month Wise Sales' || $pagename == 'Retailer Target Vs Achievement' ||  $pagename == 'Retailer Detail' || $pagename == "SalesvsServiceCompensation"  || $pagename == "RRR Report" || $pagename == 'TAT' || $pagename=='Branch Or Franchisee Retailer Classification Wise Billing' ||$pagename == 'Branch Month Wise Sales' || $pagename=='Budget VS Actual' || $pagename == 'Retailer Month Wise Sales' || $pagename =='Retailer wise Day Sales' || $pagename =='Product Type Month Wise  Sales'){
        $brn = $brn . "rs.branchname = '" . $general_row['branchname']."'  OR ". "\n";
      }else if($pagename == 'Purchase Order' || $pagename == 'Sub-Product Wise Sales Report'|| $pagename == 'Sales with Sales Return Report' || $pagename =='Purchase with Purchase Return Report'){
        $brn = $brn . "r.branchname = '" . $general_row['branchname']."'  OR ". "\n";
      }else if($pagename == 'Division Wise Closing Stock Report' || $pagename == 'Location Wise Stock Summary' || $pagename == 'Consolidated Stock Summary' || $pagename == ''){
        $brn = $brn . "Branch = '" . $general_row['branchname']."'  OR ". "\n";
      }else{
        $brn = $brn . "branchname = '" . $general_row['branchname']."'  OR ". "\n";
      }

      }
    
  }
?>