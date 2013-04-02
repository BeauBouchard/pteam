<?php 
/**Geolocation extraction from exif image data
 *     _______                   
 *    |__   __|                  
 *  _ __ | | ___  __ _ _ __ ___  
 * | '_ \| |/ _ \/ _` | '_ ` _ \ 
 * | |_) | |  __/ (_| | | | | | |
 * | .__/|_|\___|\__,_|_| |_| |_|
 * | |                           
 * |_| 
 * Title: geoloc/index.php
 * Author: Beau Bouchard - beau@beaubouchard.com 
 * Description: This will extract all the exif data from //images/ directory and displays it, used to test scripts and verify uploads to the server
**/

header("Content-Type:text/xml");

/**
 * Title: exifToNumber($value, $format)
 * Description: Turns Exif data into numbers
**/
function exifToNumber($value, $format) {
  $spos = strpos($value, '/');
	if ($spos === false) {
		return sprintf($format, $value);
	} else {
		list($base,$divider) = split("/", $value, 2);
		if ($divider == 0) 
			return sprintf($format, 0);
		else
			return sprintf($format, ($base / $divider));
	}
}

/**
 * Title: exifToCoordinate($reference, $coordinate)
 * Description: turns the exif return value to rational degree numbers
**/
function exifToCoordinate($reference, $coordinate) {
	if ($reference == 'S' || $reference == 'W')
		$prefix = '-';
	else
		$prefix = '';
		
	return $prefix . sprintf('%.6F', exifToNumber($coordinate[0], '%.6F') +
		(((exifToNumber($coordinate[1], '%.6F') * 60) +	
		(exifToNumber($coordinate[2], '%.6F'))) / 3600));
}

/**
 * Title: getCoordinates($filename)
 * Description: Extracts the exif coords from a given file
**/
function getCoordinates($filename) {
	if (extension_loaded('exif')) {
		$exif = exif_read_data($filename, 'EXIF');
		
		if (isset($exif['GPSLatitudeRef']) && isset($exif['GPSLatitude']) && 
			isset($exif['GPSLongitudeRef']) && isset($exif['GPSLongitude'])) {
			return array (
				exifToCoordinate($exif['GPSLatitudeRef'], $exif['GPSLatitude']), 
				exifToCoordinate($exif['GPSLongitudeRef'], $exif['GPSLongitude'])
			);
		}
	}
}

/**
 * Title: coordinate2DMS($coordinate, $pos, $neg)
 * Description: output coords in degree mercator format
**/
function coordinate2DMS($coordinate, $pos, $neg) {
	$sign = $coordinate >= 0 ? $pos : $neg;
	
	$coordinate = abs($coordinate);
	$degree = intval($coordinate);
	$coordinate = ($coordinate - $degree) * 60;
	$minute = intval($coordinate);
	$second = ($coordinate - $minute) * 60;
	
	return sprintf("%s %d&#xB0; %02d&#x2032; %05.2f&#x2033;", $sign, $degree, $minute, $second);
}
/**
 * Title: getFilesFromDir($dir)
 * Description: gets the file listing from a given directory
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

echo "<kml xmlns='http://www.opengis.net/kml/2.2'>
";


	$dir = "images";
	$filelist = getFilesFromDir($dir);
	$count=1;
//For each file in the directory, Extract the Exif data
	foreach ($filelist as &$value)
	{
		if ($c = getCoordinates($value)) 
		{
			$latitude = $c[0];
			$longitude = $c[1];
			$exif = exif_read_data($value, 'EXIF');
			//echo "Latitude: ".$latitude." <br/>Longitude: ".$longitude."<br/>";
			
			$filename = $exif["FileName"];
			$temp_datetime = $exif["DateTimeOriginal"];
			$temp_when = split(" ", $temp_datetime);
			$date = str_replace( ":","-",$temp_when[0]);
			$time = $temp_when[1];
			echo "		<Placemark id='tx".$count."'>
			";
			echo "			<name>Name Image '".$filename."'	</name>
			";
						//echo "<description>Description</description>";
			echo "			<description><![CDATA[ <p>Image with Geolocation tag, <a href='http://www.beaubouchard.com/pteam/geoloc/images/".$filename."'>".$filename."</a></p>]]></description> 
			";
			print( "		<Point>
			");
			echo"				<coordinates>".$longitude.",".$latitude.",0</coordinates>
			";
			print( "		</Point>
			");
			print( "	</Placemark>
			");
		$count++;

		}
		else {}
	}
		echo "</kml>
		";

?>
