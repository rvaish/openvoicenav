<html>
<head>
<title>OSM Talking Maps</title>
    <script type="text/javascript" src="json2.js"></script>
    <script type="text/javascript" src="outfox-0.4.0.js"></script>
    <script type="text/javascript">
    function onLoad(event) {
        outfox.init("box", JSON.stringify, JSON.parse);
        var def = outfox.startService("audio");
        def.addCallback(function(cmd) {
            outfox.audio.say('Greetings from Outfox!');
           // enableButtons(true);
        });
        def.addErrback(function(cmd) {
            var box = document.getElementById('status');
            box.innerHTML = cmd.description;
        });
		
    }

    
    function singleSpeech(img,idt) {
	    //alert(idt);
        
        var node = document.getElementById(idt).alt;
        var token = outfox.audio.addObserver(function(tts, cmd) {
		tts.removeObserver(token);
        }, 0, ['finished-say']);
        outfox.audio.say(node);
    
    }
	
	
	</script>

</head>
<body onload="onLoad()">
 <center<h4>OSM Audible Maps for Firefox</h4></center>
 <div id="box"></div>
 
 



<?php 

$choice = $_GET["poi_ors"];
$poi_search = $_GET["poi_name"];
if (eregi("^[a-z]+(\s,[a-z]+)*", $poi_search))
 {
$url_query = str_ireplace(" ","%20","$poi_search");
$url_orsnamefinder = "http://data.giub.uni-bonn.de/openrouteservice/php/Geocode_rajan.php?FreeFormAdress=";
$url_geo = $url_orsnamefinder."".$url_query.""."&MaxResponse=1";

// Calculating Lat/Long for POI CENTER
$xml_geo = simplexml_load_file("$url_geo");
define("NS_XLS_geo", "http://www.opengis.net/xls"); 
define("NS_Point_geo", "http://www.opengis.net/gml");
$ril_geo = $xml_geo->children(NS_XLS_geo)->Response->GeocodeResponse->GeocodeResponseList;
foreach ($ril_geo->GeocodedAddress as $ri_geo) 
{
$ril_geo1 = $ri_geo->children(NS_Point_geo)->Point->pos;
   
}

//Extracting Lat/Long for POI CENTER
$pos_geo = stripos("$ril_geo1"," ");
$long_geo = substr("$ril_geo1",0,"$pos_geo");
$lat_geo = substr("$ril_geo1","$pos_geo");
 }
 
elseif (eregi("^[0-9-]+(\.,\s[0-9]+)*", $poi_search))
 {
  //Extracting Lat/Long for POI CENTER
  $pos_geo = stripos("$poi_search",",");
  $lat_geo = substr("$poi_search",0,"$pos_geo");
  $long_geo = substr("$poi_search",$pos_geo+1);
 
 }

// Creating URL for POI Extraction
$finalq_q = $long_geo.",".$lat_geo;
 if ( $choice == "public_tran" || $choice == "amenity" || $choice == "shop" || $choice == "tourism" )
 {
  $finalq = "http://data.giub.uni-bonn.de/openrouteservice/php/Directory_rajan.php?SearchType=dwithin&Position="."$finalq_q"."&MinDistance=0&MaxDistance=1000&POIname=Keyword&POIvalue="."$choice"."&MaxResponse=20";
 }
 else
 {
  $finalq = "http://data.giub.uni-bonn.de/openrouteservice/php/Directory_rajan.php?SearchType=dwithin&Position="."$finalq_q"."&MinDistance=0&MaxDistance=1000&POIname=NAICS_type&POIvalue="."$choice"."&MaxResponse=20";
 }

// Parsing for POI Extraion 
$xml = simplexml_load_file("$finalq"); 
define("NS_XLS", "http://www.opengis.net/xls"); 
define("NS_Point", "http://www.opengis.net/gml");
$ril = $xml->children(NS_XLS)->Response->DirectoryResponse;
$in = 0;

