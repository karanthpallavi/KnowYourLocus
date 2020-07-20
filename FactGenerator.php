<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>
        Know Your Locus
        </title>
        <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<script src="js/jquery-1.2.6.min.js" type="text/javascript"></script>
<script src="js/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="js/jquery.kwicks-1.5.1.pack.js" type="text/javascript"></script>

<script type="text/javascript">
	$().ready(function() {
		$('.kwicks').kwicks({
			max : 710,
			spacing : 0,  sticky: true
		});
	});
</script>

<script type="text/javascript" src="js/jquery-1-4-2.min.js"></script>
<!--<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" />-->
<script type="text/JavaScript" src="js/slimbox2.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDSj70kGpBWTTTzmj2O6k6AuinjhTJk1Ck&libraries=places&sensor=false"></script>
<script>

// Success callback function
function displayPosition(pos) {
    var mylat = "<?php echo $latitude; ?>";
    var mylong = "<?php echo $longitude; ?>";
    //Load Google Map
    var latlng = new google.maps.LatLng(mylat, mylong);
    var myOptions = {
    zoom: 16,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.HYBRID
};

var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

// Places
var request = {
    location: latlng,
    radius: '20000',
    name: ['whatever']
};

var service = new google.maps.places.PlacesService(map);
service.search( request, callback );

function callback(results, status) 
{
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        for (var i = 0; i < results.length; i++) {
            var place = results[i];
            createMarker(results[i]);
            }
        }
    }

    function createMarker(place) {
        var placeLoc = place.geometry.location;
        var marker = new google.maps.Marker({
        map: map,
        position: place.geometry.location
        });
        }

var marker = new google.maps.Marker({
     position: latlng, 
     map: map, 
     title:"You're here"
 });


}

// Error callback function
function errorFunction(pos) {
    alert('It seems like your browser or phone has blocked our access to viewing your location. Please enable this before trying again.');
}
</script>

</head>
    <body>
        <form name="FactGenerator" id="FactGenerator" method="POST" action="http://localhost/KnowYourLocus/FactGenerator.php">
        <div id="logo">
		<h1><a href="#">Know Your Locus </a></h1>
		<h3>&nbsp;<b>Fact Generator based on your locus</b></h3>
		</div>		
<p></p><p>&nbsp;</p>                    
         <div id="map-canvas" style="width: 320px; height: 480px;"></div>                                      
	
 <?php
 
	// Function to convert IP Address to decimal
	function ip2dec($IPaddr) {
  $d = 0.0;
    $b = explode(".", $IPaddr,4);
      for ($i = 0; $i < 4; $i++) {
         $d *= 256.0;
         $d += $b[$i];
       };
  return $d;

}

