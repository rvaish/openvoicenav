<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>
Point of Interests along Route and/or around Source/Destination in Europe
</title>
</head>
<body>
<br>
<form action="orspoi.php" method="get">
<center><h3>Point of Interests along Route and/or around Source/Destination in Europe</h3></center><br>
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
<br><br>
<b> Source </b> (e.g. Street, City or Lat, Long): <input type="text" name="source" />
<b> Destination </b> (e.g. Street, City or Lat, Long): <input type="text" name="desti" />


  
<br>
<small><center>Tip : Narrow down search by adding Country or exact latitude/longitude in query (when dublicacy exists).</center></small>
<br>
<center><input type="submit" /></center>
</form><br><br><br><br>

<center>
<h4><a href="orspoi.php?poi_ors=amenity&source=tower+of+london,+london&desti=big+ben,+london">Let's check out amenities along route from Tower of London to Big Ben in London!</a></h4>
<h4><a href="orspoi.php?poi_ors=hotel&source=Eiffel+Tower,+paris&desti=Louvre,+paris">or Hotels around Eiffel Tower and Louvre in Paris! </a></h4>
</center>
<center><a href="http://webanywhere.cs.washington.edu/wa.php">WebAnywhere</a>
<a href="http://openstreetmap.org">OSM</a>
<a href="http://openrouteservice.org/">ORS</a></center>
<br>
<small><center>Geocoding:ORS - Routing Engine:ORS</center></small>
</body>
</html>