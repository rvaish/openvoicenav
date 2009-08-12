<html>
<head>
<title>POI along Route</title>
  
</head>
<body onload="onLoad()">
 <center<h4>POI along Route</h4></center>
 <div id="status"></div>
 <div id="box"></div>

<?php 

$choice = $_GET["poi_ors"];
$source = $_GET["source"];
$desti = $_GET["desti"];
$url_query = str_ireplace(" ","%20","$source");
$url_query1 = str_ireplace(" ","%20","$desti");
$url_orsnamefinder = "http://data.giub.uni-bonn.de/openrouteservice/php/Geocode_rajan.php?FreeFormAdress=";
$url = $url_orsnamefinder."".$url_query.""."&MaxResponse=1";
$url1 = $url_orsnamefinder."".$url_query1.""."&MaxResponse=1";

// Calculating Lat/Long for Source
$xml_source = simplexml_load_file("$url");
define("NS_XLS", "http://www.opengis.net/xls"); 
define("NS_Point", "http://www.opengis.net/gml");
$ril_source = $xml_source->children(NS_XLS)->Response->GeocodeResponse->GeocodeResponseList;
foreach ($ril_source->GeocodedAddress as $ri_source) 
{
$ril1_source = $ri_source->children(NS_Point)->Point->pos;
   
}
//Calculate Lat/Long for Desti
$xml_desti = simplexml_load_file("$url1");
define("NS_XLS1", "http://www.opengis.net/xls"); 
define("NS_Point1", "http://www.opengis.net/gml");
$ril_desti = $xml_desti->children(NS_XLS1)->Response->GeocodeResponse->GeocodeResponseList;
foreach ($ril_desti->GeocodedAddress as $ri_desti) 
{
$ril2_desti = $ri_desti->children(NS_Point1)->Point->pos;
 	
}
//Extracting Lat/Long for Source
$pos = stripos("$ril1_source"," ");
$long_geo = substr("$ril1_source",0,"$pos");

$lat_geo = substr("$ril1_source","$pos");


//Extracting Lat/Long for Desti
$pos1 = stripos("$ril2_desti"," ");
$long_geod = substr("$ril2_desti",0,"$pos1");

$lat_geod = substr("$ril2_desti","$pos1");



// Creating URL for POI Extraction Source
$finalq_qs = $long_geo.",".$lat_geo;
$finalq_qd = $long_geod.",".$lat_geod;
 if ( $choice == "public_tran" || $choice == "amenity" || $choice == "shop" || $choice == "tourism" )
 {
  $finalqs = "http://data.giub.uni-bonn.de/openrouteservice/php/Directory_rajan.php?SearchType=dwithin&Position="."$finalq_qs"."&MinDistance=0&MaxDistance=1000&POIname=Keyword&POIvalue="."$choice"."&MaxResponse=20";
  $finalqd = "http://data.giub.uni-bonn.de/openrouteservice/php/Directory_rajan.php?SearchType=dwithin&Position="."$finalq_qd"."&MinDistance=0&MaxDistance=1000&POIname=Keyword&POIvalue="."$choice"."&MaxResponse=20";
  }
 else
 {
  $finalqs = "http://data.giub.uni-bonn.de/openrouteservice/php/Directory_rajan.php?SearchType=dwithin&Position="."$finalq_qs"."&MinDistance=0&MaxDistance=1000&POIname=NAICS_type&POIvalue="."$choice"."&MaxResponse=20";
  $finalqd = "http://data.giub.uni-bonn.de/openrouteservice/php/Directory_rajan.php?SearchType=dwithin&Position="."$finalq_qd"."&MinDistance=0&MaxDistance=1000&POIname=NAICS_type&POIvalue="."$choice"."&MaxResponse=20";
 }
 
 

// Parsing for POI Extraion Source
$xmls = simplexml_load_file("$finalqs"); 
define("NS_XLSs", "http://www.opengis.net/xls"); 
define("NS_Points", "http://www.opengis.net/gml");
$rils = $xmls->children(NS_XLSs)->Response->DirectoryResponse;
$ins = 0;