function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    /*else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];*/
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

        require_once 'dbcredential.php';
		
		/*if (is_null($_POST["dlevel"])) $wantlevel = 3;
   else $wantlevel = $_POST["dlevel"];
   
   $wantlevel = ($wantlevel + rand(1,3))%6 + 1;*/ 
       $db_server = mysql_connect($db_hostname, $db_username, $db_password);

        if(!$db_server) die("Unable to connect to MySQL: ".mysql_error());
        //else
            //echo"Connected to DB Successfully!";

        mysql_select_db($db_database) or die("Unable to connect to database: ".mysql_error());
		
		// Find IP address of the Client to get the geolocation
		//$ip = $_SERVER['REMOTE_ADDR'];
		//$ip = get_client_ip();
		//echo $ip;
		
		// Hard Code IP Address for now
		//$ip = 27.251.237.154;
		// Convert IP to Decimal notation from dot format
		//$ipno = ip2dec($ip);
		//Blore
		$ipno = 469495194;
		//Mumbai
		//$ipno = 1936719887;
		//Thrissur
		//$ipno = 1976335122;
		//echo $ipno;
		
		// SQL query string to match the recordset that the IP number fall between the valid range
		$query = "SELECT * FROM ip2location_db11 WHERE $ipno <= ip_to LIMIT 1";
		$result = mysql_query($query) or die("IP2Location Query Failed");
		$row = mysql_fetch_object($result);
		// Obtain the city,region names, latitude and longitude from the results
		$cityname = $row->city_name;
		$region_name = $row->region_name;
		$latitude = $row->latitude;
		$longitude = $row->longitude;
		$city_name = strtolower($cityname);
		echo $city_name. " ";
		mysql_free_result($result);
		
		// Check if the IP Address is the same as last time's ip address
		
		if(isset($_POST["ipAddr"]))
		{
		echo "Am in ipAddr set";
		$lastIpAddr = $_POST["ipAddr"];
		//echo $lastIpAddr;
		if($ipno == $lastIpAddr)
		{
			if(isset($_POST["SeqNums"]))
			{	
			echo "SeqNum Array set - Get random SeqNum from the array";
				$seqNumVar = $_POST["SeqNums"];
				$array_var = unserialize(base64_decode($seqNumVar));
				//var_dump($array_var);
				$seqIdx = array_rand($array_var);
				$seqNum = $array_var[$seqIdx];
				echo $seqIdx;
				$seqSet = 1;
			}
			else
			{
				echo "SeqNum Array not set";
				$seqSet = 0;
			}
	    }
		else
			echo "Am in IPAddr not set";
			$seqSet = 0;
		}
		$SeqNums = array();
		// Query to get facts about the city
		echo " ".isset($seqSet);
		if(!isset($seqSet))
		{
		echo "In seqSet = 0";
		$query = "select NT11,NT12,NT13,NT21,NT22,NT23,NT31,NT32,NT33, level, Comparator, SeqNum from location_based_facts where NT11 like '" . $city_name . "' or NT13 like '" . $city_name . "' or NT21 like '" . $city_name . "' or NT23 like '" . $city_name . "' or NT31 like '". $city_name . "' or NT33 like '" . $city_name . "' and NT11 is not null LIMIT 1";
		$result = mysql_query($query) or die("Failed Query of " . mysql_error());  //do the query
        while ($row = mysql_fetch_array($result, MYSQL_NUM))
        {
	    $nt11 = '';
	    $nt12 = '';
	    $nt13 = '';
	    $nt21 = '';
	    $nt22 = '';
	    $nt23 = '';
	    $nt31 = '';
	    $nt32 = '';
	    $nt33 = '';
		$factnum = '';
            //printf("NT11 %s NT12 %s NT13 %s ", $row[0], $row[1], $row[2]);
            $nt11 = $row[0];
            $nt12 = $row[1];
            $nt13 = $row[2];
            $nt21 = $row[3];
            $nt22 = $row[4];
            $nt23 = $row[5];
            $nt31 = $row[6];
            $nt32 = $row[7];
            $nt33 = $row[8];
            $level = $row[9];
            $comparator = $row[10];
            $factnum = $row[11];
			//array_push($SeqNums,$factnum);
            $chaining = strcmp($comparator,"Chaining");
            $hasSameAsComparator = strcmp($comparator,"same");
            $GrammyAward = strcmp($comparator,"never");
			
			//var_dump($SeqNums);
            //echo $hasSameCurrency;
            //echo $comparator;
			//echo $nt11. " " . $nt12 . " " . $nt13;
            //echo $nt21. " " . $nt22 . " " . $nt23;
			//echo $nt31. " " . $nt32 . " " . $nt33;
        } // end of while results
		
		// Get all SeqNums of facts for this place and store in the array SeqNums for future use
		$query = "select SeqNum from location_based_facts where NT11 like '" . $city_name . "' or NT13 like '" . $city_name . "' or NT21 like '" . $city_name . "' or NT23 like '" . $city_name . "' or NT31 like '". $city_name . "' or NT33 like '" . $city_name . "' and NT11 is not null";
		$result = mysql_query($query) or die("Failed Query of " . mysql_error());  //do the query
        while ($row = mysql_fetch_array($result, MYSQL_NUM))
        {
			$factnum = $row[0];
			array_push($SeqNums,$factnum);
			//var_dump($SeqNums);
		} 
		} // End of if
		else
		{
			echo "In seqSet = 1";
			$query = "select NT11,NT12,NT13,NT21,NT22,NT23,NT31,NT32,NT33, level, Comparator, SeqNum from location_based_facts where SeqNum = ". $seqNum . ";";
		$result = mysql_query($query) or die("Failed Query of " . mysql_error());  //do the query
        while ($row = mysql_fetch_array($result, MYSQL_NUM))
        {
	    $nt11 = '';
	    $nt12 = '';
	    $nt13 = '';
	    $nt21 = '';
	    $nt22 = '';
	    $nt23 = '';
	    $nt31 = '';
	    $nt32 = '';
	    $nt33 = '';
		$factnum = '';
            //printf("NT11 %s NT12 %s NT13 %s ", $row[0], $row[1], $row[2]);
            $nt11 = $row[0];
            $nt12 = $row[1];
            $nt13 = $row[2];
            $nt21 = $row[3];
            $nt22 = $row[4];
            $nt23 = $row[5];
            $nt31 = $row[6];
            $nt32 = $row[7];
            $nt33 = $row[8];
            $level = $row[9];
            $comparator = $row[10];
            $factnum = $row[11];
			array_push($SeqNums,$factnum);
            $chaining = strcmp($comparator,"Chaining");
            $hasSameAsComparator = strcmp($comparator,"same");
            $GrammyAward = strcmp($comparator,"never");
			
			
            //echo $hasSameCurrency;
            //echo $comparator;
			//echo $nt11. " " . $nt12 . " " . $nt13;
            //echo $nt21. " " . $nt22 . " " . $nt23;
			//echo $nt31. " " . $nt32 . " " . $nt33;
			
			// Get all SeqNums of facts for this place and store in the array SeqNums for future use
		$query = "select SeqNum from location_based_facts where NT11 like '" . $city_name . "' or NT13 like '" . $city_name . "' or NT21 like '" . $city_name . "' or NT23 like '" . $city_name . "' or NT31 like '". $city_name . "' or NT33 like '" . $city_name . "' and NT11 is not null";
		$result = mysql_query($query) or die("Failed Query of " . mysql_error());  //do the query
        while ($row = mysql_fetch_array($result, MYSQL_NUM))
        {
			$factnum = $row[0];
			array_push($SeqNums,$factnum);
			//var_dump($SeqNums);
		} 
		//var_dump($SeqNums);
        } // end of while results
		}
		
        // Fetched results, Process the relation
        $str = $nt12;
        $str1 = $nt22;
        $str2 = $nt32;
		
		// Print array of Sequence Numbers of Facts for the current location
		//print_r($SeqNums);
        //echo "more wars happened? " . !(strcmp($nt12,"moreWarsHappenedIn"));
        //echo "is numeric" . is_numeric($comparator);
        /*echo $nt11 . "  " . $nt12 . "  ". $nt13;
        echo $nt21 . "  " . $nt22 . "  ".$nt23;
        echo $nt31 . "  " . $nt32 . "  ".$nt33;*/
        //$relationCurrency = $comparator;

