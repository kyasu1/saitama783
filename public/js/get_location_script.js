(function() {
  var el = document.getElementById('get-location-button');

  if (!el) return;

  if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(function(position) {
    });
  } else {
  }
})();

