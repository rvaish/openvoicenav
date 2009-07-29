<html>
<body>

<?php 

$source = $_GET["source"];
$desti = $_GET["desti"];
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
	
// Stuff for Static Maps API

$url_route_static = "http://www.yournavigation.org/gosmore.php?flat="."$lat"."&flon="."$long"."&tlat="."$lat1"."&tlon="."$long1"."&v=motorcar&fast=1&layer=mapnik";

$xml_static = simplexml_load_file("$url_route_static");
//$xml2 = simplexml_load_file('http://data.giub.uni-bonn.de/openrouteservice/php/DetermineRoute_rajan.php?Start=7.0892567,50.7265543&Via=&End=7.0986258,50.7323634&lang=en&distunit=YD&routepref=Fastest&avoidAreas=&useTMC=false&noMotorways=false&noTollways=false&instructions=true');

$ril_static = $xml_static->children()->Document->Folder->Placemark->LineString;
$costring = $ril_static->coordinates;
function count_words($str) 
 {
 $no = count(explode(",",$str));
 return $no;
 }
$number_of_latlong_temp = count_words("$costring");
$number_of_latlong = $number_of_latlong_temp-1; 
echo $number_of_latlong;

//$number_of_latlong;   //[It's Important]
$arry = (str_word_count("$costring",1,"0123456789."));


$latlonglist ="";
for ($i=0,$j=0; $i<$number_of_latlong; $i=$i+10,$j++)
  {

  $latlonglist = $latlonglist.$arry[$i].",".$arry[$i+1].","; 
  $arry_for_lat[$j] = $arry[$i+1];
  $arry_for_long[$j] = $arry[$i];
  
  }
  
  $maxlat = max($arry_for_lat);
  $maxlong = max($arry_for_long);
  $minlat = min($arry_for_lat);
  $minlong = min($arry_for_long);
  $latlonglist_url = "http://dev.openstreetmap.org/~pafciu17/?module=map&bbox=";
  $latlonglist_bbox = $minlong.",".$maxlat.",".$maxlong.",".$minlat;
  $latlonglist_inter = "&width=600&paths=";
  $latlonglist_query = $latlonglist;
  $latlonglist_completeurl = $latlonglist_url."".$latlonglist_bbox."".$latlonglist_inter."".$latlonglist_query;

  

?>
<IMG SRC="<?php echo $latlonglist_completeurl ?>" BORDER="0" ALT="Route for <?php echo $source?> to <?php echo $desti?>" />
</body>
</html>