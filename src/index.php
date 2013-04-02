<?php 
include_once('./lib/pteam_lib.php');
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
 * Description: Main page for viewing output of pteam. 
**/
?>
<!DOCTYPE html>
<head>
<meta name='description' content='Beaubouchard.com serves to showcase projects and word for IT Grad student Beau Bouchard of RIT' />
<meta name='keywords' content='Porfolio, Beau, Bouchard, Beau Bouchard, Database, Tutorial, PHP, Javascript' />
<meta name='author' content='Beau Bouchard' />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title> Beau Bouchard - pTeam </title>
<link rel='stylesheet' type='text/css' href='/common/style.css' />
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
//#####################SCRIPT######################################
var map;
var overlayArray = [];
 

function drawChart(inc_mycar,inc_747,inc_320,inc_priv) 
{

  var data = new google.visualization.DataTable();
	data.addColumn('string', 'Your Trip');
	data.addColumn('number', 'Your Car');
	data.addColumn('number', 'Boeing 747 w 400 passangers');
	data.addColumn('number', 'Airbus A320 w 87 passangers ');
	data.addColumn('number', 'Private Jet w 1 passanger');
	data.addRows([
	  ['KG of CO2', parseFloat(inc_mycar),parseFloat(inc_747),parseFloat(inc_320),parseFloat(inc_priv)]
	]);

	var options = {
	  width: 400, height: 240,
	  title: 'KG of CO2 used by Vehicle',
	  hAxis: {title: 'KG of CO2', titleTextStyle: {color: 'red'}}
	};
	document.getElementById('chart_div').style.visibility='visible'; 
	var dropchart = document.getElementById('chart_div');
	var chart = new google.visualization.ColumnChart(dropchart);
	chart.draw(data, options);
}

