pteam
=====

Personal Travel Emission Analysis Map -- This web application serves to calculate CO2 emissions generated during a period of time

Project In-depth Description
---
In preparation for working on this project I tracked my location information using the Google Latitude application over the course of several months. I also collected a separate dataset which consists of the EXIF geotag data from pictures I took during this time.

The program first extracts the data and inserts it into a MySQL database which is then read by a series of python scripts. The most notable being gheat.py an open source python library which creates heatmaps based on the latitude and longitude data. Higher concentrations of GPS points in an area would show up brighter on the heatmap and demonstrate a longer amount of time spent in that location (assuming locations are taken at steady intervals).

I set my fuel usage at 28MPG which is the average MPG my Ford Taurus car receives highway. My fuel consumption is compared to three types of airplane fuel consumption.

 * Boeing 747 fully loaded with 400 passengers
 * Airbus A320 fully loaded with 87 passengers
 * Private Jet with crew and 1 passanger


How to Use
---
 * [Currently I need to upload the database creation scripts] 
 * Use uploadfiles.php to upload your Google Latitude location KML file. 
 * Verify the file is uploaded by going to ./geoloc/index.php
   When the file is uploaded, it was also inserted into the database as a "trip", and all the location points are apart of the trip
   Using crude euclinian distance, the trip distance is calcuclated between the geolocations
 * Once file is uploaded into /tmp/ directory, run heatmapGenerate.py to create a heatmap in the /mapfiles/ directory
 * View the result with index.php