// Parsing for POI Extraion Destination
$xmld = simplexml_load_file("$finalqd"); 
define("NS_XLSd", "http://www.opengis.net/xls"); 
define("NS_Pointd", "http://www.opengis.net/gml");
$rild = $xmld->children(NS_XLSd)->Response->DirectoryResponse;
$ind = 0;
$point_things = " ";
$point_thingd = " ";
// foreach to find MAX/MIN of Lat/Long to create BBOX in Image
echo "<center>";
echo "POIs near".$source."<br/>";
echo "</center>";
foreach ($rils->POIContext as $rimms) 
{
$rilmm1s = $rimms->POI->attributes();
$rilmm2s = $rimms->POI->children(NS_Points)->Point->pos;

 if (strlen("$rilmm1s") != 0)
  {   
      echo "<center>";
      echo $names[$ins] = $rilmm1s;
	  echo "</center>";
      $posmms = stripos("$rilmm2s"," ");
      $longmms[$ins] = substr("$rilmm2s",0,"$posmms");
      $latmms[$ins] = substr("$rilmm2s","$posmms");
      $point_temps = $longmms[$ins].','.$latmms[$ins].';';
      $point_things = $point_things.$point_temps;

      $ins++;
  }
}
$tops = max($latmms)+0.000001;
$rights = max($longmms)+0.000001;
$downs = min($latmms)-0.000001;
$lefts = min($longmms)-0.000001;
// foreach to find MAX/MIN of Lat/Long to create BBOX in Image
echo "<br/>";
echo "<center>";
echo "POIs near".$desti."<br/>";
echo "</center>";
foreach ($rild->POIContext as $rimmd) 
{
$rilmm1d = $rimmd->POI->attributes();
$rilmm2d = $rimmd->POI->children(NS_Pointd)->Point->pos;

 if (strlen("$rilmm1d") != 0)
  {   
      echo "<center>";
      echo $named[$ind] = $rilmm1d;
      echo "</center>";	  
      $posmmd = stripos("$rilmm2d"," ");
      $longmmd[$ind] = substr("$rilmm2d",0,"$posmmd");
      $latmmd[$ind] = substr("$rilmm2d","$posmmd");
      $point_tempd = $longmmd[$ind].','.$latmmd[$ind].';';
      $point_thingd = $point_thingd.$point_tempd;
     
      $ind++;
  }
}
$topd = max($latmmd)+0.000001;
$rightd = max($longmmd)+0.000001;
$downd = min($latmmd)-0.000001;
$leftd = min($longmmd)-0.000001;

// final TOP,DOWN,RIGHT,LEFT

if($topd > $tops)
  {
  $top = $topd + 0.000001;
  }
else
  {
  $top = $tops + 0.000001;
  }
if($downd < $downs)
  {
  $down = $downd - 0.000001;
  }
else
  {
  $down = $downs - 0.000001;
  }
if($rightd > $rights)
  {
  $right = $rightd + 0.000001;
  }
else
  {
  $right = $rights + 0.000001;
  }
if($leftd < $lefts)
  {
  $left = $leftd - 0.000001;
  }
else
  {
  $left = $lefts - 0.000001;
  }  
$point_thing = $point_things.$point_thingd; 
$image_url1 = "http://dev.openstreetmap.org/~pafciu17/?module=map&bbox=";
$image_url2 = $left.",".$top.",".$right.",".$down;
$image_url3 = "&width=900&height=500&points="; 
$image_url = "$image_url1"."$image_url2"."$image_url3"."$point_thing";
$image_url_final = str_ireplace(" ","%20","$image_url");

/*
$name = array_merge($names, $named);
$latitude = array_merge($longmms, $longmmd);
$longitude = array_merge($longmms, $longmmd);
$c = 0;
$a = 0;
$number = $inc + $ind;
for($k=0;$k<$number;$k++)
 { 
  $flag = 0;
  for($l=0;$l<$k;$l++)
   {
   if("$name[$k]" == "$name[$l]")
      {
	  $flag = 1;
	  }
   }
   if($flag == 0)
      {
	
	 $index[$a] = $k;

	 $c++;
	 $a++;
	  }
 } // end for

 for($m=0;$m<$c;$m++) // running for number of distinct locations
  {

  $inx = "$index[$m]";
  echo "<center>";
  echo $location_geo[$m] = $name[$inx];
  echo "&nbsp;&nbsp;&nbsp;";
  echo $lat_geo[$m] = $latitude[$inx];
  echo "&nbsp;&nbsp;&nbsp;";
  echo $long_geo[$m] = $longitude[$inx];
  echo "<br/>";
  echo "</center>";
  
  }
*/
?>
<center><IMG SRC="<?php echo $image_url_final ?>" BORDER="0" ALT="Route for <?php echo $source?> to <?php echo $desti?>" /></center>

</body>

</html>