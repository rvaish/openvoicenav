This directory contains 4 folders,each containing a prototype:

Copy any of these folders and paste the same in the htdocs folder where Apache is installed for PHP.Opening web browser, and accessing through localhost will run the prototype.

Explanation of folders/prototypes:

cmcm: Geocoding engine is CloudMade and Routing Engine is CloudMade.Implemented by parsing JSON response through PHP.
nmcm: Geocoding engine is Namefinder and Routing Engine is CloudMade Web Maps Lite API.Used hacks to extract text of output from API's response.
orsors: Geocoding engine is OpenRouteService.org and Routing Engine is ORS again.Implemented by parsing XML response through PHP.
yahooors: Geocoding engine is Yahoo! Geo data and Routing Engine is ORS again.Implemented by parsing XML response through PHP.

Please note: This is a very early stage prototype and needs lots of polishing and error handling capabilities.Soon, all the options will be available through one interface.Thanks.
