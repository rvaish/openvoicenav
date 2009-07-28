#!c:/python25/python.exe -u

# Import modules for CGI handling 
import urllib
from xml.dom import minidom
import cgi, cgitb

# Create instance of FieldStorage 
form = cgi.FieldStorage() 

# Get data from fields
sourceori = form.getvalue('source')
source = sourceori.replace(" ", "%20");
destiori  = form.getvalue('desti')
desti = destiori.replace(" ", "%20");
url_orsnamefinder = "http://data.giub.uni-bonn.de/openrouteservice/php/Geocode_rajan.php?FreeFormAdress="
url = url_orsnamefinder + source + "&MaxResponse=1"
url1 = url_orsnamefinder + desti + "&MaxResponse=1"

# FOR SOURCE
DOMTree = minidom.parse(urllib.urlopen(url))
hmm = DOMTree.getElementsByTagNameNS("http://www.opengis.net/gml","Point")

print "Content-Type: text/html\n"
for Point in hmm:
   type = Point.getElementsByTagNameNS("http://www.opengis.net/gml","pos")[0]
   latlong = type.childNodes[0].data

spacepos = latlong.find(" ", 0)
longsource = latlong[0:spacepos]
latsource = latlong[spacepos+1:len(latlong)]

#FOR DESTINATION
DOMTree1 = minidom.parse(urllib.urlopen(url1))
hmm1 = DOMTree1.getElementsByTagNameNS("http://www.opengis.net/gml","Point")

for Point1 in hmm1:
   type1 = Point1.getElementsByTagNameNS("http://www.opengis.net/gml","pos")[0]
   latlong1 = type1.childNodes[0].data

spacepos1 = latlong1.find(" ", 0)
longdesti = latlong1[0:spacepos1]
latdesti = latlong1[spacepos1+1:len(latlong1)]

# FINAL CALCULATION OF ROUTES
url_source = longsource + "," + latsource
url_desti = longdesti + "," + latdesti
url_route = "http://data.giub.uni-bonn.de/openrouteservice/php/DetermineRoute_rajan.php?Start=" + url_source + "&Via=&End=" + url_desti + "&lang=en&distunit=KM&routepref=Fastest&avoidAreas=&useTMC=false&noMotorways=false&noTollways=false&instructions=true"


# Open XML document using minidom parser
DOMTree2 = minidom.parse(urllib.urlopen(url_route))
hmm2 = DOMTree2.getElementsByTagNameNS("http://www.opengis.net/xls","RouteInstruction")

for RouteInstruction in hmm2:
   type2 = RouteInstruction.getElementsByTagNameNS("http://www.opengis.net/xls","Instruction")[0]
   print type2.childNodes[0].data
   type3 = RouteInstruction.getElementsByTagNameNS("http://www.opengis.net/xls","distance")[0]
   print type3.getAttribute('value') + " km "
   print '<br>'
  

   

