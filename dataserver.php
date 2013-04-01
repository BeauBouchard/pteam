<?php
header('Access-Control-Allow-Origin: http://beaubouchard.com');
header ("Content-Type:text/xml");  

/**
 *     _______                   
 *    |__   __|                  
 *  _ __ | | ___  __ _ _ __ ___  
 * | '_ \| |/ _ \/ _` | '_ ` _ \ 
 * | |_) | |  __/ (_| | | | | | |
 * | .__/|_|\___|\__,_|_| |_| |_|
 * | |                           
 * |_| 
 * Author: Beau Bouchard (Beaubouchard.com) @beaubouchard
 * Filename: dataserver.php
 * Description: used for the pteam app, beaubouchard.com/pteam/
 * dataserver is used to relay data and computes CO2 emissions to the front end, in XML format
 * Database and passwords should be changed
 **/

print ("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n");

//Dependencies
//include_once('/pteam/lib/location.class.php');
include_once('lib/location.class.php');
function retrieveTripInfo($inc_tripID)
{
  
	$link= mysql_connect('localhost', 'UsernameHere','Passwordhere') or die(mysql_error());
	
	mysql_select_db('DatabaseHere') or die(mysql_error());
	echo "<trip>";
	
	$tripinfoquery = sprintf("SELECT tripname, tripmpg FROM trip WHERE tripid = %s",mysql_real_escape_string($inc_tripID));
	$tripinforesults = mysql_query($tripinfoquery);
	while ($row = mysql_fetch_assoc($tripinforesults)) {
		echo "<tripname>".$row['tripname']."</tripname>";
		echo "<tripmpg>".$row['tripmpg']."</tripmpg>";
	}
	
	
	$filenamequery = sprintf("SELECT filename FROM tripfiles WHERE tripid = %s",mysql_real_escape_string($inc_tripID));
	$filenameresults = mysql_query($filenamequery);
	while ($row = mysql_fetch_assoc($filenameresults)) {
		echo "<filename>".$row['filename']."</filename>";
	}
	
	mysql_free_result($tripinforesults);
	mysql_free_result($filenameresults);
	
	mysql_close($link);
	echo "</trip>";
}


function listtrips()
{
	echo"<list>";
	$link= mysql_connect('localhost', 'UsernameHere','Passwordhere') or die(mysql_error());
	
	mysql_select_db('DatabaseHere') or die(mysql_error());
	
	$tripinfoquery = "SELECT * FROM trip";
	
	$tripinforesults = mysql_query($tripinfoquery);
	while ($row = mysql_fetch_assoc($tripinforesults)) {
		echo "<trip>";
		echo "<tripid>".$row['tripid']."</tripid>";
		echo "<tripname>".$row['tripname']."</tripname>";
		echo "<tripmpg>".$row['tripmpg']."</tripmpg>";
		echo "</trip>";
	}
	
	
	mysql_free_result($tripinforesults);
	mysql_close($link);
	echo"</list>";
}


function getMPG($inc_tripID)
{
	
	$link= mysql_connect('localhost', 'UsernameHere','Passwordhere') or die(mysql_error());
	
	mysql_select_db('DatabaseHere') or die(mysql_error());
	
	$tripinfoquery = sprintf("SELECT tripmpg FROM trip WHERE tripid = %s",mysql_real_escape_string($inc_tripID));
	
	$tripinforesults = mysql_query($tripinfoquery);
	$r_mpg = 0;
	while ($row = mysql_fetch_assoc($tripinforesults)) {
		$r_mpg = $row['tripmpg'];
	}
	
	
	mysql_free_result($tripinforesults);
	mysql_close($link);
	
return $r_mpg;
}

//http://www.carbonfund.org/site/pages/carbon_calculators/category/Assumptions
//The average fuel economy for cars sold in 2005 is about 25.2 MPG 
function calcFuelUsed($inc_mpg=25.2, $inc_miles=0)
{
	$gallons_Spent 	= 0;
	$gallons_Spent	= round($inc_miles/$inc_mpg, 2);
	return $gallons_Spent;
}

//http://micpohling.wordpress.com/2007/05/08/math-how-much-co2-released-by-aeroplane/
//There are different fuel rates for different planes
// Type 1 =  747 - 400  					30.638 kg/km 
// Type 2 = Airbus A320 				   	9.149 kg/km
// Type 3 = Bombardier Learjet 45 Super-Light Business Jet. 	1.766 kg/km
function calcPlaneCO2($inc_km=0,$inc_type=0)
{
	$CO_waste = 0;

	if($inc_type==1) // 747
	{
		$CO_waste = ($inc_km * 30.638)/400;	
	}
	else if($inc_type==2)
	{
		$CO_waste = ($inc_km * 9.149)/84; 
	}
	else if($inc_type==3)
	{
		$CO_waste = $inc_km * 1.766;
	}

	return $CO_waste;
}

//http://www.eia.gov/oiaf/1605/coefficients.html
//Unleaded gasoline has 8.91 kg (19.643lbs) of CO2 per gallon
function calcCarCO2($inc_gallons=0,$inc_type=0)
{
	$pounds 	= 19.643;
	$kilograms 	= 8.91;
	$CO_waste 	= 0;
	if($inc_type==0)	//miles (default)
	{
		$CO_waste = $inc_gallons * $pounds;
	}
	else if($inc_type==1) //kilograms
	{
		$CO_waste = $inc_gallons * $kilograms;
	}
	return $CO_waste;
}

