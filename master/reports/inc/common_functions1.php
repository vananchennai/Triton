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