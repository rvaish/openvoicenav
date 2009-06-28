<html>
<head>
<script>
function textual()
{
txt=document.getElementById("panel").innerHTML;
txt1=document.getElementById("a").innerHTML;
txt2=document.getElementById("latlong").innerHTML;
txt3=document.getElementById("latlong1").innerHTML;
txt4=document.getElementById("b").innerHTML;
txt5=document.getElementById("latlong2").innerHTML;
txt6=document.getElementById("latlong3").innerHTML;
document.write(" <p>The Routing information from: " + txt1 + " to " + txt4 + " is " + txt + " </p> ");
document.write(" <p>For Source :" + txt1 + " Latitude is " + txt2 + " and Longitude is " + txt3 + " </p> ");
document.write(" <p>For Destination :" + txt4 + " Latitude is " + txt5 + " and Longitude is " + txt6 + " </p> ");

}

</script>
<br>
<a href="http://localhost/cm/index.php">Try more!</a>
</head>
<body onload="textual()">
<!-- Simple PHP Script to calculate Latitude and Longitude for a given location using OSM Namefinder (http://gazetteer.openstreetmap.org/namefinder/).Developed by Rajan Vaish,mentored by Artem Dudarev-->
<!--Form to enter location-->
<!--<form name="text" action="latlong1.php" onSubmit="textual()">-->
<!--<center><input type="submit" value="Press this button to see Textual representation of Routing Instruction"></center>-->
<!--</form>-->


<?php 

// for source
$source = $_GET["source"];
$url_query = str_ireplace(" ","%20","$source");
$url_nf = "http://gazetteer.openstreetmap.org/namefinder/search.xml?find=";
$url = $url_nf."".$url_query.""."&max=1&any=0";
$xml = simplexml_load_file("$url");
$ril = $xml->children();
echo "Latitude: ";
echo $lat = $ril->named->attributes()->lat, "<br>\n";
echo "Longitude: ";
echo $long = $ril->named->attributes()->lon, "";
echo "<br>\n for "." ".$source;
// for desti
$desti = $_GET["desti"];
$url_query1 = str_ireplace(" ","%20","$desti");
$url_nf1 = "http://gazetteer.openstreetmap.org/namefinder/search.xml?find=";
$url1 = $url_nf1."".$url_query1.""."&max=1&any=0";
$xml1 = simplexml_load_file("$url1");
$ril1 = $xml1->children();
echo "Latitude: ";
echo $lat1 = $ril1->named->attributes()->lat, "<br>\n";
echo "Longitude: ";
echo $long1 = $ril1->named->attributes()->lon, "";
echo "<br>\n for "." ".$source;

$string_data = $lat;
$string_data1 = $long;
$string_data2 = $lat1;
$string_data3 = $long1;

// for example,you can also try for http://gazetteer.openstreetmap.org/namefinder/?find=Green+Street,+london
//or http://gazetteer.openstreetmap.org/namefinder/?find=oxford+street,london
?>
<center>
<table border="0.5">
<tr>
<td>
SOURCE: <div id="a"><?php echo $source; ?></div>
Lat-
<div id="latlong"><?php echo $string_data; ?></div>
Long-
<div id="latlong1"><?php echo $string_data1; ?></div>
</td>
<td>
DESTINATION: <div id="b"><?php echo $desti; ?></div>
Lat-
<div id="latlong2"><?php echo $string_data2; ?></div>
Long-
<div id="latlong3"><?php echo $string_data3; ?></div>
</td>
</tr>
</table>
</center>

<div id="cm-example" style="width: 500px; height: 370px; border: 1px solid #ccc; float: left"></div> 
	<div id="panel" style="float: left; padding-left: 10px"></div> 
	
	<script type="text/javascript" src="http://tile.cloudmade.com/wml/0.2/web-maps-lite.js"></script> 
	<script type="text/javascript"> 
	    var la = document.getElementById("latlong");
		var la1 = document.getElementById("latlong1");
		var la2 = document.getElementById("latlong2");
		var la3 = document.getElementById("latlong3");
		var sourcelat = la.innerHTML;
		var sourcelong = la1.innerHTML;
		var destilat = la2.innerHTML;
		var destilong = la3.innerHTML;
		var solat = sourcelat.substring(0,sourcelat.indexOf(".")+3);
		var solong = sourcelong.substring(0,sourcelong.indexOf(".")+3);
		var delat = destilat.substring(0,destilat.indexOf(".")+3);
		var delong = destilong.substring(0,destilong.indexOf(".")+3);
		//document.write(solat);
		
		var map = new CM.Map('cm-example', new CM.Tiles.CloudMade.Web({key: '1c5b4d1eb3a159f0a14d8e1e3eb5d857'}));
		var lamid = (solat+delat)/2;
		var lomid = (solong+delong)/2;
		map.setCenter(new CM.LatLng(lamid,lomid), 15);
		
		var directions = new CM.Directions(map, 'panel', '1c5b4d1eb3a159f0a14d8e1e3eb5d857');
 
		var waypoints = [new CM.LatLng(solat,solong), new CM.LatLng(delat,delong)];
		directions.loadFromWaypoints(waypoints);

	</script> 




</body>
</html>