<?php
require_once 'Mysql.php'; // include this file so we can use the Mysql Class inside this class as well.

class News
{
    // In this class we'll have a few variables to be used by the class.
    var $mysql; // the mysql variable that will hold the Mysql Object.
    
    public function __construct()
    {
        $this->mysql = new Mysql(); // Connect to mysql and the database so we can use it.
    }
    // This function does what it says, it gets all of the current news records from the db.
    public function getNews($tname)
    {
        $rset = $this->mysql->select('*',$tname);
        return $rset;
    }
    // You should update this function how you see fit. You could add more params to return only
    // the fields you want.
       
    // This will get a single record in the database.
    public function getNewsById($id)
    {
        $rset = $this->mysql->select('*',$tname,"ProductCode='$id'");
        return $rset;
    }
    
    // Your going to need some way to add and edit your news entries right?
    // well here ya go :P
    
    // This function will save you lots of time writing out the fields variable everytime.
    public function buildFields($post, $sep=" ") // $post comes in as an array of variables.
    {    
        $fields = ""; // This makes sure we don't run into any past fields.
		$i = "";
        foreach($post as $key => $value)
        {
        
		    // foreach will take each element of the $post array and seperate
            // each of the values with its key $post[key] = value;
            $value = mysql_real_escape_string($value); // We'll do a small security check here.
            // I'll explain that in another tutorial. Basically it protect mysql from hackers.
            if($i == 0)
                $fields .= "$key='$value'";
            else
                $fields .= $sep . "$key='$value'";
            // This will create your fields string based on each element in the post array.
            $i++;
        }
        return $fields; // Return the string, $fields.
    }
    public function addNews($post,$tname)
    {
        $fields = $this->buildFields($post, ", "); // take the post array and break it into a string.
        if( $this->mysql->insert($tname,$fields) ) // This is pretty basic. Inserts the new news record.
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    // This function is just the same as addnews, except that it updates an existing record.
    public function editNews($post,$tname,$wherecon)
    {
        $fields = $this->buildFields($post, ", ");
        		//$var1=$post['ProductCode'];
				//$var2=$post['ManufactureDate']; // retreive the newsId we need to update
        if( $this->mysql->update($tname,$fields,$wherecon) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function deleteNews($tname,$wherecon)
    {
		
        if( $this->mysql->delete($tname,$wherecon) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
	public function dateformat($theDate)
	 {
		if(!empty($theDate))
		{
		 $date = DateTime::createFromFormat('d/m/Y', $theDate);
		 $newDate = $date->format('Y-m-d');
		 return $newDate;
		}
      }
	public function write($line)
	{
		echo $line;
		
	}
public function unlinkfun($path)
	{
		unlink('data.csv');
		unlink($path);
	}
	public function exceldate($dateval)
	{
		$count=strlen($dateval);
		if($count==5)
		{
			$defaultdate='01/01/1970';
			$defaultdatevalue='25569';
			$currentdatevalue=$dateval;
			$diffdate= $currentdatevalue-$defaultdatevalue;
			$adddays='+ '.$diffdate.' days';
			$date=date('Y-m-d', strtotime($defaultdate. $adddays));
			return $date;
			
		}
		else if($count==10)
		{
			$date = DateTime::createFromFormat('d/m/Y', $dateval);
		 	$newDate = $date->format('Y-m-d');
			$subday='- 1 days';
			$date=date('Y-m-d', strtotime($newDate. $subday));
			return $date;
		}
	}
	
  public function checkcon($tname,$wherecon)
    {
		
        if( $this->mysql->delete($tname,$wherecon) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
/*	public function pagnationfunintial($tname,$pagecount)
	{
	

	   $page = (int) (!isset($pagecount) ? 1 : 1);
    	$limit = $pagecount;
    	$startpoint = ($page * $limit) - $limit;
        $statement = $tname; 
		$starvalue = "";
        $query = mysql_query("SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}");
		$_SESSION['testingpage']=$limit;
		
}
public function pagnationfunpre($tname,$pagecount,$session)
{
	if(isset($session))
	{
		
	   $page = (int) (!isset($pagecount) ? 1 : $pagecount);
    	$limit = $session;
    	$startpoint = ($page * $limit) - $limit;
        $statement = $tname; 
		$starvalue = "";
        $query = mysql_query("SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}");
	}
	else
	{
		
	   $page = (int) (!isset($pagecount) ? 1 : $pagecount);
    	$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
        $statement = $tname; 
		$starvalue = "";
        $query = mysql_query("SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}");
	}

}*/
}
?>
