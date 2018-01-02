<script>
//Select First and last date


$(function() {
    $( "#rp_frdate" ).datepicker({
      defaultDate: "+1w",
	  changeYear:true,
	  maxDate: '0', 
	  yearRange: '2006:3050',
	  dateFormat:'dd-mm-yy',
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        $( "#rp_todate" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#rp_todate" ).datepicker({
      defaultDate: "+1w",
	  changeYear:true, 
	  yearRange: '2006:3050',
	  dateFormat:'dd-mm-yy',
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate ) {
	//date comparision from and to
	var fd=document.getElementById("rp_frdate").value;
	var fstval=fd.split("-");
	fd=new Date(fstval[1]+"-"+fstval[0]+"-"+fstval[2]);
	var td=selectedDate;
	var lstval=td.split("-");
	td=new Date(lstval[1]+"-"+lstval[0]+"-"+lstval[2]);
	if(fd>td)
	{
	alert('To date should be greater than from date');
	}
        $( "#rp_todate" ).datepicker( "option", "minDate", document.getElementById("rp_frdate").value);
	
      }
    });
	
  });
  </script>
  <script>
$(function() {
    $( "#stock_frdate" ).datepicker({
	  changeYear:true,
	  maxDate: '0', 
	  yearRange: '2006:3050',
	  dateFormat:'dd-mm-yy',
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
	  var s_fd=selectedDate;
	var lstval=s_fd.split("-");
	var stockdate="01-"+lstval[1]+"-"+lstval[2];	   
		$("#stock_frdate").datepicker("setDate", stockdate);
	   $( "#stock_todate" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#stock_todate" ).datepicker({
      defaultDate: "+1w",
	  changeYear:true, 
	  yearRange: '2006:3050',
	  dateFormat:'dd-mm-yy',
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate ) {
	//date comparision from and to
	var fd=document.getElementById("stock_frdate").value;
	var fstval=fd.split("-");
	//var fstval[0]
	fd=new Date(fstval[1]+"-"+fstval[0]+"-"+fstval[2]);
	var td=selectedDate;
	var lstval=td.split("-");
	td=new Date(lstval[1]+"-"+lstval[0]+"-"+lstval[2]);
	if(fd>td)
	{
	alert('To date should be greater than from date');
	}
        $( "#stock_todate" ).datepicker( "option", "minDate", document.getElementById("stock_frdate").value);
	
      }
    });
	
  });
  
  
  //To Select date period for reports
