import cgi
import urllib
import urllib2
from google.appengine.ext import webapp
from google.appengine.ext.webapp.util import run_wsgi_app
from google.appengine.api.urlfetch import fetch
from xml.dom import minidom

class MainPage(webapp.RequestHandler):
  def get(self):
    self.response.out.write("""
      <html>
        <body>
          <form action="/ors" method="get">
            <div><input type="text" name="source">
			<input type="text" name="desti">
            <input type="submit" value="Search"></div>
          </form>
        </body>
      </html>""")

class Orsroute(webapp.RequestHandler):
    def get(self):
        sourceori = self.request.get('source')
        destiori = self.request.get('desti')
        self.response.out.write('<html><body>You wrote:<pre>')
        self.response.out.write(sourceori+'<br/>')
        self.response.out.write(destiori+'<br/><br/>')
        source = urllib.quote(sourceori.encode('utf8'))
        desti = urllib.quote(destiori.encode('utf8'))
        url_orsnamefinder = "http://data.giub.uni-bonn.de/openrouteservice/php/Geocode_rajan.php?FreeFormAdress="
        url = url_orsnamefinder + source + "&MaxResponse=1"
        url1 = url_orsnamefinder + desti + "&MaxResponse=1"	  

        # FOR SOURCE
        DOMTree = minidom.parseString(fetch(url).content)
        hmm = DOMTree.getElementsByTagNameNS("http://www.opengis.net/gml","Point")
        for Point in hmm:
            type = Point.getElementsByTagNameNS("http://www.opengis.net/gml","pos")[0]
            latlong = type.childNodes[0].data
            spacepos = latlong.find(" ", 0)
            longsource = latlong[0:spacepos]
            latsource = latlong[spacepos+1:len(latlong)]

        #FOR DESTINATION
        DOMTree1 = minidom.parseString(fetch(url1).content)
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
        # output lat,lon
        self.response.out.write(url_source+'<br/>')
        self.response.out.write(url_desti)
        self.response.out.write('<br/><br/>')
        url_route = "http://data.giub.uni-bonn.de/openrouteservice/php/DetermineRoute_rajan.php?Start=" + url_source + "&Via=&End=" + url_desti + "&lang=en&distunit=KM&routepref=Fastest&avoidAreas=&useTMC=false&noMotorways=false&noTollways=false&instructions=true"

	# Open XML document using minidom parser
        DOMTree2 = minidom.parseString(fetch(url_route).content)
	hmm2 = DOMTree2.getElementsByTagNameNS("http://www.opengis.net/xls","RouteInstruction")

	for RouteInstruction in hmm2:
            type2 = RouteInstruction.getElementsByTagNameNS("http://www.opengis.net/xls","Instruction")[0]
            self.response.out.write(cgi.escape(type2.childNodes[0].data)+'&nbsp;&nbsp;&nbsp;')
            type3 = RouteInstruction.getElementsByTagNameNS("http://www.opengis.net/xls","distance")[0]
            self.response.out.write(cgi.escape(type3.getAttribute('value'))+'<br/>')

        self.response.out.write('</pre></body></html>')

application = webapp.WSGIApplication(
                                     [('/', MainPage),
                                      ('/ors', Orsroute)],
                                     debug=True)

def main():
  run_wsgi_app(application)

if __name__ == "__main__":
  main()   
