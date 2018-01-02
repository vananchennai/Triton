<?php 
    include '../../functions.php';
    sec_session_start();
    require_once '../../masterclass.php';
    include("../../header.php");
    require_once '../../paginationfunction.php';
    $scode = 'userid';
    $sname = '';
    $tname = 'usercreation';
    $ttname = "userrights";
    require_once '../../searchfun.php';
    $news = new News(); // Create a new News Object
    $newsRecordSet = $news->getNews($ttname);
    $pagename = "User rights Master";
    $validuser = $_SESSION['username'];
    $selectvar =mysql_query( "select * from userrights where userid = '$validuser' and screen = '$pagename'");
    $row = mysql_fetch_array($selectvar);
    
    if (($row['viewrights'])== 'No')
    {
        header("location:/".$_SESSION['mainfolder']."/home/home/master.php");
    }
    if(isset($_POST['permiss'])) // If the submit button was clicked
    {
        ?>
            <script type="text/javascript">
            alert("you are not allowed to do this action..!!",'userrights.php');//document.location='userrights.php';   
            </script>
         <?
        
    }
    if(isset($_GET['permiss']))
    {
    ?>
            <script type="text/javascript">
            alert("you are not allowed to do this action..!!",'userrights.php');    
            </script>
         <?
        
    }
    
    if(isset($_POST['Save']))
    {   
        $post['userid'] = isset($_POST['userid']) ? $_POST['userid'] : NULL;
        // echo $_POST['userid'];
        // exit;
        if(!empty($_POST['userid']))
        {
            $delete_user_rights = mysql_query("DELETE FROM userrights WHERE userid ='".$_POST['userid']."'");
            // echo "DELETE FROM userrights WHERE userid ='".$_POST['userid']."'";
            $master = mysql_query("select pagename from pagename where pagetype='Master' order by sorting");
            while($master_result = mysql_fetch_array($master)){  
                $screename = trim(str_replace(" ","",$master_result['pagename']));
                $add = trim($screename.'add');
                $edit = trim($screename.'edit');
                $view = trim($screename.'view');
                $delete = trim($screename.'delete');
                // $select = trim($screename.'select')

                $check_add = isset($_POST[$add]) ? 'Yes': 'No';
                $check_edit  = isset($_POST[$edit]) ? 'Yes': 'No';  
                $check_view  = isset($_POST[$view]) ? 'Yes': 'No';    
                $check_delete  = isset($_POST[$delete]) ? 'Yes': 'No';

                $insert_query = "insert into userrights(userid, screen, viewrights, addrights, editrights, deleterights) VALUES('".$_POST['userid']."','".$master_result['pagename']."','".$check_view."','".$check_add."','".$check_edit."','".$check_delete."')";
                $master_insert = mysql_query($insert_query);
				
                // echo '<br>'.$insert_query;
            }
            // exit;
            echo " <script type='text/javascript'>alert('Updated Sucessfully..!!','userrights.php');</script>";
                                            
        }else{
            echo " <script type='text/javascript'>alert('Please Enter the user Id..!!','userrights.php');</script>";
        }
    }    
    if(isset($_POST['Cancel']))
    {   
    $_SESSION['codesval']=NULL;
    $_SESSION['namesval']=NULL;
    header('Location:userrights.php');
    }

    if(!empty($_GET['edi']))
    {
        $prmaster =$_GET['edi'];
        $userid=$prmaster;
        $post['userid'] = $prmaster;
    }

    ?>
    <script type="text/javascript">
    function all_edit(){
        if($('#editselect').is(':checked')){
            check('edit');
            check('view');
        }else{
            uncheck('edit');
        }
        see_checked('addselect','editselect','viewselect','deleteselect','allselect1');
        if($('#allselect1').is(':checked')){
            $('#allselect1').attr('checked', true);
            all_checked();
        }
    }
    function all_Add(){
        if($('#addselect').is(':checked')){
            check('add');
            check('view');
        }else{
            uncheck('add');
        }
        see_checked('addselect','editselect','viewselect','deleteselect','allselect1');
        if($('#allselect1').is(':checked')){
            $('#allselect1').attr('checked', true);
            all_checked();
        }
        // all_checked();
    }
    function all_view(){
        var  vflag = 0;
        if($('#viewselect').is(':checked')){
            check('view');

        }else{
            if(!$('#addselect').is(':checked') && !$('#deleteselect').is(':checked') && !$('#editselect').is(':checked')){
                uncheck('view');
            }
        }
        see_checked('addselect','editselect','viewselect','deleteselect','allselect1');
        if($('#allselect1').is(':checked')){
            $('#allselect1').attr('checked', true);
            all_checked();
        }
        // all_checked();
    }
    function all_del(){
        if($('#deleteselect').is(':checked')){
            check('delete');
            check('view');
        }else{
            uncheck('delete');
        }
        see_checked('addselect','editselect','viewselect','deleteselect','allselect1');
        if($('#allselect1').is(':checked')){
            $('#allselect1').attr('checked', true);
            all_checked();
        }
        // all_checked();

    }
    function all_checked(){

        if($('#allselect1').is(':checked')){
            check('delete');
            check('view');
            check('add');
            check('edit');
            check('select');
            $('#addselect').attr('checked', true);
            $('#viewselect').attr('checked', true);
            $('#deleteselect').attr('checked', true);
            $('#editselect').attr('checked', true);

        }else{
            uncheck('delete');
            uncheck('view');
            uncheck('add');
            uncheck('edit');
            uncheck('select');
            $('#addselect').attr('checked', false);
            $('#viewselect').attr('checked', false);
            $('#deleteselect').attr('checked', false);
            $('#editselect').attr('checked', false);
        }
        // see_checked('addselect','editselect','viewselect','deleteselect','allselect1');
    }
    function check(opsel){
        $("#pagelist option").each(function()
        {
    // Add $(this).val() to your list
            var ele = $(this).val();
            ele = '#'+ele+opsel;
            $(ele).attr('checked', true);
        });
        // see_checked('addselect','editselect','viewselect','deleteselect','allselect1');
    }
    function uncheck(opsel){
            $("#pagelist option").each(function()
        {
    // Add $(this).val() to your list
            var ele = $(this).val();
            opele = '#'+ele+opsel;
            selele = '#'+ele+'select';
            $(opele).attr('checked', false);
            $(selele).attr('checked', false);
        });
        $('#allselect1').attr('checked', false);
        // see_checked('addselect','editselect','viewselect','deleteselect','allselect1');
    }
        function checkboxcheck()
            {
                var addflag = 0;
                var delflag = 0;
                var editflag = 0;
                var viewflag = 0;
                var selflag = 0;
                $("#pagelist option").each(function()
                {
                    var ele = $(this).val();
                    addele = '#'+ele+'add';
                    editele = '#'+ele+'edit';
                    viewele = '#'+ele+'view';
                    delele = '#'+ele+'delete';
                    selectele = '#'+ele+'select';
                    if(!$(addele).is(':checked')){
                        addflag++;
                    }if(!$(editele).is(':checked')){
                        editflag++;
                    }if(!$(viewele).is(':checked')){
                        viewflag++;
                    }if(!$(delele).is(':checked')){
                        delflag++;
                    }if(!$(selectele).is(':checked')){
                        selflag++;
                    }
                });
                if(addflag == 0 && delflag == 0 && viewflag == 0 && editflag == 0){
                    $('input[name=allselect1]').attr('checked', true);
                    all_checked();
                }
                if(addflag == 0){
                    $('input[id=addselect]').attr('checked', true);
                }else{
                    $('input[id=addselect]').attr('checked', false);
                }
                if(delflag == 0){
                    $('input[id=deleteselect]').attr('checked', true);
                }else{
                    $('input[id=deleteselect]').attr('checked', false);
                }
                if(editflag == 0){
                    $('input[id=editselect]').attr('checked', true);
                }else{
                    $('input[id=editselect]').attr('checked', false);
                }
                if(viewflag == 0){
                    $('input[id=viewselect]').attr('checked', true);
                }else{
                    $('input[id=viewselect]').attr('checked', false);
                }
                if(selflag == 0){
                    $('input[name=allselect1]').attr('checked', true);
                    all_checked();
                }else{
                    $('input[name=allselect1]').attr('checked', false);
                }
                
}


    function see_checked(a,b,c,d,e)
    {
        if($('input[name='+a+']').is(':checked')||$('input[name='+b+']').is(':checked')||$('input[name='+d+']').is(':checked') || ('input[name='+c+']').is(':checked') )
        {
            $('input[name='+c+']').attr('checked', true);
        }
        else if($('input[name='+c+']').is(':checked'))
        {
            $('input[name='+c+']').attr('checked', false);  
        }

        if($('input[name='+a+']').is(':checked')&&$('input[name='+b+']').is(':checked')&&$('input[name='+c+']').is(':checked')&&$('input[name='+d+']').is(':checked'))
        {
            if(a=='addselect'){
                $('#allselect1').attr('checked', true);
            }
            $('input[id='+e+']').attr('checked', true);
        }
        else
        {
            $('input[id='+e+']').attr('checked', false);
            $('input[id=allselect1]').attr('checked', false);
            // add ed vi del 
            if(!$('input[name='+a+']').is(':checked')){
                $('#addselect').attr('checked', false);
            }
            if(!$('input[name='+b+']').is(':checked')){
                $('#editselect').attr('checked', false);
            }
            if(!$('input[name='+c+']').is(':checked')){
                $('#viewselect').attr('checked', false);
            }
            if(!$('input[name='+d+']').is(':checked')){
                $('#deleteselect').attr('checked', false);
            }
        // $('#viewselect').attr('checked', false);
        // $('#deleteselect').attr('checked', false);
        // $('#editselect').attr('checked', false);
        }
        checkboxcheck();
    }
    function set_checked(a,b,c,d){
    
    
    if($('input[name='+a+']').is(':checked')&&$('input[name='+b+']').is(':checked')&&$('input[name='+c+']').is(':checked')&&$('input[name='+d+']').is(':checked'))
    {
        
    $('input[name='+a+']').attr('checked', false);
    $('input[name='+b+']').attr('checked', false);
    $('input[name='+c+']').attr('checked', false);
    $('input[name='+d+']').attr('checked', false);
    $('input[id=allselect1]').attr('checked', false);
    $('#addselect').attr('checked', false);
    $('#viewselect').attr('checked', false);
    $('#deleteselect').attr('checked', false);
    $('#editselect').attr('checked', false);
    }
    else
    {
    $('input[name='+a+']').attr('checked', true);
    $('input[name='+b+']').attr('checked', true);
    $('input[name='+c+']').attr('checked', true);
    $('input[name='+d+']').attr('checked', true);
    // if(a== 'addselect'){

    // }
    
    }
    checkboxcheck();
}
    </script>
    <title>SSV || User Rights Master</title>
