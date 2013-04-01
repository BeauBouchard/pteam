import MySQLdb as mdb
import sys
import heatmap
import random
#      _______                   
#     |__   __|                  
#   _ __ | | ___  __ _ _ __ ___  
#  | '_ \| |/ _ \/ _` | '_ ` _ \ 
#  | |_) | |  __/ (_| | | | | | |
#  | .__/|_|\___|\__,_|_| |_| |_|
#  | |                           
#  |_| 
# Filename: testimg.py
###################
# Generates a map based on the image exif data in the database 
#invoke using the following command "python testimg.py" to use default or supply as many values as u need
##############
#  python testimg.py 
#		dotOpacity
#		dotSize
#		fileName
#		size 		- Image size, expecting tuplet, leave null
#		scheme 		- Color scheme of images ['classic','fire','omg','pbj']
#		where clause 	- 
#

#python testimg.py 170 100 "image-map" "NULL" "classic" "NULL"
if (len(sys.argv) >= 2):
	var_dotOpacity 	= int(sys.argv[1]) # 0 - 255
	if (len(sys.argv) >= 3):
		var_dotSize	= int(sys.argv[2])
		if (len(sys.argv) >= 4):
			var_fileName	= sys.argv[3]
			if (len(sys.argv) >= 5):
				var_size	= (2048,2048) #sys.argv[5]
				if (len(sys.argv) >= 6):
					var_scheme	= sys.argv[5]
					if (len(sys.argv) >= 7):
						var_clause	= sys.argv[6]
				else:
					var_scheme	='classic'
			else:
				var_size	= (2048,2048)
				var_scheme	='classic'
		else:
			var_fileName 	= 'test'
			var_size	= (2048,2048)
			var_scheme	='classic'
	else:
		var_dotSize	= 100 # 2 - 320
		var_fileName 	= 'test'
		var_size	= (2048,2048)
		var_scheme	='classic'
else:
	var_dotOpacity 	= 170 # 0 - 255
	var_dotSize	= 100 # 2 - 320
	var_fileName 	= 'test'
	var_size	= (2048,2048)
	var_scheme	='classic'
#FILENAME		= sys.argv[1];

hm = heatmap.Heatmap()
pts = []
con = None

#try:`id`, `time`, `date`, `latitude`, `longitude`
con = mdb.connect('localhost', 'UsernameHere','PasswordHere', 'DatabaseHere')	


cur = con.cursor()
#imglocations (`id`, `time`, `date`, `latitude`, `longitude`,`filename`)
cur.execute("SELECT * FROM imglocations")
rows = cur.fetchall()
for row in rows:
	#print row[3] row[4]
	pts.append(((float(row[4])),(float(row[3]))))
con.close()

hm.heatmap(pts,
           fout 	= './mapfiles/'+var_fileName+'-'+var_scheme+'.png',
           dotsize	= var_dotSize,
           opacity	= var_dotOpacity,
           size		= var_size,
           scheme	= var_scheme)

hm.saveKML('./mapfiles/'+var_fileName+'-'+var_scheme+'.kml')
