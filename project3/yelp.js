 
var latLng = [];
var maps;
var markers = [];

function initializeMaps(){
  // show Google Map with center as 32.75,-97.13 and zoom as 16.
    maps = new google.maps.Map(document.getElementById('map'), {
    zoom: 16,
    center: {lat: 32.75, lng: -97.13}          
  });
  // Call Yelp API everytime Boundary changes.
  google.maps.event.addListener(maps,'idle', function(){
      sendRequest();
  });
}

function sendRequest () {
   var xhr = new XMLHttpRequest();
   var searchTerm = document.getElementById('search').value;
   var center = maps.getCenter();         //center of the map
   var bounds = maps.getBounds();         //corners of the map
   var ne = bounds.getNorthEast(); 
   // Raidus will be the distance from center to the NorthEast Corner
   var radius =  parseInt(google.maps.geometry.spherical.computeDistanceBetween(center, ne));

   var query =  "proxy.php?term="+searchTerm
                +"&latitude="+center.lat()
                +"&longitude="+center.lng()
                +"&radius="+radius 
                +"&limit=10"
                +"&sort_by=best_match";
   if(searchTerm.length>0){
   // Remove old markers if present 
    removeMarkers();

   xhr.open("GET",query);
   xhr.setRequestHeader("Accept","application/json");

   //Handle the JSON Response
   xhr.onreadystatechange = function () {
       if (this.readyState == 4) {
          var json = JSON.parse(this.responseText);
          var businessesJSON = json.businesses;     //Get all relevant businesses in the searched area
          var businessesList = "";
         businessesList = "<ol>";
          for(var i = 0; i<businessesJSON.length;i++){
            var obj = new google.maps.LatLng( businessesJSON[i].coordinates.latitude,
                                              businessesJSON[i].coordinates.longitude);
            
            // Show makers only if they are in the visible area of the map
            if(maps.getBounds().contains(obj)) {         

            businessesList += "<li><img src=" 
                            + "\""
                            + businessesJSON[i].image_url
                            + "\"</img>";
            businessesList += "<a href =\""+ businessesJSON[i].url+"\" target = \"_blank\">"+
                            businessesJSON[i].name+"</a>";
            businessesList += "<span>&nbsp&nbsp&nbsp&nbspRating: " + businessesJSON[i].rating + "</span></li>";
            // get Latitude and Longitude of all the businesses to place markers on them
            latLng.push(obj);
            }
          }
          businessesList+= "</ol>";
          document.getElementById('output').innerHTML = businessesList;

          // plot Markers 
          mapPlot();
       }
   };
   xhr.send(null);
 }
}

/*Invalidating markers */
function removeMarkers(){
  for (var i=0;i<markers.length;i++){
    // set all markers' map to null;
    markers[i].setMap(null);
  }
  markers = [];
  markers.length = 0;
  latLng = [];
  latLng.length = 0;
}

/*Plot businesses on the map*/
function mapPlot(){
  var bounds = maps.getBounds();
    for(var i =0; i< latLng.length;i++){
       var marker = new google.maps.Marker({
        position: {lat:latLng[i].lat(),lng:latLng[i].lng()},
        animation: google.maps.Animation.DROP,
        map:maps
        });
       //keep track of markers to invalidate them on a new request
       markers.push(marker);
  }
}