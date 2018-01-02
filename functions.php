<?Php
function sec_session_start() {
      /*  $session_name = 'sec_session_id'; // Set a custom session name
        $secure = true; // Set to true if using https.
        $httponly = false; // This stops javascript being able to access the session id. 
 
        ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
        $cookieParams = session_get_cookie_params(); // Gets current cookies params.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name); // Sets the session name to the one set above.*/
		if(!isset($_SESSION))
        session_start(); // Start the php session
      //  session_regenerate_id(true); // regenerated the session, delete the old one.   
		}

function login($userid, $password, $mysqli) {
	
   // Using prepared Statements means that SQL injection is not possible. 
   if ($stmt = $mysqli->prepare("SELECT id, userid, password, empcode, salt, timeout, status FROM usercreation WHERE userid = ? LIMIT 1")) { 
   
      $stmt->bind_param('s', $userid); // Bind "$userid" to parameter.
      $stmt->execute(); // Execute the prepared query.
      $stmt->store_result();
	 
      $stmt->bind_result($table_id, $userid,$db_password,$empcode,$salt,$timeout,$status); // get variables from result.
      $stmt->fetch();
     $password = hash('sha512', $password.$salt); // hash the password with the unique salt.
      if($stmt->num_rows == 1) { // If the user exists
      	 if($status == 'OUT') { // Check if the user already login or not. 
	
         if($db_password == $password) { // Check if the password in the database matches the password the user submitted. 
            // Password is correct!
 
             $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
             $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
             $userid = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $userid); // XSS protection as we might print this value
             $_SESSION['username'] = $userid;
			 $empcode = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $empcode); // XSS protection as we might print this value
			 $empnqry = $mysqli->prepare("select employeename from employeemaster where employeecode='".$empcode."'");
			 $empnqry->execute();
			 $empnqry->store_result();
			 $empnqry->bind_result($employeename); // get variables from result.
      		 $empnqry->fetch();
             $_SESSION['employeeusername']= $employeename;
             //$_SESSION['employeeusername']= $empcode;
             $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
			 $pagenation = $mysqli->prepare("select page from pagination");
			 $pagenation->execute(); // Execute the prepared query.
     		 $pagenation->store_result();
			$pagenation->bind_result($pagecount); // get variables from result.
			$pagenation->fetch();
			$_SESSION['pagecount']=$pagecount;
			$mysqli->query("UPDATE usercreation SET status = 'IN' WHERE userid= '".$_SESSION['username']."'");
               // Login successful.
               return true;    
         } 
		 }
		 else {// The time has not come yet.

			 date_default_timezone_set ("Asia/Calcutta");
			 $row_date = strtotime($timeout);
			 $currtime=date("Y-m-d H:i:s", time());
			 $curr_date = strtotime($currtime);
			 $timediff=$curr_date-$row_date;
			 if( $timediff >= "5"){// The time has not come yet.
			 
				if($db_password == $password) { // Check if the password in the database matches the password the user submitted. 
				// Password is correct!
				
					$ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
					$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
					
					$userid = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $userid); // XSS protection as we might print this value
					$_SESSION['username'] = $userid;
					$empcode = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $empcode); // XSS protection as we might print this value
					$empnqry = $mysqli->prepare("select employeename from employeemaster where employeecode='".$empcode."'");
					$empnqry->execute();
					$empnqry->store_result();
					$empnqry->bind_result($employeename); // get variables from result.
					$empnqry->fetch();
					$_SESSION['employeeusername']= $employeename;
					$_SESSION['login_string'] = hash('sha512', $password.$user_browser);
					$pagenation = $mysqli->prepare("select page from pagination");
					$pagenation->execute(); // Execute the prepared query.
					$pagenation->store_result();
					$pagenation->bind_result($pagecount); // get variables from result.
					$pagenation->fetch();
					$_SESSION['pagecount']=$pagecount;
					$mysqli->query("UPDATE usercreation SET status = 'IN' WHERE userid= '".$_SESSION['username']."'");
					// Login successful.
					return true;    
         		} 
		 	}
			else {
					?>
					<script type="text/javascript">			
					alert("<? echo $_SESSION['username']?> user already logged in!");document.location='logout.php';			
					</script>
					<? 
			}
		 }
      } else {// No user exists. 
         return false;
      }
   }
}

function login_check($mysqli) {
   // Check if all session variables are set
   
   if(isset($_SESSION['employeeusername'], $_SESSION['username'], $_SESSION['login_string'])) {
	  
     $empcode = $_SESSION['employeeusername'];
     $login_string = $_SESSION['login_string'];
     $userid = $_SESSION['username'];
     $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
     $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
     if ($stmt = $mysqli->prepare("SELECT password FROM usercreation WHERE userid = ? LIMIT 1")) { 
        $stmt->bind_param('i', $userid); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();
 
        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($password); // get variables from result.
           $stmt->fetch();
           return true;
        } else {
            // Not logged in
            return false;
        }
     } else {
        // Not logged in
        return false;
     }
   } else {
     // Not logged in
     return false;
   }
}

function reportcall($reportname,$conditions,$startpoint,$limit) {
	$url = 'http://localhost:8090/solr/'.$reportname.'/select?q='.urlencode($conditions).'&wt=json&start='.$startpoint.'&rows='.$limit.'&indent=true';
$response= file_get_contents($url);
$secresponse = json_decode($response);	
return 	$secresponse;				
}

function exportcall($reportname,$conditions,$htitle) {
	$url = 'http://localhost:8090/solr/'.$reportname.'/select?q='.urlencode($conditions).'&fl='.$htitle.'&wt=csv&start=0&rows=200000&indent=true';
$response= file_get_contents($url);
return 	$response;				
}
?>
