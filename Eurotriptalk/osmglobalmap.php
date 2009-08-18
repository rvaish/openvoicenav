<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>OSM Maps for Partially Visually Impaired-Global</title>
 </head>
<body onload="onLoad()">
 <center<h4>OSM Maps for Partially Visually Impaired-Global</h4></center>
 <div id="status"></div>
 <div id="box"></div>

<?php 

$poi_search = $_GET["poi_name"];
$scale = $_GET["scale"];
if (eregi("^[a-z]+(\s,[a-z]+)*", $poi_search))
 {
$url_query = str_ireplace(" ","%20","$poi_search");
$url_yahoofinder = "http://where.yahooapis.com/v1/places.q(".""."$url_query"."".")?appid=R3uEZcjV34FcVcdijSJakINk9bhElP4V4YqM5sNthFx.12Up8j.8sahnPUkDFvxkG0c-";

// Calculating Lat/Long for Location
$xml_geo = simplexml_load_file("$url_yahoofinder");
$lat_geo = $xml_geo->children()->place->centroid->latitude;
$long_geo = $xml_geo->children()->place->centroid->longitude;

 }


elseif(eregi("^[0-9-]+(\.,\s[0-9]+)*", $poi_search))
 {
  //Extracting Lat/Long for POI CENTER
  $pos_geo = stripos("$poi_search",",");
  $lat_geo = substr("$poi_search",0,"$pos_geo");
  $long_geo = substr("$poi_search",$pos_geo+1);
  
 }
 
// Original 
$left = $long_geo-$scale;
$right = $long_geo+$scale;
$top = $lat_geo+$scale;
$down = $lat_geo-$scale;

$image_url1 = "http://dev.openstreetmap.org/~pafciu17/?module=map&bbox=";
$image_url2 = $left.",".$top.",".$right.",".$down;
$image_url3 = "&width=900&height=500";
$image_url = "$image_url1"."$image_url2"."$image_url3";


?>
<center><IMG SRC="<?php echo $image_url ?>" BORDER="0" ALT="Map of <?php echo $poi_search?>" /></center>
</body>


</html>