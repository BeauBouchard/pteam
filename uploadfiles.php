<?php
/*
 *     _______                   
 *    |__   __|                  
 *  _ __ | | ___  __ _ _ __ ___  
 * | '_ \| |/ _ \/ _` | '_ ` _ \ 
 * | |_) | |  __/ (_| | | | | | |
 * | .__/|_|\___|\__,_|_| |_| |_|
 * | |                           
 * |_|  
 * Title: uploadfiles.php
 *  Description: This is used to upload the Google Latitude Location KML file 
 * to parse and enter the location information into the database
 *
 */

	//Dependencies
	include_once('./lib/location.class.php');
	$pointlessdate = "";

/**
 * Title: parseKML($inc_filename = "")
 * Description: Parses the KML to get the Lat Long from each location in the document, and inserts them into the database
 * $inc_filename - Incoming filename, this is the file which will be parsed
 * 
**/
function parseKML($inc_filename = "")
{
	$location_array = array();
	//echo "Collecting Location information from file";
	if(file_exists($inc_filename))
	{
		//yay!
	}
	else
	{
		//Error 
	}	
	echo "<p>File Accepted... ".$inc_filename."</p>";
	//Starting to parse KML file
	$filedom =  simplexml_load_file($inc_filename);
	$count = 0;
		//Testing to see if the data exists in correct format
	//echo "<p>" . htmlentities( (string) $filedom->Document->name ) . "</p>";
	//echo "<p>" . htmlentities( (string) $filedom->Document->Placemark->name ) . "</p>";


	//Use that namespace
	$namespaces = $filedom->Document->Placemark->getNameSpaces(true);
	$gx = $filedom->Document->Placemark->children($namespaces['gx']); 

	$src = new DOMDocument('1.0', 'utf-8');
	$src->formatOutput = true;
	$src->preserveWhiteSpace = false;
	$src->load($inc_filename);
	$components = $src->getElementsByTagName('when');
	echo "<p>Starting Upload</p>";

	//Waste of my time	
	$latmax = 0;
	$latmin = -360;
	$longmax = 0;
	$longmin = 360;
	$buffer = 0.02;
	$link= mysql_connect('localhost', 'UsernameHere','PasswordHere') or die(mysql_error());
	mysql_select_db('DatabaseHere') or die(mysql_error());
	//echo "<p>Starting parseing...</p>";
	foreach( $gx->Track->coord as $acord )
	{
		$latlongcombo = htmlentities( (string) $acord );
			$temp_latlong = split(" ", $latlongcombo);

		 $lat = $temp_latlong[0]; 
		 $long = $temp_latlong[1];

		$when = (string)$components->item($count)->nodeValue;
					$temp_when = split("T", $when);
		$date = $temp_when[0];
					$temp_time = split("-",$temp_when[1]);
					$temp_time2 = split("\.",$temp_time[0]);
		$time = $temp_time2[0];
	$loc_obj = new location();
	$loc_obj->settime($time);
	$loc_obj->setdate($date);
	$pointlessdate = $date;
	$loc_obj->setlongitude($long);
	$loc_obj->setlatitude($lat);
	$sql = "INSERT INTO locations (`id`, `time`, `date`, `latitude`, `longitude` ,`tripid`) VALUES (NULL, '".$time."', '".$date."', '".$long."', '".$lat."',NULL)";
	
	$result = mysql_query($sql);

		$location_array[] = $loc_obj;
		$count ++;
	}
	mysql_close($link);
	return $location_array;
} 


/*
 * Title: errorCode()
 * Description: Used durring testing to output stuff :P delete
 *
 */
function errorCode($inc_errstr)
{
	echo "<p><b>ERROR! ".$inc_errstr."</b></p>";
}

/**
 * cleanFilename($inc_filename='')
 * Description: Reads and outputs a list of files and directories 
 * $inc_filename - Incoming filename, this will be the filename to cleanup 
 * and remove escape characters and abnormal file characters.
 * 
**/
function cleanFilename($inc_filename='')
{
	$SafeFile = $inc_filename; 
	$SafeFile = str_replace('#', 'No.', $SafeFile); 
	$SafeFile = str_replace('$', 'Dollar', $SafeFile); 
	$SafeFile = str_replace('%', 'Percent', $SafeFile); 
	$SafeFile = str_replace('^', '', $SafeFile); 
	$SafeFile = str_replace('&', 'and', $SafeFile); 
	$SafeFile = str_replace('*', '', $SafeFile); 
	$SafeFile = str_replace('?', '', $SafeFile); 

	$uploaddir = 'tmp/'; 
	$path = $uploaddir.$SafeFile;
	return $path;
}

/**
 * getFilesFromDir($dir)
 * Description: Reads and outputs a list of files and directories 
 * $dir - directory to read from,
 * 
**/
function getFilesFromDir($dir) { 

  $files = array(); 
  if ($handle = opendir($dir)) { 
    while (false !== ($file = readdir($handle))) { 
        if ($file != "." && $file != "..") { 
            if(is_dir($dir.'/'.$file)) { 
                $dir2 = $dir.'/'.$file; 
                $files[] = getFilesFromDir($dir2); 
            } 
            else { 
              $files[] = $dir.'/'.$file; 
            } 
        } 
    } 
    closedir($handle); 
  } 

  return $files; 
} 

// ------------------------Stuff to run at end of page load ----------------

	$locationList = array();
	$kmlList = array();
	//Read in a list of KML files
		
 $path=cleanFilename($_FILES['ufile']['name']);

if($ufile != none){ //AS LONG AS A FILE WAS SELECTED... 
    if(copy($_FILES['ufile']['tmp_name'], $path)){ //echo $path;//IF IT HAS BEEN COPIED... 
    } else { } 
} 
	
	//parseing the KML file to read the location data.
	$locationList[] = parseKML($path); // default

	echo "Done uploading";//
	$lol = "python test.py '170' '50' '".$pointlessdate."' 'derp' 'classic' '".$pointlessdate."'";
	echo $lol;
	//later this needs to go to a "pending" to be loaded page
	//$locationList[0]
	//map each location to a map,
echo "Done Generating Map";//

?>