// echo "fact num is " . ($factnum);
        if(empty($nt21) or empty($nt22) or empty($nt23))
        {
            $secondtriple = 0;
            $thirdtriple = 0;
            //echo $secondtriple;
        }
        else if(empty($nt31) or empty($nt32) or empty($nt33))
        {
            $secondtriple = 1;
            $thirdtriple = 0;
            //echo $secondtriple;
        }
        else
        {
            $thirdtriple = 1;
        }


        // Process the first argument to see if it contains _

        // If relation - $nt12 is hasAcademicAdvisor, replace it with hadAcademicAdvisor
        if(!strcmp($nt12,"hasAcademicAdvisor"))
        {
            $str = "hadAcademicAdvisor";
        }

        // For relation - warsHappenedIn
        if((!strcmp($nt12,"warsHappenedIn") or !strcmp($nt12,"moreActorsBornIn")) and !strcmp($comparator,"number"))
        {
            $warsHappenedIn = 1;
        }
        else
        {
            $warsHappenedIn = 0;
        }

	
        #
        $pattern = "/(.)([A-Z])/";
        $replacement = "\\1 \\2";
        $splitRelation = strtolower(preg_replace($pattern, $replacement, $str));
        $splitRelation1 = strtolower(preg_replace($pattern, $replacement, $str1));
        //$splitRelation2 = strtolower(preg_replace($pattern, $replacement, $str2));
        //$relationCurrencyCleaned = strtolower(preg_replace($pattern, $replacement, $relationCurrency));
        $neverGrammyAward = strtolower(preg_replace($pattern, $replacement, $comparator));
        $wasBornInRelation = strtolower(preg_replace($pattern, $replacement, $nt22));
        $moreWarsHappenedIn = strtolower(preg_replace($pattern, $replacement, $nt12));

        #
        //        For endedOnDate relation - Find if only year is present
        //        Check if the number of occorences of #
        if(!strcmp($nt12,"endedOnDate"))
        {
            // only for endedOnDate relation, check the number of # present in $nt13
            //echo "Hashes count = " .substr_count($nt13,'#');
            $hashCount = substr_count($nt13,'#');
            if($hashCount==4)
            {
                // Only year is present in date string, change endedOnDate relation
                $splitRelation = "ended in year";
                // Also change the string $nt13 to contain only the year
                //echo "the year is ". substr($nt13,0,4);
                $nt13 = substr($nt13,0,4);
            }
        }

  // get descriptions for persons if available
        if (strcmp($nt12,"description"))
	{
          $personQuery="select description from persons where name = '" . $nt11 . "' limit 1;";
          $personResult = mysql_query($personQuery) or die("Failed Query of " . mysql_error());  //do the query
	  $personAbout = "";
          while ($personRow = mysql_fetch_array($personResult, MYSQL_NUM))
          {
	      $personAbout = $personRow[0];
	  }
	  if (strlen($personAbout) > 4)
	  {
	     $nt11 = $nt11 . ", " . $personAbout . ",";
	  } 
	}


        // Replace \\u pattern with empty string in names - $nt11, $nt13, $nt21, $nt23, $nt31 and $nt33
        //$nt13 = preg_replace('\\u[0-9a-f][0-9a-f][0-9a-f][0-9a-f]', " ", $nt13);
        //$nt13 = preg_replace('//u\[0-9a-z\]\[0-9a-z\]\[0-9a-z\]\[0-9a-z\]', "", $nt13);
       // echo $nt13;
       // $nt11 = iconv("UTF-8", "ISO-8859-1", $nt11)