function loadstats(inc_tripID)
{
	var gl_CO2KG ;
	var gl_747 ; 
	var gl_320 ; 
	var gl_priv ;
	document.getElementById('statsdroppoint').innerHTML = "";
	document.getElementById('statsdrop').style.visibility='visible'; 
	var newtext1 = document.createTextNode(" ");
				//button1.appendChild(newtext2);
	var h2 = document.createElement('h2');
		h2.appendChild(document.createTextNode("Trip Statistics"));
	var p1 = document.createElement('p');
	var p2 = document.createElement('p');
	var p3 = document.createElement('p');
	var p4 = document.createElement('p');
	var p5 = document.createElement('p');
	var p6 = document.createElement('p');
	//<div id="chart_div"></div>
	//var chartdiv = document.createElement('div');
	//	chartdiv.setAttribute('id','chart_div');
	
	var trande = "";
	var xmlhttp;
	if (window.XMLHttpRequest)
	{	
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}else{
		//code for IE5,6
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			xmlDoc=xmlhttp.responseXML;
			var list_CO2KG			= xmlDoc.getElementsByTagName("CO2KG");
			var list_747			= xmlDoc.getElementsByTagName("CO2747");
			var list_320			= xmlDoc.getElementsByTagName("CO2A320");
			var list_priv			= xmlDoc.getElementsByTagName("CO2PRIV");
			gl_CO2KG 			= list_CO2KG[0].childNodes[0].nodeValue;
			gl_747 				= list_747[0].childNodes[0].nodeValue; 
			gl_320 				= list_320[0].childNodes[0].nodeValue; 
			gl_priv 			= list_priv[0].childNodes[0].nodeValue; 
			
			var list_distance_miles 	= xmlDoc.getElementsByTagName("distance_miles");
			var list_distance_kilometers	= xmlDoc.getElementsByTagName("distance_kilometers");
			var list_MPG			= xmlDoc.getElementsByTagName("MPG");
			var list_gallons_spent		= xmlDoc.getElementsByTagName("gallons_spent");
			var list_CO2LB			= xmlDoc.getElementsByTagName("CO2LB");

			
			p1.appendChild(document.createTextNode("Distance (Miles): "+list_distance_miles[0].childNodes[0].nodeValue));
			p2.appendChild(document.createTextNode("Distance (Kilometers): "+list_distance_kilometers[0].childNodes[0].nodeValue));
			p3.appendChild(document.createTextNode("Trip MPG: "+list_MPG[0].childNodes[0].nodeValue));
			p4.appendChild(document.createTextNode("Gallons of fuel Used: "+list_gallons_spent[0].childNodes[0].nodeValue));
			p5.appendChild(document.createTextNode("Lbs of CO2: "+list_CO2LB[0].childNodes[0].nodeValue));
			p6.appendChild(document.createTextNode("Kgs of CO2: "+list_CO2KG[0].childNodes[0].nodeValue));
		
			
			drawChart(gl_CO2KG,gl_747,gl_320,gl_priv);
				//trande =  trande+ "<p><strong>Distance (Miles): </strong>" +list_distance_miles[0].childNodes[0].nodeValue + "</p>";
				//trande =  trande+ "<p><strong>Distance (Kilometers): </strong>" +list_distance_kilometers[0].childNodes[0].nodeValue+ "</p>";
				//trande =  trande+ "<p><strong>Trip MPG: </strong>" +list_MPG[0].childNodes[0].nodeValue+ "</p>";
				//trande =  trande+ "<p><strong>Gallons of fuel Used: </strong>" +list_gallons_spent[0].childNodes[0].nodeValue+ "</p>";
				//trande =  trande+ "<p><strong>Lbs of CO2: </strong>" +list_CO2LB[0].childNodes[0].nodeValue+ "</p>";
				//trande =  trande+ "<p><strong>Kgs of CO2: </strong>" +list_CO2KG[0].childNodes[0].nodeValue+ "</p>";

			
			//newtext1.nodeValue = trande;
		}
		
	}
	xmlhttp.open("GET","http://www.beaubouchard.com/pteam/dataserver.php?calcall="+inc_tripID,true);
	xmlhttp.send();
	
	//alert(tobeadded);
	//document.getElementById('statsdroppoint').innerHTML = trande;
	document.getElementById('statsdroppoint').appendChild(h2);
	document.getElementById('statsdroppoint').appendChild(p1);
	document.getElementById('statsdroppoint').appendChild(p2);
	document.getElementById('statsdroppoint').appendChild(p3);
	document.getElementById('statsdroppoint').appendChild(p4);
	document.getElementById('statsdroppoint').appendChild(p5);
	document.getElementById('statsdroppoint').appendChild(p6);//statsdroppoint
//	document.getElementById('statsdroppoint').appendChild(chartdiv);
	//var t = setTimeout("",1000);//;

}


