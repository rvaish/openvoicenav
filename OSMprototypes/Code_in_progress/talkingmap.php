<html>
<head>
<title>Outfox Audio Examples</title>
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
 <center<h4>Audible Maps for Firefox</h4></center>
 <div id="status"></div>
 <div id="box"></div>

<?php 

$left = -0.0900;
$right = -0.0850;
$top = 51.505;
$down = 51.502;
$query = $left.",".$down.",".$right.",".$top; 
$basicq = "http://api.openstreetmap.org/api/0.6/map?bbox=";
$finalq = "$basicq"."$query";
$image_url1 = "http://dev.openstreetmap.org/~pafciu17/?module=map&bbox=";
$image_url2 = $left.",".$top.",".$right.",".$down;
$image_url3 = "&width=900&height=500";
$image_url = "$image_url1"."$image_url2"."$image_url3";

$long_diff = $right-$left;
$lat_diff = $top-$down;
$area = $long_diff*$lat_diff;
echo "<br/>";
$xml = simplexml_load_file("$finalq");
$ril = $xml->children();
// Assuming image is of size 1000 x 500
$long_para = 900/$long_diff;
$lat_para = 500/$lat_diff;
$number = 0;
 
$html_str1 = '<img src='.$image_url.'alt="Planets" usemap="#planetmap" />'.'<map name="planetmap">';  
//echo "$html_str";
$html_str2 = " ";
foreach ($ril->node as $ri) 
{
if($ri->tag["k"] == "name" && $ri["lat"]>$down && $ri["lat"]<$top && $ri["lon"]<$right && $ri["lon"]>$left)
 {

//for Lat/Y 
$lat[$number] = $ri["lat"];
$y_temp = "$lat[$number]"-$down;
$y[$number] = (500 - ($lat_para*$y_temp));


// for Long/X
$long[$number] = $ri["lon"];
$x_temp = "$long[$number]"-($left);
$x[$number] = $long_para*$x_temp;


// for place/name of location
$name[$number] = $ri->tag["v"];
echo "<center>";
echo $name[$number];
echo "&nbsp;&nbsp;&nbsp;";
echo " Lat ";
echo $lat[$number];
echo "&nbsp;&nbsp;&nbsp;";
echo " Long ";
echo $long[$number];
echo "&nbsp;&nbsp;&nbsp;";
echo " X ";
echo $x[$number];
echo "&nbsp;&nbsp;&nbsp;";
echo " Y ";
echo $y[$number];
echo "</center>";
echo "<br/>";




// Audible mapping
$html_str2_temp = '<area shape="circle" coords="'.$x[$number].",".$y[$number].",".'20" alt="'.$name[$number].'" id="'.$number.'" onmouseover="singleSpeech(this,id)" />'.'<br/>';
$html_str2 = $html_str2.$html_str2_temp;

$number++; 
 } // end of if

} // end of loop
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