function haversineDistance($inc_point1, $inc_point2, $inc_type =0)
{
	$haversineOutput = 0;
	$point1 = new location();
	$point1 =  $inc_point1;
	
	$point2 = new location();
	$point2 = $inc_point2;
	
	 $lat1= $point1->getlongitude();//getlongitude()
	 $lat2= $point2->getlongitude();
	$long1 = $point1->getlatitude();//getlatitude()
	$long2 = $point2->getlatitude();

	$longDifference = $long1 - $long2;
	$latDifference = $lat1 - $lat2;

	/*$distance =acos((
			sin(deg2rad($lat1)) * 
			sin(deg2rad($lat2)) +  
			cos(deg2rad($lat1)) * 
			cos(deg2rad($lat2)) * 
			cos(deg2rad($longDifference))
			));*/
  $distance = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($longDifference)); 
 $distance = acos($distance); 
  $distance = rad2deg($distance);  
		

	switch ($inc_type)
	{
		case 0: //miles
			$haversineOutput = $distance * 60 * 1.1515;
		break;
		case 1: //kilometers
			$haversineOutput = $distance * 60 * 1.1515 * 1.609344;
		break;
		default: //miles
			$haversineOutput = $distance * 60 * 1.1515;
		break;
	}
	return $haversineOutput;
}

function calcdistance($inc_tripID,$inc_type=0)
{
	$total_distance = 0;
	$link= mysql_connect('localhost', 'freeigno_pteam','pteam7') or die(mysql_error());
	
	mysql_select_db('freeigno_pteamdb') or die(mysql_error());
	//echo "<trip>";
	
	$locationquery = sprintf("SELECT * FROM locations WHERE tripid = %s",mysql_real_escape_string($inc_tripID));
	$locationresults = mysql_query($locationquery);
	
	$old_loc_obj = new location();
	$count = 0;
	while ($row = mysql_fetch_assoc($locationresults)) {
		//echo $count;
		if($count==0)
		{
			//echo "First";
			$old_loc_obj->setid($row['id']);
			$old_loc_obj->settime($row['time']);
			$old_loc_obj->setdate($row['date']);
			$old_loc_obj->setlatitude($row['latitude']);
			$old_loc_obj->setlongitude($row['longitude']);
		}
		else
		{
			if(($old_loc_obj->getlatitude() == $row['latitude'])&&($old_loc_obj->getlongitude() == $row['longitude']))
			{
				//echo "skip";
			}
			else
			{
				$loc_obj = new location();
				$loc_obj->setid($row['id']);
				$loc_obj->settime($row['time']);
				$loc_obj->setdate($row['date']);
				$loc_obj->setlatitude($row['latitude']);
				$loc_obj->setlongitude($row['longitude']);
			
				$total_distance = $total_distance +haversineDistance($old_loc_obj, $loc_obj, $inc_type);
				//echo "<p>".$row['id'] ." :: ".$total_distance."</p>";
			
				$old_loc_obj = new location();
				$old_loc_obj->setid($row['id']);
				$old_loc_obj->settime($row['time']);
				$old_loc_obj->setdate($row['date']);
				$old_loc_obj->setlatitude($row['latitude']);
				$old_loc_obj->setlongitude($row['longitude']);
			}
		}
		$count++;
	}
	return $total_distance;
}

function calcCost($inc_gallons)
{
	$fuelCost = 3.604;
	return $inc_gallons*$fuelCost;
}

if(isset($_GET['tripinfo']))
{
 
	retrieveTripInfo($_GET['tripinfo']);
}
else if(isset($_GET['listtrips']))
{
	listtrips();	
}
else if(isset($_GET['calcdistance']))
{

	$distance_miles 	= calcDistance($_GET['calcdistance']);
	$distance_kilometers 	= calcDistance($_GET['calcdistance'],1);
	
	echo "	<distance_miles>".$distance_miles."</distance_miles>";
	echo "	<distance_kilometers>".$distance_kilometers."</distance_kilometers>";
}
else if(isset($_GET['calcall']))
{
	$distance_miles 	= calcDistance($_GET['calcall']);
	$distance_kilometers 	= calcDistance($_GET['calcall'],1);
	$MPG 			= getMPG($_GET['calcall']);
	$gallons_spent 		= calcFuelUsed($MPG,$distance_miles);//must be miles
	$CO2LB			= calcCarCO2($gallons_spent,0);
	$CO2KG			= calcCarCO2($gallons_spent,1);
	$cost			= calcCost($gallons_spent);
	$CO2747			= calcPlaneCO2($distance_kilometers,1);
	$CO2A320		= calcPlaneCO2($distance_kilometers,2);
	$CO2PRIV		= calcPlaneCO2($distance_kilometers,3);

// Type 1 =  747 - 400  					30.638 kg/km 
// Type 2 = Airbus A320 				   	9.149 kg/km
// Type 3 = Bombardier Learjet 45 Super-Light Business Jet. 	1.766 kg/km
	echo "<trip>";
	echo "<info>";
	echo "	<distance_miles>".$distance_miles."</distance_miles>";
	echo "	<distance_kilometers>".$distance_kilometers."</distance_kilometers>";
	echo "	<MPG>".$MPG ."</MPG>";
	echo "	<gallons_spent>".$gallons_spent ."</gallons_spent>";
	echo "	<CO2LB>".$CO2LB ."</CO2LB>";
	echo "	<CO2KG>".$CO2KG ."</CO2KG>";
	echo "	<CO2747>".$CO2747."</CO2747>";
	echo "	<CO2A320>".$CO2A320."</CO2A320>";
	echo "	<CO2PRIV>".$CO2PRIV."</CO2PRIV>";
	echo "		<fuelCost>".$cost."</fuelCost>";
	echo "</info>";	
	echo "</trip>";
}
?>
