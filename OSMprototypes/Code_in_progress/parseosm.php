<html>
<body>

<?php 

$left = -0.0900;
$right = -0.0850;
$top = 51.505;
$down = 51.502;
$query = $left.",".$down.",".$right.",".$top; 
$basicq = "http://api.openstreetmap.org/api/0.6/map?bbox=";
$finalq = "$basicq"."$query";
$long_diff = $right-$left;
$lat_diff = $top-$down;
$area = $long_diff*$lat_diff;
echo "<br/>";
$xml = simplexml_load_file("$finalq");
$ril = $xml->children();
// Assuming image is of size 1000 x 500
$long_para = 1000/$long_diff;
$lat_para = 600/$lat_diff;
$number = 0;
foreach ($ril->node as $ri) 
{
if($ri->tag["k"] == "name" && $ri["lat"]>$down && $ri["lat"]<$top && $ri["lon"]<$right && $ri["lon"]>$left)
 {
//for Lat/Y 
$lat[$number] = $ri["lat"];
$y_temp = "$lat[$number]"-$down;
$y[$number] = (600 - ($lat_para*$y_temp));


// for Long/X
$long[$number] = $ri["lon"];
$x_temp = "$long[$number]"-($left);
$x[$number] = $long_para*$x_temp;


// for place/name of location
$name[$number] = $ri->tag["v"];
echo $name[$number];
echo "<br/>";
echo " Lat ";
echo $lat[$number];
echo "<br/>";
echo " Long ";
echo $long[$number];
echo "<br/>";
echo " X ";
echo $x[$number];
echo "<br/>";
echo " Y ";
echo $y[$number];
echo "<br/>";
echo "<br/>";
$number++; 
 }

}
//echo $number;
?>
</body>
</html>