import MySQLdb as mdb
import sys
import heatmap
import random
#
#      _______                   
#     |__   __|                  
#   _ __ | | ___  __ _ _ __ ___  
#  | '_ \| |/ _ \/ _` | '_ ` _ \ 
#  | |_) | |  __/ (_| | | | | | |
#  | .__/|_|\___|\__,_|_| |_| |_|
#  | |                           
#  |_| 
# Filename: heatmapGenerate.py
# This script will generate a heatmap and KML file from the database. 
# 
# Execution: heatmapGenerate.py [dot Opacity] [dot Size] [Filename][size of image xxxx,yyyy] [sheme, classic, fire, pbj] [clause]
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
if (len(sys.argv) >= 7):
	query = "SELECT * FROM locations WHERE date = '"+var_clause+"'"
	cur.execute(query)
else:
	cur.execute("SELECT * FROM locations")
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
