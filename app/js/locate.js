function beginSearch() {
  $('#preSearch').hide();
  $('#searchArea').removeClass('hidden');
  //if (navigator.geolocation) { //navigator.
  if (geoPosition.init()) {
    geoPosition.getCurrentPosition(success, errorHandler, {timeout:3000});
  } else {
    error('Sorry, we are not able to use browser geolocation to find you.');
  }  
}

function success(position) {
  $('#actionBar').removeClass('hidden');
  $('#autolocateAlert').addClass('hidden');
  var s = document.querySelector('#status');
  //var buttons = document.querySelector('#locate_actions');
  if (s.className == 'success') {
    // not sure why we're hitting this twice in FF, I think it's to do with a cached result coming back    
    return;
  }
  
  s.innerHTML = "You are here:";
  s.className = 'success';
  
  var mapcanvas = document.createElement('div');
  mapcanvas.id = 'mapcanvas';
  mapcanvas.style.height = '300px';
  mapcanvas.style.width = '300px';
  mapcanvas.style.border = '1px solid black';
    
  document.querySelector('article').appendChild(mapcanvas);  
  
  var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
  var myOptions = {
    zoom: 16,
    center: latlng,
    mapTypeControl: false,
    navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById("mapcanvas"), myOptions);
  
  var marker = new google.maps.Marker({
      position: latlng, 
      map: map, 
      title:"You are here! (at least within a "+position.coords.accuracy+" meter radius)"
  });
  $('#locate_actionbar').removeClass('hidden');
  $('#Geolocation_lat').val(position.coords.latitude);
  $('#Geolocation_lon').val(position.coords.longitude);
}

function errorHandler(err) {
  var s = document.querySelector('#status');
  s.innerHTML = typeof msg == 'string' ? msg : "failed";
  s.className = 'fail';  
  //if (err.code == 1) {} // user said no! 
  document.location.href='/mapapp/app/geolocation/error';
}

