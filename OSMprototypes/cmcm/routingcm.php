<html>
<body>

<?php 

$source = $_GET["source"];
$desti = $_GET["desti"];
$url_query = str_ireplace(" ","%20","$source");
$url_query1 = str_ireplace(" ","%20","$desti");
$url_cmnamefinder = "http://geocoding.cloudmade.com/1c5b4d1eb3a159f0a14d8e1e3eb5d857/geocoding/find/";
$url = $url_cmnamefinder."".$url_query."".".js?results=1";
$url1 = $url_cmnamefinder."".$url_query1."".".js?results=1";
//echo $url;
//echo $url1;
// Calculating Lat/Long for Source
$json_in_site = file_get_contents("$url");
$obj = json_decode($json_in_site);
$lats = $obj->features[0]->centroid->coordinates[0];
$longs = $obj->features[0]->centroid->coordinates[1];
// Calculating Lat/Long for Desti
$json_in_site1 = file_get_contents("$url1");
$obj1 = json_decode($json_in_site1);
$latd = $obj1->features[0]->centroid->coordinates[0];
$longd = $obj1->features[0]->centroid->coordinates[1];
// Now use Routing APIs from CloudMade
$url_route = "http://routes.cloudmade.com/1c5b4d1eb3a159f0a14d8e1e3eb5d857/api/0.3/".""."$lats,".""."$longs,".""."$latd,".""."$longd".""."/bicycle.js?units=kms&lang=en";
$json_in_site2 = file_get_contents("$url_route");
$obj2 = json_decode($json_in_site2);
$x = 0;

foreach ($obj2->route_instructions as $key => $val)
{
echo $obj2->route_instructions[$key][0];
echo " ";
echo $obj2->route_instructions[$key][4];
echo " ";
echo $obj2->route_instructions[$key][5];
echo " ";
echo "<br>\n";

}




?>

</body>
</html>