function datefun()
{
	var e = document.getElementById("Period");
	var tt = document.getElementById("curdate"); 
	var er=e.options[e.selectedIndex].value;
	var today = new Date(tt.value);
	var startval,endval;
	if(er=="Today")
	{
		var dd = today.getDate();
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear();
		if(dd<10){
			dd='0'+dd
		} 
		if(mm<10){
			mm='0'+mm
		} 
		endval=dd+'-'+mm+'-'+yyyy ;
		if(mm<04)
		{
		yyyy = today.getFullYear()-1;
		}
		startval='01-04-'+yyyy ;
	}
	else if(er=="Yesterday")
	{
		var dd = today.getDate()-1;
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear();
		if(dd<10){
			dd='0'+dd
		} 
		if(mm<10){
			mm='0'+mm
		}
		endval=dd+'-'+mm+'-'+yyyy ;
		if(mm<04)
		{
		yyyy = today.getFullYear()-1;
		}
		startval='01-04-'+yyyy ;
	}else if(er=="Only Today")
	{
		var dd = today.getDate();
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear();
		if(dd<10){
			dd='0'+dd
		} 
		if(mm<10){
			mm='0'+mm
		}
		startval=dd+'-'+mm+'-'+yyyy ;
		endval=startval;
	}else if(er=="Only Yesterday")
	{
		var dd = today.getDate()-1;
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear();
		if(dd<10){
			dd='0'+dd
		} 
		if(mm<10){
			mm='0'+mm
		}
		startval=dd+'-'+mm+'-'+yyyy ;
		endval=startval;
	}else if(er=="Current Calender Year")
	{
		var yyyy = today.getFullYear();
		startval='01-01-'+yyyy ;
		endval='31-12-'+yyyy ;
	}else if(er=="Last Calender Year")
	{
		var yyyy = today.getFullYear()-1;
		startval='01-01-'+yyyy ;
		endval='31-12-'+yyyy ;
	}else if(er=="Current Week")
	{
		var first = new Date(today.setDate((today.getDate() - today.getDay())));
		var dd = first.getDate();
		var mm = first.getMonth()+1;//January is 0!
		var yyyy = first.getFullYear();
		//var last = first + 6;
		if(dd<10){
			dd='0'+dd
		} 
		if(mm<10){
			mm='0'+mm
		}
		startval=dd+'-'+mm+'-'+yyyy ;
		var last= new Date(today.setDate(today.getDate()+6));
		var dd = last.getDate();
		var mm = last.getMonth()+1;//January is 0!
		var yyyy = last.getFullYear();
		if(dd<10){
			dd='0'+dd
		} 
		if(mm<10){
			mm='0'+mm
		}
		endval=dd+'-'+mm+'-'+yyyy ;
	}else if(er=="Last Week")
	{
		var first = new Date(today.setDate((today.getDate() - today.getDay())-7));
		var dd = first.getDate();
		var mm = first.getMonth()+1;//January is 0!
		var yyyy = first.getFullYear();
		//var last = first + 6;
		if(dd<10){
			dd='0'+dd;
		} 
		if(mm<10){
			mm='0'+mm;
		}
		startval=dd+'-'+mm+'-'+yyyy ;
		var last= new Date(today.setDate(today.getDate()+6));
		var dd = last.getDate();
		var mm = last.getMonth()+1;//January is 0!
		var yyyy = last.getFullYear();
		if(dd<10){
			dd='0'+dd
		} 
		if(mm<10){
			mm='0'+mm;
		}
		endval=dd+'-'+mm+'-'+yyyy ;
		
	}else if(er=="Current Month")
	{
		var dd = today.getDate();
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear();
		if(mm<10){
			mm='0'+mm
		}
		startval='01-'+mm+'-'+yyyy ;
		today=new Date(yyyy, mm , 0);
		dd = today.getDate();
		mm = today.getMonth()+1;//January is 0!
		yyyy = today.getFullYear();
		if(dd<10){
			dd='0'+dd;
		} 
		if(mm<10){
			mm='0'+mm;
		}
		endval=dd+'-'+mm+'-'+yyyy ;
	}else if(er=="Last Month")
	{
		var dd = today.getDate();
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear();
		/*if(mm<04)
		{
		var yyyy = today.getFullYear()-1;
		}
		else
		{
		var yyyy = today.getFullYear();
		}*/
		if(mm==1)
		{
		mm =12;
		var yyyy = today.getFullYear()-1;
		}
		else
		{
		mm = mm -1;
		}
		if(dd<10){
			dd='0'+dd;
		} 
		if(mm<10){
			mm='0'+mm;
		}
		
		startval='01-'+mm+'-'+yyyy ;
		today=new Date(yyyy, mm , 0);
		dd = today.getDate();
		endval=dd+'-'+mm+'-'+yyyy ;
	}else if(er=="Current Financial Year")
	{
	
		var mm = today.getMonth()+1;//January is 0!
		if(mm<04)
		{
		var yyyy = today.getFullYear()-1;
		}
		else
		{
		var yyyy = today.getFullYear();
		}
		startval='01-04-'+yyyy ;
		if(mm<04)
		{
		var yyyy = today.getFullYear();
		}
		else
		{
		var yyyy = today.getFullYear()+1;
		}
		endval='31-03-'+yyyy ;
	}
	else if(er=="Last Financial Year")
	{
		var mm = today.getMonth()+1;//January is 0!
		if(mm<04)
		{
		var yyyy = today.getFullYear()-2;
		}
		else
		{
		var yyyy = today.getFullYear()-1;
		}
		startval='01-04-'+yyyy ;
		if(mm<04)
		{
		var yyyy = today.getFullYear()-1;
		}
		else
		{
		var yyyy = today.getFullYear();
		}
		endval='31-03-'+yyyy ;
	}
	else if(er=="Current Quarter")
	{
    var month = today.getMonth();
	var yyyy = today.getFullYear();
    if (month < 4)
	{
    startval='01-01-'+yyyy ;
    endval='31-03-'+yyyy ;
    }
	else if (month < 7)
    {
	startval='01-04-'+yyyy ;
    endval='30-06-'+yyyy ;
    }
	else if (month < 10)
    {
	startval='01-07-'+yyyy ;
    endval='30-09-'+yyyy ;
    }
	else if (month < 13)
    {
	startval='01-10-'+yyyy ;
    endval='31-12-'+yyyy ;
	}
	
	}
	
		else if(er=="Last Quarter")
	{
    var month = today.getMonth();
	var yyyy = today.getFullYear();
    if (month < 4)
	{
	yyyy = today.getFullYear()-1;
    startval='01-10-'+yyyy ;
    endval='31-12-'+yyyy ;
    }
	else if (month < 7)
    {
	startval='01-01-'+yyyy ;
    endval='31-03-'+yyyy ;
    }
	else if (month < 10)
    {
	startval='01-04-'+yyyy ;
    endval='30-06-'+yyyy ;
    }
	else if (month < 13)
    {
	startval='01-07-'+yyyy ;
    endval='30-09-'+yyyy ;
	}
	
	}
	
	document.getElementById("rp_frdate").value=startval;
	document.getElementById("rp_todate").value=endval;
	document.getElementById('frdate').value = startval;
	document.getElementById('todate').value = endval;
	if(er=="Custom")
	{
	document.getElementById('rp_frdate').type = 'text';
	document.getElementById('rp_todate').type = 'text';
	document.getElementById('frdate').type = 'hidden';
	document.getElementById('todate').type = 'hidden';
	document.getElementById("rp_todate").value="";
	document.getElementById("rp_frdate").value="";
	document.getElementById("frdate").disabled="";
	document.getElementById("todate").value="";
	document.getElementById('frdate').disabled = true;
	document.getElementById('todate').disabled = true;
	}
}

