#!/usr/bin/python
#----------------------------------------------------------------------------
# Download OSM data covering the area of a slippy-map tile 
#
# Features:
#  * Cached (all downloads stored in cache/z/x/y/data.osm)
#----------------------------------------------------------------------------
# Copyright 2008, Oliver White
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#---------------------------------------------------------------------------
from google.appengine.api import urlfetch
import logging
import os
import zlib
from datetime import datetime
from osmtiledata import OSMTileData

def DownloadLevel():
  """All primary downloads are done at a particular zoom level"""
  return(15)

def GetOsmTileDataNotFile(z,x,y):
  """Download OSM data for the region covering a slippy-map tile"""
  if(x < 0 or y < 0 or z < 0 or z > 25):
    logging.debug("Disallowed %d,%d at %d" % (x,y,z))
    return ""

  if(z == DownloadLevel()):
    # Download the data
    URL = 'http://dev.openstreetmap.org/~ojw/api/?/map/%d/%d/%d' % (z,x,y)

    mykey = "OSMTILE%s%s" % (x,y)
    toreturn = ""
    myosmtile = OSMTileData.get_by_key_name(mykey)
    if myosmtile: # TODO: allow expiry of old data
      logging.debug('Using data from cache...')
      myosmtile.lastaccessdate = datetime.now().date()
      myosmtile.accesscount += 1
      myosmtile.put()
    else:
      logging.debug('Not in cache, downloading...')
      result = urlfetch.fetch(URL)
      myosmtile = OSMTileData(key_name=mykey)
      myosmtile.blobdata = zlib.compress(result.content)
      logging.debug('Compressing from %s to %s' % (len(result.content), len(myosmtile.blobdata)))
      myosmtile.accesscount = 0
      myosmtile.put()
      
    return(zlib.decompress(myosmtile.blobdata).split('\n'))
  return("")

if(__name__ == "__main__"):
  """test mode"""
  #~ print GetOsmTileData(15, 16218, 10741)
  pass
