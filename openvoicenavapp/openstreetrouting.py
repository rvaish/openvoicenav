import route
from loadOsm import *
import os
import locationService
import logging
import urllib

from django.utils import simplejson
from google.appengine.ext import webapp
from google.appengine.ext.webapp import template
from google.appengine.ext.webapp import util
from google.appengine.api import urlfetch
try:
  from google.appengine.runtime import DeadlineExceededError
except:
  from google.appengine.runtime.apiproxy_errors import DeadlineExceededError


class MainPage(webapp.RequestHandler):
  """ Renders the main template."""
  def get(self):

    lat = self.request.get('lat').strip()
    lon = self.request.get('lon').strip()
    zoom = self.request.get('zoom').strip()

    template_values = { 
            'title':'OpenStreetRouting',
            'lat':lat,
            'lon':lon,
            'zoom':zoom,
            }
    path = os.path.join(os.path.dirname(__file__), "index.html")
    self.response.out.write(template.render(path, template_values))


class RPCHandler(webapp.RequestHandler):
  """ Allows the functions defined in the RPCMethods class to be RPCed."""
  def __init__(self):
    webapp.RequestHandler.__init__(self)
    self.methods = RPCMethods()
 
  def get(self):
    func = None
   
    action = self.request.get('action')
    if action:
      if action[0] == '_':
        self.error(403) # access denied
        return
      else:
        func = getattr(self.methods, action, None)
   
    if not func:
      self.error(404) # file not found
      return
     
    args = ()
    while True:
      key = 'arg%d' % len(args)
      val = self.request.get(key)
      if val:
        args += (simplejson.loads(val),)
      else:
        break
    
    result = func(*args)
    self.response.out.write(simplejson.dumps(result))


class RPCMethods:
  """ Defines the methods that can be RPCed.
  NOTE: Do not allow remote callers access to private/protected "_*" methods.
  """

  def Add(self, *args):
    # The JSON encoding may have encoded integers as strings.
    # Be sure to convert args to any mandatory type(s).
    ints = [int(arg) for arg in args]
    return sum(ints)

  def Route2Points(self, *args):
    if len(args) == 5:
      lat1, lon1, lat2, lon2, transportmean = args
      if transportmean not in ['car','cycle','train','foot','horse']:
        transportmean = 'car'
    elif len(args) == 4:
      lat1, lon1, lat2, lon2 = args
      transportmean = 'car'
    else:
      return "ERROR: Argument count is not valid"
    
    data = LoadOsm(transportmean)

    node1 = data.findNode(lat1,lon1)
    node2 = data.findNode(lat2,lon2)
    #~ node1 = data.findNode(-7.049032,110.432809)
    #~ node2 = data.findNode(-7.0135376,110.4177235)
    #~ node2 = data.findNode(-7.0500114,110.4309719)

    #~ print node1
    #~ print node2
    
    router = route.Router(data)
    try:
      result, myroute = router.doRoute(node1, node2)
    except DeadlineExceededError:
      result = 'Routing time outed by google. Try again or try a shorter distance.'
    
    toreturn = {"type": "Feature"}
    toreturn["geometry"] = {"type": "LineString"}
    toreturn["geometry"]["coordinates"] =  []
    if result == 'success':
      toreturn["geometry"]["coordinates"].append([lon1,lat1])     # add starting point
      
      # list the lat/long
      for i in myroute:
        node = data.rnodes[i]
        #~ print "%d: %f,%f" % (i,node[0],node[1])
        toreturn["geometry"]["coordinates"].append([node[1],node[0]])
      
      toreturn["geometry"]["coordinates"].append([lon2,lat2])     # add ending point
      toreturn["properties"] = { "route_error": ""}
    else:
      #print result
      toreturn["geometry"]["coordinates"].append([lon1,lat1])
      toreturn["geometry"]["coordinates"].append([lon2,lat2])        
      toreturn["properties"] = { "route_error": "%s" % result}
      
    return simplejson.dumps(toreturn)
  
  def GetCurrentLocation(self, *args):
    toreturn = '{ "type": "Point", "coordinates": '
    #~ toreturn += "[%f,%f], " % (-7.049032, 110.432809)
    loctuple = locationService.getLocationFromIP()
    if loctuple:
        toreturn += "[%f,%f]" % locationService.getLocationFromIP()
    else:
        toreturn += "[0.0,0.0]"
    toreturn += ' }'
    
    return toreturn
  
  def GetPlacesFromString(self, *args):
    if len(args) != 1:
      return "ERROR: Argument count is not valid"
    querydata = {'name': args[0], 'featureClass': 'P', 'maxRows': 20}
    # myurl = "http://ws.geonames.org/searchJSON?name=%s&featureClass=P&maxRows=20" % args[0]
    myurl = "http://ws.geonames.org/searchJSON?%s" % urllib.urlencode(querydata)
    result = urlfetch.fetch(myurl)
    return result.content

def main():
  app = webapp.WSGIApplication([
    ('/', MainPage),
    ('/rpc', RPCHandler),
    ], debug=True)
  util.run_wsgi_app(app)

if __name__ == '__main__':
  main()

