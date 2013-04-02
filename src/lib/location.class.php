<?php


/*################ location Class ################ 
 *     _______                   
 *    |__   __|                  
 *  _ __ | | ___  __ _ _ __ ___  
 * | '_ \| |/ _ \/ _` | '_ ` _ \ 
 * | |_) | |  __/ (_| | | | | | |
 * | .__/|_|\___|\__,_|_| |_| |_|
 * | |                           
 * |_| 
 * Title: location.class
 * Author: Beau Bouchard - beau@beaubouchard.com 
 * Description: 
   Used to store location information extracted from KML documents as objects in php
 	To be used with pteam program. --beaubouchard.com
 *
*/
include_once('./lib/sanidata_lib.php');
 
class location 
{ 
	private $id;
 	private $latitude;
	private $longitude;
	private $date;
	private $time;
	
	function __construct()
	{ 
		$this->id		= "(absent or missing)";
 		$this->latitude		= "(absent or missing)";
		$this->longitude	= "(absent or missing)";
		$this->date		= "(absent or missing)";
		$this->time		= "(absent or missing)";
	}

	// ####################### Setters, Mutators  ####################### 
	public function setid($inc_data)
	{
		$this->id = sanitizeData_hard($inc_data);
	}
	public function setlatitude($inc_data)
	{
		$this->latitude = sanitizeData_hard($inc_data);
	}
	public function setlongitude($inc_data)
	{
		$this->longitude = sanitizeData_hard($inc_data);
	}
	public function setdate($inc_data)
	{
		$this->date = sanitizeData_lite($inc_data);
	}
	public function settime($inc_data)
	{
		$this->time = sanitizeData_lite($inc_data);
	}
	
	// ####################### Accessors ####################### 
	public function getid()
	{
		return $this->id;
	}
	public function getlatitude()
	{
		return $this->latitude;
	}
	public function getlongitude()
	{
		return $this->longitude;
	}
	public function getdate()
	{
		return $this->date;
	}
	public function gettime()
	{
		return $this->time;
	}

public function toPyth()
{
	$toReturn = "<p>pts.append((".$this->latitude.",".$this->longitude."))</p>";
	return $toReturn;
}

	
}
?>