$patterns = array();
$patterns[0] = '/u0020/';
$patterns[1] = '/u0021/';
$patterns[2] = '/u0022/';
$patterns[3] = '/u0023/';
$patterns[4] = '/u0024/';
$patterns[5] = '/u0025/';
$patterns[6] = '/u0026/';
$patterns[7] = '/u0027/';
$patterns[8] = '/u0028/';
$patterns[9] = '/u0029/';
$patterns[10] = '/u002a/';
$patterns[11] = '/u002b/';
$patterns[12] = '/u002c/';
$patterns[13] = '/u002d/';
$patterns[14] = '/u002e/';
$patterns[15] = '/u002f/';
$replacements = array();
$replacements[0] = ' ';
$replacements[1] = '!';
$replacements[2] = '\"';
$replacements[3] = '#';
$replacements[4] = '$';
$replacements[5] = '%';
$replacements[6] = '&';
$replacements[7] = '\'';
$replacements[8] = '(';
$replacements[9] = ')';
$replacements[10] = '*';
$replacements[11] = '+';
$replacements[12] = ',';
$replacements[13] = '-';
$replacements[14] = '.';
$replacements[15] = '/';

$nt11 = preg_replace($patterns, $replacements, $nt11);
$nt13 = preg_replace($patterns, $replacements, $nt13);
$nt21 = preg_replace($patterns, $replacements, $nt21);
$nt23 = preg_replace($patterns, $replacements, $nt23);
$nt31 = preg_replace($patterns, $replacements, $nt31);
$nt33 = preg_replace($patterns, $replacements, $nt33);


         $nt11 = preg_replace('/u[0-9a-f][0-9a-f][0-9a-f][0-9a-f]/', " ", $nt11);
         $nt11 = str_replace('\\', "", $nt11);
         $nt13 = preg_replace('/u[0-9a-f][0-9a-f][0-9a-f][0-9a-f]/', " ", $nt13);
         $nt13 = str_replace('\\', "", $nt13);

         $nt21 = preg_replace('/u[0-9a-f][0-9a-f][0-9a-f][0-9a-f]/', " ", $nt21);
         $nt21 = str_replace('\\', "", $nt21);
         $nt23 = preg_replace('/u[0-9a-f][0-9a-f][0-9a-f][0-9a-f]/', " ", $nt23);
         $nt23 = str_replace('\\', "", $nt23);

         $nt31 = preg_replace('/u[0-9a-f][0-9a-f][0-9a-f][0-9a-f]/', " ", $nt31);
         $nt31 = str_replace('\\', "", $nt31);
         $nt33 = preg_replace('/u[0-9a-f][0-9a-f][0-9a-f][0-9a-f]/', " ", $nt33);
         $nt33 = str_replace('\\', "", $nt33);