function loadaTrip()
{
/*	echo "<select id='mapselect' name='mapselect'>";
	foreach ($files as &$value)
	{
		echo "<option value='".$value."'>".$value."</option>";
	}	
	echo "</select>";*/
	
	document.getElementById('heatselect').innerHTML = "";
	document.getElementById('statsdroppoint').innerHTML = "";
	
	var b = document.createElement('b');
		b.appendChild(document.createTextNode("Select a Heatmap"));
	var p8 = document.createElement('p');
		p8.setAttribute('class', 'makecenter');
		
		p8.appendChild(b);
	
	
	var temp_tag = document.getElementById("tripselecter");
	var temp_tripID = temp_tag.options[temp_tag.selectedIndex].value;
	
	
	if(temp_tripID == "5")
	{
		
	}
	else
	{
		loadstats(temp_tripID);
	}
	
	var select1 = document.createElement('select');
		select1.setAttribute('id', 'mapselect');
		select1.setAttribute('name', 'mapselect');
	
	var button1 = document.createElement('button');
				button1.setAttribute('type', 'button');
				button1.setAttribute('onClick', 'loadLayer()');
	var button7 = document.createElement('button');
				button7.setAttribute('type', 'button');
				button7.setAttribute('onClick', 'clearOverlays()');
				button7.appendChild(document.createTextNode("Clear KML Overlay"));
	var newtext2 = document.createTextNode("Load KML Overlay");
				button1.appendChild(newtext2);
				
				var pp = document.createElement('p');
		pp.appendChild(button1);
		pp.appendChild(button7);
				
	var xmlhttp;
	if (window.XMLHttpRequest)
  	{	
  		// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
	}else{
		//code for IE5,6
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			//http://www.beaubouchard.com/pteam/dataserver.php?tripinfo=1
			//http://www.beaubouchard.com/pteam/dataserver.php?calcall=1
			xmlDoc=xmlhttp.responseXML;
			x=xmlDoc.getElementsByTagName("filename");
			//y=xmlDoc.getElementsByTagName("filename");
			for (i=0;i<x.length;i++)
			{
				//tobeadded = tobeadded +
				var option = document.createElement('option'); 
					option.setAttribute('value',x[i].childNodes[0].nodeValue );
				var newtext = document.createTextNode(x[i].childNodes[0].nodeValue);
					option.appendChild(newtext);
					select1.appendChild(option);
	
			}
			//xmlhttp.responseText
			
		}
	}
	xmlhttp.open("GET","http://www.beaubouchard.com/pteam/dataserver.php?tripinfo="+temp_tripID,true);
	xmlhttp.send();

	//alert(tobeadded);
	document.getElementById("heatselect").appendChild(p8);
	document.getElementById("heatselect").appendChild(select1);
	document.getElementById("heatselect").appendChild(pp);
	
}

function loadtrips()
{
	var select1 = document.createElement('select');
		select1.setAttribute('id', 'tripselecter');
		select1.setAttribute('name', 'tripselecter');
	var button1 = document.createElement('button');
				button1.setAttribute('type', 'button');
				button1.setAttribute('onClick', 'loadaTrip()');
	var newtext2 = document.createTextNode("Load KML Overlay");
				button1.appendChild(newtext2);
	var xmlhttp;
	if (window.XMLHttpRequest)
  	{	
  		// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
	}else{
		//code for IE5,6
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			//http://www.beaubouchard.com/pteam/dataserver.php?tripinfo=1
			//http://www.beaubouchard.com/pteam/dataserver.php?calcall=1
			xmlDoc=xmlhttp.responseXML;
			x=xmlDoc.getElementsByTagName("tripname");
			y=xmlDoc.getElementsByTagName("tripid");
			for (i=0;i<x.length;i++)
			{
				//tobeadded = tobeadded +
				var option = document.createElement('option'); 
					option.setAttribute('value',y[i].childNodes[0].nodeValue );
				var newtext = document.createTextNode(x[i].childNodes[0].nodeValue);
					option.appendChild(newtext);
					select1.appendChild(option);
	
			}
			//xmlhttp.responseText
			
		}
	}
	xmlhttp.open("GET","http://www.beaubouchard.com/pteam/dataserver.php?listtrips=1",true);
	xmlhttp.send();

	//alert(tobeadded);
	document.getElementById("tripselect").appendChild(select1);
	document.getElementById("tripselect").appendChild(button1);
	
}

