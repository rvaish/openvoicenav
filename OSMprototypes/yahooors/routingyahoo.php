<html>
<body>

<?php 

$source = $_GET["source"];
$desti = $_GET["desti"];
$url_query = str_ireplace(" ","%20","$source");
$url_query1 = str_ireplace(" ","%20","$desti");
$url_yahoofinder = "http://where.yahooapis.com/v1/places.q(".""."$url_query"."".")?appid=R3uEZcjV34FcVcdijSJakINk9bhElP4V4YqM5sNthFx.12Up8j.8sahnPUkDFvxkG0c-";
$url_yahoofinder1 = "http://where.yahooapis.com/v1/places.q(".""."$url_query1"."".")?appid=R3uEZcjV34FcVcdijSJakINk9bhElP4V4YqM5sNthFx.12Up8j.8sahnPUkDFvxkG0c-";

// Calculating Lat/Long for Source
$xml = simplexml_load_file("$url_yahoofinder");
$lats = $xml->children()->place->centroid->latitude;
$longs = $xml->children()->place->centroid->longitude;


// Calculating Lat/Long for Desti
$xml1 = simplexml_load_file("$url_yahoofinder1");
$latd = $xml1->children()->place->centroid->latitude;
$longd = $xml1->children()->place->centroid->longitude;


$url_source = $longs.",".$lats;
$url_desti = $longd.",".$latd;
$url_route = "http://data.giub.uni-bonn.de/openrouteservice/php/DetermineRoute_rajan.php?Start=".""."$url_source".""."&Via=&End=".""."$url_desti".""."&lang=en&distunit=KM&routepref=Fastest&avoidAreas=&useTMC=false&noMotorways=false&noTollways=false&instructions=true";
$xml2 = simplexml_load_file("$url_route");
//$xml2 = simplexml_load_file('http://data.giub.uni-bonn.de/openrouteservice/php/DetermineRoute_rajan.php?Start=7.0892567,50.7265543&Via=&End=7.0986258,50.7323634&lang=en&distunit=YD&routepref=Fastest&avoidAreas=&useTMC=false&noMotorways=false&noTollways=false&instructions=true');
define("NS_XLS2", "http://www.opengis.net/xls");
$ril4 = $xml2->children(NS_XLS2)->Response->DetermineRouteResponse->RouteInstructionsList;
echo $ril4->RouteInstruction->Instruction;

foreach ($ril4->RouteInstruction as $ri2) {
    echo $ri2->Instruction;
	echo " ";
	echo $ri2->distance->attributes(), "Km <br>\n";
	}

?>

</body>
</html>