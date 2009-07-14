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

// Calculating Lat/Long for Source using ORS Geocoding
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

  function count_words($str) 
 {
 $no = count(explode(",",$str));
 return $no;
 }
//Extracting Lat/Long for Source
$pos = stripos("$ril1"," ");
$long = substr("$ril1",0,"$pos");

$lat = substr("$ril1","$pos");


//Extracting Lat/Long for Desti
$pos1 = stripos("$ril3"," ");
$long1 = substr("$ril3",0,"$pos1");
$lat1 = substr("$ril3","$pos1");


$url_route = "http://www.yournavigation.org/gosmore.php?flat="."$lat"."&flon="."$long"."&tlat="."$lat1"."&tlon="."$long1"."&v=motorcar&fast=1&layer=mapnik";
//echo $url_route;
$xml2 = simplexml_load_file("$url_route");
//$xml2 = simplexml_load_file('http://data.giub.uni-bonn.de/openrouteservice/php/DetermineRoute_rajan.php?Start=7.0892567,50.7265543&Via=&End=7.0986258,50.7323634&lang=en&distunit=YD&routepref=Fastest&avoidAreas=&useTMC=false&noMotorways=false&noTollways=false&instructions=true');

$ril4 = $xml2->children()->Document->Folder->Placemark->LineString;
$costring = $ril4->coordinates;

$number_of_latlong_temp = count_words("$costring");
$number_of_latlong = $number_of_latlong_temp-1;

//$number_of_latlong;   //[It's Important]
$arry = (str_word_count("$costring",1,"0123456789."));




function distance($Aa, $Ba, $Ca, $Da){
// Help from a random forum on Internet was sought to implement this algorithm.
// The math for this was taken from http://mathforum.org/library/drmath/view/51711.html
$input = array($Aa, $Ba, $Ca, $Da);
foreach($input as $name){
if (ereg("[[:alpha:]]",$name)){
echo "You cannot enter letters into this function<br>\n";
die;
}
if(ereg("\.",$name)){
$dot = ".";
$pos = strpos($name, $dot);
//echo $pos." <br>\n";
if($pos > 3){
echo "The input cannot exceed more than 3 digits left of the decimal<br>\n";
die;
}
}
if($name > 365){
echo "The input cannot exceed 365 degrees <BR>\n";
die;

}
}



$A = $Aa/57.29577951;
$B = $Ba/57.29577951;
$C = $Ca/57.29577951;
$D = $Da/57.29577951; 
//convert all to radians: degree/57.29577951

if ($A == $C && $B == $D ){
$dist = 0; 
}
else if ( (sin($A)* sin($C)+ cos($A)* cos($C)* cos($B-$D)) > 1){ 
$dist = 3963.1* acos(1);// solved a prob I ran into. I haven't fully analyzed it yet 

}

else{

$dist = 3963.1* acos(sin($A)*sin($C)+ cos($A)* cos($C)* cos($B-$D));
}
return ($dist);
} // end of function distance