function initialize(){
	var Rochester = new google.maps.LatLng(43.1561, -77.607);
	var myOptions = {
		zoom: 11,
		center: Rochester,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}

	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	
	
<?php 
//++++++++++++++++++++++PHP+++++++++++++++++++++++++++++++++
	if(isset($_GET['mapname']))//http://www.beaubouchard.com/pteam/index.php?mapname=2011-10-07-classic.kml&tripID=2
	{
		echo "var overlay = new google.maps.KmlLayer('http://www.beaubouchard.com/pteam/mapfiles/".$_GET['mapname']."');";
		echo "overlay.setMap(map);";
		echo "overlayArray.push(overlay);";
		if(isset($_GET['tripID']))
		{
			echo "loadtrips();";
			echo "loadstats(".$_GET['tripID'].");";
			
		}
	}
	else if(isset($_GET['tripID']))
	{

		echo "loadstats(".$_GET['tripID'].");";
	}
	else
	{
		echo "loadtrips();";
	}
//++++++++++++++++++++++PHP+++++++++++++++++++++++++++++++++
?>
	
}
function loadLayer()
{
	var temp_tag = document.getElementById("mapselect");
	var mapname = temp_tag.options[temp_tag.selectedIndex].value;
	var overlay = new google.maps.KmlLayer("http://www.beaubouchard.com"+mapname);
	overlay.setMap(map);
	overlayArray.push(overlay);
}
function addLayer(inc_filename)
{
	var mapname = inc_filename;
	var overlay = new google.maps.KmlLayer(mapname);
	overlay.setMap(map);
	overlayArray.push(overlay);
}

function addHM()
{
	var temp_tag = document.getElementById("mapselect");
	var mapname = temp_tag.options[temp_tag.selectedIndex].value;
  	var overlay = new google.maps.KmlLayer('http://www.beaubouchard.com/pteam/'+mapname);
	overlay.setMap(map);
	overlayArray.push(overlay);
}

function clearOverlays() 
{
	if (overlayArray) 
	{
		for (i in overlayArray) 
		{
			overlayArray[i].setMap(null);
		}
	}
}


//#####################SCRIPT######################################
</script>
</head>
<body onload="initialize()">
	<div id='wrapper'>
		<div id='header'>
			<div id='banner'>
				<h1><span class='accent'>B</span>eau <span class='accent'>B</span>ouchard</h1>
				<h2>pTeam</h2>
			</div>	
<ul><li><a href='/index.php' >Home</a></li><li>::</li><li><a href='/work/' >Work</a></li><li>::</li><li class='selected'><a href='/blog/' >Blog</a></li><li>::</li><li><a href='/about/index.php?subject=pteam' >About</a></li></ul>
					

		</div>
		
		<div id='main'>
			<div id='start'>
				</br>
				
				<h1>Personal Travel Emission Analysis Map</h1>
				<div class='blogpost'>
					<p></p>
					<div class='box'>
						<div style='width:100%;height:100%;min-height:500px' id="map_canvas"></div>
					</div><p></p>
					<div class='box' style="min-height:100px">
					
						<div style="text-align:center;float:left;">
					
							<div class='makecenter'>
					
								<p><b>Select Existing Trip</b></p>
								<p>
								<div id="tripselect">
		
					
								</div> 
								</p>
							</div>
						</div>
						
						<div style="text-align:center;float:right;">
							<div class='makecenter' id="heatselect">
								
			<?php
			//Switching the map to use a AJAX call to populate select drop downs

			//++++++++++++++++++++++PHP+++++++++++++++++++++++++++++++++
			//makeKMLSelect("mapfiles");
			//getmytrips();
			//++++++++++++++++++++++PHP+++++++++++++++++++++++++++++++++
			?>
								
							</div>
						</div>	
					</div>
					<p></p>

					<div  class='box' style="min-height:240px" id='statsdrop'>
							<div style="width:400px; float:left;" id='statsdroppoint'>
								
							</div>
							<div style="min-height:240px ; width:400px;float:right;" id="chart_div"></div>
							
					</div>
					<p></p>
					

					
				</div>
			</div>
		</div>
		<div id='footer'>
			<ul>
				<li><a href='#'>Privacy Policy</a></li>
				<li><a href='#'>Terms of Use</a></li>
				<li><a href='mailto:beau@beaubouchard.com'>Contact Me</a></li>
			</ul>
		</div>
	</div>
	
	<script type="text/javascript">
document.getElementById('statsdrop').style.visibility='hidden'; 

</script>
</body>
</html>

