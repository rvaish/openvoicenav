<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Directions tool with Routing Information for any Source to any Destination in Europe</title>
</head>
<body>

<?php 

$source = $_GET["source"];
$desti = $_GET["desti"];
echo "</br>";
echo "<center>"."<b>";
echo " Routing Information from ".$source." to ".$desti;
echo "</b>"."</center>";
echo "</br>";
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


$url_source = $long.",".$lat;
$url_desti = $long1.",".$lat1;

// Desti wrt to Source
if($lat1 > $lat && $long1 > $long)
 {
 $direction = $desti." is North East of ".$source;
 }
if($lat1 > $lat && $long1 < $long)
 {
 $direction = $desti." is North West of ".$source;
 }
if($lat1 < $lat && $long1 > $long)
 {
 $direction = $desti." is South East of ".$source;
 }
if($lat1 < $lat && $long1 < $long)
 {
 $direction = $desti." is South West of ".$source;
 } 
if($lat1 == $lat && $long1 > $long)
 {
 $direction = $desti." is East of ".$source;
 } 
if($lat1 == $lat && $long1 < $long)
 {
 $direction = $desti." is West of ".$source;
 } 
if($lat1 > $lat && $long1 == $long)
 {
 $direction = $desti." is North of ".$source;
 } 
if($lat1 < $lat && $long1 == $long)
 {
 $direction = $desti." is South of ".$source;
 }  
echo "</br>"; 
echo "<center>";
echo $direction;
echo "</center>";
echo "</br>";
$url_route = "http://data.giub.uni-bonn.de/openrouteservice/php/DetermineRoute_rajan.php?Start=".""."$url_source".""."&Via=&End=".""."$url_desti".""."&lang=en&distunit=KM&routepref=Fastest&avoidAreas=&useTMC=false&noMotorways=false&noTollways=false&instructions=true";
$xml2 = simplexml_load_file("$url_route");
//$xml2 = simplexml_load_file('http://data.giub.uni-bonn.de/openrouteservice/php/DetermineRoute_rajan.php?Start=7.0892567,50.7265543&Via=&End=7.0986258,50.7323634&lang=en&distunit=YD&routepref=Fastest&avoidAreas=&useTMC=false&noMotorways=false&noTollways=false&instructions=true');
define("NS_XLS2", "http://www.opengis.net/xls");
$ril4 = $xml2->children(NS_XLS2)->Response->DetermineRouteResponse->RouteInstructionsList;
foreach ($ril4->RouteInstruction as $ri2) {
    echo "<center>";
	echo $ri2->Instruction;
	echo " ";
	echo $ri2->distance->attributes(), "Km <br>\n";
	echo "</center>";
	}

echo "</br>";
echo "<center>";
echo "<a href='http://localhost/eurotriptalk/indexrouting.php'> Back </a>";
echo "<a href='home.html'> Home </a>";	
echo "</center>";
echo "</br>";
echo "</br>";	
// Stuff for Static Maps API
echo "<br/>";
$ri6 = "";
$counter = 0;
define("NS_XLS_static", "http://www.opengis.net/xls"); 
define("NS_Point_static", "http://www.opengis.net/gml");
$ril5 = $xml2->children(NS_XLS_static)->Response->DetermineRouteResponse->RouteGeometry;
foreach ($ril5->children(NS_Point_static)->LineString->pos as $ri5) 
{
$ri6 = $ri6."".$ri5;
$latlongarry[$counter] = $ri5;
$counter++;   
}
$url_size = $counter/86; // IE support URL max=2000 chars approx (MAKING IT VERY DYNAMIC)
$url_sizer = round($url_size);
if($url_sizer%2 != 0) // should be even
 {
 $url_sizer++;
 }
//echo $url_sizer;
//echo $ri6;
$latlonglist ="";
for($i=0,$j=0;$i<=$counter;$i++,$j=$j+$url_sizer)
 {
  $lenstr =strlen("$latlongarry[$i]");
  $position = stripos("$latlongarry[$i]"," ");
  $arry_for_long[$i] = substr("$latlongarry[$i]",0,$position);
  $arry_for_lat[$i] = substr("$latlongarry[$i]",$position+1,$lenstr);
  if($i<$counter && $j<$counter)
   {
    $lenstr1 =strlen("$latlongarry[$j]");
    $position1 = stripos("$latlongarry[$j]"," ");
    $arry_for_long1[$i] = substr("$latlongarry[$j]",0,$position1);
    $arry_for_lat1[$i] = substr("$latlongarry[$j]",$position1+1,$lenstr1);
    $latlonglist = $latlonglist.$arry_for_long1[$i].",".$arry_for_lat1[$i].","; 
   }
 }

  $maxlat = max($arry_for_lat);
  $maxlong = max($arry_for_long);
  
  $minlat = $arry_for_lat[0];
  $minlong = $arry_for_long[0];
    
  for($k=0;$k<$counter;$k++)
   {
   if($arry_for_lat[$k]<$minlat)
    {
	 $minlat = $arry_for_lat[$k];
	}
	if($arry_for_long[$k]<$minlong)
    {
	 $minlong = $arry_for_long[$k];
	}
   }

 
  $latlonglist_url = "http://dev.openstreetmap.org/~pafciu17/?module=map&bbox=";
  $latlonglist_bbox = $minlong.",".$maxlat.",".$maxlong.",".$minlat;
  $latlonglist_inter = "&width=600&height=600&paths=";
  $latlonglist_query = $latlonglist;
  $latlonglist_thick = "thickness:2";
  $latlonglist_completeurl = $latlonglist_url."".$latlonglist_bbox."".$latlonglist_inter."".$latlonglist_query.$latlonglist_thick;
 

?>
<center><IMG SRC="<?php echo $latlonglist_completeurl ?>" BORDER="0" ALT="Route for <?php echo $source?> to <?php echo $desti?>" /></center>
</body>
</html>