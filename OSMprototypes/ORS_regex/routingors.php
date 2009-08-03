<html>
<body>

<?php 

$source = $_GET["source"];
$desti = $_GET["desti"];

if (eregi("^[a-z]+(\s,[a-z]+)*", $source) && eregi("^[a-z]+(\s,[a-z]+)*", $desti))
 {
$url_query = str_ireplace(" ","%20","$source");
$url_query1 = str_ireplace(" ","%20","$desti");
$url_orsnamefinder = "http://data.giub.uni-bonn.de/openrouteservice/php/Geocode_rajan.php?FreeFormAdress=";
$url = $url_orsnamefinder."".$url_query.""."&MaxResponse=1";
$url1 = $url_orsnamefinder."".$url_query1.""."&MaxResponse=1";

// Calculating Lat/Long for Source
$xml = simplexml_load_file("$url");
define("NS_XLS", "http://www.opengis.net/xls"); 
define("NS_Point", "http://www.opengis.net/gml");
$ril = $xml->children(NS_XLS)->Response->GeocodeResponse->GeocodeResponseList;
foreach ($ril->GeocodedAddress as $ri) 
{
$ril1 = $ri->children(NS_Point)->Point->pos;
   
}
//Calculate Lat/Long for Desti
$xml1 = simplexml_load_file("$url1");
define("NS_XLS1", "http://www.opengis.net/xls"); 
define("NS_Point1", "http://www.opengis.net/gml");
$ril2 = $xml1->children(NS_XLS1)->Response->GeocodeResponse->GeocodeResponseList;
foreach ($ril2->GeocodedAddress as $ri1) 
{
$ril3 = $ri1->children(NS_Point1)->Point->pos;
 	
}

//Extracting Lat/Long for Source
$pos = stripos("$ril1"," ");
$long = substr("$ril1",0,"$pos");

$lat = substr("$ril1","$pos");

//Extracting Lat/Long for Desti
$pos1 = stripos("$ril3"," ");
$long1 = substr("$ril3",0,"$pos1");

$lat1 = substr("$ril3","$pos1");
 }
 
elseif (eregi("^[0-9-]+(\.,\s[0-9]+)*", $source) && eregi("^[0-9-]+(\.,\s[0-9]+)*", $desti))
 {
 //Extracting Lat/Long for Source
$poss = stripos("$source",",");
$lat = substr("$source",0,"$poss");
$long = substr("$source","$poss");

//Extracting Lat/Long for Desti
$poss1 = stripos("$desti",",");
$lat1 = substr("$desti",0,"$poss1");
$long1 = substr("$desti","$poss1");

 }
  
 //} 

$url_source = $long.",".$lat;
$url_desti = $long1.",".$lat1;
$url_route = "http://data.giub.uni-bonn.de/openrouteservice/php/DetermineRoute_rajan.php?Start=".""."$url_source".""."&Via=&End=".""."$url_desti".""."&lang=en&distunit=KM&routepref=Fastest&avoidAreas=&useTMC=false&noMotorways=false&noTollways=false&instructions=true";
$xml2 = simplexml_load_file("$url_route");
//$xml2 = simplexml_load_file('http://data.giub.uni-bonn.de/openrouteservice/php/DetermineRoute_rajan.php?Start=7.0892567,50.7265543&Via=&End=7.0986258,50.7323634&lang=en&distunit=YD&routepref=Fastest&avoidAreas=&useTMC=false&noMotorways=false&noTollways=false&instructions=true');
define("NS_XLS2", "http://www.opengis.net/xls");
$ril4 = $xml2->children(NS_XLS2)->Response->DetermineRouteResponse->RouteInstructionsList;
foreach ($ril4->RouteInstruction as $ri2) {
    echo $ri2->Instruction;
	echo " ";
	echo $ri2->distance->attributes(), "Km <br>\n";
	}


?>

</body>
</html>