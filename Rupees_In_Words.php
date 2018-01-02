<?
class number_to_words
{
        public function __construct()
    {
             // initialization values
        $this->_hyphen      = '-';
        $this->_separator   = ', ';
        $this->_negative    = 'negative ';
        $this->_space       = ' ';
        $this->_conjunction = ' and ';
        // $this->_decimal     = 'paise ';  
		 $this->_decimal     = '';
        $this->_rupees      = ' rupees';
        $this->_only        = ' only';
            
        // call array of words
        $this->arr_words();           
    }

    protected function arr_words()
    {
        // array words
        $this->_dictionary   = array(
          "0"                   => 'zero',
          "1"                   => 'one',
          "2"                   => 'two',
          "3"                   => 'three',
          "4"                   => 'four',
          "5"                   => 'five',
          "6"                   => 'six',
          "7"                   => 'seven',
          "8"                   => 'eight',
          "9"                   => 'nine',
          "00"                  => 'zero zero',
          "01"                  => 'zero one',
          "02"                  => 'zero two',
          "03"                  => 'zero three',
          "04"                  => 'zero four',
          "05"                  => 'zero five',
          "06"                  => 'zero six',
          "07"                  => 'zero seven',
          "08"                  => 'zero eight',
          "09"                  => 'zero nine',
          "10"                  => 'ten',
          "11"                  => 'eleven',
          "12"                  => 'twelve',
          "13"                  => 'thirteen',
          "14"                  => 'fourteen',
          "15"                  => 'fifteen',
          "16"                  => 'sixteen',
          "17"                  => 'seventeen',
          "18"                  => 'eighteen',
          "19"                  => 'nineteen',
          "20"                  => 'twenty',
          "30"                  => 'thirty',
          "40"                  => 'fourty',
          "50"                  => 'fifty',
          "60"                  => 'sixty',
          "70"                  => 'seventy',
          "80"                  => 'eighty',
          "90"                  => 'ninety',
          "100"                 => 'hundred',
          "1000"                => 'thousand',
          "1000000"             => 'million',
          "1000000000"          => 'billion',
          "1000000000000"       => 'trillion',
          "1000000000000000"    => 'quadrillion',
          "1000000000000000000" => 'quintillion'
      );
   } // end function arr_words
                                
   /**  
     * @param $number
    * @param $first
    */
    public function convert_number_to_words($number, $first=true) 
    {
       //check number is number or not
       if (!is_numeric($number)) {
          return false;
       }
            
       if (($number >= 0 && intval($number )< 0) || intval($number) < 0 - PHP_INT_MAX) {
                
          // overflow
          trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING);
                 return false;
       }
        
       //check number whether is negative or not
       //if it is negative then call the function with positive number
       if ($number < 0) {
          return $this->_negative . $this->convert_number_to_words(abs($number));
       }
       //assign null value to variables
       $string = $fraction = null;
            
       // check Decimal place in number
       if (strpos($number, '.') !== false) {
               
           list($number, $fraction) = explode('.', $number);
       }
           
       switch (true) 
       {
           case $number < 21:
                    
             $string = $this->_dictionary["$number"];
             break;
                    
           case $number < 100:
                     
              $tens   = (intval($number / 10)) * 10;
              $units  = $number % 10;
              $string = $this->_dictionary["$tens"];
                   
              if ($units) {
                 $string .= $this->_space . $this->_dictionary["$units"];
              }
              break;
                    
           case $number < 1000:
                    
               $hundreds  = intval($number / 100);
               $remainder = $number % 100;
$string = $this->_dictionary["$hundreds"] . ' ' .$this->_dictionary["100"];
                    
               if ($remainder) {
                        $string .= $this->_conjunction . $this->convert_number_to_words($remainder, false);
               }
               break;
                    
           default:
                   
              $baseUnit = pow(1000, floor(log($number, 1000)));                
              $numBaseUnits = intval($number / $baseUnit);
              $remainder = $number % $baseUnit;
              $string = $this->convert_number_to_words($numBaseUnits, false) . ' ' . $this->_dictionary["$baseUnit"];
                    
              if ($remainder) {
                        
                     $string .= $this->_conjunction;
                 $string .= $this->convert_number_to_words($remainder, false);
              }
              break;
      }
    
       // start - decimal place
        if (null !== $fraction && is_numeric($fraction)) {
        	
         $string .= $this->_rupees . $this->_conjunction . $this->_decimal;
        		
        /**
         * if decimal comes 10, 20, 30 ..upto 90. 0 is removing from number.
         * suppose you were not specify decimal place with 2 digits. like 100.5, 3.9
         * so we need CONCAT 0 with number
         * it would come ten twenty..
         */
       if ($fraction < 10) $fraction = $fraction . '0';
        		    
          $string .= $this->convert_number_to_words($fraction, false);
              //add only
          $string .= $this->_only;
       }
       // end  - decimal place
            
       //first time only this condition would execute.
       //without decimal place.
        if ($fraction === null && $first == true) {
            $string .= $this->_rupees . $this->_only;
        }
            
      return $string;
            
   } // end function convert_number_to_words
        
}// end class
//............................................................................................................................................................
?>