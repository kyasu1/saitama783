/*
 * https://gist.github.com/hndkn/6153303
 */

(function() {
  var dom = document.getElementById('map');

  var center = {
    lat: parseFloat(dom.getAttribute('data-lat')),
    lng: parseFloat(dom.getAttribute('data-lng')),
  };
  var options = {
    center: center,
    zoom: 14,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    scrollwheel: true,
  };

  var map = new google.maps.Map(dom, options);

  var marker = new google.maps.Marker({
    position: center,
    map: map,
  });
})();