// to handle #m in hasHeight in nt13
         $nt13 = preg_replace('/#m/', " m", $nt13);
// For relation hasHeight, round of $nt13
        if(!strcmp($nt12,"hasHeight"))
        {
            if(!is_numeric($nt13))
            {
                $nt13 = round($nt13,2);
                $nt13 = $nt13. " m";
                //echo $nt13;
            }
        }
        if(!strcmp($nt12,"hasLength"))
        {
            $nt13 = round($nt13,2);
            $nt13 = $nt13. " m";
            // echo $nt13;
        }
       // echo $nt11;
        //echo $splitRelation;
        //echo $nt12;
        //echo $nt22;
        //echo $nt32;
        //echo (strcmp($nt12,$nt22));
        //echo (strcmp($str1,$str2));

        //echo $nt32;
        // Find out whether same or different relations are chained
        if(empty($chaining))
        {
            if(strcmp($nt12,$nt22) and empty($nt32))
            {
                // Relations are different and only two triples are present
                $relationChainedDifferent = 1;
                //echo $relationChainedDifferent;
            }
            else if(!strcmp($nt12,$nt22))
            {
                // Relations are same
                $relationChainedDifferent = 0;
                //echo $relationChainedDifferent;
            }
        }

        // Get the quotes string to render
        $seqnumquote = rand(1,6);
        $queryquote="select Quote from quotes as t where t.SeqNum = " . $seqnumquote . ";";
        $resultquote = mysql_query($queryquote) or die("Failed Query of " . mysql_error());  //do the query
        while ($rowquote = mysql_fetch_array($resultquote, MYSQL_NUM))
        {
            $quotefornow = $rowquote[0];
        }

	// Don't use Wow! for low level facts

	if ((strpos($quotefornow,'Wow!') !== false) and ($level < 5))
	   $quotefornow = "I think";
	if ($level > 3)
	{
	  $endChar = "!";
	}
	else
	{
	  $endChar = ".";
	}

	if(!strcmp($nt12,"description") and strcmp($nt12,"actorsBornIn")) // and strcmp($comparator,"died")
        {
		$firstChar = substr($nt13,0,1);

		if (preg_match("/".$firstChar."/", "UAEIOaeiou"))
	        {
		  $vowelConn = " was an ";
	        }
		else
		{
		  $vowelConn = " was a ";
                }

            if ((strpos($quotefornow,'Did you know that') !== false) or
                (strpos($quotefornow,'Have you heard that') !== false))
            {
	//echo $comparator;
                echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt11) . $vowelConn . str_replace("_"," ",$nt13) . "?</h2>";
            }
            else
            {
                //echo $comparator;
                echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt11) . $vowelConn . str_replace("_"," ",$nt13) . $endChar . "</h2>";
            }
        }
	else if(!strcmp($nt12,"actorsBornIn")) 
        {
            if ((strpos($quotefornow,'Did you know that') !== false) or
                (strpos($quotefornow,'Have you heard that') !== false))
            {
                $nt12 = "actors were born in";
                echo "<h2>". $quotefornow . " " . $nt13." " .$nt12 . " " . str_replace("_"," ",$nt11) . "?</h2>";
            }
            else
            {
                $nt12 = "actors were born in";
                echo "<h2>". $quotefornow . " " . $nt13." " . $nt12 . " " . str_replace("_"," ",$nt11) .  $endChar . "</h2>";
            }
        }
        else if(empty($secondtriple)and empty($thirdtriple) and $GrammyAward and !$warsHappenedIn and !is_numeric($comparator))
        {
            // For Basic level Facts
	    if ((strpos($quotefornow,'Did you know that') !== false) or
                (strpos($quotefornow,'Have you heard that') !== false))
            {
            	echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt11). " " . $splitRelation . " " . $nt13 . "?</h2>";
	    }
	    else
            {
		echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt11). " " . $splitRelation . " " . $nt13 .  $endChar . "</h2>";
	    }
        }
        else if(empty($secondtriple)and empty($thirdtriple) and $GrammyAward and !$warsHappenedIn and (!strcmp($nt12,"moreWarsHappenedIn") or !strcmp($nt12,"moreActorsBornIn")) and is_numeric($comparator))
        {
		
            // Level 5 - moreWarsHappenedIn relation
            //echo "We are in rgt place";
	    if ((strpos($quotefornow,'Did you know that') !== false) or
                (strpos($quotefornow,'Have you heard that') !== false))
            {
            	echo "<h2>". $quotefornow . " " . round($comparator). " times" . " " . $moreWarsHappenedIn. " " . str_replace("_"," ",$nt11). " than " . str_replace("_"," ",$nt13) . "?</h2>";
            }
            else
	    {
		echo "<h2>". $quotefornow . " " . round($comparator). " times" . " " . $moreWarsHappenedIn. " " . str_replace("_"," ",$nt11). " than " . str_replace("_"," ",$nt13) .  $endChar . "</h2>";
            }
        }
        else if(empty($secondtriple)and empty($thirdtriple) and $GrammyAward and $warsHappenedIn)
        {
            // For warsHappenedIn relation, render as NT13 NT12 and NT11
            if ((strpos($quotefornow,'Did you know that') !== false) or
                (strpos($quotefornow,'Have you heard that') !== false))
            {
            	echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt13). " " . $splitRelation . " " . str_replace("_"," ",$nt11) . "?</h2>";
            }
            else
            {
 		echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt13). " " . $splitRelation . " " . str_replace("_"," ",$nt11) .  $endChar . "</h2>";
            }
        }
        else if(empty($chaining) and $hasSameAsComparator and $GrammyAward and $relationChainedDifferent)
        {
            //echo $relationChainedDifferent;
	    if ((strpos($quotefornow,'Did you know that') !== false) or
                (strpos($quotefornow,'Have you heard that') !== false))
	    {
            	echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt11). " who has won " . str_replace("_"," ",$nt13) . " " .$wasBornInRelation ." " . str_replace("_"," ",$nt23) . "?</h2>";
	    }
	    else
	    {
		echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt11). " who has won " . str_replace("_"," ",$nt13) . " " .$wasBornInRelation ." " . str_replace("_"," ",$nt23) .  $endChar . "</h2>";
            }
        }
        else if(empty($chaining) and $hasSameAsComparator and $GrammyAward and !($relationChainedDifferent))
        {
	    if ((strpos($quotefornow,'Did you know that') !== false) or
                (strpos($quotefornow,'Have you heard that') !== false))
	    {
            	//echo $relationChainedDifferent;
               echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt11). " " . $splitRelation . " " . str_replace("_"," ",$nt13) . " whose academic advisor was " . str_replace("_"," ",$nt23) . "?</h2>";
            	// For hasAcademicAdvisor relation Chaining 
            }
            else
            {
            	echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt11). " " . $splitRelation . " " . str_replace("_"," ",$nt13) . " whose academic advisor was " . str_replace("_"," ",$nt23) .  $endChar . "</h2>";
            }
        }
        else if(!$hasSameAsComparator)
        {
            // For hasCurrency - Countries having same Currency
	    if ((strpos($quotefornow,'Did you know that') !== false) or
                (strpos($quotefornow,'Have you heard that') !== false))
	    {
            echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt11). " , " . str_replace("_"," ",$nt21). " and " . str_replace("_"," ",$nt31). " have the " . $comparator . " " . strtolower(substr($str,-8,8)) ." ". str_replace("_"," ",$nt13) . "?</h2>";
            }
	    else
	    {
		echo "<h2>". $quotefornow . " " . str_replace("_"," ",$nt11). " , " . str_replace("_"," ",$nt21). " and " . str_replace("_"," ",$nt31). " have the " . $comparator . " " . strtolower(substr($str,-8,8)) ." ". str_replace("_"," ",$nt13) .  $endChar . "</h2>";
            }
        }
        else if(!$GrammyAward)
        {
            // For only grammy Lifetime Achievement Award and never Grammy Award
            // Split arg2 using explode
            $pieces = explode("_",$nt13);
            //echo $pieces[0];
            	echo "<h2>Hey, how come" . " " . str_replace("_"," ",$nt11). " won " . str_replace("_"," ",$nt13) . " but ". $neverGrammyAward ." a ". $pieces[0] . " " .$pieces[3] ."?</h2>";
        }
		else // For tourist_attraction_in
		{
			if ((strpos($quotefornow,'Did you know that') !== false) or
                (strpos($quotefornow,'Have you heard that') !== false))
				{
					if(!isset($comparator))
						echo "<h2>". $quotefornow . " " . str_replace("_"," ", $nt11). " " . str_replace("_"," ",$nt12) . str_replace("_"," ",$nt13) . "," . str_replace("_"," ",$nt21) . " " .str_replace("_"," ",$nt22) ." " . str_replace("_"," ",$nt23) . "," . str_replace("_"," ",$nt31) . " " .str_replace("_"," ",$nt32) ." " . str_replace("_"," ",$nt33) . "?";
					else
					    echo "<h2>". $quotefornow . " " . str_replace("_"," ", $nt11). " " . str_replace("_"," ",$nt12) . str_replace("_"," ",$nt13) . "," . str_replace("_"," ",$nt21) . " " .str_replace("_"," ",$nt22) . " " .str_replace("_"," ",$nt23) . "," . str_replace("_"," ",$nt31) . " " .str_replace("_"," ",$nt32) ." " . str_replace("_"," ",$nt33) .str_replace("_"," ",$comparator) . "?";
				}
				else
				{
					if(!isset($comparator))
						echo "<h2>". $quotefornow . " " . str_replace("_"," ", $nt11). " " . str_replace("_"," ",$nt12) . str_replace("_"," ",$nt13) . "," . str_replace("_"," ",$nt21) . " " .str_replace("_"," ",$nt22) . " " .str_replace("_"," ",$nt23) . "," . str_replace("_"," ",$nt31) . " " .str_replace("_"," ",$nt32) ." " . str_replace("_"," ",$nt33);
					else
					    echo "<h2>". $quotefornow . " " . str_replace("_"," ", $nt11). " " . str_replace("_"," ",$nt12) . str_replace("_"," ",$nt13) . "," . str_replace("_"," ",$nt21) . " " .str_replace("_"," ",$nt22) . " " .str_replace("_"," ",$nt23) . "," . str_replace("_"," ",$nt31) . " " .str_replace("_"," ",$nt32) ." " . str_replace("_"," ",$nt33) .str_replace("_"," ", $comparator);
				}
				
		}
        #echo $nt1. " " . $splitRelation . " " . $nt3;
        mysql_close($db_server);

echo "<br><h4> Interestingness level: " . ($level) . " (of 6)</h4>";
        ?>

        <p>&nbsp;</p>&nbsp;&nbsp; <input type="submit" name="NewFact" value="Get another fact!"/>
       
<p>&nbsp;</p>

<h3><strong><a href="http://www.kanoe.org">Center for Knowledge Analytics and Ontological Engineering,</a> <a href="http://pes.edu">PES Institute of Technology</a></strong></h3>
 </div>
        </li>
                        </ul>
                </div>
        </div>
        </div>
	<input type="hidden" name="dlevel" value="<?=$level?>"/>
	<input type="hidden" name="ipAddr" value="<?php print "$ipno";?>"/>
	<input type="hidden" name="SeqNums" value="<?php print base64_encode(serialize($SeqNums));?>"/>
        </form>
    </body>
</html>