function locationfinder($x,$y,$j,$number_of_latlong)
{
static $lat_place;
$lat_place[$j] = $x;

static $long_place;
$long_place[$j] = $y;

$url_revgeo = "http://data.giub.uni-bonn.de/openrouteservice/php/Geocode_rajan.php?Lon=";
$query_revgeo = $y.""."&Lat="."".$x.""."&MaxResponse=1";
$query_url = "$url_revgeo"."$query_revgeo";

$xml_geo = simplexml_load_file("$query_url");

define("NS_XLS2", "http://www.opengis.net/xls"); 
$ril_geo = $xml_geo->children(NS_XLS2)->Response->ReverseGeocodeResponse;
foreach ($ril_geo->ReverseGeocodedLocation as $ri_geo) 
{
$ril1_geo = $ri_geo->Address->StreetAddress->Street->attributes();

static $places;
$places[$j] = "$ril1_geo";
}

if($j==(($number_of_latlong/2)-1))
{
$c = 0;
$a = 0;
 for($k=0;$k<($number_of_latlong/2);$k++)
 { 
  $flag = 0;
  for($l=0;$l<$k;$l++)
   {
   if($places[$k] == $places[$l])
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
 
  
 
  $wow = "$index[$m]";

  $lat_geo[$m] = $lat_place[$wow];

  $long_geo[$m] = $long_place[$wow];

  $location_geo[$m] = $places[$wow];

  
  }
  
  for($t=0,$p=0;$t<$c;$t++,$p++)
  {
   
   echo $location_geo[$t];
   echo "<br />";
   
   if($p<$c-1) // to prevent garbage value of $t+1 thing 
    {
   $lat_diff = $lat_geo[$t+1]-$lat_geo[$t];
    $long_diff = $long_geo[$t+1]-$long_geo[$t];
	$distance_geo = distance($lat_geo[$t],$long_geo[$t],$lat_geo[$t+1],$long_geo[$t+1]);

    if($lat_geo[$t+1]>0 && $lat_geo[$t]>0 && $long_geo[$t+1]>0 && $long_geo[$t]>0) // North Hemisphere EAST
	  {
	   if($lat_diff==0 && $long_diff>0)
	   echo "Move East"; 
	   if($lat_diff>0 && $long_diff>0)	
	   echo "Move North East"; 	
	   if($lat_diff>0 && $long_diff==0)	 
	   echo "Move North"; 	 
	   if($lat_diff>0 && $long_diff<0)	
	   echo "Move North West";	
	   if($lat_diff==0 && $long_diff<0)	
	   echo "Move West"; 	 
	   if($lat_diff<0 && $long_diff<0)	
	   echo "Move South West"; 	
	   if($lat_diff<0 && $long_diff==0)	
	   echo "Move South"; 	 
	   if($lat_diff<0 && $long_diff>0)	
	   echo "Move South East"; 
	 
	  }
	  
	if($lat_geo[$t+1]>0 && $lat_geo[$t]>0 && $long_geo[$t+1]<0 && $long_geo[$t]<0) // North Hemisphere WEST
	 {
	   if($lat_diff==0 && $long_diff>0)
	   echo "Move East"; 
	   if($lat_diff>0 && $long_diff>0)
	   echo "Move North East"; 
	   if($lat_diff>0 && $long_diff==0)
	   echo "Move North"; 
	   if($lat_diff>0 && $long_diff<0)
	   echo "Move North West";
	   if($lat_diff==0 && $long_diff<0)
	   echo "Move West"; 
	   if($lat_diff<0 && $long_diff<0)
	   echo "Move South West"; 
	   if($lat_diff<0 && $long_diff==0)
	   echo "Move South"; 
	   if($lat_diff<0 && $long_diff>0)
	   echo "Move South East"; 
	  }  
	  
	 if($lat_geo[$t+1]<0 && $lat_geo[$t]<0 && $long_geo[$t+1]>0 && $long_geo[$t]>0) // South Hemisphere EAST
	  {
	   if($lat_diff==0 && $long_diff>0)
	   echo "Move East"; 
	   if($lat_diff>0 && $long_diff>0)	
	   echo "Move North East"; 	
	   if($lat_diff>0 && $long_diff==0)	 
	   echo "Move North"; 	 
	   if($lat_diff>0 && $long_diff<0)	
	   echo "Move North West";	
	   if($lat_diff==0 && $long_diff<0)	
	   echo "Move West"; 	 
	   if($lat_diff<0 && $long_diff<0)	
	   echo "Move South West "; 	
	   if($lat_diff<0 && $long_diff==0)	
	   echo "Move South "; 	 
	   if($lat_diff<0 && $long_diff>0)	
	   echo "Move South East"; 
	 
	  }
	  

	 if($lat_geo[$t+1]<0 && $lat_geo[$t]<0 && $long_geo[$t+1]<0 && $long_geo[$t]<0) // South Hemisphere WEST
	  {
	   if($lat_diff==0 && $long_diff>0)
	   echo "Move East"; 
	   if($lat_diff>0 && $long_diff>0)	
	   echo "Move North East"; 	
	   if($lat_diff>0 && $long_diff==0)	 
	   echo "Move North"; 	 
	   if($lat_diff>0 && $long_diff<0)	
	   echo "Move North West";	
	   if($lat_diff==0 && $long_diff<0)	
	   echo "Move West"; 	 
	   if($lat_diff<0 && $long_diff<0)	
	   echo "Move South West "; 	
	   if($lat_diff<0 && $long_diff==0)	
	   echo "Move South "; 	 
	   if($lat_diff<0 && $long_diff>0)	
	   echo "Move South East"; 
	 
	  }
	    
	if($lat_geo[$t+1]>0 && $lat_geo[$t]>0 && ($long_geo[$t+1]>0 && $long_geo[$t]<0) || ($long_geo[$t+1]<0 && $long_geo[$t]>0)) // Cross North
	  {
	   if($lat_diff==0 && $long_diff>0)
	   echo "Move East"; 
	   if($lat_diff>0 && $long_diff>0)	
	   echo "Move North East"; 	
	   if($lat_diff>0 && $long_diff==0)	 
	   echo "Move North"; 	 
	   if($lat_diff>0 && $long_diff<0)	
	   echo "Move North West";	
	   if($lat_diff==0 && $long_diff<0)	
	   echo "Move West"; 	 
	   if($lat_diff<0 && $long_diff<0)	
	   echo "Move South West "; 	
	   if($lat_diff<0 && $long_diff==0)	
	   echo "Move South "; 	 
	   if($lat_diff<0 && $long_diff>0)	
	   echo "Move South East"; 
	 
	  }
	  
	if($lat_geo[$t+1]<0 && $lat_geo[$t]<0 && ($long_geo[$t+1]<0 && $long_geo[$t]>0) || ($long_geo[$t+1]>0 && $long_geo[$t]<0)) // Cross South
	  {
	   if($lat_diff==0 && $long_diff>0)
	   echo "Move East"; 
	   if($lat_diff>0 && $long_diff>0)	
	   echo "Move North East"; 	
	   if($lat_diff>0 && $long_diff==0)	 
	   echo "Move North"; 	 
	   if($lat_diff>0 && $long_diff<0)	
	   echo "Move North West";	
	   if($lat_diff==0 && $long_diff<0)	
	   echo "Move West"; 	 
	   if($lat_diff<0 && $long_diff<0)	
	   echo "Move South West "; 	
	   if($lat_diff<0 && $long_diff==0)	
	   echo "Move South "; 	 
	   if($lat_diff<0 && $long_diff>0)	
	   echo "Move South East"; 
	 
	  }
	  
	if($long_geo[$t+1]>0 && $long_geo[$t]>0 && ($lat_geo[$t+1]<0 && $lat_geo[$t]>0) || ($lat_geo[$t+1]>0 && $lat_geo[$t]<0)) // Cross East
	  {
	   if($lat_diff==0 && $long_diff>0)
	   echo "Move East"; 
	   if($lat_diff>0 && $long_diff>0)	
	   echo "Move North East"; 	
	   if($lat_diff>0 && $long_diff==0)	 
	   echo "Move North"; 	 
	   if($lat_diff>0 && $long_diff<0)	
	   echo "Move North West";	
	   if($lat_diff==0 && $long_diff<0)	
	   echo "Move West"; 	 
	   if($lat_diff<0 && $long_diff<0)	
	   echo "Move South West "; 	
	   if($lat_diff<0 && $long_diff==0)	
	   echo "Move South "; 	 
	   if($lat_diff<0 && $long_diff>0)	
	   echo "Move South East"; 
	 
	  }  
	  
	if($long_geo[$t+1]<0 && $long_geo[$t]<0 && ($lat_geo[$t+1]<0 && $lat_geo[$t]>0) || ($lat_geo[$t+1]>0 && $lat_geo[$t]<0)) // Cross West
	  {
	   if($lat_diff==0 && $long_diff>0)
	   echo "Move East"; 
	   if($lat_diff>0 && $long_diff>0)	
	   echo "Move North East"; 	
	   if($lat_diff>0 && $long_diff==0)	 
	   echo "Move North"; 	 
	   if($lat_diff>0 && $long_diff<0)	
	   echo "Move North West";	
	   if($lat_diff==0 && $long_diff<0)	
	   echo "Move West"; 	 
	   if($lat_diff<0 && $long_diff<0)	
	   echo "Move South West "; 	
	   if($lat_diff<0 && $long_diff==0)	
	   echo "Move South "; 	 
	   if($lat_diff<0 && $long_diff>0)	
	   echo "Move South East"; 
	 
	  }  
   
    echo " by ";
	echo $distance_geo.""." miles ";
	echo "<br />";
    }
   
   }
  
  
 
} // if ($j=14)


}

for ($i=0,$j=0; $i<$number_of_latlong; $i=$i+2,$j++)
  {
  $location = locationfinder($arry[$i+1],$arry[$i],$j,$number_of_latlong);
  }




?>

</body>
</html>