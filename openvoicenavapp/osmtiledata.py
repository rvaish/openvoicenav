from google.appengine.ext import db

class OSMTileData(db.Model):
    blobdata = db.BlobProperty()
    accesscount = db.IntegerProperty()
    lastaccessdate = db.DateProperty(auto_now_add=True)
    lsatupdatedate = db.DateProperty(auto_now_add=True)