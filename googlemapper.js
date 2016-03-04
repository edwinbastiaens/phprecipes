 /* initialize the googlemap */
var mygeomap = null;
function schietMarkerIn(lat,lon,name){
    // Creating a marker and positioning it on the map
  var marker = new google.maps.Marker({    
    position: new google.maps.LatLng(lat,lon),    
    map: mygeomap,
    icon: 'map_point.png',
    title:name,
    imgsrc:'',
    url:'',
  });  

}

jQuery( function(){
    var mc = document.getElementById("map-canvas");
    var mcj = jQuery("#map-canvas");
    var lon = mcj.attr('lon');
    var lat = mcj.attr('lat');
    var ttl = mcj.attr('title');
    var latlng = new google.maps.LatLng(lat, lon);  
    var myOptions = {  
        zoom: 9,  
        center: latlng,  
        mapTypeId: google.maps.MapTypeId.ROADMAP  
    };
    mygeomap = new google.maps.Map(mc, myOptions);
    schietMarkerIn(lat, lon, ttl);
});