// foreach to find MAX/MIN of Lat/Long to create BBOX in Image
foreach ($ril->POIContext as $rimm) 
{
$rilmm1 = $rimm->POI->attributes();
$rilmm2 = $rimm->POI->children(NS_Point)->Point->pos;

 if (strlen("$rilmm1") != 0)
  {
  $posmm = stripos("$rilmm2"," ");
  $longmm[$in] = substr("$rilmm2",0,"$posmm");
  $latmm[$in] = substr("$rilmm2","$posmm");
  $in++;
  }
}
$top = max($latmm)+0.000001;
$right = max($longmm)+0.000001;
$down = min($latmm)-0.000001;
$left = min($longmm)-0.000001;

$image_url1 = "http://dev.openstreetmap.org/~pafciu17/?module=map&bbox=";
$image_url2 = $left.",".$top.",".$right.",".$down;
$image_url3 = "&width=800&height=400&points=";
$long_diff = $right-$left;
$lat_diff = $top-$down;
$area = $long_diff*$lat_diff;


// Assuming image is of size 800 x 400
$long_para = 800/$long_diff;
$lat_para = 400/$lat_diff;
$number = 0;
$html_str2 = " ";
$point_thing = " ";

 
 
//   foreach to find Name and Lat/Long to normalize lat/long with X/Y and display POIs
foreach ($ril->POIContext as $ri) 
{
$ril1 = $ri->POI->attributes();
$ril2 = $ri->POI->children(NS_Point)->Point->pos;
$pos = stripos("$ril2"," ");
$long = substr("$ril2",0,"$pos");
$lat = substr("$ril2","$pos");

if (strlen("$ril1") != 0)
 {
  $name[$number] = $ril1;
  foreach($ri->Distance->attributes() as $r => $v)
      {
      }
  $dist_in_m[$number] = $v;
  //for Lat/Y 
  $lat_arry[$number] = $lat;
  $y_temp = "$lat_arry[$number]"-$down;
  $y[$number] = (400 - ($lat_para*$y_temp));


  // for Long/X
  $long_arry[$number] = $long;
  $x_temp = "$long_arry[$number]"-($left);
  $x[$number] = $long_para*$x_temp;


// Point thing on Map's image
$point_temp = $long_arry[$number].','.$lat_arry[$number].';';
$point_thing = $point_thing.$point_temp;
// Audible mapping
$html_str2_temp = '<area shape="circle" coords="'.$x[$number].",".$y[$number].",".'20" alt="'.$name[$number].'" id="'.$number.'" onmouseover="singleSpeech(this,id)" />'.'<br/>';
$html_str2 = $html_str2.$html_str2_temp;

$number++; 
 } // end of if
   
} // end of for

// for Display of unique places (removing repetition)
$c = 0;
$a = 0;
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
  echo "&nbsp;&nbsp;";
  $lati_geo[$m] = $lat_arry[$inx];
  $longi_geo[$m] = $long_arry[$inx];
  echo " is ".$dist_in_m[$inx]." meters from "." $poi_search ";
  echo "<br/>";
  echo "</center>";
  
  }
echo "</br>";
echo "<center>";
echo "<a href='http://localhost/eurotriptalk/indexaudi.php'> Back </a>";
echo "<a href='home.html'> Home </a>";	
echo "</center>";
echo "</br>";
$image_url = "$image_url1"."$image_url2"."$image_url3"."$point_thing";
$image_url_final = str_ireplace(" ","%20","$image_url");
$html_str1 = '<img src='."$image_url_final".'alt="Planets" usemap="#planetmap" />'.'<map name="planetmap">'; 
$html_str3 = '</map>';

$final_html = $html_str1.$html_str2.$html_str3;
echo "<br/>";
echo "<center>";
echo $final_html;
echo "</center>";

//echo $number;
?>




</body>
</html>