//Day wise Sysc Report to fetch From date To date

function SetDateValue()
{

	
   try
   {
   	var er = document.getElementById("Period").value;
	var tt = document.getElementById("curdate"); 
	//var er=e.options[e.selectedIndex].value;
	var today = new Date(tt.value);
	var startval,endval;
if(er =="Custom")
{
console.log("bdg");
startval='';
endval='';
var yyyy = today.getFullYear();
document.getElementById("dw_YrPick").value = yyyy;
//$("#div_txt1").show();
var div2 = document.getElementById('div_txt1'); 
div2.style.visibility = '';
div2.style.visibility = 'visible';
}
else if(er=="Current Month")
	{
		var dd = today.getDate();
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear();
		startval='01-'+mm+'-'+yyyy ;
		today=new Date(yyyy, mm , 0);
		dd = today.getDate();
		mm = today.getMonth()+1;//January is 0!
		yyyy = today.getFullYear();
		endval=dd+'-'+mm+'-'+yyyy ;
		var div2 = document.getElementById('div_txt1'); 
        div2.style.visibility = 'hidden';
		//$("#div_txt1").hide();

	}
	else if(er=="Last Month")
	{
		var dd = today.getDate();
		var mm = today.getMonth()+1;//January is 0!
		var yyyy = today.getFullYear();
		if(mm==1)
		{
		mm =12;
		yyyy=today.getFullYear()-1;
		
		}
		else
		{
		mm = mm -1;
		}
		startval='01-'+mm+'-'+yyyy ;
		today=new Date(yyyy, mm , 0);
		dd = today.getDate();
		mm = today.getMonth()+1;//January is 0!
		//yyyy = today.getFullYear();
		endval=dd+'-'+mm+'-'+yyyy ;
		//$("#div_txt1").hide();
		var div2 = document.getElementById('div_txt1'); 
        div2.style.visibility = 'hidden';

	}

	

	document.getElementById('frdate').value = startval;
	document.getElementById('todate').value = endval;

	

}
catch(Exception)
{alert("Error");}
}

