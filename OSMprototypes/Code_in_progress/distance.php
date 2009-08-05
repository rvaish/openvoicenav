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
/*
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
*/
$number++; 
$abc = "Old King's Head";

 } // END OF IF

} // END OF FOR
echo "<br/>";
$o = 1; // for Guy's Hosp
$g = 9; // for Old King's 
$km_conv = 0.321/613.422; // On the basis of some calculations 
for ($num=0;$num<$number;$num++)
 {
  if ("$num"!="$o" && "$num"!="$g")
    { 
     $dist_n[$num] = ((($y[$o]-$y[$g])*$x[$num]) + (($x[$g]-$x[$o])*$y[$num]) + (($x[$o]*$y[$g])-($x[$g]*$y[$o])));
	 $dist_d[$num] = sqrt((($x[$g]-$x[$o])*($x[$g]-$x[$o])) + (($y[$g]-$y[$o])*($y[$g]-$y[$o])));
	 $dist[$num] = abs($dist_n[$num]/$dist_d[$num]);
	 if ($dist[$num] < 100)
	  {
	  echo "NEAR: ";
	  echo " $name[$num] "." is ".$dist[$num]*$km_conv." Kilometers away from Old King's Head and Guy's Hospital ";
	  echo "<br/>";
	  }
	 elseif ($dist[$num] > 100)
	  {
	  echo "FAR: ";
	  echo " $name[$num] "." is ".$dist[$num]*$km_conv." Kilometers away from Old King's Head and Guy's Hospital ";
	  echo "<br/>";
	  }
	}
 
 }
 
//echo $number;
?>
</body>
</html>