<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>OSM Maps for Partially Visually Impaired-Global</title>
</head>
<body> 
<br>
<center><h3>OSM Maps for Partially Visually Impaired-Global</h3></center><br>
<div id="view-source">&nbsp;</div>
 <center>You are at: <p id="status"></p></center>
 <script type="text/javascript" src="gears_init.js"></script>
 <script type="text/javascript" src="sample.js"></script>
<script>

init();

function init() {
  if (!window.google || !google.gears) {
    addStatus('Gears is not installed', 'error');
    return;
  }


  //addStatus('Getting location...');

    function successCallback(p) {
    var address =   p.latitude + ', '
                  + p.longitude ;

    clearStatus();
	//var latlong = 'p.latitude' + 'p.longitude';
	//gt = "<"; lt = ">";
	//addressu = gt + '\?php ' + ' $address ' + ' = ' + '\"' + address + '\"' + ' ?' + lt;
    addStatus(address);
	//addStatus(latlong);



  }



  function errorCallback(err) {
    var msg = 'Error retrieving your location: ' + err.message;
    setError(msg);
  }

  try {
    var geolocation = google.gears.factory.create('beta.geolocation');
    geolocation.getCurrentPosition(successCallback,
                                   errorCallback,
                                   { enableHighAccuracy: true,
                                     gearsRequestAddress: true });
  } catch (e) {
    setError('Error using Geolocation API: ' + e.message);
    return;
  }

}
</script>

<form name="geo" action="osmglobalmap.php" method="get">
<center>
<select name="scale"> 
            <optgroup label="Scale">
            <option value="0.0005">0.1 km</option>
            <option value="0.005">0.5 km</option>
            <option value="0.01">1 km</option>
            <option value="0.05">5 km</option>
            <option value="0.1">10 km</option>
            </optgroup>
</select>
<b> Search Map around </b> (e.g. Street, City or Lat, Long) <input type="text" name="poi_name" />

  
<br>
<small><center>Tip : Narrow down search by adding exact Lat/Long in query (when dublicacy exists).</center></small>
<br>
<center><input type="submit" /> </center>
<script>
y=document.getElementById("status");
var t=setTimeout("document.geo.poi_name.value = y.innerHTML",1000);
</script>
</form>

<br>
<br>

<br>
<br>
<center>
<a href="http://openstreetmap.org">OSM</a>
<a href="http://openrouteservice.org/">ORS</a>
<a href="http://developer.yahoo.com/geo/geoplanet/guide/">Yahoo! GeoPlanet</a>
<a href="http://webanywhere.cs.washington.edu/wa.php">WebAnywhere</a>
<a href="http://gears.google.com/">Install Google Gears</a>
</center>
<br><br>

</body>
</html>