function customdate()
{

   	var er = document.getElementById("Period").value;
	var tt = document.getElementById("curdate"); 
	//var er=e.options[e.selectedIndex].value;
	var today = new Date(tt.value);
	var startval,endval;
	
			
if(er =="Custom")
{

		var dd = "30";
		var mm = document.getElementById("Month").value;//January is 0!
		var yyyy = document.getElementById("dw_YrPick").value;
		startval='01-'+mm+'-'+yyyy ;
		today=new Date(yyyy, mm , 0);
		dd = today.getDate();
		mm = today.getMonth()+1;//January is 0!
		yyyy = today.getFullYear();
		endval=dd+'-'+mm+'-'+yyyy ;
		
}
	document.getElementById('frdate').value = startval;
	document.getElementById('todate').value = endval;


}
  </script>
<?php

function ep($obj) {
    echo '<pre>';
    print_r($obj);
}

function epe($obj) {
    ep($obj);
    exit;
}

function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d') {

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while ($current <= $last) {

        $dates[] = date($format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}

function rangeWeek($datestr) {
    date_default_timezone_set(date_default_timezone_get());
    $date = strtotime($datestr);

    $weekstart = strtotime('last monday', $date);   //Week Start and End date
    $weekend = strtotime('sunday', $date);
    if (date('l', $date) == 'Monday') {                //To find Current day is Monday or Sunday
        $weekstart = $date;
        $weekend = strtotime('sunday', $date);
    }
    //Month Start and End date
    $monthstart = strtotime("-" . (date("d", $date) - 1) . " days", $date);
    $monthend = strtotime("+" . (date("t", $monthstart) - 1) . " days", $monthstart);
    $monthstart_minusone = strtotime(date('d-m-Y', $monthstart) . ' -1 day');
    $monthend_plusone = strtotime(date('d-m-Y', $monthend) . ' + 1 day');

    if ($weekend > $monthend) {
        $res['week1_starts'] = $weekstart;
        $res['week1_ends'] = $monthend;
        $res['week2_starts'] = $monthend_plusone;
        $res['week2_ends'] = $weekend;
    } else if ($weekstart < $monthstart) {
        $res['week1_starts'] = date('d-m-Y', $weekstart);
        $res['week1_ends'] = date('d-m-Y', $monthstart_minusone);
        $res['week2_starts'] = date('d-m-Y', $monthstart);
        $res['week2_ends'] = date('d-m-Y', $weekend);
    } else {
        $res['week_starts'] = $weekstart;
        $res['week_ends'] = $weekend;
    }
    return $res;
}

function CurrentQuarter($n) {
    $currentQuarter = 0;
    if ($n < 4) {
        $currentQuarter = "01-01-" . date('Y') . " to 31-03-" . date('Y') . "";
    } elseif ($n > 3 && $n < 7) {
        $currentQuarter = "01-04-" . date('Y') . " to 31-06-" . date('Y') . "";
    } elseif ($n > 6 && $n < 10) {
        $currentQuarter = "01-07-" . date('Y') . " to 31-09-" . date('Y') . "";
    } elseif ($n > 9) {
        $currentQuarter = "01-10-" . date('Y') . " to 31-12-" . date('Y') . "";
    }
    return $quarter = explode(" to ", $currentQuarter);
}

/*
 * Weeks in Current Quarter
 */

function WeeksInsideQuarter($quarter_start_date, $quarter_end_date) {
    $start_date = strtotime($quarter_start_date);
    $end_date = strtotime($quarter_end_date);
    $days_between = ceil(abs($end_date - $start_date) / 86400);
    $weeks = $days_between / 7;
    for ($i = 1; $i <= $weeks; $i++, $start_date+=604800) {
        $week_dates[$i] = date('M j', $start_date);
    }
    return $week_dates;
}

function get_months($date1, $date2) {
    $time1 = strtotime($date1);
    $time2 = strtotime($date2);
    $my = date('n-Y', $time2);
    $mesi = range(1, 12);

    //$months = array(date('F', $time1));  
    $months = array();
    $f = '';

    while ($time1 < $time2) {
        if (date('n-Y', $time1) != $f) {
            $f = date('n-Y', $time1);
            if (date('n-Y', $time1) != $my && ($time1 < $time2)) {
                $str_mese = $mesi[(date('n', $time1) - 1)];
                $months[] = date('F Y', $time1);
            }
        }
        $time1 = strtotime((date('Y-n-d', $time1) . ' +15days'));
    }

    $str_mese = $mesi[(date('n', $time2) - 1)];
    $months[] = date('F Y', $time2);
    return $months;
}

function week_date_range($st, $et) {

    $start_date = date('Y-m-d', strtotime($st));
    $end_date = date('Y-m-d', strtotime($et));
    $end_date1 = date('Y-m-d', strtotime($et . '+ 7 days'));

    $weekfrom = array();
    $weekto = array();

    for ($date = $start_date; $date <= $end_date1; $date = date('Y-m-d', strtotime($date . ' + 7 days'))) {

        $week = date('W', strtotime($date));
        $year = date('Y', strtotime($date));
        $from = date("Y-m-d", strtotime("{$year}-W{$week} - 1 days")); //Returns the date of monday in week
        if ($from < $start_date)
            $from = $start_date;
        $to = date("Y-m-d", strtotime("{$year}-W{$week} + 6 days - 1 days"));   //Returns the date of sunday in week
        if ($to > $end_date) {
            $to = $end_date;
        }
        if ($from < $to) {
            array_push($weekfrom, $from);
            array_push($weekto, $to);
        }
    }
    $n = count($weekfrom);

    for ($i = 0; $i < $n; $i++) {
        $result[$i]['start'] = $weekfrom[$i];
        $result[$i]['end'] = $weekto[$i];
        //echo "Start Date-->" . $weekfrom[$i];
        //echo " End Date -->" . $weekto[$i] . "\n";
    }
    return $result;
}

function get_weekwise_date_month($from_date, $to_date) {
    $str_from_date = date('m-Y', strtotime($from_date));
    $str_to_date = date('m-Y', strtotime($to_date));
    $months = get_months($from_date, $to_date);
//print("<pre>"); print_r($months); print("</pre>");
    $i = 0;
    $count = count($months);
    foreach ($months as $month_val) {
        $i++;
        $end_val = date('t', strtotime($month_val)) . '-' . $month_val;
        if ($i == 1) {
            $start_val = $from_date;
            if ($str_from_date == $str_to_date) {
                $end_val = $to_date;
            }
        } else if ($i == $count) {
            $start_val = '01-' . $month_val;
            $end_val = $to_date;
        } else {
            $start_val = '01-' . $month_val;
        }
        $st = date('Y-m-d', strtotime($start_val));
        $et = date('Y-m-d', strtotime($end_val));

        $result[date('m-Y', strtotime($month_val))] = week_date_range($st, $et);
        //$result[date('m-Y', strtotime($month_val))] = $st.' to '.$et;
    }
    return $result;
}

function getdays()
{
$year = document.getElementById("dw_YrPick").value; $month = document.getElementById("Month").value;

$starts = 1;
$ends = date('t', strtotime($month.'/'.$year));

}