</head>
 <?php
  
 if(empty($_SESSION['codesval']) && empty($_SESSION['namesval']))
{ ?>
<body class="default" > <?
}
 else
 { ?>
 <body class="default" onLoad="document.form1.codes.focus()">
 <? }
 ?><center>


<?php include("../../menu.php") ?>


<div style="width:100%; height:auto; float:none;">
     <div style="width:980px; height:auto; float:none; margin-top:8px;" class="container">
                  
          <div style="width:950px; height:auto; float:left; margin-bottom:10px; margin-left:15px;" id="center">  
            <!-- form id start-->
             <form method="POST" name="form1" action="<?php $_PHP_SELF ?>">
                <table style='display:none'>
                    <tr>
                        <td>
                            <select  name="pagelist" id="pagelist">
                                     <?
                                                                                
                                        $master = mysql_query("select pagename from pagename where pagetype='Master' order by sorting");
                                       
                                     while( $record = mysql_fetch_array($master))
                                     {
                                        $screename = trim(str_replace(" ","",$record['pagename']));
                          
                                      echo "<option value=\"".$screename."\">".$screename."\n "; 
                    }
                                   
                                    ?>
                            </select>   
                        </td>
                    </tr>
                </table>
            <div style="width:930px; height:auto;   min-height: 250px; padding-bottom:8px; margin-top:8px; float:left; margin-left:10px;">
                    
                        <div style="width:930px; height:25px; float:left;  margin-left:0px;" class="head">
                        <p>User Rights Master</p>
                        </div>
              <!-- main row 1 start-->     
                    <div style="width:925px; height:auto; float:left;  margin-top:8px; margin-left:5px;">
                     <!-- col1 -->   
                           <div style="width:300px; height:auto; padding-bottom:5px; float:left; " class="cont">
                             <!--Row1 -->  
                               <div style="width:55px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                                  <label>User ID :</label>
                               </div>
                              <div style="width:145px; height:30px;  float:left;  margin-top:5px; margin-left:3px;">
                                   <input type="text" name="userid" style="border-style:hidden; background:#f5f3f1;" readonly="readonly" value="<?php echo $userid;?>"/>
                                     
                               </div>
                                <div style="width:70px; height:30px; float:left; margin-top:5px; margin-left:3px;">
                            
                               </div>
                            <!--Row1 end--> 
                            
                            <!--Row2 -->  
                              
                             <!--Row6 end-->                        
                                               
                           </div>                             
                     <!-- col1 end --> 
                     <div style="width:600px; height:60px;"></div>
                     <!-- col2 -->   
                           <div style="width:300px; height:auto; float:left; padding-bottom:5px; margin-left:2px;" class="cont">
                                 <table  width="750" style=" float:left; margin-left:80px; border-color:#058AFF; border-bottom-color:#058AFF; border-left-color:#058AFF; border-right-color:#058AFF; border-top-color:#058AFF;" border="2" >
                                   <?
                                     $master_query1 = mysql_query("select pagename,displayname from pagename where pagetype='Master' order by sorting");
                                        $user_query1 = "select p.pagename as screen,u.viewrights,u.addrights,u.deleterights,u.editrights from userrights u left join pagename p on p.pagename = u.screen where p.pagetype='Master' and u.userid='".$userid."' order by p.sorting"; 
                                        // echo $user_query;
                                        // echo "<br>select pagename from pagename where pagetype='Master' order by sorting";
                                        $userrights_query1 = mysql_query($user_query1);
                                        $master_rows1 = mysql_num_rows($master_query1);
                                        $user_rows1 = mysql_num_rows($userrights_query1);
                                        $view_uncheck = 0;
                                        $edit_uncheck = 0;
                                        $add_uncheck =  0;
                                        $delete_uncheck = 0;
                                        $select_uncheck =0;
                                            if( $master_rows1 != $user_rows1){
                                                $addallselect = '';
                                                $editallselect = '';
                                                $delallselect = '';
                                                $viewallselect = '';
                                                $allselect = '';
                                            }
                                            else{
                                            while($master_result1[] = mysql_fetch_array($master_query1));
                                            while($user_result1[] = mysql_fetch_array($userrights_query1));
                                            for($i=0;$i<$master_rows1;$i++){
                                                $flag = 0;
                                                for($j=0;$j<$user_rows1;$j++){
                                                    if($master_result1[$i]['pagename']== $user_result1[$j]['screen']) { 
                                                    
                                                 if($user_result1[$j]['viewrights'] == "Yes"){
                                                        $view_check = 'checked="checked"';
                                                    }else{
                                                        $view_uncheck++;
                                                    }
                                                    if($user_result1[$j]['editrights'] == "Yes"){
                                                        $edit_check = 'checked="checked"';
                                                    }else{
                                                        $edit_uncheck++;
                                                    }
                                                    if($user_result1[$j]['addrights'] == "Yes"){
                                                        $add_check = 'checked="checked"';
                                                    }else{
                                                        $add_uncheck++;
                                                    }
                                                    if($user_result1[$j]['deleterights']=="Yes"){
                                                        $delete_check = 'checked="checked"';
                                                    }else{
                                                        $delete_uncheck++;
                                                    }
                                                    if($user_result1[$j]['deleterights']=="Yes" && $user_result1[$j]['addrights']=="Yes" && $user_result1[$j]['editrights']=="Yes" && $user_result1[$j]['viewrights']=="Yes"){
                                                        $select_check = 'checked="checked"';
                                                    }else{
                                                        $select_uncheck++;
                                                    }
                                                    // str_replace(" ","",$master_result[$i]['pagename']);
                                                        $flag = 1;
                                                        break;

                                                    }
                                                }
                                                if($flag == 0){
                                                    $addallselect = '';
                                                    $editallselect = '';
                                                    $delallselect = '';
                                                    $viewallselect = '';
                                                    $allselect = '';
                                                    break;
                                              }


                                            }
                                            if($add_uncheck == 0){
                                                $addallselect = 'checked="checked"';
                                            }else{
                                                $addallselect = '';
                                            }
                                             if($view_uncheck == 0){
                                                $viewallselect = 'checked="checked"';
                                            }else{
                                                 $viewallselect = '';
                                            }
                                             if($edit_uncheck == 0){
                                                $editallselect = 'checked="checked"';
                                            }else{
                                                $editallselect = '';
                                            }
                                             if($delete_uncheck == 0){
                                                $delallselect = 'checked="checked"';
                                            }else{
                                                $delallselect ='';
                                            }
                                            if($add_uncheck == 0 && $view_uncheck == 0 && $edit_uncheck == 0 &&$delete_uncheck == 0){
                                                $allselect = 'checked="checked"';
                                            }else{
                                                $allselect = '';
                                            }

                                        }
                                    ?>
                                    <tr style="border-color:#058AFF; background-color:#058AFF;">
                                       
                                        <td style="border-color:#058AFF; text-align:center;" width="80px"><h4  style="color:white;">Screen Name</h4></td>

                                        <td style="border-color:#058AFF;text-align:center;"width="30px"><h4  style="color:white;">Add<br />
                                            <input style="text-align:right" type="checkbox" name="addselect" id="addselect" <?php echo $addallselect; ?> onClick="all_Add()" />
                                        </h4></td>
                                        <td style="border-color:#058AFF;text-align:center;"width="30px"><h4  style="color:white;">Edit<br />
                                            <input style="text-align:right" type="checkbox" name="editselect" id="editselect" <?php echo $editallselect; ?> onClick="all_edit()" />       
                                        </h4></td>
                                        <td style="border-color:#058AFF;text-align:center;"width="30px"><h4  style="color:white;">View<br />
                                            <input style="text-align:right" type="checkbox" name="viewselect" id="viewselect" <?php echo $viewallselect; ?> onClick="all_view()" />      
                                        </h4></td>
                                        <td style="border-color:#058AFF;text-align:center;"width="20px"><h4  style="color:white;">Delete<br />
                                            <input style="text-align:right" type="checkbox" name="deleteselect" id="deleteselect" <?php echo $delallselect; ?> onClick="all_del()" />      
                                        </h4></td>
                                        <td style="border-color:#058AFF;"width="40px"><div align="justify" style="margin-top:2px;"><h4   style=" text-align:left;color:white;">
                                          <center>Select All</center><center>
                                        
                                            <input style="text-align:center" type="checkbox" name="allselect1" id="allselect1" <?php echo $allselect; ?> onClick="all_checked()" /></center>
                                            </h4>
                                        </td>
                                     </tr>
                                     <? if(empty($userid)){ 
                                            $master_query = mysql_query("select pagename,displayname from pagename where pagetype='Master' order by sorting");
                                            while($master_result = mysql_fetch_array($master_query)){  ?>
                                                   <tr style="border-color:#058AFF; background-color:#F9F9F9;">
                                                        <td style="border-color:#058AFF;text-align:justify;"><h4  style="color:Black;"><? echo ucwords($master_result['displayname']); ?></h4></td>
                                                        <? 
                                                            $screename = trim(str_replace(" ","",$master_result['pagename']));
                                                            $add = trim($screename.'add');
                                                            $edit = trim($screename.'edit');
                                                            $view = trim($screename.'view');
                                                            $delete = trim($screename.'delete');
                                                            $select = trim($screename.'select');
                                                          ?>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox"  name="<? echo $add; ?>" id="<? echo $add; ?>"  value="" <?php //echo $add; ?>  onclick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')" /></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox"  name="<? echo $edit; ?>" id="<? echo $edit; ?>" value="" <?php //echo $edit; ?> onClick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')"/></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox" name="<? echo $view; ?>" id="<? echo $view; ?>" value="" <?php //echo $view; ?> onClick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')"/></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox"  name="<? echo $delete; ?>" id="<? echo $delete; ?>"  value="" <?php //echo $delete; ?> onClick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')"/></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox" name="<? echo $select; ?>" id="<? echo $select; ?>" <?php //echo $select; ?> onClick="set_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>')"  /></td> 
                                                    </tr>
                                                        
                                                      
                                        <?
                                            }
                                     ?>

                                     <? }else{
                                        $master_query = mysql_query("select pagename,displayname from pagename where pagetype='Master' order by sorting");
                                        $user_query = "select p.pagename as screen,u.viewrights,u.addrights,u.deleterights,u.editrights from userrights u left join pagename p on p.pagename = u.screen where p.pagetype='Master' and u.userid='".$userid."' order by p.sorting"; 
                                        // echo $user_query;
                                        // echo "<br>select pagename from pagename where pagetype='Master' order by sorting";
                                        $userrights_query = mysql_query($user_query);
                                        $master_rows = mysql_num_rows($master_query);
                                        $user_rows = mysql_num_rows($userrights_query);
                                            while($master_result[] = mysql_fetch_array($master_query));
                                            while($user_result[] = mysql_fetch_array($userrights_query));
                                            for($i=0;$i<$master_rows;$i++){
                                                $flag = 0;
                                                for($j=0;$j<$user_rows;$j++){
                                                    if($master_result[$i]['pagename']== $user_result[$j]['screen']) { ?>
                                                    <tr style="border-color:#058AFF; background-color:#F9F9F9;">
                                                    <td style="border-color:#058AFF;text-align:justify;"><h4  style="color:Black;"><? echo ucwords($master_result[$i]['displayname']); ?></h4></td>

                                            <?      if($user_result[$j]['viewrights'] == "Yes"){
                                                        $view_check = 'checked="checked"';
                                                    }else{
                                                        $view_check = '';
                                                    }
                                                    if($user_result[$j]['editrights'] == "Yes"){
                                                        $edit_check = 'checked="checked"';
                                                    }else{
                                                        $edit_check = '';
                                                    }
                                                    if($user_result[$j]['addrights'] == "Yes"){
                                                        $add_check = 'checked="checked"';
                                                    }else{
                                                        $add_check = '';
                                                    }
                                                    if($user_result[$j]['deleterights']=="Yes"){
                                                        $delete_check = 'checked="checked"';
                                                    }else{
                                                        $delete_check = '';
                                                    }
                                                    if($user_result[$j]['deleterights']=="Yes" && $user_result[$j]['addrights']=="Yes" && $user_result[$j]['editrights']=="Yes" && $user_result[$j]['viewrights']=="Yes"){
                                                        $select_check = 'checked="checked"';
                                                    }else{
                                                        $select_check = '';
                                                    }
                                                    // str_replace(" ","",$master_result[$i]['pagename']);
                                                    $screename = trim(str_replace(" ","",$master_result[$i]['pagename']));
                                                    $add = trim($screename.'add');
                                                    $edit = trim($screename.'edit');
                                                    $view = trim($screename.'view');
                                                    $delete = trim($screename.'delete');
                                                    $select = trim($screename.'select'); ?>
                                                       <td style="border-color:#058AFF;text-align:center;"><input type="checkbox"  name="<? echo $add; ?>" id="<? echo $add; ?>"  value="" <?php echo $add_check; ?>  onclick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')" /></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox"  name="<? echo $edit; ?>" id="<? echo $edit; ?>"  value="" <?php echo $edit_check; ?> onClick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')"/></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox" name="<? echo $view; ?>" id="<? echo $view; ?>" value="" <?php echo $view_check; ?> onClick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')"/></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox"  name="<? echo $delete; ?>" id="<? echo $delete; ?>" value="" <?php echo $delete_check; ?> onClick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')"/></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox" name="<? echo $select; ?>" id="<? echo $select; ?>" <?php echo $select_check; ?> onClick="set_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>')"  /></td> 
                                                    </tr>

                                                    <?

                                                        $flag = 1;
                                                        break;

                                                    }
                                                }
                                                if($flag == 0){
                                                    $screename = trim(str_replace(" ","",$master_result[$i]['pagename']));
                                                    $add = trim($screename.'add');
                                                    $edit = trim($screename.'edit');
                                                    $view = trim($screename.'view');
                                                    $delete = trim($screename.'delete');
                                                    $select = trim($screename.'select');?>
                                                    <tr style="border-color:#058AFF; background-color:#F9F9F9;">
                                                    <td style="border-color:#058AFF;text-align:justify;"><h4  style="color:Black;"><? echo ucwords($master_result[$i]['pagename']); ?></h4></td>
                                                    <td style="border-color:#058AFF;text-align:center;"><input type="checkbox"  name="<? echo $add; ?>" id="<? echo $add; ?>"  value=""  onclick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')" /></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox"  name="<? echo $edit; ?>" id="<? echo $edit; ?>" value=""  onClick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')"/></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox" name="<? echo $view; ?>" id="<? echo $view; ?>" value=""  onClick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')"/></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox"  name="<? echo $delete; ?>" id="<? echo $delete; ?>" value=""  onClick="see_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>','<?php echo $select; ?>')"/></td>
                                                        <td style="border-color:#058AFF;text-align:center;"><input type="checkbox" name="<? echo $select; ?>" id="<? echo $select; ?>"  onClick="set_checked('<?php echo $add; ?>','<?php echo $edit; ?>','<?php echo $view; ?>','<?php echo $delete; ?>')"  /></td> 
                                                    </tr>
                                            <?  }


                                            } 

                                        
                                     } ?>
                                     <!-- <tr> -->

                                     <!-- </tr> -->

                                 </table>

                           </div>
                      </div> 
                       <div style="width:930px; height:60px; float:left; margin-left:8px; margin-top:8px;">
                             
                    <div style="width:235px; height:50px; float:left;  margin-left:120px; margin-top:0px;" id="center1">
                           
                          <div style="width:90px; height:32px; float:left; margin-top:16px; margin-left:25px;">
                           <input name="<?php if(($row['editrights'])=='Yes') echo 'Save'; else echo 'permiss'; ?>" type="submit" class="button" value="Update" >
                           </div>   
                                                       
                          <div style="width:90px; height:32px; float:left;margin-top:16px; ">
                          <input name="Cancel" type="submit" class="button" value="Reset">
                           </div>                          
                                                   
                     </div> 
                         
                          <div style="width:400px; height:50px; float:left;  margin-left:25px; margin-top:0px;" class="cont" id="center2">
                              <!--Row1 -->  
                               <div style="width:80px; height:30px; float:left; margin-left:20px; margin-top:16px;" >
                                <label>User Id</label>
                               </div>
                               <div style="width:145px; height:30px;  float:left; margin-left:10px; margin-top:16px;">
                                 <input type="text" name="codes" onKeyPress="searchKeyPress(event);" value="<? echo  $_SESSION['codesval'] ?>"/>
                               </div>
                             <!--Row1 end-->  
                             
                               <!--Row2 -->  
                             
                             <!--Row2 end-->
                             
                             <div style="width:83px; height:32px; margin-left:10px; float:left; margin-top:16px;">
                                <!-- <input type="submit" name="Search" id="Search" value="" class="button1"/> -->
                                <input type="submit" name="Search" id="Search" value="Search" class="button"/>  
                               </div>  
                          </div> 


                </div>

               <div style="width:930px; height:auto; padding-bottom:8px; margin-top:20px; float:left; margin-left:10px;" class="grid">
                                  <table align="center" class="sortable" bgcolor="#FF0000" border="1" width="800px">
     <tr>
     
     <td class="sorttable_nosort" style="font-weight:bold; text-align:center;" width="12px">Action</td>
     <td style="font-weight:bold;">User ID</td>
    <!-- <td style="font-weight:bold;">Password </td>-->
     <td style="font-weight:bold;">Employee Name</td></tr>
 
 <?php
      // This while will loop through all of the records as long as there is another record left. 
      while( $record = mysql_fetch_array($query))
    { // Basically as long as $record isn't false, we'll keep looping.
      // You'll see below here the short hand for echoing php strings.
      // <?=$record[key] - will display the value for that array.
    ?>
    
     <tr>
   <!--<?php /*?>  <td style="font-weight:bold; text-align:center;"  bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<? echo $record['userid'];<?php */?>-->

  <td style="font-weight:bold; text-align:center" bgcolor="#FFFFFF"> <a style="color:#0360B2" name="edit" href="userrights.php?
  <?php if(($row['editrights'])=='Yes'){ echo 'edi=';  echo $record['userid']; } elseif($record['userid']==$_SESSION['username']){echo 'edi=';
  echo $record['userid']; } else echo 'permiss'; ?> " >Edit</a></td>

    <td  bgcolor="#FFFFFF">
        <?=$record['userid']?>
    </td>
     <!--<td  bgcolor="#FFFFFF"  align="left" valign="top">
      <?php /*?>  <?=$record['Password']?><?php */?>
    </td>-->
    <?php $empqry= mysql_query("select employeename from employeemaster where employeecode='".$record['empcode']."' ")  ;
    $emprec = mysql_fetch_array($empqry); ?>
    <td  bgcolor="#FFFFFF" ><?=$emprec['employeename']?></td>
   </tr>
  
   
  <?php
      }
  ?>
                 <?php
  if(isset($_POST['Search']))
{
if($myrow1==0)  
{?>
        <? echo '<tr ><td colspan="11" align="center" bgcolor="#FFFFFF" style="color:#F00"  >No Records Found</td></tr>'; ?>    
<? } }?>
</table>

               </div>
                  </div>
               <?php include("../../paginationdesign.php") ?>
               <!--  grid end here-->
            </form>
             <!-- form id start end-->      
          </div> 
          
     </div>       
</div>
<!--Third Block - Menu -Container -->


<!--Footer Block -->
<div id="footer-wrap1">
        <?php include("../../footer.php") ?>
  </div>
<!--Footer Block - End-->
</center></body>
</html>
