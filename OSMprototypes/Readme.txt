This directory contains 13 folders,each containing a prototype:

Copy any of these folders and paste the same in the htdocs folder where Apache is installed for PHP.Opening web browser, and accessing through localhost will run the prototype.

Explanation of folders/prototypes:

1-[[cmcm]]: Routing info in text app, Geocoding engine is CloudMade and Routing Engine is CloudMade.Implemented by parsing JSON response through PHP.

2-[[nmcm]]: Routing info in text app, Geocoding engine is Namefinder and Routing Engine is CloudMade Web Maps Lite API.Used hacks to extract text of output from API's response.

3-[[orsors]]: Routing info in text app, Geocoding engine is OpenRouteService.org(ORS) and Routing Engine is ORS again.Implemented by parsing XML response through PHP.

4-[[yahooors]]: Routing info in text app, Geocoding engine is Yahoo! Geo data and Routing Engine is ORS again.Implemented by parsing XML response through PHP.

5-[[Audible with ORS]]: This is the prototye, used to create dynamic talking maps. The parseom_points.php file shows points on static map too! Geocoding and Directory/POI API by OpenRouteService.org . Talking ability by Outfox plug-in for Firefox by UNC, USA ( Open Sour ce).

6-[[ORS_POI_SourceDesti]]: Prototype to find POIs along route from Source to Destination. 

7-[[ORS_Python]]: Translation of orsors from PHP to Python, which can further be used to run on Google Apps Engine. 

8-[[ORS_regex]]: A prototype to implement regex features on orsors, such that user can enter query as text/lat,long hence, increasing accuracy.

9-[[ORS_StaticMap]]: The prototype is foundation to produce static maps, on search query, not only helping partially visually impaired/ general user but also helping develop talking maps.

10-[[ORS_StaticMap_Gosmore]]: Just like ORS_StaticMap, only difference being the Routing info generated through Gosmore Routing Engine, rather than ORS. 

11-[[orsgae]]: The ORS_Python's implementation to make it running on Google Apps Engine. 

12-[[YOURS_Prototype]]: The implementation of Routing info in TEXT, using YOURS API.

13-[[Code_in_progress]]: These are incomplete set of code, which might not run directly. But they were eventually used in Talking maps module. Google Gears Geolocation API is also tested here. Distance.php is implementation of distance formula, to find close POIs along the routing path.. 



 








