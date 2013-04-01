<?php
  /*
	 * Title: sanidata
	 * Description: Cleans your f*cking inputs
	 * Created: 10/3/10
	 * Author: Beau Bouchard
	 * Last updated: 1/15/2011
	 */
	 
	/*
	 * Title: sanitizeData_hard
	 * Description: Used to sanitize data entry before being inserted into a database
	 */
	function sanitizeData_hard($var)
	{
		$var = trim($var); // gets rid of white space
		$var = stripslashes($var); // no slashes protecting stuff
		$var = htmlentities($var); // no html :-(
		$var = strip_tags($var); // no tags
		return $var; //returns clean data
	}
	
	/*
	 * Title: sanitizeData_lite
	 * Description: Used to sanitize data entry before being inserted into a database
	 * This is a lighter version that will keep HTML
	 */
	function sanitizeData_lite($var)
	{
		$var = trim($var); // gets rid of white space
		$var = stripslashes($var); // no slashes protecting stuff
		return $var; //returns clean data
	} 
	
	/*
	 * Title: sanitizeData_percent
	 * Description: Used to sanitize data entry before being inserted into a database
	 * This will also replace spaces with a "%20" value
	 * Note: it there is any HTML it will be stripped out :-(
	 */
	function sanitizeData_percent($var)
	{
		$var = trim($var); // gets rid of white space
		$var = stripslashes($var); // no slashes protecting stuff
		$var = htmlentities($var); // no html :-(
		$var = strip_tags($var); // no tags
		$var = str_replace(" ","%20",$var);
		return $var; //returns clean data
	} 
	
	/*
	 * Title: fixPercent
	 * Description: replaces the "%20" value with a space again
	 */
	function fixPercent($var)
	{
		$var = str_replace("%20"," ",$var);
		return $var;
		
	}
