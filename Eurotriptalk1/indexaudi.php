<html>
<head>
<title>Firefox user?, try Talking Map, also browse Point of Interests around any location in Europe</title>
</head>
<body> 
<br>
<center><h3> Firefox user?, try Talking Map, also browse Point of Interests around any location in Europe</h3></center><br>
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

<form name="geo" action="orsaudible.php" method="get">
<center>
<select name="poi_ors"> 
            <option value="amenity">AMENITY</option>
            <option value="atm">ATM / EC</option>
            <option value="bank">Bank</option>
            <option value="bureau_de_change">Bureau de Change</option>
            <option value="biergarten">Biergarten</option>
            <option value="bus_station">Bus Station</option>
            <option value="cafe">Cafe</option>
            <option value="cinema">Cinema</option>
            <option value="college">College</option>
            <option value="courthouse">Courthouse</option>
            <option value="fast_food">Fast Food</option>
            <option value="fuel">Fuel</option>
            <option value="hospital">Hospital</option>
            <option value="library">Library</option>
            <option value="nightclub">Nightclub</option>
            <option value="parking">Parking</option>
            <option value="pharmacy">Pharmacy</option>
            <option value="place_of_worship">Place of Worship</option>
            <option value="police">Police</option>
            <option value="post_box">Post Box</option>
            <option value="post_office">Post Office</option>
            <option value="pub">Pub</option>
            <option value="public_building">Public Building</option>
            <option value="restaurant">Restaurant</option>
            <option value="school">School</option>
            <option value="taxi">Taxi</option>
            <option value="telephone">Telephone</option>
            <option value="theatre">Theatre</option>
            <option value="toilets">Toilets</option>
            <option value="townhall">Townhall</option>
            <option value="university">University</option>
            <option value="public_tran">PUBLIC TRANSPORT</option>
            <option value="bus_stop">Bus Stop</option>
            <option value="bus_station">Bus Station</option>
            <option value="railway_station">Railway Station</option>
            <option value="tram_stop">Tram Stop</option>
            <option value="subway_entrance">Subway Entrance</option>
            <option value="parking">Parking</option>
            <option value="shop">SHOPS</option>
            <option value="supermarket">Supermarket</option>
            <option value="convenience">Convenience</option>
            <option value="bakery">Bakery</option>
            <option value="butcher">Butcher</option>
            <option value="kiosk">Kiosk</option>
            <option value="tourism">TOURISM</option>
            <option value="information">Information</option>
            <option value="hotel">Hotel</option>
            <option value="motel">Motel</option>
            <option value="guest_house">Guest House</option>
            <option value="hostel">Hostel</option>
</select>
<b> POI </b> (e.g. Street, City or Lat, Long) <input type="text" name="poi_name" />

  
<br>
<small><center>Tip : Narrow down search by adding Country and/or exact Lat/Long in query (when dublicacy exists).</center></small>
<br>
<center><input type="submit" /> </center>
<script>
y=document.getElementById("status");
var t=setTimeout("document.geo.poi_name.value = y.innerHTML",1000);
</script>
</form>

<br>
<br>
<center>
Let's check out amenities around some famous European places! 
<h4><a href="http://localhost/eurotriptalk/orsaudible.php?poi_ors=amenity&poi_name=berlin+wall,+berlin,+germany">Berlin Wall, Germany</a></h4>
<h4><a href="http://localhost/eurotriptalk/orsaudible.php?poi_ors=amenity&poi_name=london+bridge,+london">London Bridge, UK</a></h4>
<h4><a href="http://localhost/eurotriptalk/orsaudible.php?poi_ors=amenity&poi_name=tower+of+pisa,+pisa,+italy">Leaning Tower of Pisa, Italy</a></h4>
<h4><a href="http://localhost/eurotriptalk/orsaudible.php?poi_ors=amenity&poi_name=eiffel+tower,+paris">Eiffel Tower, France</a></h4>
<h4><a href="http://localhost/eurotriptalk/orsaudible.php?poi_ors=amenity&poi_name=royal+palace,+madrid,+spain">Royal Palace, Spain</a></h4>
</center>
<br>
<br>
<center>
<a href="http://openstreetmap.org">OSM</a>
<a href="http://openrouteservice.org/">ORS</a>
<a href="http://localhost/ors/indexors.php">WebAnywhere</a>
<a href="http://www.mozilla.com/products/download.html?product=firefox-3.0.13&os=win&lang=en-US">Download Firefox</a>
<a href="http://cs.unc.edu/Research/assist/outfox/xpi/outfox.xpi">Install Outfox</a>
<a href="http://gears.google.com/">Install Google Gears</a>
</center>
<br><br>

</body>
</html>