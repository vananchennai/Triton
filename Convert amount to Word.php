<?php
  /**
   * Created by PhpStorm.
   * User: sakthikarthi
   * Date: 9/22/14
   * Time: 11:26 AM
   * Converting Currency Numbers to words currency format
   */
   class Convert_Number_Into_Word
   {
   public function array_words($number)
   {
//$number = 6852825.10;
   $SplitedNumberValue=explode(".", $number);
   $no = $SplitedNumberValue[0];//round($number);
   $point = $SplitedNumberValue[1];//round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'One', '2' => 'Two',
    '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    '13' => 'Thirteen', '14' => 'Fourteen',
    '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
    '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    '60' => 'Sixty', '70' => 'Seventy',
    '80' => 'Eighty', '90' => 'Ninety');
   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? '' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  // $points = ($point) ?
    // "." . $words[$point / 10] . " " . 
          // $words[$point = $point % 10] : '';
	  
		  
  while ($i < $point) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($point % $divider);
     $point = floor($point / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str1)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str1[0]) ? ' and ' : null;
        $str1 [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str1[] = null;
  }
  $str1 = array_reverse($str1);
  $result1 = implode('', $str1);
  
  if ($SplitedNumberValue[1]=="00"){
 $FinalValue =$result . "Rupees Only" ;
 }else{
 $FinalValue =$result . "Rupees and " . $result1 ."Paise Only";
 }
 // $FinalValue =$result . "Rupees  " . $points . " Paise";
  //$FinalValue =$result . "Rupees  " . "and" . " Paise";
  return $FinalValue;
  }
  }
  
 ?> 